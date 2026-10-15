<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\TourBooking\App\Models\TransferBooking;

final class TransferBookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = TransferBooking::with(['route', 'vehicle', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transfer_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transfer_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('guest_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('guest_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(20);
        
        $stats = [
            'total' => TransferBooking::count(),
            'pending' => TransferBooking::where('status', 'pending')->count(),
            'confirmed' => TransferBooking::where('status', 'confirmed')->count(),
            'completed' => TransferBooking::where('status', 'completed')->count(),
        ];

        return view('tourbooking::admin.transfer-bookings.index', compact('bookings', 'stats'));
    }

    public function show(TransferBooking $transferBooking): View
    {
        $transferBooking->load(['route', 'vehicle', 'user']);
        return view('tourbooking::admin.transfer-bookings.show', compact('transferBooking'));
    }

    public function updateStatus(Request $request, TransferBooking $transferBooking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $transferBooking->status;
        $transferBooking->update($validated);

        if ($validated['status'] === 'confirmed' && $oldStatus !== 'confirmed') {
            $this->sendConfirmationEmail($transferBooking);
        }

        return redirect()->back()->with(['message' => 'Статус бронирования обновлён', 'alert-type' => 'success']);
    }

    public function destroy(TransferBooking $transferBooking): RedirectResponse
    {
        $transferBooking->delete();

        return redirect()
            ->route('admin.tourbooking.transfer-bookings.index')
            ->with(['message' => 'Бронирование удалено', 'alert-type' => 'success']);
    }

  private function sendConfirmationEmail(TransferBooking $booking): void
    {
        try {
            $booking->load(['route', 'vehicle']);

            \Illuminate\Support\Facades\Mail::to($booking->guest_email)
                ->send(new \Modules\EmailSetting\App\Mail\TransferBookingConfirmationMail($booking));

        } catch (\Exception $e) {
            \Log::error('Transfer confirmation email failed: ' . $e->getMessage());
        }
    }
}
