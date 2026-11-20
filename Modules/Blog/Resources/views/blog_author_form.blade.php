@extends('admin.master_layout')
@php($isEdit = $author->exists)
@section('title')
    <title>{{ $isEdit ? 'Редактировать автора' : 'Новый автор' }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ $isEdit ? 'Редактировать автора' : 'Новый автор' }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Blog') }} >> Авторы и врачи</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ $isEdit ? route('admin.blog-author.update', $author->id) : route('admin.blog-author.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($isEdit) @method('PUT') @endif
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ $isEdit ? $author->name : 'Новый автор' }}</h4>
                                                <a href="{{ route('admin.blog-author.index') }}" class="crancy-btn"><i class="fa fa-list"></i> Список авторов</a>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Имя *</label>
                                                        <input class="crancy__item-input" type="text" name="name" value="{{ old('name', $author->name) }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Роль (например, «Эксперт по санаториям»)</label>
                                                        <input class="crancy__item-input" type="text" name="role" value="{{ old('role', $author->role) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Специальность врача (для «Проверено врачом»)</label>
                                                        <input class="crancy__item-input" type="text" name="specialty" value="{{ old('specialty', $author->specialty) }}" placeholder="например, врач-физиотерапевт">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Ссылка на страницу в «Команде» (необязательно)</label>
                                                        <input class="crancy__item-input" type="text" name="profile_url" value="{{ old('profile_url', $author->profile_url) }}" placeholder="/ru/team/...">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Коротко об авторе</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="bio" rows="3">{{ old('bio', $author->bio) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Фото (квадратное, от 200×200)</label>
                                                        @if ($author->photo)
                                                            <div class="mg-top-10"><img src="{{ asset($author->photo) }}" alt="" style="width:80px;height:80px;border-radius:50%;object-fit:cover"></div>
                                                        @endif
                                                        <input class="crancy__item-input" type="file" name="photo" accept="image/*">
                                                        <small>Без фото показывается кружок с инициалами.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    @foreach ([['is_medical', 'Медицинский эксперт (врач)'], ['is_default', 'Автор по умолчанию (для статей без автора)'], ['status', 'Активен']] as [$field, $label])
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ $label }}</label>
                                                            <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                                <label class="crancy__item-switch">
                                                                    <input name="{{ $field }}" type="checkbox" value="1" {{ old($field, $author->{$field}) ? 'checked' : '' }}>
                                                                    <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ $isEdit ? __('translate.Update') : __('translate.Save') }}</button>
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
