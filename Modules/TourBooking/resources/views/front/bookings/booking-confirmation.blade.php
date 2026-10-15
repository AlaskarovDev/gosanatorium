@extends('layout_inner_page')

@section('title')
    <title>Бронирование подтверждено</title>
@endsection

@push('style_section')
<style>
.check-circle {
    width: 100px;
    height: 100px;
    border: 4px solid #28a745;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.account-credentials code {
    font-size: 14px;
}

.confirmation-wrapper {
    border: 1px solid #e0e0e0;
}

.booking-details table tr td {
    padding: 8px 0;
}
</style>
@endpush

@section('front-content')
    <section class="booking-confirmation-area pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="confirmation-wrapper text-center bg-white p-5 rounded shadow">
                        
                        {{-- Иконка успеха --}}
                        <div class="success-icon mb-4">
                            <div class="check-circle mx-auto">
                                <i class="fa-solid fa-check fa-3x text-success"></i>
                            </div>
                        </div>

                        {{-- Главное сообщение --}}
                        <h2 class="mb-3 text-success">Ваш заказ принят!</h2>
                        
                        <div class="alert alert-info mx-auto" style="max-width: 500px;">
                            <i class="fa-solid fa-envelope me-2"></i>
                            <strong>Когда ваш заказ будет подтверждён, вам придёт уведомление на email.</strong>
                        </div>

                        {{-- Детали бронирования --}}
                        <div class="booking-details mt-4 p-4 bg-light rounded text-start mx-auto" style="max-width: 500px;">
                            <h5 class="mb-3 text-center">
                                <i class="fa-solid fa-receipt me-2"></i>Информация о заказе
                            </h5>
                            
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td><strong>Код заказа:</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-primary fs-6">{{ $booking->booking_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Отель:</strong></td>
                                    <td class="text-end">{{ $booking->service->title }}</td>
                                </tr>
                                @if($booking->room)
                                <tr>
                                    <td><strong>Номер:</strong></td>
                                    <td class="text-end">{{ $booking->room->name }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Дата заезда:</strong></td>
                                    <td class="text-end">{{ $booking->check_in_date->format('d.m.Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Дата выезда:</strong></td>
                                    <td class="text-end">{{ $booking->check_out_date->format('d.m.Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Количество ночей:</strong></td>
                                    <td class="text-end">{{ $booking->nights }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Итого:</strong></td>
                                    <td class="text-end"><strong class="text-primary">{{ currency($booking->total) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Статус:</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-warning">Ожидает подтверждения</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        {{-- Данные для входа нового пользователя --}}
                        @if(session('is_new_user') && session('user_credentials'))
                            <div class="account-credentials mt-4 p-4 border border-success rounded mx-auto" style="max-width: 500px;">
                                <h5 class="text-success mb-3">
                                    <i class="fa-solid fa-user-check me-2"></i>Данные для входа в личный кабинет:
                                </h5>
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td class="text-end">
                                            <code class="bg-light p-1 rounded">{{ session('user_credentials.email') }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Пароль:</strong></td>
                                        <td class="text-end">
                                            <code class="bg-light p-1 rounded">{{ session('user_credentials.password') }}</code>
                                        </td>
                                    </tr>
                                </table>
                                <div class="alert alert-warning mt-3 mb-0 small">
                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                    Сохраните эти данные! Пароль можно изменить позже в личном кабинете.
                                </div>
                            </div>
                        @elseif(!session('is_new_user'))
                            {{-- Для существующего пользователя --}}
                            <div class="account-info mt-4 p-3 border rounded mx-auto" style="max-width: 500px;">
                                <p class="mb-0">
                                    <i class="fa-solid fa-info-circle me-2 text-info"></i>
                                    Ваш аккаунт уже существует. Вы можете войти с вашим email и паролем.
                                </p>
                            </div>
                        @else
                            {{-- Информация об аккаунте по умолчанию --}}
                            <div class="account-info mt-4 p-3 border rounded mx-auto" style="max-width: 500px;">
                                <p class="mb-2">
                                    <i class="fa-solid fa-user-check me-2 text-success"></i>
                                    Ваш аккаунт успешно создан!
                                </p>
                                <p class="mb-0 small text-muted">
                                    Для управления бронированиями войдите с вашим email и паролем.
                                </p>
                            </div>
                        @endif

                        {{-- Кнопки действий --}}
                        <div class="action-buttons mt-4">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-primary me-2">
                                <i class="fa-solid fa-tachometer-alt me-2"></i>Личный кабинет
                            </a>
                            <a href="{{ route('front.tourbooking.services') }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-search me-2"></i>Смотреть другие отели
                            </a>
                        </div>

                        {{-- Контактная информация --}}
                        <div class="contact-info mt-4 pt-4 border-top">
                            <p class="text-muted mb-0">
                                Есть вопросы? Свяжитесь с нами: 
                                <a href="mailto:{{ $general_setting->contact_email ?? 'support@example.com' }}">
                                    {{ $general_setting->contact_email ?? 'support@example.com' }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection