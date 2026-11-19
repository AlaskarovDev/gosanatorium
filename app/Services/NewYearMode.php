<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\App\Models\GlobalSetting;

/**
 * Seasonal New Year theme (top bar, garland, snow, promo badge).
 *
 * Settings live in `global_settings` (keys `ny_*`), edited in
 * Admin → Website Setup → New Year mode. Mode: auto (by dates, Asia/Baku, both days included) / on / off.
 * When the mode is not active nothing is printed: no CSS, no JS, no bar, no badge.
 */
class NewYearMode
{
    public const TZ = 'Asia/Baku';

    public const DEFAULTS = [
        'ny_mode'      => 'auto',
        'ny_start'     => '2026-11-19',
        'ny_end'       => '2027-01-16',
        'ny_link'      => '/ru/blog/novyy-god-v-sanatorii-2027-naftalan-sairme-tskhaltubo',
        'ny_akcii_ids' => '',
    ];

    private static ?bool $active = null;

    public static function get(string $key): string
    {
        $setting = Cache::get('setting');
        $value = $setting->{$key} ?? null;
        if ($value === null || trim((string) $value) === '') {
            return self::DEFAULTS[$key] ?? '';
        }

        return trim((string) $value);
    }

    public static function mode(): string
    {
        $mode = self::get('ny_mode');

        return in_array($mode, ['auto', 'on', 'off'], true) ? $mode : 'auto';
    }

    public static function start(): Carbon
    {
        return self::date('ny_start')->startOfDay();
    }

    public static function end(): Carbon
    {
        return self::date('ny_end')->endOfDay();
    }

    private static function date(string $key): Carbon
    {
        try {
            return Carbon::createFromFormat('Y-m-d', self::get($key), self::TZ);
        } catch (\Throwable $e) {
            return Carbon::createFromFormat('Y-m-d', self::DEFAULTS[$key], self::TZ);
        }
    }

    public static function isActive(): bool
    {
        if (self::$active !== null) {
            return self::$active;
        }

        $mode = self::mode();
        if ($mode !== 'auto') {
            return self::$active = ($mode === 'on');
        }

        $now = Carbon::now(self::TZ);

        return self::$active = $now->between(self::start(), self::end());
    }

    /** Top bar link (root-relative path or full URL). */
    public static function link(): string
    {
        $link = self::get('ny_link');

        // Root-relative path is printed as is (url() would add the locale prefix twice).
        return preg_match('#^https?://#i', $link) ? $link : '/' . ltrim($link, '/');
    }

    /** «43 дня», «31 день», «5 дней». */
    public static function days(int $n): string
    {
        return CompanyFacts::plural($n, 'день', 'дня', 'дней');
    }

    /** Bar text for today (Baku): countdown → «tonight» on 31 Dec → greeting in January/February. */
    public static function barText(?Carbon $today = null): string
    {
        $today = ($today ?? Carbon::now(self::TZ))->copy()->startOfDay();

        if ($today->month <= 2) {
            return '🎄 С Новым ' . $today->year . ' годом! Зимние цены в санаториях →';
        }
        if ($today->month === 12 && $today->day === 31) {
            return '🎄 Новый год уже сегодня ночью! Новогодние предложения в санаториях →';
        }
        $newYear = Carbon::create($today->year + 1, 1, 1, 0, 0, 0, self::TZ);
        $n = (int) $today->diffInDays($newYear);

        return '🎄 До Нового года ' . self::days($n) . '. Новогодние предложения в санаториях →';
    }

    /** Promotion IDs marked «New Year offer» in admin. */
    public static function akciiIds(): array
    {
        $ids = array_filter(array_map('intval', explode(',', self::get('ny_akcii_ids'))));

        return array_values(array_unique($ids));
    }

    public static function isNewYearAkcii($akcii): bool
    {
        return $akcii && !empty($akcii->id) && in_array((int) $akcii->id, self::akciiIds(), true);
    }

    /** Show the badge only while the mode is active. */
    public static function showBadge($akcii): bool
    {
        return self::isActive() && self::isNewYearAkcii($akcii);
    }

    public static function setAkcii(int $id, bool $on): void
    {
        $ids = self::akciiIds();
        $ids = $on ? array_merge($ids, [$id]) : array_diff($ids, [$id]);
        sort($ids);
        self::save(['ny_akcii_ids' => implode(',', array_unique($ids))]);
    }

    /** Save `ny_*` keys and refresh the settings cache. */
    public static function save(array $values): void
    {
        foreach ($values as $key => $value) {
            $setting = GlobalSetting::where('key', $key)->first() ?? new GlobalSetting();
            $setting->key = $key;
            $setting->value = (string) $value;
            $setting->save();
        }

        $all = [];
        foreach (GlobalSetting::get() as $row) {
            $all[$row->key] = $row->value;
        }
        Cache::put('setting', (object) $all);
        self::$active = null;
    }

    /** Asset URL with file-time version. */
    public static function asset(string $file): string
    {
        $path = 'frontend/assets/' . $file;

        return asset($path) . '?v=' . (@filemtime(public_path($path)) ?: '1');
    }
}
