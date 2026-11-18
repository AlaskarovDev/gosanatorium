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
        'badge_text',
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

    protected static function booted(): void
    {
        $forget = fn () => \Illuminate\Support\Facades\Cache::forget('announcement_bar_' . static::today()->toDateString());
        static::saved($forget);
        static::deleted($forget);
    }

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
    /**
     * Promotions are dated in Baku time; end date is inclusive.
     */
    public static function today(): \Carbon\Carbon
    {
        return now('Asia/Baku')->startOfDay();
    }

    public function scopeVisible($query)
    {
        $today = static::today()->toDateString();

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

    /**
     * Whole days left until the end date (0 = last day), null when there is no end date.
     */
    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->valid_until) {
            return null;
        }
        $end = \Carbon\Carbon::parse($this->valid_until->toDateString(), 'Asia/Baku')->startOfDay();

        return (int) static::today()->diffInDays($end, false);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->days_left !== null && $this->days_left < 0;
    }

    /**
     * «Осталось N дней» / «Последний день акции» (null when no end date or expired).
     */
    public function getCountdownLabelAttribute(): ?string
    {
        $d = $this->days_left;
        if ($d === null || $d < 0) {
            return null;
        }
        if ($d === 0) {
            return 'Последний день акции';
        }
        $n = $d + 1; // end date is inclusive: today + N remaining days
        $m10 = $n % 10;
        $m100 = $n % 100;
        $word = ($m100 >= 11 && $m100 <= 14) ? 'дней' : ($m10 === 1 ? 'день' : (($m10 >= 2 && $m10 <= 4) ? 'дня' : 'дней'));

        return 'Осталось ' . $n . ' ' . $word;
    }

    /**
     * Card text: plain text cut at a word boundary.
     */
    public function shortText(int $limit = 110): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $this->description), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        $cut = mb_substr($text, 0, $limit);
        $space = mb_strrpos($cut, ' ');
        if ($space !== false && $space > $limit * 0.6) {
            $cut = mb_substr($cut, 0, $space);
        }

        return rtrim($cut, " ,.;:—-•") . '…';
    }

    /**
     * Short label for the top announcement bar, e.g. «Парк Нафталан — скидка 15%».
     */
    public function getBannerLabelAttribute(): string
    {
        $name = trim(preg_replace('/\s*\(.*?\)\s*/u', ' ', (string) ($this->sanatorium_name ?: $this->service?->translation?->title)));
        $pct = $this->discount_label;
        if ($name !== '' && $pct) {
            return $name . ' — скидка ' . $pct . '%';
        }

        return (string) $this->title;
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

    public function getDetailUrlAttribute(): string
    {
        return $this->slug ? route('akcii.show', ['slug' => $this->slug]) : $this->url;
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
