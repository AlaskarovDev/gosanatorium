@php
    // Task 15: same format as canonical (https, non-www, no trailing slash, ?page=N kept).
    // Task 17: only real (translated) versions + x-default → /ru.
    $canonicalUrl = \App\Support\CanonicalUrl::current();
    $qs = parse_url($canonicalUrl, PHP_URL_QUERY);
    $basePath = \App\Support\CanonicalUrl::basePath();
    $suffix = $qs ? '?' . $qs : '';
@endphp
@foreach(\App\Support\SiteLanguages::activeCodes() as $hreflangCode)
<link rel="alternate" hreflang="{{ $hreflangCode }}" href="{{ \App\Support\CanonicalUrl::to($basePath, $hreflangCode) . $suffix }}" />
@endforeach
<link rel="alternate" hreflang="x-default" href="{{ \App\Support\CanonicalUrl::to($basePath, \App\Support\SiteLanguages::default()) . $suffix }}" />
