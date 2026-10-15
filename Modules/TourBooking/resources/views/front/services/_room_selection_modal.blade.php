{{-- Room Selection Modal - Place this at the END of the page, before </body> --}}
<div class="modal fade" id="roomSelectionModal" tabindex="-1" aria-labelledby="roomSelectionModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="position: relative; z-index: 99999;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roomSelectionModalLabel">
                    <i class="fa-solid fa-bed me-2"></i>{{ __('translate.Select Your Room') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                {{-- Date Selection --}}
                <div class="row mb-4 sticky-top bg-white py-3" style="z-index: 100;">
                    <div class="col-md-3 col-6 mb-2">
                        <label class="form-label fw-bold">{{ __('translate.Check-in Date') }}</label>
                        <input type="date" class="form-control" id="modal_check_in" 
                               min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label class="form-label fw-bold">{{ __('translate.Check-out Date') }}</label>
                        <input type="date" class="form-control" id="modal_check_out" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label class="form-label fw-bold">{{ __('translate.Guests') }}</label>
                        <select class="form-select" id="modal_guests">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Guest' : 'Guests' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 col-6 mb-2">
                        <label class="form-label fw-bold">{{ __('translate.Nights') }}</label>
                        <input type="text" class="form-control bg-light" id="modal_nights" value="1" readonly>
                    </div>
                </div>

                <hr>

                {{-- Room List --}}
                <div class="room-list">
                    @php
                        $rooms = $service->rooms()->where('status', true)->orderBy('display_order')->get();
                    @endphp
                    
                    @forelse($rooms as $room)
                        <div class="room-card card mb-3 room-item" data-max-guests="{{ $room->max_guests }}" data-room-id="{{ $room->id }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    {{-- Room Image --}}
<div class="col-lg-4 col-md-4 col-12 mb-3 mb-md-0">
    <div class="room-image-wrapper position-relative">
        @if($room->thumbnail_url)
            <img src="{{ $room->thumbnail_url }}" 
                 alt="{{ $room->name }}" 
                 class="img-fluid rounded"
                 style="width: 100%; height: 180px; object-fit: cover;">
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                 style="width: 100%; height: 180px;">
                <i class="fa-solid fa-image fa-3x text-muted"></i>
            </div>
        @endif
        
        @if($room->media && $room->media->count() > 1)
            <span class="badge bg-dark position-absolute bottom-0 end-0 m-2">
                <i class="fa-solid fa-images me-1"></i>{{ $room->media->count() }}
            </span>
        @endif
    </div>
</div>

                                    {{-- Room Details --}}
                                    <div class="col-lg-5 col-md-4 col-12 mb-3 mb-md-0">
                                        <h5 class="room-name mb-2">{{ $room->name }}</h5>
                                        
                                        <div class="room-specs mb-2">
                                            @if($room->room_size)
                                                <span class="badge bg-light text-dark me-1 mb-1">
                                                    <i class="fa-solid fa-ruler-combined me-1"></i>{{ $room->room_size }} m²
                                                </span>
                                            @endif
                                            @if($room->bed_type)
                                                <span class="badge bg-light text-dark me-1 mb-1">
                                                    <i class="fa-solid fa-bed me-1"></i>{{ $room->bed_type }}
                                                </span>
                                            @endif
                                            <span class="badge bg-light text-dark mb-1">
                                                <i class="fa-solid fa-user me-1"></i>{{ $room->max_guests }} Guests
                                            </span>
                                        </div>

                                        @if($room->description)
                                            <p class="room-description text-muted small mb-2">
                                                {{ Str::limit($room->description, 100) }}
                                            </p>
                                        @endif

                                        @if($room->features && is_array($room->features) && count($room->features) > 0)
                                            <div class="room-features">
                                                @foreach(array_slice($room->features, 0, 4) as $feature)
                                                    <span class="text-success small me-2 d-inline-block">
                                                        <i class="fa-solid fa-check me-1"></i>{{ $feature }}
                                                    </span>
                                                @endforeach
                                                @if(count($room->features) > 4)
                                                    <span class="text-muted small">+{{ count($room->features) - 4 }} more</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Room Price & Select --}}
                                    <div class="col-lg-3 col-md-4 col-12">
                                        <div class="room-price-box p-3 bg-light rounded text-center">
                                            @php
                                                $effectivePrice = method_exists($room, 'priceForDate')
                                                    ? ($room->current_price_per_night ?? $room->discount_price ?? $room->price_per_night)
                                                    : ($room->discount_price ?? $room->price_per_night);
                                            @endphp
                                            
                                            @if($room->discount_price)
                                                <del class="text-muted d-block small">{{ currency($room->price_per_night) }}</del>
                                            @endif
                                            
                                            <span class="h4 text-primary d-block room-base-price" data-price="{{ $effectivePrice }}">
                                                {{ currency($effectivePrice) }}
                                            </span>
                                            <small class="text-muted">/ per night</small>
                                            
                                            <div class="room-total-price mt-2">
                                                <strong>Total: </strong>
                                                <span class="total-price-value text-success fw-bold">
                                                    {{ currency($effectivePrice) }}
                                                </span>
                                            </div>

                                            @if($room->quantity <= 3 && $room->quantity > 0)
                                                <small class="text-danger d-block mt-1">
                                                    <i class="fa-solid fa-fire me-1"></i>Only {{ $room->quantity }} left!
                                                </small>
                                            @endif

                                            <button type="button" class="btn btn-primary w-100 mt-2 select-room-btn"
                                                    data-room-id="{{ $room->id }}"
                                                    data-room-name="{{ $room->name }}"
                                                    data-room-price="{{ $effectivePrice }}">
                                                <i class="fa-solid fa-check me-1"></i>Select
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">
                            <i class="fa-solid fa-info-circle me-2"></i>No rooms available
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Force Modal to Front */
    .modal-backdrop {
        z-index: 99998 !important;
    }
    
    #roomSelectionModal {
        z-index: 99999 !important;
    }
    
    #roomSelectionModal .modal-dialog {
        z-index: 99999 !important;
        pointer-events: auto;
    }
    
    #roomSelectionModal .modal-content {
        pointer-events: auto;
        box-shadow: 0 10px 50px rgba(0,0,0,0.3);
    }

    .room-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
    }
    
    .room-card:hover {
        border-color: #007bff;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .room-image-wrapper {
        overflow: hidden;
        border-radius: 8px;
    }
    
    .room-image-wrapper img {
        transition: transform 0.3s ease;
    }
    
    .room-card:hover .room-image-wrapper img {
        transform: scale(1.05);
    }
    
    .room-price-box {
        border: 1px solid #e0e0e0;
    }
    
    .select-room-btn {
        transition: all 0.3s ease;
    }
    
    .select-room-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.4);
    }
    
    #roomSelectionModal .modal-content {
        border-radius: 15px;
        overflow: hidden;
        border: none;
    }
    
    #roomSelectionModal .modal-header {
        border-bottom: none;
    }
    
    .room-item.hidden-room {
        display: none !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        #roomSelectionModal .modal-dialog {
            margin: 10px;
        }
        .room-price-box {
            margin-top: 15px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate nights when dates change
    function calculateNights() {
        var checkInEl = document.getElementById('modal_check_in');
        var checkOutEl = document.getElementById('modal_check_out');
        var nightsEl = document.getElementById('modal_nights');
        
        if (!checkInEl || !checkOutEl || !nightsEl) return;
        
        var checkIn = new Date(checkInEl.value);
        var checkOut = new Date(checkOutEl.value);
        
        if (checkIn && checkOut && checkOut > checkIn) {
            var nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            nightsEl.value = nights;
            updateTotalPrices(nights);
        } else {
            nightsEl.value = 1;
            updateTotalPrices(1);
        }
    }

    // Update total prices for all rooms
    function updateTotalPrices(nights) {
        document.querySelectorAll('.room-item').forEach(function(room) {
            var basePriceEl = room.querySelector('.room-base-price');
            var totalPriceEl = room.querySelector('.total-price-value');
            
            if (basePriceEl && totalPriceEl) {
                var basePrice = parseFloat(basePriceEl.dataset.price);
                var total = basePrice * nights;
                totalPriceEl.textContent = '$' + total.toFixed(2);
            }
        });
    }

    // Filter rooms by guest count
    function filterRoomsByGuests() {
        var guestsEl = document.getElementById('modal_guests');
        if (!guestsEl) return;
        
        var selectedGuests = parseInt(guestsEl.value);
        
        document.querySelectorAll('.room-item').forEach(function(room) {
            var maxGuests = parseInt(room.dataset.maxGuests);
            if (maxGuests >= selectedGuests) {
                room.classList.remove('hidden-room');
            } else {
                room.classList.add('hidden-room');
            }
        });
    }

    // Event listeners for date inputs
    var checkInInput = document.getElementById('modal_check_in');
    var checkOutInput = document.getElementById('modal_check_out');
    var guestsSelect = document.getElementById('modal_guests');
    
    if (checkInInput) {
        checkInInput.addEventListener('change', function() {
            // Ensure check-out is after check-in
            var checkIn = this.value;
            var checkOut = checkOutInput ? checkOutInput.value : '';
            
            if (checkIn && checkOut && checkOut <= checkIn) {
                var nextDay = new Date(checkIn);
                nextDay.setDate(nextDay.getDate() + 1);
                if (checkOutInput) {
                    checkOutInput.value = nextDay.toISOString().split('T')[0];
                }
            }
            calculateNights();
        });
    }
    
    if (checkOutInput) {
        checkOutInput.addEventListener('change', calculateNights);
    }
    
    if (guestsSelect) {
        guestsSelect.addEventListener('change', filterRoomsByGuests);
    }

    // Select room button click
    document.querySelectorAll('.select-room-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var roomId = this.dataset.roomId;
            var checkIn = document.getElementById('modal_check_in').value;
            var checkOut = document.getElementById('modal_check_out').value;
            var guests = document.getElementById('modal_guests').value;
            var nights = document.getElementById('modal_nights').value;

            // Validate dates
            if (!checkIn || !checkOut) {
                alert('Please select check-in and check-out dates');
                return;
            }

            // Redirect to booking form with parameters
            var url = '{{ route("front.tourbooking.guest-booking-form", $service->slug) }}';
            url += '?room_id=' + roomId;
            url += '&check_in=' + checkIn;
            url += '&check_out=' + checkOut;
            url += '&guests=' + guests;
            url += '&nights=' + nights;

            window.location.href = url;
        });
    });

    // Initialize on modal open
    var roomModal = document.getElementById('roomSelectionModal');
    if (roomModal) {
        roomModal.addEventListener('shown.bs.modal', function() {
            calculateNights();
            filterRoomsByGuests();
            
            // Force focus on modal
            this.focus();
        });
        
        // Fix backdrop z-index when modal shows
        roomModal.addEventListener('show.bs.modal', function() {
            setTimeout(function() {
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.style.zIndex = '99998';
                }
            }, 10);
        });
    }
});
</script>