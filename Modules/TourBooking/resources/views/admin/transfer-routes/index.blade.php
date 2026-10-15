@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Transfer Routes') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Transfer Routes') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage transfer routes') }}</p>
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
                                    <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                        <h4 class="crancy-product-card__title">{{ __('translate.Transfer Routes List') }}</h4>
                                        <a href="{{ route('admin.tourbooking.transfer-routes.create') }}"
                                            class="crancy-btn crancy-btn__primary">
                                            <i class="fas fa-plus"></i> {{ __('translate.Add New Route') }}
                                        </a>
                                    </div>
                                </div>

                                <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer">
                                    <table class="crancy-table__main crancy-table__main-v3 no-footer" id="dataTable">
                                        <thead class="crancy-table__head">
                                            <tr>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">#</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.From') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.To') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Price') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Duration') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Status') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Order') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Action') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="crancy-table__body">
                                            @forelse ($routes as $key => $route)
                                                <tr class="odd">
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ $routes->firstItem() + $key }}
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <strong>{{ $route->from_location }}</strong>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <strong>{{ $route->to_location }}</strong>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ $route->base_price ? currency($route->base_price) : '-' }}
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ $route->duration_minutes ? $route->duration_minutes . ' ' . __('translate.min') : '-' }}
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <form action="{{ route('admin.tourbooking.transfer-routes.update-status', $route) }}" 
                                                              method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm {{ $route->status ? 'btn-success' : 'btn-secondary' }}">
                                                                {{ $route->status ? __('translate.Active') : __('translate.Inactive') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ $route->display_order }}
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <a href="{{ route('admin.tourbooking.transfer-routes.edit', $route) }}"
                                                            class="crancy-action__btn crancy-action__edit crancy-btn">
                                                            <i class="fas fa-edit"></i> {{ __('translate.Edit') }}
                                                        </a>
                                                        <a onclick="itemDeleteConfirmation({{ $route->id }})"
                                                            href="javascript:;" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            class="crancy-btn crancy-action__btn crancy-action__edit delete_danger_btn">
                                                            <i class="fas fa-trash"></i> {{ __('translate.Delete') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        {{ __('translate.No transfer routes found') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    
                                    <div class="crancy-table__pagination">
                                        {{ $routes->links() }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you sure you want to delete this route?') }}</p>
                </div>
                <div class="modal-footer">
                    <form id="item_delete_form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('translate.Close') }}
                        </button>
                        <button type="submit" class="btn btn-danger">
                            {{ __('translate.Yes, Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
    <script>
        "use strict";
        
        function itemDeleteConfirmation(id) {
            $("#item_delete_form").attr("action", '{{ url("admin/tourbooking/transfer-routes") }}/' + id);
        }
    </script>
@endpush