@extends('layout_inner_page')

@section('title')
    <title>Акции и скидки в санаториях | GoSanatorium.com</title>
    <meta name="title" content="Акции и скидки в санаториях | GoSanatorium.com">
    <meta name="description" content="Выгодные предложения для вашего отдыха и лечения в санаториях. Специальные акции и скидки на GoSanatorium.com">
@endsection

@push('style_section')
<style>
    .fakcii-header { background: #f4faf9 !important; padding: 48px 0 !important; text-align: center !important; }
    .fakcii-label { display: block !important; color: #1a9e87 !important; font-size: 14px !important; font-weight: 600 !important; text-transform: uppercase !important; letter-spacing: 1px !important; margin-bottom: 8px !important; }
    .fakcii-title { font-size: 34px !important; font-weight: 700 !important; color: #1f2d3d !important; margin: 0 0 12px !important; }
    .fakcii-subtitle { font-size: 16px !important; color: #6b7280 !important; margin: 0 !important; }
    .fakcii-section { background: #ffffff !important; padding: 60px 0 !important; }
    .fakcii-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 24px !important; }
    .fakcii-card { background: #ffffff !important; border-radius: 12px !important; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08) !important; overflow: hidden !important; display: flex !important; flex-direction: column !important; }
    .fakcii-img { display: block !important; width: 100% !important; height: 200px !important; object-fit: cover !important; }
    .fakcii-placeholder { display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important; height: 200px !important; background: linear-gradient(135deg, #1a9e87, #2ec4a9) !important; color: #ffffff !important; font-size: 48px !important; font-weight: 700 !important; }
    .fakcii-body { padding: 20px !important; display: flex !important; flex-direction: column !important; flex: 1 !important; }
    .fakcii-sanatorium { color: #1a9e87 !important; font-size: 13px !important; margin-bottom: 4px !important; }
    .fakcii-card-title { font-size: 16px !important; font-weight: 700 !important; color: #1f2d3d !important; margin: 0 !important; }
    .fakcii-desc { color: #6b7280 !important; font-size: 14px !important; margin: 8px 0 0 !important; }
    .fakcii-row { display: flex !important; align-items: center !important; justify-content: space-between !important; margin-top: auto !important; padding-top: 12px !important; }
    .fakcii-badge { background: #1a9e87 !important; color: #ffffff !important; font-size: 13px !important; font-weight: 700 !important; padding: 4px 10px !important; border-radius: 6px !important; }
    .fakcii-valid { color: #6b7280 !important; font-size: 13px !important; }
    .fakcii-btn { display: block !important; width: 100% !important; background: #1a9e87 !important; color: #ffffff !important; text-align: center !important; border-radius: 8px !important; margin-top: 12px !important; padding: 10px 16px !important; font-weight: 600 !important; text-decoration: none !important; }
    .fakcii-btn:hover { background: #158572 !important; color: #ffffff !important; }
    .fakcii-empty { text-align: center !important; color: #6b7280 !important; }
    .fakcii-pagination { margin-top: 40px !important; display: flex !important; justify-content: center !important; }
    @media (max-width: 991px) { .fakcii-grid { grid-template-columns: repeat(2, 1fr) !important; } }
    @media (max-width: 575px) { .fakcii-grid { grid-template-columns: 1fr !important; } .fakcii-title { font-size: 26px !important; } }
</style>
@endpush

@section('front-content')
    <section class="fakcii-header">
        <div class="container">
            <span class="fakcii-label">Специальные предложения</span>
            <h1 class="fakcii-title">Акции и скидки в санаториях</h1>
            <p class="fakcii-subtitle">Выгодные предложения для вашего отдыха и лечения</p>
        </div>
    </section>

    <section class="fakcii-section">
        <div class="container">
            @if ($akcii->count())
                <div class="fakcii-grid">
                    @foreach ($akcii as $item)
                        <div class="fakcii-card">
                            @if ($item->image)
                                <img class="fakcii-img" src="{{ asset($item->image) }}" alt="{{ $item->title }}" loading="lazy">
                            @else
                                <div class="fakcii-placeholder">{{ $item->discount_percent ? $item->discount_percent . '%' : '%' }}</div>
                            @endif
                            <div class="fakcii-body">
                                @if ($item->sanatorium_name)
                                    <div class="fakcii-sanatorium">{{ $item->sanatorium_name }}</div>
                                @endif
                                <h3 class="fakcii-card-title">{{ $item->title }}</h3>
                                @if ($item->description)
                                    <p class="fakcii-desc">{{ $item->description }}</p>
                                @endif
                                <div class="fakcii-row">
                                    <span>
                                        @if ($item->discount_percent)
                                            <span class="fakcii-badge">−{{ $item->discount_percent }}%</span>
                                        @endif
                                    </span>
                                    @if ($item->valid_until)
                                        <span class="fakcii-valid">до {{ $item->valid_until->format('d.m.Y') }}</span>
                                    @endif
                                </div>
                                <a class="fakcii-btn" href="https://wa.me/994519158894" target="_blank" rel="noopener">Забронировать</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="fakcii-pagination">
                    {{ $akcii->links() }}
                </div>
            @else
                <p class="fakcii-empty">Пока нет активных акций.</p>
            @endif
        </div>
    </section>
@endsection
