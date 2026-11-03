<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akcii extends Model
{
    protected $table = 'akcii';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'discount_percent',
        'valid_until',
        'sanatorium_name',
        'service_id',
        'original_price',
        'discounted_price',
        'valid_from',
        'is_hot',
        'is_active',
        'sort_order',
        'lang',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'valid_from' => 'date',
        'is_hot' => 'boolean',
        'original_price' => 'float',
        'discounted_price' => 'float',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function service()
    {
        return $this->belongsTo(\Modules\TourBooking\App\Models\Service::class, 'service_id');
    }

    /**
     * Active, started and not expired promotions; soonest expiring first.
     */
    public function scopeVisible($query)
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', $today);
            })
            ->orderByRaw('valid_until IS NULL')
            ->orderBy('valid_until')
            ->orderBy('sort_order');
    }

    public function getDiscountLabelAttribute(): ?int
    {
        if ($this->discount_percent) {
            return (int) $this->discount_percent;
        }
        if ($this->original_price > 0 && $this->discounted_price > 0 && $this->discounted_price < $this->original_price) {
            return (int) round((1 - $this->discounted_price / $this->original_price) * 100);
        }

        return null;
    }

    public function getUrlAttribute(): string
    {
        if ($this->service && $this->service->slug) {
            return route('front.tourbooking.services.show', ['slug' => $this->service->slug]);
        }

        return route('front.tourbooking.services');
    }

    /**
     * Pad the list with non-persisted seasonal placeholders up to $min cards.
     */
    public static function withPlaceholders($items, int $min = 4)
    {
        $items = collect($items)->values();
        $year = now()->year;
        $seasons = [
            'Зимний сезон ' . $year . ' — специальные условия',
            'Весенний сезон ' . ($year + 1) . ' — специальные условия',
            'Летний сезон ' . ($year + 1) . ' — специальные условия',
            'Осенний сезон ' . ($year + 1) . ' — специальные условия',
            'Новогодние каникулы — специальные условия',
            'Раннее бронирование — специальные условия',
        ];
        $i = 0;
        while ($items->count() < $min) {
            $p = new self(['title' => $seasons[$i % count($seasons)], 'description' => 'Уточняйте актуальные цены и скидки у наших консультантов.']);
            $p->is_placeholder = true;
            $items->push($p);
            $i++;
        }

        return $items;
    }
}
