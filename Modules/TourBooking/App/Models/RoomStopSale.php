<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Task 42: «Нет мест» (rooms_left = null) or «осталось N номеров» for a date range (nights, inclusive). */
final class RoomStopSale extends Model
{
    protected $table = 'room_stop_sales';

    protected $fillable = ['service_room_id', 'date_from', 'date_to', 'rooms_left', 'note'];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'rooms_left' => 'integer',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ServiceRoom::class, 'service_room_id');
    }

    public function isStopSale(): bool
    {
        return $this->rooms_left === null || $this->rooms_left <= 0;
    }
}
