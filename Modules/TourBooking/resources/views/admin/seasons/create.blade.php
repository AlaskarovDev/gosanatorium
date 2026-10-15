@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Add Season') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Add Season') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Seasons') }} >> {{ __('translate.Add') }}</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner p-3 bg-white rounded">
                            <form method="POST" action="{{ route('admin.tourbooking.seasons.store') }}">
                                @include('tourbooking::admin.seasons._form')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
