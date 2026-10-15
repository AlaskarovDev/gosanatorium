{{-- Room Selection & Booking Popup Modal --}}
<div class="modal fade" id="roomBookingModal" tabindex="-1" aria-labelledby="roomBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roomBookingModalLabel">
                    <i class="fa fa-bed me-2"></i>
                    {{ __('translate.Select Your Room') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Date Selection Header -->
                <div class="booking-dates-header sticky-top bg-light p-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="small fw-bold text-muted">{{ __('translate.Check-in Date') }}</label>
                                <input type="date" id="popup_check_in" class="form-control" 
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="small fw-bold text-muted">{{ __('translate.Check-out Date') }}</label>
                                <input type="date" id="popup_check_out" class="form-control" 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-end h-100">
                                <div class="booking-summary bg-white rounded p-2 w-100 text-center">
                                    <span class="nights-count fw-bold text-primary fs-5" id="popup_nights_count">0</span>
                                    <span class="text-muted">{{ __('translate.Nights') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Loading State -->
                <div id="roomsLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('translate.Loading...') }}</span>
                    </div>
                    <p class="mt-2">{{ __('translate.Loading available rooms...') }}</p>
                </div>

                <!-- Rooms List -->
                <div id="roomsList" class="p-3" style="display: none;">
                    <!-- Rooms will be dynamically inserted here -->
                </div>

                <!-- No Rooms Available -->
                <div id="noRoomsMessage" class="text-center py-5" style="display: none;">
                    <i class="fa fa-bed fa-3x text-muted mb-3"></i>
                    <h5>{{ __('translate.No rooms available') }}</h5>
                    <p class="text-muted">{{ __('translate.Please try different dates or contact us directly.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Guest Booking Form Modal --}}
<div class="modal fade" id="guestBookingModal" tabindex="-1" aria-labelledby="guestBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="guestBookingModalLabel">
                    <i class="fa fa-user-plus me-2"></i>
                    {{ __('translate.Complete Your Booking') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Booking Summary -->
                <div class="booking-summary-card bg-light rounded p-3 mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="mb-2" id="summary_room_name">-</h6>
                            <p class="mb-1 small text-muted">
                                <i class="fa fa-calendar me-1"></i>
                                <span id="summary_dates">-</span>
                                (<span id="summary_nights">0</span> {{ __('translate.nights') }})
                            </p>
                            <p class="mb-0 small text-muted">
                                <i class="fa fa-user me-1"></i>
                                <span id="summary_guests">-</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="total-price">
                                <small class="text-muted d-block">{{ __('translate.Total Price') }}</small>
                                <span class="fs-4 fw-bold text-success" id="summary_total_price">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guest Information Form -->
                <form id="guestBookingForm">
                    <input type="hidden" name="service_id" id="form_service_id">
                    <input type="hidden" name="room_id" id="form_room_id">
                    <input type="hidden" name="check_in_date" id="form_check_in_date">
                    <input type="hidden" name="check_out_date" id="form_check_out_date">
                    <input type="hidden" name="adults" id="form_adults">
                    <input type="hidden" name="children" id="form_children">

                    <h6 class="mb-3">{{ __('translate.Guest Information') }}</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('translate.First Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" required
                                    placeholder="{{ __('translate.Enter your first name') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('translate.Last Name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" required
                                    placeholder="{{ __('translate.Enter your last name') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('translate.Email Address') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required
                                    placeholder="{{ __('translate.your@email.com') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('translate.Phone Number') }} <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" required
                                    placeholder="{{ __('translate.+994 XX XXX XX XX') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('translate.City') }}</label>
                                <input type="text" name="city" class="form-control"
                                    placeholder="{{ __('translate.Enter your city') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('translate.Country') }}</label>
                                <input type="text" name="country" class="form-control"
                                    placeholder="{{ __('translate.Enter your country') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('translate.Address') }}</label>
                        <input type="text" name="address" class="form-control"
                            placeholder="{{ __('translate.Enter your address') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('translate.Special Requests') }}</label>
                        <textarea name="special_requests" class="form-control" rows="3"
                            placeholder="{{ __('translate.Any special requests or notes for your stay...') }}"></textarea>
                    </div>

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        {{ __('translate.No payment required at this time. You will receive a confirmation email once your booking is approved.') }}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('translate.Cancel') }}
                </button>
                <button type="button" class="btn btn-success" id="submitBookingBtn" onclick="submitGuestBooking()">
                    <i class="fa fa-check me-2"></i>
                    {{ __('translate.Confirm Booking') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Booking Success Modal --}}
<div class="modal fade" id="bookingSuccessModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body py-5">
                <div class="success-checkmark mb-4">
                    <div class="check-icon">
                        <span class="icon-line line-tip"></span>
                        <span class="icon-line line-long"></span>
                        <div class="icon-circle"></div>
                        <div class="icon-fix"></div>
                    </div>
                </div>
                <h4 class="text-success mb-3">{{ __('translate.Booking Submitted Successfully!') }}</h4>
                <p class="text-muted mb-3" id="success_message">
                    {{ __('translate.Your booking request has been submitted. You will receive a confirmation email once your reservation is approved.') }}
                </p>
                <div class="booking-details bg-light rounded p-3 mb-3">
                    <p class="mb-1"><strong>{{ __('translate.Booking Code') }}:</strong> <span id="success_booking_code">-</span></p>
                    <p class="mb-0"><strong>{{ __('translate.Status') }}:</strong> <span class="badge bg-warning" id="success_status">{{ __('translate.Pending') }}</span></p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="location.reload()">
                    {{ __('translate.Close') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Room Card Template --}}
<template id="roomCardTemplate">
    <div class="room-card card mb-3 shadow-sm" data-room-id="">
        <div class="row g-0">
            <!-- Room Image/Video Section -->
            <div class="col-md-4">
                <div class="room-media position-relative h-100">
                    <div class="room-gallery h-100">
                        <img src="" class="room-thumbnail img-fluid h-100 w-100" style="object-fit: cover; min-height: 200px;" alt="">
                    </div>
                    <!-- Video Play Button (if video exists) -->
                    <div class="video-play-btn position-absolute top-50 start-50 translate-middle" style="display: none;">
                        <button class="btn btn-light rounded-circle p-2" onclick="playRoomVideo(this)">
                            <i class="fa fa-play text-primary"></i>
                        </button>
                    </div>
                    <!-- Discount Badge -->
                    <div class="discount-badge position-absolute top-0 start-0 m-2" style="display: none;">
                        <span class="badge bg-danger"></span>
                    </div>
                </div>
            </div>

            <!-- Room Details Section -->
            <div class="col-md-5">
                <div class="card-body h-100 d-flex flex-column">
                    <h5 class="room-name card-title mb-2"></h5>
                    <p class="room-short-desc text-muted small mb-2"></p>
                    
                    <div class="room-features mb-2">
                        <span class="badge bg-light text-dark me-1 mb-1">
                            <i class="fa fa-user me-1"></i>
                            <span class="max-adults"></span> {{ __('translate.Adults') }}
                        </span>
                        <span class="badge bg-light text-dark me-1 mb-1">
                            <i class="fa fa-child me-1"></i>
                            <span class="max-children"></span> {{ __('translate.Children') }}
                        </span>
                        <span class="badge bg-light text-dark me-1 mb-1 room-size-badge" style="display: none;">
                            <i class="fa fa-expand me-1"></i>
                            <span class="room-size"></span> m²
                        </span>
                        <span class="badge bg-light text-dark me-1 mb-1 bed-info-badge" style="display: none;">
                            <i class="fa fa-bed me-1"></i>
                            <span class="bed-info"></span>
                        </span>
                    </div>

                    <div class="room-amenities mt-auto small">
                        <!-- Amenities will be added dynamically -->
                    </div>
                </div>
            </div>

            <!-- Price & Book Section -->
            <div class="col-md-3">
                <div class="card-body h-100 d-flex flex-column justify-content-center text-center border-start">
                    <div class="price-section mb-3">
                        <div class="original-price text-muted text-decoration-line-through small" style="display: none;"></div>
                        <div class="current-price">
                            <span class="price-amount fs-4 fw-bold text-primary"></span>
                            <span class="price-period text-muted small">/ {{ __('translate.night') }}</span>
                        </div>
                    </div>

                    <div class="total-price-section mb-3" style="display: none;">
                        <small class="text-muted">{{ __('translate.Total for') }} <span class="total-nights"></span> {{ __('translate.nights') }}</small>
                        <div class="total-amount fs-5 fw-bold text-success"></div>
                    </div>

                    <!-- Guest Selection -->
                    <div class="guest-selection mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="small text-muted">{{ __('translate.Adults') }}</label>
                                <select class="form-select form-select-sm room-adults">
                                    <!-- Options will be added dynamically -->
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted">{{ __('translate.Children') }}</label>
                                <select class="form-select form-select-sm room-children">
                                    <!-- Options will be added dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary book-room-btn w-100">
                        <i class="fa fa-check-circle me-1"></i>
                        {{ __('translate.Book Now') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    /* Room Booking Popup Styles */
    #roomBookingModal .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }

    .room-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .room-card:hover {
        border-color: var(--tg-theme-primary);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1) !important;
    }

    .room-card.selected {
        border-color: var(--tg-theme-primary);
        background-color: rgba(var(--tg-theme-primary-rgb), 0.05);
    }

    .room-gallery {
        position: relative;
        overflow: hidden;
        border-radius: 0.375rem 0 0 0.375rem;
    }

    .room-amenities .badge {
        font-weight: normal;
    }

    /* Success Animation */
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto;
    }

    .check-icon {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4caf50;
    }

    .check-icon::before {
        top: 3px;
        left: -2px;
        width: 30px;
        transform-origin: 100% 50%;
        border-radius: 100px 0 0 100px;
    }

    .check-icon::after {
        top: 0;
        left: 30px;
        width: 60px;
        transform-origin: 0 50%;
        border-radius: 0 100px 100px 0;
        animation: rotate-circle 4.25s ease-in;
    }

    .check-icon::before,
    .check-icon::after {
        content: '';
        height: 100px;
        position: absolute;
        background: #fff;
        transform: rotate(-45deg);
    }

    .icon-line {
        height: 5px;
        background-color: #4caf50;
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }

    .icon-line.line-tip {
        top: 46px;
        left: 14px;
        width: 25px;
        transform: rotate(45deg);
        animation: icon-line-tip 0.75s;
    }

    .icon-line.line-long {
        top: 38px;
        right: 8px;
        width: 47px;
        transform: rotate(-45deg);
        animation: icon-line-long 0.75s;
    }

    @keyframes icon-line-tip {
        0% { width: 0; left: 1px; top: 19px; }
        54% { width: 0; left: 1px; top: 19px; }
        70% { width: 50px; left: -8px; top: 37px; }
        84% { width: 17px; left: 21px; top: 48px; }
        100% { width: 25px; left: 14px; top: 45px; }
    }

    @keyframes icon-line-long {
        0% { width: 0; right: 46px; top: 54px; }
        65% { width: 0; right: 46px; top: 54px; }
        84% { width: 55px; right: 0; top: 35px; }
        100% { width: 47px; right: 8px; top: 38px; }
    }
</style>

<script>
    // Room Booking Popup Script
    let serviceId = null;
    let roomsData = [];
    let selectedRoom = null;

    // Initialize popup when Book Now is clicked
    function openRoomBookingPopup(svcId) {
        serviceId = svcId;
        
        // Reset states
        document.getElementById('roomsList').style.display = 'none';
        document.getElementById('noRoomsMessage').style.display = 'none';
        document.getElementById('roomsLoading').style.display = 'block';
        
        // Set default dates
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        document.getElementById('popup_check_in').value = formatDate(today);
        document.getElementById('popup_check_out').value = formatDate(tomorrow);
        updateNightsCount();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('roomBookingModal'));
        modal.show();
        
        // Load rooms
        loadRooms();
    }

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function loadRooms() {
        fetch(`/booking/services/${serviceId}/room-popup-data`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    roomsData = data.rooms;
                    renderRooms();
                } else {
                    showNoRooms();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNoRooms();
            });
    }

    function renderRooms() {
        const container = document.getElementById('roomsList');
        container.innerHTML = '';

        if (roomsData.length === 0) {
            showNoRooms();
            return;
        }

        const template = document.getElementById('roomCardTemplate');

        roomsData.forEach(room => {
            const card = template.content.cloneNode(true);
            const cardElement = card.querySelector('.room-card');
            
            cardElement.dataset.roomId = room.id;
            cardElement.querySelector('.room-thumbnail').src = room.thumbnail;
            cardElement.querySelector('.room-thumbnail').alt = room.name;
            cardElement.querySelector('.room-name').textContent = room.name;
            cardElement.querySelector('.room-short-desc').textContent = room.short_description || '';
            cardElement.querySelector('.max-adults').textContent = room.max_adults;
            cardElement.querySelector('.max-children').textContent = room.max_children;
            
            // Room size
            if (room.room_size) {
                cardElement.querySelector('.room-size-badge').style.display = '';
                cardElement.querySelector('.room-size').textContent = room.room_size;
            }
            
            // Bed info
            if (room.bed_type_display) {
                cardElement.querySelector('.bed-info-badge').style.display = '';
                cardElement.querySelector('.bed-info').textContent = `${room.bed_count} ${room.bed_type_display}`;
            }
            
            // Price
            const priceAmount = cardElement.querySelector('.price-amount');
            priceAmount.textContent = formatCurrency(room.effective_price);
            
            // Discount
            if (room.discount_price && room.discount_percentage > 0) {
                cardElement.querySelector('.original-price').style.display = '';
                cardElement.querySelector('.original-price').textContent = formatCurrency(room.price_per_night);
                cardElement.querySelector('.discount-badge').style.display = '';
                cardElement.querySelector('.discount-badge .badge').textContent = `-${room.discount_percentage}%`;
            }
            
            // Video button
            if (room.videos && room.videos.length > 0) {
                cardElement.querySelector('.video-play-btn').style.display = '';
                cardElement.querySelector('.video-play-btn button').dataset.videoUrl = room.videos[0].url;
            }
            
            // Guest selects
            const adultsSelect = cardElement.querySelector('.room-adults');
            const childrenSelect = cardElement.querySelector('.room-children');
            
            for (let i = 1; i <= room.max_adults; i++) {
                adultsSelect.add(new Option(i, i, i === 1, i === 1));
            }
            
            for (let i = 0; i <= room.max_children; i++) {
                childrenSelect.add(new Option(i, i, i === 0, i === 0));
            }
            
            // Update price on guest change
            adultsSelect.addEventListener('change', () => updateRoomTotal(cardElement, room));
            childrenSelect.addEventListener('change', () => updateRoomTotal(cardElement, room));
            
            // Book button
            cardElement.querySelector('.book-room-btn').addEventListener('click', () => selectRoom(room, cardElement));
            
            // Amenities
            if (room.amenities && room.amenities.length > 0) {
                const amenitiesContainer = cardElement.querySelector('.room-amenities');
                room.amenities.slice(0, 5).forEach(amenityId => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-light text-dark me-1 mb-1';
                    badge.innerHTML = `<i class="fa fa-check text-success me-1"></i>Amenity`;
                    amenitiesContainer.appendChild(badge);
                });
            }

            container.appendChild(card);
            
            // Initial total calculation
            updateRoomTotal(cardElement, room);
        });

        document.getElementById('roomsLoading').style.display = 'none';
        container.style.display = 'block';
    }

    function updateRoomTotal(cardElement, room) {
        const nights = getNightsCount();
        const totalSection = cardElement.querySelector('.total-price-section');
        
        if (nights > 0) {
            const total = room.effective_price * nights;
            totalSection.querySelector('.total-nights').textContent = nights;
            totalSection.querySelector('.total-amount').textContent = formatCurrency(total);
            totalSection.style.display = '';
        } else {
            totalSection.style.display = 'none';
        }
    }

    function selectRoom(room, cardElement) {
        const checkIn = document.getElementById('popup_check_in').value;
        const checkOut = document.getElementById('popup_check_out').value;
        const nights = getNightsCount();
        
        if (!checkIn || !checkOut || nights <= 0) {
            toastr.error('{{ __('translate.Please select valid dates') }}');
            return;
        }

        const adults = parseInt(cardElement.querySelector('.room-adults').value);
        const children = parseInt(cardElement.querySelector('.room-children').value);
        const total = room.effective_price * nights;

        // Store selected room data
        selectedRoom = {
            room: room,
            checkIn: checkIn,
            checkOut: checkOut,
            nights: nights,
            adults: adults,
            children: children,
            total: total
        };

        // Fill the booking form
        document.getElementById('form_service_id').value = serviceId;
        document.getElementById('form_room_id').value = room.id;
        document.getElementById('form_check_in_date').value = checkIn;
        document.getElementById('form_check_out_date').value = checkOut;
        document.getElementById('form_adults').value = adults;
        document.getElementById('form_children').value = children;

        // Update summary
        document.getElementById('summary_room_name').textContent = room.name;
        document.getElementById('summary_dates').textContent = `${formatDisplayDate(checkIn)} - ${formatDisplayDate(checkOut)}`;
        document.getElementById('summary_nights').textContent = nights;
        document.getElementById('summary_guests').textContent = `${adults} {{ __('translate.Adults') }}, ${children} {{ __('translate.Children') }}`;
        document.getElementById('summary_total_price').textContent = formatCurrency(total);

        // Hide room modal and show booking form
        bootstrap.Modal.getInstance(document.getElementById('roomBookingModal')).hide();
        
        setTimeout(() => {
            const bookingModal = new bootstrap.Modal(document.getElementById('guestBookingModal'));
            bookingModal.show();
        }, 300);
    }

    function submitGuestBooking() {
        const form = document.getElementById('guestBookingForm');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Validate required fields
        if (!data.first_name || !data.last_name || !data.email || !data.phone) {
            toastr.error('{{ __('translate.Please fill in all required fields') }}');
            return;
        }

        // Disable button
        const btn = document.getElementById('submitBookingBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{ __('translate.Processing...') }}';

        fetch('/booking/guest-booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check me-2"></i>{{ __('translate.Confirm Booking') }}';

            if (result.success) {
                // Hide booking form
                bootstrap.Modal.getInstance(document.getElementById('guestBookingModal')).hide();
                
                // Show success modal
                document.getElementById('success_booking_code').textContent = result.booking.code;
                document.getElementById('success_message').textContent = result.message;
                
                setTimeout(() => {
                    const successModal = new bootstrap.Modal(document.getElementById('bookingSuccessModal'));
                    successModal.show();
                }, 300);
            } else {
                toastr.error(result.message || '{{ __('translate.An error occurred') }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check me-2"></i>{{ __('translate.Confirm Booking') }}';
            toastr.error('{{ __('translate.An error occurred. Please try again.') }}');
        });
    }

    function showNoRooms() {
        document.getElementById('roomsLoading').style.display = 'none';
        document.getElementById('roomsList').style.display = 'none';
        document.getElementById('noRoomsMessage').style.display = 'block';
    }

    function getNightsCount() {
        const checkIn = new Date(document.getElementById('popup_check_in').value);
        const checkOut = new Date(document.getElementById('popup_check_out').value);
        return Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
    }

    function updateNightsCount() {
        const nights = getNightsCount();
        document.getElementById('popup_nights_count').textContent = nights > 0 ? nights : 0;
        
        // Update all room totals
        if (roomsData.length > 0) {
            document.querySelectorAll('.room-card').forEach((cardElement, index) => {
                updateRoomTotal(cardElement, roomsData[index]);
            });
        }
    }

    function formatCurrency(amount) {
        return '{{ default_currency()['currency_icon'] }}' + parseFloat(amount).toFixed(2);
    }

    function formatDisplayDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('{{ app()->getLocale() }}', { day: 'numeric', month: 'short', year: 'numeric' });
    }

    function playRoomVideo(btn) {
        const videoUrl = btn.dataset.videoUrl;
        if (videoUrl) {
            window.open(videoUrl, '_blank');
        }
    }

    // Date change listeners
    document.getElementById('popup_check_in').addEventListener('change', function() {
        const checkIn = new Date(this.value);
        const minCheckOut = new Date(checkIn);
        minCheckOut.setDate(minCheckOut.getDate() + 1);
        
        document.getElementById('popup_check_out').min = formatDate(minCheckOut);
        
        if (new Date(document.getElementById('popup_check_out').value) <= checkIn) {
            document.getElementById('popup_check_out').value = formatDate(minCheckOut);
        }
        
        updateNightsCount();
    });

    document.getElementById('popup_check_out').addEventListener('change', updateNightsCount);
</script>
