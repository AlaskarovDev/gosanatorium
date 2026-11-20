<div class="tg-breadcrumb-area tg-breadcrumb-spacing-5 fix p-relative z-index-1 include-bg"
    data-background="{{ asset($general_setting->breadcrumb_image) }}">
    <div class="tg-hero-top-shadow"></div>
    <div class="tg-breadcrumb-shadow"></div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tg-breadcrumb-content text-center">
                    @php $gsBcTag = ($breadcrumb_tag ?? 'h2') === 'div' ? 'div' : 'h2'; @endphp
                    <{{ $gsBcTag }} class="tg-breadcrumb-title mb-10 fs-40">{{ $breadcrumb_title }}</{{ $gsBcTag }}>
                    <div class="tg-breadcrumb-list-4">
                        @if (! empty($breadcrumb_items))
                            <ul aria-label="breadcrumb">
                                @foreach ($breadcrumb_items as $crumb)
                                    @if (! $loop->first)<li><i class="fa-sharp fa-solid fa-angle-right" aria-hidden="true"></i></li>@endif
                                    @if ($crumb['url'])
                                        <li><a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a></li>
                                    @else
                                        <li aria-current="page">{{ $crumb['name'] }}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @include('partials.breadcrumb_trail', ['crumbs' => $breadcrumb_items])
                        @else
                        <ul>
                            <li><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li>{{ $breadcrumb_title }}</li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tg-hero-bottom-shape d-none d-md-block">
        <span>
          @include('svg.breadcrumb_shape1')
        </span>
    </div>
    <div class="tg-hero-bottom-shape-2 d-none d-md-block">
        <span>
          @include('svg.breadcrumb_shape2')
        </span>
    </div>
</div>
