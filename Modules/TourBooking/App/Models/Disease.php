<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Disease extends Model
{
    protected $fillable = [
        'slug',
        'name_ru',
        'name_az',
        'name_en',
        'image',
        'description_ru',
        'description_az',
        'description_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function sanatoriums(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'disease_sanatorium', 'disease_id', 'sanatorium_id')
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Language of the current page (URL prefix: ru / az / en); other languages fall back to Russian.
     */
    private static function pageLang(): string
    {
        $lang = (string) (request()->attributes->get('display_lang') ?: app()->getLocale());

        return in_array($lang, ['az', 'en'], true) ? $lang : 'ru';
    }

    /**
     * Front-end string in the language of the current page (ru / az / en) from the translate language files.
     */
    public static function t(string $key): string
    {
        $lang = self::pageLang();
        $text = trans('translate.' . $key, [], $lang);

        return $text === 'translate.' . $key ? trans('translate.' . $key, [], 'ru') : $text;
    }

    private function localized(string $field): ?string
    {
        $value = $this->{$field . '_' . self::pageLang()};

        return filled($value) ? $value : $this->{$field . '_ru'};
    }

    public function getNameAttribute(): string
    {
        return (string) $this->localized('name');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localized('description');
    }

    public function getImageUrlAttribute(): string
    {
        return asset($this->image ?: 'images/diseases/placeholder.svg');
    }
}
