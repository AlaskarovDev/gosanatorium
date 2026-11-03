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
        <div class="gsp-track gsp-track--grid gsp-track--home">
            @foreach ($latestAkcii->take(3) as $item)
                @include('partials.akcii-card', ['item' => $item])
            @endforeach
        </div>
        <div class="hakcii-more"><a href="/ru/aktsii">Все акции →</a></div>
    </div>
</section>
@include('partials.akcii-script')
@endif
