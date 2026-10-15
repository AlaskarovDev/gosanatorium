@extends('admin.master_layout')

@section('title')
    <title>Транспорт для трансфера</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Транспорт для трансфера</h3>
    <p class="crancy-header__text">Управление транспортными средствами</p>
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
                                        <h4 class="crancy-product-card__title">Список транспорта</h4>
                                        <a href="{{ route('admin.tourbooking.transfer-vehicles.create') }}"
                                            class="crancy-btn crancy-btn__primary">
                                            <i class="fas fa-plus"></i> Добавить транспорт
                                        </a>
                                    </div>
                                </div>

                                <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer">
                                    <table class="crancy-table__main crancy-table__main-v3 no-footer">
                                        <thead class="crancy-table__head">
                                            <tr>
                                                <th>#</th>
                                                <th>Изображение</th>
                                                <th>Название</th>
                                                <th>Вместимость</th>
                                                <th>Цена</th>
                                                <th>Статус</th>
                                                <th>Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody class="crancy-table__body">
                                            @forelse ($vehicles as $key => $vehicle)
                                                <tr>
                                                    <td>{{ $vehicles->firstItem() + $key }}</td>
                                                    <td>
                                                        @if($vehicle->image)
                                                            <img src="{{ asset($vehicle->image) }}" 
                                                                 alt="{{ $vehicle->name }}" 
                                                                 style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                        @else
                                                            <div style="width: 80px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fas fa-car text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td><strong>{{ $vehicle->name }}</strong></td>
                                                    <td>
                                                        <span class="badge bg-info"><i class="fas fa-users"></i> {{ $vehicle->passenger_capacity }}</span>
                                                        <span class="badge bg-secondary"><i class="fas fa-suitcase"></i> {{ $vehicle->luggage_capacity }}</span>
                                                    </td>
                                                    <td><strong>{{ number_format($vehicle->base_price, 2) }} ₼</strong></td>
                                                    <td>
                                                        <form action="{{ route('admin.tourbooking.transfer-vehicles.update-status', $vehicle) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm {{ $vehicle->status ? 'btn-success' : 'btn-secondary' }}">
                                                                {{ $vehicle->status ? 'Активен' : 'Неактивен' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.tourbooking.transfer-vehicles.edit', $vehicle) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a onclick="itemDeleteConfirmation({{ $vehicle->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <i class="fas fa-car fa-3x text-muted mb-3"></i>
                                                        <p>Транспорт не найден</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="mt-4">{{ $vehicles->links() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить этот транспорт?</p>
                </div>
                <div class="modal-footer">
                    <form id="item_delete_form" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
<script>
    function itemDeleteConfirmation(id) {
        $("#item_delete_form").attr("action", '{{ url("admin/tourbooking/transfer-vehicles") }}/' + id);
    }
</script>
@endpush
