@php $isEdit = $akcii->exists; @endphp
@extends('admin.master_layout')
@section('title')
    <title>Акции</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Акции</h3>
    <p class="crancy-header__text">Manage Content >> Акции >> {{ $isEdit ? 'Edit' : 'Add new' }}</p>
@endsection
@section('body-content')

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ $isEdit ? route('admin.akcii.update', $akcii->id) : route('admin.akcii.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($isEdit)
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ $isEdit ? 'Edit' : 'Add new' }}</h4>
                                                <a href="{{ route('admin.akcii.index') }}" class="crancy-btn"><i class="fa fa-list"></i> Акции</a>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Title *</label>
                                                        <input class="crancy__item-input" type="text" name="title" value="{{ old('title', $akcii->title) }}" required>
                                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Slug</label>
                                                        <input class="crancy__item-input" type="text" name="slug" value="{{ old('slug', $akcii->slug) }}" placeholder="leave empty to generate from title">
                                                        @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Short description</label>
                                                        <textarea class="crancy__item-input" name="description" rows="3">{{ old('description', $akcii->description) }}</textarea>
                                                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Content</label>
                                                        <textarea class="crancy__item-input" name="content" rows="8">{{ old('content', $akcii->content) }}</textarea>
                                                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Image</label>
                                                        @if ($akcii->image)
                                                            <div class="mb-2"><img src="{{ asset($akcii->image) }}" alt="" style="max-height:80px"></div>
                                                        @endif
                                                        <input class="crancy__item-input" type="file" name="image" accept="image/*">
                                                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Sanatorium name</label>
                                                        <input class="crancy__item-input" type="text" name="sanatorium_name" value="{{ old('sanatorium_name', $akcii->sanatorium_name) }}">
                                                        @error('sanatorium_name') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Discount, %</label>
                                                        <input class="crancy__item-input" type="number" min="0" max="100" name="discount_percent" value="{{ old('discount_percent', $akcii->discount_percent) }}">
                                                        @error('discount_percent') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Valid until</label>
                                                        <input class="crancy__item-input" type="date" name="valid_until" value="{{ old('valid_until', $akcii->valid_until?->format('Y-m-d')) }}">
                                                        @error('valid_until') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Sort order</label>
                                                        <input class="crancy__item-input" type="number" name="sort_order" value="{{ old('sort_order', $akcii->sort_order ?? 0) }}">
                                                        @error('sort_order') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Language</label>
                                                        <select class="form-select crancy__item-input" name="lang">
                                                            @foreach (['ru' => 'Русский', 'en' => 'English'] as $code => $label)
                                                                <option value="{{ $code }}" {{ old('lang', $akcii->lang ?? 'ru') === $code ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Active</label>
                                                        <input type="hidden" name="is_active" value="0">
                                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $akcii->is_active) ? 'checked' : '' }}>
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
