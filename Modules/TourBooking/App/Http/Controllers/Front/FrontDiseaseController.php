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

    /**
     * All sanatoriums (/ru/sanatoriyi), optionally filtered by treatment direction (?disease=slug).
     */
    public function sanatoriums(Request $request): View
    {
        $slug = (string) $request->query('disease', '');
        $disease = $slug !== '' ? Disease::active()->where('slug', $slug)->first() : null;

        $query = Service::where('status', true)
            ->with(['thumbnail', 'serviceType', 'translation']);

        if ($disease) {
            $query->whereIn('services.id', $disease->sanatoriums()->pluck('services.id'));
        }

        $services = $query->latest('services.created_at')->paginate(200)->withQueryString();

        $this->applySeo('sanatoriums', [
            'title'       => $disease ? $disease->name . ' — санатории' : 'Все санатории',
            'description' => 'Каталог санаториев Нафталана и Грузии: цены, лечение, отзывы.',
        ]);

        return view('tourbooking::front.diseases.sanatoriums', compact('services', 'disease'));
    }
}
