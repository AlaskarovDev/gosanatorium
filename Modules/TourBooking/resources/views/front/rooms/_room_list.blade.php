@forelse($rooms as $room)
    @php
        $roomName = $room->name ?? $room->room_name ?? 'Номер';
        
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
    
    @php
        $rcQuote = (($search['min_stay_ok'] ?? false) ? ($quotes[$room->id] ?? null) : null);
    @endphp
    <div class="room-card" 
         data-price="{{ $rcQuote ? number_format($rcQuote['per_night'], 2, '.', '') : 0 }}" 
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
                @if($rcQuote && $rcQuote['has_discount'])
                    <div class="mobile-discount-badge">
                        -{{ $rcQuote['discount_percent'] }}%
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
                    {{-- Task 5: no scarcity label until real availability exists (Task 42) --}}
                    @if(!empty($room->is_popular))
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
                            <i class="fa-solid fa-bed"></i>{{ \Modules\TourBooking\App\Models\Room::bedTypeLabel($room->bed_type) }}
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
            @php
                $rsSearch = $search ?? null;
                $rsPriced = $rsSearch && ($rsSearch['min_stay_ok'] ?? false);
                $rsQuote = $rsPriced ? ($quotes[$room->id] ?? null) : null;
                $rsNightsWord = function ($n) {
                    $n = abs((int) $n) % 100; $n1 = $n % 10;
                    if ($n > 10 && $n < 20) return 'ночей';
                    if ($n1 > 1 && $n1 < 5) return 'ночи';
                    if ($n1 == 1) return 'ночь';
                    return 'ночей';
                };
                $rsAdultsWord = fn ($n) => $n == 1 ? 'взрослый' : 'взрослых';
                $rsChildrenWord = fn ($n) => $n == 1 ? 'ребёнок' : 'детей';
            @endphp
            <div class="room-price-col">
                @if($rsQuote)
                    <div class="rc-price-line rc-price-line--quote">
                        @if($rsQuote['has_discount'])
                            <div class="discount-tag">
                                <i class="fa-solid fa-tag me-1"></i>{{ $rsQuote['discount_percent'] }}% скидка
                            </div>
                            <div class="old-price">{!! currency_span($rsQuote['list_total']) !!}</div>
                        @endif
                        <div class="current-price rc-total-price">{!! currency_span($rsQuote['total']) !!} <span class="rc-total-for">за {{ $rsQuote['nights'] }} {{ $rsNightsWord($rsQuote['nights']) }}</span></div>
                        <div class="price-note rc-per-night">≈ {!! currency_span($rsQuote['per_night']) !!} за ночь</div>
                        <div class="rc-guests-line"><i class="fa-solid fa-user-group"></i> {{ $rsSearch['adults'] }} {{ $rsAdultsWord($rsSearch['adults']) }}, {{ $rsSearch['children'] }} {{ $rsChildrenWord($rsSearch['children']) }}</div>
                        @if($rsSearch['children'] > 0 && empty($rsQuote['child_priced']))
                            <div class="rc-children-note">Стоимость для детей уточнит менеджер</div>
                        @endif
                    </div>
                @elseif($rsPriced)
                    @php
                        $rsWaText = 'Здравствуйте! Хочу узнать цену. Санаторий: ' . ($service->translation->title ?? $service->title ?? '')
                            . '. Номер: ' . $roomName
                            . '. Даты: ' . $rsSearch['checkin']->format('d.m.Y') . ' — ' . $rsSearch['checkout']->format('d.m.Y')
                            . ' (' . $rsSearch['nights'] . ' ' . $rsNightsWord($rsSearch['nights']) . ')'
                            . '. Гости: ' . $rsSearch['adults'] . ' ' . $rsAdultsWord($rsSearch['adults'])
                            . ', ' . $rsSearch['children'] . ' ' . $rsChildrenWord($rsSearch['children'])
                            . (!empty($rsSearch['ages']) ? ' (возраст: ' . implode(', ', $rsSearch['ages']) . ')' : '') . '.';
                    @endphp
                    <div class="rc-price-line rc-price-line--request">
                        <div class="rc-request-price">Цена по запросу</div>
                        <div class="rc-guests-line"><i class="fa-solid fa-user-group"></i> {{ $rsSearch['adults'] }} {{ $rsAdultsWord($rsSearch['adults']) }}, {{ $rsSearch['children'] }} {{ $rsChildrenWord($rsSearch['children']) }}</div>
                    </div>
                @else
                    <div class="rc-price-line rc-price-line--hidden">
                        <div class="rc-hidden-price-note"><i class="fa-regular fa-calendar"></i> Укажите даты и гостей, чтобы увидеть цену</div>
                    </div>
                @endif
                
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
                    if ($room->relationLoaded('activeExtras')) {
                        $roomExtrasCol = $room->activeExtras;
                    } elseif ($room->relationLoaded('extras')) {
                        $roomExtrasCol = $room->extras->where('status', true);
                    } elseif (method_exists($room, 'extras')) {
                        $roomExtrasCol = $room->extras()->where('status', true)->orderBy('sort_order')->get();
                    } else {
                        $roomExtrasCol = collect();
                    }
                    // Only optional add-ons: adult/child prices are now part of the occupancy price.
                    $roomExtrasJson = json_encode(
                        $roomExtrasCol->where('type', 'optional')->map(fn($x) => [
                            'id' => $x->id,
                            'name' => $x->name,
                            'type' => $x->type,
                            'price' => (float) $x->price,
                        ])->values(),
                        JSON_HEX_APOS | JSON_HEX_QUOT
                    );
                @endphp
                @if($quantity <= 0)
                    <button type="button" class="select-room-btn" disabled>
                        <i class="fa-solid fa-ban me-2"></i>Распродано
                    </button>
                @elseif($rsQuote)
                    <button type="button"
                            class="select-room-btn"
                            data-room-id="{{ $room->id }}"
                            data-room-name="{{ $roomName }}"
                            data-room-price="{{ number_format($rsQuote['per_night'], 2, '.', '') }}"
                            data-quote-total="{{ number_format($rsQuote['total'], 2, '.', '') }}"
                            data-quote-nights="{{ $rsQuote['nights'] }}"
                            data-quote-child-priced="{{ !empty($rsQuote['child_priced']) ? 1 : 0 }}"
                            data-max-guests="{{ $maxGuests }}"
                            data-extras='{!! $roomExtrasJson !!}'
                            data-room-thumb="{{ $thumbnailUrl ?? '' }}">
                        <i class="fa-regular fa-calendar-check"></i>Забронировать
                    </button>
                @elseif($rsPriced)
                    <a class="select-room-btn rc-ask-btn rc-ask-btn--wa" target="_blank" rel="noopener"
                       href="https://wa.me/994519158894?text={{ rawurlencode($rsWaText) }}">
                        <i class="fa-brands fa-whatsapp"></i>Узнать цену
                    </a>
                @else
                    <button type="button" class="select-room-btn rc-ask-btn" data-ask-price="1">
                        <i class="fa-regular fa-calendar"></i>Узнать цену
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