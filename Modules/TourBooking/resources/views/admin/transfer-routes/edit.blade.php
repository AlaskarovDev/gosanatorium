@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Edit Transfer Route') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Transfer Route') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Update transfer route details') }}</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-product-card crancy__h-full mg-top-30">
                                <div class="crancy-product-card__content">
                                    
                                    <div class="crancy-customer-filter">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Edit Transfer Route') }}</h4>
                                            <a href="{{ route('admin.tourbooking.transfer-routes.index') }}"
                                                class="crancy-btn crancy-btn__secondary">
                                                <i class="fas fa-arrow-left"></i> {{ __('translate.Back to List') }}
                                            </a>
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.tourbooking.transfer-routes.update', $transferRoute) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.From Location') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input class="crancy__item-input @error('from_location') is-invalid @enderror" 
                                                           type="text" 
                                                           name="from_location" 
                                                           value="{{ old('from_location', $transferRoute->from_location) }}"
                                                           placeholder="{{ __('translate.Enter departure location') }}"
                                                           required>
                                                    @error('from_location')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.To Location') }} <span class="text-danger">*</span>
                                                    </label>
                                                    <input class="crancy__item-input @error('to_location') is-invalid @enderror" 
                                                           type="text" 
                                                           name="to_location" 
                                                           value="{{ old('to_location', $transferRoute->to_location) }}"
                                                           placeholder="{{ __('translate.Enter destination location') }}"
                                                           required>
                                                    @error('to_location')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Base Price') }}
                                                    </label>
                                                    <input class="crancy__item-input @error('base_price') is-invalid @enderror" 
                                                           type="number" 
                                                           step="0.01"
                                                           min="0"
                                                           name="base_price" 
                                                           value="{{ old('base_price', $transferRoute->base_price) }}"
                                                           placeholder="{{ __('translate.Enter base price') }}">
                                                    @error('base_price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Duration (minutes)') }}
                                                    </label>
                                                    <input class="crancy__item-input @error('duration_minutes') is-invalid @enderror" 
                                                           type="number" 
                                                           min="0"
                                                           name="duration_minutes" 
                                                           value="{{ old('duration_minutes', $transferRoute->duration_minutes) }}"
                                                           placeholder="{{ __('translate.Enter duration in minutes') }}">
                                                    @error('duration_minutes')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Display Order') }}
                                                    </label>
                                                    <input class="crancy__item-input @error('display_order') is-invalid @enderror" 
                                                           type="number" 
                                                           min="0"
                                                           name="display_order" 
                                                           value="{{ old('display_order', $transferRoute->display_order) }}"
                                                           placeholder="{{ __('translate.Enter display order') }}">
                                                    @error('display_order')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Status') }}
                                                    </label>
                                                    <div class="crancy-ptabs__notify-switch">
                                                        <label class="crancy__item-switch">
                                                            <input type="checkbox" name="status" value="1" 
                                                                   {{ old('status', $transferRoute->status) ? 'checked' : '' }}>
                                                            <span class="crancy__item-switch--slider crancy__item-switch--round"></span>
                                                        </label>
                                                        <span class="ml-3">{{ __('translate.Active') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">
                                                        {{ __('translate.Description') }}
                                                    </label>
                                                    <textarea class="crancy__item-input crancy__item-textarea @error('description') is-invalid @enderror" 
                                                              name="description" 
                                                              rows="4"
                                                              placeholder="{{ __('translate.Enter description (optional)') }}">{{ old('description', $transferRoute->description) }}</textarea>
                                                    @error('description')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        @include('tourbooking::admin._partials.season_prices', ['entity' => $transferRoute])

                                        <div class="crancy__item-button--group mg-top-30">
                                            <button type="submit" class="crancy-btn crancy-btn__primary">
                                                <i class="fas fa-save"></i> {{ __('translate.Update Route') }}
                                            </button>
                                        </div>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection