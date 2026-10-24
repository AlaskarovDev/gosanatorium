@php
    // Table is created by a migration; skip the section until it has been run
    $homeDiseases = \Illuminate\Support\Facades\Schema::hasTable('diseases')
        ? \Modules\TourBooking\App\Models\Disease::active()->ordered()->take(6)->get()
        : collect();

    $u = 'https://images.unsplash.com/photo-';
    $diseaseImages = [
        'psoria' => $u . '1631815588090-d4bfec5b1ccb?w=600&q=80',
        'sustav' => $u . '1559757148-5c350d0d3c56?w=600&q=80',
        'kozh' => $u . '1576091160550-2173dba999ef?w=600&q=80',
        'nerv' => $u . '1559757175-0eb30cd8c063?w=600&q=80',
        'pishchev' => $u . '1505751172876-fa1923c5c528?w=600&q=80',
        'opor' => $u . '1571019613454-1cb2f99b2d8b?w=600&q=80',
    ];
    $diseaseImageKeys = [
        'psoria' => ['psoria', 'псориаз'],
        'sustav' => ['sustav', 'сустав'],
        'kozh' => ['kozh', 'кож'],
        'nerv' => ['nerv', 'нервн'],
        'pishchev' => ['pishchev', 'pischev', 'pishev', 'пищевар', 'digest'],
        'opor' => ['opor', 'dvigat', 'опорно', 'musculo'],
    ];
    $diseaseImageFor = function ($disease, $index) use ($diseaseImages, $diseaseImageKeys) {
        $hay = mb_strtolower($disease->slug . ' ' . ($disease->name_ru ?? '') . ' ' . ($disease->name_en ?? ''));
        foreach ($diseaseImageKeys as $key => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($hay, $needle)) {
                    return $diseaseImages[$key];
                }
            }
        }
        return array_values($diseaseImages)[$index % count($diseaseImages)];
    };
@endphp

@if ($homeDiseases->count() > 0)
    <style>
        .home-diseases-section { background: #f8f9fa; padding: 50px 0; }
        .home-diseases-inner { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
        .home-diseases-title { text-align: center; font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
        .home-diseases-sub { text-align: center; color: #666; margin-bottom: 32px; }
        .home-diseases-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 20px; }
        .home-dg-card { position: relative !important; display: block !important; height: 220px; border-radius: 14px; overflow: hidden; text-decoration: none; background-color: #e9ecef; box-sizing: border-box; }
        .home-dg-bg { position: absolute; inset: 0; background-size: cover !important; background-position: center !important; transition: transform 0.3s ease; }
        .home-dg-card:hover .home-dg-bg { transform: scale(1.04); }
        .home-dg-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.6)); border-radius: inherit; }
        .home-dg-title { position: absolute; bottom: 0; left: 0; right: 0; z-index: 2; padding: 16px; color: #fff; font-size: 18px; font-weight: 700; line-height: 1.3; text-align: left; }
        .home-diseases-btn { display: inline-block; background: #1a5c36; color: #fff; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; }
        .home-diseases-btn:hover { background: #154d2e; color: #fff; }
        @media (max-width: 768px) { .home-diseases-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 480px) { .home-diseases-grid { grid-template-columns: 1fr !important; } .home-dg-card { height: 180px; } }
    </style>

    <section id="section-diseases" class="home-diseases-section">
        <div class="home-diseases-inner">
            <h2 class="home-diseases-title">{{ \Modules\TourBooking\App\Models\Disease::t('Find a Sanatorium for Your Condition') }}</h2>

            <div class="home-diseases-grid">
                @foreach ($homeDiseases as $i => $disease)
                    <a href="{{ route('diseases.show', ['slug' => $disease->slug]) }}" class="home-dg-card">
                        <div class="home-dg-bg" role="img" aria-label="{{ $disease->name }}" style="background-image: url('{{ $diseaseImageFor($disease, $i) }}') !important;"></div>
                        <div class="home-dg-overlay"></div>
                        <span class="home-dg-title">{{ $disease->name }}</span>
                    </a>
                @endforeach
            </div>

            <div style="text-align:center; margin-top:24px;">
                <a href="{{ route('diseases.index') }}" class="home-diseases-btn">{{ \Modules\TourBooking\App\Models\Disease::t('View All') }} →</a>
            </div>
        </div>
    </section>
@endif
