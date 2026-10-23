@extends('theme::layouts.app')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection
@section('front-content')
    {{-- hero (statik fon + içində axtarış forması, theme3 üslubu) --}}
    {{-- köhnə slider-ə qayıtmaq üçün: @include('theme::components.slider') + @include('theme::components.booking-form') --}}
    @include('theme::components.hero-search')

    {{-- Why choose us --}}
    @include('components.home-why-section')

    @include('components.features')
    @include('theme::components.statistics')

    {{-- package section --}}
    @include('theme::components.package')

    {{-- consultant card --}}
    @include('components.home-consultant-card')

@include('components.room-booking-popup')

    {{-- diseases section --}}
    @include('components.diseases-section')

    {{-- Consultation section --}}
    @include('components.home-consultation-section')

    {{-- why choose section (hidden) --}}
    {{-- @include('theme::components.why-choose') --}}

    {{-- destination section --}}
    @include('theme::components.destination')

    {{-- banner section (video + arrow box) — hidden on request --}}
    {{-- @include('theme::components.banner') --}}

    {{-- banner two section (Начните Путь К Здоровью) — hidden on request --}}
    {{-- @include('theme::components.banner-two') --}}

    {{-- testimonial section --}}
    @include('theme::components.testimonial')

    {{-- blog section --}}
    @include('theme::components.blog')

    {{-- How to book section --}}
    @include('components.how-to-book-section')

    {{-- FAQ section --}}
    @include('components.faq-section')

    {{-- Akcii section --}}
    @include('components.home-akcii-section')

    {{-- Naftalan products preview --}}
    @include('components.home-products-section')

    {{-- Live currency rates section --}}
    @include('components.home-currency-section')

    {{-- Interactive sanatorium map --}}
    @include('components.home-map-section')

    {{-- Sticky side navigation --}}
<style>
.gssticky-nav{position:fixed!important;right:16px!important;top:50%!important;transform:translateY(-50%)!important;z-index:99999!important;display:flex!important;flex-direction:column!important;gap:6px!important;background:#fff!important;border-radius:12px!important;box-shadow:0 4px 24px rgba(0,0,0,0.12)!important;padding:10px 8px!important;max-height:94vh!important;overflow-y:auto!important;scrollbar-width:none!important}
.gssticky-nav::-webkit-scrollbar{display:none}
.gssticky-item{display:flex!important;flex-direction:column!important;align-items:center!important;gap:4px!important;cursor:pointer!important;padding:8px 6px!important;border-radius:8px!important;text-decoration:none!important;transition:background 0.2s!important;width:72px!important}
.gssticky-item:hover{background:#f0faf8!important}
.gssticky-item.active{background:linear-gradient(135deg,#1a9e87,#2ec4a9)!important}
.gssticky-icon{width:28px!important;height:28px!important;border-radius:50%!important;background:linear-gradient(135deg,#1a9e87,#2ec4a9)!important;display:flex!important;align-items:center!important;justify-content:center!important;flex-shrink:0!important}
.gssticky-item.active .gssticky-icon{background:rgba(255,255,255,0.25)!important}
.gssticky-icon svg{width:15px!important;height:15px!important;fill:#fff!important}
.gssticky-label{font-size:11px!important;color:#222!important;text-align:center!important;line-height:1.3!important;font-weight:600!important}
.gssticky-item:hover .gssticky-label{color:#1a9e87!important}
.gssticky-item.active .gssticky-label{color:#fff!important}
@media(max-height:760px){.gssticky-nav{gap:2px!important}.gssticky-item{padding:4px 6px!important;gap:2px!important}}
@media(max-width:768px){.gssticky-nav{display:none!important}}
</style>

<nav class="gssticky-nav" id="gssticky-nav">

  <a href="#section-consultant" class="gssticky-item" onclick="gsScrollTo('section-consultant');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
    </div>
    <span class="gssticky-label">Консультант</span>
  </a>

  <a href="#section-diseases" class="gssticky-item" onclick="gsScrollTo('section-diseases');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
    </div>
    <span class="gssticky-label">По болезни</span>
  </a>

  <a href="#section-doctor" class="gssticky-item" onclick="gsScrollTo('section-doctor');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/></svg>
    </div>
    <span class="gssticky-label">Врач бесплатно</span>
  </a>

  <a href="#section-directions" class="gssticky-item" onclick="gsScrollTo('section-directions');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
    </div>
    <span class="gssticky-label">Направления</span>
  </a>

  <a href="#section-howtobook" class="gssticky-item" onclick="gsScrollTo('section-howtobook');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
    </div>
    <span class="gssticky-label">Как забронировать</span>
  </a>

  <a href="#" class="gssticky-item" id="gsStickyValuta">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M7.5 21 3 16.5 7.5 12v3H15v3H7.5v3zM16.5 12v-3H9V6h7.5V3L21 7.5 16.5 12z" transform="translate(0 1)"/></svg>
    </div>
    <span class="gssticky-label">Валюта</span>
  </a>

  <a href="#section-map" class="gssticky-item" onclick="gsScrollTo('section-map');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/></svg>
    </div>
    <span class="gssticky-label">Карта</span>
  </a>

  <a href="#section-faq" class="gssticky-item" onclick="gsScrollTo('section-faq');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
    </div>
    <span class="gssticky-label">FAQ</span>
  </a>

  <a href="#section-akcii" class="gssticky-item" onclick="gsScrollTo('section-akcii');return false;">
    <div class="gssticky-icon">
      <svg viewBox="0 0 24 24"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
    </div>
    <span class="gssticky-label">Акции</span>
  </a>

</nav>

<script>
function gsScrollTo(id){
  var el=document.getElementById(id);
  if(el){el.scrollIntoView({behavior:'smooth',block:'start'});}
}
(function(){
  var items=document.querySelectorAll('.gssticky-item');
  var sections=['section-consultant', 'section-diseases', 'section-doctor', 'section-directions', 'section-howtobook', 'section-faq', 'section-akcii'];
  window.addEventListener('scroll',function(){
    var current='';
    sections.forEach(function(id){
      var el=document.getElementById(id);
      if(el&&el.getBoundingClientRect().top<=200)current=id;
    });
    items.forEach(function(item){
      var href=(item.getAttribute('href')||'').replace('#','');
      if(href)item.classList.toggle('active',href===current);
    });
  });
})();
</script>

<script>
document.getElementById('gsStickyValuta').addEventListener('click', function(e) {
    e.preventDefault();
    var el = document.querySelector('.gscurrate-section');
    if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
});
</script>

@endsection
