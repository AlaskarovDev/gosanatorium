<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Enums\Language;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewResponse;
use Modules\GlobalSetting\App\Models\GlobalSetting;
use Modules\TourBooking\App\Models\Amenity;
use Modules\TourBooking\App\Models\AmenityTranslation;
use Modules\TourBooking\App\Models\Booking;
use Modules\TourBooking\App\Models\Destination;
use Modules\TourBooking\App\Models\Review;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceReview;
use Modules\TourBooking\App\Models\ServiceSlugHistory;
use Modules\TourBooking\App\Models\ServiceType;
use Modules\TourBooking\App\Repositories\ServiceRepository;
use Modules\TourBooking\App\Repositories\ServiceTypeRepository;

final class FrontServiceController extends Controller
{

    public function __construct(
        private ServiceRepository $serviceRepository,
        private ServiceTypeRepository $serviceTypeRepository,
    ) {}

    /**
     * Display the home page of the tour booking module.
     */
    public function index(): RedirectResponse
    {
        return redirect('/');
    }
    
    public function roomSelection(string $slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', true)
            ->with(['translation', 'rooms' => function($query) {
                $query->where('status', true)->orderBy('display_order');
            }, 'rooms.media', 'rooms.thumbnail', 'rooms.activeExtras'])
            ->firstOrFail();
        
        if (!$service->rooms || $service->rooms->isEmpty()) {
            return redirect()->route('front.tourbooking.guest-booking-form', $slug);
        }
        
        $nights = request('nights', 1);
        $rooms = $service->rooms;
        
        return view('tourbooking::front.rooms.room-selection', compact('service', 'rooms', 'nights'));
    }

    public function filterRooms(Request $request, string $slug)
    {
        // Filter kodu
    }

    /**
     * Search for services.
     */
    public function search(Request $request): ViewResponse
    {
        $query = Service::where('status', true)
            ->with(['thumbnail', 'serviceType', 'reviews']);

        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('service_type')) {
            $query->where('service_type_id', $request->input('service_type'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%" . $request->input('location') . "%");
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_price', '>=', $request->input('min_price'))
                    ->orWhere('discount_price', '>=', $request->input('min_price'))
                    ->orWhere('price_per_person', '>=', $request->input('min_price'));
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_price', '<=', $request->input('max_price'))
                    ->orWhere('discount_price', '<=', $request->input('max_price'))
                    ->orWhere('price_per_person', '<=', $request->input('max_price'));
            });
        }

        $sort = $request->input('sort', 'newest');

        switch ($sort) {
            case 'price_low':
                $query->orderBy('discount_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('discount_price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating');
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $services = $query->paginate(12)->withQueryString();
        $serviceTypes = ServiceType::where('status', true)->get();
        $destinations = Destination::where('status', true)->get();
        $amenities = Amenity::where('status', true)->with('translation')->get();
        $languages = Language::cases();

        return view('tourbooking::front.services', compact('services', 'serviceTypes', 'destinations', 'amenities', 'languages'));
    }


    public function autocompleteSearch(Request $request)
    {
        $search = $request->input('q', '');
        
        if (empty($search) || strlen($search) < 2) {
            
            $destinations = Destination::select('id', 'name', 'country')
                ->where('status', true)
                ->orderBy('name', 'asc')
                ->limit(5)
                ->get();
            
            $services = Service::select('id', 'slug', 'location', 'destination_id')
                ->where('status', true)
                ->with([
                    'translation:id,service_id,title',
                    'destination:id,name'
                ])
                ->orderBy('is_featured', 'desc')
                ->orderBy('is_popular', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($service) {
                    return [
                        'id' => $service->id,
                        'title' => $service->translation?->title ?? 'N/A',
                        'location' => $service->destination?->name ?? $service->location,
                        'url' => route('front.tourbooking.services.show', $service->slug)
                    ];
                });
            
            return response()->json([
                'destinations' => $destinations,
                'services' => $services
            ]);
        }
        
        $destinations = Destination::select('id', 'name', 'country')
            ->where('status', true)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('country', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(5)
            ->get();
        
        $services = Service::select('id', 'slug', 'location', 'destination_id')
            ->where('status', true)
            ->with([
                'translation:id,service_id,title',
                'destination:id,name'
            ])
            ->where(function($query) use ($search) {
                $query->whereHas('translation', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                })
                ->orWhere('location', 'like', "%{$search}%");
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('is_popular', 'desc')
            ->limit(15)
            ->get()
            ->map(function($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->translation?->title ?? 'N/A',
                    'location' => $service->destination?->name ?? $service->location,
                    'url' => route('front.tourbooking.services.show', $service->slug)
                ];
            });
        
        return response()->json([
            'destinations' => $destinations,
            'services' => $services
        ]);
    }

    /**
     * Display all services.
     */
    public function allServices(Request $request): ViewResponse
    {
        $query = Service::where('status', true)
            ->with(['thumbnail', 'serviceType', 'translation']);

        if ($request->filled('type')) {
            $query->where('service_type_id', $request->input('type'));
        }

        if ($request->filled('destination')) {
            $query->where('destination_id', $request->input('destination'));
        }

        $services = $query->latest()->paginate(12)->withQueryString();

        $serviceTypes = ServiceType::where('status', true)->get();
        $destinations = Destination::where('status', true)->get();

        // Dropdown üçün bütün sanatoriyaların siyahısı
        $allServicesList = Service::where('status', true)
            ->with('translation:id,service_id,title')
            ->select('id', 'slug', 'destination_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Amenities siyahısı
        $amenities = Amenity::where('status', true)
            ->with('translation')
            ->get();

        // Dil siyahısı
        $languages = collect();
        try {
            $languages = collect(Language::cases())->map(function ($lang) {
                return (object) [
                    'name' => $lang->value,
                    'value' => $lang->name,
                ];
            });
        } catch (\Throwable $e) {
            $languages = collect();
        }

        return view('tourbooking::front.services', compact(
            'services',
            'serviceTypes',
            'destinations',
            'amenities',
            'languages',
            'allServicesList'
        ));
    }


    /**
     * Load services via AJAX — HTML qaytarır (response.view).
     * Bütün filtrləri tətbiq edir: selected_service_id, search, service_type_ids,
     * amenity_ids, ratings, languages, min_price, max_price, sort_by, destination_id
     */
    public function loadServicesAjax(Request $request)
    {
        $query = Service::where('status', true)
            ->with([
                'thumbnail',
                'translation',
                'serviceType',
            ])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->withExists('myWishlist');

        // ── Konkret sanatoriya seçimi (dropdown-dan) ──
        if ($request->filled('selected_service_id')) {
            $query->where('id', $request->input('selected_service_id'));
        }

        // ── Text search ──
        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('translation', function ($t) use ($keyword) {
                    $t->where('title', 'like', "%{$keyword}%")
                      ->orWhere('short_description', 'like', "%{$keyword}%");
                })
                ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        // ── Destination filter (yalnız selected_service_id yoxdursa) ──
        if ($request->filled('destination_id') && !$request->filled('selected_service_id')) {
            $query->where('destination_id', $request->input('destination_id'));
        }

        // ── Service type filter ──
        if ($request->filled('service_type_ids')) {
            $typeIds = $request->input('service_type_ids');
            if (is_array($typeIds) && count($typeIds) > 0) {
                $query->whereIn('service_type_id', $typeIds);
            }
        }

        // Əski `type` parametri ilə uyumluluk
        if ($request->filled('type') && !$request->filled('service_type_ids')) {
            $query->where('service_type_id', $request->input('type'));
        }

        // ── Price filter ──
        if ($request->filled('min_price')) {
            $minPrice = $request->input('min_price');
            $query->where(function ($q) use ($minPrice) {
                $q->where('full_price', '>=', $minPrice)
                  ->orWhere('discount_price', '>=', $minPrice)
                  ->orWhere('price_per_person', '>=', $minPrice);
            });
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->input('max_price');
            $query->where(function ($q) use ($maxPrice) {
                $q->where(function ($inner) use ($maxPrice) {
                    $inner->where('discount_price', '>', 0)
                          ->where('discount_price', '<=', $maxPrice);
                })
                ->orWhere(function ($inner) use ($maxPrice) {
                    $inner->where(function ($check) {
                        $check->whereNull('discount_price')
                              ->orWhere('discount_price', 0);
                    })
                    ->where('full_price', '<=', $maxPrice);
                })
                ->orWhere('price_per_person', '<=', $maxPrice);
            });
        }

        // ── Amenity filter ──
        if ($request->filled('amenity_ids')) {
            $amenityIds = $request->input('amenity_ids');
            if (is_array($amenityIds) && count($amenityIds) > 0) {
                foreach ($amenityIds as $amenityId) {
                    $query->whereJsonContains('amenities', $amenityId);
                }
            }
        }

        // ── Rating filter ──
        if ($request->filled('ratings')) {
            $ratings = $request->input('ratings');
            if (is_array($ratings) && count($ratings) > 0) {
                $intRatings = array_map('intval', $ratings);
                $placeholders = implode(',', array_fill(0, count($intRatings), '?'));
                $query->whereRaw(
                    "ROUND(COALESCE((SELECT AVG(rating) FROM reviews WHERE reviews.service_id = services.id AND reviews.status = 1), 0)) IN ({$placeholders})",
                    $intRatings
                );
            }
        }

        // ── Language filter ──
        if ($request->filled('languages')) {
            $languages = $request->input('languages');
            if (is_array($languages) && count($languages) > 0) {
                foreach ($languages as $lang) {
                    $query->whereJsonContains('languages', $lang);
                }
            }
        }

        // ── Sort ──
        $sortBy = $request->input('sort_by', 'default');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, full_price, price_per_person) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, full_price, price_per_person) DESC');
                break;
            case 'trending':
            case 'popular':
                $query->orderByDesc('active_reviews_avg_rating');
                break;
            case 'location_asc':
                $query->orderBy('location', 'asc');
                break;
            case 'location_desc':
                $query->orderBy('location', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        // ── Paginate ──
        $allServices = $query->paginate(12);

        $isListView = $request->input('isListView', 'false');
        $style = $request->input('style', 'style1');

        // ── HTML view render ──
        $view = view('tourbooking::front.services.services-item', compact('allServices', 'isListView'))->render();

        // Pagination count
        $from = $allServices->firstItem() ?? 0;
        $to = $allServices->lastItem() ?? 0;
        $total = $allServices->total();
        $customPaginationCount = "<strong>{$from}-{$to}</strong> из <strong>{$total}</strong> результатов";

        return response()->json([
            'view' => $view,
            'customPaginationCount' => $customPaginationCount,
        ]);
    }

    /**
     * Display service details.
     */
    public function serviceDetail(string $slug): ViewResponse|RedirectResponse
    {
        $service = Service::where('slug', $slug)
            ->where('status', true)
            ->first();

        // Köhnə slug-dur? Tarixçəyə bax, varsa 301 redirect ver.
        if (!$service) {
            $history = ServiceSlugHistory::where('slug', $slug)
                ->with('service')
                ->first();

            if ($history && $history->service && $history->service->status) {
                return redirect()->route(
                    'front.tourbooking.services.show',
                    ['slug' => $history->service->slug],
                    301
                );
            }

            // Köhnə/sınıq slug-lar (əvvəl-sonda artıq defis, məs. "-lankaran-springs-wellness-resort-").
            // Defisləri təmizləyib oxşar slug-lu servisi tap və düzgün URL-ə 301 yönləndir.
            $clean = trim($slug, '-');
            if (strlen($clean) >= 3 && $clean !== $slug) {
                $alt = Service::where('status', true)
                    ->where(function ($q) use ($clean) {
                        $q->where('slug', $clean)
                          ->orWhere('slug', 'like', $clean . '-%')   // suffiksli: ...-2026
                          ->orWhere('slug', 'like', '%-' . $clean)   // prefiksli
                          ->orWhere('slug', 'like', '%' . $clean . '%');
                    })
                    ->orderByRaw('CHAR_LENGTH(slug) asc')
                    ->first();

                if ($alt) {
                    return redirect()->route(
                        'front.tourbooking.services.show',
                        ['slug' => $alt->slug],
                        301
                    );
                }
            }

            abort(404);
        }
        
        $serviceTypeSlug = $service?->serviceType?->slug;
        
        $serviceView = match ($serviceTypeSlug) {
            'hotels', 'sanatoriums' => 'tourbooking::front.service-detail2',
            default => 'tourbooking::front.service-detail',
        };

        $service = Service::where('slug', $slug)
            ->where('status', true)
            ->with([
                'media',
                'thumbnail',
                'itineraries',
                'extraCharges',
                'serviceType',
                'availabilities',
                'destination',
                'translation',
                    'treatmentItems',
                'priceLists',
            ])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->withExists('myWishlist')
            ->firstOrFail();

        $amenities = [];
        if (is_array($service->amenities) && $service->amenities) {
            $amenities = AmenityTranslation::select('id', 'name')->whereIn('id', $service->amenities ?? [])->get();
        }

        $reviews = Review::select('id', 'service_id', 'rating_attributes')
            ->where('service_id', $service->id)
            ->where('status', true)
            ->get();

        $avgRating = Review::where('service_id', $service->id)
            ->where('status', true)
            ->avg('rating');

        $categories = [];

        foreach ($reviews as $review) {
            $attributes = $review->rating_attributes;

            if (!is_array($attributes)) continue;

            foreach ($attributes as $attr) {
                $category = $attr['category'];
                $rating = floatval($attr['rating']);

                if (!isset($categories[$category])) {
                    $categories[$category] = ['total' => 0, 'count' => 0];
                }

                $categories[$category]['total'] += $rating;
                $categories[$category]['count']++;
            }
        }

        $averageRatings = collect($categories)->map(function ($data, $category) {
            $avg = $data['total'] / $data['count'];
            return [
                'category' => $category,
                'average' => round($avg, 1),
                'percent' => round(($avg / 5) * 100),
            ];
        })->values()->toArray();

        $paginatedReviews = Review::where('service_id', $service->id)
            ->where('status', true)
            ->with('user')
            ->latest()
            ->paginate(14);

        $popularServices = $this->popularServices();

        return view($serviceView, compact('service', 'paginatedReviews', 'averageRatings', 'reviews', 'avgRating', 'popularServices', 'amenities'));
    }

    public function popularServices()
    {
        return Service::select('id', 'service_type_id', 'price_per_person', 'slug', 'location', 'is_featured', 'badges', 'full_price', 'discount_price', 'is_new', 'duration', 'group_size')
            ->where('is_popular', true)
            ->withExists('myWishlist')
            ->where('status', true)
            ->with([
                'thumbnail:id,service_id,caption,file_path',
                'translation:id,service_id,locale,title,short_description'
            ])
            ->withCount('activeReviews')
            ->withAvg('activeReviews', 'rating')
            ->latest()
            ->take(6)
            ->get();
    }

    /**
     * Filter services by category (tours, hotels, etc.).
     */
    private function getServicesByType(string $type): ViewResponse
    {
        $serviceType = ServiceType::where('slug', $type)->firstOrFail();

        $services = Service::where('service_type_id', $serviceType->id)
            ->where('status', true)
            ->with(['thumbnail', 'reviews'])
            ->latest()
            ->paginate(12);

        $title = ucfirst($type);

        return view('tourbooking::front.services-by-type', compact('services', 'serviceType', 'title'));
    }

    /**
     * Display all tours.
     */
    public function tours(): ViewResponse
    {
        return $this->getServicesByType('tours');
    }

    /**
     * Display all hotels.
     */
    public function hotels(): ViewResponse
    {
        return $this->getServicesByType('hotels');
    }

    /**
     * Display all restaurants.
     */
    public function restaurants(): ViewResponse
    {
        return $this->getServicesByType('restaurants');
    }

    /**
     * Display all rentals.
     */
    public function rentals(): ViewResponse
    {
        return $this->getServicesByType('rentals');
    }

    /**
     * Display all activities.
     */
    public function activities(): ViewResponse
    {
        return $this->getServicesByType('activities');
    }

    /**
     * Display all destinations.
     */
    public function destinations(): ViewResponse
    {
        $destinations = Destination::where('status', true)
            ->paginate(12);

        return view('tourbooking::front.destinations', compact('destinations'));
    }

    /**
     * Display a specific destination with related services.
     */
    public function destinationDetail(string $slug): ViewResponse
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $services = Service::where('status', true)
            ->where('location', 'like', "%{$destination->name}%")
            ->with(['thumbnail', 'serviceType', 'reviews'])
            ->paginate(12);

        return view('tourbooking::front.destination-detail', compact('destination', 'services'));
    }

    /**
     * Təmiz SEO URL: /hotels/{slug}
     *
     * Destination slug-una görə həmin bölgənin sanatoriya/otellərini adi servis
     * listing görünüşündə göstərir. Köhnə texniki URL-in
     * (/tour-booking/services?destination_id=..&destination=..) təmiz qarşılığıdır.
     */
    public function hotelsByDestination(string $slug): ViewResponse
    {
        $destination = Destination::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $services = Service::where('status', true)
            ->where('destination_id', $destination->id)
            ->with(['thumbnail', 'serviceType', 'translation'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $serviceTypes = ServiceType::where('status', true)->get();
        $destinations = Destination::where('status', true)->get();

        // Dropdown üçün bütün sanatoriyaların siyahısı
        $allServicesList = Service::where('status', true)
            ->with('translation:id,service_id,title')
            ->select('id', 'slug', 'destination_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // Amenities siyahısı
        $amenities = Amenity::where('status', true)
            ->with('translation')
            ->get();

        // Dil siyahısı
        $languages = collect();
        try {
            $languages = collect(Language::cases())->map(function ($lang) {
                return (object) [
                    'name' => $lang->value,
                    'value' => $lang->name,
                ];
            });
        } catch (\Throwable $e) {
            $languages = collect();
        }

        // View filtr panelində bu istiqaməti seçilmiş göstərə bilsin deyə
        $selectedDestination = $destination;

        return view('tourbooking::front.services', compact(
            'services',
            'serviceTypes',
            'destinations',
            'amenities',
            'languages',
            'allServicesList',
            'selectedDestination'
        ));
    }

    /**
     * Display service types.
     */
    public function serviceTypes(): ViewResponse
    {
        $serviceTypes = ServiceType::where('status', true)
            ->withCount('services')
            ->get();

        return view('tourbooking::front.service-types', compact('serviceTypes'));
    }

    /**
     * Display a specific service type with its services.
     */
    public function serviceTypeDetail(string $slug): ViewResponse
    {
        $serviceType = ServiceType::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $services = Service::where('service_type_id', $serviceType->id)
            ->where('status', true)
            ->with(['thumbnail', 'reviews', 'translation'])
            ->latest()
            ->paginate(12);

        return view('tourbooking::front.service-type-detail', compact('serviceType', 'services'));
    }

    /**
     * Store a new review for a service.
     */
    public function storeReview(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You must be logged in to submit a review.',
                ]
            );
        }

        $existingBooking = Booking::where('service_id', $request->service_id)
            ->where('user_id', Auth::id())
            ->where('booking_status', 'confirmed')
            ->first();

        if (!$existingBooking) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You must have a completed booking to submit a review.',
                ]
            );
        }

        if (Review::where('service_id', $request->service_id)->where('user_id', Auth::id())->exists()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You have already submitted a review for this service.',
                ]
            );
        }

        if (count($request->ratings) != 5) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You must rate all categories.',
                ]
            );
        }

        $request->validate([
            'message' => 'required|string',
            'ratings' => 'required|array',
            'ratings.*.category' => 'required|string',
            'ratings.*.rating' => 'required|numeric|min:0|max:5'
        ]);

        $allRating = 0.0;
        foreach ($request->ratings as $rating) {
            $allRating += $rating['rating'];
        }

        Review::create([
            'service_id' => $request->service_id,
            'user_id' => Auth::id(),
            'booking_id' => null,
            'review' => $request->message,
            'rating' => $allRating / 5,
            'rating_attributes' => $request->ratings,
            'status' => false,
        ]);

        return response()->json(
            [
                'success' => true,
                'message' => 'Your review has been submitted and is pending approval.',
            ]
        );
    }
}