@php $isEdit = $seo->exists; @endphp
@extends('admin.master_layout')
@section('title')
    <title>SEO</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">SEO</h3>
    <p class="crancy-header__text">Manage Content >> SEO >> {{ $isEdit ? 'Edit' : 'Add new page' }}</p>
@endsection
@section('body-content')

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ $isEdit ? route('admin.seo.update', $seo->id) : route('admin.seo.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($isEdit)
                                    @method('PUT')
                                @endif
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ $isEdit ? 'Edit' : 'Add new page' }}</h4>
                                                <a href="{{ route('admin.seo.index') }}" class="crancy-btn"><i class="fa fa-list"></i> SEO</a>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Page Key *</label>
                                                        <input class="crancy__item-input" type="text" name="page_key" value="{{ old('page_key', $seo->page_key) }}" placeholder="home, diseases, bez-predoplaty, blogs, blog-{slug}" {{ $isEdit ? 'disabled' : 'required' }}>
                                                        @error('page_key') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">SEO Title</label>
                                                        <input class="crancy__item-input seo-count" data-limit="60" data-target="#cnt_seo_title" type="text" name="seo_title" value="{{ old('seo_title', $seo->seo_title) }}">
                                                        <small id="cnt_seo_title" class="text-muted"></small>
                                                        @error('seo_title') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">SEO Description</label>
                                                        <textarea class="crancy__item-input seo-count" data-limit="160" data-target="#cnt_seo_description" name="seo_description" rows="3">{{ old('seo_description', $seo->seo_description) }}</textarea>
                                                        <small id="cnt_seo_description" class="text-muted"></small>
                                                        @error('seo_description') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">SEO Keywords</label>
                                                        <input class="crancy__item-input" type="text" name="seo_keywords" value="{{ old('seo_keywords', $seo->seo_keywords) }}">
                                                        @error('seo_keywords') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">OG Title</label>
                                                        <input class="crancy__item-input" type="text" name="og_title" value="{{ old('og_title', $seo->og_title) }}" placeholder="leave empty to use SEO Title">
                                                        @error('og_title') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">OG Description</label>
                                                        <textarea class="crancy__item-input" name="og_description" rows="3" placeholder="leave empty to use SEO Description">{{ old('og_description', $seo->og_description) }}</textarea>
                                                        @error('og_description') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">OG Image (URL or upload, 1200×630 recommended)</label>
                                                        <input class="crancy__item-input" type="text" name="og_image" value="{{ old('og_image', $seo->og_image) }}" placeholder="https://... (leave empty to use the default image)">
                                                        @error('og_image') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                                        <input class="crancy__item-input mg-top-10" type="file" name="og_image_file" accept="image/*">
                                                        @error('og_image_file') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                                        @if ($seo->og_image)
                                                            <img src="{{ preg_match('#^https?://#i', $seo->og_image) ? $seo->og_image : asset($seo->og_image) }}" alt="" style="max-width:240px;margin-top:10px;">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Canonical URL</label>
                                                        <input class="crancy__item-input" type="text" name="canonical_url" value="{{ old('canonical_url', $seo->canonical_url) }}" placeholder="leave empty to use current page URL">
                                                        @error('canonical_url') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">Robots</label>
                                                        <select class="form-select crancy__item-input" name="robots">
                                                            @foreach ($robots as $r)
                                                                <option value="{{ $r }}" {{ old('robots', $seo->robots) === $r ? 'selected' : '' }}>{{ $r }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('robots') <span class="text-danger">{{ $message }}</span> @enderror
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

@push('js_section')
    <script>
        (function () {
            document.querySelectorAll('.seo-count').forEach(function (el) {
                var out = document.querySelector(el.dataset.target);
                var limit = parseInt(el.dataset.limit, 10);
                function update() {
                    var n = el.value.length;
                    out.textContent = n + ' / ' + limit + (n > limit ? ' — too long, may be cut off in search results' : '');
                    out.className = n > limit ? 'text-danger' : 'text-muted';
                }
                el.addEventListener('input', update);
                update();
            });
        })();
    </script>
@endpush
