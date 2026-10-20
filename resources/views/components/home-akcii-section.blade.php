@if (isset($latestAkcii) && $latestAkcii->count() > 0)
<style>
.hakcii-section{background:#fff !important;padding-top:72px !important;padding-bottom:72px !important;width:100% !important}
.hakcii-container{max-width:1200px !important;margin:0 auto !important;padding:0 20px !important;box-sizing:border-box !important}
.hakcii-head{text-align:center !important;margin-bottom:48px !important}
.hakcii-label{display:block !important;color:#1a9e87 !important;font-size:13px !important;font-weight:700 !important;letter-spacing:1px !important;text-transform:uppercase !important;margin-bottom:10px !important}
.hakcii-title{font-size:32px !important;font-weight:800 !important;color:#1f2937 !important;margin:0 !important}
.hakcii-grid{display:grid !important;grid-template-columns:1fr !important;gap:28px !important}
@media(min-width:768px){.hakcii-grid{grid-template-columns:repeat(3,1fr) !important}}
.hakcii-card{background:#fff !important;border-radius:14px !important;box-shadow:0 4px 20px rgba(0,0,0,0.08) !important;overflow:hidden !important;display:flex !important;flex-direction:column !important}
.hakcii-media{height:180px !important;width:100% !important;display:flex !important;align-items:center !important;justify-content:center !important;background:linear-gradient(135deg,#1a9e87,#2ec4a9) !important;color:#fff !important;font-size:40px !important;font-weight:700 !important;overflow:hidden !important}
.hakcii-img{width:100% !important;height:100% !important;object-fit:cover !important;display:block !important}
.hakcii-body{padding:20px !important;display:flex !important;flex-direction:column !important;flex:1 !important}
.hakcii-san{color:#1a9e87 !important;font-size:13px !important}
.hakcii-card-title{font-weight:700 !important;color:#1f2937 !important;font-size:16px !important;margin-top:4px !important;margin-bottom:0 !important}
.hakcii-desc{color:#6b7280 !important;font-size:14px !important;margin-top:8px !important;margin-bottom:0 !important;display:-webkit-box !important;-webkit-line-clamp:2 !important;-webkit-box-orient:vertical !important;overflow:hidden !important}
.hakcii-row{display:flex !important;align-items:center !important;justify-content:space-between !important;gap:10px !important;margin-top:auto !important;padding-top:12px !important}
.hakcii-badge{background:#1a9e87 !important;color:#fff !important;font-size:13px !important;font-weight:700 !important;padding:3px 10px !important;border-radius:20px !important}
.hakcii-date{color:#6b7280 !important;font-size:13px !important}
.hakcii-btn{display:block !important;width:100% !important;background:#1a9e87 !important;color:#fff !important;text-align:center !important;border-radius:8px !important;padding:10px !important;margin-top:12px !important;text-decoration:none !important;font-weight:600 !important;box-sizing:border-box !important}
.hakcii-more{text-align:center !important;margin-top:40px !important}
.hakcii-more a{color:#1a9e87 !important;font-size:16px !important;font-weight:600 !important;text-decoration:none !important}
</style>
<section id="section-akcii" class="hakcii-section">
    <div class="hakcii-container">
        <div class="hakcii-head">
            <span class="hakcii-label">Акции и спецпредложения</span>
            <h2 class="hakcii-title">Выгодные акции для вашего лечения</h2>
        </div>
        <div class="hakcii-grid">
            @foreach ($latestAkcii as $item)
                <div class="hakcii-card">
                    <div class="hakcii-media">
                        @if ($item->image)
                            <img class="hakcii-img" src="{{ asset($item->image) }}" alt="{{ $item->title }}" loading="lazy">
                        @else
                            {{ $item->discount_percent ? $item->discount_percent . '%' : '%' }}
                        @endif
                    </div>
                    <div class="hakcii-body">
                        @if ($item->sanatorium_name)
                            <div class="hakcii-san">{{ $item->sanatorium_name }}</div>
                        @endif
                        <div class="hakcii-card-title">{{ $item->title }}</div>
                        @if ($item->description)
                            <p class="hakcii-desc">{{ $item->description }}</p>
                        @endif
                        <div class="hakcii-row">
                            @if ($item->discount_percent)
                                <span class="hakcii-badge">−{{ $item->discount_percent }}%</span>
                            @endif
                            @if ($item->valid_until)
                                <span class="hakcii-date">до {{ $item->valid_until->format('d.m.Y') }}</span>
                            @endif
                        </div>
                        <a class="hakcii-btn" href="https://wa.me/994519158894" target="_blank" rel="noopener">Забронировать</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="hakcii-more"><a href="/ru/akcii">Все акции →</a></div>
    </div>
</section>
@endif
