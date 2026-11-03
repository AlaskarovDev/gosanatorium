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

    /** Side-by-side comparison of up to 3 sanatoriums: /sravnenie?s1=slug&s2=slug&s3=slug */
    public function index(Request $request): View
    {
        $slugs = [];
        foreach (range(1, self::MAX) as $i) {
            $slug = strtolower(trim((string) $request->query('s' . $i, '')));
            if ($slug !== '' && preg_match('/^[a-z0-9-]+$/', $slug) && !in_array($slug, $slugs, true)) {
                $slugs[] = $slug;
            }
        }

        $found = $slugs === [] ? collect() : Service::where('status', true)
            ->whereIn('slug', $slugs)
            ->with(['thumbnail', 'translation', 'activeRooms'])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->get()
            ->keyBy('slug');

        // keep the order of the URL
        $services = collect($slugs)->map(fn ($s) => $found->get($s))->filter()->values();

        $rows = $services->map(fn (Service $s) => $this->presentRow($s));

        return view('tourbooking::front.compare', ['rows' => $rows]);
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

        return [
            'slug' => $s->slug,
            'title' => $s->translation?->title ?: $s->title,
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
