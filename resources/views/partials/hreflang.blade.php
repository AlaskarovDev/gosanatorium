@php
    // Task 15: same format as canonical (https, non-www, no trailing slash, ?page=N kept).
    $canonicalUrl = \App\Support\CanonicalUrl::current();
    $qs = parse_url($canonicalUrl, PHP_URL_QUERY);
    $basePath = \App\Support\CanonicalUrl::basePath();
    $suffix = $qs ? '?' . $qs : '';

    $ruUrl = \App\Support\CanonicalUrl::to($basePath, 'ru') . $suffix;
    $azUrl = \App\Support\CanonicalUrl::to($basePath, 'az') . $suffix;
    $enUrl = \App\Support\CanonicalUrl::to($basePath, 'en') . $suffix;
@endphp
<link rel="alternate" hreflang="ru" href="{{ $ruUrl }}" />
<link rel="alternate" hreflang="en" href="{{ $enUrl }}" />
<link rel="alternate" hreflang="az" href="{{ $azUrl }}" />
<link rel="alternate" hreflang="x-default" href="{{ $ruUrl }}" />
