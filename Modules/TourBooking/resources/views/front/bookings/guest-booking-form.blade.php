@extends('layout_inner_page')

@section('title')
    <title>Оформление заказа</title>
@endsection

@section('front-content')
    @include('breadcrumb', ['breadcrumb_title' => 'Оформление заказа'])

@php
    $roomImage = ($room && $room->thumbnail_url) ? $room->thumbnail_url : null;
    if (!$roomImage && $service->thumbnail && $service->thumbnail->file_path) {
        $roomImage = asset($service->thumbnail->file_path);
    }
    $adultsCount = $adults ?? $guests;
    $guestsText = $adultsCount . ' взр.' . (!empty($children) ? ', ' . $children . ' дет.' : '');
    $fmtDate = fn($d) => \Carbon\Carbon::parse($d)->format('d.m.Y');
    $pricePerNightShown = $pricePerNight ?? ($nights ? $totalPrice / $nights : $totalPrice);
    $countries = ['Азербайджан','Россия','Казахстан','Узбекистан','Туркменистан','Кыргызстан','Таджикистан','Грузия','Армения','Беларусь','Украина','Молдова','Турция','Иран','Ирак','Израиль','ОАЭ','Саудовская Аравия','Катар','Кувейт','Бахрейн','Оман','Египет','Индия','Пакистан','Китай','Германия','Франция','Италия','Испания','Великобритания','Польша','Чехия','Латвия','Литва','Эстония','Финляндия','Швеция','Норвегия','Нидерланды','Бельгия','Швейцария','Австрия','Болгария','Румыния','Сербия','Греция','Кипр','США','Канада','Другая страна'];
@endphp

<style>
    .gb-page{background:#f9fafb;padding:40px 0 80px;overflow-x:hidden}
    .gb-grid{display:grid;grid-template-columns:minmax(0,3fr) minmax(0,2fr);gap:28px;align-items:start}
    .gb-card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08)}
    .gb-form-card{padding:32px}
    .gb-summary{position:sticky;top:24px;overflow:hidden}
    .gb-summary img{width:100%;height:160px;object-fit:cover;display:block}
    .gb-summary-body{padding:20px}
    .gb-hotel{font-size:16px;font-weight:700;color:#111827;margin:0 0 2px}
    .gb-room{font-size:14px;color:#374151;margin:0 0 14px}
    .gb-row{display:flex;justify-content:space-between;gap:12px;font-size:14px;color:#374151;padding:6px 0}
    .gb-row span:last-child{font-weight:500;color:#111827;text-align:right}
    .gb-divider{border-top:1px solid #e5e7eb;margin:10px 0}
    .gb-total{display:flex;justify-content:space-between;align-items:center;gap:12px}
    .gb-total-label{font-size:18px;font-weight:700;color:#111827}
    .gb-total-sum{font-size:24px;font-weight:700;color:var(--gb-accent)}
    .gb-note{font-size:12px;color:#6b7280;margin:14px 0 0;text-align:center}
    .gb-page{--gb-accent:#1a56db;--gb-accent-rgb:26,86,219}
    .gb-section-title{font-size:16px;font-weight:700;color:#111827;margin:0 0 20px}
    .gb-fields{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .gb-full{grid-column:1/-1}
    .gb-label{display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px}
    .gb-label .req{color:#ef4444;margin-left:2px}
    .gb-input{width:100%;height:48px;padding:12px 16px;border:1px solid #e5e7eb;border-radius:8px;font-size:15px;color:#111827;background:#fff;outline:none;transition:border-color .15s,box-shadow .15s;appearance:none;-webkit-appearance:none}
    select.gb-input{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M1 1l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 16px center;padding-right:40px}
    textarea.gb-input{height:96px;resize:vertical}
    .gb-input::placeholder{color:#9ca3af}
    .gb-input:focus{border-color:var(--gb-accent);box-shadow:0 0 0 3px rgba(var(--gb-accent-rgb),.15)}
    .gb-input.is-invalid,.gb-input.gb-touched:invalid{border-color:#ef4444}
    .gb-error{color:#ef4444;font-size:12px;margin-top:4px}
    .gb-account{border:1px solid #e5e7eb;border-radius:10px;margin-top:24px;overflow:hidden}
    .gb-account-head{width:100%;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 16px;background:#f9fafb;border:0;font-size:14px;font-weight:600;color:#374151;text-align:left;cursor:pointer}
    .gb-account-head i.fa-chevron-down{transition:transform .2s}
    .gb-account-head[aria-expanded=true] i.fa-chevron-down{transform:rotate(180deg)}
    .gb-account-body{padding:16px;display:none}
    .gb-account-body.open{display:block}
    .gb-account-text{font-size:14px;color:#374151;margin:0 0 14px}
    .gb-check{display:flex;align-items:flex-start;gap:10px;font-size:14px;color:#374151;cursor:pointer;margin:0}
    .gb-check input{position:absolute;opacity:0;width:0;height:0}
    .gb-box{flex:0 0 18px;width:18px;height:18px;border:1.5px solid #d1d5db;border-radius:4px;background:#fff;display:inline-flex;align-items:center;justify-content:center;margin-top:2px;transition:all .15s}
    .gb-box::after{content:"\2713";color:#fff;font-size:12px;line-height:1;display:none}
    .gb-check input:checked + .gb-box{background:var(--gb-accent);border-color:var(--gb-accent)}
    .gb-check input:checked + .gb-box::after{display:block}
    .gb-check input:focus-visible + .gb-box{box-shadow:0 0 0 3px rgba(var(--gb-accent-rgb),.25)}
    .gb-check a{color:var(--gb-accent);text-decoration:underline}
    .gb-pw-fields{display:none;margin-top:16px}
    .gb-pw-fields.open{display:grid}
    .gb-terms{margin-top:24px}
    .gb-submit{width:100%;height:56px;margin-top:24px;border:0;border-radius:10px;background:var(--gb-accent);color:#fff;font-size:18px;font-weight:700;cursor:pointer;transition:background .15s,transform .1s}
    .gb-submit:hover:not(:disabled){background:#1648b8}
    .gb-submit:active:not(:disabled){transform:scale(.98)}
    .gb-submit:disabled{background:#d1d5db;color:#fff;cursor:not-allowed}
    .gb-secure{font-size:12px;color:#9ca3af;text-align:center;margin:12px 0 0}
    .gb-mobile-sum{display:none}
    .gb-submit .gb-mob-total{display:none}
    @media (max-width:991px){
        .gb-grid{grid-template-columns:1fr}
        .gb-summary-col{order:-1}
        .gb-summary{position:static}
    }
    @media (max-width:767px){
        .gb-page{padding:20px 0 90px}
        .gb-summary img{display:none}
        .gb-summary-body{padding:16px}
        .gb-form-card{padding:20px 16px}
        .gb-fields{grid-template-columns:1fr}
        .gb-total-sum{font-size:20px}
        .gb-submit-wrap{position:sticky;bottom:0;margin:20px -16px -20px;padding:12px 16px;background:#fff;border-top:1px solid #e5e7eb;z-index:20;border-radius:0 0 12px 12px}
        .gb-submit-wrap .gb-submit{margin-top:0}
        .gb-submit .gb-mob-total{display:inline}
    }
</style>

    <section class="gb-page">
        <div class="container">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            <div class="gb-grid">
                {{-- Сводка бронирования --}}
                <aside class="gb-summary-col">
                    <div class="gb-card gb-summary">
                        @if($roomImage)
                            <img src="{{ $roomImage }}" alt="{{ $room->name ?? $service->title }}">
                        @endif
                        <div class="gb-summary-body">
                            <h2 class="gb-hotel">{{ $service->title }}</h2>
                            @if($room)<p class="gb-room">{{ $room->name }}</p>@endif
                            <div class="gb-divider"></div>
                            <div class="gb-row"><span>📅 Заезд</span><span>{{ $fmtDate($checkIn) }}</span></div>
                            <div class="gb-row"><span>📅 Выезд</span><span>{{ $fmtDate($checkOut) }}</span></div>
                            <div class="gb-row"><span>🌙 Ночей</span><span>{{ $nights }}</span></div>
                            <div class="gb-row"><span>👥 Гостей</span><span>{{ $guestsText }}</span></div>
                            <div class="gb-divider"></div>
                            <div class="gb-row"><span>Цена за ночь</span><span>{{ currency($pricePerNightShown) }}</span></div>
                            <div class="gb-row"><span>Количество ночей</span><span>×{{ $nights }}</span></div>
                            @if(!empty($extrasBreakdown) && $extrasBreakdown->count())
                                @foreach($extrasBreakdown as $row)
                                    <div class="gb-row"><span>{{ $row['name'] }}</span><span>+{{ currency($row['amount']) }} / ночь</span></div>
                                @endforeach
                                <div class="gb-row"><span colspan="2" style="font-size:12px;color:#6b7280">Цена за ночь включает дополнительные услуги</span><span></span></div>
                            @endif
                            <div class="gb-divider"></div>
                            <div class="gb-total">
                                <span class="gb-total-label">Итого к оплате</span>
                                <span class="gb-total-sum">{{ currency($totalPrice) }}</span>
                            </div>
                            <p class="gb-note">Оплата при заселении · Бесплатная отмена</p>
                        </div>
                    </div>
                </aside>

                {{-- Форма --}}
                <div>
                    <div class="gb-card gb-form-card">
                        <form action="{{ route('front.tourbooking.process-guest-booking') }}" method="POST" id="guestBookingForm" autocomplete="off" novalidate>
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

                            <h3 class="gb-section-title">👤 Ваши личные данные</h3>

                            <div class="gb-fields">
                                <div>
                                    <label class="gb-label" for="gbName">Имя и Фамилия<span class="req">*</span></label>
                                    <input type="text" id="gbName" name="name" class="gb-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Введите ваше полное имя" autocomplete="name">
                                    @error('name')<div class="gb-error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label class="gb-label" for="gbEmail">Email<span class="req">*</span></label>
                                    <input type="email" id="gbEmail" name="email" class="gb-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="name@example.com" autocomplete="off">
                                    @error('email')<div class="gb-error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label class="gb-label" for="gbPhone">Телефон<span class="req">*</span></label>
                                    <input type="tel" id="gbPhone" name="phone" class="gb-input @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="+994 00 000 00 00" autocomplete="tel">
                                    @error('phone')<div class="gb-error">{{ $message }}</div>@enderror
                                </div>
                                <div>
                                    <label class="gb-label" for="gbCountry">Страна</label>
                                    <select id="gbCountry" name="country" class="gb-input" autocomplete="country-name">
                                        <option value="">Выберите страну</option>
                                        @foreach($countries as $c)
                                            <option value="{{ $c }}" @selected(old('country') === $c)>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="gb-full">
                                    <label class="gb-label" for="gbAddress">Адрес</label>
                                    <input type="text" id="gbAddress" name="address" class="gb-input" value="{{ old('address') }}" placeholder="Введите ваш адрес" autocomplete="street-address">
                                </div>
                                <div class="gb-full">
                                    <label class="gb-label" for="gbNotes">Особые пожелания</label>
                                    <textarea id="gbNotes" name="notes" class="gb-input" placeholder="Любые особые пожелания или заметки">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            {{-- Необязательный личный кабинет --}}
                            @php $accOpen = old('create_account') || $errors->has('password'); @endphp
                            <div class="gb-account">
                                <button type="button" class="gb-account-head" id="gbAccountToggle" aria-expanded="{{ $accOpen ? 'true' : 'false' }}">
                                    <span><i class="fa-solid fa-circle-info me-2" style="color:var(--gb-accent)"></i>Создать личный кабинет (необязательно)</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </button>
                                <div class="gb-account-body {{ $accOpen ? 'open' : '' }}" id="gbAccountBody">
                                    <p class="gb-account-text">Сохраните бронирование в личном кабинете, чтобы управлять им онлайн</p>
                                    <label class="gb-check">
                                        <input type="checkbox" name="create_account" id="gbCreateAccount" value="1" @checked(old('create_account'))>
                                        <span class="gb-box"></span>
                                        <span>Создать личный кабинет</span>
                                    </label>
                                    <div class="gb-fields gb-pw-fields {{ old('create_account') ? 'open' : '' }}" id="gbPwFields">
                                        <div>
                                            <label class="gb-label" for="gbPass">Пароль<span class="req">*</span></label>
                                            <input type="password" id="gbPass" name="password" class="gb-input @error('password') is-invalid @enderror" minlength="6" placeholder="Минимум 6 символов" autocomplete="new-password">
                                            @error('password')<div class="gb-error">{{ $message }}</div>@enderror
                                        </div>
                                        <div>
                                            <label class="gb-label" for="gbPass2">Подтвердите пароль<span class="req">*</span></label>
                                            <input type="password" id="gbPass2" name="password_confirmation" class="gb-input" minlength="6" placeholder="Введите пароль ещё раз" autocomplete="new-password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="gb-terms">
                                <label class="gb-check">
                                    <input type="checkbox" name="terms" id="termsCheck" required>
                                    <span class="gb-box"></span>
                                    <span>Я принимаю <a href="{{ route('terms-conditions') }}" target="_blank">условия и правила</a></span>
                                </label>
                                @error('terms')<div class="gb-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="gb-submit-wrap">
                                <button type="submit" class="gb-submit" id="submitBtn" disabled>
                                    <span class="gb-mob-total">{{ currency($totalPrice) }} · </span>Подтвердить бронирование →
                                </button>
                            </div>
                            <p class="gb-secure">🔒 Безопасное соединение · Оплата при заселении · Бесплатная отмена</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('guestBookingForm');
        var btn = document.getElementById('submitBtn');
        var toggle = document.getElementById('gbAccountToggle');
        var body = document.getElementById('gbAccountBody');
        var create = document.getElementById('gbCreateAccount');
        var pw = document.getElementById('gbPwFields');
        var pass = document.getElementById('gbPass');
        var pass2 = document.getElementById('gbPass2');
        var original = btn.innerHTML;

        // Не подставлять автозаполнением чужие данные в email/пароль
        window.addEventListener('load', function () {
            setTimeout(function () {
                var email = document.getElementById('gbEmail');
                if (email && !email.defaultValue && email.value) { email.value = ''; }
                if (!create.checked) { pass.value = ''; pass2.value = ''; }
                validate();
            }, 300);
        });

        toggle.addEventListener('click', function () {
            var open = body.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        function syncAccount() {
            pw.classList.toggle('open', create.checked);
            pass.required = pass2.required = create.checked;
            if (!create.checked) { pass.value = ''; pass2.value = ''; }
            validate();
        }
        create.addEventListener('change', syncAccount);

        function validate() {
            var ok = form.checkValidity();
            if (create.checked && pass.value !== pass2.value) ok = false;
            btn.disabled = !ok;
        }
        form.addEventListener('input', validate);
        form.addEventListener('change', validate);
        form.querySelectorAll('.gb-input').forEach(function (el) {
            el.addEventListener('blur', function () { el.classList.add('gb-touched'); });
        });
        syncAccount();

        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Обработка...';
        });
    })();
</script>
@endpush
