@if (isset($latestAkcii) && $latestAkcii->count() > 0)

<style>
.hakcii-section{background:#fff !important;padding:72px 0 !important;width:100% !important}
.hakcii-container{max-width:1200px !important;margin:0 auto !important;padding:0 20px !important;box-sizing:border-box !important}
.hakcii-head{text-align:center !important;margin-bottom:48px !important}
.hakcii-label{display:block !important;color:#1a9e87 !important;font-size:13px !important;font-weight:700 !important;letter-spacing:1px !important;text-transform:uppercase !important;margin-bottom:10px !important}
.hakcii-title{font-size:32px !important;font-weight:800 !important;color:#1f2937 !important;margin:0 !important}
.hakcii-more{text-align:right !important;margin-top:32px !important}
.hakcii-more a{color:#1a9e87 !important;font-size:16px !important;font-weight:600 !important;text-decoration:none !important}
</style>
<section id="section-akcii" class="hakcii-section">
    <div class="hakcii-container">
        <div class="hakcii-head">
            <span class="hakcii-label">Акции и спецпредложения</span>
            <h2 class="hakcii-title">Выгодные акции для вашего лечения</h2>
        </div>
        <div class="home-promos-grid">
            @foreach ($latestAkcii->take(3) as $item)
                @php
                    $ph = !empty($item->is_placeholder);
                    $svc = $item->service;
                    $img = $item->image ?: ($svc?->thumbnail?->file_path);
                    $pct = $item->discount_label;
                    $desc = $item->description ?: ($item->sanatorium_name ?: $svc?->translation?->title);
                    $href = $ph ? route('front.tourbooking.services') : $item->url;
                @endphp
                <a href="{{ $href }}" class="home-promo-card">
                    <div class="home-promo-img">
                        @if ($img)
                            <img src="{{ asset($img) }}" alt="{{ $item->title }}" loading="lazy">
                        @else
                            <div class="home-promo-img-placeholder"></div>
                        @endif
                        @if ($pct)
                            <span class="home-promo-badge">−{{ $pct }}%</span>
                        @endif
                    </div>
                    <div class="home-promo-body">
                        <h3>{{ $item->title }}</h3>
                        @if ($desc)
                            <p>{{ \Illuminate\Support\Str::limit($desc, 80) }}</p>
                        @endif
                        @if (!$ph && $item->valid_until)
                            <span class="home-promo-date">Действует до {{ $item->valid_until->format('d.m.Y') }}</span>
                        @endif
                        <span class="home-promo-btn">Подробнее →</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="hakcii-more"><a href="/ru/aktsii">Все акции →</a></div>
    </div>
</section>
@endif
