<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\GlobalSetting\App\Models\GlobalSetting;

/**
 * One source of truth for company facts and policies (Task 9).
 *
 * Typed values live in `global_settings` (keys `company_*`) and are edited in
 * Admin → Website Setup → Company facts. Counts (partners, sanatoriums per country,
 * reviews) are calculated from the database; years of experience = current year − founded_year.
 *
 * Usage in Blade: {{ company_fact('working_hours_text') }}, company_facts()->experienceYears().
 */
class CompanyFacts
{
    /** Typed fields: key => [label for admin, default, type]. */
    public const FIELDS = [
        'founded_year'        => ['Год основания', '2018', 'number'],
        'clients_count'       => ['Количество клиентов (как показывать, напр. «28765+»)', '28765+', 'text'],
        'working_hours'       => ['Часы работы (коротко, напр. «24/7» или «ежедневно 9:00–21:00»)', '24/7', 'text'],
        'phone_main'          => ['Телефон (основной, Баку)', '+994 51 915 88 94', 'text'],
        'phone_second'        => ['Телефон (второй, Тбилиси)', '+995 568 10 27 44', 'text'],
        'email'               => ['E-mail', 'info@gosanatorium.com', 'text'],
        'whatsapp'            => ['WhatsApp (номер, только цифры)', '994519158894', 'text'],
        'telegram'            => ['Telegram (имя без @)', 'gosanatorium', 'text'],
        'cancellation_short'  => ['Отмена — коротко (для карточек и бейджей)', 'бесплатная отмена за 7–14 дней до заезда', 'text'],
        'cancellation_full'   => ['Отмена — полный текст', 'Стандартный срок отмены без штрафа — за 7–14 дней до даты заезда. Для горящих предложений срок может быть короче — 3–4 дня. Если вы не приехали и не предупредили, санаторий вправе удержать стоимость одних суток.', 'textarea'],
        'prepayment_policy'   => ['Предоплата', 'Без предоплаты — оплата при заезде', 'text'],
        'payment_methods'     => ['Способы оплаты', 'Наличными или картой (Visa, Mastercard) в санатории', 'text'],
        'legal_name'          => ['Юридическое название', 'GoSanatorium MMC', 'text'],
        'voen'                => ['VÖEN', '', 'text'],
        'office_address'      => ['Адрес офиса', 'Баку, Азербайджан', 'text'],
    ];

    public const PREFIX = 'company_';

    private const CACHE_KEY = 'company_facts_counts';

    private ?array $counts = null;

    public static function instance(): self
    {
        return app(self::class);
    }

    /** Typed value from admin settings (falls back to the default). */
    public function get(string $key): string
    {
        $computed = $this->computed($key);
        if ($computed !== null) {
            return $computed;
        }

        $setting = Cache::get('setting');
        $value = $setting->{self::PREFIX . $key} ?? null;
        if ($value === null || trim((string) $value) === '') {
            $value = self::FIELDS[$key][1] ?? '';
        }

        return trim((string) $value);
    }

    private function computed(string $key): ?string
    {
        return match ($key) {
            'experience_years'      => (string) $this->experienceYears(),
            'experience_text'       => $this->experienceText(),
            'working_hours_text'    => 'Работаем ' . $this->get('working_hours'),
            'partners_count'        => (string) $this->partnersCount(),
            'reviews_count'         => (string) $this->reviewsCount(),
            'phone_main_tel'        => '+' . preg_replace('/\D/', '', $this->get('phone_main')),
            'phone_second_tel'      => '+' . preg_replace('/\D/', '', $this->get('phone_second')),
            'whatsapp_url'          => 'https://wa.me/' . preg_replace('/\D/', '', $this->get('whatsapp')),
            'telegram_url'          => 'https://t.me/' . ltrim($this->get('telegram'), '@'),
            default                 => null,
        };
    }

    public function foundedYear(): int
    {
        $year = (int) $this->get('founded_year');

        return $year > 1900 ? $year : (int) self::FIELDS['founded_year'][1];
    }

    public function experienceYears(): int
    {
        return max(1, (int) now()->year - $this->foundedYear());
    }

    /** «8 лет», «21 год», «3 года». */
    public function experienceText(): string
    {
        return self::plural($this->experienceYears(), 'год', 'года', 'лет');
    }

    public function partnersCount(): int
    {
        return $this->counts()['partners'];
    }

    public function reviewsCount(): int
    {
        return $this->counts()['reviews'];
    }

    /** Active sanatoriums per country, e.g. ['Азербайджан' => 17, 'Грузия' => 6]. */
    public function sanatoriumsByCountry(): array
    {
        return $this->counts()['countries'];
    }

    public function countryCount(string $country): int
    {
        return (int) ($this->sanatoriumsByCountry()[$country] ?? 0);
    }

    public static function plural(int $n, string $one, string $few, string $many): string
    {
        $mod10 = $n % 10;
        $mod100 = $n % 100;
        $word = ($mod10 === 1 && $mod100 !== 11) ? $one
            : (($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14)) ? $few : $many);

        return $n . ' ' . $word;
    }

    /** Forget cached counts (called when admin saves facts). */
    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
        app()->forgetInstance(self::class);
    }

    private function counts(): array
    {
        if ($this->counts !== null) {
            return $this->counts;
        }

        try {
            $this->counts = Cache::remember(self::CACHE_KEY, now()->addMinutes(30), function () {
                $services = DB::table('services')
                    ->leftJoin('destinations', 'destinations.id', '=', 'services.destination_id')
                    ->where('services.status', 1)
                    ->select('services.id', 'destinations.country', 'destinations.name')
                    ->get();

                $countries = [];
                foreach ($services as $row) {
                    $country = self::countryName((string) ($row->country ?: $row->name));
                    if ($country !== '') {
                        $countries[$country] = ($countries[$country] ?? 0) + 1;
                    }
                }

                $reviews = DB::table('reviews')
                    ->join('services', 'services.id', '=', 'reviews.service_id')
                    ->where('reviews.status', 1)
                    ->where('services.status', 1)
                    ->count();

                return [
                    'partners'  => $services->count(),
                    'reviews'   => $reviews,
                    'countries' => $countries,
                ];
            });
        } catch (\Throwable $e) {
            Log::warning('CompanyFacts counts: ' . $e->getMessage());
            $this->counts = ['partners' => 0, 'reviews' => 0, 'countries' => []];
        }

        return $this->counts;
    }

    /** «Нафталан - Азербайджан» → «Азербайджан». */
    private static function countryName(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        $parts = preg_split('/\s*[-|–—,]\s*/u', $value);

        return trim((string) end($parts));
    }

    /**
     * Fill company facts into a text typed in the admin (SEO meta, page texts):
     * placeholders {working_hours}, {phone}, {email}, {experience}, {founded_year},
     * {clients}, {partners}, {reviews}, {cancellation}; old hard-coded phrases
     * («Работаем 24/7», «Поддержка 24/7») follow the working hours setting.
     */
    public function text(?string $value): string
    {
        $value = (string) $value;
        if ($value === '') {
            return '';
        }

        $hours = $this->get('working_hours');
        $value = strtr($value, [
            '{working_hours}' => $hours,
            '{phone}'         => $this->get('phone_main'),
            '{email}'         => $this->get('email'),
            '{experience}'    => $this->experienceText(),
            '{founded_year}'  => (string) $this->foundedYear(),
            '{clients}'       => $this->get('clients_count'),
            '{partners}'      => (string) $this->partnersCount(),
            '{reviews}'       => (string) $this->reviewsCount(),
            '{cancellation}'  => $this->get('cancellation_short'),
        ]);

        return preg_replace(
            ['/Работаем\s+24\/7/u', '/Поддержка\s+24\/7/u'],
            ['Работаем ' . $hours, 'Поддержка ' . $hours],
            $value
        );
    }

    /** Values for the admin form. */
    public static function adminValues(): array
    {
        $stored = GlobalSetting::where('key', 'like', self::PREFIX . '%')->pluck('value', 'key');
        $values = [];
        foreach (self::FIELDS as $key => [$label, $default]) {
            $values[$key] = $stored[self::PREFIX . $key] ?? $default;
        }

        return $values;
    }
}
