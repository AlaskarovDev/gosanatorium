@php
    $theme1_service_type = serviceTypeTab();
    $theme1_destinations = destinations();
    
    // Get all active services for autocomplete
    $autocomplete_services = \Modules\TourBooking\App\Models\Service::where('status', true)
        ->with(['translation:id,service_id,title', 'destination:id,name'])
        ->orderBy('is_featured', 'desc')
        ->orderBy('is_popular', 'desc')
        ->limit(50)
        ->get()
        ->map(function($service) {
            return [
                'id' => $service->id,
                'title' => $service->translation?->title ?? 'N/A',
                'location' => $service->destination?->name ?? $service->location ?? '',
                'slug' => $service->slug,
                'url' => route('front.tourbooking.services.show', ['slug' => $service->slug]),
                'service_type_id' => $service->service_type_id
            ];
        });
    
    // Get all active destinations for autocomplete
    $autocomplete_destinations = \Modules\TourBooking\App\Models\Destination::where('status', true)
        ->orderBy('is_featured', 'desc')
        ->orderBy('name', 'asc')
        ->get()
        ->map(function($dest) {
            return [
                'id' => $dest->id,
                'name' => $dest->name,
                'country' => $dest->country ?? ''
            ];
        });
    
    // ===== Transfer marşrutlarını al =====
    $transfer_routes = [];
    if (class_exists(\Modules\TourBooking\App\Models\TransferRoute::class)) {
        $transfer_routes = \Modules\TourBooking\App\Models\TransferRoute::where('status', true)
            ->orderBy('display_order', 'asc')
            ->get()
            ->map(function($route) {
                return [
                    'id' => $route->id,
                    'name' => $route->from_location . ' → ' . $route->to_location,
                    'from' => $route->from_location,
                    'to' => $route->to_location,
                    'price' => $route->base_price,
                    'duration' => $route->duration_minutes
                ];
            });
    }
    
    // Transfer service type ID-sini tap
    $transfer_type_ids = \Modules\TourBooking\App\Models\ServiceType::where('status', true)
        ->where(function($q) {
            $q->where('slug', 'like', '%transfer%')
              ->orWhere('name', 'like', '%transfer%')
              ->orWhere('name', 'like', '%Трансфер%');
        })
        ->pluck('id')
        ->toArray();
@endphp

@if ($theme1_service_type->count() > 0)
    <!-- tg-booking-form-area-start -->
    <style>
        .tg-booking-form-tabs {
            padding-top: 10px !important;
            padding-bottom: 6px !important;
            margin-bottom: 24px !important;
        }
        .tg-booking-form-tabs .nav-links {
            padding-bottom: 16px !important;
            padding-top: 8px !important;
        }
    </style>
    <div class="tg-booking-form-area tg-booking-form-space pb-105">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tg-booking-form-wrap">
                        <div class="tg-booking-form-tabs">
                            <div class="nav nav-tab justify-content-center" id="nav-tab" role="tablist">
                                @foreach ($theme1_service_type as $key => $service_type)
                                    <button @class(['nav-links', 'active' => $loop->first])
                                        id="service_type-{{ $service_type->id }}-tab" data-bs-toggle="tab"
                                        data-bs-target="#service_type-{{ $service_type->id }}" type="button"
                                        role="tab" aria-controls="service_type-{{ $service_type->id }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                        data-service-type-id="{{ $service_type->id }}"
                                        data-is-transfer="{{ in_array($service_type->id, $transfer_type_ids) ? 'true' : 'false' }}">
                                        <span class="borders"></span>
                                        <span class="icon">
                                            @if ($service_type->image)
                                                <img width="19" height="12" src="{{ asset($service_type->image) }}">
                                            @elseif ($service_type->icon)
                                                <i class="{{ $service_type->icon }}"></i>
                                            @else
                                                @if(in_array($service_type->id, $transfer_type_ids))
                                                    <i class="fa-solid fa-bus"></i>
                                                @else
                                                    <i class="fa-solid fa-bed"></i>
                                                @endif
                                            @endif
                                        </span>
                                        <span>{{ $service_type->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-content" id="nav-tabContent">
                            @foreach ($theme1_service_type as $key => $service_type)
                                @php
                                    $isTransferType = in_array($service_type->id, $transfer_type_ids);
                                @endphp
                                <div @class(['tab-pane fade', 'show active' => $loop->first]) id="service_type-{{ $service_type->id }}"
                                    role="tabpanel" aria-labelledby="service_type-{{ $service_type->id }}-tab"
                                    tabindex="0">
                                    <div class="tg-booking-form-item">
                                        
                                        @if($isTransferType)
                                            {{-- ==================== TRANSFER FORM ==================== --}}
<form x-data="transferBookingForm()" x-init="initForm()" @submit.prevent="submitForm" class="gstrf-form">
<style>
.gstrf-card{background:#fff !important;border-radius:16px !important;box-shadow:0 4px 24px rgba(0,0,0,0.10) !important;padding:28px 32px !important;text-align:left}
.gstrf-dir{display:flex !important;gap:10px;margin-bottom:18px;flex-wrap:wrap}
.gstrf-dir-btn{border:1.5px solid #ddd !important;background:transparent !important;color:#666 !important;border-radius:50px !important;padding:8px 20px !important;font-size:14px !important;font-weight:400 !important;cursor:pointer;line-height:1.3}
.gstrf-dir-btn.active{border:2px solid #1a9e87 !important;color:#1a9e87 !important;background:transparent !important;font-weight:500 !important}
.gstrf-row{display:grid !important;grid-template-columns:1fr 40px 1fr !important;align-items:center !important;gap:0 !important;margin-bottom:14px}
.gstrf-row-3{grid-template-columns:1fr 1fr 1fr !important;gap:16px !important;align-items:end !important}
.gstrf-field{min-width:0;position:relative}
.gstrf-label{display:block;font-size:11px !important;font-weight:400 !important;color:#9e9e9e !important;margin-bottom:4px !important;text-transform:none !important;letter-spacing:0.3px !important}
.gstrf-input{width:100% !important;border:1px solid #e8e8e8 !important;border-radius:10px !important;padding:14px 16px !important;font-size:15px !important;background:#fff !important;height:52px !important;color:#222 !important;outline:none !important;box-sizing:border-box !important}
.gstrf-input:focus{border-color:#1a9e87 !important;outline:none !important;box-shadow:0 0 0 3px rgba(26,158,135,0.12) !important}
.gstrf-input[type="date"]::-webkit-calendar-picker-indicator{opacity:0.5}
.gstrf-swap{width:40px !important;height:52px !important;align-self:end !important;border:none !important;background:transparent !important;color:#1a9e87 !important;font-size:18px !important;cursor:pointer;padding:0 !important;line-height:1;border-radius:0 !important}
.gstrf-icon-wrap{position:relative}
.gstrf-icon-wrap .gstrf-icon{position:absolute;right:14px;top:50%;transform:translateY(-50%);pointer-events:none;color:#888;font-size:14px}
.gstrf-pax{display:flex !important;align-items:center;justify-content:space-between;border:1px solid #e8e8e8 !important;border-radius:10px !important;padding:0 16px !important;height:52px !important;box-sizing:border-box !important;font-size:15px !important;background:#fff !important}
.gstrf-pax button{width:32px;height:32px;border-radius:50% !important;border:1px solid #1a9e87 !important;background:#fff !important;color:#1a9e87 !important;font-size:18px;line-height:1;cursor:pointer;padding:0 !important}
.gstrf-pax span{font-size:15px;font-weight:600;color:#222}
.gstrf-help{text-align:center;font-size:12px !important;color:#888 !important;margin:0 0 4px}
.gstrf-submit{display:block;width:100% !important;margin-top:16px;border:0 !important;border-radius:12px !important;height:54px !important;padding:0 20px !important;font-size:16px !important;font-weight:600 !important;letter-spacing:0.2px !important;color:#fff !important;cursor:pointer;background:linear-gradient(135deg,#1a9e87,#2ec4a9) !important}
.gstrf-dd{position:absolute;left:0;right:0;top:100%;z-index:50;background:#fff;border:1px solid #e0e0e0;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);max-height:240px;overflow:auto;margin-top:4px}
.gstrf-dd div{padding:10px 14px;cursor:pointer;font-size:14px;color:#333}
.gstrf-dd div:hover{background:#f2faf8}
@media (max-width:767px){.gstrf-row,.gstrf-row-3{grid-template-columns:1fr !important;gap:12px !important}.gstrf-swap{align-self:center !important;height:40px !important;transform:rotate(90deg)}.gstrf-card{padding:20px 16px !important}}
</style>
<div class="gstrf-card">
    <div class="gstrf-dir">
        <button type="button" class="gstrf-dir-btn" :class="{active: direction==='from'}" @click="setDirection('from')">Из аэропорта</button>
        <button type="button" class="gstrf-dir-btn" :class="{active: direction==='to'}" @click="setDirection('to')">До аэропорта</button>
    </div>

    <div class="gstrf-row">
        <div class="gstrf-field" x-ref="routeWrapper">
            <label class="gstrf-label" x-text="direction==='from' ? 'Аэропорт прилёта' : 'Откуда забрать'">Аэропорт прилёта</label>
            <input type="text" class="gstrf-input" x-model="leftValue" @input="filterRoutes()" @focus="openRouteDropdown()"
                   @keydown.escape="showRouteDropdown = false" autocomplete="off"
                   :placeholder="direction==='from' ? 'Например, Гянджа (GNJ)' : 'Санаторий, отель или адрес'">
            <div class="gstrf-dd" x-show="showRouteDropdown" x-cloak>
                <template x-for="route in filteredRoutes" :key="route.id">
                    <div @mousedown.prevent="selectRoute(route)">
                        <span x-text="route.name"></span>
                        <small x-show="route.price" x-text="' • от ' + route.price + ' ₼'" style="color:#888"></small>
                    </div>
                </template>
                <div x-show="filteredRoutes.length === 0" style="color:#888;cursor:default">Маршрут не найден</div>
            </div>
        </div>
        <button type="button" class="gstrf-swap" @click="swapDirection()" aria-label="Swap">⇄</button>
        <div class="gstrf-field">
            <label class="gstrf-label" x-text="direction==='from' ? 'Куда отвезти' : 'Аэропорт вылета'">Куда отвезти</label>
            <input type="text" class="gstrf-input" x-model="rightValue" autocomplete="off"
                   :placeholder="direction==='from' ? 'Санаторий, отель или адрес' : 'Например, Гянджа (GNJ)'">
        </div>
    </div>

    <div class="gstrf-row gstrf-row-3">
        <div class="gstrf-field">
            <label class="gstrf-label" x-text="direction==='from' ? 'Дата встречи' : 'Дата поездки'">Дата встречи</label>
            <div class="gstrf-icon-wrap">
                <input type="text" id="transferDatePicker-{{ $service_type->id }}" class="gstrf-input single-date-picker"
                       placeholder="Выберите дату" x-model="transfer_date" readonly>
                <span class="gstrf-icon"><i class="fa-regular fa-calendar"></i></span>
            </div>
        </div>
        <div class="gstrf-field">
            <label class="gstrf-label" x-text="direction==='from' ? 'Время встречи' : 'Время подачи'">Время встречи</label>
            <div class="gstrf-icon-wrap">
                <input type="time" class="gstrf-input" x-model="transfer_time" value="12:00">
                <span class="gstrf-icon"><i class="fa-regular fa-clock"></i></span>
            </div>
        </div>
        <div class="gstrf-field">
            <label class="gstrf-label">Пассажиры · взрослые и дети</label>
            <div class="gstrf-pax">
                <button type="button" @click="decrementPassengers()">−</button>
                <span id="gstrfCount" x-text="passengers">1</span>
                <button type="button" @click="incrementPassengers()">+</button>
            </div>
        </div>
    </div>

    <p class="gstrf-help">Встреча в аэропорту и поездка до санатория, отеля или указанного адреса.</p>
    <p class="gstrf-help">Стоимость трансфера можно уточнить в WhatsApp.</p>

    <button type="submit" class="gstrf-submit">Подобрать трансфер →</button>
</div>
</form>
                                            {{-- ==================== TRANSFER FORM END ==================== --}}
                                        @else
                                            {{-- ==================== SANATORIUM/HOTEL FORM ==================== --}}
                                            <form x-data="bookingForm({{ $service_type->id }})" x-init="initForm()" @submit.prevent="submitForm">
                                                <div class="tg-booking-form-input-group d-flex align-items-end justify-content-between flex-wrap">
                                                    
                                                    {{-- ========== 1. AUTOCOMPLETE SEARCH ========== --}}
                                                    <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15 tg-autocomplete-parent"
                                                         x-ref="autocompleteWrapper">
                                                        <span class="tg-booking-form-title mb-5">Курорт, СПА Отель, Диагноз</span>
                                                        <div class="tg-autocomplete-field">
                                                            <input type="text" 
                                                                   x-model="searchQuery"
                                                                   @input="filterResults()"
                                                                   @focus="openDropdown()"
                                                                   @keydown.escape="showDropdown = false"
                                                                   @keydown.arrow-down.prevent="navigateDown()"
                                                                   @keydown.arrow-up.prevent="navigateUp()"
                                                                   @keydown.enter.prevent="selectHighlighted()"
                                                                   class="tg-autocomplete-input"
                                                                   placeholder="Введите для поиска или выберите..."
                                                                   autocomplete="off">
                                                            <span class="tg-autocomplete-arrow" 
                                                                  :class="{ 'open': showDropdown }"
                                                                  @click.prevent.stop="showDropdown = !showDropdown; if(showDropdown) openDropdown()">
                                                                <svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M1 1.5L6 6.5L11 1.5" stroke="#666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        
                                                        {{-- Autocomplete Dropdown - HEÇ BİR @click.away YOX --}}
                                                        <div class="tg-autocomplete-dropdown" 
                                                             x-show="showDropdown"
                                                             x-cloak
                                                             x-transition:enter="transition ease-out duration-150"
                                                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                             x-transition:enter-end="opacity-100 transform translate-y-0">
                                                            
                                                            <div class="tg-ac-results">
                                                                {{-- Resorts Section --}}
                                                                <template x-if="filteredDestinations.length > 0">
                                                                    <div class="tg-ac-section">
                                                                        <div class="tg-ac-section-header">
                                                                            <span>Курорты</span>
                                                                        </div>
                                                                        <ul class="tg-ac-list">
                                                                            <template x-for="(dest, index) in filteredDestinations" :key="'dest-' + dest.id">
                                                                                <li @mousedown.prevent="selectDestination(dest.id, dest.name)" 
                                                                                    class="tg-ac-item"
                                                                                    :class="{ 'highlighted': highlightedIndex === index }">
                                                                                    <span class="tg-ac-icon">
                                                                                        <svg width="12" height="15" viewBox="0 0 12 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <path d="M6 0C2.69 0 0 2.69 0 6C0 10.5 6 15 6 15C6 15 12 10.5 12 6C12 2.69 9.31 0 6 0ZM6 8C4.9 8 4 7.1 4 6C4 4.9 4.9 4 6 4C7.1 4 8 4.9 8 6C8 7.1 7.1 8 6 8Z" fill="#008080"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                    <div class="tg-ac-info">
                                                                                        <span class="tg-ac-name" x-text="dest.name"></span>
                                                                                        <span class="tg-ac-location" x-text="dest.country"></span>
                                                                                    </div>
                                                                                </li>
                                                                            </template>
                                                                        </ul>
                                                                    </div>
                                                                </template>
                                                                
                                                                {{-- Spa Hotels Section --}}
                                                                <template x-if="filteredServices.length > 0">
                                                                    <div class="tg-ac-section">
                                                                        <div class="tg-ac-section-header">
                                                                            <span>СПА Отели</span>
                                                                        </div>
                                                                        <ul class="tg-ac-list">
                                                                            <template x-for="(service, index) in filteredServices" :key="'service-' + service.id">
                                                                                <li @mousedown.prevent="selectService(service)" 
                                                                                    class="tg-ac-item"
                                                                                    :class="{ 'highlighted': highlightedIndex === (filteredDestinations.length + index) }">
                                                                                    <span class="tg-ac-icon">
                                                                                        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                            <path d="M2 6V2C2 1.46957 2.21071 0.960859 2.58579 0.585786C2.96086 0.210714 3.46957 0 4 0H12C12.5304 0 13.0391 0.210714 13.4142 0.585786C13.7893 0.960859 14 1.46957 14 2V6" stroke="#008080" stroke-width="1.5"/>
                                                                                            <path d="M1 6H15C15.2652 6 15.5196 6.10536 15.7071 6.29289C15.8946 6.48043 16 6.73478 16 7V10C16 10.2652 15.8946 10.5196 15.7071 10.7071C15.5196 10.8946 15.2652 11 15 11H1C0.734784 11 0.48043 10.8946 0.292893 10.7071C0.105357 10.5196 0 10.2652 0 10V7C0 6.73478 0.105357 6.48043 0.292893 6.29289C0.48043 6.10536 0.734784 6 1 6Z" fill="#008080"/>
                                                                                        </svg>
                                                                                    </span>
                                                                                    <div class="tg-ac-info">
                                                                                        <span class="tg-ac-name" x-text="service.title"></span>
                                                                                        <span class="tg-ac-location" x-text="service.location"></span>
                                                                                    </div>
                                                                                </li>
                                                                            </template>
                                                                        </ul>
                                                                    </div>
                                                                </template>
                                                                
                                                                {{-- No Results --}}
                                                                <template x-if="searchQuery.length > 0 && filteredDestinations.length === 0 && filteredServices.length === 0">
                                                                    <div class="tg-ac-no-results">
                                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <circle cx="11" cy="11" r="8" stroke="#ccc" stroke-width="2"/>
                                                                            <path d="M21 21L16.65 16.65" stroke="#ccc" stroke-width="2" stroke-linecap="round"/>
                                                                        </svg>
                                                                        <span>Ничего не найдено для "<span x-text="searchQuery"></span>"</span>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- ========== 2. DATE RANGE PICKER ========== --}}
                                                    <div class="tg-booking-form-parent-inner mr-15 mb-15 tg-daterange-parent">
                                                        <span class="tg-booking-form-title mb-5">Заезд, Выезд</span>
                                                        <div class="tg-date-field p-relative">
                                                            <input type="text" 
                                                                   id="dateRangePicker-{{ $service_type->id }}"
                                                                   class="input daterange-picker" 
                                                                   placeholder="Выберите даты"
                                                                   x-ref="dateRangePicker"
                                                                   readonly>
                                                            <span class="tg-date-icon">
                                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M12.6667 2.66667H3.33333C2.59695 2.66667 2 3.26362 2 4V13.3333C2 14.0697 2.59695 14.6667 3.33333 14.6667H12.6667C13.403 14.6667 14 14.0697 14 13.3333V4C14 3.26362 13.403 2.66667 12.6667 2.66667Z" stroke="#666" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M10.6667 1.33333V4" stroke="#666" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M5.33333 1.33333V4" stroke="#666" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    <path d="M2 6.66667H14" stroke="#666" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    {{-- ========== 3. SEARCH BUTTON ========== --}}
                                                    <div class="tg-booking-form-search-btn mb-15">
                                                        <button class="bk-search-button" type="submit">
                                                            Поиск
                                                            <span class="ml-5">
                                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <g clip-path="url(#clip0_53_103)">
                                                                        <path d="M13.2218 13.2222L10.5188 10.5192M12.1959 6.48705C12.1959 9.6402 9.63977 12.1963 6.48662 12.1963C3.33348 12.1963 0.777344 9.6402 0.777344 6.48705C0.777344 3.3339 3.33348 0.777771 6.48662 0.777771C9.63977 0.777771 12.1959 3.3339 12.1959 6.48705Z" stroke="currentColor" stroke-width="1.575" stroke-linecap="round" stroke-linejoin="round"/>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            {{-- ==================== SANATORIUM/HOTEL FORM END ==================== --}}
                                        @endif
                                        
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tg-booking-form-area-end -->

    {{-- ========== STYLES ========== --}}
    <style>
        /* Alpine.js Cloak */
        [x-cloak] { display: none !important; }

        /* Form Layout */
        .tg-autocomplete-parent {
            flex: 1;
            min-width: 300px;
            max-width: 500px;
        }
        .tg-daterange-parent {
            flex: 0 0 280px;
        }

        /* ===== AUTOCOMPLETE INPUT ===== */
        .tg-autocomplete-field {
            position: relative;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: text;
            transition: border-color 0.2s ease;
        }
        .tg-autocomplete-field:hover {
            border-color: #008080;
        }
        .tg-autocomplete-field:focus-within {
            border-color: #008080;
            box-shadow: 0 0 0 2px rgba(0,128,128,0.1);
        }
        .tg-autocomplete-input {
            width: 100%;
            height: 46px;
            padding: 0 35px 0 15px;
            border: none;
            background: transparent;
            font-size: 14px;
            color: #333;
            outline: none;
        }
        .tg-autocomplete-input::placeholder {
            color: #999;
        }
        
        /* FIX: Arrow artıq tıklana bilən olmalıdır */
        .tg-autocomplete-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: auto;
            cursor: pointer;
            padding: 5px;
            transition: transform 0.2s ease;
        }
        .tg-autocomplete-arrow.open {
            transform: translateY(-50%) rotate(180deg);
        }

        /* ===== AUTOCOMPLETE DROPDOWN ===== */
        .tg-autocomplete-dropdown {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            width: 480px;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.12);
            z-index: 99999;
            max-height: 380px;
            overflow: hidden;
        }
        
        /* Route dropdown width */
        .tg-route-dropdown {
            width: 400px;
        }

        /* Results Container with Scroll */
        .tg-ac-results {
            max-height: 360px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Scrollbar Styling */
        .tg-ac-results::-webkit-scrollbar {
            width: 8px;
        }
        .tg-ac-results::-webkit-scrollbar-track {
            background: #f5f5f5;
        }
        .tg-ac-results::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
        .tg-ac-results::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        /* Section */
        .tg-ac-section {
            border-bottom: 1px solid #eee;
        }
        .tg-ac-section:last-child {
            border-bottom: none;
        }

        /* Section Header */
        .tg-ac-section-header {
            position: sticky;
            top: 0;
            background: #fff;
            padding: 12px 16px 8px;
            border-bottom: 2px solid #008080;
            z-index: 1;
        }
        .tg-ac-section-header span {
            color: #008080;
            font-size: 14px;
            font-weight: 600;
        }
        .tg-ac-section-header i {
            margin-right: 8px;
        }

        /* List */
        .tg-ac-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Item */
        .tg-ac-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f5f5f5;
            transition: background 0.15s ease;
        }
        .tg-ac-item:last-child {
            border-bottom: none;
        }
        .tg-ac-item:hover,
        .tg-ac-item.highlighted {
            background: #f0f9f9;
        }

        /* Icon */
        .tg-ac-icon {
            flex-shrink: 0;
            width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }
        .tg-ac-icon svg {
            width: 16px;
            height: 16px;
        }

        /* Info */
        .tg-ac-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow: hidden;
            flex: 1;
        }
        .tg-ac-name {
            font-size: 14px;
            font-weight: 500;
            color: #222;
            line-height: 1.3;
        }
        .tg-ac-location {
            font-size: 12px;
            color: #888;
        }

        /* No Results */
        .tg-ac-no-results {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 40px 20px;
            color: #999;
            font-size: 14px;
            text-align: center;
        }
        .tg-ac-no-results svg {
            opacity: 0.5;
        }
        .tg-ac-no-results-item {
            padding: 20px;
            text-align: center;
            color: #999;
        }

        /* ===== DATE FIELD ===== */
        .tg-date-field {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            transition: border-color 0.2s ease;
        }
        .tg-date-field:hover {
            border-color: #008080;
        }
        .tg-date-field:focus-within {
            border-color: #008080;
            box-shadow: 0 0 0 2px rgba(0,128,128,0.1);
        }
        .daterange-picker,
        .single-date-picker {
            width: 100%;
            height: 46px;
            padding: 0 40px 0 15px;
            border: none;
            background: transparent;
            font-size: 14px;
            color: #333;
            cursor: pointer;
            outline: none;
        }
        .daterange-picker::placeholder,
        .single-date-picker::placeholder {
            color: #999;
        }
        .tg-date-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        /* ===== Flatpickr — təmiz və yığcam təqvim ===== */
        .flatpickr-calendar {
            width: auto !important;
            border-radius: 14px !important;
            box-shadow: 0 14px 44px rgba(0, 0, 0, 0.18) !important;
            border: none !important;
            padding: 8px !important;
        }
        /* Ölçünü yığcam et (mövcud tema CSS-i böyüdürdü) */
        .flatpickr-days,
        .flatpickr-rContainer { width: auto !important; }
        .dayContainer {
            width: 252px !important;
            min-width: 252px !important;
            max-width: 252px !important;
            padding: 0 !important;
        }
        .flatpickr-day {
            max-width: 36px !important;
            height: 34px !important;
            line-height: 34px !important;
            margin: 1px 0 !important;
            border: none !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            color: #33373d;
        }
        .flatpickr-days { padding: 0 2px; }

        /* Başlıq / aylar */
        .flatpickr-months { padding-bottom: 4px; }
        .flatpickr-current-month { font-size: 15px !important; padding-top: 4px !important; }
        .flatpickr-current-month .cur-month,
        .flatpickr-current-month .numInputWrapper { font-weight: 600; }
        .flatpickr-weekday {
            color: #a0a4ab !important;
            font-size: 11px !important;
            font-weight: 600 !important;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg { fill: var(--tg-theme-primary) !important; }

        /* Hover / bugün / seçilmiş */
        .flatpickr-day:hover,
        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover { background: #f1ecfe !important; }
        .flatpickr-day.today {
            border: 1px solid var(--tg-theme-primary) !important;
            color: var(--tg-theme-primary);
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: var(--tg-theme-primary) !important;
            border-color: var(--tg-theme-primary) !important;
            color: #fff !important;
            box-shadow: none !important;
        }

        /* Aralıq — hamar, açıq bənövşəyi (səliqəsiz zolaqlar getsin) */
        .flatpickr-day.inRange {
            background: #efeafe !important;
            border-color: #efeafe !important;
            color: #33373d !important;
            box-shadow: -2px 0 0 #efeafe, 2px 0 0 #efeafe !important;
        }
        .flatpickr-day.startRange { border-radius: 8px 0 0 8px !important; }
        .flatpickr-day.endRange { border-radius: 0 8px 8px 0 !important; }
        .flatpickr-day.flatpickr-disabled,
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay { color: #cfd2d6 !important; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1199px) {
            .tg-autocomplete-parent {
                flex: 1 1 100%;
                min-width: 200px;
                max-width: none;
            }
            .tg-daterange-parent {
                flex: 1 1 calc(50% - 10px);
                min-width: 200px;
                max-width: none;
            }
            .tg-autocomplete-dropdown,
            .tg-route-dropdown {
                width: 100%;
            }
        }
        @media (max-width: 767px) {
            .tg-booking-form-input-group {
                flex-direction: column;
            }
            .tg-autocomplete-parent,
            .tg-daterange-parent {
                flex: 1 1 100%;
                min-width: 100%;
                max-width: 100%;
                margin-right: 0 !important;
            }
            .tg-autocomplete-dropdown,
            .tg-route-dropdown {
                width: 100%;
                left: 0;
            }
            .tg-booking-form-search-btn {
                width: 100%;
            }
            .tg-booking-form-search-btn .bk-search-button {
                width: 100%;
            }
        }
    </style>

    {{-- ========== JAVASCRIPT DATA ========== --}}
    <script>
        window.autocompleteData = {
            destinations: @json($autocomplete_destinations),
            services: @json($autocomplete_services)
        };
        window.transferRoutesData = @json($transfer_routes);
    </script>

    @push('js_section')
        {{-- 
            ╔══════════════════════════════════════════════════════════════╗
            ║  ƏSAS FIX: Alpine function-ları Alpine yüklənmədən ƏVVƏL   ║
            ║  təyin olunur. Dropdown bağlanması üçün manual              ║
            ║  document.addEventListener istifadə olunur.                 ║
            ║  @click.away / @click.outside TAMAMILƏ SİLİNİB.            ║
            ║  Dropdown item-larda @mousedown.prevent istifadə olunur     ║
            ║  ki, input blur olmadan seçim edilsin.                       ║
            ╚══════════════════════════════════════════════════════════════╝
        --}}
        <script>
            // ==================== SANATORIUM/HOTEL BOOKING FORM ====================
            function bookingForm(serviceTypeId) {
                return {
                    // Form Data
                    destination: '',
                    destination_id: '',
                    check_in: '',
                    check_out: '',
                    rooms: 1,
                    adults: 2,
                    children: 0,
                    activeServiceTypeId: serviceTypeId || null,

                    // Autocomplete Data
                    searchQuery: '',
                    showDropdown: false,
                    allDestinations: [],
                    allServices: [],
                    filteredDestinations: [],
                    filteredServices: [],
                    selectedService: null,
                    highlightedIndex: -1,

                    // ===== FIX: initForm - x-init ilə çağırılır =====
                    initForm() {
                        // Data-nı sinxron yüklə
                        this.allDestinations = window.autocompleteData?.destinations || [];
                        var allSvc = window.autocompleteData?.services || [];
                        // Filter services by active service type
                        if (this.activeServiceTypeId) {
                            this.allServices = allSvc.filter(s => s.service_type_id == this.activeServiceTypeId);
                        } else {
                            this.allServices = allSvc;
                        }
                        this.filteredDestinations = this.allDestinations;
                        this.filteredServices = this.allServices.slice(0, 10);
                        
                        // ===== FIX: Manual click outside handler =====
                        // Alpine direktivləri əvəzinə birbaşa document listener
                        const wrapper = this.$refs.autocompleteWrapper;
                        this._onDocClick = (e) => {
                            if (wrapper && !wrapper.contains(e.target)) {
                                this.showDropdown = false;
                            }
                        };
                        document.addEventListener('click', this._onDocClick, true);
                    },

                    // ===== FIX: Sadəcə aç, heç bir toggle/delay yox =====
                    openDropdown() {
                        this.highlightedIndex = -1;
                        
                        if (this.searchQuery.length === 0) {
                            this.filteredDestinations = this.allDestinations;
                            this.filteredServices = this.allServices.slice(0, 10);
                        }
                        
                        // Birbaşa aç - $nextTick yox, setTimeout yox
                        this.showDropdown = true;
                    },

                    // Filter results based on search
                    filterResults() {
                        this.highlightedIndex = -1;
                        this.showDropdown = true;
                        
                        const query = this.searchQuery.toLowerCase().trim();
                        
                        if (query.length === 0) {
                            this.filteredDestinations = this.allDestinations;
                            this.filteredServices = this.allServices.slice(0, 10);
                            return;
                        }
                        
                        this.filteredDestinations = this.allDestinations.filter(dest => 
                            dest.name.toLowerCase().includes(query) || 
                            (dest.country && dest.country.toLowerCase().includes(query))
                        ).slice(0, 5);
                        
                        this.filteredServices = this.allServices.filter(service => 
                            service.title.toLowerCase().includes(query) || 
                            (service.location && service.location.toLowerCase().includes(query))
                        ).slice(0, 15);
                    },

                    // Keyboard navigation
                    navigateDown() {
                        const totalItems = this.filteredDestinations.length + this.filteredServices.length;
                        if (this.highlightedIndex < totalItems - 1) {
                            this.highlightedIndex++;
                        }
                    },
                    navigateUp() {
                        if (this.highlightedIndex > 0) {
                            this.highlightedIndex--;
                        }
                    },
                    selectHighlighted() {
                        if (this.highlightedIndex < 0) return;
                        
                        if (this.highlightedIndex < this.filteredDestinations.length) {
                            const dest = this.filteredDestinations[this.highlightedIndex];
                            this.selectDestination(dest.id, dest.name);
                        } else {
                            const serviceIndex = this.highlightedIndex - this.filteredDestinations.length;
                            const service = this.filteredServices[serviceIndex];
                            this.selectService(service);
                        }
                    },

                    selectDestination(id, name) {
                        this.destination_id = id;
                        this.destination = name;
                        this.searchQuery = name;
                        this.showDropdown = false;
                        this.selectedService = null;
                    },
                    selectService(service) {
                        this.selectedService = service;
                        this.searchQuery = service.title;
                        this.destination = service.location;
                        this.showDropdown = false;
                    },

                    // Room/Guest Methods
                    incrementRooms() { if (this.rooms < 10) this.rooms++; },
                    decrementRooms() { if (this.rooms > 1) this.rooms--; },
                    incrementAdults() { if (this.adults < 20) this.adults++; },
                    decrementAdults() { if (this.adults > 1) this.adults--; },
                    incrementChildren() { if (this.children < 10) this.children++; },
                    decrementChildren() { if (this.children > 0) this.children--; },

                    // Form Submit
                    submitForm() {
                        const params = new URLSearchParams();
                        if (this.destination) params.append('destination', this.destination);
                        if (this.destination_id) params.append('destination_id', this.destination_id);
                        if (this.check_in) params.append('check_in', this.check_in);
                        if (this.check_out) params.append('check_out', this.check_out);
                        if (this.rooms) params.append('rooms', this.rooms);
                        if (this.adults) params.append('adults', this.adults);
                        if (this.children) params.append('children', this.children);
                        if (this.activeServiceTypeId) params.append('service_type_ids[]', this.activeServiceTypeId);

                        const baseUrl = window.location.origin;

                        if (this.selectedService && this.selectedService.url) {
                            window.location.href = this.selectedService.url + '?' + params.toString();
                        } else {
                            window.location.href = baseUrl + '/tour-booking/services?' + params.toString();
                        }
                    }
                }
            }
            
            // ==================== TRANSFER BOOKING FORM ====================
            function transferBookingForm() {
                return {
                    // Form Data
                    transfer_route_id: '',
                    transfer_route_name: '',
                    transfer_date: '',
                    transfer_time: '12:00',
                    passengers: 1,
                    direction: 'from',
                    leftValue: '',
                    rightValue: '',
                    
                    // Route Selection
                    routeSearchQuery: '',
                    showRouteDropdown: false,
                    allRoutes: [],
                    filteredRoutes: [],
                    selectedRoute: null,

                    // ===== FIX: initForm - x-init ilə çağırılır =====
                    initForm() {
                        this.allRoutes = window.transferRoutesData || [];
                        this.filteredRoutes = this.allRoutes;
                        
                        // ===== FIX: Manual click outside handler =====
                        const wrapper = this.$refs.routeWrapper;
                        this._onDocClick = (e) => {
                            if (wrapper && !wrapper.contains(e.target)) {
                                this.showRouteDropdown = false;
                            }
                        };
                        document.addEventListener('click', this._onDocClick, true);
                    },

                    // ===== FIX: Sadəcə aç =====
                    openRouteDropdown() {
                        if (this.routeSearchQuery.length === 0) {
                            this.filteredRoutes = this.allRoutes;
                        }
                        this.showRouteDropdown = true;
                    },

                    setDirection(d) { this.direction = d; },
                    swapDirection() {
                        this.direction = this.direction === 'from' ? 'to' : 'from';
                        const tmp = this.leftValue; this.leftValue = this.rightValue; this.rightValue = tmp;
                    },

                    // Filter routes
                    filterRoutes() {
                        this.routeSearchQuery = this.leftValue;
                        this.transfer_route_id = '';
                        this.showRouteDropdown = true;
                        
                        const query = this.routeSearchQuery.toLowerCase().trim();
                        
                        if (query.length === 0) {
                            this.filteredRoutes = this.allRoutes;
                            return;
                        }
                        
                        this.filteredRoutes = this.allRoutes.filter(route => 
                            route.name.toLowerCase().includes(query) ||
                            route.from.toLowerCase().includes(query) ||
                            route.to.toLowerCase().includes(query)
                        );
                    },

                    // Select route
                    selectRoute(route) {
                        this.selectedRoute = route;
                        this.transfer_route_id = route.id;
                        this.transfer_route_name = route.name;
                        this.routeSearchQuery = route.name;
                        this.leftValue = this.direction === 'from' ? route.from : route.to;
                        this.rightValue = this.direction === 'from' ? route.to : route.from;
                        this.showRouteDropdown = false;
                    },

                    // Passenger Methods
                    incrementPassengers() { if (this.passengers < 10) this.passengers++; },
                    decrementPassengers() { if (this.passengers > 1) this.passengers--; },

                    // Form Submit
                    submitForm() {
                        if (!this.transfer_route_id) {
                            const q = (this.leftValue || '').toLowerCase().trim();
                            const m = q ? this.allRoutes.find(r => r.name.toLowerCase() === q || r.from.toLowerCase() === q || r.to.toLowerCase() === q) : null;
                            if (m) { this.transfer_route_id = m.id; }
                        }
                        if (!this.transfer_route_id) {
                            alert('Пожалуйста, выберите маршрут');
                            return;
                        }
                        if (!this.transfer_date) {
                            alert('Пожалуйста, выберите дату');
                            return;
                        }
                        
                        const params = new URLSearchParams();
                        params.append('route_id', this.transfer_route_id);
                        params.append('transfer_date', this.transfer_date);
                        params.append('passengers', this.passengers);
                        params.append('transfer_time', this.transfer_time);
                        params.append('direction', this.direction);
                        if (this.rightValue) params.append('address', this.rightValue);
                        
                        const baseUrl = window.location.origin;
                        window.location.href = baseUrl + '/tour-booking/transfer/select-vehicle?' + params.toString();
                    }
                }
            }
        </script>

        {{-- Alpine.js - function təyinlərindən SONRA yüklənir --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            (function($) {
                "use strict";
                $(document).ready(function() {
                    // Date Range Picker (for Sanatorium/Hotel)
                    $(".daterange-picker").flatpickr({
                        mode: "range",
                        showMonths: window.innerWidth < 768 ? 1 : 2,
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "D d.m.Y",
                        minDate: "today",
                        locale: {
                            firstDayOfWeek: 1,
                            rangeSeparator: ' - '
                        },
                        onChange: function(selectedDates, dateStr, instance) {
                            if (selectedDates.length === 2) {
                                const form = instance.element.closest('form');
                                if (form && form.__x) {
                                    const alpineData = form.__x.$data;
                                    alpineData.check_in = selectedDates[0].toISOString().split('T')[0];
                                    alpineData.check_out = selectedDates[1].toISOString().split('T')[0];
                                }
                            }
                        }
                    });
                    
                    // Single Date Picker (for Transfer)
                    $(".single-date-picker").flatpickr({
                        mode: "single",
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d.m.Y",
                        minDate: "today",
                        locale: {
                            firstDayOfWeek: 1
                        },
                        onChange: function(selectedDates, dateStr, instance) {
                            if (selectedDates.length === 1) {
                                const form = instance.element.closest('form');
                                if (form && form.__x) {
                                    const alpineData = form.__x.$data;
                                    alpineData.transfer_date = dateStr;
                                }
                            }
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush
@endif