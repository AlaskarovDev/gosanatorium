<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Modules\TourBooking\App\Models\Booking;
use Modules\TourBooking\App\Models\Service;
use Modules\TourBooking\App\Models\ServiceRoom;
use Carbon\Carbon;

final class GuestBookingController extends Controller
{
    /**
     * Show the guest booking form
     */
    public function showBookingForm(Request $request, string $slug)
    {
        $service = Service::where('slug', $slug)
            ->where('status', true)
            ->with(['activeRooms.media', 'activeRooms.thumbnail'])
            ->firstOrFail();

        // Check if service has rooms
        $hasRooms = $service->activeRooms->count() > 0;

        // If no room_id provided and service has rooms, send to the room selection page.
        if (!$request->has('room_id') && $hasRooms) {
            return redirect()->route('front.tourbooking.rooms.selection', ['slug' => $service->slug]);
        }

        // Get booking details
        $roomId = $request->input('room_id');
        $room = $roomId ? ServiceRoom::with('activeExtras')->find($roomId) : null;

        $checkIn = $request->input('check_in', date('Y-m-d'));
        $checkOut = $request->input('check_out', date('Y-m-d', strtotime('+1 day')));
        $adults = max(1, (int) $request->input('adults', $request->input('guests', 2)));
        $children = max(0, (int) $request->input('children', 0));
        $guests = $adults + $children;

        // Calculate nights
        $checkInDate = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);
        $nights = $checkInDate->diffInDays($checkOutDate) ?: 1;

        // Parse selected optional extras (comma-separated string from URL or array)
        $optionalIds = collect();
        $rawExtras = $request->input('optional_extras');
        if (is_array($rawExtras)) {
            $optionalIds = collect($rawExtras)->map(fn($v) => (int) $v)->filter();
        } elseif (is_string($rawExtras) && $rawExtras !== '') {
            $optionalIds = collect(explode(',', $rawExtras))->map(fn($v) => (int) trim($v))->filter();
        }

        // Calculate price (including extras) with seasonal pricing
        $pricePerNight = 0;
        $extrasBreakdown = collect();
        if ($room) {
            $basePrice = $room->discount_price ?? $room->price_per_night;
            if (method_exists($room, 'priceForDate')) {
                $seasonal = $room->current_price_per_night;
                if ($seasonal !== null) $basePrice = $seasonal;
            }
            $basePriceFor = (int) ($room->base_price_for ?: ($room->max_guests ?: 2));
            $pricePerNight = (float) $basePrice;

            $extras = $room->activeExtras ?: $room->extras()->where('status', true)->get();
            $extras = $extras->keyBy('id');

            // Auto adult extra
            $extraAdults = max(0, $adults - $basePriceFor);
            if ($extraAdults > 0) {
                $adultExtra = $extras->firstWhere('type', 'adult');
                if ($adultExtra) {
                    $amt = (float) $adultExtra->price * $extraAdults;
                    $pricePerNight += $amt;
                    $extrasBreakdown->push(['name' => "$extraAdults × {$adultExtra->name}", 'amount' => $amt]);
                }
            }
            // Auto child extra
            if ($children > 0) {
                $childExtra = $extras->firstWhere('type', 'child');
                if ($childExtra) {
                    $amt = (float) $childExtra->price * $children;
                    $pricePerNight += $amt;
                    $extrasBreakdown->push(['name' => "$children × {$childExtra->name}", 'amount' => $amt]);
                }
            }
            // Optional extras
            foreach ($optionalIds as $exId) {
                $ex = $extras->get($exId);
                if ($ex && $ex->type === 'optional') {
                    $pricePerNight += (float) $ex->price;
                    $extrasBreakdown->push(['name' => $ex->name, 'amount' => (float) $ex->price]);
                }
            }
        } else {
            $pricePerNight = $service->discount_price ?? $service->full_price ?? $service->price_per_person;
        }
        $totalPrice = $pricePerNight * $nights;

        return view('tourbooking::front.bookings.guest-booking-form', [
            'service' => $service,
            'room' => $room,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'nights' => $nights,
            'guests' => $guests,
            'adults' => $adults,
            'children' => $children,
            'totalPrice' => $totalPrice,
            'pricePerNight' => $pricePerNight,
            'extrasBreakdown' => $extrasBreakdown,
            'optionalIds' => $optionalIds->all(),
        ]);
    }

    /**
     * Process the guest booking
     */
    public function processBooking(Request $request)
    {
        // Validation - DÜZƏLDILDI: after_or_equal:today silindi (keçmiş tarixlər üçün problem yaradırdı)
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'room_id' => 'nullable|exists:service_rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'nights' => 'required|integer|min:1',
            'guests' => 'required|integer|min:1',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted',
            'optional_extras' => 'nullable|array',
            'optional_extras.*' => 'integer|exists:room_extras,id',
        ], [
            // Azərbaycanca xəta mesajları
            'service_id.required' => 'Xidmət seçilməyib.',
            'service_id.exists' => 'Seçilmiş xidmət mövcud deyil.',
            'room_id.exists' => 'Seçilmiş otaq mövcud deyil.',
            'check_in.required' => 'Giriş tarixi tələb olunur.',
            'check_in.date' => 'Giriş tarixi düzgün formatda deyil.',
            'check_out.required' => 'Çıxış tarixi tələb olunur.',
            'check_out.date' => 'Çıxış tarixi düzgün formatda deyil.',
            'check_out.after' => 'Çıxış tarixi giriş tarixindən sonra olmalıdır.',
            'nights.required' => 'Gecə sayı tələb olunur.',
            'nights.min' => 'Minimum 1 gecə olmalıdır.',
            'guests.required' => 'Qonaq sayı tələb olunur.',
            'guests.min' => 'Minimum 1 qonaq olmalıdır.',
            'total_price.required' => 'Ümumi qiymət tələb olunur.',
            'name.required' => 'Ad tələb olunur.',
            'name.max' => 'Ad 255 simvoldan çox ola bilməz.',
            'email.required' => 'Email tələb olunur.',
            'email.email' => 'Email düzgün formatda deyil.',
            'phone.required' => 'Telefon nömrəsi tələb olunur.',
            'password.required' => 'Şifrə tələb olunur.',
            'password.min' => 'Şifrə minimum 6 simvol olmalıdır.',
            'password.confirmed' => 'Şifrələr uyğun gəlmir.',
            'terms.required' => 'Şərtləri qəbul etməlisiniz.',
            'terms.accepted' => 'Şərtləri qəbul etməlisiniz.',
        ]);

        DB::beginTransaction();

        try {
            // Check if user already exists
            $existingUser = User::where('email', $validated['email'])->first();
            $isNewUser = false;
            
            if ($existingUser) {
                // If user exists, just use that account
                $user = $existingUser;
            } else {
                // Create new user account
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'phone' => $validated['phone'],
                    'address' => $validated['address'] ?? null,
                    'country' => $validated['country'] ?? null,
                    'status' => 'enable',
                    'email_verified_at' => now(),
                    'verification_token' => Str::random(32),
                ]);
                $isNewUser = true;
            }

            // Get service and room
            $service = Service::findOrFail($validated['service_id']);
            $room = $validated['room_id'] ? ServiceRoom::with('activeExtras')->find($validated['room_id']) : null;

            // Calculate price based on room or service
            if ($room) {
                $servicePrice = $room->discount_price ?? $room->price_per_night;
            } else {
                $servicePrice = $service->discount_price ?? $service->full_price ?? $service->price_per_person;
            }

            // Build extras breakdown to save with the booking
            $adultsCount = $validated['adults'] ?? $validated['guests'];
            $childrenCount = $validated['children'] ?? 0;
            $extrasBreakdown = [];

            if ($room) {
                $basePriceFor = (int) ($room->base_price_for ?: ($room->max_guests ?: 2));
                $extras = ($room->activeExtras ?? collect())->keyBy('id');

                $extraAdults = max(0, $adultsCount - $basePriceFor);
                if ($extraAdults > 0) {
                    $adultExtra = $extras->firstWhere('type', 'adult');
                    if ($adultExtra) {
                        $extrasBreakdown[] = [
                            'type'     => 'adult',
                            'name'     => $adultExtra->name,
                            'quantity' => $extraAdults,
                            'unit_price' => (float) $adultExtra->price,
                            'amount_per_night' => (float) $adultExtra->price * $extraAdults,
                        ];
                    }
                }
                if ($childrenCount > 0) {
                    $childExtra = $extras->firstWhere('type', 'child');
                    if ($childExtra) {
                        $extrasBreakdown[] = [
                            'type'     => 'child',
                            'name'     => $childExtra->name,
                            'quantity' => $childrenCount,
                            'unit_price' => (float) $childExtra->price,
                            'amount_per_night' => (float) $childExtra->price * $childrenCount,
                        ];
                    }
                }
                foreach ((array) ($validated['optional_extras'] ?? []) as $optId) {
                    $ex = $extras->get((int) $optId);
                    if ($ex && $ex->type === 'optional') {
                        $extrasBreakdown[] = [
                            'type'     => 'optional',
                            'name'     => $ex->name,
                            'quantity' => 1,
                            'unit_price' => (float) $ex->price,
                            'amount_per_night' => (float) $ex->price,
                        ];
                    }
                }
            }

            // Create booking
            $booking = Booking::create([
                'booking_code' => Booking::generateBookingCode(),
                'service_id' => $service->id,
                'service_room_id' => $room?->id,
                'user_id' => $user->id,
                'check_in_date' => $validated['check_in'],
                'check_out_date' => $validated['check_out'],
                'nights' => $validated['nights'],
                'adults' => $adultsCount,
                'children' => $childrenCount,
                'infants' => 0,
                'service_price' => $servicePrice,
                'subtotal' => $validated['total_price'],
                'total' => $validated['total_price'],
                'paid_amount' => 0,
                'due_amount' => $validated['total_price'],
                'payment_method' => 'pending',
                'payment_status' => 'pending',
                'booking_status' => 'pending',
                'customer_name' => $validated['name'],
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'] ?? null,
                'customer_notes' => $validated['notes'] ?? null,
                'extra_services' => $extrasBreakdown,
            ]);

            // Log in the user
            Auth::login($user);

            // Send notification email to admin
            $this->sendAdminNotification($booking);

            // Send confirmation email to customer
            $this->sendCustomerNotification($booking);

            DB::commit();

            // DÜZƏLDILDI: İstifadəçi məlumatlarını session-a yazırıq
            $sessionData = [
                'success' => 'Sifarişiniz qəbul edildi! Təsdiq sizə email vasitəsilə göndəriləcək.',
                'booking_code' => $booking->booking_code,
                'is_new_user' => $isNewUser,
            ];
            
            // Yeni istifadəçi üçün giriş məlumatlarını əlavə et
            if ($isNewUser) {
                $sessionData['user_credentials'] = [
                    'email' => $user->email,
                    'password' => $validated['password'], // Orijinal şifrəni göstəririk
                ];
            }

            return redirect()->route('front.tourbooking.booking-confirmation', $booking->booking_code)
                ->with($sessionData);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // DÜZƏLDILDI: Xətanı log edirik
            Log::error('Guest booking error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->except(['password', 'password_confirmation'])
            ]);
            
            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['general' => 'Xəta baş verdi: ' . $e->getMessage()]);
        }
    }

    /**
     * Show booking confirmation page
     */
    public function showConfirmation(string $bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->with(['service', 'room', 'user'])
            ->firstOrFail();

        return view('tourbooking::front.bookings.booking-confirmation', [
            'booking' => $booking,
        ]);
    }

    /**
     * Send notification to admin
     */
    private function sendAdminNotification(Booking $booking): void
    {
        // Get admin email from settings
        $adminEmail = optional(\Modules\GlobalSetting\App\Models\GlobalSetting::first())->contact_email;
        
        if ($adminEmail) {
            // You can implement mail sending here
            // Mail::to($adminEmail)->send(new NewBookingNotification($booking));
        }
    }

    /**
     * Send confirmation email to customer
     */
private function sendCustomerNotification(Booking $booking): void
{
    // Email yalnız admin confirm etdikdə göndərilir (BookingController::bookingConfirm)
}
}