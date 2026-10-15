@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Consultation Request Detail') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Consultation Request Detail') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Pages') }} >> {{ __('translate.Consultation Requests') }} >> {{ __('translate.Detail') }}</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Consultation Request Detail') }}</h4>
                                            <a href="{{ route('admin.consultation-requests.index') }}" class="crancy-btn">
                                                <i class="fa fa-list"></i> {{ __('translate.Back to List') }}
                                            </a>
                                        </div>

                                        <div class="row mg-top-30">
                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.First Name') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">{{ $consultation_request->first_name }}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Last Name') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">{{ $consultation_request->last_name }}</p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Email') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">
                                                        <a href="mailto:{{ $consultation_request->email }}">{{ $consultation_request->email }}</a>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Phone') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">
                                                        {{ $consultation_request->country ? '(' . $consultation_request->country . ') ' : '' }}{{ $consultation_request->phone }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Page') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">
                                                        {{ $consultation_request->customPage?->translate?->page_name ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Date') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">
                                                        {{ $consultation_request->created_at->format('d.m.Y H:i:s') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Marketing Consent') }}</strong></label>
                                                    <p class="crancy__item-input" style="background: #f8f9fa; padding: 12px;">
                                                        @if($consultation_request->marketing_consent)
                                                            <span class="badge bg-success">{{ __('translate.Yes') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('translate.No') }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label"><strong>{{ __('translate.Message') }}</strong></label>
                                                    <div class="crancy__item-input" style="background: #f8f9fa; padding: 15px; min-height: 100px; white-space: pre-wrap;">{{ $consultation_request->message }}</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection