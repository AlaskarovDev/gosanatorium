@extends('theme::layouts.app')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection
@section('front-content')
    {{-- hero section --}}
    @include('theme::components.hero')

    {{-- booking form section --}}
    @include('theme::components.booking-form')

    {{-- partner section --}}
    @include('theme::components.partner')

    {{-- destination section --}}
    @include('theme::components.destination')

    {{-- banner section --}}
    @include('theme::components.banner')

    {{-- package section --}}
    @include('theme::components.package')

    {{-- why choose section --}}
    @include('theme::components.why-choose')

    {{-- counter section --}}
    @include('theme::components.counter')

    {{-- testimonial section --}}
    @include('theme::components.testimonial')

    {{-- blog section --}}
    @include('theme::components.blog')

    {{-- cta section --}}
    @include('theme::components.cta')
@endsection
