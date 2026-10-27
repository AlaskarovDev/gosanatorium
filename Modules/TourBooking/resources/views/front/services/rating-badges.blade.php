@if (!is_null($service?->rating_general) && !is_null($service?->rating_treatment))
    <div class="gs-rating-badges {{ $class ?? '' }}">
        <div class="gs-rating-badges__row">
            <div class="gs-rating-badge gs-rating-badge--general">
                <div class="gs-rating-badge__num">{{ number_format($service->rating_general, 1) }}</div>
                <div class="gs-rating-badge__label">Общий</div>
            </div>
            <div class="gs-rating-badge gs-rating-badge--treatment">
                <div class="gs-rating-badge__num">{{ number_format($service->rating_treatment, 1) }}</div>
                <div class="gs-rating-badge__label">Лечение</div>
            </div>
        </div>
        @if ($service->reviews_count > 0)
            <div class="gs-rating-badges__count">Отзывы гостей: {{ $service->reviews_count }}</div>
        @endif
    </div>
@endif
