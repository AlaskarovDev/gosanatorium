@if (request()->routeIs('home') || request()->path() === '/')
@php
    $lbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => 'GoSanatorium',
        'legalName' => 'GoSanatorium MMC',
        'url' => 'https://gosanatorium.com',
        'logo' => asset('images/logo.png'),
        'telephone' => ['+994 51 915 88 94', '+995 568 10 27 44'],
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Baku', 'addressCountry' => 'AZ'],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($lbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endif
