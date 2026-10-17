@php
    $bpFaq = [
        ['q' => 'Мне нужно вносить депозит?', 'a' => 'Нет. При стандартном бронировании без предоплаты никакой депозит не требуется. Вы платите только по прибытии.'],
        ['q' => 'Что будет, если я не приеду?', 'a' => 'Если вы отменили бронирование в срок (за 7–14 дней), никаких штрафов нет. При незаезде без отмены санаторий может удержать стоимость 1 суток — это зависит от условий конкретного объекта.'],
        ['q' => 'Можно ли оплатить картой в санатории?', 'a' => 'Большинство наших партнёров принимают карты Visa и Mastercard. Наличными часто выгоднее — уточняйте на месте.'],
        ['q' => 'Как я получу подтверждение бронирования?', 'a' => 'После оформления заявки вы получите подтверждение на email в течение нескольких часов. В нём будут все детали: даты, стоимость, условия отмены.'],
        ['q' => 'Безопасно ли бронировать через GoSanatorium.com?', 'a' => 'Да. Мы работаем только с проверенными санаториями, с которыми у нас заключены официальные договоры. Ваши данные защищены и не передаются третьим лицам.'],
    ];
    $bpSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn ($i) => [
            '@type' => 'Question',
            'name' => $i['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $i['a']],
        ], $bpFaq),
    ];
@endphp
@extends('layout_inner_page')

@section('title')
    <title>Бронирование без предоплаты — санатории Нафталана и Грузии | GoSanatorium.com</title>
    <meta name="title" content="Бронирование без предоплаты — санатории Нафталана и Грузии | GoSanatorium.com">
    <meta name="description" content="Забронируйте санаторий в Нафталане или Грузии без предоплаты. Оплата по прибытии, бесплатная отмена за 7–14 дней, никаких скрытых комиссий. GoSanatorium.com">
@endsection

@push('style_section')
<style>
.bp-hero,.bp-cta{background:#1a5c36;color:#fff;padding:60px 20px;text-align:center;width:100%}
.bp-hero h1,.bp-cta h2{color:#fff;margin-bottom:12px}
.bp-hero p,.bp-cta p{color:#fff;font-size:18px;margin-bottom:24px}
.bp-btn{display:inline-block;background:#2ea35f;color:#fff;font-weight:600;padding:14px 32px;border-radius:8px;text-decoration:none}
.bp-btn:hover{background:#23844c;color:#fff}
.bp-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;max-width:1100px;margin:40px auto;padding:0 20px}
.bp-card{background:#fff;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.1);padding:24px;text-align:center}
.bp-card-icon{font-size:40px;margin-bottom:10px}
.bp-card h3{font-size:18px;font-weight:700;margin-bottom:8px}
.bp-card p{margin:0;font-size:15px}
.bp-article{display:grid;grid-template-columns:2fr 1fr;gap:40px;max-width:1100px;margin:0 auto 40px;padding:0 20px}
.bp-article h2{font-size:24px;margin:28px 0 12px}
.bp-article h2:first-child{margin-top:0}
.bp-sidebar{position:sticky;top:20px;align-self:start}
.bp-sidebar-box{background:#1a5c36;color:#fff;border-radius:12px;padding:24px}
.bp-sidebar-box h3{color:#fff;font-size:22px}
.bp-sidebar-box p{color:#fff}
.bp-sidebar-box .bp-btn{display:block;text-align:center;margin:16px 0}
.bp-phone{display:block;color:#fff;font-size:20px;font-weight:700;text-align:center}
.bp-phone:hover{color:#fff}
.bp-small{font-size:13px;text-align:center;margin:10px 0 0;opacity:.85}
.bp-faq{max-width:900px;margin:0 auto 50px;padding:0 20px}
.bp-faq h2{text-align:center;margin-bottom:24px}
.bp-faq details{border-bottom:1px solid #eee;padding:16px 0}
.bp-faq summary{font-weight:600;cursor:pointer;list-style:none}
.bp-faq summary::-webkit-details-marker{display:none}
.bp-faq summary::after{content:'+';float:right;font-size:22px;line-height:1;color:#1a5c36}
.bp-faq details[open] summary::after{content:'−'}
.bp-faq details p{margin:12px 0 0}
@media(max-width:768px){.bp-cards{grid-template-columns:repeat(2,1fr)}.bp-article{grid-template-columns:1fr}.bp-sidebar{position:static}}
</style>
@endpush

@section('front-content')
<section class="bp-hero">
    <h1>Бронирование без предоплаты</h1>
    <p>Забронируйте санаторий сейчас — оплатите по прибытии. Без риска, без комиссий.</p>
    <a class="bp-btn" href="{{ url('/') }}">Выбрать санаторий</a>
</section>

<section class="bp-cards">
    <div class="bp-card"><div class="bp-card-icon">🏨</div><h3>Оплата по прибытии</h3><p>Платите наличными или картой напрямую в санатории при заезде</p></div>
    <div class="bp-card"><div class="bp-card-icon">🔒</div><h3>Никакого риска</h3><p>Ваши деньги не списываются при бронировании — только при заезде</p></div>
    <div class="bp-card"><div class="bp-card-icon">❌</div><h3>Бесплатная отмена</h3><p>Отмените бронирование за 7–14 дней до заезда совершенно бесплатно</p></div>
    <div class="bp-card"><div class="bp-card-icon">💳</div><h3>Никаких комиссий</h3><p>Наш сервис бесплатен для гостей — мы не берём платы за бронирование</p></div>
</section>

<section class="bp-article">
    <article>
        <h2>Как работает бронирование без предоплаты?</h2>
        <p>При бронировании через GoSanatorium.com вы не платите ничего заранее. Мы передаём санаторию вашу заявку, они подтверждают место — и вы просто приезжаете и оплачиваете проживание и лечение на месте. Большинство наших партнёров принимают как наличные (рубли, доллары, евро), так и банковские карты.</p>
        <h2>Нужна ли карта при бронировании?</h2>
        <p>В большинстве случаев — нет. Для обычного бронирования достаточно имени и контактных данных. В редких случаях (высокий сезон, длительные заезды) санаторий может запросить реквизиты карты в качестве гарантии — без списания средств. Об этом всегда указывается в условиях конкретного предложения.</p>
        <h2>Условия бесплатной отмены</h2>
        <p>Стандартный срок бесплатной отмены — за 7–14 дней до даты заезда. Для некоторых спецпредложений срок может составлять 3–4 дня. Точные условия отмены указаны в карточке каждого санатория и в подтверждении бронирования, которое вы получите на email.</p>
        <h2>В какой валюте производится оплата?</h2>
        <p>Цены на сайте отображаются в рублях. Оплата в санатории производится в местной валюте: в Нафталане (Азербайджан) — в азербайджанских манатах (AZN), в Грузии (Саирме, Боржоми, Цхалтубо) — в грузинских лари (GEL). Актуальный курс уточняйте у администрации санатория при заезде.</p>
        <h2>Есть ли скидки за полную оплату?</h2>
        <p>Да! Некоторые санатории предлагают скидку 5–10% при оплате полного курса заранее. Такие предложения отмечены специальным значком на странице санатория. Вы всегда можете уточнить у нашего менеджера.</p>
    </article>
    <aside class="bp-sidebar">
        <div class="bp-sidebar-box">
            <h3>Есть вопросы?</h3>
            <p>Наш менеджер ответит на все вопросы по бронированию</p>
            <a class="bp-btn" href="https://wa.me/994519158894" target="_blank" rel="noopener">Написать в WhatsApp</a>
            <a class="bp-phone" href="tel:+994519158894">+994 51 915 88 94</a>
            <p class="bp-small">Работаем ежедневно 9:00–21:00</p>
        </div>
    </aside>
</section>

<section class="bp-faq">
    <h2>Часто задаваемые вопросы</h2>
    @foreach ($bpFaq as $item)
        <details>
            <summary>{{ $item['q'] }}</summary>
            <p>{{ $item['a'] }}</p>
        </details>
    @endforeach
</section>

<section class="bp-cta">
    <h2>Готовы забронировать?</h2>
    <p>Выберите санаторий — оплатите по приезду</p>
    <a class="bp-btn" href="{{ url('/') }}">Смотреть все санатории</a>
</section>

<script type="application/ld+json">{!! json_encode($bpSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endsection
