<style>
.gscons2-wrap{background:#f8f9fa!important;padding:60px 0!important}
.gscons2-inner{max-width:900px!important;margin:0 auto!important;padding:0 32px!important}
.gscons2-heading{text-align:center!important;font-size:28px!important;font-weight:700!important;color:#1a1a2e!important;margin-bottom:8px!important}
.gscons2-sub{text-align:center!important;font-size:16px!important;color:#666!important;margin-bottom:40px!important}
.gscons2-card{background:#fff!important;border-radius:16px!important;box-shadow:0 4px 24px rgba(0,0,0,0.08)!important;padding:40px!important;display:flex!important;align-items:center!important;gap:40px!important}
.gscons2-photo{width:160px!important;height:160px!important;border-radius:50%!important;object-fit:cover!important;flex-shrink:0!important;border:4px solid #1a9e87!important}
.gscons2-photo-placeholder{width:160px!important;height:160px!important;border-radius:50%!important;background:linear-gradient(135deg,#1a9e87,#2ec4a9)!important;display:flex!important;align-items:center!important;justify-content:center!important;flex-shrink:0!important;border:4px solid #1a9e87!important;font-size:48px!important;color:#fff!important;font-weight:700!important}
.gscons2-info{flex:1!important}
.gscons2-name{font-size:26px!important;font-weight:700!important;color:#1a1a2e!important;margin-bottom:4px!important}
.gscons2-role{font-size:16px!important;color:#1a9e87!important;font-weight:600!important;margin-bottom:24px!important}
.gscons2-divider{border:none!important;border-top:1px solid #eee!important;margin:20px 0!important}
.gscons2-contacts{display:flex!important;flex-direction:column!important;gap:12px!important}
.gscons2-contact-row{display:flex!important;align-items:center!important;gap:12px!important;font-size:16px!important;color:#333!important}
.gscons2-contact-icon{width:36px!important;height:36px!important;border-radius:50%!important;background:linear-gradient(135deg,#1a9e87,#2ec4a9)!important;display:flex!important;align-items:center!important;justify-content:center!important;flex-shrink:0!important}
.gscons2-contact-icon svg{width:18px!important;height:18px!important;fill:#fff!important}
.gscons2-phone{font-size:22px!important;font-weight:700!important;color:#1a1a2e!important}
.gscons2-email{color:#1a9e87!important;text-decoration:none!important}
.gscons2-btn{display:inline-block!important;margin-top:24px!important;background:linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%)!important;color:#fff!important;padding:14px 32px!important;border-radius:8px!important;font-size:16px!important;font-weight:600!important;text-decoration:none!important;transition:opacity 0.2s!important}
.gscons2-btn:hover{opacity:0.9!important;color:#fff!important}
@media(max-width:768px){
.gscons2-card{flex-direction:column!important;text-align:center!important;padding:32px 24px!important}
.gscons2-contact-row{justify-content:center!important}
.gscons2-heading{font-size:22px!important}
}
</style>

@php
    try {
        $consultant = \App\Models\Consultant::where('is_active', 1)->first();
    } catch (\Throwable $e) {
        // table not created yet: keep showing the original card
        $consultant = new \App\Models\Consultant([
            'name' => 'Муртузаев Рамил',
            'role' => 'Эксперт по санаториям Нафталана',
            'phone' => '+994 51 915 88 94',
            'email' => 'info@gosanatorium.com',
            'whatsapp' => '994519158894',
            'button_text' => 'Получить консультацию →',
            'button_url' => '/ru/custom-page/consultant',
        ]);
    }
@endphp
@if($consultant)
<section class="gscons2-wrap">
  <div class="gscons2-inner">
    <h2 class="gscons2-heading">Бесплатная консультация эксперта</h2>
    <p class="gscons2-sub">Помогу подобрать санаторий под ваш запрос и бюджет</p>
    <div class="gscons2-card">
      @if($consultant->photo)
        <img src="/images/consultants/{{ $consultant->photo }}" alt="{{ $consultant->name }}" class="gscons2-photo">
      @else
        <div class="gscons2-photo-placeholder">{{ mb_substr($consultant->name, 0, 1) }}</div>
      @endif
      <div class="gscons2-info">
        <div class="gscons2-name">{{ $consultant->name }}</div>
        <div class="gscons2-role">{{ $consultant->role }}</div>
        <hr class="gscons2-divider">
        <div class="gscons2-contacts">
          @if($consultant->phone)
          <div class="gscons2-contact-row">
            <div class="gscons2-contact-icon">
              <svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
            </div>
            <a href="tel:{{ preg_replace('/[^\d+]/', '', $consultant->phone) }}" class="gscons2-phone" style="text-decoration:none!important">{{ $consultant->phone }}</a>
          </div>
          @endif
          @if($consultant->email)
          <div class="gscons2-contact-row">
            <div class="gscons2-contact-icon">
              <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </div>
            <a href="mailto:{{ $consultant->email }}" class="gscons2-email">{{ $consultant->email }}</a>
          </div>
          @endif
          @if($consultant->whatsapp)
          <div class="gscons2-contact-row">
            <div class="gscons2-contact-icon">
              <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.556 4.112 1.516 5.836L.05 23.5l5.821-1.527A11.956 11.956 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.016-1.378l-.36-.213-3.457.907.926-3.369-.234-.374A9.818 9.818 0 012.182 12C2.182 6.578 6.578 2.182 12 2.182S21.818 6.578 21.818 12 17.422 21.818 12 21.818z"/></svg>
            </div>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $consultant->whatsapp) }}" target="_blank" rel="noopener" class="gscons2-email">WhatsApp / Telegram</a>
          </div>
          @endif
        </div>
        <a href="{{ $consultant->button_url }}" class="gscons2-btn">{{ $consultant->button_text }}</a>
      </div>
    </div>
  </div>
</section>
@endif
