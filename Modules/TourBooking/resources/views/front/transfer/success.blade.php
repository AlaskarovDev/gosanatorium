@extends('layout_inner_page')

@section('title')
    <title>Бронирование подтверждено - {{ $booking->booking_code }}</title>
@endsection

@section('front-content')
    <!-- Success Content -->
    <section class="tg-success-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Success Message -->
                    <div class="tg-success-card text-center mb-4">
                        <div class="tg-success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2>Ваше бронирование принято!</h2>
                        <p class="text-muted mb-4">
                            Спасибо за ваш заказ. Мы отправим подтверждение на вашу электронную почту 
                            после обработки заявки.
                        </p>
                        <div class="tg-booking-code">
                            <span>Код бронирования:</span>
                            <strong>{{ $booking->booking_code }}</strong>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="tg-details-card mb-4">
                        <div class="tg-card-header">
                            <h4><i class="fas fa-info-circle"></i> Детали бронирования</h4>
                        </div>
                        <div class="tg-card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="tg-detail-item">
                                        <label>Статус:</label>
                                        <p>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> В ожидании подтверждения
                                            </span>
                                        </p>
                                    </div>
                                    <div class="tg-detail-item">
                                        <label>Маршрут:</label>
                                        <p>
                                            @if($booking->route)
                                                <strong>{{ $booking->route->from_location }}</strong>
                                                <i class="fas fa-arrow-right mx-2"></i>
                                                <strong>{{ $booking->route->to_location }}</strong>
                                            @else
                                                <span class="text-muted">Не указан</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="tg-detail-item">
                                        <label>Дата трансфера:</label>
                                        <p><strong>{{ $booking->transfer_date->format('d.m.Y') }}</strong>
                                            @if($booking->transfer_time)
                                                в {{ $booking->transfer_time }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="tg-detail-item">
                                        <label>Пассажиры:</label>
                                        <p><strong>{{ $booking->passengers }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="tg-detail-item">
                                        <label>Транспорт:</label>
                                        <p>
                                            @if($booking->vehicle)
                                                <strong>{{ $booking->vehicle->name }}</strong>
                                            @else
                                                <span class="text-muted">Не указан</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($booking->pickup_address)
                                        <div class="tg-detail-item">
                                            <label>Адрес подачи:</label>
                                            <p>{{ $booking->pickup_address }}</p>
                                        </div>
                                    @endif
                                    @if($booking->dropoff_address)
                                        <div class="tg-detail-item">
                                            <label>Адрес высадки:</label>
                                            <p>{{ $booking->dropoff_address }}</p>
                                        </div>
                                    @endif
                                    @if($booking->flight_number)
                                        <div class="tg-detail-item">
                                            <label>Номер рейса:</label>
                                            <p>{{ $booking->flight_number }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($booking->special_requests)
                                <hr>
                                <div class="tg-detail-item">
                                    <label>Особые пожелания:</label>
                                    <p>{{ $booking->special_requests }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="tg-details-card mb-4">
                        <div class="tg-card-header">
                            <h4><i class="fas fa-user"></i> Контактная информация</h4>
                        </div>
                        <div class="tg-card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="tg-detail-item">
                                        <label>Имя:</label>
                                        <p><strong>{{ $booking->guest_name }}</strong></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="tg-detail-item">
                                        <label>Email:</label>
                                        <p>{{ $booking->guest_email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="tg-detail-item">
                                        <label>Телефон:</label>
                                        <p>{{ $booking->guest_phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="tg-details-card mb-4">
                        <div class="tg-card-header">
                            <h4><i class="fas fa-receipt"></i> Итого</h4>
                        </div>
                        <div class="tg-card-body">
                            <div class="tg-price-row">
                                <span>Маршрут:</span>
                                <span>{{ currency($booking->base_price) }}</span>
                            </div>
                            <div class="tg-price-row">
                                <span>Транспорт:</span>
                                <span>{{ currency($booking->vehicle_price) }}</span>
                            </div>
                            <hr>
                            <div class="tg-price-row tg-price-total">
                                <span>Итого к оплате:</span>
                                <span>{{ currency($booking->total_price) }}</span>
                            </div>
                            <p class="text-muted text-center mt-3 mb-0">
                                <i class="fas fa-info-circle"></i> Оплата производится при получении услуги
                            </p>
                        </div>
                    </div>

                    <!-- What's Next -->
                    <div class="tg-next-steps">
                        <h4><i class="fas fa-list-ol"></i> Что дальше?</h4>
                        <div class="tg-steps">
                            <div class="tg-step">
                                <div class="tg-step-number">1</div>
                                <div class="tg-step-content">
                                    <h5>Обработка заявки</h5>
                                    <p>Наш менеджер проверит детали вашего бронирования</p>
                                </div>
                            </div>
                            <div class="tg-step">
                                <div class="tg-step-number">2</div>
                                <div class="tg-step-content">
                                    <h5>Подтверждение</h5>
                                    <p>Вы получите email с подтверждением бронирования</p>
                                </div>
                            </div>
                            <div class="tg-step">
                                <div class="tg-step-number">3</div>
                                <div class="tg-step-content">
                                    <h5>Трансфер</h5>
                                    <p>Водитель встретит вас в указанное время и место</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="tg-actions text-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-home"></i> На главную
                        </a>
                        @auth
                            <a href="{{ route('user.bookings.index') }}" class="btn btn-outline-primary btn-lg ms-2">
                                <i class="fas fa-user"></i> Мои бронирования
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .tg-success-card {
            background: #fff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .tg-success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .tg-success-icon i {
            font-size: 50px;
            color: #fff;
        }
        .tg-success-card h2 {
            color: #28a745;
            margin-bottom: 12px;
        }
        .tg-booking-code {
            background: #f8f9fa;
            padding: 16px 24px;
            border-radius: 8px;
            display: inline-block;
        }
        .tg-booking-code span {
            display: block;
            font-size: 14px;
            color: #888;
        }
        .tg-booking-code strong {
            font-size: 24px;
            color: #008080;
            letter-spacing: 2px;
        }
        
        .tg-details-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .tg-card-header {
            background: #f8f9fa;
            padding: 16px 20px;
            border-bottom: 1px solid #eee;
        }
        .tg-card-header h4 {
            margin: 0;
            font-size: 18px;
        }
        .tg-card-body {
            padding: 20px;
        }
        
        .tg-detail-item {
            margin-bottom: 16px;
        }
        .tg-detail-item:last-child {
            margin-bottom: 0;
        }
        .tg-detail-item label {
            display: block;
            font-size: 13px;
            color: #888;
            margin-bottom: 4px;
        }
        .tg-detail-item p {
            margin: 0;
        }
        
        .tg-price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
        .tg-price-total {
            font-size: 20px;
            font-weight: 700;
            color: #008080;
        }
        
        .tg-next-steps {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .tg-next-steps h4 {
            margin-bottom: 20px;
        }
        .tg-steps {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .tg-step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        .tg-step-number {
            width: 36px;
            height: 36px;
            background: #008080;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }
        .tg-step-content h5 {
            margin: 0 0 4px;
            font-size: 16px;
        }
        .tg-step-content p {
            margin: 0;
            color: #888;
            font-size: 14px;
        }
    </style>
@endsection