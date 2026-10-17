@extends('layout_inner_page')

@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}" />
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}" />
    {{-- noindex silindi (istifadəçi istəyi ilə). Canonical saxlanılır ki, search/filter
         səhifələri əsas /blogs-a yönəlsin. --}}
    @if (request()->filled('search') || request()->filled('category'))
        <link rel="canonical" href="{{ url('blogs') }}" />
    @endif
@endsection

@section('front-content')
    @include('breadcrumb')
    <div class="blog-hero">
        <h1>Блог GoSanatorium</h1>
        <p>Советы по лечению, обзоры курортов и полезные статьи о санаторном отдыхе</p>
    </div>
    <div class="tg-blog-grid-area pb-100">
        <div class="container">
            <div class="blog-grid">
                @forelse ($blogs as $blog)
                    <div class="blog-card">
                        <a href="{{ route('blog', $blog->slug) }}">
                            <img class="blog-card-img"
                                src="{{ safe_image($blog->image, $general_setting->not_found ?? null) }}"
                                alt="{{ $blog->title }}" loading="lazy">
                        </a>
                        <div class="blog-card-body">
                            <span class="blog-card-date">{{ \Carbon\Carbon::parse($blog->created_at)->format('d.m.Y') }}</span>
                            <a href="{{ route('blog', $blog->slug) }}" class="blog-card-title">{{ $blog->title }}</a>
                            <p class="blog-card-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($blog->short_description ?: $blog->description), 100) }}</p>
                            <a href="{{ route('blog', $blog->slug) }}" class="blog-card-link">Читать далее →</a>
                        </div>
                    </div>
                @empty
                    <div class="blog-empty">
                        <h3>{{ __('translate.OOPS! Blog not Found') }}</h3>
                        <p>{{ __('translate.Oops! this information is not available for a moment') }}</p>
                        <a href="{{ route('blogs') }}" class="blog-card-link">{{ __('translate.Back to Blog') }}</a>
                    </div>
                @endforelse
            </div>
            <div class="tg-pagenation-wrap text-center pt-40 mb-30">
                <nav>
                    @if ($blogs->hasPages())
                        {{ $blogs->links('custom_pagination') }}
                    @endif
                </nav>
            </div>
            <div class="row">
                <div class="col-12">
                    @include('components.common_blog_sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
