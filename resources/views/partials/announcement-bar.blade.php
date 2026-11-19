@php
    // Same data source as /akcii and the homepage cards: only active, started, not expired offers.
    try {
        $gsAnnItems = \Illuminate\Support\Facades\Cache::remember('announcement_bar_' . \App\Models\Akcii::today()->toDateString(), 600, function () {
            return \App\Models\Akcii::visible()->with('service.translation')->get()
                ->sortByDesc('is_hot')->take(4)->map(fn ($a) => $a->banner_label)->filter()->values()->all();
        });
    } catch (\Throwable $e) {
        $gsAnnItems = [];
    }
@endphp
@if (!empty($gsAnnItems))
<style>
.announcement-bar{background:linear-gradient(90deg,#1a7c2f 0%,#2e9e47 100%);color:#fff;padding:10px 20px;display:flex;align-items:center;justify-content:center;gap:12px;position:relative;z-index:10000;font-size:13px}
.announcement-bar__content{display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:center;padding-right:30px}
.announcement-bar__fire{font-size:16px}
.announcement-bar__text{color:rgba(255,255,255,.95);font-weight:500}
.announcement-bar__link{color:#fff;font-weight:700;text-decoration:underline;white-space:nowrap}
.announcement-bar__link:hover{color:#ffe082}
.announcement-bar__close{position:absolute;right:16px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:16px;cursor:pointer;opacity:.7;padding:4px 8px}
.announcement-bar__close:hover{opacity:1}
.announcement-bar{overflow:hidden;box-sizing:border-box;transition:max-height .3s ease,padding .3s ease,opacity .3s ease}
@media (max-width:600px){.announcement-bar__text{display:none}}
</style>
<div class="announcement-bar gs-topbar" id="announcementBar">
  <div class="announcement-bar__content">
    <span class="announcement-bar__fire">🔥</span>
    <span class="announcement-bar__text">Специальные акции: {{ implode(' · ', $gsAnnItems) }}</span>
    <a href="{{ url('/akcii') }}" class="announcement-bar__link">Смотреть все акции →</a>
  </div>
  <button type="button" class="announcement-bar__close" onclick="closeAnnouncementBar()" aria-label="Закрыть">✕</button>
</div>
<script>
try { if (sessionStorage.getItem('announcementClosed') === 'true') document.getElementById('announcementBar').style.display = 'none'; } catch (e) {}
window.closeAnnouncementBar = function () { window.gsTopbarClose && gsTopbarClose(document.getElementById('announcementBar'), 'announcementClosed'); };
</script>
@endif
