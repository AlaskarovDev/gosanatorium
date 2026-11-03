@php
    $isRu = app()->getLocale() === 'ru';
    $base = ($isRu ? '/ru' : '/en') . '/sanatoriyi';
    $stroke = 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
    $diseaseCards = [
        [
            'name' => $isRu ? 'Суставы и опорно-двигательный аппарат' : 'Joints & Musculoskeletal System',
            'sub' => $isRu ? 'Артрит, артроз, остеохондроз, подагра' : 'Arthritis, arthrosis, osteochondrosis, gout',
            'key' => 'joints',
            'svg' => '<circle cx="7" cy="6" r="2.5"/><circle cx="17" cy="18" r="2.5"/><path d="M8.8 7.8l6.4 8.4"/><path d="M4 3.5l-1 1M10 3l1 1M20 20.5l1-1M14 21l-1-1"/>',
        ],
        [
            'name' => $isRu ? 'Кожные заболевания (псориаз, экзема)' : 'Skin Diseases (psoriasis, eczema)',
            'sub' => $isRu ? 'Псориаз, экзема, нейродермит' : 'Psoriasis, eczema, neurodermatitis',
            'key' => 'skin',
            'svg' => '<path d="M12 3c3.5 4.5 6 7.5 6 11a6 6 0 0 1-12 0c0-3.5 2.5-6.5 6-11z"/><path d="M9.5 14.5a2.5 2.5 0 0 0 2.5 2.5"/>',
        ],
        [
            'name' => $isRu ? 'Неврология' : 'Neurology',
            'sub' => $isRu ? 'Межпозвоночные грыжи, радикулит, невриты' : 'Spinal hernias, radiculitis, neuritis',
            'key' => 'neuro',
            'svg' => '<path d="M9.5 4A3.5 3.5 0 0 0 6 7.5v.3A3.2 3.2 0 0 0 4 10.8c0 1 .4 1.8 1 2.4A3.3 3.3 0 0 0 6 18a3.5 3.5 0 0 0 6 1V5.5A2.5 2.5 0 0 0 9.5 4z"/><path d="M14.5 4A3.5 3.5 0 0 1 18 7.5v.3a3.2 3.2 0 0 1 2 3c0 1-.4 1.8-1 2.4A3.3 3.3 0 0 1 18 18a3.5 3.5 0 0 1-6 1"/><path d="M12 9h-2M12 14h2"/>',
        ],
        [
            'name' => $isRu ? 'Гинекология' : 'Gynecology',
            'sub' => $isRu ? 'Нарушения цикла, воспалительные заболевания' : 'Cycle disorders, inflammatory diseases',
            'key' => 'gyneco',
            'svg' => '<circle cx="12" cy="8" r="4.5"/><path d="M12 12.5V21M9 17.5h6"/>',
        ],
        [
            'name' => $isRu ? 'Заболевания ЖКТ' : 'Digestive Diseases',
            'sub' => $isRu ? 'Гастрит, колит, заболевания печени' : 'Gastritis, colitis, liver diseases',
            'key' => 'gastro',
            'svg' => '<path d="M8 3v5a4 4 0 0 0 4 4h1a3.5 3.5 0 0 1 0 7h-3"/><path d="M8 3h5a3 3 0 0 1 0 6"/><circle cx="7" cy="19" r="2"/>',
        ],
        [
            'name' => $isRu ? 'Другие заболевания' : 'Other Diseases',
            'sub' => $isRu ? 'Сердце, дыхание, эндокринология, реабилитация' : 'Heart, respiratory, endocrine, rehabilitation',
            'key' => 'all',
            'svg' => '<rect x="3.5" y="3.5" width="17" height="17" rx="4"/><path d="M12 8v8M8 12h8"/>',
        ],
    ];
@endphp

<style>
    .home-diseases-section { background: #f8f9fa; padding: 50px 0; }
    .home-diseases-inner { max-width: 1100px; margin: 0 auto; padding: 0 16px; }
    .home-diseases-title { text-align: center; font-size: 28px; font-weight: 700; color: #1a1a1a; margin-bottom: 32px; }
    .home-diseases-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .home-dc-card { display: flex; flex-direction: column; background: #fff; border: 1px solid #e5e8eb; border-radius: 14px; padding: 24px; text-decoration: none; box-sizing: border-box; transition: box-shadow .25s ease, border-color .25s ease, transform .25s ease; }
    .home-dc-card:hover { border-color: #1a5c36; box-shadow: 0 8px 24px rgba(26,92,54,.14); transform: translateY(-2px); }
    .home-dc-icon { width: 52px; height: 52px; border-radius: 12px; background: #e8f3ec; color: #1a5c36; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
    .home-dc-icon svg { width: 28px; height: 28px; }
    .home-dc-name { font-size: 18px; font-weight: 700; color: #1a1a1a; line-height: 1.3; margin: 0 0 8px; }
    .home-dc-sub { font-size: 14px; color: #777; line-height: 1.4; margin: 0 0 18px; }
    .home-dc-link { margin-top: auto; font-size: 15px; font-weight: 600; color: #1a5c36; }
    @media (max-width: 1024px) { .home-diseases-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 767px) { .home-diseases-grid { grid-template-columns: 1fr; } }
</style>

<section id="section-diseases" class="home-diseases-section">
    <div class="home-diseases-inner">
        <h2 class="home-diseases-title">{{ $isRu ? 'Найти по заболеванию' : 'Find by Condition' }}</h2>
        <div class="home-diseases-grid">
            @foreach ($diseaseCards as $card)
                <a href="{{ $base }}?disease={{ $card['key'] }}" class="home-dc-card">
                    <span class="home-dc-icon"><svg viewBox="0 0 24 24" {!! $stroke !!} aria-hidden="true">{!! $card['svg'] !!}</svg></span>
                    <h3 class="home-dc-name">{{ $card['name'] }}</h3>
                    <p class="home-dc-sub">{{ $card['sub'] }}</p>
                    <span class="home-dc-link">{{ $isRu ? 'Подобрать санаторий' : 'Find a sanatorium' }} →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
