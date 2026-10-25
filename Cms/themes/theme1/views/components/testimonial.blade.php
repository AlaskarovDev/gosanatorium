@php
    use Modules\Testimonial\App\Models\Testimonial;
    use Illuminate\Support\Facades\Schema;

    $testimonials = Testimonial::with(['translate', 'front_translate'])->where('status', 'active')->latest()->get();
    $hasCountry = Schema::hasColumn('testimonials', 'country');

    // country key => [flag, name]
    $gsreviewCountries = [
        'uz' => ['🇺🇿', 'Узбекистан'],
        'ru' => ['🇷🇺', 'Россия'],
        'il' => ['🇮🇱', 'Израиль'],
        'kz' => ['🇰🇿', 'Казахстан'],
        'de' => ['🇩🇪', 'Германия'],
    ];
@endphp

@if ($testimonials->count() > 0)
<section class="gsreview-section">
    <style>
        .gsreview-section{display:block!important;background:#f8fafc!important;padding:80px 0!important;width:100%!important}
        .gsreview-container{display:block!important;max-width:1200px!important;margin:0 auto!important;padding:0 20px!important}
        .gsreview-head{display:block!important;text-align:center!important}
        .gsreview-label{display:block!important;color:#1a9e87!important;font-size:13px!important;letter-spacing:3px!important;font-weight:600!important;margin:0 0 12px!important;text-transform:none!important}
        .gsreview-title{display:block!important;color:#1a2540!important;font-size:36px!important;font-weight:800!important;line-height:1.2!important;margin:0!important;text-transform:none!important}
        .gsreview-subtitle{display:block!important;color:#64748b!important;font-size:16px!important;margin:12px 0 0!important;text-transform:none!important}
        .gsreview-line{display:block!important;width:60px!important;height:4px!important;background:linear-gradient(135deg,#1a9e87,#2ec4a9)!important;border-radius:2px!important;margin:12px auto 40px!important}
        .gsreview-tabs{display:flex!important;flex-wrap:wrap!important;justify-content:center!important;gap:10px!important;margin:0 0 32px!important}
        .gsreview-tab{display:inline-block!important;background:#fff!important;color:#64748b!important;border:1px solid #e2e8f0!important;border-radius:20px!important;padding:6px 16px!important;font-size:14px!important;cursor:pointer!important;text-transform:none!important;line-height:1.5!important}
        .gsreview-tab.gsreview-active{background:#1a9e87!important;border-color:#1a9e87!important;color:#fff!important}
        .gsreview-slider-wrap{position:relative!important;padding:0 56px!important}
        .gsreview-slider{overflow:hidden!important;padding:8px 4px 24px!important}
        .gsreview-slider .swiper-slide{height:auto!important}
        .gsreview-card{display:flex!important;flex-direction:column!important;height:100%!important;background:#fff!important;border-radius:16px!important;box-shadow:0 4px 24px rgba(0,0,0,0.07)!important;padding:28px!important;border:0!important}
        .gsreview-quote{display:block!important;color:#1a9e87!important;font-size:60px!important;line-height:1!important;opacity:.3!important;font-family:Georgia,serif!important;height:40px!important}
        .gsreview-text{display:block!important;flex:1 1 auto!important;color:#374151!important;font-size:15px!important;line-height:1.7!important;font-style:italic!important;text-transform:none!important;margin:0 0 12px!important}
        .gsreview-stars{display:block!important;color:#1a9e87!important;font-size:18px!important;letter-spacing:2px!important;margin:0 0 16px!important}
        .gsreview-stars .gsreview-off{opacity:.25!important}
        .gsreview-user{display:flex!important;align-items:center!important;gap:12px!important;border-top:1px solid #f0f4f8!important;padding-top:16px!important}
        .gsreview-avatar{display:flex!important;flex:0 0 44px!important;align-items:center!important;justify-content:center!important;width:44px!important;height:44px!important;border-radius:50%!important;background:linear-gradient(135deg,#1a9e87,#2ec4a9)!important;color:#fff!important;font-weight:700!important;font-size:16px!important;overflow:hidden!important;text-transform:none!important}
        .gsreview-avatar img{width:44px!important;height:44px!important;object-fit:cover!important;border-radius:50%!important}
        .gsreview-name{display:block!important;color:#1a2540!important;font-weight:700!important;font-size:16px!important;line-height:1.3!important;text-transform:none!important}
        .gsreview-meta{display:block!important;color:#94a3b8!important;font-size:13px!important;line-height:1.4!important;text-transform:none!important}
        .gsreview-arrow{position:absolute!important;top:50%!important;transform:translateY(-50%)!important;width:44px!important;height:44px!important;border-radius:50%!important;background:#fff!important;border:1px solid #1a9e87!important;color:#1a9e87!important;font-size:22px!important;line-height:1!important;cursor:pointer!important;display:flex!important;align-items:center!important;justify-content:center!important;z-index:5!important;padding:0!important}
        .gsreview-arrow:hover{background:#1a9e87!important;color:#fff!important}
        .gsreview-prev{left:0!important}
        .gsreview-next{right:0!important}
        @media(max-width:768px){.gsreview-section{padding:56px 0!important}.gsreview-title{font-size:28px!important}.gsreview-slider-wrap{padding:0 8px!important}.gsreview-arrow{display:none!important}}
        .gs-translate-btn{display:inline-block;margin-top:10px;padding:5px 14px;font-size:12px;font-weight:600;color:#1a9e87;background:#f0faf8;border:1px solid #d0f0eb;border-radius:50px;cursor:pointer;transition:all .2s;font-style:normal;align-self:flex-start}
        .gs-translate-btn:hover{background:#1a9e87;color:#fff}
        .gs-translated-text{margin-top:8px;font-size:14px;color:#374151;font-style:italic;display:none;padding:8px 12px;background:#f9fffe;border-left:3px solid #1a9e87;border-radius:0 8px 8px 0}
    </style>
    <div class="gsreview-container">
        <div class="gsreview-head">
            <span class="gsreview-label">ОТЗЫВЫ КЛИЕНТОВ</span>
            <h2 class="gsreview-title">Что говорят наши гости</h2>
            <p class="gsreview-subtitle">Реальные отзывы людей, которые уже побывали в санаториях</p>
            <span class="gsreview-line"></span>
        </div>

        <div class="gsreview-tabs">
            <button type="button" class="gsreview-tab gsreview-active" data-country="all">🌍 Все</button>
            @foreach ($gsreviewCountries as $key => $c)
                <button type="button" class="gsreview-tab" data-country="{{ $key }}">{{ $c[0] }} {{ $c[1] }}</button>
            @endforeach
        </div>

        <div class="gsreview-slider-wrap">
            <button type="button" class="gsreview-arrow gsreview-prev" aria-label="Previous">‹</button>
            <div class="gsreview-slider swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                        @php
                            $name = $testimonial->translate?->name ?: $testimonial->front_translate?->name;
                            $comment = $testimonial->translate?->comment ?: $testimonial->front_translate?->comment;
                            $designation = $testimonial->translate?->designation ?: $testimonial->front_translate?->designation;
                            $cKey = $hasCountry ? ($testimonial->country ?? '') : '';
                            $parts = preg_split('/[\s,]+/u', trim(preg_replace('/[^\p{L}\s\-]/u', ' ', (string) $name)), -1, PREG_SPLIT_NO_EMPTY);
                            $initials = '';
                            foreach (array_slice($parts, 0, 2) as $p) {
                                $initials .= mb_strtoupper(mb_substr($p, 0, 1));
                            }
                            $rating = (int) $testimonial->rating;
                        @endphp
                        <div class="swiper-slide" data-country="{{ $cKey }}">
                            <div class="gsreview-card">
                                <span class="gsreview-quote">&ldquo;</span>
                                <p class="gsreview-text">{{ $comment }}</p>
                                <span class="gs-translate-btn" onclick="gsTranslate(this)">🌐 Перевести</span>
                                <div class="gs-translated-text"></div>
                                <span class="gsreview-stars">@for ($i = 1; $i <= 5; $i++)<span class="{{ $i <= $rating ? '' : 'gsreview-off' }}">★</span>@endfor</span>
                                <div class="gsreview-user">
                                    <div class="gsreview-avatar">
                                        @if (!empty($testimonial->image))
                                            <img src="{{ asset($testimonial->image) }}" alt="{{ $name }}" loading="lazy">
                                        @else
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <div>
                                        <span class="gsreview-name">{{ $name }}</span>
                                        <span class="gsreview-meta">
                                            @if (isset($gsreviewCountries[$cKey]))
                                                {{ $gsreviewCountries[$cKey][0] }} {{ $gsreviewCountries[$cKey][1] }}@if ($designation) · {{ $designation }}@endif
                                            @else
                                                {{ $designation }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="gsreview-arrow gsreview-next" aria-label="Next">›</button>
        </div>
    </div>
</section>

@push('js_section')
    <script>
function gsTranslate(btn){
  if(!btn)return;
  var textEl=btn.previousElementSibling;
  var outEl=btn.nextElementSibling;
  if(!textEl||!outEl)return;
  if(outEl.style.display==='block'){outEl.style.display='none';btn.textContent='🌐 Перевести';return;}
  var text=textEl.textContent.trim();
  if(!text)return;
  btn.textContent='⏳ Перевод...';
  fetch('https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=ru&dt=t&q='+encodeURIComponent(text))
  .then(function(r){return r.json();})
  .then(function(d){
    var result=d[0].map(function(x){return x[0];}).join('');
    outEl.textContent=result;
    outEl.style.display='block';
    btn.textContent='✕ Скрыть перевод';
  })
  .catch(function(){btn.textContent='🌐 Перевести';alert('Ошибка перевода');});
}
    </script>
    <script>
        (function () {
            var root = document.querySelector('.gsreview-section');
            if (!root || typeof Swiper === 'undefined') return;
            var wrapper = root.querySelector('.swiper-wrapper');
            var allSlides = Array.prototype.slice.call(wrapper.children);
            var swiper;

            function build(country) {
                if (swiper) { swiper.destroy(true, true); }
                wrapper.innerHTML = '';
                var shown = allSlides.filter(function (s) {
                    return country === 'all' || s.getAttribute('data-country') === country;
                });
                shown.forEach(function (s) { wrapper.appendChild(s); });
                swiper = new Swiper(root.querySelector('.gsreview-slider'), {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    speed: 600,
                    loop: shown.length > 3,
                    watchOverflow: true,
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    navigation: {
                        prevEl: root.querySelector('.gsreview-prev'),
                        nextEl: root.querySelector('.gsreview-next')
                    },
                    breakpoints: { 992: { slidesPerView: 3 }, 640: { slidesPerView: 2 } }
                });
            }

            root.querySelectorAll('.gsreview-tab').forEach(function (tab) {
                tab.addEventListener('click', function () {
                    root.querySelectorAll('.gsreview-tab').forEach(function (t) { t.classList.remove('gsreview-active'); });
                    tab.classList.add('gsreview-active');
                    build(tab.getAttribute('data-country'));
                });
            });
            build('all');
        })();
    </script>
@endpush
@endif
