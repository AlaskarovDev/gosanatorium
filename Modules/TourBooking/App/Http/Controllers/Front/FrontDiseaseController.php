<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Traits\UsesSeoMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\Disease;

final class FrontDiseaseController extends Controller
{
    use UsesSeoMeta;

    public function index(): View
    {
        $diseases = Disease::visible()->ordered()->get();

        $this->applySeo('diseases', [
            'title'       => 'Санатории по заболеваниям — подбор по диагнозу',
            'description' => 'Найдите санаторий в Нафталане или Грузии по вашему диагнозу. Лечение суставов, кожных заболеваний, опорно-двигательного аппарата.',
        ]);

        return view('tourbooking::front.diseases.index', compact('diseases'));
    }

    /** /napravleniya-lecheniya: all treatment directions with a short text. */
    public function directions(): View
    {
        $diseases = Disease::visible()->ordered()->get();

        $this->applySeo('treatment-directions', [
            'title'       => 'Направления лечения в санаториях Азербайджана и Грузии',
            'description' => 'Направления лечения: суставы, кожа, неврология, гинекология, урология, ЖКТ, диабет и другие. Выберите направление и подберите санаторий.',
        ]);

        return view('tourbooking::front.diseases.directions', compact('diseases'));
    }

    public function show(string $slug): View|RedirectResponse
    {
        if (isset(Disease::LEGACY_SLUGS[$slug])) {
            return redirect(url('/bolezni/' . Disease::LEGACY_SLUGS[$slug]), 301);
        }

        $disease = Disease::active()->where('slug', $slug)->firstOrFail();

        $services = $disease->sanatoriums()
            ->where('services.status', true)
            ->with(['thumbnail', 'serviceType', 'translation'])
            ->latest('services.created_at')
            ->paginate(200); // show every sanatorium that treats the disease

        return view('tourbooking::front.diseases.show', compact('disease', 'services'));
    }

    /**
     * All sanatoriums (/ru/sanatoriyi) — Task 28: filters, sorting, map, pagination.
     * Query parameters (all optional, kept in the URL):
     *  disease={slug} (old codes are 301-redirected), country, destination_id, budget_min / budget_max (USD, «от» price),
     *  stars (min), rating (4 | 4.5), pool=1, transfer=1 (free transfer), child_age (children's treatment from this age),
     *  nights=7|10|14|21|other, sort=popular|price_asc|price_desc|rating|new, view=map, page.
     */
    public function sanatoriums(Request $request): View|RedirectResponse
    {
        $slug = trim((string) $request->query('disease', ''));
        if ($slug === 'all') {
            $slug = '';
        }
        if (isset(Disease::LEGACY_SLUGS[$slug])) {
            $query = array_merge($request->query(), ['disease' => Disease::LEGACY_SLUGS[$slug]]);

            return redirect(url('/sanatoriyi') . '?' . http_build_query($query), 301);
        }
        $disease = $slug !== '' ? Disease::active()->where('slug', $slug)->first() : null;

        $num = fn (string $k) => is_numeric($request->query($k)) ? (float) $request->query($k) : null;
        $f = [
            'disease'        => $slug,
            'country'        => trim((string) $request->query('country', '')),
            'destination_id' => (int) $request->query('destination_id', 0),
            'budget_min'     => $num('budget_min'),
            'budget_max'     => $num('budget_max'),
            'stars'          => min(5, max(0, (int) $request->query('stars', 0))),
            'rating'         => in_array((string) $request->query('rating'), ['4', '4.5'], true) ? (float) $request->query('rating') : null,
            'pool'           => $request->query('pool') === '1',
            'transfer'       => $request->query('transfer') === '1',
            'child_age'      => is_numeric($request->query('child_age')) ? max(0, min(17, (int) $request->query('child_age'))) : null,
            'nights'         => in_array(strtolower(trim((string) $request->query('nights', ''))), ['7', '10', '14', '21', 'other'], true) ? strtolower(trim((string) $request->query('nights'))) : '',
        ];
        $sort = (string) $request->query('sort', 'popular');
        $sorts = ['popular' => 'Популярные', 'price_asc' => 'Цена ↑', 'price_desc' => 'Цена ↓', 'rating' => 'Рейтинг', 'new' => 'Новые'];
        if (! isset($sorts[$sort])) {
            $sort = 'popular';
        }
        $view = $request->query('view') === 'map' ? 'map' : 'list';

        $filtersActive = $f['disease'] !== '' || $f['country'] !== '' || $f['destination_id'] > 0 || $f['budget_min'] !== null
            || $f['budget_max'] !== null || $f['stars'] > 0 || $f['rating'] !== null || $f['pool'] || $f['transfer']
            || $f['child_age'] !== null || $f['nights'] !== '';

        $all = Service::where('status', true)
            ->with(['thumbnail', 'serviceType', 'translation', 'destination:id,name,country', 'diseases'])
            ->withExists('myWishlist')
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->get();

        // Filter options come from the real data
        $prices = $all->map(fn (Service $s) => \Modules\TourBooking\App\Services\FromPrice::for($s))->filter(fn ($p) => $p !== null && $p > 0);
        $options = [
            'countries'    => $all->map(fn ($s) => trim((string) ($s->destination?->country ?? '')))->filter()->unique()->sort()->values(),
            'destinations' => $all->map(fn ($s) => $s->destination)->filter()->unique('id')->sortBy('name')
                ->filter(fn ($d) => $f['country'] === '' || trim((string) $d->country) === $f['country'])->values(),
            'diseases'     => Disease::visible()->ordered()->get(),
            'price_min'    => $prices->isEmpty() ? 0 : (int) floor($prices->min()),
            'price_max'    => $prices->isEmpty() ? 0 : (int) ceil($prices->max()),
            'sorts'        => $sorts,
            'has_stars'    => $all->contains(fn ($s) => (int) $s->stars > 0),
        ];

        $list = $all->filter(function (Service $s) use ($f, $disease) {
            if ($f['disease'] !== '' && (! $disease || ! $s->diseases->contains('id', $disease->id))) {
                return false;
            }
            if ($f['country'] !== '' && trim((string) ($s->destination?->country ?? '')) !== $f['country']) {
                return false;
            }
            if ($f['destination_id'] > 0 && (int) $s->destination_id !== $f['destination_id']) {
                return false;
            }
            if ($f['budget_min'] !== null || $f['budget_max'] !== null) {
                $price = \Modules\TourBooking\App\Services\FromPrice::for($s);
                if ($price === null || $price <= 0) {
                    return false;
                }
                if ($f['budget_min'] !== null && $price < $f['budget_min']) {
                    return false;
                }
                if ($f['budget_max'] !== null && $f['budget_max'] > 0 && $price > $f['budget_max']) {
                    return false;
                }
            }
            if ($f['stars'] > 0 && (int) $s->stars < $f['stars']) {
                return false;
            }
            if ($f['rating'] !== null && ((int) $s->active_reviews_count === 0 || (float) $s->active_reviews_avg_rating < $f['rating'])) {
                return false;
            }
            if ($f['pool'] && ! ($s->pool_indoor || $s->pool_outdoor)) {
                return false;
            }
            if ($f['transfer'] && ! $s->free_transfer) {
                return false;
            }
            if ($f['child_age'] !== null && ($s->child_min_age === null || (int) $s->child_min_age > $f['child_age'])) {
                return false;
            }
            if ($f['nights'] !== '' && preg_match('/\d+/', (string) $s->duration, $m)) {
                // Sanatoriums with an explicit stay length must match; unspecified ones accept any length.
                $d = (int) $m[0];
                if ($f['nights'] === 'other' ? in_array($d, [7, 10, 14, 21], true) : $d !== (int) $f['nights']) {
                    return false;
                }
            }

            return true;
        });

        $priceOf = fn (Service $s) => \Modules\TourBooking\App\Services\FromPrice::for($s);
        $list = match ($sort) {
            'price_asc'  => $list->sortBy(fn ($s) => [$priceOf($s) === null ? 1 : 0, $priceOf($s) ?? 0]),
            'price_desc' => $list->sortBy(fn ($s) => [$priceOf($s) === null ? 1 : 0, -($priceOf($s) ?? 0)]),
            'rating'     => $list->sortBy(fn ($s) => [-(float) ($s->active_reviews_avg_rating ?? 0), -(int) $s->active_reviews_count]),
            'new'        => $list->sortByDesc(fn ($s) => (string) $s->created_at),
            default      => $list->sortBy(fn ($s) => [-(int) ($s->sort_weight ?? 0), -(int) $s->active_reviews_count, (string) ($s->translation?->title ?? $s->title)]),
        };
        $list = $list->values();

        $mapPoints = $list->map(fn (Service $s) => $this->mapPoint($s))->filter()->values();

        $perPage = 12;
        $page = max(1, (int) $request->query('page', 1));
        $lastPage = max(1, (int) ceil($list->count() / $perPage));
        if ($page > $lastPage && $list->count() > 0) {
            abort(404);
        }
        $services = new \Illuminate\Pagination\LengthAwarePaginator(
            $list->forPage($page, $perPage)->values(),
            $list->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->except('page')]
        );

        $this->applySeo('sanatoriums', [
            'title'       => $disease ? $disease->h1 : \Modules\TourBooking\App\Services\SeoText::catalogTitle(),
            'description' => 'Каталог санаториев Нафталана и Грузии: цены, лечение, отзывы.',
        ]);

        return view('tourbooking::front.diseases.sanatoriums', compact('services', 'disease', 'filtersActive', 'f', 'sort', 'view', 'options', 'mapPoints'));
    }

    /** Map marker data: real coordinates (admin) or the ones in the Google Maps link; null when unknown. */
    private function mapPoint(Service $s): ?array
    {
        $lat = is_numeric($s->latitude) ? (float) $s->latitude : null;
        $lng = is_numeric($s->longitude) ? (float) $s->longitude : null;
        if (($lat === null || $lng === null) && $s->google_map_url
            && preg_match('/(?:@|q=|ll=|!3d)(-?\d{1,2}\.\d+)(?:,|!4d)(-?\d{1,3}\.\d+)/', (string) $s->google_map_url, $m)) {
            [$lat, $lng] = [(float) $m[1], (float) $m[2]];
        }
        if ($lat === null || $lng === null || ($lat == 0.0 && $lng == 0.0)) {
            return null;
        }
        $price = \Modules\TourBooking\App\Services\FromPrice::for($s);

        return [
            'slug'  => $s->slug,
            'title' => gs_display_title($s->translation?->title ?: $s->title),
            'lat'   => $lat,
            'lng'   => $lng,
            'price' => $price,
            'price_text' => $price ? currency($price) : null,
            'url'   => route('front.tourbooking.services.show', ['slug' => $s->slug]),
            'img'   => $s->thumbnail?->file_path ? asset($s->thumbnail->file_path) : null,
        ];
    }
}
