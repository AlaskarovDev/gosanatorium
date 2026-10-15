@extends('admin.master_layout')

@section('title')
    <title>Бронирования трансфера</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Бронирования трансфера</h3>
    <p class="crancy-header__text">Управление заказами трансфера</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            {{-- Статистика --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5>Всего</h5>
                            <h2>{{ $stats['total'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h5>В ожидании</h5>
                            <h2>{{ $stats['pending'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5>Подтверждено</h5>
                            <h2>{{ $stats['confirmed'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5>Завершено</h5>
                            <h2>{{ $stats['completed'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-table crancy-table--v3">
                                
                                {{-- Фильтры --}}
                                <div class="crancy-customer-filter mb-4">
                                    <form action="" method="GET" class="row g-3">
                                        <div class="col-md-3">
                                            <input type="text" name="search" class="form-control" placeholder="Поиск по коду, имени, email..." value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="status" class="form-select">
                                                <option value="">Все статусы</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>В ожидании</option>
                                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждён</option>
                                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменён</option>
                                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершён</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="date_from" class="form-control" placeholder="Дата от" value="{{ request('date_from') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" name="date_to" class="form-control" placeholder="Дата до" value="{{ request('date_to') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Фильтр</button>
                                            <a href="{{ route('admin.tourbooking.transfer-bookings.index') }}" class="btn btn-outline-secondary">Сброс</a>
                                        </div>
                                    </form>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Код</th>
                                                <th>Клиент</th>
                                                <th>Маршрут</th>
                                                <th>Дата</th>
                                                <th>Транспорт</th>
                                                <th>Сумма</th>
                                                <th>Статус</th>
                                                <th>Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($bookings as $booking)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $booking->booking_code }}</strong>
                                                        <br><small class="text-muted">{{ $booking->created_at->format('d.m.Y H:i') }}</small>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $booking->guest_name }}</strong>
                                                        <br><small>{{ $booking->guest_email }}</small>
                                                        <br><small>{{ $booking->guest_phone }}</small>
                                                    </td>
                                                    <td>
                                                        @if($booking->route)
                                                            {{ $booking->route->from_location }}
                                                            <br>↓
                                                            <br>{{ $booking->route->to_location }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $booking->transfer_date->format('d.m.Y') }}</strong>
                                                        @if($booking->transfer_time)
                                                            <br><small>{{ $booking->transfer_time }}</small>
                                                        @endif
                                                        <br><small>{{ $booking->passengers }} пасс.</small>
                                                    </td>
                                                    <td>
                                                        @if($booking->vehicle)
                                                            {{ $booking->vehicle->name }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ number_format($booking->total_price, 2) }} ₼</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $booking->status_badge }}">
                                                            {{ $booking->status_label }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.tourbooking.transfer-bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a onclick="itemDeleteConfirmation({{ $booking->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                        <p>Бронирования не найдены</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4">{{ $bookings->links() }}</div>
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
                    <p>Вы уверены, что хотите удалить это бронирование?</p>
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
        $("#item_delete_form").attr("action", '{{ url("admin/tourbooking/transfer-bookings") }}/' + id);
    }
</script>
@endpush
