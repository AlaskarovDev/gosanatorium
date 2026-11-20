{{-- Task 26: «Нас оценивают» — Google / Yandex rating badges (Admin → Company facts) + link to /otzyvy.
     $rbVariant: 'section' (homepage) | 'footer'. Hidden when no rating is entered and there are no company reviews. --}}
@php
    $rbVariant = $rbVariant ?? 'section';
    $rbBadges = [];
    foreach (['google' => 'Google', 'yandex' => 'Яндекс Карты'] as $rbKey => $rbName) {
        $rbRating = str_replace(',', '.', company_fact($rbKey . '_rating'));
        if (is_numeric($rbRating) && (float) $rbRating > 0) {
            $rbCount = (int) company_fact($rbKey . '_reviews');
            $rbUrl = company_fact($rbKey . '_url');
            $rbBadges[] = [
                'key' => $rbKey,
                'name' => $rbName,
                'rating' => number_format((float) $rbRating, 1, '.', ''),
                'count' => $rbCount > 0 ? $rbCount . ' ' . trans_choice('отзыв|отзыва|отзывов', $rbCount, [], 'ru') : null,
                'url' => preg_match('#^https?://#i', $rbUrl) ? $rbUrl : null,
            ];
        }
    }
    try {
        $rbOwn = \Illuminate\Support\Facades\Schema::hasTable('company_reviews')
            ? \App\Models\CompanyReview::approved()->selectRaw('COUNT(*) c, AVG(rating) a')->first() : null;
    } catch (\Throwable) {
        $rbOwn = null;
    }
    $rbOwnCount = (int) ($rbOwn->c ?? 0);
@endphp
@if (count($rbBadges) || $rbOwnCount > 0)
    <div @class(['gs-rated', 'gs-rated--footer' => $rbVariant === 'footer'])>
        @if ($rbVariant !== 'footer')<h2 class="gs-rated__title">Нас оценивают</h2>@else<p class="gs-rated__ftitle">Нас оценивают</p>@endif
        <div class="gs-rated__list">
            @foreach ($rbBadges as $b)
                <{{ $b['url'] ? 'a' : 'div' }} class="gs-rated__badge gs-rated__badge--{{ $b['key'] }}" @if ($b['url']) href="{{ $b['url'] }}" target="_blank" rel="noopener" @endif>
                    <span class="gs-rated__src">{{ $b['name'] }}</span>
                    <span class="gs-rated__score"><span class="gs-rated__star" aria-hidden="true">★</span> {{ $b['rating'] }}</span>
                    @if ($b['count'])<span class="gs-rated__count">{{ $b['count'] }}</span>@endif
                </{{ $b['url'] ? 'a' : 'div' }}>
            @endforeach
            <a class="gs-rated__badge gs-rated__badge--own" href="{{ localize_front_url('/otzyvy') }}">
                <span class="gs-rated__src">Отзывы о компании</span>
                @if ($rbOwnCount > 0)
                    <span class="gs-rated__score"><span class="gs-rated__star" aria-hidden="true">★</span> {{ number_format((float) $rbOwn->a, 1, '.', '') }}</span>
                    <span class="gs-rated__count">{{ $rbOwnCount }} {{ trans_choice('отзыв|отзыва|отзывов', $rbOwnCount, [], 'ru') }}</span>
                @else
                    <span class="gs-rated__count">Оставить отзыв →</span>
                @endif
            </a>
        </div>
    </div>
    @once
    <style>
        .gs-rated { max-width: 1200px; margin: 0 auto; padding: 40px 16px; text-align: center; }
        .gs-rated__title { font-size: 28px; font-weight: 800; color: #1a2540; margin: 0 0 20px; }
        .gs-rated__list { display: flex; flex-wrap: wrap; justify-content: center; gap: 14px; }
        .gs-rated__badge { display: flex; flex-direction: column; align-items: center; gap: 2px; min-width: 170px; padding: 14px 20px; border-radius: 14px; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,.05); color: #1a2540; text-decoration: none; }
        a.gs-rated__badge:hover { border-color: #1a9e87; color: #1a2540; }
        .gs-rated__src { font-size: 13px; color: #64748b; font-weight: 600; }
        .gs-rated__score { font-size: 24px; font-weight: 800; }
        .gs-rated__star { color: #f59e0b; }
        .gs-rated__count { font-size: 13px; color: #475569; }
        .gs-rated--footer { padding: 0; margin-top: 22px; text-align: left; }
        .gs-rated__ftitle { font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,.4); margin: 0 0 10px; }
        .gs-rated--footer .gs-rated__list { justify-content: flex-start; gap: 8px; }
        .gs-rated--footer .gs-rated__badge { min-width: 0; flex-direction: row; gap: 8px; padding: 6px 12px; border-radius: 20px; background: rgba(255,255,255,.07); border-color: rgba(255,255,255,.12); box-shadow: none; color: #fff; }
        .gs-rated--footer .gs-rated__src, .gs-rated--footer .gs-rated__count { color: rgba(255,255,255,.7); font-size: 12px; }
        .gs-rated--footer .gs-rated__score { font-size: 14px; }
    </style>
    @endonce
@endif
