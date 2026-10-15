<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ServiceSlugHistory extends Model
{
    protected $fillable = ['service_id', 'slug'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
