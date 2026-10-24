@php
    use Modules\Blog\App\Models\Blog;

    $home1_blog = getContent('theme1_blog.content', true);
    $blogs = Blog::with('translate:id,blog_id,lang_code,title,reading_time')
        ->where('status', true)
        ->latest()
        ->take(3)
        ->get();
    $gsIsRu = app()->getLocale() === 'ru';
@endphp

<!-- blog-area-start -->
<section class="gsblog-section">
    <div class="container">
        <div class="text-center">
            <div class="gsblog-underline"></div>
            <span class="gsblog-label">{{ getTranslatedValue($home1_blog, 'sub_title') }}</span>
            <h2 class="gsblog-title">{{ getTranslatedValue($home1_blog, 'title') }}</h2>
            <p class="gsblog-subtitle">{!! strip_tags(clean(getTranslatedValue($home1_blog, 'description')), '<br>') !!}</p>
        </div>
        <div class="gsblog-grid">
            @foreach ($blogs as $blog)
                <a href="{{ route('blog', ['slug' => $blog->slug]) }}" class="gsblog-card">
                    <div class="gsblog-img-wrap">
                        <img src="{{ asset($blog->image) }}" alt="{{ $blog?->translate?->title }}" class="gsblog-img" loading="lazy">
                        @if ($blog?->category?->name)
                            <span class="gsblog-tag">{{ $blog->category->name }}</span>
                        @endif
                    </div>
                    <div class="gsblog-body">
                        <div class="gsblog-meta">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $gsIsRu ? $blog->created_at->locale('ru')->isoFormat('D MMMM YYYY') . ' г.' : $blog->created_at->format('jS M, Y') }}</span>
                        </div>
                        <div class="gsblog-post-title">{{ $blog?->translate?->title }}</div>
                        <div class="gsblog-read-more">{{ $gsIsRu ? 'Читать далее' : 'Read more' }} <i class="fas fa-arrow-right"></i></div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="gsblog-footer">
            <a href="{{ route('blogs') }}" class="gsblog-btn">
                {{ $gsIsRu ? 'Все статьи' : 'All articles' }} <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
<!-- blog-area-end -->
@push('style_section')
    <style>
/* === BLOG SECTION (gsblog- prefix) === */
.gsblog-section { padding: 80px 0 !important; background: #f8fafb !important; }
.gsblog-label { display: inline-block !important; font-size: 13px !important; font-weight: 700 !important; color: #1a9e87 !important; text-transform: uppercase !important; letter-spacing: 2px !important; margin-bottom: 12px !important; }
.gsblog-title { font-size: 36px !important; font-weight: 800 !important; color: #1a1a2e !important; margin-bottom: 12px !important; text-transform: none !important; letter-spacing: -0.5px !important; }
.gsblog-subtitle { font-size: 16px !important; color: #6b7280 !important; margin-bottom: 50px !important; text-transform: none !important; }
.gsblog-underline { width: 60px !important; height: 4px !important; background: linear-gradient(135deg,#1a9e87,#2ec4a9) !important; border-radius: 2px !important; margin: 0 auto 16px !important; }
.gsblog-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 28px !important; }
@media(max-width:992px){ .gsblog-grid{ grid-template-columns: repeat(2,1fr) !important; } }
@media(max-width:576px){ .gsblog-grid{ grid-template-columns: 1fr !important; } .gsblog-title{ font-size: 28px !important; } }
.gsblog-card { background: #ffffff !important; border-radius: 18px !important; overflow: hidden !important; box-shadow: 0 2px 16px rgba(0,0,0,0.07) !important; transition: all 0.3s ease !important; display: flex !important; flex-direction: column !important; text-decoration: none !important; }
.gsblog-card:hover { transform: translateY(-6px) !important; box-shadow: 0 16px 40px rgba(0,0,0,0.13) !important; }
.gsblog-img-wrap { position: relative !important; overflow: hidden !important; height: 210px !important; }
.gsblog-img { width: 100% !important; height: 100% !important; object-fit: cover !important; transition: transform 0.4s ease !important; }
.gsblog-card:hover .gsblog-img { transform: scale(1.06) !important; }
.gsblog-tag { position: absolute !important; top: 14px !important; left: 14px !important; background: linear-gradient(135deg,#1a9e87,#2ec4a9) !important; color: #fff !important; font-size: 12px !important; font-weight: 700 !important; padding: 4px 12px !important; border-radius: 20px !important; text-transform: none !important; }
.gsblog-body { padding: 24px !important; flex: 1 !important; display: flex !important; flex-direction: column !important; }
.gsblog-post-title { font-size: 17px !important; font-weight: 700 !important; color: #1a1a2e !important; line-height: 1.45 !important; margin-bottom: 12px !important; text-transform: none !important; flex: 1 !important; }
.gsblog-card:hover .gsblog-post-title { color: #1a9e87 !important; }
.gsblog-meta { display: flex !important; align-items: center !important; gap: 8px !important; font-size: 13px !important; color: #9ca3af !important; margin-bottom: 16px !important; text-transform: none !important; }
.gsblog-meta i { color: #1a9e87 !important; font-size: 12px !important; }
.gsblog-read-more { display: inline-flex !important; align-items: center !important; gap: 6px !important; font-size: 14px !important; font-weight: 600 !important; color: #1a9e87 !important; text-decoration: none !important; margin-top: auto !important; text-transform: none !important; }
.gsblog-read-more:hover { gap: 10px !important; }
.gsblog-footer { text-align: center !important; margin-top: 48px !important; }
.gsblog-btn { display: inline-flex !important; align-items: center !important; gap: 8px !important; padding: 14px 36px !important; background: linear-gradient(135deg,#1a9e87,#2ec4a9) !important; color: #fff !important; font-size: 15px !important; font-weight: 700 !important; border-radius: 50px !important; text-decoration: none !important; box-shadow: 0 6px 20px rgba(26,158,135,0.35) !important; transition: all 0.3s ease !important; text-transform: none !important; }
.gsblog-btn:hover { transform: translateY(-2px) !important; box-shadow: 0 10px 28px rgba(26,158,135,0.45) !important; color: #fff !important; }
    </style>
@endpush
