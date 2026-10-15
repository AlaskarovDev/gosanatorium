@php
    $statistics = getContent('theme1_statistics.content', true);

    $stats = [
        [
            'value' => getTranslatedValue($statistics, 'stat_1_value') ?: '10+',
            'label' => getTranslatedValue($statistics, 'stat_1_label') ?: 'Лет опыта',
            'icon' => 'calendar',
        ],
        [
            'value' => getTranslatedValue($statistics, 'stat_2_value') ?: '50 000+',
            'label' => getTranslatedValue($statistics, 'stat_2_label') ?: 'Довольных клиентов',
            'icon' => 'users',
        ],
        [
            'value' => getTranslatedValue($statistics, 'stat_3_value') ?: '50+',
            'label' => getTranslatedValue($statistics, 'stat_3_label') ?: 'Санаториев-партнёров',
            'icon' => 'handshake',
        ],
        [
            'value' => getTranslatedValue($statistics, 'stat_4_value') ?: '2 000+',
            'label' => getTranslatedValue($statistics, 'stat_4_label') ?: 'Отзывов и оценок',
            'icon' => 'star',
        ],
    ];
@endphp

<!-- statistics-counter-area-start -->
<section class="tg-statistics-section">
    <div class="container">
        <div class="tg-statistics-banner">
            @foreach($stats as $index => $stat)
                <div class="tg-stat-item" style="--i: {{ $index }};">
                    <div class="tg-stat-icon-ring">
                        @switch($stat['icon'])
                            @case('calendar')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                    <path d="M9 16l2 2 4-4"/>
                                </svg>
                            @break
                            @case('users')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            @break
                            @case('handshake')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.42 4.58a5.4 5.4 0 0 0-7.65 0l-.77.78-.77-.78a5.4 5.4 0 0 0-7.65 0C1.46 6.7 1.33 10.28 4 13l8 8 8-8c2.67-2.72 2.54-6.3.42-8.42z"/>
                                </svg>
                            @break
                            @case('star')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @break
                        @endswitch
                    </div>
                    <div class="tg-stat-content">
                        <span class="tg-stat-number">{{ $stat['value'] }}</span>
                        <span class="tg-stat-text">{{ $stat['label'] }}</span>
                    </div>
                    @if(!$loop->last)
                        <div class="tg-stat-divider"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- statistics-counter-area-end -->

<style>
/* ============================================
   STATISTICS SECTION - Light Style
   ============================================ */
.tg-statistics-section {
    padding: 0 0 70px 0;
    background: transparent;
    overflow: hidden;
}

.tg-statistics-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #f0fdfa 0%, #e6f7f4 40%, #f0f9ff 100%);
    border-radius: 24px;
    padding: 0;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(13, 148, 136, 0.12);
    box-shadow: 0 4px 24px rgba(13, 148, 136, 0.08);
}

.tg-statistics-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(13, 148, 136, 0.06) 0%, transparent 70%);
    pointer-events: none;
}

.tg-statistics-banner::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.04) 0%, transparent 70%);
    pointer-events: none;
}

/* Stat Item */
.tg-stat-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 44px 20px;
    position: relative;
    z-index: 1;
    transition: all 0.4s ease;
}

.tg-stat-item:hover {
    background: rgba(13, 148, 136, 0.04);
}

/* Divider */
.tg-stat-divider {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 1px;
    height: 60%;
    background: linear-gradient(180deg, transparent 0%, rgba(13, 148, 136, 0.15) 50%, transparent 100%);
}

/* Icon Ring */
.tg-stat-icon-ring {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    border: 2px solid rgba(13, 148, 136, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    position: relative;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: rgba(13, 148, 136, 0.06);
}

.tg-stat-item:hover .tg-stat-icon-ring {
    border-color: rgba(13, 148, 136, 0.5);
    background: rgba(13, 148, 136, 0.1);
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(13, 148, 136, 0.12);
}

.tg-stat-icon-ring svg {
    width: 28px;
    height: 28px;
    color: #0d9488;
    transition: all 0.4s ease;
}

.tg-stat-item:hover .tg-stat-icon-ring svg {
    color: #0f766e;
    transform: scale(1.05);
}

/* Content */
.tg-stat-content {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.tg-stat-number {
    font-size: 38px;
    font-weight: 800;
    color: #1a1a2e;
    line-height: 1.1;
    letter-spacing: -0.02em;
    transition: all 0.3s ease;
}

.tg-stat-item:hover .tg-stat-number {
    color: #0d9488;
}

.tg-stat-text {
    font-size: 14px;
    color: #6b7280;
    font-weight: 400;
    line-height: 1.4;
    letter-spacing: 0.02em;
    transition: color 0.3s ease;
}

.tg-stat-item:hover .tg-stat-text {
    color: #374151;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1200px) {
    .tg-stat-number {
        font-size: 32px;
    }
    .tg-stat-item {
        padding: 36px 16px;
    }
}

@media (max-width: 991px) {
    .tg-statistics-banner {
        flex-wrap: wrap;
        border-radius: 20px;
    }
    .tg-stat-item {
        flex: 0 0 50%;
        padding: 32px 16px;
    }
    .tg-stat-divider {
        display: none;
    }
    .tg-stat-item:nth-child(1),
    .tg-stat-item:nth-child(2) {
        border-bottom: 1px solid rgba(13, 148, 136, 0.1);
    }
    .tg-stat-item:nth-child(1),
    .tg-stat-item:nth-child(3) {
        border-right: 1px solid rgba(13, 148, 136, 0.1);
    }
}

@media (max-width: 575px) {
    .tg-statistics-section {
        padding: 0 0 50px 0;
    }
    .tg-statistics-banner {
        border-radius: 16px;
    }
    .tg-stat-item {
        padding: 24px 12px;
    }
    .tg-stat-icon-ring {
        width: 52px;
        height: 52px;
        margin-bottom: 14px;
    }
    .tg-stat-icon-ring svg {
        width: 22px;
        height: 22px;
    }
    .tg-stat-number {
        font-size: 26px;
    }
    .tg-stat-text {
        font-size: 12px;
    }
}
</style>
