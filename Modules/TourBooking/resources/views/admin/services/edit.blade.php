@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Service') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('Edit Service') }}</h3>
    <p class="crancy-header__text">{{ __('Tour Booking') }} >> {{ __('Edit Service') }}</p>
@endsection

@push('style_section')
    <link rel="stylesheet" href="{{ asset('global/select2/select2.min.css') }}">
    <style>
        /* === Service edit page — collapsible card sections === */
        #serviceEditNav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(20, 30, 60, .08);
            margin-bottom: 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        #serviceEditNav .nav-pill {
            border: 1px solid #e3e7ef;
            background: #f7f9fc;
            color: #2c3a55;
            font-size: 13px;
            font-weight: 500;
            padding: 7px 14px;
            border-radius: 999px;
            cursor: pointer;
            transition: all .18s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        #serviceEditNav .nav-pill:hover { background: #ecf2fa; border-color: #b9c8e3; }
        #serviceEditNav .nav-pill.is-active { background: #1f4ad7; color: #fff; border-color: #1f4ad7; }
        #serviceEditNav .nav-pill .badge-dot {
            width: 6px; height: 6px; border-radius: 50%; background: #b9c8e3; display: inline-block;
        }
        #serviceEditNav .nav-pill.is-active .badge-dot { background: #fff; }

        #serviceForm .crancy-product-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(20, 30, 60, .04);
            overflow: hidden;
            margin-bottom: 16px;
            transition: box-shadow .2s;
            border: 1px solid #eef1f6;
        }
        #serviceForm .crancy-product-card:hover { box-shadow: 0 6px 20px rgba(20, 30, 60, .07); }
        #serviceForm .crancy-product-card > *:first-child {
            padding: 16px 20px;
            background: linear-gradient(to right, #f9fbff, #fff);
            border-bottom: 1px solid #eef1f6;
            margin: 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
            position: relative;
        }
        #serviceForm .crancy-product-card > *:first-child::before {
            content: "";
            position: absolute;
            right: 18px;
            top: 50%;
            width: 8px; height: 8px;
            border-right: 2px solid #6b7a99;
            border-bottom: 2px solid #6b7a99;
            transform: translateY(-75%) rotate(45deg);
            transition: transform .2s;
        }
        #serviceForm .crancy-product-card.is-collapsed > *:first-child::before {
            transform: translateY(-25%) rotate(-45deg);
        }
        #serviceForm .crancy-product-card > *:first-child .crancy-product-card__title {
            margin: 0 !important;
            padding-right: 30px;
            font-size: 16px;
            font-weight: 600;
            color: #1d2540;
        }
        #serviceForm .crancy-product-card.is-collapsed > *:not(:first-child) { display: none !important; }
        #serviceForm .crancy-product-card > *:not(:first-child) { padding: 0 20px 20px; }
        #serviceForm .crancy-product-card .row.mg-top-30,
        #serviceForm .crancy-product-card .mg-top-30 { margin-top: 12px !important; }

        /* Tighter form fields, more responsive */
        #serviceForm .crancy__item-form--group { margin-bottom: 12px; }
        #serviceForm .crancy__item-label {
            font-size: 13px;
            font-weight: 500;
            color: #4a5670;
            margin-bottom: 6px;
            display: block;
        }
        #serviceForm .crancy__item-input {
            border: 1px solid #dde3ec;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            background: #fff;
        }
        #serviceForm .crancy__item-input:focus { border-color: #1f4ad7; box-shadow: 0 0 0 3px rgba(31,74,215,.1); outline: none; }

        @media (max-width: 768px) {
            #serviceEditNav { padding: 10px; gap: 4px; }
            #serviceEditNav .nav-pill { font-size: 12px; padding: 6px 10px; }
            #serviceForm .crancy-product-card > *:first-child { padding: 14px 16px; }
            #serviceForm .crancy-product-card > *:not(:first-child) { padding: 0 14px 14px; }
        }

        /* ===== Add/Edit Room modal — modern design ===== */
        .room-edit-modal .modal-content {
            border: none !important;
            border-radius: 18px !important;
            overflow: hidden !important;
            box-shadow: 0 30px 90px rgba(15, 25, 50, .35) !important;
        }
        .room-edit-modal .modal-header {
            background: linear-gradient(135deg, #1f4ad7 0%, #5b3df5 100%);
            color: #fff;
            padding: 18px 24px;
            border: none;
            position: relative;
        }
        .room-edit-modal .modal-title {
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            display: flex;
            align-items: center;
        }
        .room-edit-modal .modal-title i { color: rgba(255,255,255,.9); }
        .room-edit-modal .btn-close {
            background-color: rgba(255,255,255,.18);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3E%3Cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3E%3C/svg%3E");
            background-size: 12px;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 50%;
            opacity: 1;
            width: 32px; height: 32px;
            transition: all .15s;
        }
        .room-edit-modal .btn-close:hover { background-color: rgba(255,255,255,.32); transform: rotate(90deg); }

        .room-edit-modal .modal-body {
            background: #f7f8fc !important;
            padding: 24px !important;
            max-height: calc(100vh - 220px);
            overflow-y: auto !important;
        }
        .room-edit-modal .modal-body::-webkit-scrollbar { width: 8px; }
        .room-edit-modal .modal-body::-webkit-scrollbar-track { background: transparent; }
        .room-edit-modal .modal-body::-webkit-scrollbar-thumb { background: #cdd3e0; border-radius: 4px; }
        .room-edit-modal .modal-body::-webkit-scrollbar-thumb:hover { background: #b9c8e3; }
        .room-edit-modal .modal-body > .row > [class^="col-"] {
            margin-bottom: 14px;
        }

        /* Field groups as cards */
        .room-edit-modal .modal-body .crancy__item-label,
        .room-edit-modal .modal-body label.form-label {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #4a5670 !important;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 6px !important;
            display: block;
        }

        .room-edit-modal .modal-body .form-control,
        .room-edit-modal .modal-body select.form-control {
            background: #fff;
            border: 1.5px solid #e5e8f0;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            color: #1d2540;
            transition: all .15s;
            box-shadow: 0 1px 2px rgba(15, 25, 50, .03);
        }
        .room-edit-modal .modal-body .form-control:focus {
            border-color: #1f4ad7 !important;
            box-shadow: 0 0 0 4px rgba(31, 74, 215, .12) !important;
            outline: none;
        }
        .room-edit-modal .modal-body small.text-muted {
            font-size: 11.5px !important;
            color: #6b7a99 !important;
            margin-top: 4px;
            display: block;
        }
        .room-edit-modal .modal-body textarea.form-control { min-height: 90px; resize: vertical; }

        /* Section dividers (hr) */
        .room-edit-modal .modal-body hr {
            margin: 18px 0;
            border-top: 1px dashed #cdd3e0;
            opacity: 1;
        }
        .room-edit-modal .modal-body h6 {
            font-weight: 700 !important;
            color: #1d2540 !important;
            font-size: 14px !important;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 6px !important;
            margin-bottom: 14px !important;
            padding: 10px 14px;
            background: linear-gradient(to right, #eef3ff, #fff);
            border-left: 3px solid #1f4ad7;
            border-radius: 6px;
        }
        .room-edit-modal .modal-body h6 i { color: #1f4ad7; }

        /* File inputs */
        .room-edit-modal .modal-body input[type="file"] {
            background: #fff;
            border: 1.5px dashed #c3cad8;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            transition: all .15s;
        }
        .room-edit-modal .modal-body input[type="file"]:hover {
            border-color: #1f4ad7;
            background: #f4f7ff;
        }

        /* Gallery zone */
        .room-edit-modal .gallery-upload-zone {
            background: #fff;
            border: 2px dashed #c3cad8;
            border-radius: 12px;
            padding: 16px;
            transition: all .2s;
        }
        .room-edit-modal .gallery-upload-zone:hover { border-color: #1f4ad7; background: #f4f7ff; }
        .room-edit-modal .gallery-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 10px;
        }
        .room-edit-modal .gallery-preview-item .gp-img-wrap {
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 4/3;
            box-shadow: 0 4px 10px rgba(15, 25, 50, .08);
        }
        .room-edit-modal .gallery-preview-item img { width: 100%; height: 100%; object-fit: cover; }
        .room-edit-modal .gallery-count-badge {
            background: #eef3ff;
            color: #1f4ad7;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
        }

        /* Card sections inside modal (Room Extras, Seasonal Pricing) */
        .room-edit-modal .card {
            background: #fff !important;
            border: 1px solid #e5e8f0 !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 6px rgba(15, 25, 50, .03);
            overflow: hidden;
        }
        .room-edit-modal .card-header {
            background: linear-gradient(to right, #f7f9ff, #fff) !important;
            border-bottom: 1px solid #eef1f6 !important;
            padding: 12px 16px !important;
            font-weight: 600 !important;
            color: #1d2540 !important;
        }
        .room-edit-modal .card-body { padding: 16px !important; }

        /* Modal footer buttons */
        .room-edit-modal .modal-footer {
            background: #fff;
            border-top: 1px solid #eef1f6;
            padding: 16px 24px;
            gap: 10px;
        }
        .room-edit-modal .modal-footer .crancy-btn,
        .room-edit-modal .modal-footer .btn {
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all .15s;
        }
        .room-edit-modal .modal-footer .crancy-btn:not(.crancy-btn__secondary) {
            background: linear-gradient(135deg, #1f4ad7 0%, #5b3df5 100%);
            color: #fff;
            box-shadow: 0 6px 16px rgba(31, 74, 215, .25);
            border: none;
        }
        .room-edit-modal .modal-footer .crancy-btn:not(.crancy-btn__secondary):hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(31, 74, 215, .35);
        }
        .room-edit-modal .modal-footer .crancy-btn__secondary {
            background: #fff;
            color: #4a5670;
            border: 1.5px solid #dde3ec;
        }
        .room-edit-modal .modal-footer .crancy-btn__secondary:hover {
            background: #f4f6fb;
            border-color: #b9c8e3;
        }

        /* Existing image overlay */
        .room-edit-modal .gp-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,.45), transparent);
            opacity: 0; transition: opacity .15s;
            display: flex; align-items: flex-start; justify-content: flex-end; padding: 6px;
        }
        .room-edit-modal .gallery-preview-item:hover .gp-overlay { opacity: 1; }
        .room-edit-modal .gp-remove-btn {
            background: rgba(255,255,255,.95);
            color: #dc2626;
            border: none;
            width: 26px; height: 26px;
            border-radius: 50%;
            cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all .15s;
        }
        .room-edit-modal .gp-remove-btn:hover { background: #dc2626; color: #fff; }
        .room-edit-modal .gp-label {
            position: absolute; bottom: 6px; left: 6px;
            background: rgba(15, 25, 50, .75);
            color: #fff;
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 10px;
            backdrop-filter: blur(4px);
        }
        .room-edit-modal .gp-label.existing-label { background: rgba(31, 74, 215, .85); }

        @media (max-width: 768px) {
            .room-edit-modal .modal-body { padding: 16px; }
            .room-edit-modal .modal-header { padding: 14px 18px; }
            .room-edit-modal .modal-title { font-size: 16px; }
        }

        /* Currency Input Field Styling */
        .crancy__item-form--currency {
            position: relative;
            display: flex;
            align-items: center;
        }

        .crancy__item-form--currency .crancy__item-input {
            width: 100%;
            padding-right: 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px 40px 10px 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .crancy__item-form--currency .crancy__item-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .crancy__currency-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 2;
        }

        .crancy__currency-icon span {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .crancy__item-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .mg-top-form-20 {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .crancy__item-form--currency .crancy__item-input {
                padding-right: 35px;
            }

            .crancy__currency-icon {
                right: 10px;
            }

            .crancy__currency-icon span {
                font-size: 13px;
            }
        }

        /* Room Management Styles */
        .room-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .room-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
        }

        .crancy-btn__danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        .crancy-btn__danger:hover {
            background-color: #c82333 !important;
            border-color: #bd2130 !important;
        }

        .crancy-btn__secondary {
            background-color: #6c757d !important;
            border-color: #6c757d !important;
        }

        .crancy-btn__nostyle {
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .crancy-color3 {
            color: #007bff;
        }

        .crancy-color4 {
            color: #dc3545;
        }

        /* Video Thumbnail Styles */
        .video-thumbnail-warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 10px;
            font-size: 13px;
        }

        .video-preview {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .thumbnail-required-star {
            color: #dc3545;
            font-weight: bold;
        }

        /* Gallery Preview Grid */
        .gallery-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 8px;
        }
        .gallery-preview-grid:empty { display: none; }
        .gallery-preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .gallery-preview-item:hover {
            border-color: #6366f1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .gallery-preview-item.removing {
            opacity: 0.4;
            border-color: #ef4444;
        }
        .gallery-preview-item .gp-img-wrap {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
        }
        .gallery-preview-item .gp-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-preview-item .gp-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .gallery-preview-item:hover .gp-overlay { opacity: 1; }
        .gallery-preview-item .gp-remove-btn {
            width: 28px; height: 28px;
            background: #ef4444; border: none; border-radius: 50%;
            color: #fff; font-size: 12px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .gallery-preview-item .gp-remove-btn:hover { background: #dc2626; transform: scale(1.1); }
        .gallery-preview-item .gp-label {
            display: block; text-align: center; padding: 3px;
            font-size: 10px; font-weight: 600; color: #fff;
        }
        .gallery-preview-item .gp-label.new-label { background: #22c55e; }
        .gallery-preview-item .gp-label.existing-label { background: #3b82f6; }
        .gallery-preview-item .gp-label.remove-label { background: #ef4444; }
        .gallery-count-badge {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 14px; background: #f0f9ff; border: 1px solid #bae6fd;
            border-radius: 8px; font-size: 13px; font-weight: 500; color: #0369a1;
            margin-top: 8px;
        }
    </style>
@endpush

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <!-- Product Card -->
                                    <div class="crancy-product-card translation_main_box">

                                        <div class="crancy-customer-filter">
                                            <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch">
                                                <div class="crancy-header__form crancy-header__form--customer">
                                                    <h4 class="crancy-product-card__title">
                                                        {{ __('Switch to language translation') }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="translation_box">
                                            <ul>
                                                @foreach ($language_list as $language)
                                                    <li><a href="{{ route('admin.tourbooking.services.edit', ['service' => $service->id, 'lang_code' => $language->lang_code]) }}">
                                                            @if (request()->get('lang_code') == $language->lang_code)
                                                                <i class="fas fa-eye"></i>
                                                            @else
                                                                <i class="fas fa-edit"></i>
                                                            @endif
                                                            {{ $language->lang_name }}
                                                        </a></li>
                                                @endforeach
                                            </ul>

                                            <div class="alert alert-secondary" role="alert">
                                                @php
                                                    $edited_language = $language_list
                                                        ->where('lang_code', request()->get('lang_code'))
                                                        ->first();
                                                @endphp
                                                <p>{{ __('Your editing mode') }} :
                                                    <b>{{ isset($edited_language) ? $edited_language->lang_name : 'Default' }}</b>
                                                </p>
                                            </div>

                                            {{-- ===== Claude ilə başqa dilə tərcümə ===== --}}
                                            <div class="ai-translate-box" style="margin-top:14px;padding-top:14px;border-top:1px dashed #e2e8f0;">
                                                <label class="crancy__item-label d-block mb-2">
                                                    <i class="fas fa-language"></i> {{ __('Translate this service to another language') }}
                                                </label>
                                                <div class="d-flex align-items-center" style="gap:8px;flex-wrap:wrap;">
                                                    <select id="aiTranslateTarget" class="form-control" style="max-width:240px;">
                                                        <option value="">{{ __('Select language') }}</option>
                                                        @foreach ($language_list as $language)
                                                            @if ($language->lang_code !== $lang_code)
                                                                <option value="{{ $language->lang_code }}">{{ $language->lang_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button type="button" id="aiTranslateBtn" class="crancy-btn">
                                                        <i class="fas fa-language me-1"></i>{{ __('Translate') }}
                                                    </button>
                                                    <span id="aiTranslateStatus" style="font-size:13px;"></span>
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    {{ __('Translates title, descriptions and SEO from the current edit language into the selected one.') }}
                                                </small>
                                            </div>

                                            <script>
                                            (function () {
                                                var btn = document.getElementById('aiTranslateBtn');
                                                if (!btn) return;
                                                btn.addEventListener('click', function () {
                                                    var sel = document.getElementById('aiTranslateTarget');
                                                    var status = document.getElementById('aiTranslateStatus');
                                                    var target = sel.value;
                                                    if (!target) {
                                                        status.style.color = '#dc3545';
                                                        status.textContent = '{{ __('Please select a language') }}';
                                                        return;
                                                    }
                                                    var original = btn.innerHTML;
                                                    btn.disabled = true;
                                                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('Translating...') }}';
                                                    status.style.color = '#64748b';
                                                    status.textContent = '{{ __('Please wait, this may take a few seconds...') }}';
                                                    fetch('{{ route('admin.tourbooking.services.translate', ['service' => $service->id]) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json',
                                                            'Content-Type': 'application/json'
                                                        },
                                                        body: JSON.stringify({ source: '{{ $lang_code }}', target: target })
                                                    })
                                                    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
                                                    .then(function (res) {
                                                        if (res.ok && res.d && res.d.success) {
                                                            status.style.color = '#16a34a';
                                                            status.textContent = res.d.message;
                                                            // Hədəf dilin redaktə ekranına keç — artıq hazır olacaq
                                                            window.location.href = '{{ route('admin.tourbooking.services.edit', ['service' => $service->id]) }}?lang_code=' + encodeURIComponent(target);
                                                        } else {
                                                            btn.disabled = false; btn.innerHTML = original;
                                                            status.style.color = '#dc3545';
                                                            status.textContent = (res.d && res.d.message) ? res.d.message : '{{ __('Error') }}';
                                                        }
                                                    })
                                                    .catch(function () {
                                                        btn.disabled = false; btn.innerHTML = original;
                                                        status.style.color = '#dc3545';
                                                        status.textContent = '{{ __('Error, please try again') }}';
                                                    });
                                                });
                                            })();
                                            </script>
                                        </div>

                                    </div>
                                    <!-- End Product Card -->
                                </div>
                            </div>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            {{-- ==================== MAIN SERVICE FORM START ==================== --}}
                            <div id="serviceEditNav" role="tablist"></div>

                            <form id="serviceForm"
                                action="{{ route('admin.tourbooking.services.update', ['service' => $service->id, 'lang_code' => $lang_code]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="translate_id" value="{{ $translation->id ?? '' }}">
                                <input type="hidden" name="lang_code" value="{{ $lang_code }}">

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('Basic Information') }}</h4>
                                                <a href="{{ route('admin.tourbooking.services.index') }}" class="crancy-btn">
                                                    <i class="fa fa-list"></i> {{ __('Service List') }}
                                                </a>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Title') }} *</label>
                                                        <input class="crancy__item-input" type="text" name="title" id="title"
                                                            value="{{ old('title', $translation->title ?? $service->title) }}" required>
                                                        @error('title')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Slug') }}</label>
                                                        <input class="crancy__item-input" type="text" name="slug" id="slug"
                                                            value="{{ old('slug', $service->slug) }}">
                                                        @error('slug')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Service Type') }}</label>
                                                        <select class="crancy__item-input" name="service_type_id" required>
                                                            <option value="">{{ __('Select Type') }}</option>
                                                            @foreach ($serviceTypes as $type)
                                                                <option value="{{ $type->id }}"
                                                                    {{ old('service_type_id', $service->service_type_id) == $type->id ? 'selected' : '' }}>
                                                                    {{ $type->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('service_type_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Select Destination') }}</label>
                                                        <select class="crancy__item-input" name="destination_id">
                                                            <option value="">{{ __('Select Destination') }}</option>
                                                            @foreach ($destinations as $destination)
                                                                <option value="{{ $destination->id }}"
                                                                    {{ old('destination_id', $service->destination_id) == $destination->id ? 'selected' : '' }}>
                                                                    {{ $destination->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('destination_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Location') }}</label>
                                                        <input class="crancy__item-input" type="text" name="location"
                                                            value="{{ old('location', $service->location) }}">
                                                        @error('location')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Duration') }}</label>
                                                        <input class="crancy__item-input" type="text" name="duration"
                                                            value="{{ old('duration', $service->duration) }}"
                                                            placeholder="e.g. 3 hours, 2 days">
                                                        @error('duration')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Group Size') }}</label>
                                                        <input class="crancy__item-input" type="text" name="group_size"
                                                            value="{{ old('group_size', $service->group_size) }}"
                                                            placeholder="e.g. Up to 10 people">
                                                        @error('group_size')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Room Count') }}</label>
                                                        <input class="crancy__item-input" type="number" name="room_count"
                                                            value="{{ old('room_count', $service->room_count) }}" placeholder="1">
                                                        @error('room_count')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Adult Count') }}</label>
                                                        <input class="crancy__item-input" type="number" name="adult_count"
                                                            value="{{ old('adult_count', $service->adult_count) }}" placeholder="1">
                                                        @error('adult_count')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Children Count') }}</label>
                                                        <input class="crancy__item-input" type="number" name="children_count"
                                                            value="{{ old('children_count', $service->children_count) }}" placeholder="0">
                                                        @error('children_count')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Short Description') }}</label>
                                                        <textarea class="crancy__item-input summernote" name="short_description" rows="3">{{ old('short_description', $translation->short_description ?? $service->short_description) }}</textarea>
                                                        @error('short_description')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Description') }}</label>
                                                        <textarea class="crancy__item-input summernote" name="description" rows="6">{{ old('description', $translation->description ?? $service->description) }}</textarea>
                                                        @error('description')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Status') }}</label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input name="status" type="checkbox" value="1"
                                                                    {{ old('status', $service->status) ? 'checked' : '' }}>
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('Pricing Details') }}</h4>

                                            <div class="col-12">
                                                <div class="crancy__item-form--group mg-top-form-20">
                                                    <label class="crancy__item-label">{{ __('Show Per Person Price') }}</label>
                                                    <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                        <label class="crancy__item-switch">
                                                            <input name="is_per_person" id="show_per_person_price" type="checkbox"
                                                                @checked($service->is_per_person == 1)>
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-4 col-md-6 col-12 d-none per_person_price_div">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Price Per Person') }}</label>
                                                        <div class="crancy__item-form--currency">
                                                            <input class="crancy__item-input" type="number" step="0.01"
                                                                name="price_per_person"
                                                                value="{{ old('price_per_person', $service->price_per_person) }}">
                                                            <div class="crancy__currency-icon">
                                                                <span>{{ config('settings.currency_icon', '$') }}</span>
                                                            </div>
                                                        </div>
                                                        @error('price_per_person')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12 full_price_div">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Full Price') }}</label>
                                                        <div class="crancy__item-form--currency">
                                                            <input class="crancy__item-input" type="number" step="0.01"
                                                                name="full_price"
                                                                value="{{ old('full_price', $service->full_price) }}">
                                                            <div class="crancy__currency-icon">
                                                                <span>{{ config('settings.currency_icon', '$') }}</span>
                                                            </div>
                                                        </div>
                                                        @error('full_price')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12 full_price_div">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Discount Price') }}</label>
                                                        <div class="crancy__item-form--currency">
                                                            <input class="crancy__item-input" type="number" step="0.01"
                                                                name="discount_price"
                                                                value="{{ old('discount_price', $service->discount_price) }}">
                                                            <div class="crancy__currency-icon">
                                                                <span>{{ config('settings.currency_icon', '$') }}</span>
                                                            </div>
                                                        </div>
                                                        @error('discount_price')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12 d-none per_person_price_div">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Child Price') }}</label>
                                                        <div class="crancy__item-form--currency">
                                                            <input class="crancy__item-input" type="number" step="0.01"
                                                                name="child_price"
                                                                value="{{ old('child_price', $service->child_price) }}">
                                                            <div class="crancy__currency-icon">
                                                                <span>{{ config('settings.currency_icon', '$') }}</span>
                                                            </div>
                                                        </div>
                                                        @error('child_price')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 mg-top-30">
                                                    @include('tourbooking::admin._partials.season_prices', ['entity' => $service])
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('Additional Information') }}</h4>

                                            <div class="row mg-top-30">
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Check-in Time') }}</label>
                                                        <input class="crancy__item-input" type="text" name="check_in_time"
                                                            value="{{ old('check_in_time', $service->check_in_time) }}"
                                                            placeholder="e.g. 14:00">
                                                        @error('check_in_time')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Check-out Time') }}</label>
                                                        <input class="crancy__item-input" type="text" name="check_out_time"
                                                            value="{{ old('check_out_time', $service->check_out_time) }}"
                                                            placeholder="e.g. 10:00">
                                                        @error('check_out_time')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Ticket') }}</label>
                                                        <input class="crancy__item-input" type="text" name="ticket"
                                                            value="{{ old('ticket', $service->ticket) }}"
                                                            placeholder="e.g. Mobile Voucher or Printed Ticket">
                                                        @error('ticket')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Video URL') }}</label>
                                                        <input class="crancy__item-input" type="url" name="video_url"
                                                            value="{{ old('video_url', $service->video_url) }}"
                                                            placeholder="YouTube or Vimeo URL">
                                                        @error('video_url')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Languages') }}</label>
                                                        <select class="crancy__item-input select2" name="languages[]" multiple>
                                                            @foreach ($enum_languages as $language)
                                                                <option value="{{ $language->name }}"
                                                                    @selected(is_array($service?->languages) && in_array($language->name, $service?->languages ?? []))>
                                                                    {{ $language->value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Amenities') }}</label>
                                                        <select class="crancy__item-input select2" name="amenities[]" multiple>
                                                            @foreach ($amenities as $amenity)
                                                                @if($amenity->translation)
                                                                <option value="{{ $amenity->translation->id }}"
                                                                    @selected(is_array($translation->amenities ?? null) && in_array($amenity->translation->id, $translation->amenities ?? []))>
                                                                    {{ $amenity->translation->name }}
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('What is included') }}</label>
<textarea class="crancy__item-input summernote" name="included" rows="6">{!! old('included', $translation->included ?? $service->included) !!}</textarea>                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('What is excluded') }}</label>
<textarea class="crancy__item-input summernote" name="excluded" rows="6">{!! old('excluded', $translation->excluded ?? $service->excluded) !!}</textarea>                                                    </div>
                                                </div>



 {{-- ========== DOCTOR / CONSULTANT SECTION ========== --}}
                                                <div class="col-12 mg-top-30">
                                                    <hr>
                                                    <h5 class="mb-3"><i class="fa fa-user-doctor me-2"></i>{{ __('translate.Doctor / Consultant') }}</h5>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Name') }}</label>
                                                        <input class="crancy__item-input" type="text" name="doctor_name"
                                                            value="{{ old('doctor_name', $service->doctor_name) }}"
                                                            placeholder="Наталья Силкова">
                                                        @error('doctor_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Position') }}</label>
                                                        <input class="crancy__item-input" type="text" name="doctor_position"
                                                            value="{{ old('doctor_position', $service->doctor_position) }}"
                                                            placeholder="Консультант">
                                                        @error('doctor_position')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Image') }}</label>
                                                        @if($service->doctor_image)
                                                            <div class="mb-2">
                                                                <img src="{{ asset($service->doctor_image) }}" 
                                                                    style="max-width: 80px; max-height: 80px; border-radius: 50%; border: 2px solid #2bbcb3;"
                                                                    class="img-thumbnail">
                                                            </div>
                                                        @endif
                                                        <input class="crancy__item-input" type="file" name="doctor_image" accept="image/*">
                                                        <small class="text-muted">{{ __('translate.Leave empty to keep current image') }}</small>
                                                        @error('doctor_image')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Video URL') }}</label>
                                                        <input class="crancy__item-input" type="url" name="doctor_video_url"
                                                            value="{{ old('doctor_video_url', $service->doctor_video_url) }}"
                                                            placeholder="https://youtube.com/watch?v=...">
                                                        @error('doctor_video_url')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Consultant Button URL') }}</label>
                                                        <input class="crancy__item-input" type="url" name="consultant_button_url"
                                                            value="{{ old('consultant_button_url', $service->consultant_button_url) }}"
                                                            placeholder="https://gosanatorium.com/custom-page/consultant">
                                                        <small class="text-muted">{{ __('translate.Default') }}: https://gosanatorium.com/custom-page/consultant</small>
                                                        @error('consultant_button_url')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Doctor Description') }}</label>
                                                        <textarea class="crancy__item-input" name="doctor_description" rows="3"
                                                            placeholder="Затрудняетесь самостоятельно выбрать санаторий? Обращайтесь в нашу службу поддержки...">{{ old('doctor_description', $service->doctor_description) }}</textarea>
                                                        @error('doctor_description')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 mg-top-10">
                                                    <hr>
                                                </div>
                                                {{-- ========== END DOCTOR / CONSULTANT SECTION ========== --}}



                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Tour Plan Sub Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="tour_plan_sub_title"
                                                            value="{{ old('tour_plan_sub_title', $service->tour_plan_sub_title) }}"
                                                            placeholder="Tour Plan Sub Title">
                                                        @error('tour_plan_sub_title')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('Contact Information') }}</h4>

                                            <div class="row mg-top-30">
                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Address') }}</label>
                                                        <textarea name="address" rows="40">{{ old('address', $service->address) }}</textarea>
                                                        @error('address')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Email') }}</label>
                                                        <input class="crancy__item-input" type="email" name="email"
                                                            value="{{ old('email', $service->email) }}">
                                                        @error('email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Phone') }}</label>
                                                        <input class="crancy__item-input" type="text" name="phone"
                                                            value="{{ old('phone', $service->phone) }}">
                                                        @error('phone')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Website') }}</label>
                                                        <input class="crancy__item-input" type="url" name="website"
                                                            value="{{ old('website', $service->website) }}">
                                                        @error('website')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Google map sub title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="google_map_sub_title"
                                                            value="{{ old('google_map_sub_title', $service->google_map_sub_title) }}"
                                                            placeholder="Google map sub title">
                                                        @error('google_map_sub_title')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Google map embed url') }}</label>
                                                        <input class="crancy__item-input" type="text" name="google_map_url"
                                                            value="{{ old('google_map_url', $service->google_map_url) }}"
                                                            placeholder="Google map embed url">
                                                        @error('google_map_url')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('SEO Information') }}</h4>

                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('SEO Title') }}</label>
                                                        <input class="crancy__item-input" type="text" name="seo_title"
                                                            value="{{ old('seo_title', $translation->seo_title ?? $service->seo_title) }}">
                                                        @error('seo_title')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('SEO Description') }}</label>
                                                        <textarea class="crancy__item-input summernote" name="seo_description" rows="3">{{ old('seo_description', $translation->seo_description ?? $service->seo_description) }}</textarea>
                                                        @error('seo_description')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('SEO Keywords') }}</label>
                                                        <input class="crancy__item-input" type="text" name="seo_keywords"
                                                            value="{{ old('seo_keywords', $translation->seo_keywords ?? $service->seo_keywords) }}"
                                                            placeholder="Comma separated keywords">
                                                        @error('seo_keywords')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('Display Options') }}</h4>

                                            <div class="row mg-top-30">
                                                <div class="col-lg-3 col-md-4 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Featured') }}</label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input name="is_featured" type="checkbox" value="1"
                                                                    {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}>
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Popular') }}</label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input name="is_popular" type="checkbox" value="1"
                                                                    {{ old('is_popular', $service->is_popular) ? 'checked' : '' }}>
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Show on Homepage') }}</label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input name="show_on_homepage" type="checkbox" value="1"
                                                                    {{ old('show_on_homepage', $service->show_on_homepage) ? 'checked' : '' }}>
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3 col-md-4 col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('Is New') }}</label>
                                                        <div class="crancy-ptabs__notify-switch crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                                <input name="is_new" type="checkbox" value="1"
                                                                    {{ old('is_new', $service->is_new) ? 'checked' : '' }}>
                                                                <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Çoxlu etiket (badge) — admin idarə edir --}}
                                            @include('tourbooking::admin.services._badges_field', ['existingBadges' => $service->badges ?? []])
                                        </div>
                                    </div>

                                    {{-- Diseases assigned to this sanatorium (managed on the disease edit page) --}}
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Diseases') }}</h4>

                                            <div class="mg-top-30">
                                                @forelse ($service->diseases as $serviceDisease)
                                                    <a href="{{ route('admin.tourbooking.diseases.edit', $serviceDisease->id) }}"
                                                        class="crancy-badge crancy-badge-success">{{ $serviceDisease->name_ru }}</a>
                                                @empty
                                                    <span class="text-muted">{{ __('translate.No diseases assigned yet') }}</span>
                                                @endforelse
                                                <p class="text-muted mg-top-form-20">{{ __('translate.Assign sanatoriums on the disease edit page') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ==================== ROOMS MANAGEMENT SECTION START ==================== --}}
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">
                                                    <i class="fas fa-bed me-2"></i>{{ __('Rooms Management') }}
                                                </h4>
                                                <button type="button" class="crancy-btn" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                                                    <i class="fas fa-plus me-1"></i>{{ __('Add Room') }}
                                                </button>
                                            </div>

                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    @if($service->rooms && $service->rooms->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped crancy-table__main crancy-table__main-v3">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="80">{{ __('Image') }}</th>
                                                                        <th>{{ __('Room Name') }}</th>
                                                                        <th>{{ __('Price/Night') }}</th>
                                                                        <th>{{ __('Max Guests') }}</th>
                                                                        <th>{{ __('Quantity') }}</th>
                                                                        <th>{{ __('Status') }}</th>
                                                                        <th width="180">{{ __('Actions') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($service->rooms as $room)
                                                                    <tr>
                                                                        <td>
                                                                            @if($room->thumbnail)
                                                                                <img src="{{ asset($room->thumbnail) }}" 
                                                                                     alt="{{ $room->name }}" 
                                                                                     class="room-thumbnail">
                                                                            @elseif($room->media && $room->media->where('is_thumbnail', true)->first())
                                                                                <img src="{{ asset($room->media->where('is_thumbnail', true)->first()->file_path) }}" 
                                                                                     alt="{{ $room->name }}" 
                                                                                     class="room-thumbnail">
                                                                            @elseif($room->media && $room->media->where('media_type', 'image')->first())
                                                                                <img src="{{ asset($room->media->where('media_type', 'image')->first()->file_path) }}" 
                                                                                     alt="{{ $room->name }}" 
                                                                                     class="room-thumbnail">
                                                                            @else
                                                                                <div class="room-placeholder">
                                                                                    <i class="fas fa-image text-muted"></i>
                                                                                </div>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            <strong>{{ $room->name }}</strong>
                                                                            @if($room->bed_type)
                                                                                <br><small class="text-muted">{{ $room->bed_type }}</small>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($room->discount_price)
                                                                                <del class="text-muted">{{ currency($room->price_per_night) }}</del>
                                                                                <br><strong class="text-success">{{ currency($room->discount_price) }}</strong>
                                                                            @else
                                                                                <strong>{{ currency($room->price_per_night) }}</strong>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $room->max_guests }} {{ __('Guests') }}</td>
                                                                        <td>{{ $room->quantity }}</td>
                                                                        <td>
                                                                            @if($room->status)
                                                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                                                            @else
                                                                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @php
                                                                                $roomGalleryImages = [];
                                                                                if ($room->media) {
                                                                                    $roomGalleryImages = $room->media
                                                                                        ->where('media_type', 'image')
                                                                                        ->where('is_thumbnail', false)
                                                                                        ->map(fn($m) => ['id' => $m->id, 'url' => asset($m->file_path)])
                                                                                        ->values()
                                                                                        ->toArray();
                                                                                }
                                                                            @endphp
                                                                            @php
                                                                                $roomSeasonPrices = $room->seasonPrices->mapWithKeys(fn($sp) => [$sp->season_id => [
                                                                                    'mode' => $sp->mode,
                                                                                    'custom_percentage' => $sp->custom_percentage,
                                                                                    'custom_price' => $sp->custom_price,
                                                                                ]])->toArray();
                                                                                $roomExtrasData = $room->extras->map(fn($x) => [
                                                                                    'name' => $x->name,
                                                                                    'type' => $x->type,
                                                                                    'price' => (float) $x->price,
                                                                                ])->toArray();
                                                                            @endphp
                                                                            <button type="button" class="crancy-btn crancy-btn__nostyle crancy-color3 edit-room-btn"
                                                                                    data-room-id="{{ $room->id }}"
                                                                                    data-room-name="{{ $room->name }}"
                                                                                    data-room-description="{{ $room->description }}"
                                                                                    data-room-price="{{ $room->price_per_night }}"
                                                                                    data-room-discount="{{ $room->discount_price }}"
                                                                                    data-room-max-guests="{{ $room->max_guests }}"
                                                                                    data-room-size="{{ $room->room_size }}"
                                                                                    data-room-bed-type="{{ $room->bed_type }}"
                                                                                    data-room-quantity="{{ $room->quantity }}"
                                                                                    data-room-status="{{ $room->status }}"
                                                                                    data-room-video-url="{{ $room->video_url }}"
data-room-features="{{ is_array($room->features) ? implode(', ', $room->features) : $room->features }}"
                                                                                    data-room-images='{!! json_encode($roomGalleryImages) !!}'
                                                                                    data-room-season-prices='{!! json_encode($roomSeasonPrices) !!}'
                                                                                    data-room-base-price-for="{{ $room->base_price_for ?? $room->max_guests ?? 2 }}"
                                                                                    data-room-extras='{!! json_encode($roomExtrasData) !!}'
                                                                                    data-room-low-stock-show="{{ $room->low_stock_show ? 1 : 0 }}"
                                                                                    data-room-low-stock-text="{{ $room->low_stock_text }}"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#editRoomModal">
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                            <button type="button" class="crancy-btn crancy-btn__nostyle crancy-color4 delete-room-btn"
                                                                                    data-room-id="{{ $room->id }}"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#deleteRoomModal">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            {{ __('No rooms added yet. Click "Add Room" to create your first room.') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ==================== ROOMS MANAGEMENT SECTION END ==================== --}}
{{-- ==================== TREATMENT ITEMS SECTION ==================== --}}
<div class="col-12 mg-top-30">
    <div class="crancy-product-card">
        <div class="create_new_btn_inline_box">
            <h4 class="crancy-product-card__title">
                <i class="fas fa-spa me-2"></i>{{ __('Treatment Gallery') }}
                <small class="text-muted ms-2" style="font-size:13px;">({{ __('Shown on service detail page') }})</small>
            </h4>
            <button type="button" class="crancy-btn" id="addTreatmentBtn">
                <i class="fas fa-plus me-1"></i>{{ __('Add Item') }}
            </button>
        </div>

        {{-- Add form (hidden initially) --}}
        <div id="treatmentAddForm" class="row mg-top-20" style="display:none;">
            <div class="col-md-5 mb-2">
                <input type="text" id="treatmentTitle" class="form-control" placeholder="{{ __('Title (e.g., Pearl Bath)') }}">
            </div>
            <div class="col-md-4 mb-2">
                <input type="file" id="treatmentImage" class="form-control" accept="image/*">
            </div>
            <div class="col-md-3 mb-2 d-flex gap-2">
                <button type="button" class="btn btn-success flex-fill" id="treatmentSaveBtn">
                    <i class="fas fa-check me-1"></i>{{ __('Save') }}
                </button>
                <button type="button" class="btn btn-secondary" id="treatmentCancelBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Items grid --}}
        <div class="row mg-top-20" id="treatmentGrid">
            @if(isset($service->treatmentItems) && $service->treatmentItems->count() > 0)
                @foreach($service->treatmentItems as $item)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3 treatment-item" data-id="{{ $item->id }}">
                        <div class="treatment-card">
                            <div class="treatment-card__img">
                                @if($item->image)
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}">
                                @else
                                    <div class="treatment-card__placeholder">
                                        <i class="fas fa-spa"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="treatment-card__title">{{ $item->title }}</div>
                            <button type="button" class="treatment-card__delete" onclick="deleteTreatmentItem({{ $item->id }}, this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center text-muted py-4" id="treatmentEmpty">
                    <i class="fas fa-spa fa-2x mb-2 d-block"></i>
                    {{ __('No treatment items yet. Click "Add Item" to start.') }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.treatment-card {
    position: relative;
    text-align: center;
    transition: all 0.3s ease;
}
.treatment-card:hover { transform: translateY(-3px); }
.treatment-card__img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 10px;
    border: 3px solid #e8e8e8;
    transition: border-color 0.3s;
}
.treatment-card:hover .treatment-card__img { border-color: #0077b6; }
.treatment-card__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.treatment-card__placeholder {
    width: 100%;
    height: 100%;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: #ccc;
}
.treatment-card__title {
    font-size: 13px;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}
.treatment-card__delete {
    position: absolute;
    top: 0;
    right: 10px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #dc3545;
    color: #fff;
    border: none;
    font-size: 11px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.treatment-card:hover .treatment-card__delete { opacity: 1; }
</style>

<script>
(function() {
    var serviceId = {{ $service->id }};
    var csrfToken = '{{ csrf_token() }}';

    document.getElementById('addTreatmentBtn').addEventListener('click', function() {
        document.getElementById('treatmentAddForm').style.display = 'flex';
        document.getElementById('treatmentTitle').focus();
    });

    document.getElementById('treatmentCancelBtn').addEventListener('click', function() {
        document.getElementById('treatmentAddForm').style.display = 'none';
        document.getElementById('treatmentTitle').value = '';
        document.getElementById('treatmentImage').value = '';
    });

    document.getElementById('treatmentSaveBtn').addEventListener('click', function() {
        var title = document.getElementById('treatmentTitle').value.trim();
        if (!title) {
            alert('{{ __("Please enter a title") }}');
            return;
        }

        var formData = new FormData();
        formData.append('title', title);
        formData.append('_token', csrfToken);
        
        var imageInput = document.getElementById('treatmentImage');
        if (imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Saving...") }}';

        fetch('/admin/tourbooking/services/' + serviceId + '/treatment-items', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': csrfToken }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                // Remove empty message
                var empty = document.getElementById('treatmentEmpty');
                if (empty) empty.remove();

                // Add new item to grid
                var grid = document.getElementById('treatmentGrid');
                var div = document.createElement('div');
                div.className = 'col-lg-2 col-md-3 col-sm-4 col-6 mb-3 treatment-item';
                div.dataset.id = data.item.id;

                var imgHtml = data.item.image 
                    ? '<img src="' + data.item.image_url + '" alt="' + data.item.title + '">'
                    : '<div class="treatment-card__placeholder"><i class="fas fa-spa"></i></div>';

                div.innerHTML = '<div class="treatment-card">' +
                    '<div class="treatment-card__img">' + imgHtml + '</div>' +
                    '<div class="treatment-card__title">' + data.item.title + '</div>' +
                    '<button type="button" class="treatment-card__delete" onclick="deleteTreatmentItem(' + data.item.id + ', this)">' +
                    '<i class="fas fa-trash"></i></button></div>';

                grid.appendChild(div);

                // Reset form
                document.getElementById('treatmentAddForm').style.display = 'none';
                document.getElementById('treatmentTitle').value = '';
                document.getElementById('treatmentImage').value = '';
            } else {
                alert(data.message || '{{ __("Error saving item") }}');
            }
        })
        .catch(function(err) {
            alert('{{ __("Error saving item") }}');
            console.error(err);
        })
        .finally(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>{{ __("Save") }}';
        });
    });

    window.deleteTreatmentItem = function(id, btn) {
        if (!confirm('{{ __("Delete this item?") }}')) return;

        fetch('/admin/tourbooking/services/treatment-items/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                var item = btn.closest('.treatment-item');
                item.remove();

                // Check if grid is empty
                var remaining = document.querySelectorAll('.treatment-item');
                if (remaining.length === 0) {
                    var grid = document.getElementById('treatmentGrid');
                    grid.innerHTML = '<div class="col-12 text-center text-muted py-4" id="treatmentEmpty">' +
                        '<i class="fas fa-spa fa-2x mb-2 d-block"></i>' +
                        '{{ __("No treatment items yet. Click \"Add Item\" to start.") }}</div>';
                }
            }
        })
        .catch(function(err) {
            alert('{{ __("Error deleting item") }}');
            console.error(err);
        });
    };
})();
</script>
                                    <div class="col-12 mg-top-30">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i>
                                            {{ __('Manage service images and videos in the') }}
                                            <a href="{{ route('admin.tourbooking.services.media', $service->id) }}"
                                                class="alert-link">{{ __('Media Gallery') }}</a>
                                        </div>
                                        <button class="crancy-btn" type="submit">{{ __('Update Service') }}</button>
                                    </div>
                                </div>
                            </form>
                            {{-- ==================== MAIN SERVICE FORM END ==================== --}}

                            <script>
                            (function () {
                                function init() {
                                    var nav = document.getElementById('serviceEditNav');
                                    if (!nav) return;
                                    var cards = document.querySelectorAll('#serviceForm .crancy-product-card');
                                    if (!cards.length) return;

                                    cards.forEach(function (card, idx) {
                                        var titleEl = card.querySelector('.crancy-product-card__title');
                                        if (!titleEl) return;

                                        var id = 'svc-section-' + idx;
                                        card.id = id;

                                        // Build pill in nav
                                        var pill = document.createElement('button');
                                        pill.type = 'button';
                                        pill.className = 'nav-pill';
                                        pill.dataset.target = id;
                                        var dot = document.createElement('span');
                                        dot.className = 'badge-dot';
                                        pill.appendChild(dot);
                                        var label = document.createElement('span');
                                        label.textContent = titleEl.textContent.trim().replace(/\s+/g, ' ');
                                        pill.appendChild(label);
                                        nav.appendChild(pill);

                                        // Collapse all except first
                                        if (idx > 0) card.classList.add('is-collapsed');

                                        // Toggle on title-row click (the first child of the card)
                                        var head = card.firstElementChild;
                                        if (head) {
                                            head.addEventListener('click', function (e) {
                                                // Don't toggle when user clicks a button/link inside the header
                                                if (e.target.closest('a, button')) return;
                                                card.classList.toggle('is-collapsed');
                                                updateActivePill();
                                            });
                                        }

                                        pill.addEventListener('click', function () {
                                            // Open this section, scroll to it
                                            card.classList.remove('is-collapsed');
                                            var top = card.getBoundingClientRect().top + window.pageYOffset - 70;
                                            window.scrollTo({ top: top, behavior: 'smooth' });
                                            setActivePill(pill);
                                        });
                                    });

                                    function setActivePill(pill) {
                                        nav.querySelectorAll('.nav-pill').forEach(function (p) { p.classList.remove('is-active'); });
                                        if (pill) pill.classList.add('is-active');
                                    }
                                    function updateActivePill() {
                                        // Highlight the first non-collapsed card pill
                                        var firstOpen = document.querySelector('#serviceForm .crancy-product-card:not(.is-collapsed)');
                                        if (!firstOpen) { setActivePill(null); return; }
                                        var pill = nav.querySelector('[data-target="' + firstOpen.id + '"]');
                                        setActivePill(pill);
                                    }

                                    updateActivePill();
                                }
                                if (document.readyState === 'loading') {
                                    document.addEventListener('DOMContentLoaded', init);
                                } else {
                                    init();
                                }
                            })();
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== MODALS OUTSIDE MAIN FORM ==================== --}}
    
    {{-- ==================== ADD ROOM MODAL ==================== --}}
    <div class="modal fade room-edit-modal" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="addRoomForm" action="{{ route('admin.tourbooking.services.rooms.store', $service->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoomModalLabel">
                            <i class="fas fa-plus me-2"></i>{{ __('Add New Room') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">{{ __('Room Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="add_room_name" class="form-control" required placeholder="{{ __('e.g., Deluxe Double Room') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">{{ __('Bed Type') }}</label>
                                <select name="bed_type" class="form-control">
                                    <option value="">{{ __('Select Bed Type') }}</option>
                                    <option value="Single Bed">Single Bed</option>
                                    <option value="Double Bed">Double Bed</option>
                                    <option value="Queen Bed">Queen Bed</option>
                                    <option value="King Bed">King Bed</option>
                                    <option value="Twin Beds">Twin Beds</option>
                                    <option value="Bunk Bed">Bunk Bed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Price Per Night') }} <span class="text-danger">*</span></label>
                                <input type="number" name="price_per_night" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Discount Price') }}</label>
                                <input type="number" name="discount_price" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('translate.Base price covers (guests)') }}</label>
                                <input type="number" name="base_price_for" class="form-control" value="2" min="1" max="20">
                                <small class="text-muted">{{ __('translate.How many guests the base price covers, e.g. 2.') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Max Guests') }}</label>
                                <input type="number" name="max_guests" class="form-control" value="2" min="1" max="20">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Room Size') }} (mÂ²)</label>
                                <input type="number" name="room_size" class="form-control" min="1" placeholder="25">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Quantity') }}</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('translate.Low Stock Badge') }}</label>
                                <select name="low_stock_show" class="form-control">
                                    <option value="0">{{ __('translate.Hidden') }}</option>
                                    <option value="1">{{ __('translate.Visible') }}</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="crancy__item-label">{{ __('translate.Badge text') }}</label>
                                <input type="text" name="low_stock_text" class="form-control" placeholder="Осталось только 1 номер!">
                                <small class="text-muted">{{ __('translate.Leave empty to auto-show remaining quantity') }}</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="crancy__item-label">{{ __('Description') }}</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="{{ __('Room description...') }}"></textarea>
                            </div>
                            <div class="col-12 mb-3">
    <label class="crancy__item-label">{{ __('Room Features') }}</label>
    <textarea name="features" class="form-control" rows="3" id="add_features_textarea" placeholder="{{ __('e.g., Free WiFi, Air Conditioning, Mini Bar, TV, Safe') }}"></textarea>
    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>{{ __('Separate features with commas') }}</small>
</div>
                            
                            {{-- VIDEO & THUMBNAIL SECTION --}}
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-video me-2"></i>{{ __('Video & Thumbnail') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">
                                    {{ __('Room Thumbnail') }}
                                    <span class="text-danger add-thumbnail-required" style="display: none;">* {{ __('Required with video') }}</span>
                                </label>
                                <input type="file" name="thumbnail" id="add_thumbnail" class="form-control" accept="image/*">
                                <small class="text-muted">{{ __('Recommended: 800x600px') }}</small>
                                <div class="alert alert-warning mt-2 add-video-warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <small>{{ __('Thumbnail is required when adding a video') }}</small>
                                </div>
                                <div id="add_thumbnail_preview" class="mt-2" style="display: none;">
                                    <img src="" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">{{ __('Video URL') }}</label>
                                <input type="url" name="video_url" id="add_video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">{{ __('YouTube or Vimeo URL') }}</small>
                                <div id="add_video_preview" class="mt-2" style="display: none;">
                                    <div class="ratio ratio-16x9" style="max-width: 250px;">
                                        <iframe id="add_video_iframe" src="" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>

                            {{-- GALLERY IMAGES SECTION --}}
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-images me-2"></i>{{ __('Gallery Images') }}</h6>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="crancy__item-label">
                                    {{ __('Room Images') }}
                                    <small class="text-muted">({{ __('You can select multiple images') }})</small>
                                </label>
                                <div class="gallery-upload-zone" id="addGalleryZone">
                                    <input type="file" 
                                           name="images[]" 
                                           id="add_gallery_images" 
                                           class="form-control"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           multiple>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('JPEG, PNG, JPG, GIF, WEBP — max 2MB per file. You can select multiple files.') }}
                                    </small>
                                </div>
                                {{-- Preview Grid --}}
                                <div id="addGalleryPreview" class="gallery-preview-grid mt-2"></div>
                                <div id="addGalleryCount" class="gallery-count-badge" style="display:none;">
                                    <i class="fas fa-images me-1"></i>
                                    <span id="addGalleryCountText">0</span> {{ __('images selected') }}
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="addGalleryClearBtn">
                                        <i class="fas fa-trash me-1"></i>{{ __('Clear All') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 mt-3 mb-2">
                                @include('tourbooking::admin._partials.room_extras', ['extras' => collect(), 'namePrefix' => 'extras', 'idSuffix' => 'add'])
                            </div>
                            <div class="col-12 mt-2">
                                @include('tourbooking::admin._partials.season_prices', ['entity' => null])
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="crancy-btn crancy-btn__secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="crancy-btn">
                            <i class="fas fa-save me-1"></i>{{ __('Save Room') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== EDIT ROOM MODAL ==================== --}}
    <div class="modal fade room-edit-modal" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoomModalLabel">
                            <i class="fas fa-edit me-2"></i>{{ __('Edit Room') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">{{ __('Room Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_room_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">{{ __('Bed Type') }}</label>
                                <select name="bed_type" id="edit_room_bed_type" class="form-control">
                                    <option value="">{{ __('Select Bed Type') }}</option>
                                    <option value="Single Bed">Single Bed</option>
                                    <option value="Double Bed">Double Bed</option>
                                    <option value="Queen Bed">Queen Bed</option>
                                    <option value="King Bed">King Bed</option>
                                    <option value="Twin Beds">Twin Beds</option>
                                    <option value="Bunk Bed">Bunk Bed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Price Per Night') }} <span class="text-danger">*</span></label>
                                <input type="number" name="price_per_night" id="edit_room_price" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Discount Price') }}</label>
                                <input type="number" name="discount_price" id="edit_room_discount" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('translate.Base price covers (guests)') }}</label>
                                <input type="number" name="base_price_for" id="edit_room_base_price_for" class="form-control" min="1" max="20">
                                <small class="text-muted">{{ __('translate.How many guests the base price covers, e.g. 2.') }}</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Max Guests') }}</label>
                                <input type="number" name="max_guests" id="edit_room_max_guests" class="form-control" min="1" max="20">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Room Size') }} (mÂ²)</label>
                                <input type="number" name="room_size" id="edit_room_size" class="form-control" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Quantity') }}</label>
                                <input type="number" name="quantity" id="edit_room_quantity" class="form-control" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('Status') }}</label>
                                <select name="status" id="edit_room_status" class="form-control">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="crancy__item-label">{{ __('translate.Low Stock Badge') }}</label>
                                <select name="low_stock_show" id="edit_room_low_stock_show" class="form-control">
                                    <option value="0">{{ __('translate.Hidden') }}</option>
                                    <option value="1">{{ __('translate.Visible') }}</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="crancy__item-label">{{ __('translate.Badge text') }}</label>
                                <input type="text" name="low_stock_text" id="edit_room_low_stock_text" class="form-control" placeholder="Осталось только 1 номер!">
                                <small class="text-muted">{{ __('translate.Leave empty to auto-show remaining quantity') }}</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="crancy__item-label">{{ __('Description') }}</label>
                                <textarea name="description" id="edit_room_description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-3">
    <label class="crancy__item-label">{{ __('Room Features') }}</label>
    <textarea name="features" class="form-control" rows="3" id="edit_features_textarea" placeholder="{{ __('e.g., Free WiFi, Air Conditioning, Mini Bar, TV, Safe') }}"></textarea>
    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>{{ __('Separate features with commas') }}</small>
</div>
                            
                            {{-- VIDEO & THUMBNAIL SECTION --}}
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-video me-2"></i>{{ __('Video & Thumbnail') }}</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">
                                    {{ __('Room Thumbnail') }}
                                    <span class="text-danger edit-thumbnail-required" style="display: none;">* {{ __('Required with video') }}</span>
                                </label>
                                <input type="file" name="thumbnail" id="edit_thumbnail" class="form-control" accept="image/*">
                                <small class="text-muted">{{ __('Leave empty to keep current image') }}</small>
                                <div class="alert alert-warning mt-2 edit-video-warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <small>{{ __('Thumbnail is required when adding a video') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="crancy__item-label">{{ __('Video URL') }}</label>
                                <input type="url" name="video_url" id="edit_video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                                <small class="text-muted">{{ __('YouTube or Vimeo URL') }}</small>
                                <div id="edit_video_preview" class="mt-2" style="display: none;">
                                    <div class="ratio ratio-16x9" style="max-width: 250px;">
                                        <iframe id="edit_video_iframe" src="" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>

                            {{-- GALLERY IMAGES SECTION --}}
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-images me-2"></i>{{ __('Gallery Images') }}</h6>
                            </div>
                            <div class="col-12 mb-3">
                                {{-- Existing images (populated via JS) --}}
                                <div id="editExistingImages" class="gallery-preview-grid mb-2"></div>
                                <div id="editRemoveImagesContainer"></div>

                                <label class="crancy__item-label">
                                    {{ __('Add More Images') }}
                                    <small class="text-muted">({{ __('You can select multiple images') }})</small>
                                </label>
                                <div class="gallery-upload-zone" id="editGalleryZone">
                                    <input type="file" 
                                           name="images[]" 
                                           id="edit_gallery_images" 
                                           class="form-control"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           multiple>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('JPEG, PNG, JPG, GIF, WEBP — max 2MB per file. You can select multiple files.') }}
                                    </small>
                                </div>
                                {{-- New images preview --}}
                                <div id="editGalleryPreview" class="gallery-preview-grid mt-2"></div>
                                <div id="editGalleryCount" class="gallery-count-badge" style="display:none;">
                                    <i class="fas fa-images me-1"></i>
                                    <span id="editGalleryCountText">0</span> {{ __('new images selected') }}
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="editGalleryClearBtn">
                                        <i class="fas fa-trash me-1"></i>{{ __('Clear') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 mt-3 mb-2" id="editRoomExtrasWrap">
                                @include('tourbooking::admin._partials.room_extras', ['extras' => collect(), 'namePrefix' => 'extras', 'idSuffix' => 'edit'])
                            </div>
                            <div class="col-12 mt-2" id="editRoomSeasonPricesWrap">
                                @include('tourbooking::admin._partials.season_prices', ['entity' => null])
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="crancy-btn crancy-btn__secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="crancy-btn">
                            <i class="fas fa-save me-1"></i>{{ __('Update Room') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== DELETE ROOM MODAL ==================== --}}
    <div class="modal fade" id="deleteRoomModal" tabindex="-1" aria-labelledby="deleteRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteRoomForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRoomModalLabel">
                            <i class="fas fa-trash me-2 text-danger"></i>{{ __('Delete Room') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are you sure you want to delete this room?') }}</p>
                        <p class="text-danger"><strong>{{ __('This action cannot be undone.') }}</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="crancy-btn crancy-btn__secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="crancy-btn crancy-btn__danger">
                            <i class="fas fa-trash me-1"></i>{{ __('Delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
    <script src="{{ asset('global/select2/select2.min.js') }}"></script>
    <script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            
            $(document).ready(function() {
                // Title to Slug
                $("#title").on("keyup", function(e) {
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
                    $("#slug").val(slug);
                });

                // Select2
                $('.select2').select2({
                    tags: true,
                    tokenSeparators: [',', ' ']
                });

                // TinyMCE
                tinymce.init({
                    selector: '.summernote',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    relative_urls: false,
                    remove_script_host: false,
                    convert_urls: false,
                    setup: function (editor) {
                        // Hər dəyişiklikdə məzmunu textarea-ya yaz (link itməsin)
                        editor.on('change keyup SetContent ExecCommand', function () {
                            editor.save();
                        });
                    }
                });

                // Form göndərilməzdən əvvəl bütün TinyMCE redaktorlarını textarea-ya flush et
                var serviceFormEl = document.getElementById('serviceForm');
                if (serviceFormEl) {
                    serviceFormEl.addEventListener('submit', function () {
                        if (window.tinymce) {
                            tinymce.triggerSave();
                        }
                    });
                }

                // Per Person Price Toggle
                @if ($service->is_per_person == 1)
                    $('.per_person_price_div').removeClass('d-none');
                    $('.full_price_div').addClass('d-none');
                @endif

                $('#show_per_person_price').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.per_person_price_div').removeClass('d-none');
                        $('.full_price_div').addClass('d-none');
                    } else {
                        $('.per_person_price_div').addClass('d-none');
                        $('.full_price_div').removeClass('d-none');
                    }
                });

                // ==================== ROOM MANAGEMENT SCRIPTS ====================
                
                // Helper: Get embed URL from YouTube/Vimeo
                function getEmbedUrl(url) {
                    if (!url) return null;
                    
                    // YouTube
                    const youtubeMatch = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
                    if (youtubeMatch) {
                        return 'https://www.youtube.com/embed/' + youtubeMatch[1];
                    }
                    
                    // Vimeo
                    const vimeoMatch = url.match(/(?:vimeo\.com\/)(\d+)/);
                    if (vimeoMatch) {
                        return 'https://player.vimeo.com/video/' + vimeoMatch[1];
                    }
                    
                    return null;
                }

             

                // ==================== ADD ROOM MODAL VALIDATION ====================
                var addVideoUrl = $('#add_video_url');
                var addThumbnail = $('#add_thumbnail');
                var addThumbnailRequired = $('.add-thumbnail-required');
                var addVideoWarning = $('.add-video-warning');
                var addVideoPreview = $('#add_video_preview');
                var addVideoIframe = $('#add_video_iframe');
                var addThumbnailPreview = $('#add_thumbnail_preview');

                function checkAddVideoUrl() {
                    var hasVideo = addVideoUrl.val().trim() !== '';
                    var hasNewThumbnail = addThumbnail[0].files && addThumbnail[0].files.length > 0;

                    if (hasVideo && !hasNewThumbnail) {
                        addThumbnailRequired.show();
                        addVideoWarning.show();
                        addThumbnail.attr('required', 'required');
                    } else {
                        addThumbnailRequired.hide();
                        addVideoWarning.hide();
                        addThumbnail.removeAttr('required');
                    }

                    // Video preview
                    if (hasVideo) {
                        var embedUrl = getEmbedUrl(addVideoUrl.val());
                        if (embedUrl) {
                            addVideoIframe.attr('src', embedUrl);
                            addVideoPreview.show();
                        }
                    } else {
                        addVideoPreview.hide();
                        addVideoIframe.attr('src', '');
                    }
                }

                addVideoUrl.on('input change', checkAddVideoUrl);
                addThumbnail.on('change', function() {
                    checkAddVideoUrl();
                    // Thumbnail preview
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            addThumbnailPreview.find('img').attr('src', e.target.result);
                            addThumbnailPreview.show();
                        };
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        addThumbnailPreview.hide();
                    }
                });

                // ==================== ADD ROOM GALLERY ====================
                var addGalleryInput = document.getElementById('add_gallery_images');
                var addGalleryPreview = document.getElementById('addGalleryPreview');
                var addGalleryCount = document.getElementById('addGalleryCount');
                var addGalleryCountText = document.getElementById('addGalleryCountText');
                var addGalleryDT = new DataTransfer();

                if (addGalleryInput) {
                    addGalleryInput.addEventListener('change', function() {
                        var files = this.files;
                        if (!files || files.length === 0) return;
                        var validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
                        
                        Array.from(files).forEach(function(file) {
                            if (!validTypes.includes(file.type)) {
                                alert(file.name + ': {{ __("Invalid file type") }}');
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                alert(file.name + ': {{ __("File too large (max 2MB)") }}');
                                return;
                            }
                            addGalleryDT.items.add(file);
                            
                            var reader = new FileReader();
                            var idx = addGalleryDT.files.length - 1;
                            reader.onload = function(e) {
                                var div = document.createElement('div');
                                div.className = 'gallery-preview-item';
                                div.dataset.idx = idx;
                                div.innerHTML = '<div class="gp-img-wrap"><img src="' + e.target.result + '" alt=""><div class="gp-overlay"><button type="button" class="gp-remove-btn" data-idx="' + idx + '"><i class="fas fa-times"></i></button></div></div><span class="gp-label new-label">{{ __("New") }}</span>';
                                addGalleryPreview.appendChild(div);
                                div.querySelector('.gp-remove-btn').addEventListener('click', function() {
                                    removeAddGalleryFile(parseInt(this.dataset.idx));
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                        
                        addGalleryInput.files = addGalleryDT.files;
                        updateAddGalleryCount();
                    });
                }

                function removeAddGalleryFile(idx) {
                    var newDT = new DataTransfer();
                    Array.from(addGalleryDT.files).forEach(function(f, i) {
                        if (i !== idx) newDT.items.add(f);
                    });
                    addGalleryDT = newDT;
                    addGalleryInput.files = addGalleryDT.files;
                    rebuildAddGalleryPreview();
                    updateAddGalleryCount();
                }

                function rebuildAddGalleryPreview() {
                    addGalleryPreview.innerHTML = '';
                    Array.from(addGalleryDT.files).forEach(function(file, i) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var div = document.createElement('div');
                            div.className = 'gallery-preview-item';
                            div.dataset.idx = i;
                            div.innerHTML = '<div class="gp-img-wrap"><img src="' + e.target.result + '" alt=""><div class="gp-overlay"><button type="button" class="gp-remove-btn" data-idx="' + i + '"><i class="fas fa-times"></i></button></div></div><span class="gp-label new-label">{{ __("New") }}</span>';
                            addGalleryPreview.appendChild(div);
                            div.querySelector('.gp-remove-btn').addEventListener('click', function() {
                                removeAddGalleryFile(parseInt(this.dataset.idx));
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function updateAddGalleryCount() {
                    var count = addGalleryDT.files.length;
                    if (count > 0) {
                        addGalleryCount.style.display = 'flex';
                        addGalleryCountText.textContent = count;
                    } else {
                        addGalleryCount.style.display = 'none';
                    }
                }

                $('#addGalleryClearBtn').on('click', function() {
                    addGalleryDT = new DataTransfer();
                    addGalleryInput.files = addGalleryDT.files;
                    addGalleryPreview.innerHTML = '';
                    updateAddGalleryCount();
                });

                // ==================== EDIT ROOM GALLERY ====================
                var editGalleryInput = document.getElementById('edit_gallery_images');
                var editGalleryPreview = document.getElementById('editGalleryPreview');
                var editGalleryCount = document.getElementById('editGalleryCount');
                var editGalleryCountText = document.getElementById('editGalleryCountText');
                var editExistingImages = document.getElementById('editExistingImages');
                var editRemoveContainer = document.getElementById('editRemoveImagesContainer');
                var editGalleryDT = new DataTransfer();

                if (editGalleryInput) {
                    editGalleryInput.addEventListener('change', function() {
                        var files = this.files;
                        if (!files || files.length === 0) return;
                        var validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
                        
                        Array.from(files).forEach(function(file) {
                            if (!validTypes.includes(file.type)) {
                                alert(file.name + ': {{ __("Invalid file type") }}');
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                alert(file.name + ': {{ __("File too large (max 2MB)") }}');
                                return;
                            }
                            editGalleryDT.items.add(file);
                            
                            var reader = new FileReader();
                            var idx = editGalleryDT.files.length - 1;
                            reader.onload = function(e) {
                                var div = document.createElement('div');
                                div.className = 'gallery-preview-item';
                                div.dataset.idx = idx;
                                div.innerHTML = '<div class="gp-img-wrap"><img src="' + e.target.result + '" alt=""><div class="gp-overlay"><button type="button" class="gp-remove-btn" data-idx="' + idx + '"><i class="fas fa-times"></i></button></div></div><span class="gp-label new-label">{{ __("New") }}</span>';
                                editGalleryPreview.appendChild(div);
                                div.querySelector('.gp-remove-btn').addEventListener('click', function() {
                                    removeEditGalleryFile(parseInt(this.dataset.idx));
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                        
                        editGalleryInput.files = editGalleryDT.files;
                        updateEditGalleryCount();
                    });
                }

                function removeEditGalleryFile(idx) {
                    var newDT = new DataTransfer();
                    Array.from(editGalleryDT.files).forEach(function(f, i) {
                        if (i !== idx) newDT.items.add(f);
                    });
                    editGalleryDT = newDT;
                    editGalleryInput.files = editGalleryDT.files;
                    rebuildEditGalleryPreview();
                    updateEditGalleryCount();
                }

                function rebuildEditGalleryPreview() {
                    editGalleryPreview.innerHTML = '';
                    Array.from(editGalleryDT.files).forEach(function(file, i) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var div = document.createElement('div');
                            div.className = 'gallery-preview-item';
                            div.dataset.idx = i;
                            div.innerHTML = '<div class="gp-img-wrap"><img src="' + e.target.result + '" alt=""><div class="gp-overlay"><button type="button" class="gp-remove-btn" data-idx="' + i + '"><i class="fas fa-times"></i></button></div></div><span class="gp-label new-label">{{ __("New") }}</span>';
                            editGalleryPreview.appendChild(div);
                            div.querySelector('.gp-remove-btn').addEventListener('click', function() {
                                removeEditGalleryFile(parseInt(this.dataset.idx));
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function updateEditGalleryCount() {
                    var count = editGalleryDT.files.length;
                    if (count > 0) {
                        editGalleryCount.style.display = 'flex';
                        editGalleryCountText.textContent = count;
                    } else {
                        editGalleryCount.style.display = 'none';
                    }
                }

                $('#editGalleryClearBtn').on('click', function() {
                    editGalleryDT = new DataTransfer();
                    editGalleryInput.files = editGalleryDT.files;
                    editGalleryPreview.innerHTML = '';
                    updateEditGalleryCount();
                });

                // Toggle remove existing image
                window.toggleRemoveExistingImage = function(mediaId, btn) {
                    var item = btn.closest('.gallery-preview-item');
                    var container = document.getElementById('editRemoveImagesContainer');
                    var existing = container.querySelector('input[value="' + mediaId + '"]');
                    
                    if (existing) {
                        existing.remove();
                        item.classList.remove('removing');
                        item.querySelector('.gp-label').className = 'gp-label existing-label';
                        item.querySelector('.gp-label').textContent = '{{ __("Existing") }}';
                    } else {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'remove_images[]';
                        input.value = mediaId;
                        container.appendChild(input);
                        item.classList.add('removing');
                        item.querySelector('.gp-label').className = 'gp-label remove-label';
                        item.querySelector('.gp-label').textContent = '{{ __("Remove") }}';
                    }
                };

                // Add Room Form Submit Validation
                $('#addRoomForm').on('submit', function(e) {
                    console.log('=== ADD ROOM FORM SUBMIT ===');
                    console.log('Form action:', $(this).attr('action'));
                    console.log('Room name value:', $('#add_room_name').val());
                    console.log('Form data:', $(this).serialize());
                    
                    var hasVideo = addVideoUrl.val().trim() !== '';
                    var hasNewThumbnail = addThumbnail[0].files && addThumbnail[0].files.length > 0;

                    if (hasVideo && !hasNewThumbnail) {
                        e.preventDefault();
                        alert('{{ __("Please upload a thumbnail image when adding a video") }}');
                        addThumbnail.focus();
                        return false;
                    }
                });

                // Reset Add Modal on close
                $('#addRoomModal').on('hidden.bs.modal', function() {
                    $(this).find('form')[0].reset();
                    addThumbnailRequired.hide();
                    addVideoWarning.hide();
                    addVideoPreview.hide();
                    addThumbnailPreview.hide();
                    // Reset gallery
                    addGalleryDT = new DataTransfer();
                    if (addGalleryInput) addGalleryInput.files = addGalleryDT.files;
                    addGalleryPreview.innerHTML = '';
                    updateAddGalleryCount();
                    // Reset features textarea
$('#add_features_textarea').val('');
                });

                // ==================== EDIT ROOM MODAL ====================
                var editVideoUrl = $('#edit_video_url');
                var editThumbnail = $('#edit_thumbnail');
                var editThumbnailRequired = $('.edit-thumbnail-required');
                var editVideoWarning = $('.edit-video-warning');
                var editVideoPreview = $('#edit_video_preview');
                var editVideoIframe = $('#edit_video_iframe');
                var editHasExistingThumbnail = false;

                function checkEditVideoUrl() {
                    var hasVideo = editVideoUrl.val().trim() !== '';
                    var hasNewThumbnail = editThumbnail[0].files && editThumbnail[0].files.length > 0;

                    if (hasVideo && !editHasExistingThumbnail && !hasNewThumbnail) {
                        editThumbnailRequired.show();
                        editVideoWarning.show();
                        editThumbnail.attr('required', 'required');
                    } else {
                        editThumbnailRequired.hide();
                        editVideoWarning.hide();
                        editThumbnail.removeAttr('required');
                    }

                    // Video preview
                    if (hasVideo) {
                        var embedUrl = getEmbedUrl(editVideoUrl.val());
                        if (embedUrl) {
                            editVideoIframe.attr('src', embedUrl);
                            editVideoPreview.show();
                        }
                    } else {
                        editVideoPreview.hide();
                        editVideoIframe.attr('src', '');
                    }
                }

                editVideoUrl.on('input change', checkEditVideoUrl);
                editThumbnail.on('change', checkEditVideoUrl);

                // Edit Room Button Click
                $('.edit-room-btn').on('click', function() {
                    var roomId = $(this).data('room-id');
                    var roomName = $(this).data('room-name');
                    var roomDescription = $(this).data('room-description');
                    var roomPrice = $(this).data('room-price');
                    var roomDiscount = $(this).data('room-discount');
                    var roomMaxGuests = $(this).data('room-max-guests');
                    var roomSize = $(this).data('room-size');
                    var roomBedType = $(this).data('room-bed-type');
                    var roomQuantity = $(this).data('room-quantity');
                    var roomStatus = $(this).data('room-status');
                    var roomVideoUrl = $(this).data('room-video-url');
                    var roomFeatures = $(this).data('room-features');
                    var roomImages = $(this).data('room-images'); // gallery images JSON

                    // Check if room has existing thumbnail (check from table row image)
                    var $row = $(this).closest('tr');
                    editHasExistingThumbnail = $row.find('.room-thumbnail').length > 0;

                    // Set form action URL
                    $('#editRoomForm').attr('action', '{{ url("admin/tourbooking/services/rooms") }}/' + roomId);

                    // Populate form fields
                    $('#edit_room_name').val(roomName);
                    $('#edit_room_description').val(roomDescription);
                    $('#edit_room_price').val(roomPrice);
                    $('#edit_room_discount').val(roomDiscount);
                    $('#edit_room_max_guests').val(roomMaxGuests);
                    $('#edit_room_size').val(roomSize);
                    $('#edit_room_bed_type').val(roomBedType);
                    $('#edit_room_quantity').val(roomQuantity);
                    $('#edit_room_status').val(roomStatus ? '1' : '0');
                    $('#edit_video_url').val(roomVideoUrl || '');

                    // Populate existing gallery images
                    editExistingImages.innerHTML = '';
                    editRemoveContainer.innerHTML = '';
                    editGalleryDT = new DataTransfer();
                    if (editGalleryInput) editGalleryInput.files = editGalleryDT.files;
                    editGalleryPreview.innerHTML = '';
                    updateEditGalleryCount();
                    
                    if (roomImages && Array.isArray(roomImages)) {
                        roomImages.forEach(function(img) {
                            var div = document.createElement('div');
                            div.className = 'gallery-preview-item';
                            div.dataset.mediaId = img.id;
                            div.innerHTML = '<div class="gp-img-wrap"><img src="' + img.url + '" alt=""><div class="gp-overlay"><button type="button" class="gp-remove-btn" onclick="toggleRemoveExistingImage(' + img.id + ', this)"><i class="fas fa-times"></i></button></div></div><span class="gp-label existing-label">{{ __("Existing") }}</span>';
                            editExistingImages.appendChild(div);
                        });
                    }

                    // Populate form fields
                    $('#edit_room_name').val(roomName);
                    $('#edit_room_description').val(roomDescription);
                    $('#edit_room_price').val(roomPrice);
                    $('#edit_room_discount').val(roomDiscount);
                    $('#edit_room_max_guests').val(roomMaxGuests);
                    $('#edit_room_size').val(roomSize);
                    $('#edit_room_bed_type').val(roomBedType);
                    $('#edit_room_quantity').val(roomQuantity);
                    $('#edit_room_status').val(roomStatus ? '1' : '0');
                    $('#edit_video_url').val(roomVideoUrl || '');

                  // Populate features as comma-separated string in textarea
var featuresText = '';
if (roomFeatures) {
    featuresText = roomFeatures.toString().split('\n').filter(function(f) { return f.trim() !== ''; }).map(function(f) { return f.trim(); }).join(', ');
}
$('#edit_features_textarea').val(featuresText);

                    // Populate base_price_for + room extras list
                    $('#edit_room_base_price_for').val($(this).data('room-base-price-for') || 2);

                    // Populate low stock badge fields
                    $('#edit_room_low_stock_show').val($(this).data('room-low-stock-show') ? '1' : '0');
                    $('#edit_room_low_stock_text').val($(this).data('room-low-stock-text') || '');

                    var roomExtras = $(this).data('room-extras') || [];
                    var extrasList = document.getElementById('extras-list-edit');
                    if (extrasList) {
                        extrasList.querySelectorAll('.extra-row, .empty-extras-msg').forEach(function (n) { n.remove(); });
                        var tpl = document.getElementById('extra-row-template-edit');
                        if (tpl && roomExtras.length) {
                            roomExtras.forEach(function (ex, i) {
                                var clone = tpl.content.firstElementChild.cloneNode(true);
                                clone.querySelector('[data-name="name"]').name = 'extras[' + i + '][name]';
                                clone.querySelector('[data-name="name"]').value = ex.name || '';
                                clone.querySelector('[data-name="type"]').name = 'extras[' + i + '][type]';
                                clone.querySelector('[data-name="type"]').value = ex.type || 'optional';
                                clone.querySelector('[data-name="price"]').name = 'extras[' + i + '][price]';
                                clone.querySelector('[data-name="price"]').value = ex.price || '';
                                var removeBtn = clone.querySelector('.remove-extra-row');
                                if (removeBtn) removeBtn.addEventListener('click', function () {
                                    clone.remove();
                                });
                                extrasList.appendChild(clone);
                            });
                        }
                        if (!extrasList.querySelector('.extra-row')) {
                            var d = document.createElement('div');
                            d.className = 'text-muted small empty-extras-msg';
                            d.textContent = '{{ __('translate.No extras yet. Click \'Add Extra\'.') }}';
                            extrasList.appendChild(d);
                        }
                    }

                    // Populate season prices for this room
                    var seasonPrices = $(this).data('room-season-prices') || {};
                    $('#editRoomSeasonPricesWrap select.season-mode').each(function() {
                        var sel = $(this);
                        var nameMatch = sel.attr('name').match(/season_prices\[(\d+)\]/);
                        if (!nameMatch) return;
                        var seasonId = nameMatch[1];
                        var rule = seasonPrices[seasonId] || { mode: 'default', custom_percentage: '', custom_price: '' };
                        sel.val(rule.mode || 'default');
                        var row = sel.closest('tr');
                        row.attr('data-mode', rule.mode || 'default');
                        row.find('input.sp-percentage').val(rule.custom_percentage || '');
                        row.find('input.sp-fixed').val(rule.custom_price || '');
                    });

                    // Check video validation
                    checkEditVideoUrl();
                });

                // Edit Room Form Submit Validation
                $('#editRoomForm').on('submit', function(e) {
                    var hasVideo = editVideoUrl.val().trim() !== '';
                    var hasNewThumbnail = editThumbnail[0].files && editThumbnail[0].files.length > 0;

                    if (hasVideo && !editHasExistingThumbnail && !hasNewThumbnail) {
                        e.preventDefault();
                        alert('{{ __("Please upload a thumbnail image when adding a video") }}');
                        editThumbnail.focus();
                        return false;
                    }
                });

                // Reset Edit Modal on close
                $('#editRoomModal').on('hidden.bs.modal', function() {
                    editThumbnailRequired.hide();
                    editVideoWarning.hide();
                    editVideoPreview.hide();
                    editHasExistingThumbnail = false;
                    // Reset gallery
                    editExistingImages.innerHTML = '';
                    editRemoveContainer.innerHTML = '';
                    editGalleryDT = new DataTransfer();
                    if (editGalleryInput) editGalleryInput.files = editGalleryDT.files;
                    editGalleryPreview.innerHTML = '';
                    updateEditGalleryCount();
                    // Reset season prices
                    $('#editRoomSeasonPricesWrap select.season-mode').each(function() {
                        $(this).val('default');
                        var row = $(this).closest('tr');
                        row.attr('data-mode', 'default');
                        row.find('input.sp-percentage, input.sp-fixed').val('');
                    });
                    // Reset extras list
                    var extrasList = document.getElementById('extras-list-edit');
                    if (extrasList) {
                        extrasList.querySelectorAll('.extra-row').forEach(function (n) { n.remove(); });
                        if (!extrasList.querySelector('.empty-extras-msg')) {
                            var d = document.createElement('div');
                            d.className = 'text-muted small empty-extras-msg';
                            d.textContent = '{{ __('translate.No extras yet. Click \'Add Extra\'.') }}';
                            extrasList.appendChild(d);
                        }
                    }
                });

                // Delete Room Button Click
                $('.delete-room-btn').on('click', function() {
                    var roomId = $(this).data('room-id');
                    $('#deleteRoomForm').attr('action', '{{ url("admin/tourbooking/services/rooms") }}/' + roomId);
                });

            });
        })(jQuery);
    </script>
@endpush