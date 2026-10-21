@php
    $gscFaq = [
        ['q' => 'Можно ли ехать в Нафталан при онкологии?', 'a' => 'Нет. Онкологические заболевания являются абсолютным противопоказанием к нафталановому лечению.'],
        ['q' => 'Можно ли лечиться в Нафталане при гипертонии?', 'a' => 'При гипертонии I–II степени лечение возможно под контролем врача. Гипертония III степени является абсолютным противопоказанием.'],
        ['q' => 'Можно ли детям принимать нафталановые ванны?', 'a' => 'Нафталановое лечение не рекомендуется лицам до 18 лет из-за мощного гормонального воздействия нафталена.'],
        ['q' => 'Можно ли ехать в Нафталан при сахарном диабете?', 'a' => 'При сахарном диабете лечение возможно с индивидуальным подбором дозировки под наблюдением врача санатория.'],
        ['q' => 'Проверяют ли противопоказания в санатории Нафталана?', 'a' => 'Да. При заезде в каждый санаторий Нафталана проводится обязательный медицинский осмотр и назначается индивидуальная программа лечения.'],
    ];
    $gscSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($i) => [
            '@type' => 'Question',
            'name' => $i['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $i['a']],
        ], $gscFaq),
    ];
@endphp
@extends('layout_inner_page')

@section('title')
    <title>Противопоказания к нафталановому лечению 2026 — полный список | GoSanatorium</title>
    <meta name="title" content="Противопоказания к нафталановому лечению 2026 — полный список | GoSanatorium">
    <meta name="description" content="Полный список противопоказаний к нафталановому лечению. Кому нельзя принимать нафталановые ванны, абсолютные и относительные противопоказания. Консультация бесплатно.">
    <script type="application/ld+json">{!! json_encode($gscSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endsection

@push('style_section')
<style>
#header-sticky,#header-sticky.tg-transparent{background-color:#1a2a2a!important}
#header-sticky .tg-header-contact-number,#header-sticky .tg-header-contact-number span,#header-sticky a,#header-sticky .tg-header-menu-bar,#header-sticky .tg-header-menu-bar span{color:#fff!important}
#header-sticky .tg-header-border{background-color:#fff!important}
.gscontra-wrap{max-width:860px!important;margin:0 auto!important;padding:40px 20px!important}
.gscontra-section{margin-bottom:48px!important}
.gscontra-list{list-style:none!important;padding:0!important;margin:0!important}
.gscontra-list li{padding:12px 16px!important;margin-bottom:8px!important;border-radius:8px!important;display:flex!important;align-items:flex-start!important;gap:12px!important;font-size:16px!important;line-height:1.5!important}
.gscontra-abs li{background:#fff5f5!important;border-left:4px solid #e74c3c!important;color:#2d2d2d!important}
.gscontra-rel li{background:#fffbf0!important;border-left:4px solid #f39c12!important;color:#2d2d2d!important}
.gscontra-icon{font-size:18px!important;flex-shrink:0!important;margin-top:2px!important}
.gscontra-special{display:flex!important;gap:20px!important;flex-wrap:wrap!important;margin-top:20px!important}
.gscontra-box{flex:1!important;min-width:260px!important;background:#f8f9fa!important;border-radius:12px!important;padding:24px!important;border-top:4px solid #1a9e87!important}
.gscontra-box h4{margin:0 0 12px!important;font-size:17px!important;color:#1a2a2a!important}
.gscontra-box p{margin:0!important;font-size:15px!important;color:#444!important;line-height:1.6!important}
.gscontra-cta{background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;padding:48px 40px!important;border-radius:16px!important;text-align:center!important;color:#fff!important;margin-top:48px!important}
.gscontra-cta h2{color:#fff!important;margin:0 0 12px!important;font-size:24px!important}
.gscontra-cta p{color:rgba(255,255,255,0.9)!important;font-size:16px!important;margin:0 0 24px!important}
.gscontra-btn{display:inline-block!important;background:#fff!important;color:#1a9e87!important;padding:14px 28px!important;border-radius:8px!important;font-weight:700!important;text-decoration:none!important;margin:6px!important;font-size:15px!important}
.gscontra-btn:hover{background:#f0f0f0!important;color:#1a9e87!important;text-decoration:none!important}
.gscontra-wrap h1{margin:0 0 24px!important;padding-top:80px!important}
.gscontra-wrap h2,.gscontra-wrap h3{margin:0 0 12px!important}
.gscontra-wrap p.gscontra-sub{margin:0 0 16px!important}
</style>
@endpush

@section('front-content')
<div class="gscontra-wrap">
    <section class="gscontra-section">
        <h1>Противопоказания к лечению нафталаном</h1>
        <p>Нафталановое лечение — одно из самых мощных бальнеологических воздействий в мире. Именно поэтому, как и любая серьёзная медицинская процедура, оно имеет противопоказания.</p>
        <p>Перед поездкой в Нафталан важно убедиться, что лечение вам подходит. Наш эксперт бесплатно ответит на ваши вопросы и поможет определить, подойдёт ли нафталановое лечение именно вам.</p>
    </section>

    <section class="gscontra-section">
        <h2>Абсолютные противопоказания</h2>
        <p class="gscontra-sub">Нафталановое лечение категорически запрещено при следующих состояниях:</p>
        <ul class="gscontra-list gscontra-abs">
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Злокачественные опухоли (онкология)</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Туберкулёз в активной форме</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Острые воспалительные заболевания</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Сердечная недостаточность II–III степени</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Тромбофлебит и тромбоэмболия</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Тяжёлая анемия</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Кровотечения любой локализации</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Тиреотоксикоз (гиперфункция щитовидной железы)</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Гипертоническая болезнь III стадии</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Беременность</span></li>
            <li><span class="gscontra-icon" style="color:#e74c3c !important">✗</span><span>Психические расстройства в острой стадии</span></li>
        </ul>
    </section>

    <section class="gscontra-section">
        <h2>Относительные противопоказания</h2>
        <p class="gscontra-sub">При следующих состояниях лечение возможно только с разрешения врача:</p>
        <ul class="gscontra-list gscontra-rel">
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Сахарный диабет (требует индивидуального подбора дозировки)</span></li>
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Артериальная гипертония I–II степени (под контролем)</span></li>
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Мочекаменная болезнь</span></li>
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Хронические заболевания почек</span></li>
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Варикозное расширение вен (ограниченные процедуры)</span></li>
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Недавно перенесённые операции (менее 6 месяцев назад)</span></li>
            <li><span class="gscontra-icon" style="color:#f39c12 !important">⚠</span><span>Острые респираторные заболевания</span></li>
        </ul>
    </section>

    <section class="gscontra-section">
        <h3>Особые категории пациентов</h3>
        <div class="gscontra-special">
            <div class="gscontra-box">
                <h4>Дети до 18 лет</h4>
                <p>Нафталановое лечение не рекомендуется для лиц моложе 18 лет. Нафтален обладает мощным гормональным воздействием, которое противопоказано растущему организму.</p>
            </div>
            <div class="gscontra-box">
                <h4>Пожилые пациенты (65+)</h4>
                <p>Лечение возможно в щадящем режиме: сокращённое время процедур, пониженная температура ванн, усиленный врачебный контроль. Обязательна консультация с врачом до поездки.</p>
            </div>
        </div>
    </section>

    <section class="gscontra-section">
        <h2>Как проверяют противопоказания в санатории</h2>
        <h3>Что происходит по приезду</h3>
        <p>В каждом санатории Нафталана при заезде проводится обязательный медицинский осмотр. Врач измеряет давление, изучает анамнез и историю болезни. Дозировка нафталановых ванн (количество минут) назначается строго индивидуально. Если врач санатория выявит противопоказания — он скорректирует программу или заменит процедуры.</p>
    </section>

    <section class="gscontra-cta">
        <h2>Не уверены, подходит ли вам лечение в Нафталане?</h2>
        <p>Эксперт GoSanatorium бесплатно ответит на ваши вопросы — просто напишите нам</p>
        <a class="gscontra-btn" href="https://wa.me/994519158894" target="_blank" rel="noopener">WhatsApp</a>
        <a class="gscontra-btn" href="mailto:info@gosanatorium.com">Email</a>
    </section>
</div>
@endsection
