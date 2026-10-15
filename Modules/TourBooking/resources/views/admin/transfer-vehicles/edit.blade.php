@extends('admin.master_layout')

@section('title')
    <title>Редактировать транспорт</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Редактировать транспорт</h3>
    <p class="crancy-header__text">{{ $transferVehicle->name }}</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.tourbooking.transfer-vehicles.update', $transferVehicle) }}" method="POST" enctype="multipart/form-data" class="crancy-wc__form crancy-wc__form--input">
                                @csrf
                                @method('PUT')
                                
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
                                                                <input class="crancy__item-input" type="text" name="name" value="{{ old('name', $transferVehicle->name) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Порядок</label>
                                                                <input class="crancy__item-input" type="number" name="display_order" value="{{ old('display_order', $transferVehicle->display_order) }}" min="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Описание</label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="description" rows="4">{{ old('description', $transferVehicle->description) }}</textarea>
                                                    </div>
                                                </div>
                                                
                                                {{-- Характеристики --}}
                                                <div class="crancy__item-group mg-top-30">
                                                    <h4 class="crancy__item-group__title">Характеристики</h4>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Вместимость пассажиров *</label>
                                                                <input class="crancy__item-input" type="number" name="passenger_capacity" value="{{ old('passenger_capacity', $transferVehicle->passenger_capacity) }}" min="1" max="50" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Вместимость багажа *</label>
                                                                <input class="crancy__item-input" type="number" name="luggage_capacity" value="{{ old('luggage_capacity', $transferVehicle->luggage_capacity) }}" min="0" max="20" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Особенности</label>
                                                        <div id="features-container">
                                                            @if($transferVehicle->features && count($transferVehicle->features) > 0)
                                                                @foreach($transferVehicle->features as $feature)
                                                                    <div class="feature-row mb-2 d-flex align-items-center gap-2">
                                                                        <input class="crancy__item-input" type="text" name="features[]" value="{{ $feature }}">
                                                                        <button type="button" class="btn btn-danger btn-sm remove-feature"><i class="fas fa-minus"></i></button>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            <div class="feature-row mb-2 d-flex align-items-center gap-2">
                                                                <input class="crancy__item-input" type="text" name="features[]" placeholder="Новая особенность">
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
                                                                <input class="crancy__item-input" type="number" step="0.01" name="base_price" value="{{ old('base_price', $transferVehicle->base_price) }}" min="0" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="crancy__item-form--group mg-top-form-20">
                                                                <label class="crancy__item-label">Цена за км (₼)</label>
                                                                <input class="crancy__item-input" type="number" step="0.01" name="price_per_km" value="{{ old('price_per_km', $transferVehicle->price_per_km) }}" min="0">
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
                                                
                                                @if($transferVehicle->image)
                                                    <div class="current-image mb-3">
                                                        <img src="{{ asset($transferVehicle->image) }}" alt="{{ $transferVehicle->name }}" class="img-fluid rounded" style="max-height: 150px;">
                                                    </div>
                                                @endif
                                                
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">Новое главное изображение</label>
                                                    <input class="crancy__item-input" type="file" name="image" accept="image/*">
                                                </div>
                                                
                                                @if($transferVehicle->gallery && count($transferVehicle->gallery) > 0)
                                                    <div class="gallery-images mt-3">
                                                        <label class="crancy__item-label">Галерея</label>
                                                        <div class="row g-2">
                                                            @foreach($transferVehicle->gallery as $index => $galleryImage)
                                                                <div class="col-4">
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset($galleryImage) }}" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                                                        <form action="{{ route('admin.tourbooking.transfer-vehicles.delete-gallery-image', $transferVehicle) }}" method="POST" class="position-absolute top-0 end-0">
                                                                            @csrf
                                                                            <input type="hidden" name="image_index" value="{{ $index }}">
                                                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 2px 6px; font-size: 10px;">
                                                                                <i class="fas fa-times"></i>
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">Добавить в галерею</label>
                                                    <input class="crancy__item-input" type="file" name="gallery[]" accept="image/*" multiple>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Статус --}}
                                        <div class="crancy-ptabs__separate mg-top-30">
                                            <div class="crancy__item-group">
                                                <h4 class="crancy__item-group__title">Статус</h4>
                                                
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="status" id="status" {{ $transferVehicle->status ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="status">Активен</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @include('tourbooking::admin._partials.season_prices', ['entity' => $transferVehicle])

                                        {{-- Действия --}}
                                        <div class="crancy-ptabs__separate mg-top-30">
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary btn-lg" type="submit">
                                                    <i class="fas fa-save"></i> Сохранить изменения
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
    $(document).on('click', '.add-feature', function() {
        var newRow = `
            <div class="feature-row mb-2 d-flex align-items-center gap-2">
                <input class="crancy__item-input" type="text" name="features[]" placeholder="Новая особенность">
                <button type="button" class="btn btn-danger btn-sm remove-feature"><i class="fas fa-minus"></i></button>
            </div>
        `;
        $('#features-container').append(newRow);
    });
    
    $(document).on('click', '.remove-feature', function() {
        $(this).closest('.feature-row').remove();
    });
});
</script>
@endpush
