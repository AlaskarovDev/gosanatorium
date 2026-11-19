{{-- NEW YEAR MODE: top bar above the header --}}
@if (\App\Services\NewYearMode::isActive())
<div class="ny-bar gs-topbar" id="nyBar">
    <a class="ny-bar__link" href="{{ \App\Services\NewYearMode::link() }}" onclick="(window.dataLayer=window.dataLayer||[]).push({event:'ny_bar_click'})">{{ \App\Services\NewYearMode::barText() }}</a>
    <button type="button" class="ny-bar__close" aria-label="Закрыть" onclick="window.gsTopbarClose&&gsTopbarClose(this.parentNode,'nyBarClosed')">×</button>
</div>
<script>try{if(sessionStorage.getItem('nyBarClosed')==='true')document.getElementById('nyBar').style.display='none'}catch(e){}</script>
@endif
