<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * Transfer Booking Model
 */
final class TransferBooking extends Model
{
    use HasFactory;

    protected $table = 'transfer_bookings';

    protected $fillable = [
        'booking_code',
        'user_id',
        'route_id',
        'vehicle_id',
        'transfer_date',
        'transfer_time',
        'passengers',
        'pickup_address',
        'dropoff_address',
        'flight_number',
        'special_requests',
        'guest_name',
        'guest_email',
        'guest_phone',
        'base_price',
        'vehicle_price',
        'total_price',
        'status',
        'payment_status',
        'admin_notes',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'passengers' => 'integer',
        'base_price' => 'decimal:2',
        'vehicle_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'TRF-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransferRoute::class, 'route_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(TransferVehicle::class, 'vehicle_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'В ожидании',
            'confirmed' => 'Подтверждён',
            'cancelled' => 'Отменён',
            'completed' => 'Завершён',
            default => 'Неизвестно'
        };
    }
}
