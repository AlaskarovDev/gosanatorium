@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Disease') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Disease') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Edit Disease') }}</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.tourbooking.diseases.update', $disease->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Disease') }}</h4>

                                                <a href="{{ route('admin.tourbooking.diseases.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Diseases') }}</a>
                                            </div>

                                            @include('tourbooking::admin.diseases._form')

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
