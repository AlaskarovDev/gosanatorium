<div class="gscard-section gscard-listing" style="background:transparent">
<div class="gscard-grid">
@foreach ($allServices as $service)
                @php
                    $gsUrl = route('front.tourbooking.services.show', ['slug' => $service?->slug]);
                    $gsCount = (int) ($service?->active_reviews_count ?? 0);
                    $gsRating = (float) ($service?->active_reviews_avg_rating ?? 0);
                    $gsRaw = $service->is_per_person
                        ? $service->price_per_person
                        : ($service->discount_price ?: $service->full_price);
                @endphp
                <div class="gscard-item type-{{ $service->service_type_id }}">
                    <div class="gscard-card">
                        <div class="gscard-media">
                            <a href="{{ $gsUrl }}">
                                <img class="gscard-img"
                                    src="{{ asset($service?->thumbnail?->file_path) }}"
                                    alt="{{ $service?->thumbnail?->caption ?? $service?->translation?->title }}">
                            </a>
                            @include('tourbooking::front.services.badges', ['service' => $service, 'class' => 'gs-sbadges--overlay', 'limit' => 3])
                            <div @class([
                                'tg-listing-item-wishlist',
                                'gscard-fav',
                                'active' => $service?->my_wishlist_exists == 1,
                            ]) data-url="{{ route('user.wishlist.store') }}"
                                onclick="addToWishlist({{ $service->id }}, this, 'service')">
                                <a href="javascript:void(0)">
                                    <svg width="20" height="18" viewBox="0 0 20 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.5167 16.3416C10.2334 16.4416 9.76675 16.4416 9.48341 16.3416C7.06675 15.5166 1.66675 12.075 1.66675 6.24165C1.66675 3.66665 3.74175 1.58331 6.30008 1.58331C7.81675 1.58331 9.15841 2.31665 10.0001 3.44998C10.8417 2.31665 12.1917 1.58331 13.7001 1.58331C16.2584 1.58331 18.3334 3.66665 18.3334 6.24165C18.3334 12.075 12.9334 15.5166 10.5167 16.3416Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="gscard-body">
                            <div class="gscard-rating">
                                @if ($gsCount > 0)
                                    <span class="gscard-stars"><i class="fa-solid fa-star"></i></span>
                                    <strong class="gscard-rating-num">{{ number_format($gsRating, 1) }}</strong>
                                    <span class="gscard-reviews">({{ gscardReviewsLabel($gsCount) }})</span>
                                @else
                                    <span class="gscard-reviews">{{ app()->getLocale() === 'ru' ? 'Нет отзывов' : 'No reviews' }}</span>
                                @endif
                            </div>
                            <h4 class="gscard-title">
                                <a href="{{ $gsUrl }}">{{ $service?->translation?->title }}</a>
                            </h4>
                            @if ($gsAddr = gscardAddress($service))
                                <div class="gscard-address">
                                    <i class="fa-solid fa-location-dot"></i> {{ $gsAddr }}
                                </div>
                            @endif
                            <div class="gs-compare-wrap"><button type="button" class="gs-compare-btn" data-slug="{{ $service->slug }}" data-title="{{ $service?->translation?->title }}" aria-pressed="false"><span class="gs-compare-box"></span><span class="gs-compare-label">Сравнить</span></button></div>
                            <div class="gscard-footer">
                                <div class="gscard-price-wrap">
                                    <div class="gscard-price">{{ app()->getLocale() === 'ru' ? 'от' : 'from' }} {!! currency_span($gsRaw) !!}</div>
                                    <div class="gscard-per">{{ app()->getLocale() === 'ru' ? 'за ночь' : 'per night' }}</div>
                                </div>
                                <a class="gscard-cta" href="{{ $gsUrl }}">{{ app()->getLocale() === 'ru' ? 'Подробнее' : 'Details' }} &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
</div>
</div>
@push('style_section')
<style>
        .gscard-grid { display: grid !important; grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 20px !important; width: 100% !important; max-width: 100% !important; height: auto !important; position: static !important; }
        
        .gscard-item { position: static !important; float: none !important; left: auto !important; top: auto !important; }
        .gscard-item { display: flex !important; min-width: 0 !important; width: 100% !important; }
        .gscard-item.gscard-hidden { display: none !important; }
        .gscard-card { height: 100% !important; display: flex !important; flex-direction: column !important; width: 100% !important; background: #fff !important; border-radius: 16px !important; box-shadow: 0 2px 16px rgba(0,0,0,0.08) !important; border: 1px solid #f0f4f8 !important; overflow: hidden !important; padding: 0 !important; transition: all .25s ease !important; }
        .gscard-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.14) !important; transform: translateY(-4px) !important; }
        .gscard-media { position: relative !important; height: 220px !important; width: 100% !important; overflow: hidden !important; }
        .gscard-media > a { display: block !important; height: 100% !important; }
        .gscard-img { width: 100% !important; height: 220px !important; object-fit: cover !important; display: block !important; }
        .gscard-badges { position: absolute !important; top: 12px !important; left: 12px !important; display: flex !important; flex-direction: column !important; align-items: flex-start !important; gap: 8px !important; max-width: calc(100% - 64px) !important; z-index: 2 !important; }
        .gscard-badge { background: #fff !important; color: #1a9e87 !important; border-radius: 20px !important; padding: 4px 10px !important; font-size: 12px !important; font-weight: 600 !important; line-height: 1.4 !important; text-transform: none !important; max-width: 100% !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; }
        .gscard-fav { position: absolute !important; top: 12px !important; right: 12px !important; width: 34px !important; height: 34px !important; border-radius: 50% !important; background: #fff !important; display: flex !important; align-items: center !important; justify-content: center !important; color: #94a3b8 !important; z-index: 3 !important; box-shadow: 0 1px 6px rgba(0,0,0,0.12) !important; cursor: pointer !important; }
        .gscard-fav a { color: inherit !important; display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important; height: 100% !important; }
        .gscard-fav:hover, .gscard-fav.active { color: #ef4444 !important; }
        .gscard-fav.active svg path, .gscard-fav:hover svg path { fill: #ef4444 !important; }
        .gscard-body { padding: 16px !important; display: flex !important; flex-direction: column !important; flex: 1 !important; }
        .gscard-rating { display: flex !important; align-items: center !important; gap: 6px !important; margin-bottom: 6px !important; }
        .gscard-stars { color: #f59e0b !important; font-size: 14px !important; }
        .gscard-rating-num { font-weight: 700 !important; font-size: 14px !important; color: #1a2540 !important; }
        .gscard-reviews { color: #94a3b8 !important; font-size: 13px !important; }
        .gscard-title { font-size: 16px !important; font-weight: 700 !important; color: #1a2540 !important; margin: 0 0 6px !important; line-height: 1.35 !important; text-transform: none !important; display: -webkit-box !important; -webkit-line-clamp: 2 !important; -webkit-box-orient: vertical !important; overflow: hidden !important; text-overflow: ellipsis !important; min-height: 43px !important; }
        .gscard-title a { color: inherit !important; text-transform: none !important; }
        .gscard-address { font-size: 13px !important; color: #64748b !important; margin-bottom: 14px !important; text-transform: none !important; }
        .gscard-address i { color: #1a9e87 !important; margin-right: 4px !important; }
        .gscard-footer { margin-top: auto !important; border-top: 1px solid #f0f4f8 !important; padding-top: 12px !important; display: flex !important; align-items: center !important; justify-content: space-between !important; gap: 8px !important; }
        .gscard-price { color: #1a9e87 !important; font-size: 20px !important; font-weight: 800 !important; line-height: 1.2 !important; background: none !important; text-transform: none !important; }
        .gscard-per { color: #94a3b8 !important; font-size: 12px !important; }
        .gscard-cta { background: #1a9e87 !important; color: #fff !important; border-radius: 8px !important; padding: 8px 16px !important; font-size: 13px !important; font-weight: 600 !important; white-space: nowrap !important; text-transform: none !important; }
        .gscard-cta:hover { background: #158573 !important; color: #fff !important; }
        @media (max-width: 1200px) { .gscard-grid { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; } }
        @media (max-width: 768px) { .gscard-grid { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; } }
        @media (max-width: 480px) { .gscard-grid { grid-template-columns: repeat(1, minmax(0, 1fr)) !important; } }
</style>
@endpush
@push('js_section')
<script src="{{ asset('frontend/assets/js/cart.js') }}?v={{ @filemtime(public_path('frontend/assets/js/cart.js')) }}"></script>
@endpush
