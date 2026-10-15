<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class ServiceTreatmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'title',
        'image',
        'display_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}