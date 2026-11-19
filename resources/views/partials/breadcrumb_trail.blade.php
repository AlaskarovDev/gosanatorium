{{--
    Task 13: one breadcrumb trail builder for sanatorium, rooms and blog pages.
    Usage: @php $crumbs = \App\Support\Breadcrumbs::forService($service) @endphp
    Renders the BreadcrumbList JSON-LD for the given $crumbs (list of ['name' => ..., 'url' => ...|null]).
--}}
@if (! empty($crumbs))
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $c['name'],
            'item' => \App\Support\StructuredData::abs($c['url'] ?: null),
        ]))->all(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endif
