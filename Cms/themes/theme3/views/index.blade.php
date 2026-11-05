@extends('theme::layouts.app')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="Бронируйте санатории Нафталана и Грузии онлайн. Лечение опорно-двигательного аппарата, кожных заболеваний, нервной системы. Без предоплаты, оплата по прибытии. Бесплатная консультация врача.">
@endsection
@section('front-content')
    {{-- hero section --}}
    @include('theme::components.hero')

    {{-- destination section --}}
    @include('theme::components.destination')

    {{-- about section --}}
    @include('theme::components.about')

    {{-- package section --}}
    @include('theme::components.package')

    {{-- ads section --}}
    @include('theme::components.ads')

    {{-- why choose section --}}
    @include('theme::components.why-choose')

    {{-- testimonial section --}}
    @include('theme::components.testimonial')

    {{-- blog section --}}
    @include('theme::components.blog')

    {{-- cta section --}}
    @include('theme::components.cta')
@endsection
