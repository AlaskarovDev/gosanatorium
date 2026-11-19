{{-- Task 15: canonical = og:url, one format for the whole site (App\Support\CanonicalUrl).
     Pages that call applySeo() already print both via SEOMeta/OpenGraph, so no duplicate here. --}}
@if (empty($seoApplied))
    @php $canonical = \App\Support\CanonicalUrl::current(); @endphp
    <link rel="canonical" href="{{ $canonical }}" />
    <meta property="og:url" content="{{ $canonical }}" />
@endif
