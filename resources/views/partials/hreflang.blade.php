@php
    // Task 15: same format as canonical (https, non-www, no trailing slash, ?page=N kept).
    // Only real (native) versions + x-default → /ru; Google-translated copies are not alternates.
    $canonicalUrl = \App\Support\CanonicalUrl::current();
    $qs = parse_url($canonicalUrl, PHP_URL_QUERY);
    $basePath = \App\Support\CanonicalUrl::basePath();
    $suffix = $qs ? '?' . $qs : '';
@endphp
@foreach(\App\Support\SiteLanguages::nativeCodes() as $hreflangCode)
<link rel="alternate" hreflang="{{ $hreflangCode }}" href="{{ \App\Support\CanonicalUrl::to($basePath, $hreflangCode) . $suffix }}" />
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ \App\Support\CanonicalUrl::to($basePath, \App\Support\SiteLanguages::default()) . $suffix }}" />
