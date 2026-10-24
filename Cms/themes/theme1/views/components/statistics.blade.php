@php
    $statistics = getContent('theme1_statistics.content', true);

    $stats = [
        [
            'value' => getTranslatedValue($statistics, 'stat_1_value') ?: '10+',
            'label' => getTranslatedValue($statistics, 'stat_1_label') ?: 'Лет опыта',
            'icon' => 'fa-calendar-check',
        ],
        [
            'value' => getTranslatedValue($statistics, 'stat_2_value') ?: '28765+',
            'label' => getTranslatedValue($statistics, 'stat_2_label') ?: 'Довольных клиентов',
            'icon' => 'fa-users',
        ],
        [
            'value' => getTranslatedValue($statistics, 'stat_3_value') ?: '50+',
            'label' => getTranslatedValue($statistics, 'stat_3_label') ?: 'Санаториев-партнёров',
            'icon' => 'fa-heart',
        ],
        [
            'value' => getTranslatedValue($statistics, 'stat_4_value') ?: '1900+',
            'label' => getTranslatedValue($statistics, 'stat_4_label') ?: 'Отзывов и оценок',
            'icon' => 'fa-star',
        ],
    ];
@endphp

<!-- statistics-counter-area-start -->
<section class="gsstat-section">
    <div class="container">
        <div class="gsstat-grid">
            @foreach($stats as $index => $stat)
                <div class="gsstat-item">
                    <span class="gsstat-icon"><i class="fas {{ $stat['icon'] }}"></i></span>
                    <div class="gsstat-number">{{ $stat['value'] }}</div>
                    <div class="gsstat-label">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- statistics-counter-area-end -->

<style>
/* === STATS BAR (gsstat- prefix) === */
.gsstat-section { padding: 60px 0 !important; background: linear-gradient(135deg,#1a9e87 0%,#2ec4a9 50%,#17b09a 100%) !important; }
.gsstat-grid { display: grid !important; grid-template-columns: repeat(4,1fr) !important; gap: 0 !important; }
@media(max-width:768px){ .gsstat-grid{ grid-template-columns: repeat(2,1fr) !important; } }
.gsstat-item { text-align: center !important; padding: 32px 20px !important; position: relative !important; }
.gsstat-item:not(:last-child)::after { content:'' !important; position:absolute !important; right:0 !important; top:25% !important; height:50% !important; width:1px !important; background:rgba(255,255,255,0.25) !important; }
.gsstat-icon { font-size: 28px !important; color: rgba(255,255,255,0.8) !important; margin-bottom: 12px !important; display: block !important; }
.gsstat-number { font-size: 48px !important; font-weight: 900 !important; color: #ffffff !important; line-height: 1 !important; margin-bottom: 8px !important; letter-spacing: -1px !important; }
.gsstat-label { font-size: 14px !important; color: rgba(255,255,255,0.85) !important; font-weight: 500 !important; text-transform: none !important; }
</style>
