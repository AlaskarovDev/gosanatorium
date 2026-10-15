@extends('layout_inner_page')

@section('title')
    <title>Оформление заказа</title>
@endsection

@section('front-content')
    @include('breadcrumb', ['breadcrumb_title' => 'Оформление заказа'])

    <section class="guest-booking-area pt-100 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="booking-form-wrapper bg-white p-4 p-lg-5 rounded shadow">
                        
                        {{-- Показать сообщения об ошибках --}}
                        @if($errors->any())
                            <div class="alert alert-danger mb-4">
                                <h6 class="alert-heading mb-2">
                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>Произошла ошибка:
                                </h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Показать сообщение об ошибке из сессии --}}
                        @if(session('error'))
                            <div class="alert alert-danger mb-4">
                                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Информация о бронировании --}}
                        <div class="booking-summary mb-4 p-3 bg-light rounded">
                            <h5 class="mb-3">
                                <i class="fa-solid fa-calendar-check me-2"></i>Информация о бронировании
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Отель:</strong> {{ $service->title }}</p>
                                    @if($room)
                                        <p class="mb-1"><strong>Номер:</strong> {{ $room->name }}</p>
                                    @endif
                                    <p class="mb-1"><strong>Дата заезда:</strong> {{ $checkIn }}</p>
                                    <p class="mb-1"><strong>Дата выезда:</strong> {{ $checkOut }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-1"><strong>Ночей:</strong> {{ $nights }}</p>
                                    <p class="mb-1">
                                        <strong>Гостей:</strong>
                                        {{ ($adults ?? $guests) }} взр.@if(!empty($children)), {{ $children }} дет.@endif
                                    </p>
                                    @if(!empty($extrasBreakdown) && $extrasBreakdown->count())
                                        <div class="mt-2 small text-muted text-md-end">
                                            <div class="fw-semibold text-dark mb-1">Дополнительно:</div>
                                            @foreach($extrasBreakdown as $row)
                                                <div>{{ $row['name'] }} <span class="text-primary">+{{ currency($row['amount']) }}</span> <span class="text-muted">/ ночь</span></div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <h4 class="text-primary mt-2">
                                        Итого: {{ currency($totalPrice) }}
                                    </h4>
                                </div>
                            </div>
                        </div>

                        {{-- Форма бронирования --}}
                        <form action="{{ route('front.tourbooking.process-guest-booking') }}" method="POST" id="guestBookingForm">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <input type="hidden" name="room_id" value="{{ $room->id ?? '' }}">
                            <input type="hidden" name="check_in" value="{{ $checkIn }}">
                            <input type="hidden" name="check_out" value="{{ $checkOut }}">
                            <input type="hidden" name="nights" value="{{ $nights }}">
                            <input type="hidden" name="guests" value="{{ $guests }}">
                            <input type="hidden" name="adults" value="{{ $adults ?? $guests ?? 2 }}">
                            <input type="hidden" name="children" value="{{ $children ?? 0 }}">
                            <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                            @foreach(($optionalIds ?? []) as $optId)
                                <input type="hidden" name="optional_extras[]" value="{{ (int) $optId }}">
                            @endforeach

                            <h5 class="mb-4">
                                <i class="fa-solid fa-user me-2"></i>Ваши личные данные
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Имя и Фамилия <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required 
                                           placeholder="Введите ваше полное имя">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" required 
                                           placeholder="Введите ваш email адрес">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Телефон <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}" required 
                                           placeholder="Введите ваш номер телефона">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Страна</label>
                                    <input type="text" name="country" class="form-control" 
                                           value="{{ old('country') }}" 
                                           placeholder="Введите вашу страну">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Адрес</label>
                                    <input type="text" name="address" class="form-control" 
                                           value="{{ old('address') }}" 
                                           placeholder="Введите ваш адрес">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Особые пожелания</label>
                                    <textarea name="notes" class="form-control" rows="3" 
                                              placeholder="Любые особые пожелания или заметки">{{ old('notes') }}</textarea>
                                </div>

                                {{-- Поля пароля --}}
                                <div class="col-12 mb-3">
                                    <div class="alert alert-info">
                                        <i class="fa-solid fa-info-circle me-2"></i>
                                        Для управления бронированиями вам будет создан личный кабинет.
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Пароль <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                           required minlength="6"
                                           placeholder="Создайте пароль (минимум 6 символов)">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Подтвердите пароль <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" 
                                           required minlength="6"
                                           placeholder="Введите пароль ещё раз">
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms') is-invalid @enderror" 
                                               type="checkbox" name="terms" id="termsCheck" required>
                                        <label class="form-check-label" for="termsCheck">
                                            Я принимаю <a href="{{ route('terms-conditions') }}" target="_blank">условия и правила</a>
                                        </label>
                                        @error('terms')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" id="submitBtn">
                                        <i class="fa-solid fa-check me-2"></i>Подтвердить заказ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Отключить кнопку при отправке формы
    document.getElementById('guestBookingForm').addEventListener('submit', function() {
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Обработка...';
    });
</script>
@endpush