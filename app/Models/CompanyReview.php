<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Task 26: review about GoSanatorium (page /otzyvy). */
class CompanyReview extends Model
{
    public const STATUSES = ['pending' => 'На модерации', 'approved' => 'Опубликован', 'rejected' => 'Отклонён'];

    protected $fillable = ['name', 'country', 'rating', 'text', 'booking_code', 'is_verified', 'status', 'reply', 'ip'];

    protected $casts = ['rating' => 'integer', 'is_verified' => 'boolean'];

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', 'approved');
    }
}
