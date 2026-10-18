@if (!empty($seoApplied))
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
@else
    @yield('title')
@endif
