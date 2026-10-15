{{-- Room Booking Popup — modern redesign with counters + extras + live pricing --}}

<div class="modal fade" id="roomBookingModal" tabindex="-1" aria-labelledby="roomBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rb-modal">
            <div class="modal-header rb-header">
                <h5 class="modal-title rb-title" id="roomBookingModalLabel">
                    <i class="fa fa-bed me-2"></i><span data-rb-step-title>{{ __('translate.Select Room') }}</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body rb-body p-0">
                <div id="roomPopupLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 text-muted mb-0">{{ __('translate.Loading rooms...') }}</p>
                </div>

                <div id="roomsList" class="d-none p-3 p-md-4">
                    <div class="row g-3" id="roomsContainer"></div>
                </div>

                <div id="roomBookingForm" class="d-none p-3 p-md-4">
                    <button type="button" class="btn btn-link rb-back" onclick="backToRoomSelection()">
                        <i class="fa fa-arrow-left me-1"></i>{{ __('translate.Back to Rooms') }}
                    </button>

                    <div class="row g-3">
                        <div class="col-lg-5">
                            <div id="selectedRoomCard" class="rb-selected-card"></div>
                        </div>

                        <div class="col-lg-7">
                            <form id="guestBookingForm" onsubmit="submitGuestBooking(event)" class="rb-form">
                                @csrf
                                <input type="hidden" name="service_id" id="popup_service_id">
                                <input type="hidden" name="room_id" id="popup_room_id">

                                <div class="rb-section">
                                    <h6 class="rb-section-title">
                                        <i class="fa fa-calendar-check me-2"></i>{{ __('translate.Dates') }}
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Check-in Date') }}</label>
                                            <input type="date" class="form-control rb-input" name="check_in_date" id="popup_check_in"
                                                   min="{{ date('Y-m-d') }}" required onchange="rbRecalc()">
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Check-out Date') }}</label>
                                            <input type="date" class="form-control rb-input" name="check_out_date" id="popup_check_out"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" required onchange="rbRecalc()">
                                        </div>
                                    </div>
                                </div>

                                <div class="rb-section">
                                    <h6 class="rb-section-title">
                                        <i class="fa fa-users me-2"></i>{{ __('translate.Guests') }}
                                    </h6>
                                    <div id="rb-base-hint" class="rb-base-hint"></div>

                                    <div class="rb-counter-row">
                                        <div class="rb-counter-info">
                                            <strong>{{ __('translate.Adults') }}</strong>
                                            <small class="text-muted d-block">{{ __('translate.18+ years') }}</small>
                                        </div>
                                        <div class="rb-counter">
                                            <button type="button" class="rb-counter-btn" data-action="dec" data-target="adults">−</button>
                                            <input type="number" id="popup_adults" name="adults" value="1" min="1" max="20" readonly>
                                            <button type="button" class="rb-counter-btn" data-action="inc" data-target="adults">+</button>
                                        </div>
                                    </div>

                                    <div class="rb-counter-row">
                                        <div class="rb-counter-info">
                                            <strong>{{ __('translate.Children') }}</strong>
                                            <small class="text-muted d-block">{{ __('translate.Under 18 years') }}</small>
                                        </div>
                                        <div class="rb-counter">
                                            <button type="button" class="rb-counter-btn" data-action="dec" data-target="children">−</button>
                                            <input type="number" id="popup_children" name="children" value="0" min="0" max="10" readonly>
                                            <button type="button" class="rb-counter-btn" data-action="inc" data-target="children">+</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="rb-section" id="rb-extras-wrap" style="display:none">
                                    <h6 class="rb-section-title">
                                        <i class="fa fa-plus-circle me-2"></i>{{ __('translate.Optional Extras') }}
                                    </h6>
                                    <div id="rb-optional-extras-list"></div>
                                </div>

                                <div class="rb-section">
                                    <h6 class="rb-section-title">
                                        <i class="fa fa-user me-2"></i>{{ __('translate.Your Information') }}
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Full Name') }}</label>
                                            <input type="text" class="form-control rb-input" name="customer_name" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Email') }}</label>
                                            <input type="email" class="form-control rb-input" name="customer_email" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Phone') }}</label>
                                            <input type="tel" class="form-control rb-input" name="customer_phone" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Address') }}</label>
                                            <input type="text" class="form-control rb-input" name="customer_address">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small text-muted mb-1">{{ __('translate.Special Requests') }}</label>
                                            <textarea class="form-control rb-input" name="customer_notes" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="rb-summary" id="rb-summary">
                                    <div class="rb-summary-row">
                                        <span>{{ __('translate.Base price') }} <span class="text-muted small" id="rb-base-detail"></span></span>
                                        <span id="rb-base-amount">—</span>
                                    </div>
                                    <div id="rb-extras-summary"></div>
                                    <div class="rb-summary-row rb-summary-row--nights">
                                        <span>{{ __('translate.Nights') }}</span>
                                        <span id="rb-nights">—</span>
                                    </div>
                                    <div class="rb-summary-total">
                                        <span>{{ __('translate.Total') }}</span>
                                        <strong id="rb-total">—</strong>
                                    </div>
                                </div>

                                <button type="submit" class="btn rb-submit w-100 mt-3" id="submitBookingBtn">
                                    <i class="fa fa-check me-2"></i>{{ __('translate.Confirm Booking') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="bookingSuccess" class="d-none p-4 p-md-5 text-center">
                    <i class="fa fa-check-circle text-success" style="font-size: 70px;"></i>
                    <h4 class="mt-3 mb-2">{{ __('translate.Booking Confirmed') }}</h4>
                    <div id="bookingDetails" class="text-start mx-auto" style="max-width: 500px;"></div>
                    <div class="mt-4">
                        <a href="{{ route('user.bookings.index') }}" class="btn btn-primary me-2">
                            <i class="fa fa-list me-2"></i>{{ __('translate.My Bookings') }}
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== Modern room booking popup ===== */
.rb-modal { border: none; border-radius: 18px; overflow: hidden; box-shadow: 0 20px 60px rgba(15, 25, 50, .25); }
.rb-header { background: linear-gradient(135deg, #560ce3 0%, #7e3df5 100%); color: #fff; border: none; padding: 16px 20px; }
.rb-title { color: #fff; font-weight: 600; margin: 0; font-size: 18px; }
.rb-body { background: #f7f8fc; }
.rb-back { color: #560ce3; font-weight: 500; padding: 0 0 14px 0; text-decoration: none; }
.rb-back:hover { color: #3e08a3; }

/* Room cards in selection list */
.rb-room-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #ebeef5;
    transition: all .25s;
    height: 100%;
    display: flex;
    flex-direction: column;
    cursor: pointer;
}
.rb-room-card:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(15, 25, 50, .12); border-color: #d3d9e8; }
.rb-room-card .rb-room-img-wrap { position: relative; height: 180px; overflow: hidden; }
.rb-room-card .rb-room-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s; }
.rb-room-card:hover .rb-room-img-wrap img { transform: scale(1.06); }
.rb-room-card .rb-cap-badge {
    position: absolute; top: 12px; left: 12px;
    background: rgba(255, 255, 255, .96); color: #1d2540;
    padding: 5px 11px; border-radius: 20px; font-size: 12px; font-weight: 600;
    backdrop-filter: blur(4px);
}
.rb-room-card .rb-room-body { padding: 16px; display: flex; flex-direction: column; flex: 1; }
.rb-room-card .rb-room-name { font-size: 16px; font-weight: 700; color: #1d2540; margin: 0 0 6px; }
.rb-room-card .rb-room-features { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
.rb-room-card .rb-room-features span {
    background: #f4f6fb; color: #4a5670; padding: 3px 9px; border-radius: 12px; font-size: 11.5px;
}
.rb-room-card .rb-room-price { display: flex; align-items: baseline; gap: 6px; margin-top: auto; }
.rb-room-card .rb-room-price .amount { font-size: 22px; font-weight: 800; color: #560ce3; }
.rb-room-card .rb-room-price .per { font-size: 12px; color: #888; }
.rb-room-card .rb-room-base-hint { font-size: 11px; color: #6b7a99; margin-top: 4px; }
.rb-room-card .rb-select-btn {
    margin-top: 12px;
    background: #560ce3; color: #fff; border: none; padding: 9px 14px;
    border-radius: 8px; font-weight: 600; font-size: 14px; transition: background .15s;
}
.rb-room-card .rb-select-btn:hover { background: #3e08a3; }

/* Selected room card on right step */
.rb-selected-card {
    background: #fff; border-radius: 14px; padding: 16px; border: 1px solid #ebeef5;
    box-shadow: 0 4px 14px rgba(15, 25, 50, .04);
    position: sticky; top: 0;
}
.rb-selected-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; margin-bottom: 12px; }
.rb-selected-card h5 { font-size: 18px; font-weight: 700; color: #1d2540; margin: 0 0 4px; }
.rb-selected-card .rb-selected-price { font-size: 20px; font-weight: 800; color: #560ce3; }
.rb-selected-card .rb-selected-price small { font-size: 12px; color: #888; font-weight: 500; }

/* Form sections */
.rb-form { display: flex; flex-direction: column; gap: 14px; }
.rb-section {
    background: #fff; border: 1px solid #ebeef5; border-radius: 14px; padding: 16px;
}
.rb-section-title { font-size: 14px; font-weight: 700; color: #1d2540; margin: 0 0 12px; display: flex; align-items: center; }
.rb-section-title i { color: #560ce3; }
.rb-input {
    border: 1px solid #dde3ec; border-radius: 8px; padding: 9px 12px; font-size: 14px;
    transition: border-color .15s, box-shadow .15s;
}
.rb-input:focus { border-color: #560ce3; box-shadow: 0 0 0 3px rgba(86, 12, 227, .12); outline: none; }

.rb-base-hint {
    background: #fff7ed; border: 1px solid #fde0bd; color: #92400e;
    padding: 8px 12px; border-radius: 8px; font-size: 12.5px; margin-bottom: 12px;
}
.rb-base-hint i { margin-right: 6px; }

.rb-counter-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0; border-bottom: 1px solid #f0f2f7;
}
.rb-counter-row:last-child { border-bottom: none; }
.rb-counter-info strong { font-size: 14px; color: #1d2540; }
.rb-counter { display: flex; align-items: center; gap: 8px; }
.rb-counter input {
    width: 44px; text-align: center; border: 1px solid #dde3ec; border-radius: 8px; padding: 6px;
    font-weight: 600; color: #1d2540; background: #fff;
}
.rb-counter-btn {
    width: 32px; height: 32px; border-radius: 50%;
    border: 1px solid #dde3ec; background: #fff; color: #560ce3;
    font-size: 18px; line-height: 1; cursor: pointer; transition: all .15s;
    display: inline-flex; align-items: center; justify-content: center;
}
.rb-counter-btn:hover:not(:disabled) { background: #560ce3; color: #fff; border-color: #560ce3; }
.rb-counter-btn:disabled { opacity: .4; cursor: not-allowed; }

.rb-extra-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 0; border-bottom: 1px solid #f0f2f7;
}
.rb-extra-item:last-child { border-bottom: none; }
.rb-extra-item .form-check-input { margin: 0; cursor: pointer; }
.rb-extra-item .form-check-label { cursor: pointer; padding-left: 10px; }
.rb-extra-item .rb-extra-price { font-weight: 600; color: #560ce3; font-size: 13px; }

.rb-summary {
    background: linear-gradient(135deg, #f7f8fc 0%, #ebedf5 100%);
    border: 1px solid #e5e8f0; border-radius: 14px; padding: 14px 16px;
    margin-top: 6px;
}
.rb-summary-row { display: flex; justify-content: space-between; align-items: baseline; padding: 4px 0; font-size: 13.5px; color: #4a5670; }
.rb-summary-row--nights { border-top: 1px dashed #cdd3e0; padding-top: 8px; margin-top: 8px; }
.rb-summary-total {
    display: flex; justify-content: space-between; align-items: baseline;
    border-top: 2px solid #cdd3e0; padding-top: 10px; margin-top: 8px;
}
.rb-summary-total span { font-size: 14px; color: #1d2540; font-weight: 600; }
.rb-summary-total strong { font-size: 22px; color: #560ce3; font-weight: 800; }

.rb-submit {
    background: linear-gradient(135deg, #560ce3 0%, #7e3df5 100%);
    color: #fff; border: none; font-weight: 700; padding: 14px;
    border-radius: 12px; transition: transform .12s, box-shadow .15s;
    box-shadow: 0 6px 18px rgba(86, 12, 227, .25);
}
.rb-submit:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(86, 12, 227, .35); color: #fff; }
.rb-submit:disabled { opacity: .55; transform: none; cursor: not-allowed; }

@media (max-width: 768px) {
    .rb-selected-card { position: static; }
    .rb-section { padding: 12px; }
    .rb-summary-total strong { font-size: 18px; }
}
</style>

<script>
(function () {
    var rbServiceId = null;
    var rbRoomId = null;
    var rbRooms = [];
    var rbSelected = null;
    var rbCurrencySymbol = '@php echo addslashes(config("settings.currency_icon", "$")); @endphp';

    function fmt(amount) {
        if (amount === null || amount === undefined || isNaN(amount)) return '—';
        return rbCurrencySymbol + Number(amount).toFixed(2);
    }

    window.openRoomBookingPopup = function (serviceId) {
        rbServiceId = serviceId;
        document.getElementById('roomPopupLoading').classList.remove('d-none');
        document.getElementById('roomsList').classList.add('d-none');
        document.getElementById('roomBookingForm').classList.add('d-none');
        document.getElementById('bookingSuccess').classList.add('d-none');
        var stepTitle = document.querySelector('[data-rb-step-title]');
        if (stepTitle) stepTitle.textContent = '{{ __('translate.Select Room') }}';

        var modal = new bootstrap.Modal(document.getElementById('roomBookingModal'));
        modal.show();

        fetch('/tour-booking/services/' + serviceId + '/room-popup-data')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    rbRooms = data.rooms || [];
                    renderRoomsList();
                } else if (typeof toastr !== 'undefined') {
                    toastr.error(data.message || 'Failed to load rooms');
                }
            })
            .catch(function () {
                if (typeof toastr !== 'undefined') toastr.error('Failed to load rooms.');
            });
    };

    function renderRoomsList() {
        var container = document.getElementById('roomsContainer');
        container.innerHTML = '';

        if (!rbRooms.length) {
            container.innerHTML = '<div class="col-12 text-center py-5"><i class="fa fa-bed text-muted" style="font-size:60px"></i><h5 class="mt-3 text-muted">{{ __('translate.No rooms available') }}</h5></div>';
        } else {
            rbRooms.forEach(function (room) {
                var thumb = (room.media && room.media.length > 0) ? room.media[0].file_path : '/frontend/assets/img/shape/placeholder.png';
                var basePrice = parseFloat(room.discount_price || room.price_per_night) || 0;
                var capacity = room.max_guests || 2;
                var basePriceFor = room.base_price_for || capacity;
                var bedType = room.bed_type ? '<span><i class="fa fa-bed me-1"></i>' + room.bed_type + '</span>' : '';
                var sizeBadge = room.room_size ? '<span><i class="fa fa-ruler-combined me-1"></i>' + room.room_size + ' m²</span>' : '';

                var card = document.createElement('div');
                card.className = 'col-lg-4 col-md-6';
                card.innerHTML = ''
                    + '<div class="rb-room-card" data-room-id="' + room.id + '">'
                    +   '<div class="rb-room-img-wrap">'
                    +     '<img src="' + thumb + '" alt="' + (room.name || '') + '">'
                    +     '<span class="rb-cap-badge"><i class="fa fa-users me-1"></i>{{ __('translate.up to') }} ' + capacity + '</span>'
                    +   '</div>'
                    +   '<div class="rb-room-body">'
                    +     '<h5 class="rb-room-name">' + (room.name || '') + '</h5>'
                    +     '<div class="rb-room-features">' + sizeBadge + bedType + '</div>'
                    +     '<div class="rb-room-price"><span class="amount">' + fmt(basePrice) + '</span><span class="per">/ {{ __('translate.night') }}</span></div>'
                    +     '<div class="rb-room-base-hint"><i class="fa fa-info-circle me-1"></i>{{ __('translate.Price for') }} ' + basePriceFor + ' {{ __('translate.guests') }}</div>'
                    +     '<button type="button" class="rb-select-btn">{{ __('translate.Select This Room') }}</button>'
                    +   '</div>'
                    + '</div>';
                card.querySelector('.rb-room-card').addEventListener('click', function () { selectRoom(room.id); });
                container.appendChild(card);
            });
        }

        document.getElementById('roomPopupLoading').classList.add('d-none');
        document.getElementById('roomsList').classList.remove('d-none');
    }

    function selectRoom(roomId) {
        rbRoomId = roomId;
        rbSelected = rbRooms.find(function (r) { return r.id === roomId; });
        if (!rbSelected) { if (typeof toastr !== 'undefined') toastr.error('Room not found'); return; }

        document.getElementById('popup_service_id').value = rbServiceId;
        document.getElementById('popup_room_id').value = roomId;

        var today = new Date();
        var tomorrow = new Date(today); tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('popup_check_in').value = today.toISOString().slice(0, 10);
        document.getElementById('popup_check_out').value = tomorrow.toISOString().slice(0, 10);

        var basePriceFor = rbSelected.base_price_for || rbSelected.max_guests || 2;
        document.getElementById('popup_adults').value = basePriceFor;
        document.getElementById('popup_children').value = 0;

        var thumb = (rbSelected.media && rbSelected.media.length) ? rbSelected.media[0].file_path : '/frontend/assets/img/shape/placeholder.png';
        var basePrice = parseFloat(rbSelected.discount_price || rbSelected.price_per_night) || 0;
        document.getElementById('selectedRoomCard').innerHTML = ''
            + '<img src="' + thumb + '" alt="">'
            + '<h5>' + (rbSelected.name || '') + '</h5>'
            + '<div class="rb-selected-price">' + fmt(basePrice) + ' <small>/ {{ __('translate.night') }}</small></div>'
            + '<small class="text-muted d-block mt-2"><i class="fa fa-users me-1"></i>{{ __('translate.Max') }} ' + (rbSelected.max_guests || 2) + ' ' + '{{ __('translate.guests') }}</small>';

        renderBaseHint();
        renderOptionalExtras();
        bindCounters();

        document.getElementById('roomsList').classList.add('d-none');
        document.getElementById('roomBookingForm').classList.remove('d-none');
        var stepTitle = document.querySelector('[data-rb-step-title]');
        if (stepTitle) stepTitle.textContent = '{{ __('translate.Confirm Your Stay') }}';

        rbRecalc();
    }

    function renderBaseHint() {
        var basePriceFor = rbSelected.base_price_for || rbSelected.max_guests || 2;
        var basePrice = parseFloat(rbSelected.discount_price || rbSelected.price_per_night) || 0;
        var hint = document.getElementById('rb-base-hint');
        hint.innerHTML = '<i class="fa fa-info-circle"></i>{{ __('translate.This price') }} <strong>' + fmt(basePrice) + '</strong> {{ __('translate.is for') }} <strong>' + basePriceFor + ' ' + '{{ __('translate.guest(s)') }}</strong>. {{ __('translate.Extra adults/children will be added automatically below.') }}';
    }

    function renderOptionalExtras() {
        var wrap = document.getElementById('rb-extras-wrap');
        var list = document.getElementById('rb-optional-extras-list');
        list.innerHTML = '';
        var optionals = (rbSelected.extras || []).filter(function (e) { return e.type === 'optional'; });
        if (!optionals.length) { wrap.style.display = 'none'; return; }
        wrap.style.display = '';
        optionals.forEach(function (ex) {
            var row = document.createElement('div');
            row.className = 'rb-extra-item';
            row.innerHTML = ''
                + '<div class="form-check m-0 d-flex align-items-center">'
                +   '<input class="form-check-input rb-extra-cb" type="checkbox" data-extra-id="' + ex.id + '" data-extra-price="' + ex.price + '" data-extra-name="' + (ex.name || '').replace(/"/g, '&quot;') + '" id="rb-ex-' + ex.id + '">'
                +   '<label class="form-check-label" for="rb-ex-' + ex.id + '">' + (ex.name || '') + '</label>'
                + '</div>'
                + '<span class="rb-extra-price">+' + fmt(ex.price) + ' / {{ __('translate.night') }}</span>';
            row.querySelector('input').addEventListener('change', rbRecalc);
            list.appendChild(row);
        });
    }

    function bindCounters() {
        document.querySelectorAll('.rb-counter-btn').forEach(function (btn) {
            if (btn.dataset.bound) return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', function () {
                var target = btn.dataset.target;
                var input = document.getElementById('popup_' + target);
                if (!input) return;
                var min = parseInt(input.min, 10) || 0;
                var max = parseInt(input.max, 10) || 99;
                var v = parseInt(input.value, 10) || min;
                if (btn.dataset.action === 'inc' && v < max) v++;
                if (btn.dataset.action === 'dec' && v > min) v--;
                // total guests can't exceed max_guests
                var maxGuests = (rbSelected && rbSelected.max_guests) || 99;
                var adults = parseInt(document.getElementById('popup_adults').value, 10) || 0;
                var children = parseInt(document.getElementById('popup_children').value, 10) || 0;
                if (target === 'adults' && (v + children) > maxGuests) return;
                if (target === 'children' && (adults + v) > maxGuests) return;
                input.value = v;
                rbRecalc();
            });
        });
    }

    window.rbRecalc = function () {
        if (!rbSelected) return;
        var basePrice = parseFloat(rbSelected.discount_price || rbSelected.price_per_night) || 0;
        var basePriceFor = rbSelected.base_price_for || rbSelected.max_guests || 2;
        var adults = parseInt(document.getElementById('popup_adults').value, 10) || 1;
        var children = parseInt(document.getElementById('popup_children').value, 10) || 0;
        var checkIn = document.getElementById('popup_check_in').value;
        var checkOut = document.getElementById('popup_check_out').value;

        var nights = 0;
        if (checkIn && checkOut) {
            var d1 = new Date(checkIn), d2 = new Date(checkOut);
            nights = Math.max(0, Math.round((d2 - d1) / 86400000));
        }

        var extraAdults = Math.max(0, adults - basePriceFor);
        var extraChildren = children;

        var adultExtra = (rbSelected.extras || []).find(function (e) { return e.type === 'adult'; });
        var childExtra = (rbSelected.extras || []).find(function (e) { return e.type === 'child'; });

        var extrasSummary = '';
        var extrasPerNight = 0;

        if (extraAdults > 0 && adultExtra) {
            var aP = adultExtra.price * extraAdults;
            extrasPerNight += aP;
            extrasSummary += '<div class="rb-summary-row"><span>' + extraAdults + ' × ' + adultExtra.name + '</span><span>+' + fmt(aP) + '</span></div>';
        } else if (extraAdults > 0) {
            extrasSummary += '<div class="rb-summary-row text-warning"><span>' + extraAdults + ' {{ __('translate.extra adult(s)') }}</span><span>—</span></div>';
        }

        if (extraChildren > 0 && childExtra) {
            var cP = childExtra.price * extraChildren;
            extrasPerNight += cP;
            extrasSummary += '<div class="rb-summary-row"><span>' + extraChildren + ' × ' + childExtra.name + '</span><span>+' + fmt(cP) + '</span></div>';
        } else if (extraChildren > 0) {
            extrasSummary += '<div class="rb-summary-row text-muted"><span>' + extraChildren + ' {{ __('translate.children') }}</span><span>—</span></div>';
        }

        document.querySelectorAll('.rb-extra-cb:checked').forEach(function (cb) {
            var p = parseFloat(cb.dataset.extraPrice) || 0;
            extrasPerNight += p;
            extrasSummary += '<div class="rb-summary-row"><span>' + cb.dataset.extraName + '</span><span>+' + fmt(p) + '</span></div>';
        });

        var perNight = basePrice + extrasPerNight;
        var total = perNight * nights;

        document.getElementById('rb-base-amount').textContent = fmt(basePrice);
        document.getElementById('rb-base-detail').textContent = '({{ __('translate.for') }} ' + basePriceFor + ' {{ __('translate.guest(s)') }})';
        document.getElementById('rb-extras-summary').innerHTML = extrasSummary;
        document.getElementById('rb-nights').textContent = nights ? nights : '—';
        document.getElementById('rb-total').textContent = nights ? fmt(total) : '—';
        document.getElementById('submitBookingBtn').disabled = !nights;
    };

    window.backToRoomSelection = function () {
        document.getElementById('roomBookingForm').classList.add('d-none');
        document.getElementById('roomsList').classList.remove('d-none');
        var stepTitle = document.querySelector('[data-rb-step-title]');
        if (stepTitle) stepTitle.textContent = '{{ __('translate.Select Room') }}';
    };

    window.submitGuestBooking = function (event) {
        event.preventDefault();
        var form = document.getElementById('guestBookingForm');
        var btn = document.getElementById('submitBookingBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __('translate.Processing...') }}';

        var fd = new FormData(form);
        // Append selected optional extras
        document.querySelectorAll('.rb-extra-cb:checked').forEach(function (cb) {
            fd.append('optional_extras[]', cb.dataset.extraId);
        });

        fetch('/tour-booking/guest-booking', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: fd
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check me-2"></i>{{ __('translate.Confirm Booking') }}';
            if (data.success) {
                document.getElementById('roomBookingForm').classList.add('d-none');
                document.getElementById('bookingSuccess').classList.remove('d-none');
                var det = '';
                if (data.booking_code) det += '<p class="mb-2"><strong>{{ __('translate.Booking Code') }}:</strong> ' + data.booking_code + '</p>';
                if (data.total) det += '<p class="mb-2"><strong>{{ __('translate.Total') }}:</strong> ' + fmt(data.total) + '</p>';
                if (data.message) det += '<p class="text-muted">' + data.message + '</p>';
                document.getElementById('bookingDetails').innerHTML = det;
            } else if (typeof toastr !== 'undefined') {
                toastr.error(data.message || 'Failed to create booking');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check me-2"></i>{{ __('translate.Confirm Booking') }}';
            if (typeof toastr !== 'undefined') toastr.error('Booking failed. Please try again.');
        });
    };
})();
</script>
