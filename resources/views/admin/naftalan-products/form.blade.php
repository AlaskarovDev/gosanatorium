@php $isEdit = $product->exists; @endphp
@extends('admin.master_layout')
@section('title')
    <title>Нафталан продукты</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Нафталан продукты</h3>
    <p class="crancy-header__text">Manage Content >> Нафталан продукты >> {{ $isEdit ? 'Edit' : 'Add new' }}</p>
@endsection
@section('body-content')

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ $isEdit ? route('admin.naftalan-products.update', $product->id) : route('admin.naftalan-products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($isEdit)
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ $isEdit ? 'Редактировать товар' : 'Новый товар' }}</h4>
                                                <a href="{{ route('admin.naftalan-products.index') }}" class="crancy-btn"><i class="fa fa-list"></i> Отмена</a>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Название товара *</label>
                                                        <input class="crancy__item-input" type="text" name="name" value="{{ old('name', $product->name) }}" required maxlength="255">
                                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Описание</label>
                                                        <textarea class="crancy__item-input" name="description" rows="3" maxlength="1000">{{ old('description', $product->description) }}</textarea>
                                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Цена в манатах *</label>
                                                        <input class="crancy__item-input" type="number" step="0.01" min="0" max="99999" name="price" value="{{ old('price', $product->price) }}" required>
                                                        @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Порядок сортировки</label>
                                                        <input class="crancy__item-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
                                                        @error('sort_order') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Фото товара</label>
                                                        @if ($isEdit && $product->image)
                                                            <div class="mb-2">
                                                                <img src="{{ $product->image_url }}" alt="" style="width:100px;height:100px;object-fit:cover;border-radius:6px;">
                                                            </div>
                                                            <label class="mb-2 d-block">
                                                                <input type="checkbox" id="replace_image"> Заменить фото
                                                            </label>
                                                        @endif
                                                        <div id="image_input_box" @if ($isEdit && $product->image) style="display:none" @endif>
                                                            <input class="crancy__item-input" type="file" name="image" id="image_input" accept="image/jpeg,image/png,image/webp">
                                                            <img id="image_preview" src="" alt="" style="display:none;margin-top:10px;width:120px;height:120px;object-fit:cover;border-radius:6px;">
                                                        </div>
                                                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Сообщение WhatsApp</label>
                                                        <textarea class="crancy__item-input" name="whatsapp_message" rows="3" maxlength="500" placeholder="Если пусто: Здравствуйте! Хочу заказать: 🛍 Название (Цена ₼) Имя: Адрес доставки:">{{ old('whatsapp_message', $product->whatsapp_message) }}</textarea>
                                                        @error('whatsapp_message') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <label>
                                                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->exists ? $product->is_active : true) ? 'checked' : '' }}>
                                                            Показывать на сайте
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mg-top-30">
                                                <button type="submit" class="crancy-btn">{{ $isEdit ? 'Обновить товар' : 'Сохранить товар' }}</button>
                                                <a href="{{ route('admin.naftalan-products.index') }}" class="crancy-btn crancy-btn__danger">Отмена</a>
                                            </div>
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

@push('js_section')
    <script>
        (function () {
            var input = document.getElementById('image_input');
            var preview = document.getElementById('image_preview');
            input.addEventListener('change', function () {
                if (input.files && input.files[0]) {
                    preview.src = URL.createObjectURL(input.files[0]);
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }
            });
            var replace = document.getElementById('replace_image');
            if (replace) {
                replace.addEventListener('change', function () {
                    document.getElementById('image_input_box').style.display = replace.checked ? 'block' : 'none';
                    if (!replace.checked) { input.value = ''; preview.style.display = 'none'; }
                });
            }
        })();
    </script>
@endpush
