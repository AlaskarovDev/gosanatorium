@extends('layout_inner_page')

@section('title')
    <title>{{ $blog->seo_title }}</title>
    <meta name="title" content="{{ $blog->seo_title }}">
    <meta name="description" content="{{ $blog->seo_description }}">

    @php
        $tags = '';
        if ($blog->tags) {
            foreach (json_decode($blog->tags) as $key => $blog_tag) {
                $tags .= $blog_tag->value . ', ';
            }
        }
    @endphp

    <meta name="keyword" content="{{ $tags }}">
@endsection

@push('style_section')
    <style>
        /* Blog məzmunundakı linklər: qalın + alt-xətt, görünən olsun */
        .tg-blog-standard-content a {
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 2px;
            text-decoration-thickness: 1px;
            color: inherit;
        }
        .tg-blog-standard-content a:hover {
            text-decoration-thickness: 2px;
        }
    </style>
@endpush

@section('front-content')
    @php
        $gsPost = \App\Support\BlogContent::prepare($blog->description);
    @endphp


    @include('breadcrumb')

    <!-- tg-blog-grid-area-start -->
    <div class="tg-blog-grid-area pt-130 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8">
                    <div class="tg-blog-details-wrap tg-blog-lg-spacing mr-50 mb-50">
                        <div class="tg-blog-standard-item mb-35">
                            <div class="tg-blog-standard-thumb mb-15">
                                <img class="w-100" {!! img_attrs(safe_image($blog->image, $general_setting->not_found ?? null), $blog?->translate?->title, ['sizes' => '(max-width: 991px) 100vw, 800px', 'priority' => true]) !!}>
                            </div>
                            <div class="tg-blog-standard-content">
                                <h2 class="tg-blog-standard-title"> {{ $blog?->title }}
                                </h2>
                                @include('partials.blog_post_header', ['blog' => $blog])

                                @if (count($gsPost['toc']) >= 2)
                                    <details class="gs-toc" open>
                                        <summary>Содержание</summary>
                                        <ol>
                                            @foreach ($gsPost['toc'] as $item)
                                                <li><a href="#{{ $item['id'] }}">{{ $item['text'] }}</a></li>
                                            @endforeach
                                        </ol>
                                    </details>
                                    <script>
                                        (function () {
                                            var d = document.currentScript.previousElementSibling;
                                            if (d && window.matchMedia('(max-width: 767px)').matches) d.removeAttribute('open');
                                        })();
                                    </script>
                                @endif

                                {!! $gsPost['first'] !!}
                                @if ($gsPost['rest'] !== '')
                                    @include('partials.blog_post_cta', ['blog' => $blog, 'gsCtaPlace' => 'middle'])
                                    {!! $gsPost['rest'] !!}
                                @endif
                                @include('partials.blog_post_cta', ['blog' => $blog, 'gsCtaPlace' => 'end'])
                                @include('partials.medical_disclaimer')
                            </div>
                        </div>

                        <div class="tg-blog-details-tag mb-40 d-flex flex-wrap justify-content-between align-items-center">
                            <div class="tg-blog-sidebar-tag-list d-flex flex-wrap align-items-center">
                                <div class="gs-h5 tg-blog-sidebar-title mr-10">{{ __('translate.Tags') }}:</div>
                                <ul>
                                    @if ($blog->tags)
                                        @foreach (json_decode($blog->tags) as $blog_tag)
                                            <li><span class="gs-blog-tag">#{{ $blog_tag->value }}</span></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="tg-blog-details-social mb-10">
                                @php
                                    $gsShareUrl = urlencode(route('blog', $blog->slug));
                                    $gsShareTitle = urlencode($blog->title);
                                @endphp
                                <span>Поделиться:</span>
                                <a target="_blank" rel="noopener" aria-label="WhatsApp" href="https://wa.me/?text={{ $gsShareTitle }}%20{{ $gsShareUrl }}"><i class="fa-brands fa-whatsapp"></i></a>
                                <a target="_blank" rel="noopener" aria-label="Telegram" href="https://t.me/share/url?url={{ $gsShareUrl }}&text={{ $gsShareTitle }}"><i class="fa-brands fa-telegram"></i></a>
                                <a target="_blank" rel="noopener" aria-label="VK" href="https://vk.com/share.php?url={{ $gsShareUrl }}&title={{ $gsShareTitle }}"><i class="fa-brands fa-vk"></i></a>
                                <a href="#" class="gs-copy-link" aria-label="Скопировать ссылку" title="Скопировать ссылку" data-url="{{ route('blog', $blog->slug) }}"><i class="fa-solid fa-link"></i></a>
                            </div>
                        </div>
                        @include('partials.blog_author_box', ['blog' => $blog])

                        @if (isset($related_blogs) && $related_blogs->count())
                            <section class="gs-related mb-40">
                                <h2 class="gs-related__title">Читайте также</h2>
                                <div class="gs-related__grid">
                                    @foreach ($related_blogs as $rb)
                                        <a class="gs-related__card" href="{{ route('blog', $rb->slug) }}">
                                            <img {!! img_attrs($rb->image, $rb->title, ['sizes' => '(max-width: 767px) 100vw, 260px']) !!}>
                                            <span class="gs-related__body">
                                                @if ($rb->category?->name)<span class="gs-related__cat">{{ $rb->category->name }}</span>@endif
                                                <span class="gs-related__name">{{ $rb->title }}</span>
                                                <span class="gs-related__meta">{{ $rb->created_at->format('d.m.Y') }} · {{ $rb->reading_minutes }} мин чтения</span>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        <div class="tg-tour-about-review-form tg-blog-details-review-form">
                            <h4 class="tg-tour-about-title mb-10">{{ __('translate.Post a comment') }}</h4>
                            <p>{{ __('translate.Your email address will not be published. Required fields are marked') }} *</p>
                            <form action="{{ route('store-blog-comment', $blog->id) }}" method="POST">
                                @csrf
                                <div class="row gx-15">
                                    <div class="col-lg-12">
                                        <textarea class="textarea  mb-5" placeholder="{{ __('translate.Write Your Comment') }}*" name="comment">{{ old('comment') }}</textarea>
                                    </div>
                                    <div class="col-lg-4 mb-15">
                                        <input class="input" type="text" placeholder="{{ __('translate.Name') }}*" name="name" value="{{ old('name') }}">
                                    </div>
                                    <div class="col-lg-4 mb-15">
                                        <input class="input" type="email" placeholder="{{ __('translate.Email') }}*" name="email" value="{{ old('email') }}">
                                    </div>
                                    <div class="col-lg-4 mb-15">
                                        <input class="input" type="text" placeholder="{{ __('translate.Phone') }}" name="phone" value="{{ old('phone') }}">
                                    </div>
                                    @if ($general_setting->recaptcha_status == 1)
                                        <div class="col-lg-4">
                                            <div class="g-recaptcha" data-sitekey="{{ $general_setting->recaptcha_site_key }}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <button type="submit" class="tg-btn tg-btn-switch-animation">
                                            <span class="d-flex align-items-center justify-content-center">
                                                <span class="btn-text">{{ __('translate.Post Comment') }}</span>
                                                <span class="btn-icon ml-5">
                                                    <svg width="21" height="16" viewBox="0 0 21 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M1.0017 8.00001H19.9514M19.9514 8.00001L12.9766 1.02515M19.9514 8.00001L12.9766 14.9749"
                                                            stroke="white" stroke-width="1.77778" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                                <span class="btn-icon ml-5">
                                                    <svg width="21" height="16" viewBox="0 0 21 16"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M1.0017 8.00001H19.9514M19.9514 8.00001L12.9766 1.02515M19.9514 8.00001L12.9766 14.9749"
                                                            stroke="white" stroke-width="1.77778" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    @include('components.common_blog_sidebar')
                </div>
            </div>
        </div>
    </div>
    <!-- tg-blog-grid-area-end -->

    {{-- Task 14: BlogPosting JSON-LD --}}
    {!! \App\Support\StructuredData::script(\App\Support\StructuredData::blogPosting($blog, ($general_setting->logo ?? null) ? asset($general_setting->logo) : null)) !!}
@endsection

@push('js_section')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

@push('js_section')
    <script>
        document.addEventListener('click', function (e) {
            var a = e.target.closest('.gs-copy-link');
            if (!a) return;
            e.preventDefault();
            var url = a.getAttribute('data-url');
            var done = function () { a.classList.add('is-copied'); a.title = 'Ссылка скопирована'; setTimeout(function () { a.classList.remove('is-copied'); a.title = 'Скопировать ссылку'; }, 2000); };
            if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(url).then(done, function () { window.prompt('Скопируйте ссылку:', url); }); }
            else { window.prompt('Скопируйте ссылку:', url); }
        });
    </script>
@endpush
