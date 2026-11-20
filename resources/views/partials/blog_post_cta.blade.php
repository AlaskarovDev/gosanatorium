{{-- Task 20: CTA inside a post — linked sanatorium card, otherwise free selection + WhatsApp --}}
@php
    $gsSvc = ($blog->linkedService && (int) $blog->linkedService->status === 1) ? $blog->linkedService : null;
    $gsWa = company_fact('whatsapp_url');
@endphp
@if ($gsSvc)
    @php
        $gsSvcUrl = route('front.tourbooking.services.show', ['slug' => $gsSvc->slug]);
        $gsSvcPrice = \Modules\TourBooking\App\Services\FromPrice::for($gsSvc);
    @endphp
    <aside class="gs-post-cta gs-post-cta--service" data-place="{{ $gsCtaPlace ?? '' }}">
        <a class="gs-post-cta__img" href="{{ $gsSvcUrl }}">
            <img {!! img_attrs($gsSvc->thumbnail?->file_path, \App\Helpers\ResponsiveImage::serviceAlt($gsSvc, $gsSvc->thumbnail?->caption), ['sizes' => '(max-width: 575px) 100vw, 220px']) !!}>
        </a>
        <div class="gs-post-cta__body">
            <div class="gs-post-cta__title"><a href="{{ $gsSvcUrl }}">{{ $gsSvc->translation?->title ?? $gsSvc->title }}</a></div>
            @if ($gsSvcPrice > 0)
                <div class="gs-post-cta__price">от {!! currency_span($gsSvcPrice) !!} <small>{{ \Modules\TourBooking\App\Services\FromPrice::shortLabel($gsSvc) }}</small></div>
            @endif
            <div class="gs-post-cta__actions">
                <a class="gs-post-cta__btn" href="{{ $gsSvcUrl }}">Проверить цены</a>
                <a class="gs-post-cta__wa" href="{{ $gsWa }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Спросить в WhatsApp</a>
            </div>
        </div>
    </aside>
@else
    <aside class="gs-post-cta" data-place="{{ $gsCtaPlace ?? '' }}">
        <div class="gs-post-cta__body">
            <div class="gs-post-cta__title">Бесплатный подбор санатория</div>
            <p class="gs-post-cta__text">Расскажите, что нужно лечить и когда хотите поехать — подберём санаторий и пришлём цены. Это бесплатно.</p>
            <div class="gs-post-cta__actions">
                <a class="gs-post-cta__wa gs-post-cta__wa--main" href="{{ $gsWa }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Написать в WhatsApp</a>
                <a class="gs-post-cta__link" href="{{ route('sanatoriums.list.alias') }}">Смотреть санатории</a>
            </div>
        </div>
    </aside>
@endif
