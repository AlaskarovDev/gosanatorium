@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean(company_text($seo_setting->seo_description))) !!}">
@endsection

@section('front-content')
<style>
*{box-sizing:border-box}
.gsfaq-hero{background:linear-gradient(135deg,#0d7a68 0%,#1a9e87 50%,#2ec4a9 100%);padding:72px 0 60px;position:relative;overflow:hidden}
.gsfaq-hero::before{content:'';position:absolute;top:-80px;right:-80px;width:400px;height:400px;background:rgba(255,255,255,0.05);border-radius:50%}
.gsfaq-hero::after{content:'';position:absolute;bottom:-100px;left:-60px;width:300px;height:300px;background:rgba(255,255,255,0.04);border-radius:50%}
.gsfaq-hero-inner{position:relative;z-index:2;text-align:center}
.gsfaq-badge{display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);border-radius:50px;padding:7px 20px;font-size:12px;font-weight:700;color:#fff;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:20px}
.gsfaq-hero-title{font-size:46px;font-weight:900;color:#fff;margin-bottom:14px;text-transform:none;letter-spacing:-1px;line-height:1.1}
.gsfaq-hero-sub{font-size:17px;color:rgba(255,255,255,0.82);max-width:520px;margin:0 auto 36px;line-height:1.6;text-transform:none}
.gsfaq-search-wrap{max-width:520px;margin:0 auto;position:relative}
.gsfaq-search{width:100%;padding:16px 56px 16px 22px;border:none;border-radius:50px;font-size:15px;color:#1a1a2e;background:#fff;box-shadow:0 8px 32px rgba(0,0,0,0.15);outline:none;font-family:inherit}
.gsfaq-search-icon{position:absolute;right:20px;top:50%;transform:translateY(-50%);color:#1a9e87;font-size:18px;pointer-events:none}
.gsfaq-stats{display:flex;justify-content:center;gap:40px;margin-top:36px;flex-wrap:wrap}
.gsfaq-stat{text-align:center}
.gsfaq-stat-num{font-size:28px;font-weight:900;color:#fff}
.gsfaq-stat-lbl{font-size:12px;color:rgba(255,255,255,0.72);text-transform:uppercase;letter-spacing:1px}
.gsfaq-main{padding:72px 0 80px;background:#f8fafb}
.gsfaq-layout{display:grid;grid-template-columns:260px 1fr;gap:40px;align-items:start}
.gsfaq-sidebar{position:sticky;top:24px}
.gsfaq-cat-list{background:#fff;border-radius:18px;padding:8px;box-shadow:0 2px 16px rgba(0,0,0,0.07)}
.gsfaq-cat-btn{display:flex;align-items:center;gap:10px;width:100%;padding:12px 16px;border:none;background:transparent;border-radius:12px;font-size:14px;font-weight:600;color:#6b7280;cursor:pointer;text-align:left;transition:all 0.2s;font-family:inherit}
.gsfaq-cat-btn:hover{background:#f0faf8;color:#1a9e87}
.gsfaq-cat-btn.active{background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff}
.gsfaq-cat-btn.active .gsfaq-cat-count{background:rgba(255,255,255,0.25);color:#fff}
.gsfaq-cat-icon{font-size:16px;flex-shrink:0}
.gsfaq-cat-text{flex:1}
.gsfaq-cat-count{background:#f0faf8;color:#1a9e87;font-size:11px;font-weight:700;padding:2px 8px;border-radius:50px;min-width:24px;text-align:center}
.gsfaq-content-area{}
.gsfaq-group{margin-bottom:40px}
.gsfaq-group-header{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.gsfaq-group-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.gsfaq-group-icon i{color:#fff;font-size:17px}
.gsfaq-group-title{font-size:20px;font-weight:800;color:#1a1a2e;text-transform:none;letter-spacing:-0.3px}
.gsfaq-accordion{display:flex;flex-direction:column;gap:10px}
.gsfaq-item{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.06);border:1.5px solid transparent;transition:border-color 0.25s}
.gsfaq-item.open{border-color:#1a9e87}
.gsfaq-q{width:100%;border:0;background:transparent;text-align:left;font-family:inherit;display:flex;align-items:center;gap:14px;padding:18px 20px;cursor:pointer;transition:background 0.2s}
.gsfaq-q:hover{background:#f9fffe}
.gsfaq-qnum{width:28px;height:28px;border-radius:8px;background:#f0faf8;color:#1a9e87;font-size:12px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.gsfaq-item.open .gsfaq-qnum{background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff}
.gsfaq-qtext{flex:1;font-size:15px;font-weight:700;color:#1a1a2e;text-transform:none;line-height:1.4}
.gsfaq-qarrow{width:28px;height:28px;border-radius:8px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.25s}
.gsfaq-item.open .gsfaq-qarrow{background:linear-gradient(135deg,#1a9e87,#2ec4a9);transform:rotate(180deg)}
.gsfaq-qarrow i{color:#6b7280;font-size:12px;transition:color 0.25s}
.gsfaq-item.open .gsfaq-qarrow i{color:#fff}
.gsfaq-ans{display:none;padding:0 20px 20px 62px;font-size:14px;color:#4b5563;line-height:1.75;text-transform:none}
.gsfaq-item.open .gsfaq-ans{display:block}
.gsfaq-cta{background:linear-gradient(135deg,#0d7a68,#1a9e87 60%,#2ec4a9);padding:64px 0;text-align:center;position:relative;overflow:hidden}
.gsfaq-cta::before{content:'';position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:rgba(255,255,255,0.05);border-radius:50%}
.gsfaq-cta-inner{position:relative;z-index:2}
.gsfaq-cta-title{font-size:32px;font-weight:800;color:#fff;margin-bottom:12px;text-transform:none}
.gsfaq-cta-sub{font-size:16px;color:rgba(255,255,255,0.82);margin-bottom:32px;text-transform:none}
.gsfaq-cta-btns{display:flex;justify-content:center;gap:16px;flex-wrap:wrap}
.gsfaq-btn-white{display:inline-flex;align-items:center;gap:8px;padding:15px 32px;background:#fff;color:#1a9e87;font-size:15px;font-weight:700;border-radius:50px;text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,0.15);transition:all 0.3s}
.gsfaq-btn-white:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(0,0,0,0.2);text-decoration:none;color:#1a9e87}
.gsfaq-btn-outline{display:inline-flex;align-items:center;gap:8px;padding:15px 32px;background:transparent;color:#fff;font-size:15px;font-weight:700;border-radius:50px;border:2px solid rgba(255,255,255,0.5);text-decoration:none;transition:all 0.3s}
.gsfaq-btn-outline:hover{background:rgba(255,255,255,0.12);text-decoration:none;color:#fff}
@media(max-width:900px){.gsfaq-layout{grid-template-columns:1fr!important}.gsfaq-sidebar{position:static!important}.gsfaq-hero-title{font-size:30px!important}}
@media(max-width:576px){.gsfaq-stats{gap:20px!important}.gsfaq-cta-title{font-size:24px!important}}
.gsfaq-qtext{margin:0}
.gsfaq-group,.gsfaq-item{scroll-margin-top:140px}
.gsfaq-ans a{color:#1a9e87;text-decoration:underline}
</style>

@php
    // Bug 2: questions grouped by topic; every group and question has its own anchor (/faq#otmena, /faq#q-…).
    $gsConsultUrl = url('/custom-page/consultant');
    $gsTransferUrl = url('/custom-page/transfer');
    $gsfaqGroups = [
        ['id' => 'bronirovanie-i-oplata', 'icon' => 'fa-calendar-check', 'emoji' => '📅', 'title' => 'Бронирование и оплата', 'items' => [
            ['id' => 'otlichie', 'q' => 'Чем GoSanatorium отличается от других сайтов?', 'a' => e('Мы подбираем санаторий под ваш диагноз, организуем трансфер и консультацию врача и поддерживаем клиента на каждом шаге (' . company_fact('working_hours') . ').')],
            ['id' => 'skolko-sanatoriev', 'q' => 'Сколько санаториев в каталоге?', 'a' => e(company_fact('partners_count') . ' санаториев-партнёров в Азербайджане и Грузии. Каталог регулярно пополняется.')],
            ['id' => 'klienty-iz-drugih-stran', 'q' => 'Работаете только с жителями Азербайджана?', 'a' => 'Нет, работаем с клиентами из любой страны: России, Казахстана, Узбекистана и других.'],
            ['id' => 'kak-zabronirovat', 'q' => 'Как забронировать санаторий?', 'a' => 'Онлайн через сайт или по телефону. После подтверждения придёт письмо с деталями бронирования.'],
            ['id' => 'predoplata', 'q' => 'Нужна ли предоплата?', 'a' => e('Нет. ' . company_fact('prepayment_policy') . '. ' . company_fact('payment_methods') . '. Карта нужна только как гарантия.')],
            ['id' => 'posle-zayavki', 'q' => 'Что происходит после заявки?', 'a' => 'Связываемся с санаторием и присылаем подтверждение, обычно в течение нескольких часов.'],
            ['id' => 'valyuta', 'q' => 'В какой валюте платить в санатории?', 'a' => 'Санатории Азербайджана: AZN, USD или картой. Санатории Грузии: GEL, USD или картой.'],
            ['id' => 'manaty', 'q' => 'Можно платить в манатах?', 'a' => 'Да, в санаториях Азербайджана — при заезде в AZN. На сайте цены можно посмотреть в USD, AZN, RUB, KZT, UZS, EUR и GEL.'],
            ['id' => 'vne-kataloga', 'q' => 'Можно забронировать санаторий вне каталога?', 'a' => 'Да. Позвоните нам, и мы проверим возможность бронирования.'],
            ['id' => 'skidki-dlya-grupp', 'q' => 'Есть ли скидки для групп?', 'a' => 'Да, условия обсуждаются индивидуально. Свяжитесь с нами для специального предложения.'],
            ['id' => 'loyalnost', 'q' => 'Есть ли программа лояльности?', 'a' => 'Да, для повторных гостей действуют особые условия и привилегии.'],
            ['id' => 'stoimost-uslug', 'q' => 'Нужно ли платить GoSanatorium за услуги?', 'a' => 'Нет. Подбор, консультация и помощь с бронированием бесплатны.'],
        ]],
        ['id' => 'otmena', 'icon' => 'fa-calendar-times', 'emoji' => '↩️', 'title' => 'Отмена', 'items' => [
            ['id' => 'otmena-bronirovaniya', 'q' => 'Можно ли отменить бронирование?', 'a' => e('Да. ' . company_fact('cancellation_full') . ' Уведомить нужно письменно.')],
        ]],
        ['id' => 'lechenie-i-dokumenty', 'icon' => 'fa-stethoscope', 'emoji' => '🏥', 'title' => 'Лечение и документы', 'items' => [
            ['id' => 'vybor-sanatoriya', 'q' => 'Как помогают с выбором санатория?', 'a' => 'Специалист выясняет диагноз, цели, бюджет и предпочтения. Консультация бесплатная.'],
            ['id' => 'kto-konsultiruet', 'q' => 'Кто проводит медицинскую консультацию?', 'a' => 'Наш врач Рена Кенгерли, дерматовенеролог. Консультация бесплатная — <a href="' . e($gsConsultUrl) . '">записаться на консультацию врача</a>.'],
            ['id' => 'napravlenie', 'q' => 'Нужно ли направление от врача?', 'a' => 'Нет. Курортный врач осмотрит на месте. Рекомендуется взять анализы и список препаратов.'],
            ['id' => 'tolko-lechenie', 'q' => 'Подходит ли санаторий только для лечения?', 'a' => 'Нет, санаторий подходит и для профилактики, восстановления, реабилитации и оздоровления.'],
            ['id' => 'bezopasnost', 'q' => 'Безопасно ли санаторное лечение?', 'a' => 'Да. Все процедуры назначаются врачом после осмотра и проводятся под медицинским контролем.'],
            ['id' => 'zabolevaniya', 'q' => 'Какие заболевания лечат?', 'a' => 'Суставы, почки, дыхание, пищеварение, сердце, нервная система, диабет, онкореабилитация, гинекология, кожа, психоэмоциональные расстройства.'],
            ['id' => 'naftalanovoe-lechenie', 'q' => 'Что такое нафталановое лечение?', 'a' => 'Ванны с нафталаном — нефтепродуктом, который добывают только в Нафталане (Азербайджан). Применяется при заболеваниях суставов и кожи.'],
            ['id' => 'dokumenty', 'q' => 'Какие документы взять в санаторий?', 'a' => 'Паспорт и подтверждение брони. Рекомендуются анализы и список постоянно принимаемых препаратов.'],
        ]],
        ['id' => 'transfer-i-doroga', 'icon' => 'fa-car', 'emoji' => '🚗', 'title' => 'Трансфер и дорога', 'items' => [
            ['id' => 'transfer-iz-aeroporta', 'q' => 'Организуете трансфер из аэропорта?', 'a' => 'Да. Водитель встретит вас в аэропорту Баку, Гянджи или Тбилиси. Цены и заказ — на странице <a href="' . e($gsTransferUrl) . '">трансфера</a>.'],
            ['id' => 'zaderzhka-reysa', 'q' => 'Что делать при задержке рейса?', 'a' => 'Сообщите по телефону поддержки, и время встречи изменят.'],
            ['id' => 'podderzhka', 'q' => 'К кому обращаться во время пребывания?', 'a' => e('В нашу службу поддержки (' . company_fact('working_hours') . '). Решаем любые вопросы — от замены номера до изменения программы.')],
        ]],
        ['id' => 'poezdka-s-detmi', 'icon' => 'fa-child', 'emoji' => '👶', 'title' => 'Поездка с детьми', 'items' => [
            ['id' => 's-detmi', 'q' => 'Можно приехать с детьми?', 'a' => 'Да, большинство санаториев принимают детей. Подбор ведётся с учётом возраста.'],
            ['id' => 'vozrast', 'q' => 'С какого возраста принимают?', 'a' => 'Обычно с 4 лет, процедуры — с 6 лет. Нужны согласие родителей и консультация врача.'],
            ['id' => 'skidki-dlya-detey', 'q' => 'Есть ли скидки для детей?', 'a' => 'До 4 лет часто бесплатно при двух взрослых. Для детей 4–12 лет действует детский тариф.'],
        ]],
    ];
    $gsfaqTotal = array_sum(array_map(fn ($g) => count($g['items']), $gsfaqGroups));
@endphp
<section class="gsfaq-hero"><div class="gsfaq-hero-inner"><div class="container">
<div class="gsfaq-badge">💬 Ответы на ваши вопросы</div>
<h1 class="gsfaq-hero-title">Частые вопросы</h1>
<p class="gsfaq-hero-sub">Всё что нужно знать о бронировании, лечении, трансфере и оплате</p>
<div class="gsfaq-search-wrap"><input type="text" class="gsfaq-search" id="gsfaqSearch" placeholder="Поиск по вопросам..." oninput="gsfaqFilter(this.value)"><span class="gsfaq-search-icon"><i class="fas fa-search"></i></span></div>
<div class="gsfaq-stats"><div class="gsfaq-stat"><div class="gsfaq-stat-num">{{ $gsfaqTotal }}</div><div class="gsfaq-stat-lbl">Ответов</div></div><div class="gsfaq-stat"><div class="gsfaq-stat-num">{{ company_fact('partners_count') }}</div><div class="gsfaq-stat-lbl">Санаториев</div></div><div class="gsfaq-stat"><div class="gsfaq-stat-num">{{ company_fact('working_hours') }}</div><div class="gsfaq-stat-lbl">Поддержка</div></div></div>
</div></div></section>

@php ob_start(); @endphp
<section class="gsfaq-main"><div class="container"><div class="gsfaq-layout">
<div class="gsfaq-sidebar"><div class="gsfaq-cat-list">
<button type="button" class="gsfaq-cat-btn active" onclick="gsfaqCat('all',this)"><span class="gsfaq-cat-icon">📋</span><span class="gsfaq-cat-text">Все вопросы</span><span class="gsfaq-cat-count">{{ $gsfaqTotal }}</span></button>
@foreach ($gsfaqGroups as $g)
<button type="button" class="gsfaq-cat-btn" onclick="gsfaqCat('{{ $g['id'] }}',this)"><span class="gsfaq-cat-icon">{{ $g['emoji'] }}</span><span class="gsfaq-cat-text">{{ $g['title'] }}</span><span class="gsfaq-cat-count">{{ count($g['items']) }}</span></button>
@endforeach
</div></div>
<div class="gsfaq-content-area" id="gsfaqContent">
@php $gsfaqN = 0; @endphp
@foreach ($gsfaqGroups as $g)
<div class="gsfaq-group" id="{{ $g['id'] }}" data-cat="{{ $g['id'] }}">
<div class="gsfaq-group-header"><div class="gsfaq-group-icon"><i class="fas {{ $g['icon'] }}"></i></div><h2 class="gsfaq-group-title">{{ $g['title'] }}</h2></div>
<div class="gsfaq-accordion">
@foreach ($g['items'] as $it)
@php $gsfaqN++; @endphp
<div class="gsfaq-item" id="q-{{ $it['id'] }}"><button type="button" class="gsfaq-q" aria-expanded="false" aria-controls="a-{{ $it['id'] }}" onclick="gsfaqToggle(this.parentNode)"><span class="gsfaq-qnum">{{ $gsfaqN }}</span><h3 class="gsfaq-qtext">{{ $it['q'] }}</h3><span class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></span></button><div class="gsfaq-ans" id="a-{{ $it['id'] }}">{!! $it['a'] !!}</div></div>
@endforeach
</div></div>
@endforeach
</div></div></div></section>
@php $gsfaqHtml = ob_get_clean(); echo $gsfaqHtml; @endphp
{{-- Task 14: FAQPage JSON-LD from the questions shown on this page --}}
{!! \App\Support\StructuredData::script(\App\Support\StructuredData::faq(\App\Support\StructuredData::faqFromHtml($gsfaqHtml, 'gsfaq-qtext', 'gsfaq-ans'))) !!}

<section class="gsfaq-cta"><div class="container"><div class="gsfaq-cta-inner">
<h2 class="gsfaq-cta-title">Не нашли ответ на свой вопрос?</h2>
<p class="gsfaq-cta-sub">Наши специалисты готовы ответить бесплатно</p>
<div class="gsfaq-cta-btns">
<a href="{{ company_fact('whatsapp_url') }}" class="gsfaq-btn-white"><i class="fab fa-whatsapp"></i> Написать в WhatsApp</a>
<a href="tel:{{ company_fact('phone_main_tel') }}" class="gsfaq-btn-outline"><i class="fas fa-phone"></i> {{ company_fact('phone_main') }}</a>
</div>
</div></div></section>
<script>
function gsfaqToggle(el){var o=el.classList.contains('open');document.querySelectorAll('.gsfaq-item.open').forEach(function(i){i.classList.remove('open');i.querySelector('.gsfaq-q').setAttribute('aria-expanded','false')});if(!o){el.classList.add('open');el.querySelector('.gsfaq-q').setAttribute('aria-expanded','true');}}
function gsfaqHash(){var h=decodeURIComponent((location.hash||'').slice(1));if(!h)return;var el=document.getElementById(h);if(!el)return;if(el.classList.contains('gsfaq-item')){if(!el.classList.contains('open'))gsfaqToggle(el);}else if(el.classList.contains('gsfaq-group')){var f=el.querySelector('.gsfaq-item');if(f&&!f.classList.contains('open'))gsfaqToggle(f);}el.scrollIntoView({block:'start'});}
document.addEventListener('DOMContentLoaded',gsfaqHash);window.addEventListener('hashchange',gsfaqHash);
function gsfaqCat(cat,btn){document.querySelectorAll('.gsfaq-cat-btn').forEach(function(b){b.classList.remove('active')});btn.classList.add('active');document.querySelectorAll('.gsfaq-group').forEach(function(g){g.style.display=(cat==='all'||g.dataset.cat===cat)?'block':'none'});}
function gsfaqFilter(v){v=v.toLowerCase().trim();document.querySelectorAll('.gsfaq-item').forEach(function(i){var t=i.querySelector('.gsfaq-qtext').textContent.toLowerCase();var a=i.querySelector('.gsfaq-ans').textContent.toLowerCase();i.style.display=(v===''||t.includes(v)||a.includes(v))?'':'none'});document.querySelectorAll('.gsfaq-group').forEach(function(g){var s=Array.from(g.querySelectorAll('.gsfaq-item')).some(function(i){return i.style.display!=='none'});g.style.display=s?'block':'none'});}
</script>
@endsection
