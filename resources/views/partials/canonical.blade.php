@php
    $host = request()->getSchemeAndHttpHost();
    $path = request()->getPathInfo();

    $basePath = preg_replace('#^/(ru|az)(?=/|$)#', '', $path);
    if ($basePath === false) {
        $basePath = $path;
    }
    $basePath = trim($basePath, '/');

    $canonical = $host . '/ru' . ($basePath !== '' ? '/' . $basePath : '') . '/';
@endphp
<link rel="canonical" href="{{ $canonical }}" />
