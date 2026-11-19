@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Review Detail') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Review Detail') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Service') }} >> {{ __('translate.Review Detail') }}</p>
@endsection

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">

                            <div class="crancy-table crancy-table--v3 mg-top-30">

                                <div class="crancy-customer-filter">
                                    <div
                                        class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div
                                            class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Review Detail') }}</h4>

                                        </div>
                                    </div>
                                </div>

                                @php
                                    $subValues = collect((array) ($review->rating_attributes ?? []))->mapWithKeys(fn ($a) => [($a['category'] ?? '') => $a['rating'] ?? null])->all();
                                @endphp
                                <form action="{{ route('admin.tourbooking.reviews.update', $review->id) }}" method="POST" class="mg-top-30" id="reviewForm">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.User') }}</label>
                                                <p class="mb-0"><a href="{{ route('admin.user-show', $review->user_id) }}">{{ html_decode($review?->user?->name) }}</a></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Service') }}</label>
                                                <p class="mb-0"><a href="{{ route('front.tourbooking.services.show', $review->service->slug) }}" target="_blank">{{ $review?->service?->translation?->title }}</a></p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Crated at') }}</label>
                                                <p class="mb-0">{{ $review->created_at->format('d.m.Y') }} · {{ $review->rating }} {{ __('translate.star') }}</p>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Review country') }}</label>
                                                <input class="crancy__item-input" type="text" name="country" maxlength="100"
                                                    value="{{ old('country', $review->country ?: $review->user?->country) }}" placeholder="Россия">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Review city') }}</label>
                                                <input class="crancy__item-input" type="text" name="city" maxlength="100"
                                                    value="{{ old('city', $review->city) }}" placeholder="Москва">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Review language') }}</label>
                                                <select class="crancy__item-input" name="language">
                                                    <option value="">—</option>
                                                    @foreach (\Modules\TourBooking\App\Models\Review::LANGUAGES as $code => $label)
                                                        <option value="{{ $code }}" @selected(old('language', $review->language) === $code)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Stay month') }}</label>
                                                <input class="crancy__item-input" type="month" name="stay_month"
                                                    value="{{ old('stay_month', $review->stay_month) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Booking ID (optional)') }}</label>
                                                <input class="crancy__item-input" type="text" name="booking_reference" maxlength="64"
                                                    value="{{ old('booking_reference', $review->booking_reference ?: $review->booking_id) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-6">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Verified guest') }}</label>
                                                <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                    <label class="crancy__item-switch">
                                                        <input name="is_verified" type="checkbox" value="1" {{ old('is_verified', $review->is_verified) ? 'checked' : '' }}>
                                                        <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-6">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Published') }}</label>
                                                <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                    <label class="crancy__item-switch">
                                                        <input name="status" type="checkbox" value="1" {{ old('status', $review->status) ? 'checked' : '' }}>
                                                        <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Original text') }} *</label>
                                                <textarea class="crancy__item-input" name="review" rows="7" style="height:auto;min-height:170px" required>{{ old('review', $review->review) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Russian translation') }}</label>
                                                <textarea class="crancy__item-input" name="review_ru" rows="7" style="height:auto;min-height:170px">{{ old('review_ru', $review->review_ru) }}</textarea>
                                                <small class="text-muted">{{ __('translate.Russian translation hint') }}</small>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="crancy__item-form--group mg-top-form-20">
                                                <label class="crancy__item-label">{{ __('translate.Overall rating') }} *</label>
                                                <select class="crancy__item-input" name="rating" style="max-width:160px">
                                                    @foreach ([5, 4.5, 4, 3.5, 3, 2.5, 2, 1.5, 1] as $r)
                                                        <option value="{{ $r }}" @selected((float) old('rating', $review->rating) == $r)>{{ $r }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label class="crancy__item-label mg-top-form-20">{{ __('translate.Sub-scores (optional)') }}</label>
                                            <div class="row">
                                                @foreach (\Modules\TourBooking\App\Models\Review::SUB_SCORE_LABELS as $key => $label)
                                                    <div class="col-lg col-md-4 col-6">
                                                        <div class="crancy__item-form--group">
                                                            <label class="crancy__item-label" style="font-weight:400">{{ $label }}</label>
                                                            <select class="crancy__item-input" name="sub[{{ $key }}]">
                                                                <option value="">—</option>
                                                                @foreach ([5, 4, 3, 2, 1] as $r)
                                                                    <option value="{{ $r }}" @selected((string) old('sub.' . $key, isset($subValues[$key]) ? (int) $subValues[$key] : '') === (string) $r)>{{ $r }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mg-top-30 d-flex gap-2 flex-wrap">
                                        <button type="submit" class="crancy-btn"><i class="fas fa-save"></i> {{ __('translate.Save') }}</button>
                                        <a href="{{ route('admin.tourbooking.reviews.index') }}" class="crancy-btn" style="background:#64748b">{{ __('translate.Review List') }}</a>
                                        <a onclick="itemDeleteConfrimation({{ $review->id }})" href="javascript:;" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" class="crancy-btn delete_danger_btn"><i class="fas fa-trash"></i>
                                            {{ __('translate.Delete') }}</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Confirmation Modal -->
    <div class="modal fade" id="reviewApproval" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Approval Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to approved this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.tourbooking.reviews.approve', $review->id) }}" class="delet_modal_form"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Approved') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
    <script>
        "use strict"

        function itemDeleteConfrimation(id) {
            $("#item_delect_confirmation").attr("action", '{{ url('admin/tourbooking/review/delete') }}' + "/" + id)
        }
    </script>
@endpush
