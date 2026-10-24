@php
    $theme1_destination = getContent('theme1_destination.content', true);
    $destination_items = popularDestinations(8);
@endphp

<!-- tg-location-area-start -->
<section id="section-directions" class="gsdest-section">
    <div class="container">
        <div class="gsdest-underline"></div>
        <h2 class="gsdest-title">
            {!! strip_tags(clean(getTranslatedValue($theme1_destination, 'title')), '<br>') !!}
        </h2>
        <p class="gsdest-subtitle">
            {!! strip_tags(clean(getTranslatedValue($theme1_destination, 'description')), '<br>') !!}
        </p>
        @if ($destination_items->count() > 0)
            <div class="gsdest-grid">
                @foreach ($destination_items as $destination_item)
                    @php
                        // Təmiz SEO URL: /ru/hotels/{slug}/ — slug varsa onu işlət,
                        // yoxdursa köhnə (texniki) URL-ə geri düş ki, link sınmasın.
                        $destination_url = !empty($destination_item->slug)
                            ? rtrim(route('front.tourbooking.hotels.destination', ['slug' => $destination_item->slug]), '/') . '/'
                            : route('front.tourbooking.services', ['destination_id' => $destination_item->id, 'destination' => $destination_item->name]);
                        $dest_count = (int) $destination_item->services_count;
                        if (app()->getLocale() == 'ru') {
                            $dest_label = $dest_count == 1 ? 'Тур' : ($dest_count < 5 ? 'Туры' : 'Туров');
                        } else {
                            $dest_label = $dest_count == 1 ? __('translate.Tour') : __('translate.Tours');
                        }
                    @endphp
                    <a href="{{ $destination_url }}" class="gsdest-card">
                        <img src="{{ asset($destination_item->image) }}" alt="{{ $destination_item->country }}" class="gsdest-card-img">
                        <div class="gsdest-overlay"></div>
                        @if ($dest_count > 0)
                            <div class="gsdest-badge">{{ $dest_count }} {{ $dest_label }}</div>
                        @endif
                        <div class="gsdest-info">
                            <div class="gsdest-name">{{ $destination_item->country }}</div>
                            <div class="gsdest-arrow">{{ app()->getLocale() == 'ru' ? 'Смотреть' : 'View' }} →</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
<!-- tg-location-area-end -->

@push('style_section')
    <style>
/* === DESTINATION CARDS (gsdest- prefix) === */
.gsdest-section { padding: 80px 0 !important; background: #f8fafb !important; }
.gsdest-title { font-size: 36px !important; font-weight: 800 !important; color: #1a1a2e !important; text-align: center !important; margin-bottom: 12px !important; text-transform: none !important; letter-spacing: -0.5px !important; }
.gsdest-subtitle { font-size: 17px !important; color: #6b7280 !important; text-align: center !important; margin-bottom: 50px !important; font-weight: 400 !important; }
.gsdest-underline { width: 60px !important; height: 4px !important; background: linear-gradient(135deg,#1a9e87,#2ec4a9) !important; border-radius: 2px !important; margin: 0 auto 16px !important; }
.gsdest-grid { display: grid !important; grid-template-columns: repeat(4, minmax(0, 1fr)) !important; gap: 24px !important; }
@media(max-width:1200px){ .gsdest-grid{ grid-template-columns: repeat(3, minmax(0, 1fr)) !important; } }
@media(max-width:768px){ .gsdest-grid{ grid-template-columns: repeat(2, minmax(0, 1fr)) !important; } }
@media(max-width:480px){ .gsdest-grid{ grid-template-columns: 1fr !important; } }
.gsdest-card { position: relative !important; border-radius: 18px !important; overflow: hidden !important; cursor: pointer !important; box-shadow: 0 4px 20px rgba(0,0,0,0.10) !important; transition: transform 0.3s ease, box-shadow 0.3s ease !important; display: block !important; text-decoration: none !important; }
.gsdest-card:hover { transform: translateY(-6px) !important; box-shadow: 0 16px 40px rgba(0,0,0,0.18) !important; }
.gsdest-card-img { width: 100% !important; height: 260px !important; max-height: none !important; min-height: 0 !important; object-fit: cover !important; display: block !important; transition: transform 0.4s ease !important; }
.gsdest-card:hover .gsdest-card-img { transform: scale(1.06) !important; }
.gsdest-overlay { position: absolute !important; inset: 0 !important; background: linear-gradient(to bottom, rgba(0,0,0,0.0) 30%, rgba(0,0,0,0.75) 100%) !important; }
.gsdest-badge { position: absolute !important; top: 14px !important; left: 14px !important; background: linear-gradient(135deg,#1a9e87,#2ec4a9) !important; color: #fff !important; font-size: 12px !important; font-weight: 700 !important; padding: 5px 12px !important; border-radius: 20px !important; letter-spacing: 0.3px !important; text-transform: none !important; }
.gsdest-info { position: absolute !important; bottom: 0 !important; left: 0 !important; right: 0 !important; padding: 20px 20px 22px !important; }
.gsdest-country { font-size: 11px !important; font-weight: 600 !important; color: rgba(255,255,255,0.75) !important; text-transform: uppercase !important; letter-spacing: 1.5px !important; margin-bottom: 4px !important; }
.gsdest-name { font-size: 20px !important; font-weight: 800 !important; color: #ffffff !important; line-height: 1.2 !important; text-transform: none !important; }
.gsdest-arrow { display: inline-flex !important; align-items: center !important; gap: 6px !important; margin-top: 10px !important; font-size: 13px !important; font-weight: 600 !important; color: #2ec4a9 !important; }
    </style>
@endpush
