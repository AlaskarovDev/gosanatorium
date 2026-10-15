@php
    $host = request()->getSchemeAndHttpHost();
    $path = request()->getPathInfo();

    // Locale prefiksini təmizləyirik (middleware adətən prefiksi artıq kəsir,
    // amma /ru, /az birbaşa gələrsə də təmizlənsin).
    $basePath = preg_replace('#^/(ru|az)(?=/|$)#', '', $path);
    if (!is_string($basePath) || $basePath === '') {
        $basePath = '/';
    }
    $basePath = rtrim($basePath, '/');

    // Bütün hreflang URL-ləri canonical ilə eyni formada olmalıdır:
    //  - hər zaman locale prefiksi (/ru, /az, /en) ilə → 301 redirect olmasın (final 200)
    //  - hər zaman sonda "/" ilə                        → canonical ilə üst-üstə düşsün
    $localizedPath = ($basePath === '' ? '/' : $basePath . '/');

    $ruUrl = $host . '/ru' . $localizedPath;
    $azUrl = $host . '/az' . $localizedPath;
    $enUrl = $host . '/en' . $localizedPath;
@endphp
<link rel="alternate" hreflang="ru" href="{{ $ruUrl }}" />
<link rel="alternate" hreflang="en" href="{{ $enUrl }}" />
<link rel="alternate" hreflang="az" href="{{ $azUrl }}" />
<link rel="alternate" hreflang="x-default" href="{{ $ruUrl }}" />
