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
        'icon' => 'fa-credit-card',
        'title' => 'Без предоплаты',
        'description' => 'Оплата по прибытии',
        'link' => url('bez-predoplaty'),
        'color' => '#00b4d8',
    ],
    [
        'icon' => 'fa-user-md',
        'title' => 'Медицинская консультация',
        'description' => 'У нас профессиональные врачи',
        'link' => 'https://gosanatorium.com/custom-page/consultant', // Link dəyişdirin
        'color' => '#0096c7',
    ],
    [
        'icon' => 'fa-bus',
        'title' => 'Трансферы',
        'description' => 'Индивидуальные и групповые поездки',
        'link' => 'https://gosanatorium.com/custom-page/transfer', // Link dəyişdirin
        'color' => '#0077b6',
    ],
    [
        'icon' => 'fa-plane',
        'title' => 'Авиабилеты',
        'description' => 'Удобный поиск авиабилетов',
        'link' => 'https://www.aviasales.az/?params=BAK1&language=ru', // Link dəyişdirin
        'color' => '#023e8a',
    ],
    [
        'icon' => 'fa-play-circle',
        'title' => 'Видео',
        'description' => 'Видео для каждого курорта',
        'link' => 'https://gosanatorium.com/custom-page/video', // Link dəyişdirin
        'color' => '#03045e',
    ],
];
@endphp

<section class="gsfeat-section">
    <div class="container">
        <div class="gsfeat-grid">
            @foreach($features as $index => $feature)
            <a href="{{ $feature['link'] }}" class="gsfeat-card">
                <div class="gsfeat-icon-wrap">
                    <i class="fas {{ $feature['icon'] }}"></i>
                </div>
                <div class="gsfeat-title">{{ $feature['title'] }}</div>
                <div class="gsfeat-desc">{{ $feature['description'] }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
/* === FEATURES STRIP (gsfeat- prefix) === */
.tg-booking-form-area { padding-bottom: 30px !important; }
.gsfeat-section { padding: 60px 0 !important; background: #ffffff !important; border-top: 1px solid #f0f0f0 !important; border-bottom: 1px solid #f0f0f0 !important; }
.gsfeat-grid { display: grid !important; grid-template-columns: repeat(5, 1fr) !important; gap: 16px !important; }
@media(max-width:992px){ .gsfeat-grid{ grid-template-columns: repeat(3,1fr) !important; } }
@media(max-width:576px){ .gsfeat-grid{ grid-template-columns: repeat(2,1fr) !important; } }
.gsfeat-card { display: flex !important; flex-direction: column !important; align-items: center !important; text-align: center !important; padding: 32px 16px !important; border-radius: 16px !important; background: #f8fafb !important; border: 1px solid #eef1f4 !important; transition: all 0.3s ease !important; text-decoration: none !important; }
.gsfeat-card:hover { background: #fff !important; border-color: #1a9e87 !important; box-shadow: 0 8px 30px rgba(26,158,135,0.12) !important; transform: translateY(-4px) !important; }
.gsfeat-icon-wrap { width: 64px !important; height: 64px !important; border-radius: 50% !important; background: linear-gradient(135deg,#e8f7f5,#d0f0eb) !important; display: flex !important; align-items: center !important; justify-content: center !important; margin-bottom: 16px !important; transition: all 0.3s ease !important; }
.gsfeat-card:hover .gsfeat-icon-wrap { background: linear-gradient(135deg,#1a9e87,#2ec4a9) !important; }
.gsfeat-icon-wrap svg, .gsfeat-icon-wrap i { color: #1a9e87 !important; font-size: 26px !important; transition: color 0.3s ease !important; }
.gsfeat-card:hover .gsfeat-icon-wrap svg,
.gsfeat-card:hover .gsfeat-icon-wrap i { color: #ffffff !important; }
.gsfeat-title { font-size: 15px !important; font-weight: 700 !important; color: #1a1a2e !important; margin-bottom: 6px !important; text-transform: none !important; }
.gsfeat-desc { font-size: 13px !important; color: #6b7280 !important; line-height: 1.5 !important; text-transform: none !important; }
</style>
