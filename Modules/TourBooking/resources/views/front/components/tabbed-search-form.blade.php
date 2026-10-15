{{-- 
    Service Type Tabbed Search Form Component
    Bu component əsas səhifədə service type-lara görə tab-lı axtarış forması göstərir
    
    Fayl yeri: resources/views/tourbooking/front/components/tabbed-search-form.blade.php
    
    İstifadə: @include('tourbooking::front.components.tabbed-search-form')
--}}

@php
    // Active service types - show_on_homepage true olanlar
    $searchServiceTypes = \Modules\TourBooking\App\Models\ServiceType::where('status', true)
        ->where('show_on_homepage', true)
        ->orderBy('ordering', 'asc')
        ->get();
    
    // Transfer routes for transfer tab
    $transferRoutes = \Modules\TourBooking\App\Models\TransferRoute::where('status', true)
        ->orderBy('display_order', 'asc')
        ->get();
    
    // Destinations
    $searchDestinations = \Modules\TourBooking\App\Models\Destination::where('status', true)
        ->orderBy('name', 'asc')
        ->get();
    
    // Transfer service type ID-sini tap
    $transferServiceType = \Modules\TourBooking\App\Models\ServiceType::where('slug', 'transfer')
        ->orWhere('slug', 'transfers')
        ->orWhere('name', 'like', '%transfer%')
        ->first();
    
    $transferServiceTypeId = $transferServiceType?->id ?? 0;
@endphp

<div class="tg-booking-form-area tg-tabbed-search p-relative z-index-1" x-data="tabbedSearchForm()">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="tg-booking-form-card">
                    
                    {{-- Service Type Tabs --}}
                    <div class="tg-booking-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($searchServiceTypes as $index => $type)
                                <li class="nav-item" role="presentation">
                                    <button 
                                        class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                        type="button"
                                        @click="selectServiceType({{ $type->id }}, '{{ $type->slug }}')"
                                        :class="{ 'active': activeServiceTypeId === {{ $type->id }} }">
                                        @if($type->icon)
                                            <i class="{{ $type->icon }}"></i>
                                        @else
                                            @if(Str::contains(strtolower($type->slug), 'transfer'))
                                                <i class="fa-solid fa-bus"></i>
                                            @elseif(Str::contains(strtolower($type->slug), ['sanator', 'hotel', 'spa']))
                                                <i class="fa-solid fa-bed"></i>
                                            @else
                                                <i class="fa-solid fa-building"></i>
                                            @endif
                                        @endif
                                        <span>{{ $type->name }}</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Search Form Content --}}
                    <div class="tg-booking-form-content">
                        <form @submit.prevent="submitSearch">
                            <div class="tg-booking-form-input-group d-flex align-items-end flex-wrap">
                                
                                {{-- SANATORIUM / HOTEL Form Fields (Non-Transfer) --}}
                                <template x-if="!isTransfer">
                                    <div class="d-flex align-items-end flex-wrap flex-grow-1">
                                        
                                        {{-- Destination / Hotel Selection --}}
                                        <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15 flex-grow-1">
                                            <span class="tg-booking-form-title mb-5">
                                                {{ __('translate.Resort, SPA Hotel, Diagnosis') }}
                                            </span>
                                            <div class="tg-booking-add-input-field tg-booking-quantity-toggle">
                                                <span x-show="formData.destination" x-text="formData.destinationName" class="tg-booking-title-value"></span>
                                                <span x-show="!formData.destination" class="tg-booking-title-value text-muted">
                                                    {{ __('translate.Enter to search or select...') }}
                                                </span>
                                                <span class="location">
                                                    <svg width="13" height="16" viewBox="0 0 13 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.3329 6.7071C12.3329 11.2324 6.55512 15.1111 6.55512 15.1111C6.55512 15.1111 0.777344 11.2324 0.777344 6.7071C0.777344 5.16402 1.38607 3.68414 2.46962 2.59302C3.55316 1.5019 5.02276 0.888916 6.55512 0.888916C8.08748 0.888916 9.55708 1.5019 10.6406 2.59302C11.7242 3.68414 12.3329 5.16402 12.3329 6.7071Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.55512 8.64649C7.61878 8.64649 8.48105 7.7782 8.48105 6.7071C8.48105 5.636 7.61878 4.7677 6.55512 4.7677C5.49146 4.7677 4.6292 5.636 4.6292 6.7071C4.6292 7.7782 5.49146 8.64649 6.55512 8.64649Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="tg-booking-form-location-list tg-booking-quantity-active">
                                                <ul class="scrool-bar scrool-height pr-5">
                                                    @foreach ($searchDestinations as $destination)
                                                        <li @click="selectDestination({{ $destination->id }}, '{{ $destination->name }}')">
                                                            <i class="fa-regular fa-location-dot"></i>
                                                            <span>{{ $destination->name }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        {{-- Check In / Check Out Dates --}}
                                        <div class="tg-booking-form-parent-inner mr-15 mb-15">
                                            <span class="tg-booking-form-title mb-5">{{ __('translate.Check in, Check out') }}</span>
                                            <div class="tg-booking-add-input-date p-relative">
                                                <input x-model="formData.dateRange" 
                                                       class="input daterangepicker-input" 
                                                       type="text" 
                                                       placeholder="{{ __('translate.Select dates') }}"
                                                       readonly>
                                                <span>
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.76501 0.777771V3.26668M4.23413 0.777771V3.26668M0.777344 5.75548H13.2218M2.16006 2.02211H11.8391C12.6027 2.02211 13.2218 2.57927 13.2218 3.26656V11.9778C13.2218 12.6651 12.6027 13.2222 11.8391 13.2222H2.16006C1.39641 13.2222 0.777344 12.6651 0.777344 11.9778V3.26656C0.777344 2.57927 1.39641 2.02211 2.16006 2.02211Z" stroke="#737374" stroke-width="0.977778" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Guests Selection --}}
                                        <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15">
                                            <span class="tg-booking-form-title mb-5">{{ __('translate.Rooms, Guests') }}</span>
                                            <div class="tg-booking-add-input-field tg-booking-quantity-toggle">
                                                <span class="tg-booking-title-value" x-text="guestsSummary"></span>
                                                <span class="location">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip_guest)">
                                                            <path d="M8 8C9.65685 8 11 6.65685 11 5C11 3.34315 9.65685 2 8 2C6.34315 2 5 3.34315 5 5C5 6.65685 6.34315 8 8 8Z" stroke="currentColor" stroke-width="1.5"/>
                                                            <path d="M14 14C14 11.2386 11.3137 9 8 9C4.68629 9 2 11.2386 2 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="tg-booking-form-location-list tg-quantity tg-booking-quantity-active">
                                                <ul>
                                                    <li>
                                                        <span class="mr-20">{{ __('translate.Rooms') }}</span>
                                                        <div class="tg-booking-quantity-item">
                                                            <span @click="formData.rooms++" class="increment">+</span>
                                                            <input x-model="formData.rooms" class="tg-quantity-input" type="text" readonly>
                                                            <span @click="formData.rooms > 1 ? formData.rooms-- : null" class="decrement">-</span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span class="mr-20">{{ __('translate.Guests') }}</span>
                                                        <div class="tg-booking-quantity-item">
                                                            <span @click="formData.guests++" class="increment">+</span>
                                                            <input x-model="formData.guests" class="tg-quantity-input" type="text" readonly>
                                                            <span @click="formData.guests > 1 ? formData.guests-- : null" class="decrement">-</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- TRANSFER Form Fields --}}
                                <template x-if="isTransfer">
                                    <div class="d-flex align-items-end flex-wrap flex-grow-1">
                                        
                                        {{-- Route Selection --}}
                                        <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15 flex-grow-1">
                                            <span class="tg-booking-form-title mb-5">
                                                {{ __('translate.Select Route') }}
                                            </span>
                                            <div class="tg-booking-add-input-field tg-booking-quantity-toggle">
                                                <span x-show="formData.transferRoute" x-text="formData.transferRouteName" class="tg-booking-title-value"></span>
                                                <span x-show="!formData.transferRoute" class="tg-booking-title-value text-muted">
                                                    {{ __('translate.Enter to search or select...') }}
                                                </span>
                                                <span class="location">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M2 5L8 2L14 5V11L8 14L2 11V5Z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M8 8V14" stroke="currentColor" stroke-width="1.2"/>
                                                        <path d="M8 8L14 5" stroke="currentColor" stroke-width="1.2"/>
                                                        <path d="M8 8L2 5" stroke="currentColor" stroke-width="1.2"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="tg-booking-form-location-list tg-booking-quantity-active">
                                                <ul class="scrool-bar scrool-height pr-5">
                                                    @forelse ($transferRoutes as $route)
                                                        <li @click="selectTransferRoute({{ $route->id }}, '{{ $route->from_location }} → {{ $route->to_location }}')">
                                                            <i class="fa-solid fa-route"></i>
                                                            <span>{{ $route->from_location }} → {{ $route->to_location }}</span>
                                                            @if($route->base_price)
                                                                <small class="text-muted ml-2">({{ currency($route->base_price) }})</small>
                                                            @endif
                                                        </li>
                                                    @empty
                                                        <li class="text-muted">
                                                            <span>{{ __('translate.No routes available') }}</span>
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>

                                        {{-- Single Date Selection --}}
                                        <div class="tg-booking-form-parent-inner mr-15 mb-15">
                                            <span class="tg-booking-form-title mb-5">{{ __('translate.Transfer Date') }}</span>
                                            <div class="tg-booking-add-input-date p-relative">
                                                <input x-model="formData.transferDate" 
                                                       class="input single-datepicker" 
                                                       type="text" 
                                                       placeholder="{{ __('translate.Select date') }}"
                                                       readonly>
                                                <span>
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.76501 0.777771V3.26668M4.23413 0.777771V3.26668M0.777344 5.75548H13.2218M2.16006 2.02211H11.8391C12.6027 2.02211 13.2218 2.57927 13.2218 3.26656V11.9778C13.2218 12.6651 12.6027 13.2222 11.8391 13.2222H2.16006C1.39641 13.2222 0.777344 12.6651 0.777344 11.9778V3.26656C0.777344 2.57927 1.39641 2.02211 2.16006 2.02211Z" stroke="#737374" stroke-width="0.977778" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Passengers Selection --}}
                                        <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15">
                                            <span class="tg-booking-form-title mb-5">{{ __('translate.Passengers') }}</span>
                                            <div class="tg-booking-add-input-field tg-booking-quantity-toggle">
                                                <span class="tg-booking-title-value" x-text="formData.passengers + ' {{ __('translate.passengers') }}'"></span>
                                                <span class="location">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip_pass)">
                                                            <path d="M8 8C9.65685 8 11 6.65685 11 5C11 3.34315 9.65685 2 8 2C6.34315 2 5 3.34315 5 5C5 6.65685 6.34315 8 8 8Z" stroke="currentColor" stroke-width="1.5"/>
                                                            <path d="M14 14C14 11.2386 11.3137 9 8 9C4.68629 9 2 11.2386 2 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="tg-booking-form-location-list tg-quantity tg-booking-quantity-active">
                                                <ul>
                                                    <li>
                                                        <span class="mr-20">{{ __('translate.Passengers') }}</span>
                                                        <div class="tg-booking-quantity-item">
                                                            <span @click="formData.passengers++" class="increment">+</span>
                                                            <input x-model="formData.passengers" class="tg-quantity-input" type="text" readonly>
                                                            <span @click="formData.passengers > 1 ? formData.passengers-- : null" class="decrement">-</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Search Button --}}
                                <div class="tg-booking-form-search-btn mb-15">
                                    <button type="submit" class="bk-search-button">
                                        {{ __('translate.Search') }}
                                        <span class="ml-5">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip_search)">
                                                    <path d="M13.2218 13.2222L10.5188 10.5192M12.1959 6.48705C12.1959 9.6402 9.63977 12.1963 6.48662 12.1963C3.33348 12.1963 0.777344 9.6402 0.777344 6.48705C0.777344 3.3339 3.33348 0.777771 6.48662 0.777771C9.63977 0.777771 12.1959 3.3339 12.1959 6.48705Z" stroke="currentColor" stroke-width="1.575" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('style_section')
<style>
    /* Tabbed Search Form Styles */
    .tg-tabbed-search {
        margin-top: -80px;
        position: relative;
        z-index: 100;
    }

    .tg-booking-form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .tg-booking-tabs {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .tg-booking-tabs .nav-tabs {
        border: none;
        display: flex;
        gap: 0;
        margin: 0;
        padding: 0;
    }

    .tg-booking-tabs .nav-item {
        flex: 0 0 auto;
    }

    .tg-booking-tabs .nav-link {
        border: none;
        border-radius: 0;
        padding: 18px 32px;
        font-weight: 500;
        color: #6c757d;
        background: transparent;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        cursor: pointer;
    }

    .tg-booking-tabs .nav-link:hover {
        color: #333;
        background: rgba(0, 0, 0, 0.02);
    }

    .tg-booking-tabs .nav-link.active {
        color: var(--tg-theme-primary, #ff5a1f);
        background: #fff;
        border-bottom-color: var(--tg-theme-primary, #ff5a1f);
    }

    .tg-booking-tabs .nav-link i {
        font-size: 18px;
    }

    .tg-booking-form-content {
        padding: 25px 30px;
    }

    .tg-booking-form-title {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .bk-search-button {
        background: var(--tg-theme-primary, #ff5a1f);
        color: #fff;
        border: none;
        padding: 16px 35px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .bk-search-button:hover {
        background: var(--tg-theme-primary-dark, #e04d15);
        transform: translateY(-2px);
    }

    /* Dropdown list style improvements */
    .tg-booking-form-location-list ul li {
        padding: 12px 15px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tg-booking-form-location-list ul li:hover {
        background: #f8f9fa;
    }

    .tg-booking-form-location-list ul li i {
        color: var(--tg-theme-primary, #ff5a1f);
    }

    /* Quantity buttons */
    .tg-booking-quantity-item .increment,
    .tg-booking-quantity-item .decrement {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f5;
        border-radius: 50%;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.2s;
    }

    .tg-booking-quantity-item .increment:hover,
    .tg-booking-quantity-item .decrement:hover {
        background: var(--tg-theme-primary, #ff5a1f);
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .tg-tabbed-search {
            margin-top: 20px;
        }

        .tg-booking-tabs .nav-link {
            padding: 14px 20px;
            font-size: 14px;
        }

        .tg-booking-form-content {
            padding: 20px;
        }
    }

    @media (max-width: 767px) {
        .tg-booking-tabs .nav-tabs {
            flex-wrap: wrap;
        }

        .tg-booking-tabs .nav-item {
            flex: 1 1 50%;
        }

        .tg-booking-tabs .nav-link {
            justify-content: center;
            padding: 12px 15px;
        }
    }
</style>
@endpush

@push('js_section')
<script>
    // Transfer Service Type ID from PHP
    const TRANSFER_SERVICE_TYPE_ID = {{ $transferServiceTypeId }};

    function tabbedSearchForm() {
        return {
            activeServiceTypeId: {{ $searchServiceTypes->first()?->id ?? 0 }},
            activeServiceTypeSlug: '{{ $searchServiceTypes->first()?->slug ?? '' }}',
            isTransfer: {{ Str::contains(strtolower($searchServiceTypes->first()?->slug ?? ''), 'transfer') ? 'true' : 'false' }},
            
            formData: {
                // Sanatorium/Hotel fields
                destination: null,
                destinationName: '',
                dateRange: '',
                checkIn: '',
                checkOut: '',
                rooms: 1,
                guests: 2,
                
                // Transfer fields
                transferRoute: null,
                transferRouteName: '',
                transferDate: '',
                passengers: 2
            },

            get guestsSummary() {
                return `{{ __('translate.Rooms') }}: ${this.formData.rooms}, {{ __('translate.Guests') }}: ${this.formData.guests}`;
            },

            selectServiceType(id, slug) {
                this.activeServiceTypeId = id;
                this.activeServiceTypeSlug = slug;
                this.isTransfer = slug.toLowerCase().includes('transfer');
                
                // Re-initialize date pickers after tab switch
                this.$nextTick(() => {
                    this.initDatePickers();
                });
            },

            selectDestination(id, name) {
                this.formData.destination = id;
                this.formData.destinationName = name;
            },

            selectTransferRoute(id, name) {
                this.formData.transferRoute = id;
                this.formData.transferRouteName = name;
            },

            submitSearch() {
                let url = '{{ route("front.tourbooking.services") }}';
                let params = new URLSearchParams();
                
                params.append('service_type', this.activeServiceTypeId);
                
                if (this.isTransfer) {
                    // Transfer search
                    if (this.formData.transferRoute) {
                        params.append('transfer_route', this.formData.transferRoute);
                    }
                    if (this.formData.transferDate) {
                        params.append('transfer_date', this.formData.transferDate);
                    }
                    params.append('passengers', this.formData.passengers);
                } else {
                    // Sanatorium/Hotel search
                    if (this.formData.destination) {
                        params.append('destination', this.formData.destination);
                    }
                    if (this.formData.checkIn) {
                        params.append('check_in', this.formData.checkIn);
                    }
                    if (this.formData.checkOut) {
                        params.append('check_out', this.formData.checkOut);
                    }
                    params.append('rooms', this.formData.rooms);
                    params.append('guests', this.formData.guests);
                }
                
                window.location.href = url + '?' + params.toString();
            },

            initDatePickers() {
                // Initialize date range picker for hotel/sanatorium
                if (typeof flatpickr !== 'undefined') {
                    flatpickr('.daterangepicker-input', {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        onChange: (selectedDates, dateStr) => {
                            if (selectedDates.length === 2) {
                                this.formData.checkIn = flatpickr.formatDate(selectedDates[0], 'Y-m-d');
                                this.formData.checkOut = flatpickr.formatDate(selectedDates[1], 'Y-m-d');
                            }
                        }
                    });

                    // Initialize single date picker for transfer
                    flatpickr('.single-datepicker', {
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        onChange: (selectedDates, dateStr) => {
                            this.formData.transferDate = dateStr;
                        }
                    });
                }
            },

            init() {
                this.$nextTick(() => {
                    this.initDatePickers();
                });
            }
        }
    }
</script>
@endpush