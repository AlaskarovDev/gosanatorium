<?php

declare(strict_types=1);

namespace Modules\TourBooking\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'user_id',
        'booking_id',
        'review',
        'rating',
        'rating_attributes',
        'status',
        'is_featured',
        'review_title',
        'review_ru',
        'country',
        'city',
        'language',
        'stay_month',
        'booking_reference',
        'is_verified',
    ];

    /**
     * Sub-score keys (as stored in rating_attributes) => Russian labels.
     */
    public const SUB_SCORE_LABELS = [
        'Location' => 'Расположение',
        'Price' => 'Цена/качество',
        'Amenities' => 'Удобства',
        'Rooms' => 'Номера',
        'Services' => 'Обслуживание',
    ];

    /**
     * Review languages offered in admin.
     */
    public const LANGUAGES = [
        'ru' => 'Русский',
        'en' => 'English',
        'az' => 'Azərbaycan',
        'tr' => 'Türkçe',
        'de' => 'Deutsch',
        'uk' => 'Українська',
        'uz' => 'Oʻzbek',
        'kk' => 'Қазақ',
        'he' => 'עברית',
        'ar' => 'العربية',
        'other' => 'Другой',
    ];

    /**
     * Country names commonly stored in English => Russian.
     */
    private const COUNTRY_RU = [
        'russia' => 'Россия', 'azerbaijan' => 'Азербайджан', 'kazakhstan' => 'Казахстан',
        'uzbekistan' => 'Узбекистан', 'belarus' => 'Беларусь', 'ukraine' => 'Украина',
        'turkey' => 'Турция', 'türkiye' => 'Турция', 'germany' => 'Германия', 'israel' => 'Израиль',
        'georgia' => 'Грузия', 'kyrgyzstan' => 'Кыргызстан', 'tajikistan' => 'Таджикистан',
        'turkmenistan' => 'Туркменистан', 'armenia' => 'Армения', 'moldova' => 'Молдова',
        'latvia' => 'Латвия', 'lithuania' => 'Литва', 'estonia' => 'Эстония', 'poland' => 'Польша',
        'usa' => 'США', 'united states' => 'США', 'uk' => 'Великобритания', 'united kingdom' => 'Великобритания',
        'uae' => 'ОАЭ', 'united arab emirates' => 'ОАЭ', 'saudi arabia' => 'Саудовская Аравия',
        'iran' => 'Иран', 'france' => 'Франция', 'italy' => 'Италия', 'spain' => 'Испания',
        'netherlands' => 'Нидерланды', 'austria' => 'Австрия', 'switzerland' => 'Швейцария',
        'czech republic' => 'Чехия', 'finland' => 'Финляндия', 'sweden' => 'Швеция', 'norway' => 'Норвегия',
        'china' => 'Китай', 'india' => 'Индия', 'pakistan' => 'Пакистан', 'kuwait' => 'Кувейт',
        'qatar' => 'Катар', 'oman' => 'Оман', 'bahrain' => 'Бахрейн',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'decimal:1',
        'rating_attributes' => 'array',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the service that this review belongs to.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the user who left this review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking that this review is for.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Approved reviews scope.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', true);
    }

    /**
     * Featured reviews scope.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Reviews with high ratings scope.
     */
    public function scopeHighRated($query, float $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Name shown on the site.
     */
    public function displayName(): string
    {
        return trim((string) html_decode($this->user?->name ?? '')) ?: 'Гость';
    }

    /**
     * Initials for the avatar circle, e.g. «Сергей Попов» → «СП», «Svetlana K.» → «SK».
     */
    public function initials(): string
    {
        $parts = preg_split('/[\s\-]+/u', $this->displayName(), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $letters = '';
        foreach ($parts as $part) {
            $first = mb_substr(preg_replace('/[^\p{L}]/u', '', $part), 0, 1);
            if ($first !== '') {
                $letters .= mb_strtoupper($first);
            }
            if (mb_strlen($letters) >= 2) {
                break;
            }
        }

        return $letters !== '' ? $letters : 'Г';
    }

    /**
     * Uploaded avatar of the reviewer, null when it is missing or a placeholder.
     */
    public function avatarUrl(): ?string
    {
        $image = $this->user?->image;
        if (!is_string($image) || trim($image) === '' || str_contains($image, 'placeholder')) {
            return null;
        }

        return asset($image);
    }

    /**
     * Country (review field first, then the user's profile), in Russian when known.
     */
    public function displayCountry(): ?string
    {
        $country = trim((string) ($this->country ?: ($this->user?->country ?? '')));
        if ($country === '') {
            return null;
        }

        return self::COUNTRY_RU[mb_strtolower($country)] ?? $country;
    }

    /**
     * «Город, Страна» line for the review header.
     */
    public function displayPlace(): ?string
    {
        $place = array_filter([trim((string) $this->city), $this->displayCountry()]);

        return $place ? implode(', ', $place) : null;
    }

    /**
     * «Отдыхал(а): март 2026» from stay_month (YYYY-MM).
     */
    public function stayMonthLabel(): ?string
    {
        if (!$this->stay_month || !preg_match('/^(\d{4})-(\d{2})$/', $this->stay_month, $m)) {
            return null;
        }
        $months = [1 => 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
        $month = (int) $m[2];

        return isset($months[$month]) ? $months[$month] . ' ' . $m[1] : null;
    }

    /**
     * True when a Russian translation exists for a non-Russian original.
     */
    public function hasTranslation(): bool
    {
        return trim((string) $this->review_ru) !== ''
            && $this->language !== 'ru'
            && trim((string) $this->review_ru) !== trim((string) $this->review);
    }

    /**
     * Text shown by default: the Russian translation when there is one, otherwise the original.
     */
    public function displayText(): string
    {
        return $this->hasTranslation() ? (string) $this->review_ru : (string) $this->review;
    }

    /**
     * Sub-scores actually entered for this review: [['label' => 'Расположение', 'rating' => 5.0], ...].
     */
    public function subScores(): array
    {
        $out = [];
        foreach ((array) ($this->rating_attributes ?? []) as $attr) {
            $category = $attr['category'] ?? null;
            $rating = $attr['rating'] ?? null;
            if (!$category || $rating === null || $rating === '' || (float) $rating <= 0) {
                continue;
            }
            $out[] = ['label' => self::subScoreLabel($category), 'rating' => (float) $rating];
        }

        return $out;
    }

    public static function subScoreLabel(string $category): string
    {
        return self::SUB_SCORE_LABELS[$category] ?? $category;
    }

    /**
     * «1 отзыв», «3 отзыва», «5 отзывов».
     */
    public static function countLabel(int $n): string
    {
        $mod10 = $n % 10;
        $mod100 = $n % 100;
        $word = ($mod10 === 1 && $mod100 !== 11) ? 'отзыв'
            : (($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14)) ? 'отзыва' : 'отзывов');

        return $n . ' ' . $word;
    }
}
