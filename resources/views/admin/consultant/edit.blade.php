@extends('admin.master_layout')
@section('title')
    <title>Консультант</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Консультант</h3>
    <p class="crancy-header__text">Manage Content >> Консультант</p>
@endsection
@section('body-content')

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.consultant.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">Консультант на главной странице</h4>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Имя *</label>
                                                        <input class="crancy__item-input" type="text" name="name" value="{{ old('name', $consultant->name) }}" required>
                                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Должность *</label>
                                                        <input class="crancy__item-input" type="text" name="role" value="{{ old('role', $consultant->role) }}" required>
                                                        @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Телефон</label>
                                                        <input class="crancy__item-input" type="text" name="phone" value="{{ old('phone', $consultant->phone) }}">
                                                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Email</label>
                                                        <input class="crancy__item-input" type="email" name="email" value="{{ old('email', $consultant->email) }}">
                                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Номер WhatsApp (только цифры, например 994519158894)</label>
                                                        <input class="crancy__item-input" type="text" name="whatsapp" value="{{ old('whatsapp', $consultant->whatsapp) }}">
                                                        @error('whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Текст кнопки</label>
                                                        <input class="crancy__item-input" type="text" name="button_text" value="{{ old('button_text', $consultant->button_text) }}">
                                                        @error('button_text') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Ссылка кнопки</label>
                                                        <input class="crancy__item-input" type="text" name="button_url" value="{{ old('button_url', $consultant->button_url) }}">
                                                        @error('button_url') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Фото</label>
                                                        @if ($consultant->photo)
                                                            <div class="mb-2"><img src="/images/consultants/{{ $consultant->photo }}" alt="" style="max-height:80px"></div>
                                                        @endif
                                                        <input class="crancy__item-input" type="file" name="photo" accept="image/*">
                                                        @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Показывать на сайте</label>
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $consultant->is_active) ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="crancy-btn mg-top-25">Save</button>
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
