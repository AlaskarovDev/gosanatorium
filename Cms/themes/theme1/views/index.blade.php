@extends('theme::layouts.app')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection
@section('front-content')
    {{-- hero (statik fon + içində axtarış forması, theme3 üslubu) --}}
    {{-- köhnə slider-ə qayıtmaq üçün: @include('theme::components.slider') + @include('theme::components.booking-form') --}}
    @include('theme::components.hero-search')

    {{-- trust bar --}}
    @include('theme::components.trust-bar')

    @include('components.features')
    @include('theme::components.statistics')

    {{-- package section --}}
    @include('theme::components.package')

@include('components.room-booking-popup')

    {{-- diseases section --}}
    @include('components.diseases-section')

    {{-- about us section --}}
    @include('theme::components.about')

    {{-- why choose section --}}
    @include('theme::components.why-choose')

    {{-- destination section --}}
    @include('theme::components.destination')

    {{-- banner section --}}
    @include('theme::components.banner')

    {{-- banner two section --}}
    @include('theme::components.banner-two')

    {{-- testimonial section --}}
    @include('theme::components.testimonial')

    {{-- blog section --}}
    @include('theme::components.blog')

@endsection
