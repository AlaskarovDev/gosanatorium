@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
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
.gsfaq-q{display:flex;align-items:center;gap:14px;padding:18px 20px;cursor:pointer;transition:background 0.2s}
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
</style>

<section class="gsfaq-hero"><div class="gsfaq-hero-inner"><div class="container">
<div class="gsfaq-badge">💬 Часто задаваемые вопросы</div>
<h1 class="gsfaq-hero-title">Ответы на ваши вопросы</h1>
<p class="gsfaq-hero-sub">Всё что нужно знать о бронировании, лечении, трансфере и оплате</p>
<div class="gsfaq-search-wrap"><input type="text" class="gsfaq-search" id="gsfaqSearch" placeholder="Поиск по вопросам..." oninput="gsfaqFilter(this.value)"><span class="gsfaq-search-icon"><i class="fas fa-search"></i></span></div>
<div class="gsfaq-stats"><div class="gsfaq-stat"><div class="gsfaq-stat-num">30+</div><div class="gsfaq-stat-lbl">Ответов</div></div><div class="gsfaq-stat"><div class="gsfaq-stat-num">50+</div><div class="gsfaq-stat-lbl">Санаториев</div></div><div class="gsfaq-stat"><div class="gsfaq-stat-num">24/7</div><div class="gsfaq-stat-lbl">Поддержка</div></div></div>
</div></div></section>

<section class="gsfaq-main"><div class="container"><div class="gsfaq-layout">
<div class="gsfaq-sidebar"><div class="gsfaq-cat-list">
<button class="gsfaq-cat-btn active" onclick="gsfaqCat('all',this)"><span class="gsfaq-cat-icon">📋</span><span class="gsfaq-cat-text">Все вопросы</span><span class="gsfaq-cat-count">30</span></button>
<button class="gsfaq-cat-btn" onclick="gsfaqCat('booking',this)"><span class="gsfaq-cat-icon">📅</span><span class="gsfaq-cat-text">Бронирование</span><span class="gsfaq-cat-count">11</span></button>
<button class="gsfaq-cat-btn" onclick="gsfaqCat('medical',this)"><span class="gsfaq-cat-icon">🏥</span><span class="gsfaq-cat-text">Медицина</span><span class="gsfaq-cat-count">5</span></button>
<button class="gsfaq-cat-btn" onclick="gsfaqCat('transfer',this)"><span class="gsfaq-cat-icon">🚗</span><span class="gsfaq-cat-text">Трансфер</span><span class="gsfaq-cat-count">3</span></button>
<button class="gsfaq-cat-btn" onclick="gsfaqCat('payment',this)"><span class="gsfaq-cat-icon">💳</span><span class="gsfaq-cat-text">Оплата и документы</span><span class="gsfaq-cat-count">5</span></button>
<button class="gsfaq-cat-btn" onclick="gsfaqCat('children',this)"><span class="gsfaq-cat-icon">👶</span><span class="gsfaq-cat-text">Дети</span><span class="gsfaq-cat-count">3</span></button>
<button class="gsfaq-cat-btn" onclick="gsfaqCat('treatment',this)"><span class="gsfaq-cat-icon">💊</span><span class="gsfaq-cat-text">Лечение</span><span class="gsfaq-cat-count">3</span></button>
</div></div>
<div class="gsfaq-content-area" id="gsfaqContent">

<div class="gsfaq-group" data-cat="booking">
<div class="gsfaq-group-header"><div class="gsfaq-group-icon"><i class="fas fa-calendar-check"></i></div><h2 class="gsfaq-group-title">Бронирование и компания</h2></div>
<div class="gsfaq-accordion">
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">1</div><div class="gsfaq-qtext">Чем GoSanatorium отличается от других сайтов?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Мы подбираем санаторий под ваш диагноз, организуем трансфер и консультацию врача, и поддерживаем клиента 24/7.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">2</div><div class="gsfaq-qtext">Сколько санаториев в каталоге?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Более 50 в Чехии, Грузии и Азербайджане. Каталог регулярно пополняется.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">3</div><div class="gsfaq-qtext">Работаете только с жителями Азербайджана?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Нет, работаем с клиентами из любой страны: России, Казахстана, Узбекистана и других.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">4</div><div class="gsfaq-qtext">Как забронировать санаторий?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Онлайн через сайт или по телефону. После подтверждения придёт письмо с деталями бронирования.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">5</div><div class="gsfaq-qtext">Нужна ли предоплата?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Нет. Оплата производится в санатории при заезде. Карта нужна только как гарантия.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">6</div><div class="gsfaq-qtext">Можно забронировать санаторий вне каталога?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Да. Позвоните нам, и мы проверим возможность бронирования.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">7</div><div class="gsfaq-qtext">Есть ли скидки для групп?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Да, условия обсуждаются индивидуально. Свяжитесь с нами для специального предложения.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">8</div><div class="gsfaq-qtext">Что происходит после заявки?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Связываемся с санаторием и присылаем подтверждение, обычно в течение нескольких часов.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">9</div><div class="gsfaq-qtext">Можно ли отменить бронирование?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Да, без финансовых потерь при соблюдении сроков. Уведомить нужно письменно.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">10</div><div class="gsfaq-qtext">Есть ли программа лояльности?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Да, для повторных гостей действуют особые условия и привилегии.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">11</div><div class="gsfaq-qtext">Нужно ли платить GoSanatorium за услуги?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Нет. Подбор, консультация и помощь с бронированием абсолютно бесплатны.</div></div>
</div></div>

<div class="gsfaq-group" data-cat="medical">
<div class="gsfaq-group-header"><div class="gsfaq-group-icon"><i class="fas fa-stethoscope"></i></div><h2 class="gsfaq-group-title">Подбор и медицина</h2></div>
<div class="gsfaq-accordion">
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">12</div><div class="gsfaq-qtext">Как помогают с выбором санатория?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Специалист выясняет диагноз, цели, бюджет и предпочтения. Консультация бесплатная.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">13</div><div class="gsfaq-qtext">Кто проводит медицинскую консультацию?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Врач-партнёр, специалист по курортологии. Консультация организуется бесплатно.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">14</div><div class="gsfaq-qtext">Нужно ли направление от врача?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Нет. Курортный врач осмотрит на месте. Рекомендуется взять анализы и список препаратов.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">15</div><div class="gsfaq-qtext">Подходит ли санаторий только для лечения?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Нет, отлично подходит для профилактики, восстановления, реабилитации и оздоровления.</div></div>
<div class="gsfaq-item" onclick="gsfaqToggle(this)"><div class="gsfaq-q"><div class="gsfaq-qnum">16</div><div class="gsfaq-qtext">Безопасно ли санаторное лечение?</div><div class="gsfaq-qarrow"><i class="fas fa-chevron-down"></i></div></div><div class="gsfaq-ans">Да. Все процедуры назначаются врачом после осмотра и проводятся под медицинским контролем.</div></div>
</div></div>
@endsection
