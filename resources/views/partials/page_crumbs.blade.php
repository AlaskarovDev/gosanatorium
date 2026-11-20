{{-- Task 13: visible breadcrumb strip + BreadcrumbList JSON-LD for static pages and promotions. Usage: @include('partials.page_crumbs', ['crumbs' => \App\Support\Breadcrumbs::page('О нас')]) --}}
@if (! empty($crumbs))
<nav class="gs-pcrumbs{{ !empty($gsCrumbsInline) ? ' gs-pcrumbs--inline' : '' }}" aria-label="Навигационная цепочка">
    <div class="{{ !empty($gsCrumbsInline) ? '' : 'container' }}">
        <ol>
            @foreach ($crumbs as $crumb)
                @if ($crumb['url'])
                    <li><a href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a></li>
                @else
                    <li aria-current="page">{{ $crumb['name'] }}</li>
                @endif
            @endforeach
        </ol>
    </div>
</nav>
@include('partials.breadcrumb_trail', ['crumbs' => $crumbs])
@endif
