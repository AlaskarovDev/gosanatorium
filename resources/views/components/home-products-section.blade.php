@php($featuredProducts = $featuredProducts ?? collect())
<style>
.gshome-products{background:#fff!important;padding:60px 20px!important}
.gshome-products-inner{max-width:1100px!important;margin:0 auto!important}
.gshome-products-header{text-align:center!important;margin-bottom:40px!important}
.gshome-products-label{display:inline-block!important;background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;color:#fff!important;font-size:13px!important;font-weight:600!important;padding:4px 14px!important;border-radius:20px!important;margin-bottom:12px!important}
.gshome-products-title{font-size:30px!important;font-weight:700!important;color:#1a2a2a!important;margin:0 0 10px!important}
.gshome-products-sub{font-size:16px!important;color:#666!important;margin:0!important}
.gshome-products-grid{display:grid!important;grid-template-columns:repeat(auto-fill,minmax(220px,1fr))!important;gap:24px!important;margin-bottom:32px!important}
.gshome-pcard{background:#f9f9f9!important;border-radius:12px!important;overflow:hidden!important;box-shadow:0 2px 12px rgba(0,0,0,0.06)!important}
.gshome-pcard-img{width:100%!important;height:180px!important;object-fit:cover!important;display:block!important}
.gshome-pcard-body{padding:16px!important}
.gshome-pcard-name{font-size:15px!important;font-weight:600!important;color:#1a2a2a!important;margin-bottom:8px!important;line-height:1.4!important}
.gshome-pcard-price{font-size:18px!important;font-weight:800!important;color:#1a9e87!important;margin-bottom:12px!important}
.gshome-pcard-btn{display:block!important;background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;color:#fff!important;text-align:center!important;padding:10px!important;border-radius:8px!important;font-size:14px!important;font-weight:700!important;text-decoration:none!important}
.gshome-products-more{text-align:center!important}
.gshome-products-allbtn{display:inline-block!important;border:2px solid #1a9e87!important;color:#1a9e87!important;padding:12px 32px!important;border-radius:8px!important;font-size:15px!important;font-weight:700!important;text-decoration:none!important}
@media(max-width:600px){.gshome-products-grid{grid-template-columns:repeat(2,1fr)!important}}
</style>
<section class="gshome-products">
  <div class="gshome-products-inner">
    <div class="gshome-products-header">
      <span class="gshome-products-label">Нафталановые продукты</span>
      <h2 class="gshome-products-title">Магазин нафталановых средств</h2>
      <p class="gshome-products-sub">Кремы, масла и косметика на основе нафталановой нефти — доставка по Азербайджану</p>
    </div>
    @if($featuredProducts->count() > 0)
    <div class="gshome-products-grid">
      @foreach($featuredProducts as $product)
      <div class="gshome-pcard">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="gshome-pcard-img">
        <div class="gshome-pcard-body">
          <div class="gshome-pcard-name">{{ $product->name }}</div>
          <div class="gshome-pcard-price">{{ $product->price }} ₼</div>
          <a href="{{ $product->whatsapp_link }}" class="gshome-pcard-btn" target="_blank" rel="noopener">🛒 Заказать</a>
        </div>
      </div>
      @endforeach
    </div>
    @endif
    <div class="gshome-products-more">
      <a href="/{{ app()->getLocale() }}/naftalan-produkty" class="gshome-products-allbtn">Смотреть все продукты →</a>
    </div>
  </div>
</section>
