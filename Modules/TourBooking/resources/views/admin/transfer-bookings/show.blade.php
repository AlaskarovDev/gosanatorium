@extends('admin.master_layout')

@section('title')
    <title>Бронирование {{ $transferBooking->booking_code }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Детали бронирования</h3>
    <p class="crancy-header__text">{{ $transferBooking->booking_code }}</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-lg-8">
                    {{-- Информация о клиенте --}}
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Информация о клиенте</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Имя:</strong><br>{{ $transferBooking->guest_name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Email:</strong><br><a href="mailto:{{ $transferBooking->guest_email }}">{{ $transferBooking->guest_email }}</a></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Телефон:</strong><br><a href="tel:{{ $transferBooking->guest_phone }}">{{ $transferBooking->guest_phone }}</a></p>
                                </div>
                            </div>
                            @if($transferBooking->user)
                                <hr>
                                <p class="mb-0"><small class="text-muted">Зарегистрированный пользователь: ID #{{ $transferBooking->user->id }}</small></p>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Детали трансфера --}}
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-route"></i> Детали трансфера</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Маршрут:</strong></p>
                                    @if($transferBooking->route)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success rounded-circle" style="width: 12px; height: 12px;"></div>
                                            <span class="ms-2">{{ $transferBooking->route->from_location }}</span>
                                        </div>
                                        <div class="border-start ms-1 ps-3" style="border-width: 2px !important; height: 30px;"></div>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger rounded-circle" style="width: 12px; height: 12px;"></div>
                                            <span class="ms-2">{{ $transferBooking->route->to_location }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted">Маршрут не указан</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Дата:</strong><br>{{ $transferBooking->transfer_date->format('d.m.Y') }}</p>
                                    @if($transferBooking->transfer_time)
                                        <p><strong>Время:</strong><br>{{ $transferBooking->transfer_time }}</p>
                                    @endif
                                    <p><strong>Пассажиры:</strong><br>{{ $transferBooking->passengers }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    @if($transferBooking->pickup_address)
                                        <p><strong>Адрес подачи:</strong><br>{{ $transferBooking->pickup_address }}</p>
                                    @endif
                                    @if($transferBooking->dropoff_address)
                                        <p><strong>Адрес высадки:</strong><br>{{ $transferBooking->dropoff_address }}</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($transferBooking->flight_number)
                                        <p><strong>Номер рейса:</strong><br>{{ $transferBooking->flight_number }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($transferBooking->special_requests)
                                <hr>
                                <p><strong>Особые пожелания:</strong><br>{{ $transferBooking->special_requests }}</p>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Транспорт --}}
                    @if($transferBooking->vehicle)
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-car"></i> Транспорт</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if($transferBooking->vehicle->image)
                                            <img src="{{ asset($transferBooking->vehicle->image) }}" alt="{{ $transferBooking->vehicle->name }}" class="img-fluid rounded">
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <h4>{{ $transferBooking->vehicle->name }}</h4>
                                        <p>
                                            <span class="badge bg-info"><i class="fas fa-users"></i> {{ $transferBooking->vehicle->passenger_capacity }} пасс.</span>
                                            <span class="badge bg-secondary"><i class="fas fa-suitcase"></i> {{ $transferBooking->vehicle->luggage_capacity }} багаж</span>
                                        </p>
                                        @if($transferBooking->vehicle->features)
                                            <p>
                                                @foreach($transferBooking->vehicle->features as $feature)
                                                    <span class="badge bg-light text-dark me-1">{{ $feature }}</span>
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="col-lg-4">
                    {{-- Статус и цена --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Статус и оплата</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <span class="badge bg-{{ $transferBooking->status_badge }} fs-5 px-4 py-2">
                                    {{ $transferBooking->status_label }}
                                </span>
                            </div>
                            
                            <table class="table table-sm">
                                <tr>
                                    <td>Базовая цена:</td>
                                    <td class="text-end">{{ number_format($transferBooking->base_price, 2) }} ₼</td>
                                </tr>
                                <tr>
                                    <td>Транспорт:</td>
                                    <td class="text-end">{{ number_format($transferBooking->vehicle_price, 2) }} ₼</td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Итого:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($transferBooking->total_price, 2) }} ₼</strong></td>
                                </tr>
                            </table>
                            
                            <hr>
                            
                            <form action="{{ route('admin.tourbooking.transfer-bookings.update-status', $transferBooking) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label class="form-label">Изменить статус</label>
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $transferBooking->status == 'pending' ? 'selected' : '' }}>В ожидании</option>
                                        <option value="confirmed" {{ $transferBooking->status == 'confirmed' ? 'selected' : '' }}>Подтверждён</option>
                                        <option value="cancelled" {{ $transferBooking->status == 'cancelled' ? 'selected' : '' }}>Отменён</option>
                                        <option value="completed" {{ $transferBooking->status == 'completed' ? 'selected' : '' }}>Завершён</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Заметки админа</label>
                                    <textarea name="admin_notes" class="form-control" rows="3">{{ $transferBooking->admin_notes }}</textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save"></i> Сохранить
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Информация --}}
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Информация</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Код бронирования:</strong><br>{{ $transferBooking->booking_code }}</p>
                            <p><strong>Создано:</strong><br>{{ $transferBooking->created_at->format('d.m.Y H:i') }}</p>
                            <p><strong>Обновлено:</strong><br>{{ $transferBooking->updated_at->format('d.m.Y H:i') }}</p>
                            
                            <hr>
                            
                            <a href="{{ route('admin.tourbooking.transfer-bookings.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left"></i> Назад к списку
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
