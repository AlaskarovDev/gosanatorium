@extends('admin.master_layout')
@section('title')
    <title>Акции</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Акции</h3>
    <p class="crancy-header__text">Manage Content >> Акции</p>
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
                                            <h4 class="crancy-product-card__title">Акции</h4>
                                            <a href="{{ route('admin.akcii.create') }}" class="crancy-btn"><i class="fa fa-plus"></i> Add new</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    @if ($akcii->count() > 0)
                                        <table class="crancy-table__main crancy-table__main-v3 no-footer">
                                            <thead class="crancy-table__head">
                                                <tr>
                                                    <th class="crancy-table__column-1 crancy-table__h1">Title</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Sanatorium</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Discount</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Valid until</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Order</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Active</th>
                                                    <th class="crancy-table__column-3 crancy-table__h3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="crancy-table__body">
                                                @foreach ($akcii as $item)
                                                    <tr>
                                                        <td class="crancy-table__column-1 crancy-table__data-1">
                                                            <h4 class="crancy-table__product-title">{{ $item->title }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->sanatorium_name ?: '—' }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->discount_percent !== null ? $item->discount_percent . '%' : '—' }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->valid_until ? $item->valid_until->format('d.m.Y') : '—' }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->sort_order }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->is_active ? 'Yes' : 'No' }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-3 crancy-table__data-3">
                                                            <a href="{{ route('admin.akcii.edit', $item->id) }}" class="crancy-btn crancy-btn__success"><i class="fas fa-edit"></i> Edit</a>
                                                            <form action="{{ route('admin.akcii.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="crancy-btn crancy-btn__danger"><i class="fas fa-trash"></i> Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="mg-top-30">{{ $akcii->links() }}</div>
                                    @else
                                        <div class="alert alert-info text-center">
                                            No promotions yet. <a href="{{ route('admin.akcii.create') }}">Add new</a>.
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
