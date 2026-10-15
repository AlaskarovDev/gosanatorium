@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Theme language') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Theme language') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Dashboard') }} >> {{ __('translate.Theme language') }}</p>
@endsection

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
                                                    <h4 class="crancy-product-card__title">{{ __('translate.Switch to language translation') }}</h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="translation_box">
                                            <ul >
                                                @foreach ($language_list as $language)
                                                <li><a href="{{ route('admin.theme-language', ['lang_code' => $language->lang_code] ) }}">
                                                    @if (request()->get('lang_code') == $language->lang_code)
                                                        <i class="fas fa-eye"></i>
                                                    @else
                                                        <i class="fas fa-edit"></i>
                                                    @endif

                                                    {{ $language->lang_name }}</a></li>
                                                @endforeach
                                            </ul>

                                            <div class="alert alert-secondary" role="alert">

                                                @php
                                                    $edited_language = $language_list->where('lang_code', request()->get('lang_code'))->first()
                                                        ?? $language_list->where('is_default', 'yes')->first()
                                                        ?? $language_list->first();
                                                @endphp

                                              <p>{{ __('translate.Your editing mode') }} : <b>{{ $edited_language?->lang_name }}</b></p>
                                            </div>
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


    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            @php
                                $searchTerm = $searchTerm ?? null;
                                $perPage = 100;
                                $currentPage = request('page', 1);
                                $dataArray = collect($data);
                                
                                // Əgər axtarış varsa, filteredData istifadə et, pagination yoxdur
                                if ($searchTerm && isset($filteredData)) {
                                    $displayData = collect($filteredData);
                                    $isSearchMode = true;
                                    $lastPage = 1;
                                } else {
                                    $displayData = $dataArray->forPage($currentPage, $perPage);
                                    $isSearchMode = false;
                                    $lastPage = ceil($dataArray->count() / $perPage);
                                }
                            @endphp
                            
                            <div class="row mb-5">
                                <div class="col-12 mg-top-30">
                                    <div class="crancy-product-card translation_main_box">
                                        <div class="mb-4">
                                            @if ($isSearchMode)
                                                <div class="alert alert-success">
                                                    <p><strong>"{{ $searchTerm }}"</strong> üçün <strong>{{ $displayData->count() }}</strong> nəticə tapıldı (bütün səhifələrdə axtarış)</p>
                                                </div>
                                            @else
                                                <div class="alert alert-info">
                                                    <p>Showing translations {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, $dataArray->count()) }} of {{ $dataArray->count() }}</p>
                                                    <p><strong>Note:</strong> You must save each page separately before moving to another page.</p>
                                                </div>
                                            @endif
                                            
                                            @if (!$isSearchMode)
                                            <div class="pagination mb-3">
                                                @for ($i = 1; $i <= $lastPage; $i++)
                                                    <a href="{{ route('admin.theme-language', ['lang_code' => request('lang_code'), 'page' => $i]) }}" 
                                                    class="btn {{ $currentPage == $i ? 'btn-primary' : 'btn-outline-primary' }} me-1">
                                                        {{ $i }}
                                                    </a>
                                                @endfor
                                            </div>
                                            @endif
                                            
                                            <!-- Global search across all pages -->
                                            <form action="{{ route('admin.theme-language') }}" method="GET" class="d-flex gap-2 mb-3">
                                                <input type="hidden" name="lang_code" value="{{ request('lang_code') }}">
                                                <input type="text" name="search" id="translationSearch" class="form-control" 
                                                    placeholder="Search in all translations..." 
                                                    value="{{ $searchTerm }}">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                                @if ($isSearchMode)
                                                    <a href="{{ route('admin.theme-language', ['lang_code' => request('lang_code')]) }}" 
                                                       class="btn btn-secondary">Clear</a>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('admin.update-theme-language') }}" method="POST">
                                @csrf
                                
                                <input type="hidden" name="lang_code" value="{{ request()->get('lang_code') }}">
                                <input type="hidden" name="page" value="{{ $currentPage }}">

                                <div class="row">
                                    <div class="col-12">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Theme language') }}</h4>
                                            </div>

                                            <div class="row mg-top-30">
                                                @foreach ($displayData as $index => $value)
                                                    <div class="col-12 translation-item">
                                                        <div class="crancy__item-form--group mg-top-form-20">
                                                            <label class="crancy__item-label">{{ $index }} </label>
                                                            <input class="crancy__item-input" type="text" name="values[{{ $index }}]"  value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>

                                        </div>
                                        <!-- End Product Card -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->
@endsection

@push('js_section')
<script>
    // Enter basanda search formu göndər
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('translationSearch');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.closest('form').submit();
                }
            });
            // Fokus ver search input-a əgər axtarış nəticəsi varsa
            @if($searchTerm)
                searchInput.focus();
            @endif
        }
    });
</script>
@endpush