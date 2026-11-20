<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Service;

final class FrontCompareController extends Controller
{
    private const MAX = 3;

    /** Side-by-side comparison of up to 3 sanatoriums: /sravnenie?ids=slug1,slug2,slug3 (old links ?s1=&s2=&s3= still work) */
    public function index(Request $request): View
    {
        $slugs = self::slugs($request, self::MAX);

        $found = $slugs === [] ? collect() : Service::where('status', true)
            ->whereIn('slug', $slugs)
            ->with(['thumbnail', 'translation', 'activeRooms', 'destination:id,name,country', 'diseases'])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->get()
            ->keyBy('slug');

        // keep the order of the URL
        $services = collect($slugs)->map(fn ($s) => $found->get($s))->filter()->values();

        $rows = $services->map(fn (Service $s) => $this->presentRow($s));

        return view('tourbooking::front.compare', ['rows' => $rows]);
    }

    /** Favorites: /izbrannoe?ids=slug1,slug2 (the list itself lives in the visitor's browser). */
    public function favorites(Request $request): View
    {
        $slugs = self::slugs($request, 50);
        $found = $slugs === [] ? collect() : Service::where('status', true)
            ->whereIn('slug', $slugs)
            ->with(['thumbnail', 'serviceType', 'translation', 'destination:id,name,country'])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->get()
            ->keyBy('slug');
        $services = collect($slugs)->map(fn ($s) => $found->get($s))->filter()->values();

        return view('tourbooking::front.favorites', ['services' => $services]);
    }

    /** @return list<string> */
    private static function slugs(Request $request, int $max): array
    {
        $raw = [];
        $ids = $request->query('ids');
        if (is_string($ids) && $ids !== '') {
            $raw = explode(',', $ids);
        } else {
            foreach (range(1, self::MAX) as $i) {
                $raw[] = (string) $request->query('s' . $i, '');
            }
        }
        $slugs = [];
        foreach ($raw as $slug) {
            $slug = strtolower(trim((string) $slug));
            if ($slug !== '' && preg_match('/^[a-z0-9-]+$/', $slug) && ! in_array($slug, $slugs, true)) {
                $slugs[] = $slug;
            }
            if (count($slugs) >= $max) {
                break;
            }
        }

        return $slugs;
    }

    private function presentRow(Service $s): array
    {
        $prices = $s->activeRooms
            ->map(fn ($r) => (float) ($r->discount_price ?: $r->price_per_night))
            ->filter(fn ($p) => $p > 0);
        if ($prices->isEmpty()) {
            $base = $s->is_per_person
                ? (float) $s->price_per_person
                : (float) ($s->discount_price ?: $s->full_price);
            $prices = $base > 0 ? collect([$base]) : collect();
        }
        $min = $prices->min();
        $max = $prices->max();
        $priceText = $prices->isEmpty() ? null
            : ($min == $max ? currency($min) : currency($min) . ' – ' . currency($max));

        $guess = function (?bool $explicit, string $regex) use ($s): ?bool {
            if ($explicit !== null) {
                return $explicit;
            }
            $included = is_array($s->included) ? implode(' ', array_map('strval', $s->included)) : '';
            return $included !== '' && preg_match($regex, mb_strtolower($included)) ? true : null;
        };

        $child = [];
        if ($s->child_min_age !== null) {
            $child[] = 'от ' . $s->child_min_age . ' лет';
        }
        if (!empty($s->child_discount)) {
            $child[] = 'скидка ' . $s->child_discount;
        }

        $fromPrice = \Modules\TourBooking\App\Services\FromPrice::for($s);
        $pools = [];
        if ($s->pool_indoor) {
            $pools[] = 'крытый';
        }
        if ($s->pool_outdoor) {
            $pools[] = 'открытый';
        }
        $poolText = $pools ? implode(' + ', $pools)
            : (($s->pool_indoor === false && $s->pool_outdoor === false) ? false : null);
        $hasRooms = $s->activeRooms->isNotEmpty();

        return [
            'slug' => $s->slug,
            'title' => gs_display_title($s->translation?->title ?: $s->title),
            'from_price' => $fromPrice,
            'from_basis' => \Modules\TourBooking\App\Services\FromPrice::label($s),
            'stars' => (int) ($s->stars ?? 0),
            'city' => gscardAddress($s) ?: null,
            'diseases' => $s->diseases->pluck('name')->filter()->values()->all(),
            'meal_plan' => trim((string) ($s->meal_plan ?? '')) ?: null,
            'pools' => $poolText,
            'spa' => $s->has_spa,
            'free_transfer' => $s->free_transfer,
            'transfer_info' => trim((string) ($s->transfer_info ?? '')) ?: null,
            'min_nights' => (int) ($s->min_stay_nights ?? 0) ?: null,
            'book_url' => $hasRooms
                ? route('front.tourbooking.rooms.selection', $s->slug)
                : route('front.tourbooking.services.show', ['slug' => $s->slug]),
            'photo' => $s->thumbnail?->file_path ? asset($s->thumbnail->file_path) : null,
            'url' => route('front.tourbooking.services.show', ['slug' => $s->slug]),
            'price' => $priceText,
            'rating' => (int) $s->active_reviews_count > 0 ? round((float) $s->active_reviews_avg_rating, 1) : null,
            'reviews' => (int) $s->active_reviews_count,
            'procedures' => $s->procedures_per_day,
            'meals' => $guess($s->meals_included, '/питани|meal|board/u'),
            'tests' => $guess($s->tests_included, '/анализ|test/u'),
            'doctor' => $guess($s->doctor_exam_included, '/осмотр|врач|doctor/u'),
            'airport' => $s->airport_distance_km !== null ? rtrim(rtrim(number_format((float) $s->airport_distance_km, 1, '.', ''), '0'), '.') : null,
            'child' => $child ? implode(', ', $child) : null,
        ];
    }
}
