@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Diseases') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Diseases') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Diseases') }}</p>
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
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Diseases') }}</h4>
                                                <a href="{{ route('admin.tourbooking.diseases.create') }}" class="crancy-btn "><i class="fa fa-plus"></i> {{ __('translate.Add New Disease') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="crancy-table__main_wrapper" class=" dt-bootstrap5 no-footer">
                                    <table class="crancy-table__main crancy-table__main-v3  no-footer" id="dataTable">
                                        <thead class="crancy-table__head">
                                            <tr>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">{{ __('translate.Image') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">{{ __('translate.Name') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">{{ __('translate.Sanatoriums') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">{{ __('translate.Sort Order') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">{{ __('translate.Status') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">{{ __('translate.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="crancy-table__body">
                                            @foreach ($diseases as $disease)
                                                <tr class="odd">
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <img src="{{ $disease->image_url }}" alt="{{ $disease->name_ru }}" width="60">
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">{{ $disease->name_ru }}</td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">{{ $disease->sanatoriums_count }}</td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">{{ $disease->sort_order }}</td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        @if ($disease->is_active)
                                                            <span class="crancy-badge crancy-badge-success">{{ __('translate.Active') }}</span>
                                                        @else
                                                            <span class="crancy-badge crancy-badge-danger">{{ __('translate.Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <a href="{{ route('admin.tourbooking.diseases.edit', $disease->id) }}" class="crancy-action__btn crancy-action__edit crancy-btn"><i class="fa fa-edit"></i> {{ __('translate.Edit') }}</a>
                                                        <a onclick="itemDeleteConfrimation({{ $disease->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="crancy-btn crancy-action__btn crancy-action__edit delete_danger_btn"><i class="fas fa-trash"></i> {{ __('translate.Delete') }}</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $diseases->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
    <script>
        "use strict"
        function itemDeleteConfrimation(id) {
            $("#item_delect_confirmation").attr("action", '{{ url('admin/tourbooking/diseases') }}' + "/" + id)
        }
    </script>
@endpush
