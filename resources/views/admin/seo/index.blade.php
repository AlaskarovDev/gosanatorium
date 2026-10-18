@extends('admin.master_layout')
@section('title')
    <title>SEO</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">SEO</h3>
    <p class="crancy-header__text">Manage Content >> SEO</p>
@endsection
@section('body-content')

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-table crancy-table--v3 mg-top-30">
                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">SEO</h4>
                                            <a href="{{ route('admin.seo.create') }}" class="crancy-btn"><i class="fa fa-plus"></i> Add new page</a>
                                        </div>
                                    </div>
                                </div>

                                <div id="crancy-table__main_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    @if ($seoItems->count() > 0)
                                        <table class="crancy-table__main crancy-table__main-v3 dataTable no-footer" id="dataTable">
                                            <thead class="crancy-table__head">
                                                <tr>
                                                    <th class="crancy-table__column-1 crancy-table__h1 sorting">Page Key</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2 sorting">SEO Title</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2 sorting">Description</th>
                                                    <th class="crancy-table__column-3 crancy-table__h3 sorting">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="crancy-table__body">
                                                @foreach ($seoItems as $item)
                                                    <tr>
                                                        <td class="crancy-table__column-1 crancy-table__data-1">
                                                            <h4 class="crancy-table__product-title">{{ $item->page_key }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->seo_title }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ \Illuminate\Support\Str::limit($item->seo_description, 100) }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-3 crancy-table__data-3">
                                                            <a href="{{ route('admin.seo.edit', $item->id) }}" class="crancy-btn crancy-btn__success"><i class="fas fa-edit"></i> Edit</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-info text-center">
                                            No SEO pages yet. <a href="{{ route('admin.seo.create') }}">Add new page</a>.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
