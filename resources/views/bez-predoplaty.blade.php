@php
    $bpFaq = [
        ['q' => 'Мне нужно вносить депозит?', 'a' => 'Нет. При стандартном бронировании без предоплаты никакой депозит не требуется. Вы не платите ничего до момента заезда — ни на сайте, ни нам, ни санаторию.'],
        ['q' => 'Что будет, если я не приеду?', 'a' => 'Если вы отменили бронирование вовремя (за 7–14 дней) — никаких последствий, всё бесплатно. Если не приехали и не предупредили — санаторий вправе удержать стоимость одних суток. Это зависит от конкретного объекта и указано в условиях. Именно поэтому мы всегда просим: если планы изменились — напишите нам заранее, мы всё уладим.'],
        ['q' => 'Можно ли оплатить картой в санатории?', 'a' => 'Большинство наших партнёров принимают Visa и Mastercard. Некоторые также принимают UnionPay. Наличными нередко выгоднее из-за курса обмена — уточните у администрации при заезде.'],
        ['q' => 'Как я получу подтверждение бронирования?', 'a' => 'После оформления заявки вы получите письмо на email в течение 1–3 часов. В нём будут: даты заезда и выезда, название санатория, стоимость, условия отмены и контакты для связи. Если письмо не пришло — проверьте папку "Спам" или напишите нам в WhatsApp.'],
        ['q' => 'Безопасно ли бронировать через GoSanatorium.com?', 'a' => 'Да. Мы работаем только с санаториями, с которыми у нас заключены официальные договоры. Ваши личные данные не передаются третьим лицам. Мы на рынке уже 7 лет и дорожим репутацией — каждый случай недовольного гостя для нас ЧП.'],
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
    // На этой странице часы работы в подвале должны совпадать с блоком контактов
    if (isset($footer) && is_object($footer)) {
        $footer->working_days = 'Работаем ежедневно 9:00–21:00 (GMT+4, Баку)';
    }
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
.bp-hero{padding-top:80px}
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
    <p>Приедете — понравится — оплатите. Никакого риска, никаких скрытых платежей. Более 500 довольных гостей выбрали именно этот способ.</p>
    <a class="bp-btn" href="{{ url('/') }}">Выбрать санаторий</a>
</section>

<section class="bp-cards">
    <div class="bp-card"><div class="bp-card-icon">🏨</div><h3>Оплата по прибытии</h3><p>Приезжайте, заселяйтесь, убедитесь что всё нравится — и только потом платите. Наличными или картой прямо в санатории</p></div>
    <div class="bp-card"><div class="bp-card-icon">🔒</div><h3>Никакого риска</h3><p>Мы не берём ни копейки при бронировании. Ваши деньги остаются у вас до момента заезда</p></div>
    <div class="bp-card"><div class="bp-card-icon">❌</div><h3>Бесплатная отмена</h3><p>Планы изменились? Отмените за 7–14 дней — без штрафов и объяснений. Просто напишите нам</p></div>
    <div class="bp-card"><div class="bp-card-icon">💳</div><h3>Сервис бесплатен</h3><p>Мы не берём комиссию с гостей. Наш заработок — от санаториев, а не от вас</p></div>
</section>

<section class="bp-article">
    <article>
        <h2>Как работает бронирование без предоплаты?</h2>
        <p>Всё устроено просто: вы оставляете заявку на сайте — мы передаём её в санаторий — санаторий подтверждает ваше место. Вы приезжаете и платите уже на месте: наличными или картой. Никаких предоплат, никаких замороженных денег на карте.</p>
        <p>За 7 лет работы через GoSanatorium.com прошли сотни гостей — и ни один не столкнулся с ситуацией, когда его место не было забронировано.</p>
        <h2>Нужна ли карта при бронировании?</h2>
        <p>В большинстве случаев — нет. Для стандартного бронирования достаточно вашего имени и номера телефона.</p>
        <p>В редких случаях — в высокий сезон или при длительных заездах от 21 дня — санаторий может попросить реквизиты карты как гарантию. Важно: деньги при этом не списываются. Это просто подтверждение вашего намерения приехать. Все такие условия всегда заранее указаны в карточке санатория.</p>
        <h2>Условия бесплатной отмены</h2>
        <p>Стандартный срок отмены без штрафа — за 7–14 дней до даты заезда. Для горящих предложений срок может быть короче — 3–4 дня.</p>
        <p>Точные условия вы всегда найдёте в карточке санатория и в письме-подтверждении, которое придёт на вашу почту после бронирования. Если что-то изменилось — просто напишите нам в WhatsApp, мы решим вопрос быстро.</p>
        <h2>В какой валюте производится оплата?</h2>
        <p>На сайте цены отображаются в той валюте, которую вы выбрали (рубли, доллары, евро). Это удобно для сравнения.</p>
        <p>Оплата в санатории — в местной валюте:<br>
        — Нафталан (Азербайджан) — азербайджанский манат (AZN). 1 USD ≈ 1.70 AZN<br>
        — Грузия (Саирме, Боржоми, Цхалтубо) — грузинский лари (GEL). 1 USD ≈ 2.70 GEL</p>
        <p>Большинство санаториев принимают карты Visa и Mastercard. Наличные часто выгоднее — уточняйте на месте.</p>
        <h2>Есть ли скидки за полную оплату?</h2>
        <p>Да, и иногда существенные. Ряд санаториев предлагает скидку 5–10% если вы оплачиваете полный курс при заезде или заранее банковским переводом.</p>
        <p>Такие предложения отмечены специальным значком на странице санатория. Наш менеджер также может подсказать, где сейчас действуют лучшие условия — просто спросите в WhatsApp.</p>
    </article>
    <aside class="bp-sidebar">
        <div class="bp-sidebar-box">
            <h3>Остались вопросы? Мы на связи</h3>
            <p>Наш менеджер ответит в течение нескольких минут и поможет подобрать санаторий под ваш диагноз и бюджет</p>
            <a class="bp-btn" href="https://wa.me/994519158894" target="_blank" rel="noopener">Написать в WhatsApp</a>
            <a class="bp-phone" href="tel:+994519158894">+994 51 915 88 94</a>
            <p class="bp-small">Работаем ежедневно 9:00–21:00 по Баку</p>
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
    <p>Выберите санаторий — оплатите по приезду. Без предоплаты, без риска, без лишних вопросов.</p>
    <a class="bp-btn" href="{{ url('/ru') }}">Забронировать без предоплаты</a>
</section>

<script type="application/ld+json">{!! json_encode($bpSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endsection
