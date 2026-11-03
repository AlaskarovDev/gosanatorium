<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Traits\UsesSeoMeta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\Disease;

final class FrontDiseaseController extends Controller
{
    use UsesSeoMeta;

    public function index(): View
    {
        $diseases = Disease::active()->ordered()->get();

        $this->applySeo('diseases', [
            'title'       => 'Санатории по заболеваниям — подбор по диагнозу',
            'description' => 'Найдите санаторий в Нафталане или Грузии по вашему диагнозу. Лечение суставов, кожных заболеваний, опорно-двигательного аппарата.',
        ]);

        return view('tourbooking::front.diseases.index', compact('diseases'));
    }

    public function show(string $slug): View
    {
        $disease = Disease::active()->where('slug', $slug)->firstOrFail();

        $services = $disease->sanatoriums()
            ->where('services.status', true)
            ->with(['thumbnail', 'serviceType', 'translation'])
            ->latest('services.created_at')
            ->paginate(200); // show every sanatorium that treats the disease

        return view('tourbooking::front.diseases.show', compact('disease', 'services'));
    }

    /** Short filter codes used in the search widget => disease slugs in the database. */
    private const DISEASE_CODES = [
        'joints' => 'sustavy',
        'skin'   => 'psoriaz',
        'neuro'  => 'nevrologiya',
        'gyneco' => 'urologiya',
        'gastro' => 'zhkt',
        'endo'   => 'diabet',
    ];

    /**
     * All sanatoriums (/ru/sanatoriyi), optionally filtered by
     * ?disease=joints|skin|neuro|gyneco|gastro|endo (or a disease slug), ?budget_min / ?budget_max (USD),
     * ?nights=7|10|14|21|other, ?destination_id.
     */
    public function sanatoriums(Request $request): View
    {
        $slug = trim((string) $request->query('disease', ''));
        if ($slug === 'all') {
            $slug = '';
        }
        $dbSlug = self::DISEASE_CODES[$slug] ?? $slug;
        $disease = $dbSlug !== '' ? Disease::active()->where('slug', $dbSlug)->first() : null;

        $budgetMin = $request->query('budget_min');
        $budgetMax = $request->query('budget_max');
        $budgetMin = is_numeric($budgetMin) ? (float) $budgetMin : null;
        $budgetMax = is_numeric($budgetMax) ? (float) $budgetMax : null;
        $nights = strtolower(trim((string) $request->query('nights', '')));
        $nights = in_array($nights, ['7', '10', '14', '21', 'other'], true) ? $nights : '';
        $destinationId = (int) $request->query('destination_id', 0);

        $diseaseFilterActive = $slug !== '';
        $filtersActive = $diseaseFilterActive || $budgetMin !== null || $budgetMax !== null
            || $nights !== '' || $destinationId > 0;

        $query = Service::where('status', true)
            ->with(['thumbnail', 'serviceType', 'translation']);

        if ($diseaseFilterActive) {
            if ($disease) {
                $query->whereIn('services.id', $disease->sanatoriums()->pluck('services.id'));
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        if ($destinationId > 0) {
            $query->where('services.destination_id', $destinationId);
        }

        if ($budgetMin !== null || $budgetMax !== null || $nights !== '') {
            $ids = $query->get()->filter(function (Service $s) use ($budgetMin, $budgetMax, $nights) {
                if ($budgetMin !== null || $budgetMax !== null) {
                    $price = $s->is_per_person
                        ? (float) $s->price_per_person
                        : (float) ($s->discount_price ?: $s->full_price);
                    if ($price <= 0) {
                        $price = (float) ($s->min_room_price ?? 0);
                    }
                    if ($price <= 0) {
                        return false;
                    }
                    if ($budgetMin !== null && $price < $budgetMin) {
                        return false;
                    }
                    if ($budgetMax !== null && $budgetMax > 0 && $price > $budgetMax) {
                        return false;
                    }
                }
                if ($nights !== '' && preg_match('/\d+/', (string) $s->duration, $m)) {
                    // Sanatoriums with an explicit stay length must match; unspecified ones accept any length.
                    $d = (int) $m[0];
                    if ($nights === 'other' ? in_array($d, [7, 10, 14, 21], true) : $d !== (int) $nights) {
                        return false;
                    }
                }
                return true;
            })->pluck('id')->all();

            $query = Service::where('status', true)->whereIn('services.id', $ids)
                ->with(['thumbnail', 'serviceType', 'translation']);
        }

        $services = $query->latest('services.created_at')->paginate(200)->withQueryString();

        $this->applySeo('sanatoriums', [
            'title'       => $disease ? $disease->name . ' — санатории' : 'Все санатории',
            'description' => 'Каталог санаториев Нафталана и Грузии: цены, лечение, отзывы.',
        ]);

        return view('tourbooking::front.diseases.sanatoriums', compact('services', 'disease', 'filtersActive'));
    }
}
