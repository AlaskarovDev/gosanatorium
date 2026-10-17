@php
    // Table is created by a migration; skip the section until it has been run
    $homeDiseases = \Illuminate\Support\Facades\Schema::hasTable('diseases')
        ? \Modules\TourBooking\App\Models\Disease::active()->ordered()->take(6)->get()
        : collect();
@endphp

@if ($homeDiseases->count() > 0)
    <style>
        .home-diseases-section { background: #f8f9fa; padding: 50px 0; }
        .home-diseases-inner { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
        .home-diseases-title { text-align: center; font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
        .home-diseases-sub { text-align: center; color: #666; margin-bottom: 32px; }
        .home-diseases-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 20px; }
        .home-dg-card { position: relative; display: block !important; height: 160px; border-radius: 10px; overflow: hidden; text-decoration: none; background-color: #1a5c36; transition: transform 0.25s ease; box-sizing: border-box; }
        .home-dg-card:hover { transform: scale(1.03); }
        .home-dg-card img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .home-dg-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0) 60%); }
        .home-dg-title { position: absolute; bottom: 12px; left: 12px; right: 12px; z-index: 2; color: #fff; font-size: 14px; font-weight: 600; line-height: 1.3; }
        .home-diseases-btn { display: inline-block; background: #1a5c36; color: #fff; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px; }
        .home-diseases-btn:hover { background: #154d2e; color: #fff; }
        @media (max-width: 768px) { .home-diseases-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 480px) { .home-diseases-grid { grid-template-columns: 1fr !important; } .home-dg-card { height: 130px; } }
    </style>

    <section class="home-diseases-section">
        <div class="home-diseases-inner">
            <h2 class="home-diseases-title">{{ \Modules\TourBooking\App\Models\Disease::t('Find a Sanatorium for Your Condition') }}</h2>

            <div class="home-diseases-grid">
                @foreach ($homeDiseases as $disease)
                    <a href="{{ route('diseases.show', ['slug' => $disease->slug]) }}" class="home-dg-card">
                        @if ($disease->image)
                            <img src="{{ asset($disease->image) }}" alt="{{ $disease->name }}" loading="lazy">
                        @endif
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
