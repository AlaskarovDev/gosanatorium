@extends('layout_inner_page')

@section('title')
    <title>Оформление трансфера</title>
@endsection

@push('style_section')
<style>
    /* ====== ШАПКА ====== */
    .checkout-header-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 25px 0 30px;
        position: relative;
    }

    .checkout-header-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
    }

    .checkout-header-section .container {
        position: relative;
        z-index: 1;
    }

    .header-title {
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .header-subtitle {
        color: rgba(255,255,255,0.85);
        font-size: 16px;
        margin-bottom: 0;
    }

    .header-info-alert {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 8px;
        color: #fff;
        padding: 12px 20px;
        margin-top: 20px;
        font-size: 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 16px;
    }

    .header-route-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.15);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        color: #fff;
    }

    .header-route-badge i {
        color: #008080;
    }

    /* ====== CHECKOUT CONTENT ====== */
    .tg-checkout-section {
        background: #f5f7fa;
        padding: 40px 0 60px;
    }

    .tg-checkout-card, .tg-summary-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .tg-checkout-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    .tg-card-header {
        background: #f8f9fa;
        padding: 16px 20px;
        border-bottom: 1px solid #eee;
    }

    .tg-card-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #333;
    }

    .tg-card-header h4 i {
        color: #008080;
        margin-right: 8px;
    }

    .tg-card-body {
        padding: 20px;
    }

    .tg-trip-detail label {
        display: block;
        font-size: 13px;
        color: #888;
        margin-bottom: 4px;
    }

    .tg-trip-detail p {
        margin: 0;
        font-size: 15px;
    }

    .tg-vehicle-placeholder-sm {
        width: 100%;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
        border-radius: 8px;
    }

    .tg-summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 15px;
        color: #555;
    }

    .tg-summary-item:last-child {
        border-bottom: none;
    }

    .tg-summary-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        font-size: 20px;
        font-weight: 700;
        color: #008080;
    }

    .tg-create-account {
        border: 1px solid #e0e0e0;
    }

    .btn-primary {
        background: #008080;
        border-color: #008080;
    }

    .btn-primary:hover {
        background: #006666;
        border-color: #006666;
    }

    @media (max-width: 991px) {
        .tg-summary-card {
            position: static !important;
            margin-top: 20px;
        }

        .header-title {
            font-size: 24px;
        }
    }

    @media (max-width: 575px) {
        .header-info-alert {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>
@endpush

@section('front-content')
    {{-- Breadcrumb --}}
    <div class="tg-breadcrumb-spacing-3 include-bg p-relative fix"
        data-background="{{ asset($general_setting->secondary_breadcrumb_image ?? $general_setting->breadcrumb_image) }}">
        <div class="tg-hero-top-shadow"></div>
    </div>
    <div class="tg-breadcrumb-list-2-wrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tg-breadcrumb-list-2">
                        <ul>
                            <li><a href="{{ url('/') }}">Главная</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li><a href="{{ route('front.tourbooking.home') }}">Трансфер</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li><span>Оформление</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Шапка --}}
    <section class="checkout-header-section">
        <div class="container">
            <h1 class="header-title">Оформление трансфера</h1>
            <p class="header-subtitle">
                <i class="fas fa-map-marker-alt" style="color: #008080;"></i>
                {{ $route->from_location }}
                <i class="fas fa-long-arrow-alt-right" style="margin: 0 8px; opacity: 0.5;"></i>
                {{ $route->to_location }}
            </p>

            <div class="header-info-alert">
                <div class="header-route-badge">
                    <i class="fas fa-car"></i>
                    {{ $vehicle->name }}
                </div>
                <div class="header-route-badge">
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($transferDate)->format('d.m.Y') }}
                </div>
                <div class="header-route-badge">
                    <i class="fas fa-users"></i>
                    {{ $passengers }} пассажир(ов)
                </div>
                <div class="header-route-badge">
                    <i class="fas fa-tag"></i>
                    {{ currency($totalPrice) }}
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="tg-checkout-section">
        <div class="container">
            <form action="{{ route('front.tourbooking.transfer.process') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="route_id" id="route_id" value="{{ $route->id }}">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="transfer_date" id="transfer_date" value="{{ $transferDate }}">
                <input type="hidden" name="passengers" value="{{ $passengers }}">

                <div class="row">
                    <!-- Left Column - Form -->
                    <div class="col-lg-8">
                        <!-- Trip Details - Editable -->
                        <div class="tg-checkout-card mb-4">
                            <div class="tg-card-header d-flex justify-content-between align-items-center">
                                <h4><i class="fas fa-route"></i> Детали поездки</h4>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTripModal">
                                    <i class="fas fa-edit"></i> Изменить
                                </button>
                            </div>
                            <div class="tg-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="tg-trip-detail">
                                            <label>Маршрут:</label>
                                            <p id="display_route"><strong>{{ $route->from_location }} → {{ $route->to_location }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="tg-trip-detail">
                                            <label>Дата:</label>
                                            <p id="display_date"><strong>{{ \Carbon\Carbon::parse($transferDate)->format('d.m.Y') }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="tg-trip-detail">
                                            <label>Пассажиры:</label>
                                            <p><strong>{{ $passengers }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Vehicle -->
                        <div class="tg-checkout-card mb-4">
                            <div class="tg-card-header">
                                <h4><i class="fas fa-car"></i> Выбранный транспорт</h4>
                            </div>
                            <div class="tg-card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($vehicle->image)
                                            <img src="{{ asset($vehicle->image) }}" alt="{{ $vehicle->name }}" class="img-fluid rounded">
                                        @else
                                            <div class="tg-vehicle-placeholder-sm">
                                                <i class="fas fa-car fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h5>{{ $vehicle->name }}</h5>
                                        <p class="mb-1">
                                            <span class="badge bg-info"><i class="fas fa-users"></i> {{ $vehicle->passenger_capacity }}</span>
                                            <span class="badge bg-secondary"><i class="fas fa-suitcase"></i> {{ $vehicle->luggage_capacity }}</span>
                                        </p>
                                        @if($vehicle->features)
                                            <p class="text-muted mb-0">
                                                @foreach(array_slice($vehicle->features, 0, 3) as $feature)
                                                    <small><i class="fas fa-check text-success"></i> {{ $feature }}</small>
                                                    @if(!$loop->last) | @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <a href="{{ route('front.tourbooking.transfer.select-vehicle', [
                                            'route_id' => $route->id,
                                            'transfer_date' => $transferDate,
                                            'passengers' => $passengers
                                        ]) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-exchange-alt"></i> Изменить
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Details -->
                        <div class="tg-checkout-card mb-4">
                            <div class="tg-card-header">
                                <h4><i class="fas fa-info-circle"></i> Дополнительная информация</h4>
                            </div>
                            <div class="tg-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Время трансфера</label>
                                            <input type="time" name="transfer_time" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Номер рейса (если есть)</label>
                                            <input type="text" name="flight_number" class="form-control" placeholder="Например: J2-8001">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Адрес подачи</label>
                                            <input type="text" name="pickup_address" class="form-control" placeholder="Укажите точный адрес">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Адрес высадки</label>
                                            <input type="text" name="dropoff_address" class="form-control" placeholder="Укажите точный адрес">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-0">
                                            <label class="form-label">Особые пожелания</label>
                                            <textarea name="special_requests" class="form-control" rows="3" placeholder="Детское кресло, встреча с табличкой и т.д."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guest Information -->
                        <div class="tg-checkout-card mb-4">
                            <div class="tg-card-header">
                                <h4><i class="fas fa-user"></i> Контактная информация</h4>
                            </div>
                            <div class="tg-card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Имя и фамилия *</label>
                                            <input type="text" name="guest_name" class="form-control @error('guest_name') is-invalid @enderror" 
                                                   value="{{ old('guest_name', $user->name ?? '') }}" required>
                                            @error('guest_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Телефон *</label>
                                            <input type="tel" name="guest_phone" class="form-control @error('guest_phone') is-invalid @enderror" 
                                                   value="{{ old('guest_phone', $user->phone ?? '') }}" required placeholder="+994 XX XXX XX XX">
                                            @error('guest_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email *</label>
                                            <input type="email" name="guest_email" class="form-control @error('guest_email') is-invalid @enderror" 
                                                   value="{{ old('guest_email', $user->email ?? '') }}" required>
                                            @error('guest_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    @guest
                                        <div class="col-12">
                                            <div class="tg-create-account mt-3 p-3 bg-light rounded">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="create_account" id="create_account" value="1">
                                                    <label class="form-check-label" for="create_account">
                                                        <strong>Создать аккаунт</strong> - чтобы отслеживать свои бронирования
                                                    </label>
                                                </div>
                                                <div id="passwordField" class="mt-3" style="display: none;">
                                                    <label class="form-label">Пароль</label>
                                                    <input type="password" name="password" class="form-control" minlength="6">
                                                    <small class="text-muted">Минимум 6 символов</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Summary -->
                    <div class="col-lg-4">
                        <div class="tg-summary-card sticky-top" style="top: 100px;">
                            <div class="tg-card-header">
                                <h4><i class="fas fa-receipt"></i> Итого</h4>
                            </div>
                            <div class="tg-card-body">
                                <div class="tg-summary-item">
                                    <span>Маршрут</span>
                                    <span id="summary_route_price">{{ currency($route->base_price) }}</span>
                                </div>
                                <div class="tg-summary-item">
                                    <span>Транспорт ({{ $vehicle->name }})</span>
                                    <span>{{ currency($vehicle->base_price) }}</span>
                                </div>
                                
                                <hr>
                                
                                <div class="tg-summary-total">
                                    <span>Итого к оплате:</span>
                                    <span id="summary_total">{{ currency($totalPrice) }}</span>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">
                                    <i class="fas fa-check-circle"></i> Подтвердить бронирование
                                </button>
                                
                                <p class="text-center text-muted mt-3 mb-0">
                                    <small><i class="fas fa-lock"></i> Оплата при получении услуги</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Edit Trip Modal -->
    <div class="modal fade" id="editTripModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Изменить детали поездки</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Маршрут</label>
                        <select id="modal_route" class="form-select">
                            @foreach($allRoutes as $r)
                                <option value="{{ $r->id }}" data-price="{{ $r->base_price }}" {{ $r->id == $route->id ? 'selected' : '' }}>
                                    {{ $r->from_location }} → {{ $r->to_location }} ({{ currency($r->base_price) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата</label>
                        <input type="date" id="modal_date" class="form-control" value="{{ $transferDate }}" min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-primary" id="applyChanges">Применить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Valyuta məlumatları (session-dan)
        const CURRENCY_ICON = @json(session('currency')['currency_icon'] ?? default_currency()['currency_icon']);
        const CURRENCY_RATE = parseFloat(@json(session('currency')['currency_rate'] ?? default_currency()['currency_rate']));
        const CURRENCY_POSITION = @json(session('currency')['currency_position'] ?? default_currency()['currency_position']);

        function formatCurrencyDynamic(amount) {
            amount = parseFloat(amount) * CURRENCY_RATE;
            let formatted = amount.toFixed(2);
            switch(CURRENCY_POSITION) {
                case 'before_price': return CURRENCY_ICON + formatted;
                case 'before_price_with_space': return CURRENCY_ICON + ' ' + formatted;
                case 'after_price': return formatted + CURRENCY_ICON;
                case 'after_price_with_space': return formatted + ' ' + CURRENCY_ICON;
                default: return CURRENCY_ICON + formatted;
            }
        }

        // Create account toggle
        const createAccountCheckbox = document.getElementById('create_account');
        const passwordField = document.getElementById('passwordField');
        
        if (createAccountCheckbox) {
            createAccountCheckbox.addEventListener('change', function() {
                passwordField.style.display = this.checked ? 'block' : 'none';
                if (this.checked) {
                    passwordField.querySelector('input').setAttribute('required', 'required');
                } else {
                    passwordField.querySelector('input').removeAttribute('required');
                }
            });
        }
        
        // Apply changes from modal
        document.getElementById('applyChanges').addEventListener('click', function() {
            const routeSelect = document.getElementById('modal_route');
            const dateInput = document.getElementById('modal_date');
            const selectedOption = routeSelect.options[routeSelect.selectedIndex];
            
            // Update hidden fields
            document.getElementById('route_id').value = routeSelect.value;
            document.getElementById('transfer_date').value = dateInput.value;
            
            // Update display
            document.getElementById('display_route').innerHTML = '<strong>' + selectedOption.text.split('(')[0].trim() + '</strong>';
            
            const dateObj = new Date(dateInput.value);
            const formattedDate = dateObj.toLocaleDateString('ru-RU', {day: '2-digit', month: '2-digit', year: 'numeric'});
            document.getElementById('display_date').innerHTML = '<strong>' + formattedDate + '</strong>';
            
            // Update prices — valyuta ilə dinamik format
            const routePrice = parseFloat(selectedOption.dataset.price);
            const vehiclePrice = {{ (float)$vehicle->base_price }};
            const totalPrice = routePrice + vehiclePrice;
            
            document.getElementById('summary_route_price').textContent = formatCurrencyDynamic(routePrice);
            document.getElementById('summary_total').textContent = formatCurrencyDynamic(totalPrice);
            
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('editTripModal')).hide();
        });
    });
</script>
@endpush