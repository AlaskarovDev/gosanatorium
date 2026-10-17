<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\Disease;

final class FrontDiseaseController extends Controller
{
    public function index(): View
    {
        $diseases = Disease::active()->ordered()->get();

        return view('tourbooking::front.diseases.index', compact('diseases'));
    }

    public function show(string $slug): View
    {
        $disease = Disease::active()->where('slug', $slug)->firstOrFail();

        $services = $disease->sanatoriums()
            ->where('services.status', true)
            ->with(['thumbnail', 'serviceType', 'translation'])
            ->latest('services.created_at')
            ->paginate(12);

        return view('tourbooking::front.diseases.show', compact('disease', 'services'));
    }
}
