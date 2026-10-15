{{-- 
    ============================================
    FEATURES SECTION - Modern Animated Design
    ============================================
    Place this file in: resources/views/components/features.blade.php
    Or: Modules/TourBooking/Resources/views/themes/theme1/components/features.blade.php
    
    Usage: @include('components.features')
    
    NOTE: Əgər yuxarıda boşluq varsa, əvvəlki section-a bu CSS əlavə edin:
    .previous-section { margin-bottom: 0 !important; }
--}}

@php
$features = [
    [
        'icon' => 'euro',
        'title' => 'Без предоплаты',
        'description' => 'Оплата по прибытии',
        'link' => '#', // Link dəyişdirin
        'color' => '#00b4d8',
    ],
    [
        'icon' => 'medical',
        'title' => 'Медицинская консультация',
        'description' => 'У нас профессиональные врачи',
        'link' => 'https://gosanatorium.com/custom-page/consultant', // Link dəyişdirin
        'color' => '#0096c7',
    ],
    [
        'icon' => 'transfer',
        'title' => 'Трансферы',
        'description' => 'Индивидуальные и групповые поездки',
        'link' => 'https://gosanatorium.com/custom-page/transfer', // Link dəyişdirin
        'color' => '#0077b6',
    ],
    [
        'icon' => 'airticket',
        'title' => 'Авиабилеты',
        'description' => 'Удобный поиск авиабилетов',
        'link' => 'https://www.aviasales.az/?params=BAK1&language=ru', // Link dəyişdirin
        'color' => '#023e8a',
    ],
    [
        'icon' => 'video',
        'title' => 'Видео',
        'description' => 'Видео для каждого курорта',
        'link' => 'https://gosanatorium.com/custom-page/video', // Link dəyişdirin
        'color' => '#03045e',
    ],
];
@endphp

<section class="tg-features-section">
    <div class="container">
        <div class="tg-features-wrapper">
            @foreach($features as $index => $feature)
            <a href="{{ $feature['link'] }}" class="tg-feature-card" style="--accent-color: {{ $feature['color'] }}; --delay: {{ $index * 0.1 }}s;">
                <div class="tg-feature-icon-wrapper">
                    <div class="tg-feature-icon-bg"></div>
                    <div class="tg-feature-icon">
                        @switch($feature['icon'])
                            @case('euro')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 10h12"/>
                                    <path d="M4 14h9"/>
                                    <path d="M19 6c-1.5-1.5-3.5-2-6-2-5 0-9 4-9 9s4 9 9 9c2.5 0 4.5-.5 6-2"/>
                                </svg>
                            @break
                            @case('medical')
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 8h-3V5c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v3H5c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h3v3c0 1.1.9 2 2 2h4c1.1 0 2-.9 2-2v-3h3c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2z"/>
                                </svg>
                            @break
                            @case('transfer')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8 6v6"/>
                                    <path d="M15 6v6"/>
                                    <path d="M2 12h19.6"/>
                                    <path d="M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/>
                                    <circle cx="7" cy="18" r="2"/>
                                    <path d="M9 18h5"/>
                                    <circle cx="16" cy="18" r="2"/>
                                </svg>
                            @break
                            @case('airticket')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/>
                                </svg>
                            @break
                            @case('video')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polygon points="10,8 16,12 10,16" fill="currentColor" stroke="none"/>
                                </svg>
                            @break
                        @endswitch
                    </div>
                </div>
                <div class="tg-feature-content">
                    <h4 class="tg-feature-title">{{ $feature['title'] }}</h4>
                    <p class="tg-feature-description">{{ $feature['description'] }}</p>
                </div>
                <div class="tg-feature-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
/* ============================================
   FEATURES SECTION - Modern Design
   ============================================ */

/* FIX: Reduce booking form bottom padding when features section follows */
.tg-booking-form-area {
    padding-bottom: 30px !important;
}

/* Features section - minimal spacing */
.tg-features-section {
    padding: 10px 0 60px 0;
    margin: 0;
    background: linear-gradient(180deg, #f8fcff 0%, #ffffff 100%);
    overflow: hidden;
}

/* Override container top spacing */
.tg-features-section > .container {
    padding-top: 0 !important;
    margin-top: 0 !important;
}

.tg-features-section > .container > .tg-features-wrapper {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Remove bottom margin from previous sibling section */
.tg-features-section {
    position: relative;
}

.tg-features-wrapper {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    margin-top: 0;
    padding-top: 0;
}

/* Feature Card */
.tg-feature-card {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 30px 20px;
    background: #fff;
    border-radius: 20px;
    text-decoration: none !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    animation: fadeInUp 0.6s ease forwards;
    animation-delay: var(--delay);
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tg-feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-color);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.tg-feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.tg-feature-card:hover::before {
    transform: scaleX(1);
}

/* Icon Wrapper */
.tg-feature-icon-wrapper {
    position: relative;
    width: 80px;
    height: 80px;
    margin-bottom: 20px;
}

.tg-feature-icon-bg {
    position: absolute;
    inset: 0;
    background: var(--accent-color);
    opacity: 0.1;
    border-radius: 50%;
    transition: all 0.4s ease;
}

.tg-feature-card:hover .tg-feature-icon-bg {
    opacity: 0.2;
    transform: scale(1.1);
    border-radius: 30%;
    animation: morphBg 2s ease-in-out infinite;
}

@keyframes morphBg {
    0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
    25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
    50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
    75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
}

.tg-feature-icon {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-color);
    transition: all 0.4s ease;
}

.tg-feature-icon svg {
    width: 40px;
    height: 40px;
    transition: all 0.4s ease;
}

.tg-feature-card:hover .tg-feature-icon svg {
    transform: scale(1.15);
}

/* Pulse Animation on Hover */
.tg-feature-card:hover .tg-feature-icon::after {
    content: '';
    position: absolute;
    width: 80px;
    height: 80px;
    border: 2px solid var(--accent-color);
    border-radius: 50%;
    animation: pulse 1.5s ease-out infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 0.5;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Content */
.tg-feature-content {
    flex: 1;
}

.tg-feature-title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0 0 8px 0;
    transition: color 0.3s ease;
    line-height: 1.3;
}

.tg-feature-card:hover .tg-feature-title {
    color: var(--accent-color);
}

.tg-feature-description {
    font-size: 13px;
    color: #6c757d;
    margin: 0;
    line-height: 1.5;
    transition: color 0.3s ease;
}

/* Arrow */
.tg-feature-arrow {
    position: absolute;
    bottom: 15px;
    right: 15px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-color);
    border-radius: 50%;
    opacity: 0;
    transform: translateX(-10px);
    transition: all 0.3s ease;
}

.tg-feature-arrow svg {
    width: 14px;
    height: 14px;
    color: #fff;
}

.tg-feature-card:hover .tg-feature-arrow {
    opacity: 1;
    transform: translateX(0);
}

/* ============================================
   RESPONSIVE DESIGN
   ============================================ */

@media (max-width: 1200px) {
    .tg-features-wrapper {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .tg-features-section {
        padding: 10px 0 40px 0;
    }
    
    .tg-features-wrapper {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .tg-feature-card {
        padding: 25px 15px;
    }
    
    .tg-feature-icon-wrapper {
        width: 65px;
        height: 65px;
    }
    
    .tg-feature-icon svg {
        width: 32px;
        height: 32px;
    }
    
    .tg-feature-title {
        font-size: 14px;
    }
    
    .tg-feature-description {
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .tg-features-wrapper {
        grid-template-columns: 1fr;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .tg-feature-card {
        flex-direction: row;
        text-align: left;
        padding: 20px;
        gap: 15px;
    }
    
    .tg-feature-icon-wrapper {
        width: 55px;
        height: 55px;
        margin-bottom: 0;
        flex-shrink: 0;
    }
    
    .tg-feature-icon svg {
        width: 28px;
        height: 28px;
    }
    
    .tg-feature-arrow {
        position: relative;
        bottom: auto;
        right: auto;
        opacity: 1;
        transform: none;
        flex-shrink: 0;
    }
}

/* ============================================
   ALTERNATIVE STYLES (Optional)
   ============================================ */

/* Style 2: Horizontal Line Layout */
.tg-features-section.style-horizontal .tg-features-wrapper {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.tg-features-section.style-horizontal .tg-feature-card {
    flex: 1;
    min-width: 180px;
    border-radius: 0;
    box-shadow: none;
    border-right: 1px solid #eee;
}

.tg-features-section.style-horizontal .tg-feature-card:last-child {
    border-right: none;
}

/* Style 3: Minimal */
.tg-features-section.style-minimal {
    background: transparent;
}

.tg-features-section.style-minimal .tg-feature-card {
    background: transparent;
    box-shadow: none;
}

.tg-features-section.style-minimal .tg-feature-card:hover {
    background: #fff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}
</style>