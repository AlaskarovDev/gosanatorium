@extends('admin.master_layout')

@section('title')
    <title>Добавить транспорт</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Добавить транспорт</h3>
    <p class="crancy-header__text">Создание нового транспортного средства</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.tourbooking.transfer-vehicles.store') }}" method="POST" enctype="multipart/form-data" class="crancy-wc__form crancy-wc__form--input">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="crancy-ptabs__separate">
                                            <div class="crancy-ptabs__form-main">
                                                {{-- Основная информация --}}
                                                <div class="crancy__item-group">
                                                    <h4 class="crancy__item-group__title">Основная информация</h4>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Название *</label>
                                                                <input class="crancy__item-input" type="text" name="name" value="{{ old('name') }}" required placeholder="Mercedes Vito">
                                                                @error('name')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Порядок</label>
                                                                <input class="crancy__item-input" type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Описание</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="description" rows="4" placeholder="Комфортабельный минивэн для групповых поездок...">{{ old('description') }}</textarea>
                                                    </div>
                                                </div>
                                                
                                                {{-- Характеристики --}}
                                                <div class="crancy__item-group mg-top-30">
                                                    <h4 class="crancy__item-group__title">Характеристики</h4>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Вместимость пассажиров *</label>
                                                                <input class="crancy__item-input" type="number" name="passenger_capacity" value="{{ old('passenger_capacity', 4) }}" min="1" max="50" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Вместимость багажа *</label>
                                                                <input class="crancy__item-input" type="number" name="luggage_capacity" value="{{ old('luggage_capacity', 2) }}" min="0" max="20" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Особенности (по одной на строку)</label>
                                                        <div id="features-container">
                                                            <div class="feature-row mb-2 d-flex align-items-center gap-2">
                                                                <input class="crancy__item-input" type="text" name="features[]" placeholder="Кондиционер">
                                                                <button type="button" class="btn btn-success btn-sm add-feature"><i class="fas fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- Цены --}}
                                                <div class="crancy__item-group mg-top-30">
                                                    <h4 class="crancy__item-group__title">Цены</h4>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Базовая цена (₼) *</label>
                                                                <input class="crancy__item-input" type="number" step="0.01" name="base_price" value="{{ old('base_price', 0) }}" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Цена за км (₼)</label>
                                                                <input class="crancy__item-input" type="number" step="0.01" name="price_per_km" value="{{ old('price_per_km') }}" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        {{-- Изображения --}}
                                        <div class="crancy-ptabs__separate">
                                            <div class="crancy__item-group">
                                                <h4 class="crancy__item-group__title">Изображения</h4>
                                                
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">Главное изображение</label>
                                                    <input class="crancy__item-input" type="file" name="image" accept="image/*">
                                                    <small class="text-muted">Рекомендуемый размер: 800x500</small>
                                                </div>
                                                
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">Галерея</label>
                                                    <input class="crancy__item-input" type="file" name="gallery[]" accept="image/*" multiple>
                                                    <small class="text-muted">Можно выбрать несколько файлов</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Статус --}}
                                        <div class="crancy-ptabs__separate mg-top-30">
                                            <div class="crancy__item-group">
                                                <h4 class="crancy__item-group__title">Статус</h4>
                                                
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                                        <label class="form-check-label" for="status">Активен</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Действия --}}
                                        <div class="crancy-ptabs__separate mg-top-30">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary btn-lg" type="submit">
                                                    <i class="fas fa-save"></i> Сохранить
                                                </button>
                                                <a href="{{ route('admin.tourbooking.transfer-vehicles.index') }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-arrow-left"></i> Назад к списку
                                                </a>
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
$(document).ready(function() {
    // Add feature row
    $(document).on('click', '.add-feature', function() {
        var newRow = `
            <div class="feature-row mb-2 d-flex align-items-center gap-2">
                <input class="crancy__item-input" type="text" name="features[]" placeholder="Новая особенность">
                <button type="button" class="btn btn-danger btn-sm remove-feature"><i class="fas fa-minus"></i></button>
            </div>
        `;
        $('#features-container').append(newRow);
    });
    
    // Remove feature row
    $(document).on('click', '.remove-feature', function() {
        $(this).closest('.feature-row').remove();
    });
});
</script>
@endpush
