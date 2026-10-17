<?php

use Illuminate\Support\Facades\Route;
use Modules\TourBooking\App\Http\Controllers\Admin\AmenitiesController;
use Modules\TourBooking\App\Http\Controllers\Admin\ServiceTypeController;
use Modules\TourBooking\App\Http\Controllers\Admin\DiseaseController;
use Modules\TourBooking\App\Http\Controllers\Front\FrontDiseaseController;
use Modules\TourBooking\App\Http\Controllers\Admin\ServiceController;
use Modules\TourBooking\App\Http\Controllers\Admin\BookingController;
use Modules\TourBooking\App\Http\Controllers\Admin\DestinationController;
use Modules\TourBooking\App\Http\Controllers\Admin\CouponController;
use Modules\TourBooking\App\Http\Controllers\Admin\ReviewController;
use Modules\TourBooking\App\Http\Controllers\Admin\ReportController;
use Modules\TourBooking\App\Http\Controllers\Admin\RoomController;
use Modules\TourBooking\App\Http\Controllers\Agency\ServiceController as AgencyServiceController;
use Modules\TourBooking\App\Http\Controllers\Front\FrontServiceController;
use Modules\TourBooking\App\Http\Controllers\Front\GuestBookingController;
use Modules\TourBooking\App\Http\Controllers\Admin\TransferRouteController;
use Modules\TourBooking\App\Http\Controllers\Admin\TransferVehicleController;
use Modules\TourBooking\App\Http\Controllers\Admin\TransferBookingController;
use Modules\TourBooking\App\Http\Controllers\Admin\SeasonController;
use Modules\TourBooking\App\Http\Controllers\Front\FrontTransferController;

use Modules\TourBooking\App\Http\Controllers\Front\FrontBookingController;
use Modules\TourBooking\App\Http\Controllers\Front\PaymentController;
use Modules\TourBooking\App\Http\Controllers\User\BookingController as UserBookingController;
use Modules\TourBooking\App\Http\Controllers\Agency\BookingController as AgencyBookingController;
use Modules\TourBooking\App\Http\Controllers\Agency\DestinationController as AgencyDestinationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group(['as' => 'admin.tourbooking.', 'prefix' => 'admin/tourbooking', 'middleware' => ['auth:admin']], function () {
    // Service Types
    Route::resource('service-types', ServiceTypeController::class);

    // Diseases
    Route::resource('diseases', DiseaseController::class)->except(['show']);




// Treatment Items
Route::post('services/{service}/treatment-items', [ServiceController::class, 'storeTreatmentItem'])->name('services.treatment-items.store');
Route::delete('services/treatment-items/{treatmentItem}', [ServiceController::class, 'deleteTreatmentItem'])->name('services.treatment-items.destroy');

    // Services
    Route::resource('services', ServiceController::class);
    // Claude ilə manual tərcümə (bir servis → seçilmiş dil)
    Route::post('services/{service}/translate', [ServiceController::class, 'translateService'])->name('services.translate');
    Route::get('services/type/{type}', [ServiceController::class, 'getByType'])->name('services.by-type');
    Route::get('services/tours', [ServiceController::class, 'tours'])->name('services.tours');
    Route::get('services/hotels', [ServiceController::class, 'hotels'])->name('services.hotels');
    Route::get('services/restaurants', [ServiceController::class, 'restaurants'])->name('services.restaurants');
    Route::get('services/rentals', [ServiceController::class, 'rentals'])->name('services.rentals');
    Route::get('services/activities', [ServiceController::class, 'activities'])->name('services.activities');

    // ============================================================================
    // TRANSFER ADMIN ROUTES
    // ============================================================================
    
    // Transfer Routes Management
    Route::resource('transfer-routes', TransferRouteController::class);
    Route::patch('transfer-routes/{transferRoute}/update-status', [TransferRouteController::class, 'updateStatus'])
        ->name('transfer-routes.update-status');
    
    // Transfer Vehicles Management
    Route::resource('transfer-vehicles', TransferVehicleController::class);
    Route::patch('transfer-vehicles/{transferVehicle}/update-status', [TransferVehicleController::class, 'updateStatus'])
        ->name('transfer-vehicles.update-status');
    Route::post('transfer-vehicles/{transferVehicle}/delete-gallery-image', [TransferVehicleController::class, 'deleteGalleryImage'])
        ->name('transfer-vehicles.delete-gallery-image');
    
    // Transfer Bookings Management
    Route::get('transfer-bookings', [TransferBookingController::class, 'index'])
        ->name('transfer-bookings.index');
    Route::get('transfer-bookings/{transferBooking}', [TransferBookingController::class, 'show'])
        ->name('transfer-bookings.show');
    Route::patch('transfer-bookings/{transferBooking}/update-status', [TransferBookingController::class, 'updateStatus'])
        ->name('transfer-bookings.update-status');
    Route::delete('transfer-bookings/{transferBooking}', [TransferBookingController::class, 'destroy'])
        ->name('transfer-bookings.destroy');

    // ============================================================================
    // END TRANSFER ADMIN ROUTES
    // ============================================================================

    // Service Media
    Route::post('services/{service}/media', [ServiceController::class, 'storeMedia'])->name('services.media.store');
    Route::delete('services/media/{media}', [ServiceController::class, 'deleteMedia'])->name('services.media.destroy');
    Route::post('services/media/{media}/set-thumbnail', [ServiceController::class, 'setThumbnail'])->name('services.media.set-thumbnail');
    Route::put('services/media/{media}/caption', [ServiceController::class, 'updateMediaCaption'])->name('services.media.caption');
    Route::post('services/{service}/media/reorder', [ServiceController::class, 'reorderMedia'])->name('services.media.reorder');
    Route::post('services/{service}/media/bulk-delete', [ServiceController::class, 'bulkDeleteMedia'])->name('services.media.bulk-delete');
    Route::get('services/{service}/media', [ServiceController::class, 'showMedia'])->name('services.media');
    Route::post('services/{service}/price-lists', [ServiceController::class, 'storePriceList'])->name('services.price-lists.store');
    Route::delete('services/price-lists/{priceList}', [ServiceController::class, 'deletePriceList'])->name('services.price-lists.destroy');

    // Itineraries
    Route::get('services/{service}/itineraries', [ServiceController::class, 'showItineraries'])->name('services.itineraries');
    Route::post('services/{service}/itineraries', [ServiceController::class, 'storeItinerary'])->name('services.itineraries.store');
    Route::put('services/itineraries/{itinerary}', [ServiceController::class, 'updateItinerary'])->name('services.itineraries.update');
    Route::delete('services/itineraries/{itinerary}', [ServiceController::class, 'deleteItinerary'])->name('services.itineraries.destroy');

    // Extra Charges
    Route::get('services/{service}/extra-charges', [ServiceController::class, 'showExtraCharges'])->name('services.extra-charges');
    Route::post('services/{service}/extra-charges', [ServiceController::class, 'storeExtraCharge'])->name('services.extra-charges.store');
    Route::put('services/extra-charges/{charge}', [ServiceController::class, 'updateExtraCharge'])->name('services.extra-charges.update');
    Route::delete('services/extra-charges/{charge}', [ServiceController::class, 'deleteExtraCharge'])->name('services.extra-charges.destroy');

    // Availability
    Route::get('services/{service}/availability', [ServiceController::class, 'showAvailability'])->name('services.availability');
    Route::post('services/{service}/availability', [ServiceController::class, 'storeAvailability'])->name('services.availability.store');
    Route::put('services/availability/{availability}', [ServiceController::class, 'updateAvailability'])->name('services.availability.update');
    Route::delete('services/availability/{availability}', [ServiceController::class, 'deleteAvailability'])->name('services.availability.destroy');

    // ============================================================================
    // ROOM MANAGEMENT ROUTES - START
    // ============================================================================
    Route::prefix('services/{service}/rooms')->name('services.rooms.')->group(function () {
        Route::post('/', [ServiceController::class, 'storeRoom'])->name('store');
    });
    
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::put('/{room}', [ServiceController::class, 'updateRoom'])->name('update');
        Route::delete('/{room}', [ServiceController::class, 'destroyRoom'])->name('destroy');
    });
    
    // Room CRUD for Services (Hotels)
    Route::get('services/{service}/rooms', [RoomController::class, 'index'])->name('services.rooms.index');
    Route::get('services/{service}/rooms/create', [RoomController::class, 'create'])->name('services.rooms.create');
    Route::post('services/{service}/rooms', [RoomController::class, 'store'])->name('services.rooms.store');
    Route::get('services/{service}/rooms/{room}', [RoomController::class, 'show'])->name('services.rooms.show');
    Route::get('services/{service}/rooms/{room}/edit', [RoomController::class, 'edit'])->name('services.rooms.edit');
    Route::put('services/{service}/rooms/{room}', [RoomController::class, 'update'])->name('services.rooms.update');
    Route::delete('services/{service}/rooms/{room}', [RoomController::class, 'destroy'])->name('services.rooms.destroy');
    
    // Room Media Management
    Route::get('services/{service}/rooms/{room}/media', [RoomController::class, 'showMedia'])->name('services.rooms.media');
    Route::post('services/{service}/rooms/{room}/media', [RoomController::class, 'storeMedia'])->name('services.rooms.media.store');
    Route::delete('rooms/media/{media}', [RoomController::class, 'deleteMedia'])->name('rooms.media.destroy');
    Route::post('rooms/media/{media}/set-primary', [RoomController::class, 'setPrimaryMedia'])->name('rooms.media.set-primary');
    
    // Room Status Toggle
    Route::put('rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');
    
    // Bulk Room Operations
    Route::post('services/{service}/rooms/bulk-delete', [RoomController::class, 'bulkDelete'])->name('services.rooms.bulk-delete');
    Route::post('services/{service}/rooms/bulk-status', [RoomController::class, 'bulkStatus'])->name('services.rooms.bulk-status');
    
    // ============================================================================
    // ROOM MANAGEMENT ROUTES - END
    // ============================================================================

    // Booking Management
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('bookings/{booking}/payment-status', [BookingController::class, 'updatePaymentStatus'])->name('bookings.payment-status');
    Route::get('bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    Route::get('bookings/{booking}/download-invoice', [BookingController::class, 'downloadInvoicePdf'])->name('bookings.download-invoice');
    Route::get('bookings/status/{status}', [BookingController::class, 'getByStatus'])->name('bookings.status');

    Route::post('bookings/confirm', [BookingController::class, 'bookingConfirm'])->name('bookings.confirm');
    Route::post('bookings/cancel', [BookingController::class, 'bookingCancel'])->name('bookings.cancel');
    Route::post('bookings/add-note', [BookingController::class, 'bookingAddNote'])->name('bookings.add-note');

    // Destinations
    Route::resource('destinations', DestinationController::class);
    Route::put('destinations/{destination}/status', [DestinationController::class, 'updateStatus'])->name('destinations.update-status');
    Route::put('destinations/{destination}/featured', [DestinationController::class, 'updateFeatured'])->name('destinations.update-featured');

    // Amenities
    Route::resource('amenities', AmenitiesController::class);
    Route::put('amenities/{amenity}/status', [AmenitiesController::class, 'updateStatus'])->name('amenities.update-status');

    // Reviews
    Route::get('reviews', [ServiceController::class, 'review_list'])->name('reviews.index');
    Route::get('review/detail/{id}', [ServiceController::class, 'review_detail'])->name('reviews.detail');
    Route::delete('review/delete/{id}', [ServiceController::class, 'review_delete'])->name('reviews.delete');
    Route::put('review/approve/{id}', [ServiceController::class, 'review_approve'])->name('reviews.approve');

    // Seasons
    Route::get('seasons/calendar-feed', [SeasonController::class, 'calendar'])->name('seasons.calendar');
    Route::post('seasons/check-overlap', [SeasonController::class, 'checkOverlap'])->name('seasons.check-overlap');
    Route::resource('seasons', SeasonController::class)->except(['show']);
});



Route::prefix('services')->group(function () {
    Route::get('{slug}/book', [GuestBookingController::class, 'showBookingForm'])
        ->name('front.tourbooking.guest-booking-form');
    Route::post('process-guest-booking', [GuestBookingController::class, 'processBooking'])
        ->name('front.tourbooking.process-guest-booking');
    Route::get('booking-confirmation/{code}', [GuestBookingController::class, 'showConfirmation'])
        ->name('front.tourbooking.booking-confirmation');
});

Route::prefix('admin/tourbooking/services')->middleware(['auth:admin'])->group(function () {
    Route::post('{service}/rooms', [ServiceController::class, 'storeRoom'])
        ->name('admin.tourbooking.services.rooms.store');
    Route::put('rooms/{room}', [ServiceController::class, 'updateRoom'])
        ->name('admin.tourbooking.services.rooms.update');
    Route::delete('rooms/{room}', [ServiceController::class, 'destroyRoom'])
        ->name('admin.tourbooking.services.rooms.destroy');
    Route::post('rooms/{room}/media', [ServiceController::class, 'storeRoomMedia'])
        ->name('admin.tourbooking.services.rooms.media.store');
    Route::delete('rooms/media/{media}', [ServiceController::class, 'destroyRoomMedia'])
        ->name('admin.tourbooking.services.rooms.media.destroy');
});

/*
|--------------------------------------------------------------------------
| Agency Routes
|--------------------------------------------------------------------------
*/
Route::group(['as' => 'agency.tourbooking.', 'prefix' => 'agency/tourbooking', 'middleware' => ['auth', 'CheckAgency']], function () {

    // Services
    Route::resource('services', AgencyServiceController::class);

    // Service Media
    Route::post('services/{service}/media', [AgencyServiceController::class, 'storeMedia'])->name('services.media.store');
    Route::delete('services/media/{media}', [AgencyServiceController::class, 'deleteMedia'])->name('services.media.destroy');
    Route::post('services/media/{media}/set-thumbnail', [AgencyServiceController::class, 'setThumbnail'])->name('services.media.set-thumbnail');
    Route::get('services/{service}/media', [AgencyServiceController::class, 'showMedia'])->name('services.media');

    // Itineraries
    Route::get('services/{service}/itineraries', [AgencyServiceController::class, 'showItineraries'])->name('services.itineraries');
    Route::post('services/{service}/itineraries', [AgencyServiceController::class, 'storeItinerary'])->name('services.itineraries.store');
    Route::put('services/itineraries/{itinerary}', [AgencyServiceController::class, 'updateItinerary'])->name('services.itineraries.update');
    Route::delete('services/itineraries/{itinerary}', [AgencyServiceController::class, 'deleteItinerary'])->name('services.itineraries.destroy');

    // Extra Charges
    Route::get('services/{service}/extra-charges', [AgencyServiceController::class, 'showExtraCharges'])->name('services.extra-charges');
    Route::post('services/{service}/extra-charges', [AgencyServiceController::class, 'storeExtraCharge'])->name('services.extra-charges.store');
    Route::put('services/extra-charges/{charge}', [AgencyServiceController::class, 'updateExtraCharge'])->name('services.extra-charges.update');
    Route::delete('services/extra-charges/{charge}', [AgencyServiceController::class, 'deleteExtraCharge'])->name('services.extra-charges.destroy');

    // Availability
    Route::get('services/{service}/availability', [AgencyServiceController::class, 'showAvailability'])->name('services.availability');
    Route::post('services/{service}/availability', [AgencyServiceController::class, 'storeAvailability'])->name('services.availability.store');
    Route::put('services/availability/{availability}', [AgencyServiceController::class, 'updateAvailability'])->name('services.availability.update');
    Route::delete('services/availability/{availability}', [AgencyServiceController::class, 'deleteAvailability'])->name('services.availability.destroy');

    // Booking Management
    Route::get('bookings', [AgencyBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [AgencyBookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings', [AgencyBookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [AgencyBookingController::class, 'show'])->name('bookings.show');
    Route::get('bookings/{booking}/edit', [AgencyBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('bookings/{booking}', [AgencyBookingController::class, 'update'])->name('bookings.update');
    Route::delete('bookings/{booking}', [AgencyBookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('bookings/{booking}/payment-status', [AgencyBookingController::class, 'updatePaymentStatus'])->name('bookings.payment-status');
    Route::get('bookings/{booking}/invoice', [AgencyBookingController::class, 'invoice'])->name('bookings.invoice');
    Route::get('bookings/{booking}/download-invoice', [AgencyBookingController::class, 'downloadInvoicePdf'])->name('bookings.download-invoice');
    Route::get('bookings/status/{status}', [AgencyBookingController::class, 'getByStatus'])->name('bookings.status');

    Route::post('bookings/confirm', [AgencyBookingController::class, 'bookingConfirm'])->name('bookings.confirm');
    Route::post('bookings/cancel', [AgencyBookingController::class, 'bookingCancel'])->name('bookings.cancel');
    Route::post('bookings/add-note', [AgencyBookingController::class, 'bookingAddNote'])->name('bookings.add-note');

    // Destinations
    Route::resource('destinations', AgencyDestinationController::class);
    Route::put('destinations/{destination}/status', [AgencyDestinationController::class, 'updateStatus'])->name('destinations.update-status');
    Route::put('destinations/{destination}/featured', [AgencyDestinationController::class, 'updateFeatured'])->name('destinations.update-featured');
});

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['HtmlSpecialchars', 'MaintenanceMode']], function () {

    Route::get('tourbookings', [FrontServiceController::class, 'index'])->name('tourbooking');
    Route::get('tourbookings/{slug}', [FrontServiceController::class, 'show'])->name('tourbooking.show');

    Route::group(['as' => 'payment.', 'prefix' => 'payment', 'middleware' => ['auth:web']], function () {

        Route::post('/stripe', [PaymentController::class, 'stripe_payment'])->name('stripe');
        Route::post('/bank', [PaymentController::class, 'bank_payment'])->name('bank');

        Route::get('/paypal', [PaymentController::class, 'paypal_payment'])->name('paypal');
        Route::get('/paypal-success-payment', [PaymentController::class, 'paypal_success_payment'])->name('paypal-success-payment');
        Route::get('/paypal-faild-payment', [PaymentController::class, 'paypal_faild_payment'])->name('paypal-faild-payment');

        Route::post('/razorpay', [PaymentController::class, 'razorpay_payment'])->name('razorpay');

        Route::post('/flutterwave', [PaymentController::class, 'flutterwave_payment'])->name('flutterwave');

        Route::post('/paystack', [PaymentController::class, 'paystack_payment'])->name('paystack');

        Route::get('/mollie', [PaymentController::class, 'mollie_payment'])->name('mollie');
        Route::get('/mollie-callback', [PaymentController::class, 'mollie_callback'])->name('mollie-callback');


        Route::get('/instamojo', [PaymentController::class, 'instamojo_payment'])->name('instamojo');
        Route::get('/instamojo-callback', [PaymentController::class, 'instamojo_callback'])->name('instamojo-callback');

        Route::get('/wallet', [PaymentController::class, 'wallet_payment'])->name('wallet');
    });
});

/*
|--------------------------------------------------------------------------
| TRANSFER FRONTEND ROUTES - AYRI QRUP
|--------------------------------------------------------------------------
*/
Route::group(['as' => 'front.tourbooking.transfer.', 'prefix' => 'tour-booking/transfer', 'middleware' => ['web']], function () {
    
    // Step 1: Vehicle Selection
    Route::get('/select-vehicle', [FrontTransferController::class, 'selectVehicle'])
        ->name('select-vehicle');
    
    // Step 2: Checkout
    Route::get('/checkout', [FrontTransferController::class, 'checkout'])
        ->name('checkout');
    
    // Step 3: Process Booking
    Route::post('/process', [FrontTransferController::class, 'processBooking'])
        ->name('process');
    
    // Step 4: Success Page
    Route::get('/success/{bookingCode}', [FrontTransferController::class, 'success'])
        ->name('success');
    
    // AJAX: Update route and date
    Route::post('/update-route-date', [FrontTransferController::class, 'updateRouteDate'])
        ->name('update-route-date');
});

/*
|--------------------------------------------------------------------------
| Main Tour Booking Front Routes
|--------------------------------------------------------------------------
*/
// Sanatoriums by treatment profile (/ru/diseases, /az/diseases, /en/diseases)
Route::group(['middleware' => ['web']], function () {
    Route::get('/diseases', [FrontDiseaseController::class, 'index'])->name('diseases.index');
    Route::get('/diseases/{slug}', [FrontDiseaseController::class, 'show'])->name('diseases.show');
});

Route::group(['as' => 'front.tourbooking.', 'prefix' => 'tour-booking', 'middleware' => ['web']], function () {
    // Home/Search Page
    Route::get('/', [FrontServiceController::class, 'index'])->name('home');
    Route::get('/search', [FrontServiceController::class, 'search'])->name('search');

    Route::get('/services/search/autocomplete', [FrontServiceController::class, 'autocompleteSearch'])
        ->name('services.autocomplete');

    // Transfer Routes API
    Route::get('/transfer-routes/active', [TransferRouteController::class, 'getActiveRoutes'])
        ->name('transfer-routes.active');

    // Service Types
    Route::get('/types', [FrontServiceController::class, 'serviceTypes'])->name('service-types');
    Route::get('/types/{slug}', [FrontServiceController::class, 'serviceTypeDetail'])->name('service-types.show');
    
Route::post('/consultant-contact', [\App\Http\Controllers\ConsultantContactController::class, 'send'])
    ->name('consultant.contact.send');
    // Services
    Route::get('/services', [FrontServiceController::class, 'allServices'])->name('services');
    Route::get('/services/load', [FrontServiceController::class, 'loadServicesAjax'])->name('services.load.ajax');
    // Köhnə URL — 301 redirect ilə yeni qısa URL-ə yönləndirilir (SEO üçün)
    Route::get('/service/{slug}', function ($slug) {
        return redirect()->route('front.tourbooking.services.show', ['slug' => $slug], 301);
    });

    Route::get('/tours', [FrontServiceController::class, 'tours'])->name('tours');
    Route::get('/hotels', [FrontServiceController::class, 'hotels'])->name('hotels');
    Route::get('/restaurants', [FrontServiceController::class, 'restaurants'])->name('restaurants');
    Route::get('/rentals', [FrontServiceController::class, 'rentals'])->name('rentals');
    Route::get('/activities', [FrontServiceController::class, 'activities'])->name('activities');

    // Destinations
    Route::get('/destinations', [FrontServiceController::class, 'destinations'])->name('destinations');
    Route::get('/destinations/{slug}', [FrontServiceController::class, 'destinationDetail'])->name('destinations.show');

    // Booking
    Route::get('/book/checkout/view', [FrontBookingController::class, 'bookingCheckoutView'])->name('book.checkout.view')->middleware('auth:web');

    // Reviews
    Route::post('/services/reviews', [FrontServiceController::class, 'storeReview'])->name('reviews.store');

    // Availability Check
    Route::post('/check-availability', [FrontBookingController::class, 'checkAvailability'])->name('check-availability');

    // Coupons
    Route::post('/validate-coupon', [FrontBookingController::class, 'validateCoupon'])->name('validate-coupon');

    // ============================================================================
    // ROOM BOOKING FRONTEND ROUTES - START
    // ============================================================================
    
    // Get service data with rooms for popup
    Route::get('/services/{service}/room-popup-data', [
        \Modules\TourBooking\App\Http\Controllers\Front\FrontBookingController::class, 
        'getRoomPopupData'
    ])->name('room-popup-data');
    
    // Get rooms list for a service
    Route::get('/services/{service}/rooms', [FrontBookingController::class, 'getServiceRooms'])
        ->name('services.rooms');
    
    // Check room availability
    Route::post('/rooms/{room}/check-availability', [
        \Modules\TourBooking\App\Http\Controllers\Front\FrontBookingController::class, 
        'checkRoomAvailability'
    ])->name('room.check-availability');
    Route::get('/service/{slug}/rooms', [FrontServiceController::class, 'roomSelection'])->name('rooms.selection');
    Route::get('/service/{slug}/rooms/filter', [FrontServiceController::class, 'filterRooms'])->name('rooms.filter');
    
    // Calculate room price
    Route::post('/rooms/{room}/calculate-price', [FrontBookingController::class, 'calculateRoomPrice'])
        ->name('rooms.calculate-price');
    
    // Guest booking (no auth required)
    Route::post('/guest-booking', [
        \Modules\TourBooking\App\Http\Controllers\Front\FrontBookingController::class, 
        'guestBooking'
    ])->name('guest-booking');
    
    // Room booking success page
    Route::get('/room-booking-success/{code}', [FrontBookingController::class, 'roomBookingSuccess'])
        ->name('room-booking-success');
    
    // ============================================================================
    // ROOM BOOKING FRONTEND ROUTES - END
    // ============================================================================
});

// User routes
Route::group(['as' => 'user.', 'prefix' => 'user'], function () {

    Route::group(['middleware' => 'auth:web'], function () {

        Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/details/{id}', [UserBookingController::class, 'details'])->name('bookings.details');
        Route::post('/bookings/cancel/{id}', [UserBookingController::class, 'cancelBooking'])->name('bookings.cancel');
    });
});

/*
|--------------------------------------------------------------------------
| Transfer API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api/transfer', 'middleware' => ['web']], function () {
    
    // Get active routes
    Route::get('/routes', function () {
        $routes = \Modules\TourBooking\App\Models\TransferRoute::where('status', true)
            ->orderBy('display_order', 'asc')
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->id,
                    'name' => $route->from_location . ' → ' . $route->to_location,
                    'from' => $route->from_location,
                    'to' => $route->to_location,
                    'price' => $route->base_price,
                    'duration' => $route->duration_minutes,
                ];
            });
        
        return response()->json(['success' => true, 'routes' => $routes]);
    });
    
    // Get active vehicles
    Route::get('/vehicles', function () {
        $vehicles = \Modules\TourBooking\App\Models\TransferVehicle::where('status', true)
            ->orderBy('display_order', 'asc')
            ->get()
            ->map(function ($vehicle) {
                return [
                    'id' => $vehicle->id,
                    'name' => $vehicle->name,
                    'image' => $vehicle->image ? asset($vehicle->image) : null,
                    'passenger_capacity' => $vehicle->passenger_capacity,
                    'luggage_capacity' => $vehicle->luggage_capacity,
                    'features' => $vehicle->features,
                    'price' => $vehicle->base_price,
                ];
            });
        
        return response()->json(['success' => true, 'vehicles' => $vehicles]);
    });
});

/*
|--------------------------------------------------------------------------
| Destination üzrə otel/sanatoriya listing — təmiz SEO URL: /hotels/{slug}
|--------------------------------------------------------------------------
| Ana səhifədəki "Populyar istiqamətlər" bölməsi buraya link verir.
| Köhnə URL: /tour-booking/services?destination_id=..&destination=..
| İki seqmentli olduğu üçün aşağıdakı tək-seqmentli /{slug} catch-all ilə
| konflikt yaratmır, amma aydınlıq üçün ondan əvvəl qeydiyyatdan keçirilir.
*/
Route::get('/hotels/{slug}', [FrontServiceController::class, 'hotelsByDestination'])
    ->where('slug', '[a-z0-9-]+')
    ->middleware(['web', 'HtmlSpecialchars', 'MaintenanceMode'])
    ->name('front.tourbooking.hotels.destination');

/*
|--------------------------------------------------------------------------
| Qısa Servis URL-i: /{slug}
|--------------------------------------------------------------------------
| BU ROUTE FAYLIN ƏN SONUNDA OLMALIDIR — çünki /{slug} catch-all-dır və
| əvvəlində qoyulsa /about-us, /blogs, /faq, /admin və s. ilə konflikt yaradar.
| Slug müvafiq servisə uyğun gəlməsə controller firstOrFail() ilə 404 qaytarır.
*/
Route::get('/{slug}', [FrontServiceController::class, 'serviceDetail'])
    // Əvvəl/sonda artıq defisli köhnə slug-lar da (məs. "-lankaran-springs-wellness-resort-")
    // bu route-a düşsün ki, controller onları düzgün servisə 301 yönləndirə bilsin.
    ->where('slug', '[a-z0-9-]+')
    ->middleware(['web', 'HtmlSpecialchars', 'MaintenanceMode'])
    ->name('front.tourbooking.services.show');