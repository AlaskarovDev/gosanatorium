<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\TransferRoute;
use Modules\TourBooking\App\Models\TransferVehicle;
use Modules\TourBooking\App\Models\TransferBooking;

final class FrontTransferController extends Controller
{
    /**
     * Show vehicle selection page - Step 1
     * URL: /tour-booking/transfer/select-vehicle
     */
    public function selectVehicle(Request $request): View
    {
        $routeId = $request->input('route_id');
        $transferDate = $request->input('transfer_date');
        $passengers = $request->input('passengers', 2);

        $route = TransferRoute::findOrFail($routeId);

        $vehicles = TransferVehicle::active()
            ->ordered()
            ->where('passenger_capacity', '>=', $passengers)
            ->get();

        return view('tourbooking::front.transfer.select-vehicle', compact(
            'route',
            'vehicles',
            'transferDate',
            'passengers'
        ));
    }

    /**
     * Show checkout page - Step 2
     * URL: /tour-booking/transfer/checkout
     */
    public function checkout(Request $request): View
    {
        $routeId = $request->input('route_id');
        $vehicleId = $request->input('vehicle_id');
        $transferDate = $request->input('transfer_date');
        $passengers = $request->input('passengers', 2);

        $route = TransferRoute::findOrFail($routeId);
        $vehicle = TransferVehicle::findOrFail($vehicleId);

        $totalPrice = (float)$route->base_price + (float)$vehicle->base_price;

        $allRoutes = TransferRoute::active()->ordered()->get();

        $user = Auth::user();

        return view('tourbooking::front.transfer.checkout', compact(
            'route',
            'vehicle',
            'transferDate',
            'passengers',
            'totalPrice',
            'allRoutes',
            'user'
        ));
    }

    /**
     * Process booking - Step 3
     * URL: POST /tour-booking/transfer/process
     */
public function processBooking(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:transfer_routes,id',
            'vehicle_id' => 'required|exists:transfer_vehicles,id',
            'transfer_date' => 'required|date|after_or_equal:today',
            'transfer_time' => 'nullable|date_format:H:i',
            'passengers' => 'required|integer|min:1|max:50',
            'pickup_address' => 'nullable|string|max:500',
            'dropoff_address' => 'nullable|string|max:500',
            'flight_number' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string|max:1000',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:50',
            'create_account' => 'nullable|boolean',
            'password' => 'nullable|required_if:create_account,1|min:6',
        ]);

        $route = TransferRoute::findOrFail($validated['route_id']);
        $vehicle = TransferVehicle::findOrFail($validated['vehicle_id']);

        $basePrice = (float)$route->base_price;
        $vehiclePrice = (float)$vehicle->base_price;
        $totalPrice = $basePrice + $vehiclePrice;

        $userId = null;
        
        if (Auth::check()) {
            $userId = Auth::id();
        } elseif ($request->input('create_account')) {
            $existingUser = User::where('email', $validated['guest_email'])->first();
            
            if ($existingUser) {
                $userId = $existingUser->id;
            } else {
                $user = User::create([
                    'name' => $validated['guest_name'],
                    'email' => $validated['guest_email'],
                    'phone' => $validated['guest_phone'],
                    'password' => Hash::make($validated['password']),
                    'status' => 1,
                ]);
                $userId = $user->id;
                Auth::login($user);
            }
        } else {
            $existingUser = User::where('email', $validated['guest_email'])->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            }
        }

        $booking = TransferBooking::create([
            'user_id' => $userId,
            'route_id' => $validated['route_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'transfer_date' => $validated['transfer_date'],
            'transfer_time' => $validated['transfer_time'] ?? null,
            'passengers' => $validated['passengers'],
            'pickup_address' => $validated['pickup_address'] ?? null,
            'dropoff_address' => $validated['dropoff_address'] ?? null,
            'flight_number' => $validated['flight_number'] ?? null,
            'special_requests' => $validated['special_requests'] ?? null,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'base_price' => $basePrice,
            'vehicle_price' => $vehiclePrice,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Send admin notification
        $this->sendAdminNotification($booking);



        return redirect()->route('front.tourbooking.transfer.success', $booking->booking_code);
    }

    /**
     * Show success page - Step 4
     * URL: /tour-booking/transfer/success/{bookingCode}
     */
    public function success(string $bookingCode): View
    {
        $booking = TransferBooking::where('booking_code', $bookingCode)
            ->with(['route', 'vehicle'])
            ->firstOrFail();

        return view('tourbooking::front.transfer.success', compact('booking'));
    }

    /**
     * Update route and date (AJAX)
     */
    public function updateRouteDate(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:transfer_routes,id',
            'vehicle_id' => 'required|exists:transfer_vehicles,id',
        ]);

        $route = TransferRoute::find($validated['route_id']);
        $vehicle = TransferVehicle::find($validated['vehicle_id']);

        $totalPrice = (float)$route->base_price + (float)$vehicle->base_price;

        return response()->json([
            'success' => true,
            'route' => [
                'id' => $route->id,
                'name' => $route->from_location . ' → ' . $route->to_location,
                'price' => $route->base_price,
            ],
            'total_price' => $totalPrice,
            'formatted_price' => number_format($totalPrice, 2) . ' ₼',
        ]);
    }

    /**
     * Send admin notification
     */
    private function sendAdminNotification(TransferBooking $booking): void
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@example.com');
            
            $subject = "Новое бронирование трансфера - {$booking->booking_code}";
            $message = "Получено новое бронирование трансфера!\n\n";
            $message .= "Код: {$booking->booking_code}\n";
            $message .= "Клиент: {$booking->guest_name}\n";
            $message .= "Email: {$booking->guest_email}\n";
            $message .= "Телефон: {$booking->guest_phone}\n";
            $message .= "Дата: {$booking->transfer_date->format('d.m.Y')}\n";
            if ($booking->route) {
                $message .= "Маршрут: {$booking->route->from_location} → {$booking->route->to_location}\n";
            }
            if ($booking->vehicle) {
                $message .= "Транспорт: {$booking->vehicle->name}\n";
            }
            $message .= "Пассажиры: {$booking->passengers}\n";
            $message .= "Итого: {$booking->total_price} ₼\n\n";
            $message .= "Войдите в админ панель для подтверждения.";
            
            $headers = "From: noreply@" . request()->getHost() . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            @mail($adminEmail, $subject, $message, $headers);
        } catch (\Exception $e) {
            \Log::error('Admin notification email failed: ' . $e->getMessage());
        }
    }
}
