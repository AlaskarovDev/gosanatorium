<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\TourBooking\App\Models\Destination;
use Modules\TourBooking\App\Models\Service;

/**
 * Task 4: server-rendered list of the sanatoriums of one destination (/hotels/{slug})
 * with sorting, filters, «from» price and SEO texts (title, meta, H1) with fallbacks.
 *
 * One destination has a few dozen sanatoriums at most, so everything is computed in PHP.
 */
final class DestinationListing
{
    public const PER_PAGE = 24;

    public const SORTS = [
        'popular' => 'Популярные',
        'price_asc' => 'Цена ↑',
        'price_desc' => 'Цена ↓',
        'rating' => 'Рейтинг',
    ];

    /** City name in the genitive case («Санатории Нафталана»). */
    private const GENITIVE = [
        'Нафталан' => 'Нафталана',
        'Ленкорань' => 'Ленкорани',
        'Лянкяран' => 'Лянкярана',
        'Баку' => 'Баку',
        'Боржоми' => 'Боржоми',
        'Цхалтубо' => 'Цхалтубо',
        'Саирме' => 'Саирме',
        'Сайрме' => 'Сайрме',
        'Габала' => 'Габалы',
        'Шеки' => 'Шеки',
        'Губа' => 'Губы',
        'Исмаиллы' => 'Исмаиллы',
        'Масаллы' => 'Масаллы',
    ];

    public function __construct(private readonly Destination $destination)
    {
    }

    /** «Нафталан - Азербайджан» → «Нафталан». */
    public function city(): string
    {
        // The «city» column holds theme leftovers (Paris, Rome …), so the name is the source.
        $name = (string) ($this->destination->name ?: $this->destination->city);
        $parts = preg_split('/\s*[-|–—,]\s*/u', trim($name));

        return trim((string) ($parts[0] ?? $name));
    }

    public function country(): string
    {
        $country = trim((string) $this->destination->country);
        if ($country !== '') {
            return $country;
        }
        $parts = preg_split('/\s*[-|–—,]\s*/u', trim((string) $this->destination->name));

        return trim((string) ($parts[1] ?? ''));
    }

    public function cityGenitive(): string
    {
        $city = $this->city();

        return self::GENITIVE[$city] ?? $city;
    }

    /** All active sanatoriums of the destination with rating data and the «from» price. */
    public function all(): Collection
    {
        $services = Service::where('status', true)
            ->where('destination_id', $this->destination->id)
            ->with(['thumbnail', 'serviceType', 'translation', 'diseases' => fn ($q) => $q->where('diseases.is_active', true)])
            ->withExists('myWishlist')
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->get();

        // Cheapest active room per sanatorium (room discount price if set), one query.
        $roomMin = \Modules\TourBooking\App\Models\ServiceRoom::query()
            ->whereIn('service_id', $services->pluck('id'))
            ->where('status', true)
            ->selectRaw('service_id, MIN(COALESCE(NULLIF(discount_price, 0), price_per_night)) as min_price')
            ->groupBy('service_id')
            ->pluck('min_price', 'service_id');

        foreach ($services as $service) {
            $room = $roomMin[$service->id] ?? null;
            $fallback = $service->is_per_person
                ? $service->price_per_person
                : ($service->discount_price ?: $service->full_price);
            $from = $room !== null && (float) $room > 0 ? (float) $room : (float) $fallback;
            $service->setAttribute('gs_from_price', $from > 0 ? $from : null);
        }

        return $services;
    }

    /**
     * Filters + sorting from the query string.
     *
     * @return array{items: LengthAwarePaginator, all: Collection, filters: array<string, mixed>, active: bool}
     */
    public function paginate(Request $request): array
    {
        $all = $this->all();

        $sort = (string) $request->query('sort', 'popular');
        if (!array_key_exists($sort, self::SORTS)) {
            $sort = 'popular';
        }
        $min = is_numeric($request->query('price_min')) ? (float) $request->query('price_min') : null;
        $max = is_numeric($request->query('price_max')) ? (float) $request->query('price_max') : null;
        $rating = is_numeric($request->query('rating')) ? (float) $request->query('rating') : null;
        $pool = $request->boolean('pool');
        $transfer = $request->boolean('transfer');
        $disease = trim((string) $request->query('disease', ''));

        $list = $all->filter(function (Service $s) use ($min, $max, $rating, $pool, $transfer, $disease) {
            $price = $s->gs_from_price;
            if ($min !== null && ($price === null || $price < $min)) {
                return false;
            }
            if ($max !== null && ($price === null || $price > $max)) {
                return false;
            }
            if ($rating !== null && (float) ($s->active_reviews_avg_rating ?? 0) < $rating) {
                return false;
            }
            if ($pool && !$this->hasFeature($s, ['бассейн', 'pool'])) {
                return false;
            }
            if ($transfer && !$this->hasFeature($s, ['трансфер', 'transfer'])) {
                return false;
            }
            if ($disease !== '' && !$s->diseases->contains('slug', $disease)) {
                return false;
            }

            return true;
        });

        $list = match ($sort) {
            'price_asc' => $list->sortBy(fn (Service $s) => [$s->gs_from_price === null ? 1 : 0, (float) $s->gs_from_price]),
            'price_desc' => $list->sortByDesc(fn (Service $s) => (float) $s->gs_from_price),
            'rating' => $list->sortByDesc(fn (Service $s) => [round((float) $s->active_reviews_avg_rating, 2), (int) $s->active_reviews_count]),
            default => $list->sortByDesc(fn (Service $s) => [
                (int) $s->is_popular + (int) $s->is_featured,
                (int) $s->active_reviews_count,
                round((float) $s->active_reviews_avg_rating, 2),
            ]),
        };
        $list = $list->values();

        $page = max(1, (int) $request->query('page', 1));
        $items = new LengthAwarePaginator(
            $list->forPage($page, self::PER_PAGE)->values(),
            $list->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $prices = $all->pluck('gs_from_price')->filter();

        return [
            'items' => $items,
            'all' => $all,
            'active' => $min !== null || $max !== null || $rating !== null || $pool || $transfer || $disease !== '',
            'filters' => [
                'sort' => $sort,
                'price_min' => $min,
                'price_max' => $max,
                'rating' => $rating,
                'pool' => $pool,
                'transfer' => $transfer,
                'disease' => $disease,
                'range_min' => $prices->isNotEmpty() ? (int) floor($prices->min()) : null,
                'range_max' => $prices->isNotEmpty() ? (int) ceil($prices->max()) : null,
                'diseases' => $all->flatMap(fn (Service $s) => $s->diseases)->unique('id')->sortBy('name')->values(),
                'has_pool' => $all->contains(fn (Service $s) => $this->hasFeature($s, ['бассейн', 'pool'])),
                'has_transfer' => $all->contains(fn (Service $s) => $this->hasFeature($s, ['трансфер', 'transfer'])),
            ],
        ];
    }

    /** Amenity names (stored as amenity translation ids) or badges contain one of the words. */
    private function hasFeature(Service $s, array $words): bool
    {
        $texts = array_map(fn ($b) => is_array($b) ? (string) ($b['text'] ?? '') : (string) $b, (array) ($s->badges ?? []));
        foreach ((array) ($s->amenities ?? []) as $id) {
            $texts[] = self::amenityNames()[(int) $id] ?? '';
        }
        foreach ($texts as $t) {
            $t = mb_strtolower($t);
            foreach ($words as $w) {
                if ($t !== '' && str_contains($t, $w)) {
                    return true;
                }
            }
        }

        return false;
    }

    /** @return array<int, string> amenity translation id => name (all languages) */
    private static function amenityNames(): array
    {
        static $names = null;
        if ($names === null) {
            $names = \Modules\TourBooking\App\Models\AmenityTranslation::query()->pluck('name', 'id')->all();
        }

        return $names;
    }

    /** @return array{title: string, description: string, h1: string} */
    public function seo(Collection $all): array
    {
        $d = $this->destination;
        $year = now('Asia/Baku')->year;
        $count = $all->count();
        $min = $all->pluck('gs_from_price')->filter()->min();

        $title = trim(strip_tags(html_entity_decode((string) $d->meta_title)));
        if ($title === '') {
            $title = "Санатории {$this->cityGenitive()}: цены {$year} с лечением | GoSanatorium";
        }

        $description = self::cleanMeta((string) $d->meta_description);
        if ($description === '') {
            $country = $this->country() !== '' ? " ({$this->country()})" : '';
            $description = "Санатории {$this->cityGenitive()}{$country} с лечением: {$count} "
                . self::plural($count, 'санаторий', 'санатория', 'санаториев')
                . ($min ? ', цены от $' . number_format((float) $min, 0, '.', ' ') . ' за ночь' : '')
                . '. Бронирование без предоплаты.';
            $description = self::cleanMeta($description);
        }

        $h1 = trim((string) ($d->h1 ?? ''));
        if ($h1 === '') {
            $h1 = "Санатории {$this->cityGenitive()}";
        }

        return ['title' => $title, 'description' => $description, 'h1' => $h1];
    }

    /** strip_tags + html_entity_decode, collapse spaces, cut at 160 chars on a word boundary. */
    public static function cleanMeta(string $text, int $limit = 160): string
    {
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = trim((string) preg_replace('/\s+/u', ' ', $text));
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        $cut = mb_substr($text, 0, $limit - 1);
        $space = mb_strrpos($cut, ' ');
        if ($space !== false && $space > $limit * 0.6) {
            $cut = mb_substr($cut, 0, $space);
        }

        return rtrim($cut, " ,.;:-") . '…';
    }

    public static function plural(int $n, string $one, string $few, string $many): string
    {
        $n = abs($n) % 100;
        $n1 = $n % 10;
        if ($n > 10 && $n < 20) {
            return $many;
        }
        if ($n1 > 1 && $n1 < 5) {
            return $few;
        }
        if ($n1 === 1) {
            return $one;
        }

        return $many;
    }

    public static function cardTags(Service $s): array
    {
        return array_slice(array_values(array_filter(array_map(
            fn ($d) => Str::limit((string) $d->name, 28),
            $s->diseases->all()
        ))), 0, 3);
    }
}
