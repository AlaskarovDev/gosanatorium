<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomExtra extends Model
{
    public const TYPE_ADULT    = 'adult';
    public const TYPE_CHILD    = 'child';
    public const TYPE_OPTIONAL = 'optional';

    protected $fillable = [
        'room_id',
        'name',
        'type',
        'price',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'status'     => 'boolean',
        'sort_order' => 'integer',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ServiceRoom::class, 'room_id');
    }
}
