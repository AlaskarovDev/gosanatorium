@forelse($rooms as $room)
    @php
        $roomName = $room->name ?? $room->room_name ?? 'Номер';
        $baseCurrentPrice = $room->discount_price ?? $room->price_per_night ?? 0;
        $currentPrice = method_exists($room, 'priceForDate') ? ($room->current_price_per_night ?? $baseCurrentPrice) : $baseCurrentPrice;
        $originalPrice = $room->price_per_night ?? 0;
        $hasDiscount = $room->discount_price && $room->discount_price < $room->price_per_night;
        $discountPercent = $hasDiscount ? round((($originalPrice - $currentPrice) / $originalPrice) * 100) : 0;
        
        $features = [];
        if (!empty($room->features)) {
            if (is_array($room->features)) {
                $features = $room->features;
            } elseif (is_string($room->features)) {
                $decoded = json_decode($room->features, true);
                $features = is_array($decoded) ? $decoded : [];
            }
        }
        
        $thumbnailUrl = null;
        if (!empty($room->thumbnail)) {
            $thumbnailUrl = $room->thumbnail;
        }
        if (!$thumbnailUrl && isset($room->media) && $room->media->count() > 0) {
            $thumbMedia = $room->media->where('is_thumbnail', true)->first();
            if ($thumbMedia && !empty($thumbMedia->file_path)) {
                $thumbnailUrl = $thumbMedia->file_path;
            } else {
                $firstImage = $room->media->where('media_type', 'image')->first();
                if ($firstImage && !empty($firstImage->file_path)) {
                    $thumbnailUrl = $firstImage->file_path;
                }
            }
        }
        if (!$thumbnailUrl && isset($room->gallery) && $room->gallery->count() > 0) {
            $firstGallery = $room->gallery->first();
            if ($firstGallery && !empty($firstGallery->image)) {
                $thumbnailUrl = $firstGallery->image;
            }
        }
        if ($thumbnailUrl) {
            if (!str_starts_with($thumbnailUrl, 'http://') && !str_starts_with($thumbnailUrl, 'https://')) {
                $thumbnailUrl = ltrim($thumbnailUrl, '/');
                $thumbnailUrl = asset($thumbnailUrl);
            }
        }
        
        $imageCount = 0;
        if (isset($room->media)) {
            $imageCount = $room->media->where('media_type', 'image')->count();
        } elseif (isset($room->gallery)) {
            $imageCount = $room->gallery->count();
        }
        if ($thumbnailUrl) $imageCount = max($imageCount, 1);
        
        $roomSize = $room->room_size ?? 0;
        $maxGuests = $room->max_guests ?? 2;
        $bedType = $room->bed_type ?? '';
        $quantity = $room->quantity ?? 0;
        
        $descriptionLength = mb_strlen($room->description ?? '');
        $hasLongDesc = $descriptionLength > 100;
        $hasMoreFeatures = count($features) > 4;
        $needsExpand = $hasLongDesc || $hasMoreFeatures;
        
        $guestWord = function($n) {
            if ($n == 1) return 'гость';
            if ($n >= 2 && $n <= 4) return 'гостя';
            return 'гостей';
        };
        $roomWord = function($n) {
            if ($n == 1) return 'номер';
            if ($n >= 2 && $n <= 4) return 'номера';
            return 'номеров';
        };
    @endphp
    
    <div class="room-card" 
         data-price="{{ $currentPrice }}" 
         data-guests="{{ $maxGuests }}" 
         data-size="{{ $roomSize }}"
         data-bed="{{ $bedType }}">
        <div class="room-card-inner">
            <!-- Изображение -->
            <div class="room-image-col">
                @php
                    $allImages = [];
                    if ($thumbnailUrl) {
                        $allImages[] = $thumbnailUrl;
                    }
                    if (isset($room->media)) {
                        foreach ($room->media->where('media_type', 'image') as $media) {
                            $imgUrl = $media->file_path;
                            if (!str_starts_with($imgUrl, 'http')) {
                                $imgUrl = asset(ltrim($imgUrl, '/'));
                            }
                            if (!in_array($imgUrl, $allImages)) {
                                $allImages[] = $imgUrl;
                            }
                        }
                    }
                @endphp
                
                @if($thumbnailUrl)
                    <a href="{{ $thumbnailUrl }}" 
                       class="room-lightbox-trigger" 
                       data-room-id="{{ $room->id }}"
                       data-room-name="{{ $roomName }}"
                       data-video-url="{{ $room->video_url ?? '' }}"
                       data-images='@json($allImages)'>
                        <img src="{{ $thumbnailUrl }}" alt="{{ $roomName }}" loading="lazy"
                             onerror="this.onerror=null; this.closest('.room-image-col').innerHTML='<div class=\'room-image-placeholder\'><i class=\'fa-solid fa-image\'></i></div>';">
                        <div class="image-overlay">
                            <i class="fa-solid fa-expand"></i>
                        </div>
                    </a>
                @else
                    <div class="room-image-placeholder">
                        <i class="fa-solid fa-image"></i>
                    </div>
                @endif
                
                @if($thumbnailUrl && count($allImages) > 1)
                    <div class="rc-counter"><span class="rc-counter-cur">1</span> / {{ count($allImages) }}</div>
                    <div class="rc-thumbs">
                        @foreach($allImages as $ti => $tu)
                            <button type="button" class="rc-thumb {{ $ti === 0 ? 'active' : '' }}" aria-label="{{ $ti + 1 }}" onclick="rcGalleryGo(this, {{ $ti }})"><img src="{{ $tu }}" alt="" loading="lazy"></button>
                        @endforeach
                    </div>
                    <button type="button" class="rc-gal-btn rc-gal-prev" aria-label="Назад" onclick="rcGallery(this, -1)"><i class="fa-solid fa-chevron-left"></i></button>
                    <button type="button" class="rc-gal-btn rc-gal-next" aria-label="Вперёд" onclick="rcGallery(this, 1)"><i class="fa-solid fa-chevron-right"></i></button>
                @endif

                {{-- Mobile: Discount badge on image --}}
                @if($hasDiscount)
                    <div class="mobile-discount-badge">
                        -{{ $discountPercent }}%
                    </div>
                @endif
                
                @if($imageCount > 0 && count($allImages) < 2)
                    <div class="image-badge">
                        <i class="fa-solid fa-camera me-1"></i>{{ $imageCount }}
                    </div>
                @endif
                
                @if(!empty($room->video_url))
                    <div class="video-badge">
                        <i class="fa-solid fa-play"></i>
                    </div>
                @endif
            </div>
            
            <!-- Детали номера -->
            <div class="room-details-col">
                <div class="rc-title-row">
                    <h3 class="room-name">{{ $roomName }}</h3>
                    @if($quantity === 1)
                        <span class="rc-badge rc-badge--last">Последний номер</span>
                    @elseif($hasDiscount)
                        <span class="rc-badge rc-badge--popular">Популярный</span>
                    @endif
                </div>
                
                <div class="room-specs">
                    @if($roomSize)
                        <span class="spec-badge">
                            <i class="fa-solid fa-ruler-combined"></i>{{ $roomSize }} м²
                        </span>
                    @endif
                    @if($bedType)
                        <span class="spec-badge">
                            <i class="fa-solid fa-bed"></i>{{ $bedType }}
                        </span>
                    @endif
                    @if($maxGuests)
                        <span class="spec-badge">
                            <i class="fa-solid fa-users"></i>{{ $maxGuests }} {{ $guestWord($maxGuests) }}
                        </span>
                    @endif
                </div>
                
                @if(!empty($room->description))
                    <div class="room-desc-wrap">
                        <p class="room-description room-desc--preview">{{ Str::limit($room->description, 100) }}</p>
                        @if($hasLongDesc)
                            <div class="room-description room-desc--full">{{ $room->description }}</div>
                        @endif
                    </div>
                @endif
                
                @php
                    $rcIcon = function ($t) {
                        $t = mb_strtolower($t);
                        $map = [
                            ['кондиц|conditi|\bac\b|air', 'fa-snowflake'],
                            ['телевиз|tv|\bтв\b', 'fa-tv'],
                            ['wi-?fi|вай|интернет|internet', 'fa-wifi'],
                            ['душ|shower|ванн|bath|санузел|туалет', 'fa-shower'],
                            ['холодил|fridge|минибар|mini', 'fa-temperature-low'],
                            ['фен|hair', 'fa-wind'],
                            ['чайник|кофе|kettle|coffee', 'fa-mug-hot'],
                            ['сейф|safe', 'fa-lock'],
                            ['балкон|balcon|вид|view', 'fa-mountain-sun'],
                            ['телефон|phone', 'fa-phone'],
                            ['завтрак|питани|breakfast|meal', 'fa-utensils'],
                            ['полотен|халат|towel|тапочки', 'fa-bath'],
                            ['отопл|heat', 'fa-fire'],
                        ];
                        foreach ($map as [$re, $ic]) {
                            if (preg_match('/' . $re . '/u', $t)) return $ic;
                        }
                        return 'fa-circle-check';
                    };
                    $cleanFeatures = array_values(array_filter(array_map(fn($f) => is_string($f) ? trim($f) : '', $features)));
                @endphp
                @if(count($cleanFeatures) > 0)
                    <div class="rc-amenities">
                        @foreach(array_slice($cleanFeatures, 0, 5) as $feature)
                            <div class="rc-amenity" title="{{ $feature }}">
                                <i class="fa-solid {{ $rcIcon($feature) }}"></i>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($needsExpand)
                    <button type="button" class="room-toggle-btn" onclick="toggleRoomDetails(this)">
                        <span class="room-toggle-btn__label">Подробнее</span>
                        <i class="fa-solid fa-chevron-down room-toggle-btn__arrow"></i>
                    </button>
                    <div class="rc-more">
                        <div class="rc-more-inner">
                            @if($hasLongDesc)
                                <p class="rc-more-text">{{ $room->description }}</p>
                            @endif
                            @if(count($cleanFeatures) > 5)
                                <div class="rc-more-title">Дополнительные удобства</div>
                                <ul class="rc-more-list">
                                    @foreach(array_slice($cleanFeatures, 5) as $feature)
                                        <li><i class="fa-solid {{ $rcIcon($feature) }}"></i>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @if(!empty($service->check_in_time) || !empty($service->check_out_time))
                                <div class="rc-more-title">Правила заселения</div>
                                <ul class="rc-more-list">
                                    @if(!empty($service->check_in_time))<li><i class="fa-solid fa-right-to-bracket"></i>Заезд: {{ $service->check_in_time }}</li>@endif
                                    @if(!empty($service->check_out_time))<li><i class="fa-solid fa-right-from-bracket"></i>Выезд: {{ $service->check_out_time }}</li>@endif
                                </ul>
                            @endif
                        </div>
                    </div>
                @endif

            <!-- Цена -->
            <div class="room-price-col">
                <div class="rc-price-line">
                @if($hasDiscount)
                    <div class="discount-tag">
                        <i class="fa-solid fa-tag me-1"></i>{{ $discountPercent }}% скидка
                    </div>
                    <div class="old-price">{{ currency($originalPrice) }}</div>
                @endif
                
                <div class="current-price">{{ currency($currentPrice) }}</div>
                <div class="price-note">за ночь</div>
                </div>
                
                @php
                    // Nişanın görünməsi və mətni admin paneldən idarə olunur.
                    $lowStockShow = (bool) ($room->low_stock_show ?? false);
                    $lowStockText = trim((string) ($room->low_stock_text ?? ''));
                @endphp
                @if($lowStockShow && $quantity > 0)
                    <div class="low-stock-alert">
                        <i class="fa-solid fa-exclamation-triangle me-1"></i>
                        {{ $lowStockText !== '' ? $lowStockText : 'Осталось только ' . $quantity . ' ' . $roomWord($quantity) . '!' }}
                    </div>
                @endif
                
                @php
                    $roomBasePriceFor = $room->base_price_for ?? $maxGuests ?? 2;
                    if ($room->relationLoaded('activeExtras')) {
                        $roomExtrasCol = $room->activeExtras;
                    } elseif ($room->relationLoaded('extras')) {
                        $roomExtrasCol = $room->extras->where('status', true);
                    } elseif (method_exists($room, 'extras')) {
                        $roomExtrasCol = $room->extras()->where('status', true)->orderBy('sort_order')->get();
                    } else {
                        $roomExtrasCol = collect();
                    }
                    $roomExtrasJson = json_encode(
                        $roomExtrasCol->map(fn($x) => [
                            'id' => $x->id,
                            'name' => $x->name,
                            'type' => $x->type,
                            'price' => (float) $x->price,
                        ])->values(),
                        JSON_HEX_APOS | JSON_HEX_QUOT
                    );
                @endphp
                @if($quantity > 0)
                    <button type="button"
                            class="select-room-btn"
                            data-room-id="{{ $room->id }}"
                            data-room-name="{{ $roomName }}"
                            data-room-price="{{ $currentPrice }}"
                            data-max-guests="{{ $maxGuests }}"
                            data-base-price-for="{{ $roomBasePriceFor }}"
                            data-extras='{!! $roomExtrasJson !!}'
                            data-room-thumb="{{ $thumbnailUrl ?? '' }}">
                        <i class="fa-regular fa-calendar-check"></i>Забронировать
                    </button>
                @else
                    <button type="button" class="select-room-btn" disabled>
                        <i class="fa-solid fa-ban me-2"></i>Распродано
                    </button>
                @endif
            </div>
            </div>
        </div>
    </div>
@empty
    <div class="no-results">
        <i class="fa-solid fa-bed"></i>
        <h4>Номера не доступны</h4>
        <p>Попробуйте изменить параметры фильтров или выбрать другие даты.</p>
    </div>
@endforelse