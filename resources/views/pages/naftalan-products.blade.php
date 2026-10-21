@extends('layout_inner_page')

@section('title')
    <title>Нафталановые продукты — купить кремы, масла и косметику | GoSanatorium</title>
    <meta name="description" content="Кремы, масла и натуральная косметика на основе нафталановой нефти. Доставка по всему Азербайджану и туристам в отелях. Заказ через WhatsApp.">
@endsection

@push('style_section')
<style>
.gsprod-hero{background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;padding:80px 20px!important;text-align:center!important}
.gsprod-hero h1{color:#fff!important;font-size:36px!important;font-weight:700!important;margin:0 0 12px!important}
.gsprod-hero p{color:rgba(255,255,255,0.9)!important;font-size:18px!important;margin:0!important}
.gsprod-wrap{max-width:1100px!important;margin:0 auto!important;padding:48px 20px!important}
.gsprod-grid{display:grid!important;grid-template-columns:repeat(auto-fill,minmax(260px,1fr))!important;gap:28px!important;margin-bottom:48px!important}
.gsprod-card{background:#fff!important;border-radius:12px!important;box-shadow:0 2px 20px rgba(0,0,0,0.08)!important;overflow:hidden!important;display:flex!important;flex-direction:column!important}
.gsprod-img{width:100%!important;height:220px!important;object-fit:cover!important;display:block!important;border-radius:12px 12px 0 0!important}
.gsprod-body{padding:20px!important;flex:1!important;display:flex!important;flex-direction:column!important}
.gsprod-name{font-size:17px!important;font-weight:700!important;color:#1a2a2a!important;margin:0 0 8px!important;line-height:1.4!important}
.gsprod-desc{font-size:14px!important;color:#666!important;line-height:1.5!important;margin:0 0 12px!important;flex:1!important}
.gsprod-price{font-size:22px!important;font-weight:800!important;color:#1a9e87!important;margin:0!important}
.gsprod-btn{display:block!important;background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;color:#fff!important;text-align:center!important;padding:12px!important;border-radius:8px!important;font-size:15px!important;font-weight:700!important;text-decoration:none!important;margin-top:16px!important}
.gsprod-empty{text-align:center!important;margin-bottom:48px!important}
.gsprod-empty p{font-size:17px!important;color:#444!important;margin:0 0 8px!important}
.gsprod-empty .gsprod-btn{display:inline-block!important;padding:12px 32px!important}
.gsprod-delivery{background:#f0faf8!important;border-radius:12px!important;padding:28px 32px!important;text-align:center!important}
.gsprod-delivery p{font-size:16px!important;color:#444!important;margin:0 0 20px!important;line-height:1.6!important}
.gsprod-delivery .gsprod-btn{display:inline-block!important;padding:12px 32px!important;margin-top:0!important}
@media(max-width:600px){.gsprod-grid{grid-template-columns:1fr!important}.gsprod-hero h1{font-size:26px!important}}
</style>
@endpush

@section('front-content')
@php($gsprodWa = 'https://wa.me/994519158894?text=' . urlencode('Здравствуйте! Хочу заказать нафталановые продукты.'))
<div class="gsprod-hero">
    <h1>Нафталановые продукты</h1>
    <p>Натуральные средства на основе нафталановой нефти — доставка по Азербайджану</p>
</div>
<div class="gsprod-wrap">
    @if($products->count() > 0)
    <div class="gsprod-grid">
        @foreach($products as $product)
        <div class="gsprod-card">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="gsprod-img" loading="lazy">
            <div class="gsprod-body">
                <h3 class="gsprod-name">{{ $product->name }}</h3>
                @if($product->description)
                <p class="gsprod-desc">{{ $product->description }}</p>
                @endif
                <p class="gsprod-price">{{ $product->price }} ₼</p>
                <a href="{{ $product->whatsapp_link }}" class="gsprod-btn" target="_blank" rel="noopener">🛒 Заказать в WhatsApp</a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="gsprod-empty">
        <p>Скоро здесь появятся товары. Напишите нам в WhatsApp для заказа.</p>
        <a href="{{ $gsprodWa }}" class="gsprod-btn" target="_blank" rel="noopener">🛒 Написать в WhatsApp</a>
    </div>
    @endif

    <div class="gsprod-delivery">
        <p>🚚 Доставка по всему Азербайджану и туристам в отелях. Оплата наличными или картой. Для заказа напишите в WhatsApp — ответим в течение 15 минут.</p>
        <a href="{{ $gsprodWa }}" class="gsprod-btn" target="_blank" rel="noopener">🛒 Заказать в WhatsApp</a>
    </div>
</div>
@endsection
