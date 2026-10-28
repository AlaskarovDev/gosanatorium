@extends('layout_inner_page')

@section('title')
    <title>Выберите номер - {{ $service->translation->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
@endsection

@push('style_section')
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

<style>
/* ========== CSS VARIABLES ========== */
:root {
    --rs-primary: #0077b6;
    --rs-primary-dark: #005f8a;
    --rs-primary-light: #00a8e8;
    --rs-danger: #e74c3c;
    --rs-success: #27ae60;
    --rs-warning: #f39c12;
    --rs-text: #333;
    --rs-text-secondary: #666;
    --rs-text-muted: #888;
    --rs-border: #e0e0e0;
    --rs-bg: #f5f7fa;
    --rs-card-bg: #fff;
    --rs-radius: 12px;
    --rs-radius-lg: 16px;
    --rs-radius-xl: 20px;
    --rs-shadow: 0 4px 20px rgba(0,0,0,0.08);
    --rs-shadow-lg: 0 8px 35px rgba(0,0,0,0.12);
    --rs-transition: all 0.3s ease;
    --safe-top: env(safe-area-inset-top, 0px);
    --safe-bottom: env(safe-area-inset-bottom, 0px);
}

.room-selection-wrapper * {
    box-sizing: border-box;
}

/* ========== FLATPICKR CALENDAR ========== */
.flatpickr-calendar {
    z-index: 999999 !important;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2) !important;
    border-radius: 12px !important;
    border: 1px solid #e0e0e0 !important;
    overflow: visible !important;
}

.flatpickr-calendar.open { display: block !important; }
.flatpickr-months, .flatpickr-innerContainer, .flatpickr-rContainer, .flatpickr-days, .dayContainer { overflow: visible !important; }
.flatpickr-calendar .flatpickr-innerContainer { padding: 0 5px; }
.flatpickr-calendar .dayContainer { min-width: 280px; padding: 0 2px; }

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background: var(--rs-primary) !important;
    border-color: var(--rs-primary) !important;
}

.flatpickr-day.inRange {
    background: rgba(0, 119, 182, 0.2) !important;
    border-color: transparent !important;
}

/* ========== ШАПКА ========== */
.room-header-section {
    background: linear-gradient(135deg, var(--rs-primary) 0%, var(--rs-primary-light) 100%);
    padding: 25px 0 30px;
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
}

.header-info-alert {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 8px;
    color: #fff;
    padding: 12px 20px;
    margin-top: 20px;
    text-align: center;
    font-size: 14px;
}

/* ========== ОСНОВНОЙ КОНТЕНТ ========== */
.room-main-section {
    padding: 40px 0 60px;
    background: var(--rs-bg);
}

/* ========== БОКОВАЯ ПАНЕЛЬ ФИЛЬТРОВ ========== */
.filter-sidebar {
    background: var(--rs-card-bg);
    border-radius: var(--rs-radius);
    box-shadow: var(--rs-shadow);
    padding: 25px;
    position: sticky;
    top: 20px;
}

.filter-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--rs-text);
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--rs-primary);
}

.filter-group { margin-bottom: 25px; }

.filter-group-title {
    font-size: 14px;
    font-weight: 600;
    color: #555;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    padding: 8px 0;
    cursor: pointer;
}

.filter-checkbox input {
    width: 18px;
    height: 18px;
    margin-right: 10px;
    cursor: pointer;
    accent-color: var(--rs-primary);
}

.filter-checkbox label {
    font-size: 14px;
    color: #444;
    cursor: pointer;
}

.price-range-inputs {
    display: flex;
    gap: 10px;
    align-items: center;
}

.price-input {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid var(--rs-border);
    border-radius: 8px;
    font-size: 14px;
}

.price-input:focus {
    outline: none;
    border-color: var(--rs-primary);
}

.filter-btn {
    width: 100%;
    padding: 12px;
    background: var(--rs-primary);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 10px;
}

.filter-btn:hover { background: var(--rs-primary-dark); }

.filter-btn.secondary {
    background: #f0f0f0;
    color: #666;
}

.filter-btn.secondary:hover { background: #e0e0e0; }

/* ========== СПИСОК НОМЕРОВ ========== */
.room-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.results-count {
    font-size: 16px;
    color: var(--rs-text-secondary);
}

.sort-select {
    padding: 10px 15px;
    border: 1px solid var(--rs-border);
    border-radius: 8px;
    font-size: 14px;
    background: var(--rs-card-bg);
    cursor: pointer;
}

/* ========== КАРТОЧКА НОМЕРА ========== */
.room-card {
    background: var(--rs-card-bg);
    border-radius: var(--rs-radius-lg);
    box-shadow: var(--rs-shadow);
    margin-bottom: 20px;
    overflow: hidden;
    transition: var(--rs-transition);
    border: 1px solid #eee;
}

.room-card:hover {
    box-shadow: var(--rs-shadow-lg);
    transform: translateY(-4px);
    border-color: var(--rs-primary);
}

.room-card-inner {
    display: grid;
    grid-template-columns: 260px 1fr 200px;
    gap: 0;
    min-height: 200px;
}

@media (max-width: 1199px) {
    .room-card-inner {
        grid-template-columns: 220px 1fr 180px;
    }
}

/* Изображение номера */
.room-image-col {
    position: relative;
    min-height: 200px;
    height: 100%;
    overflow: hidden;
}

.room-image-col a { display: block; width: 100%; height: 100%; }

.room-image-col img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.room-card:hover .room-image-col img { transform: scale(1.05); }

.room-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.room-image-placeholder i { font-size: 48px; color: #bbb; }

.image-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.room-image-col:hover .image-overlay { opacity: 1; }
.image-overlay i { color: #fff; font-size: 24px; }

.image-badge {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(0,0,0,0.7);
    color: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
}

.video-badge {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: var(--rs-danger);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mobile-discount-badge {
    display: none;
}

/* Детали номера */
.room-details-col {
    padding: 20px 25px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 200px;
    min-width: 0;
    overflow: hidden;
}

.room-name {
    font-size: 20px;
    font-weight: 700;
    color: var(--rs-text);
    margin-bottom: 12px;
    margin-top: 0;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.room-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
}

.spec-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f0f7ff;
    color: var(--rs-primary);
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
}

.spec-badge i { font-size: 12px; flex-shrink: 0; }

.room-description {
    color: var(--rs-text-secondary);
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 12px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.room-features {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 15px;
    align-items: flex-start;
    max-width: 100%;
}

.feature-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: #f0f7ff;
    color: #555;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid #e0e9f5;
    white-space: nowrap;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.feature-tag i { color: var(--rs-success); font-size: 10px; flex-shrink: 0; }

/* Цена номера */
.room-price-col {
    padding: 20px 25px;
    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    border-left: 1px solid #eee;
    min-width: 200px;
}

.discount-tag {
    background: linear-gradient(135deg, var(--rs-danger) 0%, #c0392b 100%);
    color: #fff;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.old-price {
    color: #999;
    font-size: 16px;
    text-decoration: line-through;
    margin-bottom: 4px;
}

.current-price {
    font-size: 32px;
    font-weight: 800;
    color: var(--rs-primary);
    line-height: 1;
}

.price-note {
    color: var(--rs-text-muted);
    font-size: 13px;
    margin-top: 4px;
    margin-bottom: 12px;
}

.low-stock-alert {
    background: #fff3cd;
    color: #856404;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 11px;
    margin-bottom: 12px;
    width: 100%;
    text-align: center;
    border: 1px solid #ffc107;
}

.select-room-btn {
    width: 100%;
    padding: 14px 20px;
    background: linear-gradient(135deg, var(--rs-primary) 0%, var(--rs-primary-light) 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--rs-transition);
    white-space: nowrap;
}

.select-room-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 119, 182, 0.35);
}

.select-room-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* ========== EXPAND / COLLAPSE ========== */
.room-desc--full {
    display: none;
    color: var(--rs-text-secondary);
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 12px;
}
.room-card.expanded .room-desc--preview { display: none; }
.room-card.expanded .room-desc--full { display: block; }
.room-card.expanded .feature-tag--hidden { display: inline-flex !important; }
.feature-tag--hidden { display: none !important; }

.room-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    padding: 6px 14px;
    background: transparent;
    border: 1.5px solid var(--rs-primary);
    border-radius: 20px;
    color: var(--rs-primary);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--rs-transition);
}
.room-toggle-btn:hover { background: var(--rs-primary); color: #fff; }
.room-toggle-btn__arrow { font-size: 11px; transition: transform 0.3s ease; }
.room-card.expanded .room-toggle-btn__arrow { transform: rotate(180deg); }

/* ========== МОДАЛЬНОЕ ОКНО БРОНИРОВАНИЯ ========== */
.booking-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    z-index: 999999;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
}

.booking-modal-overlay.active { display: flex; }

.booking-modal {
    background: var(--rs-card-bg);
    border-radius: var(--rs-radius-xl);
    box-shadow: 0 30px 90px rgba(0,0,0,0.35);
    width: 100%;
    max-width: 760px;
    max-height: 92vh;
    overflow-y: auto;
    position: relative;
    animation: modalSlideIn 0.4s ease;
}

/* Modern modal scrollbar */
.booking-modal::-webkit-scrollbar { width: 8px; }
.booking-modal::-webkit-scrollbar-track { background: transparent; }
.booking-modal::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 4px; }

/* Helper hint above guest counters */
.modal-base-hint {
    display: flex; align-items: flex-start; gap: 10px;
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1px solid #fcd34d; border-radius: 10px;
    color: #92400e; padding: 10px 14px; margin-bottom: 14px;
    font-size: 13px; line-height: 1.45;
}
.modal-base-hint i { color: #d97706; margin-top: 2px; }
.modal-base-hint strong { color: #78350f; }

/* Extras list rows */
#modalExtrasList { display: flex; flex-direction: column; gap: 10px; }
.modal-extra-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 10px;
    background: #fff; transition: all .15s;
}
.modal-extra-row:hover { border-color: #94a3b8; }
.modal-extra-row .extra-info { display: flex; align-items: center; gap: 10px; flex: 1; }
.modal-extra-row .extra-info input[type="checkbox"] {
    width: 18px; height: 18px; accent-color: var(--rs-primary, #2563eb); cursor: pointer;
}
.modal-extra-row .extra-info label {
    font-weight: 500; cursor: pointer; margin: 0; flex: 1; color: #1e293b;
}
.modal-extra-row .extra-price {
    font-weight: 700; color: var(--rs-primary, #2563eb); font-size: 14px; white-space: nowrap;
}
.modal-extra-row.is-auto {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-color: #bfdbfe;
}
.modal-extra-row.is-auto .extra-info { gap: 12px; }
.modal-extra-row.is-auto .extra-info::before {
    content: "\f12d"; /* fa-magic-wand-sparkles */
    font-family: "Font Awesome 6 Free"; font-weight: 900;
    color: #2563eb; font-size: 14px;
}
.modal-extra-row.is-auto label { color: #1e3a8a; }
.modal-extra-row.is-auto label small { color: #475569; font-weight: 400; display: block; }

/* Summary breakdown rows for extras */
#summaryExtras .price-row { color: #475569; font-size: 13px; }
#summaryExtras .price-row .extra-name { display: inline-flex; align-items: center; gap: 6px; }
#summaryExtras .price-row .extra-amount { color: var(--rs-primary, #2563eb); font-weight: 600; }

@keyframes modalSlideIn {
    from { opacity: 0; transform: scale(0.9) translateY(-30px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-header {
    background: linear-gradient(135deg, var(--rs-primary) 0%, var(--rs-primary-light) 100%);
    padding: 25px 30px;
    position: relative;
}

.modal-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    color: #fff;
    transition: var(--rs-transition);
}

.modal-close-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: rotate(90deg);
}

.modal-room-info {
    display: flex;
    gap: 15px;
    align-items: center;
}

.modal-room-thumb {
    width: 80px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,0.3);
}

.modal-room-name {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.modal-room-price {
    color: rgba(255,255,255,0.9);
    font-size: 14px;
    margin-top: 5px;
}

.modal-body { padding: 30px; }
.form-section { margin-bottom: 25px; }

.form-section-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--rs-text);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section-title i { color: var(--rs-primary); }

.date-range-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.date-input-group { position: relative; }

.date-input-group label {
    display: block;
    font-size: 12px;
    color: var(--rs-text-secondary);
    margin-bottom: 6px;
    font-weight: 500;
}

.date-input {
    width: 100%;
    padding: 14px 16px;
    padding-left: 44px;
    border: 2px solid var(--rs-border);
    border-radius: 10px;
    font-size: 15px;
    transition: var(--rs-transition);
    cursor: pointer;
    background: var(--rs-card-bg);
}

.date-input:focus {
    outline: none;
    border-color: var(--rs-primary);
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
}

.guest-counters {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.guest-counter-box {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: var(--rs-radius);
    padding: 15px;
}

.guest-counter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.guest-counter-label i {
    width: 28px;
    height: 28px;
    background: var(--rs-primary);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.guest-counter-label span {
    font-weight: 600;
    color: var(--rs-text);
    font-size: 14px;
}

.guest-counter-label small {
    display: block;
    color: var(--rs-text-muted);
    font-size: 11px;
    font-weight: 400;
}

.counter-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.counter-btn {
    width: 38px;
    height: 38px;
    border: 2px solid var(--rs-primary);
    background: var(--rs-card-bg);
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
    color: var(--rs-primary);
    transition: var(--rs-transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.counter-btn:hover:not(:disabled) {
    background: var(--rs-primary);
    color: #fff;
}

.counter-btn:disabled {
    border-color: #ddd;
    color: #ddd;
    cursor: not-allowed;
}

.counter-value {
    font-size: 22px;
    font-weight: 700;
    color: var(--rs-text);
    min-width: 40px;
    text-align: center;
}

.price-summary {
    background: linear-gradient(135deg, #f0f7ff 0%, #e8f4fc 100%);
    border: 1px solid #c8e1f4;
    border-radius: var(--rs-radius);
    padding: 20px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    font-size: 14px;
    color: #555;
}

.price-row.total {
    border-top: 2px dashed #c8e1f4;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 18px;
    font-weight: 700;
    color: var(--rs-text);
}

.price-row.total .price-value {
    color: var(--rs-primary);
    font-size: 24px;
}

.nights-badge {
    background: var(--rs-primary);
    color: #fff;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.modal-footer { padding: 0 30px 30px; }

.confirm-booking-btn {
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, var(--rs-success) 0%, #2ecc71 100%);
    color: #fff;
    border: none;
    border-radius: var(--rs-radius);
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: var(--rs-transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.confirm-booking-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(39, 174, 96, 0.4);
}

.confirm-booking-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.confirm-booking-btn .spinner {
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ========== ЛАЙТБОКС ========== */
.room-lightbox-modal {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.95);
    z-index: 999999;
}

.room-lightbox-modal.active {
    display: flex;
    flex-direction: column;
}

.lightbox-header {
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(0,0,0,0.5);
}

.lightbox-title { color: #fff; font-size: 18px; font-weight: 600; }
.lightbox-counter { color: rgba(255,255,255,0.7); font-size: 14px; }

.lightbox-close {
    width: 40px; height: 40px;
    background: rgba(255,255,255,0.1);
    border: none; border-radius: 50%;
    color: #fff; font-size: 20px;
    cursor: pointer; transition: var(--rs-transition);
}

.lightbox-close:hover { background: rgba(255,255,255,0.2); }

.lightbox-body {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 20px;
}

.lightbox-image-container img {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
}

.lightbox-video-container {
    display: none;
    width: 100%;
    max-width: 900px;
}

.lightbox-video-container iframe {
    width: 100%;
    aspect-ratio: 16/9;
    border: none;
    border-radius: 8px;
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 50px; height: 50px;
    background: rgba(255,255,255,0.1);
    border: none; border-radius: 50%;
    color: #fff; font-size: 20px;
    cursor: pointer; transition: var(--rs-transition);
}

.lightbox-nav:hover { background: rgba(255,255,255,0.2); }
.lightbox-nav.prev { left: 20px; }
.lightbox-nav.next { right: 20px; }

.lightbox-thumbnails {
    padding: 15px 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
    background: rgba(0,0,0,0.5);
    overflow-x: auto;
}

.lightbox-thumb {
    width: 60px; height: 45px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    opacity: 0.6;
    transition: var(--rs-transition);
    border: 2px solid transparent;
}

.lightbox-thumb:hover, .lightbox-thumb.active {
    opacity: 1;
    border-color: #fff;
}

.lightbox-thumb.video-thumb {
    background: var(--rs-danger);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ========== НЕТ РЕЗУЛЬТАТОВ ========== */
.no-results {
    text-align: center;
    padding: 60px 20px;
    background: var(--rs-card-bg);
    border-radius: var(--rs-radius-lg);
}

.no-results i { font-size: 64px; color: #ddd; margin-bottom: 20px; }
.no-results h4 { font-size: 20px; color: var(--rs-text); margin-bottom: 10px; }
.no-results p { color: var(--rs-text-muted); }

/* ═══════════════════════════════════════════════════════════════
   ██  MOBILE APP EXPERIENCE  ██
═══════════════════════════════════════════════════════════════ */

/* ── Mobile: Back header ── */
.mobile-room-header {
    display: none;
}

@media (max-width: 991px) {
    .mobile-room-header {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 998;
        padding: 12px 16px;
        padding-top: calc(12px + var(--safe-top));
        align-items: center;
        gap: 12px;
        background: var(--rs-primary);
        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
    }

    .mobile-room-header__back {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        flex-shrink: 0;
        transition: var(--rs-transition);
    }

    .mobile-room-header__back:active {
        transform: scale(0.92);
    }

    .mobile-room-header__title {
        flex: 1;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mobile-room-header__count {
        background: rgba(255,255,255,0.2);
        color: #fff;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
}

/* ── Mobile: Filter bar (horizontal) ── */
.mobile-filter-bar {
    display: none;
}

@media (max-width: 991px) {
    .mobile-filter-bar {
        display: flex;
        position: sticky;
        top: calc(62px + var(--safe-top));
        z-index: 100;
        background: var(--rs-card-bg);
        border-bottom: 1px solid var(--rs-border);
        padding: 10px 16px;
        gap: 8px;
        overflow-x: auto;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .mobile-filter-bar::-webkit-scrollbar { display: none; }

    .mobile-filter-chip {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border: 1.5px solid var(--rs-border);
        border-radius: 100px;
        background: var(--rs-card-bg);
        font-size: 13px;
        font-weight: 500;
        color: var(--rs-text-secondary);
        cursor: pointer;
        white-space: nowrap;
        transition: var(--rs-transition);
    }

    .mobile-filter-chip i { font-size: 12px; color: var(--rs-primary); }

    .mobile-filter-chip:active,
    .mobile-filter-chip.active {
        border-color: var(--rs-primary);
        background: #f0f7ff;
        color: var(--rs-primary);
    }
}

/* ── Mobile: Bottom sheet for filters ── */
.mobile-filter-sheet {
    display: none;
}

@media (max-width: 991px) {
    .mobile-filter-sheet {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99999;
        background: var(--rs-card-bg);
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.2);
        max-height: 75vh;
        overflow-y: auto;
        transform: translateY(100%);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0 20px 30px;
        padding-bottom: calc(30px + var(--safe-bottom));
    }

    .mobile-filter-sheet.open {
        display: block;
        transform: translateY(0);
    }

    .mobile-filter-sheet__handle {
        width: 40px;
        height: 4px;
        background: #ddd;
        border-radius: 100px;
        margin: 12px auto 20px;
    }

    .mobile-filter-sheet__title {
        font-size: 18px;
        font-weight: 700;
        color: var(--rs-text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mobile-filter-sheet__close {
        width: 32px;
        height: 32px;
        border: none;
        background: #f0f0f0;
        border-radius: 50%;
        font-size: 14px;
        color: var(--rs-text-secondary);
        cursor: pointer;
    }

    .mobile-filter-sheet-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 99998;
    }

    .mobile-filter-sheet-backdrop.open {
        display: block;
    }
}

/* ── Mobile: Room card redesign ── */
@media (max-width: 991px) {
    /* Hide desktop header & filter sidebar */
    .room-header-section {
        padding-top: calc(70px + var(--safe-top));
        padding-bottom: 20px;
    }

    .header-title { font-size: 20px; }
    .header-subtitle { font-size: 13px; }
    .header-info-alert { font-size: 12px; padding: 10px 14px; }

    .room-main-section { padding: 16px 0 30px; }

    /* Hide desktop filter sidebar on mobile */
    .filter-sidebar-col { display: none !important; }

    .room-list-header {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
        margin-bottom: 14px;
    }

    .results-count { font-size: 14px; text-align: center; }

    .sort-select {
        width: 100%;
        padding: 10px 12px;
        font-size: 13px;
    }

    /* Room card - stacked layout */
    .room-card {
        border-radius: var(--rs-radius);
        margin-bottom: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }

    .room-card:hover {
        transform: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }

    .room-card-inner {
        grid-template-columns: 1fr;
        min-height: auto;
    }

    /* Image - full width with swipe hint */
    .room-image-col {
        height: 200px;
        min-height: auto;
    }

    .mobile-discount-badge {
        display: flex;
        position: absolute;
        top: 12px;
        left: 12px;
        background: var(--rs-danger);
        color: #fff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        z-index: 5;
    }

    .image-overlay { opacity: 0 !important; }

    /* Details */
    .room-details-col {
        padding: 16px;
        min-height: auto;
    }

    .room-name {
        font-size: 17px;
        margin-bottom: 10px;
    }

    .room-specs {
        gap: 6px;
        margin-bottom: 10px;
    }

    .spec-badge {
        padding: 4px 10px;
        font-size: 11px;
        gap: 4px;
    }

    .spec-badge i { font-size: 10px; }

    .room-description {
        font-size: 13px;
        margin-bottom: 8px;
    }

    .room-features {
        gap: 6px;
        margin-top: 10px;
    }

    .feature-tag {
        padding: 4px 10px;
        font-size: 11px;
    }

    .room-toggle-btn {
        padding: 5px 12px;
        font-size: 12px;
    }

    /* Price - horizontal inline layout */
    .room-price-col {
        border-left: none;
        border-top: 1px solid #eee;
        padding: 14px 16px;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        min-width: auto;
        background: #fafbfc;
    }

    .room-price-col .discount-tag { display: none; }
    /* Discount already shown as badge on image */

    .room-price-col .old-price {
        font-size: 13px;
        margin-bottom: 0;
        margin-right: 6px;
    }

    .room-price-col .current-price {
        font-size: 24px;
    }

    .room-price-col .price-note {
        margin: 0 0 0 4px;
        font-size: 12px;
    }

    /* Group price items together on left */
    .room-price-col {
        flex-wrap: wrap;
    }

    .mobile-price-left {
        display: flex;
        align-items: baseline;
        gap: 4px;
        flex-wrap: wrap;
    }

    .low-stock-alert {
        width: 100%;
        margin-bottom: 8px;
        font-size: 11px;
        padding: 6px 10px;
        order: -1;
    }

    .select-room-btn {
        padding: 12px 20px;
        font-size: 13px;
        border-radius: 100px;
        width: auto;
        flex-shrink: 0;
    }
}

/* ── Mobile: Booking modal - full screen bottom sheet ── */
@media (max-width: 991px) {
    .booking-modal-overlay {
        padding: 0;
        align-items: flex-end;
    }

    .booking-modal {
        max-width: 100%;
        max-height: 95vh;
        border-radius: 20px 20px 0 0;
        animation: modalSlideUp 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes modalSlideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    .modal-header {
        padding: 20px;
        border-radius: 20px 20px 0 0;
    }

    .modal-header::before {
        content: '';
        display: block;
        width: 40px;
        height: 4px;
        background: rgba(255,255,255,0.3);
        border-radius: 100px;
        margin: 0 auto 16px;
    }

    .modal-room-info {
        flex-direction: row;
        text-align: left;
    }

    .modal-room-thumb {
        width: 60px;
        height: 48px;
    }

    .modal-room-name { font-size: 16px; }

    .modal-body { padding: 20px; }
    .form-section { margin-bottom: 20px; }
    .form-section-title { font-size: 13px; margin-bottom: 12px; }

    .date-range-container {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .date-input {
        padding: 12px 14px;
        padding-left: 40px;
        font-size: 14px;
    }

    .guest-counters {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .guest-counter-box { padding: 12px; }

    .counter-btn {
        width: 42px;
        height: 42px;
        font-size: 20px;
    }

    .counter-value { font-size: 24px; }

    .price-summary { padding: 16px; }
    .price-row { font-size: 13px; }
    .price-row.total { font-size: 16px; }
    .price-row.total .price-value { font-size: 22px; }

    .modal-footer { padding: 0 20px 20px; padding-bottom: calc(20px + var(--safe-bottom)); }

    .confirm-booking-btn {
        padding: 16px;
        font-size: 15px;
        border-radius: 100px;
    }
}

/* ── Mobile: Lightbox ── */
@media (max-width: 991px) {
    .lightbox-header { padding: 12px 16px; }
    .lightbox-title { font-size: 14px; }

    .lightbox-nav {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    .lightbox-nav.prev { left: 10px; }
    .lightbox-nav.next { right: 10px; }

    .lightbox-body { padding: 10px; }

    .lightbox-image-container img { max-height: 60vh; }

    .lightbox-thumbnails {
        padding: 10px 16px;
        gap: 8px;
    }

    .lightbox-thumb {
        width: 50px;
        height: 38px;
    }
}

/* ── Mobile: Smooth behavior ── */
@media (max-width: 991px) {
    html {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    body {
        overscroll-behavior-y: contain;
    }

    .flatpickr-calendar {
        max-width: 95vw !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
}

/* =================================================================
   FILTER + ROOM CARD REDESIGN (overrides earlier styles)
   ================================================================= */

/* Filter toggle button on top of room list */
.filter-toggle-bar {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 16px; flex-wrap: wrap;
}
.filter-toggle-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; border: 1.5px solid #e2e8f0; color: #1e293b;
    padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; transition: all .18s;
    box-shadow: 0 2px 6px rgba(15, 25, 50, .04);
}
.filter-toggle-btn:hover { border-color: #2563eb; color: #2563eb; transform: translateY(-1px); }
.filter-toggle-btn.is-active {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    color: #fff; border-color: #2563eb;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .28);
}
.filter-toggle-btn .toggle-badge {
    background: rgba(255,255,255,.25); padding: 2px 8px; border-radius: 12px; font-size: 11px;
}

/* Compact filter sidebar — 240px instead of full col-lg-3 */
.filter-sidebar-col {
    flex: 0 0 240px !important;
    max-width: 240px !important;
    transition: transform .3s cubic-bezier(.4, 0, .2, 1), opacity .25s, max-width .3s, margin .3s;
    overflow: hidden;
}
.filter-sidebar-col.is-collapsed {
    flex: 0 0 0 !important;
    max-width: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    transform: translateX(-12px); opacity: 0;
    pointer-events: none;
}

/* When sidebar collapsed, room column takes the full width */
.room-list-col {
    flex: 1 1 auto !important;
    max-width: 100% !important;
    transition: max-width .3s, flex-basis .3s;
}

.filter-sidebar {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;
    padding: 16px; box-shadow: 0 4px 14px rgba(15, 25, 50, .04);
    position: sticky; top: 90px;
    max-height: calc(100vh - 110px); overflow-y: auto;
}
.filter-sidebar::-webkit-scrollbar { width: 6px; }
.filter-sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

.filter-sidebar .filter-title { font-size: 15px; margin-bottom: 14px; }
.filter-sidebar .filter-group { margin-bottom: 14px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; }
.filter-sidebar .filter-group:last-child { border-bottom: none; padding-bottom: 0; }
.filter-sidebar .filter-group-title { font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #475569; }
.filter-sidebar .price-input { font-size: 13px; padding: 7px 10px; }
.filter-sidebar .filter-checkbox { font-size: 13px; }

@media (max-width: 991px) {
    .filter-sidebar-col {
        position: fixed; top: 0; left: 0; bottom: 0; z-index: 1050;
        flex: 0 0 320px !important; max-width: 320px !important;
        background: #fff; transform: translateX(-100%); transition: transform .3s;
        padding: 20px 16px !important; overflow-y: auto;
        box-shadow: 8px 0 30px rgba(15, 25, 50, .15);
    }
    .filter-sidebar-col.is-open { transform: translateX(0); }
    .filter-sidebar-col.is-collapsed { transform: translateX(-100%); }
    .filter-sidebar { position: static; box-shadow: none; padding: 0; border: none; }
}
.filter-backdrop {
    position: fixed; inset: 0; background: rgba(15, 25, 50, .35);
    z-index: 1049; opacity: 0; pointer-events: none; transition: opacity .25s;
}
.filter-backdrop.is-visible { opacity: 1; pointer-events: auto; }

/* ============== Room cards ============== */
.room-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 18px;
    overflow: hidden; transition: all .25s cubic-bezier(.4, 0, .2, 1);
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(15, 25, 50, .04);
}
.room-card:hover {
    box-shadow: 0 14px 38px rgba(15, 25, 50, .12);
    border-color: #c7d2fe;
    transform: translateY(-3px);
}
.room-card.expanded {
    box-shadow: 0 18px 46px rgba(37, 99, 235, .15);
    border-color: #93c5fd;
    transform: translateY(-2px);
}
.room-card .room-image-col {
    position: relative; overflow: hidden;
}
.room-card .room-image-col img { transition: transform .5s cubic-bezier(.4, 0, .2, 1); }
.room-card:hover .room-image-col img { transform: scale(1.06); }

.room-card .room-toggle-btn {
    background: transparent; border: 1.5px solid #cbd5e1; color: #475569;
    padding: 8px 16px; border-radius: 999px; font-weight: 600; font-size: 13px;
    transition: all .2s; display: inline-flex; align-items: center; gap: 8px;
}
.room-card .room-toggle-btn:hover {
    background: #2563eb; color: #fff; border-color: #2563eb;
}
.room-card .room-toggle-btn__arrow { transition: transform .3s; }
.room-card.expanded .room-toggle-btn__arrow { transform: rotate(180deg); }

/* Smooth expand animation */
.room-card .room-collapsible {
    max-height: 0; overflow: hidden;
    transition: max-height .4s cubic-bezier(.4, 0, .2, 1), opacity .25s ease .15s;
    opacity: 0;
}
.room-card.expanded .room-collapsible {
    max-height: 1200px; opacity: 1;
}

/* Wider container on the room selection page */
.room-main-section .container {
    max-width: 1640px !important;
    padding-left: 32px;
    padding-right: 32px;
}
@media (max-width: 1199px) {
    .room-main-section .container { padding-left: 20px; padding-right: 20px; }
}

/* Keep cards full-width — DON'T override the original CSS grid layout */
.room-card { width: 100%; margin-bottom: 22px; }
/* Wider image col + content + sleeker price col */
.room-list-col.is-wide .room-card-inner { grid-template-columns: 380px 1fr 260px !important; min-height: 280px !important; }
@media (max-width: 1399px) {
    .room-list-col.is-wide .room-card-inner { grid-template-columns: 320px 1fr 240px !important; }
}
@media (max-width: 1199px) {
    .room-list-col.is-wide .room-card-inner { grid-template-columns: 280px 1fr 220px !important; }
}
/* Mobil (≤991px): kartı tək sütuna sal — başlığın şaquli sıxılmasının qarşısını alır.
   `is-wide` qaydası 3 class + !important olduğu üçün eyni güclə override lazımdır. */
@media (max-width: 991px) {
    .room-list-col.is-wide .room-card-inner {
        grid-template-columns: 1fr !important;
        min-height: auto !important;
    }
}

/* Visual polish only — no layout overrides */
.room-card { border: 1px solid #e2e8f0 !important; border-radius: 18px !important; }
.room-card:hover { border-color: #c7d2fe !important; }
.room-card.expanded { border-color: #93c5fd !important; }

.room-card .room-image-col img { transition: transform .5s cubic-bezier(.4, 0, .2, 1); }
.room-card:hover .room-image-col img { transform: scale(1.06); }

.room-card .spec-badge {
    background: #eff6ff !important; color: #1d4ed8 !important; border: 1px solid #dbeafe !important;
    padding: 6px 12px !important; border-radius: 999px !important; font-size: 12.5px !important;
    font-weight: 500 !important; display: inline-flex; align-items: center; gap: 6px;
}
.room-card .spec-badge i { font-size: 11px; }

.room-card .feature-tag {
    background: #f1f5f9 !important; color: #475569 !important;
    padding: 4px 10px !important; border-radius: 14px !important; font-size: 12px !important;
}
.room-card .feature-tag i { color: #16a34a !important; margin-right: 4px; }

.room-card .room-price-col {
    background: linear-gradient(160deg, #f8fafc 0%, #eff6ff 100%) !important;
    border-left: 1px solid #e2e8f0 !important;
    border-top-right-radius: 18px; border-bottom-right-radius: 18px;
}
.room-card .current-price {
    font-size: 28px !important; font-weight: 800 !important; color: #2563eb !important;
}

.room-card .select-room-btn {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
    color: #fff !important; border: none !important; padding: 11px 14px !important;
    border-radius: 10px !important; font-weight: 700 !important; font-size: 14px !important;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .25) !important;
    display: inline-flex !important; align-items: center; justify-content: center; gap: 6px;
    width: 100%; cursor: pointer; transition: all .18s !important;
}
.room-card .select-room-btn:hover:not(:disabled) {
    transform: translateY(-1px) !important; box-shadow: 0 10px 22px rgba(37, 99, 235, .35) !important;
}
.room-card .select-room-btn:disabled {
    background: #cbd5e1 !important; color: #fff; cursor: not-allowed; box-shadow: none !important;
}

.room-card .discount-tag {
    background: #fef2f2 !important; color: #dc2626 !important; border: 1px solid #fecaca !important;
    padding: 4px 10px !important; border-radius: 999px !important; font-size: 11px !important;
    font-weight: 700 !important; display: inline-flex; align-items: center; gap: 4px;
}

.room-card .low-stock-alert {
    background: #fffbeb !important; border: 1px solid #fde68a !important; color: #92400e !important;
    padding: 8px 10px !important; border-radius: 8px !important; font-size: 11.5px !important;
    text-align: center !important; line-height: 1.3 !important;
}

/* ==== Modern visual upgrades ==== */
.room-card {
    background: #fff !important;
    box-shadow: 0 4px 16px rgba(15, 25, 50, .06) !important;
    transition: transform .25s cubic-bezier(.4,0,.2,1), box-shadow .25s, border-color .25s !important;
    position: relative;
    overflow: hidden;
}
.room-card:hover {
    transform: translateY(-6px) !important;
    box-shadow: 0 22px 50px rgba(15, 25, 50, .14) !important;
}

/* Subtle gradient ribbon on the right edge of image */
.room-card .room-image-col::after {
    content: "";
    position: absolute; top: 0; right: 0; bottom: 0; width: 80px;
    background: linear-gradient(to right, transparent, rgba(255,255,255,.05));
    pointer-events: none;
}

/* Modernize gallery counter badge */
.room-card .room-image-col .image-counter,
.room-card .room-image-col [class*="image-count"],
.room-card .room-image-col [class*="lightbox"]:not(.room-lightbox-trigger) {
    backdrop-filter: blur(6px);
}
.room-card .room-image-col .room-img-count,
.room-card .room-image-col .gallery-count {
    background: rgba(15, 25, 50, .65) !important;
    color: #fff !important;
    padding: 5px 10px !important;
    border-radius: 999px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    backdrop-filter: blur(6px);
}

/* Title typography */
.room-card .room-name {
    font-size: 22px !important;
    font-weight: 800 !important;
    color: #0f172a !important;
    letter-spacing: -0.3px !important;
    margin-bottom: 14px !important;
}

/* Content padding more breathing room */
.room-card .room-content-col {
    padding: 26px 30px !important;
}

/* Modern toggle button */
.room-card .room-toggle-btn {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%) !important;
    border: 1px solid #cbd5e1 !important;
    color: #475569 !important;
    padding: 9px 18px !important;
    border-radius: 999px !important;
    font-weight: 600 !important; font-size: 13px !important;
    transition: all .2s !important;
    display: inline-flex !important; align-items: center; gap: 8px;
}
.room-card .room-toggle-btn:hover {
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
    color: #fff !important; border-color: #2563eb !important;
    box-shadow: 0 6px 16px rgba(37, 99, 235, .25);
}

/* Price col cleaner */
.room-card .room-price-col {
    padding: 26px 22px !important;
    display: flex !important; flex-direction: column;
    justify-content: space-between !important;
    gap: 10px;
}
.room-card .current-price {
    font-size: 32px !important;
    letter-spacing: -1px !important;
    line-height: 1 !important;
    background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    text-align: center;
}
.room-card .price-note {
    text-align: center !important;
    text-transform: uppercase !important;
    font-size: 10.5px !important;
    letter-spacing: 1.5px !important;
    color: #64748b !important;
    font-weight: 600 !important;
    margin-top: 4px !important;
}

.room-card .select-room-btn {
    padding: 13px 14px !important;
    font-size: 14.5px !important;
    letter-spacing: 0.3px;
}

/* Discount tag — premium look */
.room-card .discount-tag {
    position: absolute; top: 14px; left: 14px; z-index: 5;
    background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%) !important;
    color: #fff !important; border: none !important;
    padding: 6px 12px !important; border-radius: 999px !important;
    font-size: 11px !important; font-weight: 700 !important;
    box-shadow: 0 6px 16px rgba(244, 63, 94, .35) !important;
    letter-spacing: 0.3px;
}

/* Spec badge upgrade */
.room-card .spec-badge {
    background: rgba(239, 246, 255, .85) !important;
    backdrop-filter: blur(4px);
    border: 1px solid #bfdbfe !important;
    box-shadow: 0 1px 3px rgba(37, 99, 235, .06);
}

@media (max-width: 768px) {
    .room-card .room-name { font-size: 18px !important; }
    .room-card .current-price { font-size: 26px !important; }
    .room-card .room-content-col { padding: 18px 20px !important; }
}

/* ========== REDESIGN 2026: room cards ========== */
.room-selection-wrapper .room-header-section { background: #fff !important; padding: 22px 0 0 !important; }
.room-selection-wrapper .header-title { color: #1a1a2e !important; font-size: 26px !important; margin-bottom: 12px !important; }
.room-selection-wrapper .header-info-alert {
    display: flex !important; align-items: center; justify-content: flex-start; gap: 10px;
    background: #f3f4f6 !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important;
    color: #4b5563 !important; min-height: 44px; padding: 0 16px !important; margin-top: 0 !important;
    text-align: left !important; font-size: 14px !important;
}
.room-selection-wrapper .header-info-alert i { color: #6b7280; }
.room-selection-wrapper .room-main-section { padding-top: 24px !important; }

.room-selection-wrapper .filter-toggle-bar { align-items: center; gap: 14px; margin-bottom: 20px; flex-wrap: nowrap; }
.room-selection-wrapper .filter-toggle-btn {
    background: #fff; border: 1.5px solid #cbd5e1; color: #1a1a2e; box-shadow: none; padding: 9px 16px; border-radius: 8px;
}
.room-selection-wrapper .filter-toggle-btn:hover { border-color: var(--rs-primary); color: var(--rs-primary); transform: none; }
.room-selection-wrapper .results-count { color: #6b7280; font-size: 14px; font-weight: 400; background: none; border: 0; padding: 0; }
.room-selection-wrapper .room-list-header { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.room-selection-wrapper .sort-select {
    -webkit-appearance: none; appearance: none; background: #fff url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'><path d='M1 1l5 5 5-5' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/></svg>") no-repeat right 12px center;
    border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 9px 34px 9px 14px; font-size: 14px; color: #374151; margin-left: auto; cursor: pointer;
}

.room-selection-wrapper .room-card {
    background: #fff; border: 1px solid #eef0f3 !important; border-radius: 12px !important;
    box-shadow: 0 2px 14px rgba(15, 23, 42, .08); margin-bottom: 24px; overflow: hidden; transform: none !important;
}
.room-selection-wrapper .room-card:hover { box-shadow: 0 8px 26px rgba(15, 23, 42, .12); border-color: #e2e8f0 !important; }
.room-selection-wrapper .room-list-col.is-wide .room-card-inner,
.room-selection-wrapper .room-card-inner {
    display: grid; grid-template-columns: 40% 60% !important; min-height: 320px !important; align-items: stretch;
}
.room-selection-wrapper .room-image-col { position: relative; height: 100% !important; min-height: 100%; overflow: hidden; background: #e5e7eb; }
.room-selection-wrapper .room-image-col::after { display: none !important; }
.room-selection-wrapper .room-image-col a { position: absolute; inset: 0; display: block; }
.room-selection-wrapper .room-image-col img { width: 100%; height: 100%; object-fit: cover; display: block; }
.rc-gal-btn {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 6; width: 34px; height: 34px; border-radius: 50%;
    border: 0; background: rgba(255,255,255,.9); color: #1a1a2e; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,.2); cursor: pointer; font-size: 13px; opacity: .95; transition: background .2s;
}
.rc-gal-btn:hover { background: #fff; color: var(--rs-primary); }
.rc-gal-prev { left: 10px; } .rc-gal-next { right: 10px; }

.room-selection-wrapper .room-details-col {
    display: flex; flex-direction: column; padding: 24px 28px !important; min-width: 0; min-height: 0;
}
.room-selection-wrapper .room-card .room-name { font-size: 22px !important; font-weight: 700; color: #1a1a2e !important; margin: 0 0 12px; line-height: 1.25; }
.room-selection-wrapper .room-specs { display: flex; flex-wrap: wrap; gap: 8px 20px; margin-bottom: 14px; }
.room-selection-wrapper .room-card .spec-badge {
    background: none !important; border: 0 !important; box-shadow: none !important; backdrop-filter: none;
    padding: 0 !important; border-radius: 0 !important; font-size: 14px !important; color: #6b7280 !important; font-weight: 400 !important;
}
.room-selection-wrapper .room-card .spec-badge i { color: #6b7280; font-size: 14px; }
.room-selection-wrapper .room-description { font-size: 15px !important; color: #374151 !important; line-height: 1.6 !important; margin: 0 0 16px; }
.room-selection-wrapper .room-desc--full { display: none !important; }
.room-selection-wrapper .room-desc--preview { display: block !important; }

.rc-amenities { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px; margin-bottom: 14px; }
.rc-amenity { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 6px; color: #9ca3af; transition: color .2s; cursor: default; min-width: 0; }
.rc-amenity i { font-size: 20px; }
.rc-amenity span { font-size: 12px; color: #6b7280; line-height: 1.25; overflow-wrap: anywhere; }
.rc-amenity:hover, .rc-amenity:hover span { color: var(--rs-primary); }

.room-selection-wrapper .room-card .room-toggle-btn {
    align-self: flex-start; background: none; border: 0; padding: 4px 0; border-radius: 0; color: var(--rs-primary);
    font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;
}
.room-selection-wrapper .room-card .room-toggle-btn:hover { background: none; color: var(--rs-primary-dark, var(--rs-primary)); }
.room-selection-wrapper .room-toggle-btn__arrow { font-size: 12px; transition: transform .3s ease; }
.room-selection-wrapper .room-card.expanded .room-toggle-btn__arrow { transform: rotate(180deg); }
.rc-more { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s ease, opacity .3s ease; opacity: 0; }
.rc-more-inner { overflow: hidden; min-height: 0; }
.room-card.expanded .rc-more { grid-template-rows: 1fr; opacity: 1; }
.rc-more-text { font-size: 15px; color: #374151; line-height: 1.6; margin: 10px 0 0; }
.rc-more-title { font-weight: 700; color: #1a1a2e; font-size: 14px; margin: 14px 0 6px; }
.rc-more-list { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 6px 16px; }
.rc-more-list li { font-size: 14px; color: #374151; display: flex; gap: 8px; align-items: center; }
.rc-more-list i { color: #9ca3af; width: 16px; text-align: center; }

.room-selection-wrapper .room-card .room-price-col {
    margin-top: auto; border: 0 !important; background: none !important; padding: 18px 0 0 !important;
    display: flex; flex-direction: column; align-items: stretch !important; justify-content: flex-start; min-width: 0 !important; width: 100%;
    position: static; text-align: left;
}
.room-selection-wrapper .room-card .room-price-col .discount-tag { position: static; display: inline-block; align-self: flex-start; margin-bottom: 4px; box-shadow: none !important; }
.room-selection-wrapper .room-card .old-price { display: inline; margin: 0 8px 0 0; font-size: 15px; color: #9ca3af; text-decoration: line-through; }
.room-selection-wrapper .room-card .current-price { display: inline; font-size: 30px !important; font-weight: 800 !important; color: var(--rs-primary) !important; line-height: 1.1; }
.room-selection-wrapper .room-card .price-note { display: inline; font-size: 13px !important; color: #9ca3af !important; text-transform: none !important; letter-spacing: 0 !important; font-weight: 400 !important; margin: 0 0 0 6px !important; }
.room-selection-wrapper .room-card .low-stock-alert { margin: 8px 0 0; order: 0; width: auto; }
.room-selection-wrapper .room-card .select-room-btn {
    width: 100% !important; margin-top: 12px; padding: 13px 20px; border: 0; border-radius: 10px; font-size: 16px; font-weight: 700;
    background: var(--rs-primary); color: #fff; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer;
}
.room-selection-wrapper .room-card .select-room-btn:hover:not(:disabled) { filter: brightness(1.08); transform: none; }
.room-selection-wrapper .room-card .select-room-btn:disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
.room-selection-wrapper .room-card .select-room-btn i { margin: 0 !important; }

@media (max-width: 991px) {
    .room-selection-wrapper .room-list-col.is-wide .room-card-inner,
    .room-selection-wrapper .room-card-inner { grid-template-columns: 1fr !important; min-height: 0 !important; }
    .room-selection-wrapper .room-image-col { height: 220px !important; min-height: 220px; }
}
@media (max-width: 767px) {
    .room-selection-wrapper { overflow-x: hidden; }
    .room-selection-wrapper .room-header-section { display: none; }
    .room-selection-wrapper .filter-toggle-bar { display: none !important; }
    .room-selection-wrapper .room-card { margin-bottom: 20px; overflow: visible; }
    .room-selection-wrapper .room-image-col { border-radius: 12px 12px 0 0; }
    .room-selection-wrapper .room-details-col { padding: 16px !important; }
    .room-selection-wrapper .room-card .room-name { font-size: 20px !important; }
    .rc-amenities { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .rc-more-list { grid-template-columns: 1fr; }
    .room-selection-wrapper .room-card .current-price { font-size: 28px !important; }
    .room-selection-wrapper .room-card .room-price-col { padding: 14px 0 0 !important; }
    .room-selection-wrapper .room-card .select-room-btn {
        position: sticky; bottom: 0; z-index: 20; box-shadow: 0 -6px 16px rgba(15,23,42,.12);
    }
}

/* ========== REDESIGN 2026 v2 ========== */
.room-selection-wrapper .room-card { border: 1px solid #e5e7eb !important; box-shadow: 0 2px 12px rgba(0,0,0,.08); transition: box-shadow .2s ease; }
.room-selection-wrapper .room-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.13); }
.room-selection-wrapper .room-image-col { border-radius: 8px 0 0 8px; }
.room-selection-wrapper .room-card .room-name { font-size: 20px !important; color: #111827 !important; }
.room-selection-wrapper .room-card { line-height: 1.6; }
.rc-amenity span { font-size: 11px; color: #9ca3af; }
.rc-amenities { display: flex; flex-wrap: wrap; gap: 16px; }
.rc-amenity { width: calc((100% - 64px) / 5); }
.rc-amenities, .rc-more { border-top: 1px solid #eef0f3; padding-top: 14px; }
.rc-more { padding-top: 0; border-top: 0; }
.rc-title-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
.rc-badge { flex: none; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 999px; white-space: nowrap; }
.rc-badge--popular { background: #fef3c7; color: #92400e; }
.rc-badge--last { background: #fee2e2; color: #b91c1c; }
.rc-counter { position: absolute; right: 10px; bottom: 76px; z-index: 6; background: rgba(0,0,0,.55); color: #fff; font-size: 12px; padding: 2px 9px; border-radius: 999px; }
.rc-thumbs { position: absolute; left: 0; right: 0; bottom: 0; z-index: 6; display: flex; gap: 6px; padding: 8px 10px; overflow-x: auto; background: linear-gradient(transparent, rgba(0,0,0,.45)); scrollbar-width: none; }
.rc-thumbs::-webkit-scrollbar { display: none; }
.rc-thumb { flex: none; width: 60px; height: 40px; padding: 0; border: 2px solid transparent; border-radius: 4px; overflow: hidden; cursor: pointer; background: #000; opacity: .8; }
.rc-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.rc-thumb.active { border-color: #fff; opacity: 1; }
.room-selection-wrapper .room-image-col .image-badge { display: none; }
.rc-price-line { display: block; }
.room-selection-wrapper .room-card .select-room-btn { height: 48px; padding: 0 20px; border-radius: 8px; font-weight: 600; transition: filter .2s, transform .1s; }
.room-selection-wrapper .room-card .select-room-btn:hover:not(:disabled) { filter: brightness(.9); }
.room-selection-wrapper .room-card .select-room-btn:active:not(:disabled) { transform: scale(.98); }
.room-selection-wrapper .results-count { flex: 1; text-align: center; }
.room-selection-wrapper .room-list-header { justify-content: center; position: relative; }
.room-selection-wrapper .sort-select { margin-left: 0; position: absolute; right: 0; }
.room-selection-wrapper .room-list-header .results-count { width: 100%; }
.rc-more { transition: grid-template-rows .3s ease, opacity .3s ease; }

.rc-pending { display: none; }
.rc-skel-card { display: grid; grid-template-columns: 40% 60%; min-height: 320px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
.rc-skel-body { padding: 24px 28px; display: flex; flex-direction: column; gap: 14px; }
.rc-skel-line { height: 16px; border-radius: 6px; }
.rc-shimmer { background: linear-gradient(90deg, #eceff3 25%, #f8f9fb 50%, #eceff3 75%); background-size: 200% 100%; animation: rcShimmer 1.3s linear infinite; }
@keyframes rcShimmer { from { background-position: 200% 0; } to { background-position: -200% 0; } }

@media (max-width: 991px) {
    .room-selection-wrapper .room-image-col { border-radius: 12px 12px 0 0; }
    .rc-skel-card { grid-template-columns: 1fr; }
    .rc-skel-img { height: 220px; }
}
@media (max-width: 767px) {
    .room-selection-wrapper { overflow-x: clip; }
    .room-selection-wrapper .room-header-section { display: block; padding-top: 12px !important; }
    .room-selection-wrapper .header-title { display: none; }
    .room-selection-wrapper .header-info-alert { font-size: 13px !important; min-height: 44px; }
    .rc-thumbs { position: static; background: #fff; padding: 8px 12px; }
    .rc-thumb { border-color: transparent; }
    .rc-thumb.active { border-color: var(--rs-primary); }
    .room-selection-wrapper .room-image-col { overflow: visible; height: auto !important; min-height: 0; }
    .room-selection-wrapper .room-image-col > a { position: relative; inset: auto; height: 220px; border-radius: 12px 12px 0 0; overflow: hidden; }
    .room-selection-wrapper .room-image-col .room-image-placeholder { height: 220px; }
    .rc-gal-btn { top: 110px; }
    .rc-counter { bottom: auto; top: 190px; right: 10px; }
    .rc-amenity { width: calc((100% - 32px) / 3); }
    .room-selection-wrapper .room-card .room-details-col { padding-bottom: 0 !important; }
    .room-selection-wrapper .room-card .room-price-col {
        position: sticky; bottom: 0; z-index: 20; background: #fff !important; margin: 14px -16px 0; width: auto;
        padding: 0 16px !important; min-height: 56px; box-shadow: 0 -2px 8px rgba(0,0,0,.08);
        display: grid; grid-template-columns: 1fr auto; align-items: center; column-gap: 12px; border-radius: 0 0 12px 12px;
    }
    .room-selection-wrapper .room-card .room-price-col > * { grid-column: 1; }
    .room-selection-wrapper .room-card .room-price-col .select-room-btn { grid-column: 2; grid-row: 1 / span 4; width: auto !important; height: 44px; margin: 0; box-shadow: none; padding: 0 16px; }
    .room-selection-wrapper .room-card .room-price-col .select-room-btn::after { content: ' \2192'; }
    .room-selection-wrapper .room-card .current-price { font-size: 22px !important; }
    .room-selection-wrapper .room-card .discount-tag, .room-selection-wrapper .room-card .low-stock-alert { font-size: 11px; }
}
</style>
@endpush

@section('front-content')
<div class="room-selection-wrapper">

    {{-- MOBILE APP HEADER --}}
    <div class="mobile-room-header">
        <a href="{{ route('front.tourbooking.services.show', $service->slug) }}" class="mobile-room-header__back">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <span class="mobile-room-header__title">{{ $service->translation->title }}</span>
        <span class="mobile-room-header__count">{{ $rooms->count() }} номеров</span>
    </div>

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
                            <li><a href="{{ route('front.tourbooking.services') }}">Услуги</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li><a href="{{ route('front.tourbooking.services.show', $service->slug) }}">{{ $service->translation->title }}</a></li>
                            <li><i class="fa-sharp fa-solid fa-angle-right"></i></li>
                            <li><span>Выберите номер</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Шапка --}}
    <section class="room-header-section">
        <div class="container">
            <h1 class="header-title">{{ $service->translation->title }}</h1>
            <div class="header-info-alert">
                <i class="fa-solid fa-circle-info"></i>
                <span>Найдено <strong>{{ $rooms->count() }}</strong> номеров. Выберите номер и укажите даты проживания.</span>
            </div>
        </div>
    </section>

    {{-- MOBILE FILTER BAR --}}
    <div class="mobile-filter-bar">
        <button class="mobile-filter-chip" onclick="openMobileFilterSheet()">
            <i class="fa-solid fa-sliders"></i> Фильтры
        </button>
        <button class="mobile-filter-chip" id="mSortChip" onclick="toggleMobileSort()">
            <i class="fa-solid fa-arrow-down-wide-short"></i> Сортировка
        </button>
        @php $bedTypes = $rooms->pluck('bed_type')->filter()->unique(); @endphp
        @foreach($bedTypes as $bed)
            <button class="mobile-filter-chip mobile-bed-chip" data-bed="{{ $bed }}" onclick="toggleMobileBedFilter(this)">
                <i class="fa-solid fa-bed"></i> {{ $bed }}
            </button>
        @endforeach
    </div>

    {{-- MOBILE FILTER BOTTOM SHEET --}}
    <div class="mobile-filter-sheet-backdrop" id="mFilterBackdrop" onclick="closeMobileFilterSheet()"></div>
    <div class="mobile-filter-sheet" id="mFilterSheet">
        <div class="mobile-filter-sheet__handle"></div>
        <div class="mobile-filter-sheet__title">
            Фильтры
            <button class="mobile-filter-sheet__close" onclick="closeMobileFilterSheet()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        
        <div class="filter-group">
            <div class="filter-group-title">Цена за ночь</div>
            <div class="price-range-inputs">
                <input type="number" id="mPriceMin" class="price-input" placeholder="От">
                <span>—</span>
                <input type="number" id="mPriceMax" class="price-input" placeholder="До">
            </div>
        </div>
        
        <div class="filter-group">
            <div class="filter-group-title">Количество гостей</div>
            @foreach([1, 2, 3, 4] as $g)
                <div class="filter-checkbox">
                    <input type="checkbox" id="mGuests{{ $g }}" class="m-filter-cb" data-filter="guests" value="{{ $g }}">
                    <label for="mGuests{{ $g }}">{{ $g }} {{ $g == 1 ? 'гость' : ($g < 5 ? 'гостя' : 'гостей') }}</label>
                </div>
            @endforeach
        </div>

        <button type="button" class="filter-btn" onclick="applyMobileFilters()">
            <i class="fa-solid fa-check me-2"></i>Применить
        </button>
        <button type="button" class="filter-btn secondary" onclick="clearMobileFilters()">
            <i class="fa-solid fa-times me-2"></i>Сбросить
        </button>
    </div>

    {{-- Основной контент --}}
    <section class="room-main-section">
        <div class="container">
            <div class="row">
                {{-- Desktop filter sidebar (collapsible) --}}
                <div class="col-lg-3 mb-4 filter-sidebar-col is-collapsed" id="filterSidebarCol">
                    <div class="filter-sidebar">
                        <h3 class="filter-title">
                            <i class="fa-solid fa-sliders me-2"></i>Фильтры
                        </h3>
                        
                        <div class="filter-group">
                            <div class="filter-group-title">Цена за ночь</div>
                            <div class="price-range-inputs">
                                <input type="number" id="priceMin" class="price-input" placeholder="От">
                                <span>—</span>
                                <input type="number" id="priceMax" class="price-input" placeholder="До">
                            </div>
                        </div>
                        
                        <div class="filter-group">
                            <div class="filter-group-title">Количество гостей</div>
                            @foreach([1, 2, 3, 4] as $g)
                                <div class="filter-checkbox">
                                    <input type="checkbox" id="guests{{ $g }}" class="filter-cb" data-filter="guests" value="{{ $g }}">
                                    <label for="guests{{ $g }}">{{ $g }} {{ $g == 1 ? 'гость' : ($g < 5 ? 'гостя' : 'гостей') }}</label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="filter-group">
                            <div class="filter-group-title">Тип кровати</div>
                            @foreach($bedTypes as $bed)
                                <div class="filter-checkbox">
                                    <input type="checkbox" id="bed{{ Str::slug($bed) }}" class="filter-cb" data-filter="beds" value="{{ $bed }}">
                                    <label for="bed{{ Str::slug($bed) }}">{{ $bed }}</label>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="filter-btn" onclick="applyFilters()">
                            <i class="fa-solid fa-check me-2"></i>Применить
                        </button>
                        <button type="button" class="filter-btn secondary" onclick="clearFilters()">
                            <i class="fa-solid fa-times me-2"></i>Сбросить
                        </button>
                    </div>
                </div>
                
                {{-- Room list --}}
                <div class="col-lg-9 room-list-col is-wide" id="roomListCol">
                    <div class="filter-toggle-bar">
                        <button type="button" class="filter-toggle-btn" id="filterToggleBtn">
                            <i class="fa-solid fa-sliders"></i>
                            <span>Фильтры</span>
                            <span class="toggle-badge" id="filterBadge" style="display:none">0</span>
                        </button>
                        <div class="room-list-header" style="flex:1; margin:0;">
                            <span id="resultsCount" class="results-count">{{ $rooms->count() }} номеров найдено</span>
                            <select id="sortSelect" class="sort-select" onchange="applyFilters()">
                                <option value="default">По умолчанию</option>
                                <option value="price_asc">Цена: по возрастанию</option>
                                <option value="price_desc">Цена: по убыванию</option>
                                <option value="size_desc">Площадь: по убыванию</option>
                                <option value="guests_desc">Вместимость: по убыванию</option>
                            </select>
                        </div>
                    </div>

                    <div id="roomSkeleton" class="rc-skeleton-wrap" aria-hidden="true">
                        @for($sk = 0; $sk < min(max($rooms->count(), 1), 3); $sk++)
                            <div class="rc-skel-card">
                                <div class="rc-skel-img rc-shimmer"></div>
                                <div class="rc-skel-body">
                                    <div class="rc-skel-line rc-shimmer" style="width:55%;height:24px"></div>
                                    <div class="rc-skel-line rc-shimmer" style="width:40%"></div>
                                    <div class="rc-skel-line rc-shimmer" style="width:90%;height:40px"></div>
                                    <div class="rc-skel-line rc-shimmer" style="width:70%"></div>
                                    <div class="rc-skel-line rc-shimmer" style="width:100%;height:48px;margin-top:auto"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <noscript><style>#roomSkeleton{display:none!important}#roomListContainer{display:block!important}</style></noscript>
                    <div id="roomListContainer" class="rc-pending">
                        @include('tourbooking::front.rooms._room_list', ['rooms' => $rooms])
                    </div>
                </div>

                <div class="filter-backdrop" id="filterBackdrop"></div>
            </div>
        </div>
    </section>
</div>

{{-- МОДАЛЬНОЕ ОКНО БРОНИРОВАНИЯ --}}
<div class="booking-modal-overlay" id="bookingModal">
    <div class="booking-modal">
        <div class="modal-header">
            <button type="button" class="modal-close-btn" onclick="closeBookingModal()">
                <i class="fa-solid fa-times"></i>
            </button>
            <div class="modal-room-info">
                <img src="" alt="" class="modal-room-thumb" id="modalRoomThumb">
                <div>
                    <h3 class="modal-room-name" id="modalRoomName">Номер</h3>
                    <p class="modal-room-price">
                        <span id="modalRoomPrice">{{ currency(0) }}</span> / ночь
                    </p>
                </div>
            </div>
        </div>
        
        <div class="modal-body">
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fa-solid fa-calendar-days"></i> Даты проживания
                </div>
                <div class="date-range-container">
                    <div class="date-input-group">
                        <label>Дата заезда</label>
                        <input type="text" id="modalCheckIn" class="date-input" placeholder="Выберите дату" readonly>
                    </div>
                    <div class="date-input-group">
                        <label>Дата выезда</label>
                        <input type="text" id="modalCheckOut" class="date-input" placeholder="Выберите дату" readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fa-solid fa-users"></i> Гости
                </div>
                <div id="modalBaseHint" class="modal-base-hint" style="display:none">
                    <i class="fa-solid fa-circle-info"></i>
                    <span></span>
                </div>
                <div class="guest-counters">
                    <div class="guest-counter-box">
                        <div class="guest-counter-label">
                            <i class="fa-solid fa-user"></i>
                            <div><span>Взрослые</span><small>от 18 лет</small></div>
                        </div>
                        <div class="counter-controls">
                            <button type="button" class="counter-btn" onclick="updateCounter('adults', -1)"><i class="fa-solid fa-minus"></i></button>
                            <span class="counter-value" id="adultsCount">2</span>
                            <button type="button" class="counter-btn" onclick="updateCounter('adults', 1)"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="guest-counter-box">
                        <div class="guest-counter-label">
                            <i class="fa-solid fa-child"></i>
                            <div><span>Дети</span><small>0-17 лет</small></div>
                        </div>
                        <div class="counter-controls">
                            <button type="button" class="counter-btn" onclick="updateCounter('children', -1)"><i class="fa-solid fa-minus"></i></button>
                            <span class="counter-value" id="childrenCount">0</span>
                            <button type="button" class="counter-btn" onclick="updateCounter('children', 1)"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section" id="modalExtrasSection" style="display:none">
                <div class="form-section-title">
                    <i class="fa-solid fa-circle-plus"></i> Дополнительные опции
                </div>
                <div id="modalExtrasList"></div>
            </div>
            
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fa-solid fa-receipt"></i> Расчёт стоимости
                </div>
                <div class="price-summary">
                    <div class="price-row">
                        <span>Базовая цена за ночь</span>
                        <span id="summaryNightPrice">{{ currency(0) }}</span>
                    </div>
                    <div id="summaryExtras"></div>
                    <div class="price-row">
                        <span>Количество ночей</span>
                        <span class="nights-badge" id="summaryNights">0 ночей</span>
                    </div>
                    <div class="price-row">
                        <span>Гости</span>
                        <span id="summaryGuests">2 взр.</span>
                    </div>
                    <div class="price-row total">
                        <span>Итого к оплате</span>
                        <span class="price-value" id="summaryTotal">{{ currency(0) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="confirm-booking-btn" id="confirmBookingBtn" onclick="confirmBooking()">
                <i class="fa-solid fa-check"></i> Подтвердить и продолжить
            </button>
        </div>
    </div>
</div>

{{-- Скрытые поля --}}
<input type="hidden" id="selectedRoomId" value="">
<input type="hidden" id="selectedRoomPrice" value="0">
<input type="hidden" id="selectedRoomMaxGuests" value="4">

{{-- ЛАЙТБОКС --}}
<div class="room-lightbox-modal" id="roomLightboxModal">
    <div class="lightbox-header">
        <span class="lightbox-title" id="lightboxTitle"></span>
        <span class="lightbox-counter" id="lightboxCounter"></span>
        <button class="lightbox-close" id="closeLightbox"><i class="fa-solid fa-times"></i></button>
    </div>
    <div class="lightbox-body">
        <button class="lightbox-nav prev" id="lightboxPrev"><i class="fa-solid fa-chevron-left"></i></button>
        <div class="lightbox-image-container" id="lightboxImageContainer">
            <img src="" alt="" id="lightboxImage">
        </div>
        <div class="lightbox-video-container" id="lightboxVideoContainer">
            <iframe id="lightboxVideo" allowfullscreen></iframe>
        </div>
        <button class="lightbox-nav next" id="lightboxNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    <div class="lightbox-thumbnails" id="lightboxThumbnails"></div>
</div>
@endsection

@push('js_section')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>

<script>
(function() {
    'use strict';
    
    // ========== CURRENCY HELPER (Session valyutası) ==========
    const CURRENCY_ICON = @json(session('currency')['currency_icon'] ?? default_currency()['currency_icon']);
    const CURRENCY_RATE = parseFloat(@json(session('currency')['currency_rate'] ?? default_currency()['currency_rate']));
    const CURRENCY_POSITION = @json(session('currency')['currency_position'] ?? default_currency()['currency_position']);

    function formatCurrencyDynamic(amount) {
        amount = parseFloat(amount) * CURRENCY_RATE;
        let formatted = amount.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});

        switch(CURRENCY_POSITION) {
            case 'before_price': return CURRENCY_ICON + formatted;
            case 'before_price_with_space': return CURRENCY_ICON + ' ' + formatted;
            case 'after_price': return formatted + CURRENCY_ICON;
            case 'after_price_with_space': return formatted + ' ' + CURRENCY_ICON;
            default: return CURRENCY_ICON + formatted;
        }
    }

    const isMobile = window.innerWidth <= 991;

    // ========== BOOKING MODAL ==========
    let bookingData = {
        roomId: null, roomName: '', roomThumb: '',
        pricePerNight: 0, maxGuests: 4,
        checkIn: null, checkOut: null,
        adults: 2, children: 0, nights: 0, total: 0
    };
    
    let checkInPicker, checkOutPicker;
    
    document.addEventListener('DOMContentLoaded', function() {
        checkInPicker = flatpickr('#modalCheckIn', {
            dateFormat: 'd.m.Y',
            minDate: 'today',
            locale: 'ru',
            disableMobile: true,
            onChange: function(selectedDates) {
                if (selectedDates[0]) {
                    bookingData.checkIn = selectedDates[0];
                    const nextDay = new Date(selectedDates[0]);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkOutPicker.set('minDate', nextDay);
                    if (bookingData.checkOut && bookingData.checkOut <= selectedDates[0]) {
                        checkOutPicker.clear();
                        bookingData.checkOut = null;
                    }
                    calculatePrice();
                }
            }
        });
        
        checkOutPicker = flatpickr('#modalCheckOut', {
            dateFormat: 'd.m.Y',
            minDate: new Date().fp_incr(1),
            locale: 'ru',
            disableMobile: true,
            onChange: function(selectedDates) {
                if (selectedDates[0]) {
                    bookingData.checkOut = selectedDates[0];
                    calculatePrice();
                }
            }
        });
        
        initRoomSelection();
        initLightbox();
    });
    
    window.openBookingModal = function(roomId, roomName, roomThumb, price, maxGuests, basePriceFor, extras) {
        bookingData.roomId = roomId;
        bookingData.roomName = roomName;
        bookingData.roomThumb = roomThumb;
        bookingData.pricePerNight = parseFloat(price);
        bookingData.maxGuests = parseInt(maxGuests);
        bookingData.basePriceFor = parseInt(basePriceFor) || parseInt(maxGuests) || 2;
        bookingData.extras = Array.isArray(extras) ? extras : [];
        bookingData.adults = bookingData.basePriceFor;
        bookingData.children = 0;
        bookingData.checkIn = null;
        bookingData.checkOut = null;
        bookingData.selectedOptional = {};

        document.getElementById('modalRoomName').textContent = roomName;
        document.getElementById('modalRoomPrice').textContent = formatCurrencyDynamic(price);
        document.getElementById('modalRoomThumb').src = roomThumb || '{{ asset("frontend/assets/img/shape/placeholder.png") }}';
        document.getElementById('selectedRoomId').value = roomId;
        document.getElementById('selectedRoomPrice').value = price;
        document.getElementById('selectedRoomMaxGuests').value = maxGuests;
        document.getElementById('adultsCount').textContent = bookingData.adults;
        document.getElementById('childrenCount').textContent = '0';

        // Render base hint
        var hint = document.getElementById('modalBaseHint');
        if (hint) {
            hint.style.display = '';
            hint.querySelector('span').innerHTML =
                'Базовая цена <strong>' + formatCurrencyDynamic(price) + '</strong> ' +
                'указана для <strong>' + bookingData.basePriceFor + ' гостя(ей)</strong>. ' +
                'Дополнительные взрослые/дети добавятся ниже автоматически.';
        }

        // Render optional extras checkboxes
        renderOptionalExtras();

        checkInPicker.clear();
        checkOutPicker.clear();
        calculatePrice();

        document.getElementById('bookingModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    function renderOptionalExtras() {
        var section = document.getElementById('modalExtrasSection');
        var list = document.getElementById('modalExtrasList');
        if (!section || !list) return;
        list.innerHTML = '';
        var optionals = (bookingData.extras || []).filter(function(e) { return e.type === 'optional'; });
        if (!optionals.length) {
            section.style.display = 'none';
            return;
        }
        section.style.display = '';
        optionals.forEach(function(ex) {
            var row = document.createElement('div');
            row.className = 'modal-extra-row';
            row.innerHTML =
                '<div class="extra-info">' +
                    '<input type="checkbox" id="rs-ex-' + ex.id + '" data-extra-id="' + ex.id + '" data-extra-price="' + ex.price + '" data-extra-name="' + (ex.name || '').replace(/"/g, '&quot;') + '">' +
                    '<label for="rs-ex-' + ex.id + '">' + (ex.name || '') + '</label>' +
                '</div>' +
                '<span class="extra-price">+' + formatCurrencyDynamic(ex.price) + ' / ночь</span>';
            row.querySelector('input').addEventListener('change', function(e) {
                bookingData.selectedOptional[ex.id] = e.target.checked ? ex : null;
                calculatePrice();
            });
            list.appendChild(row);
        });
    }
    
    window.closeBookingModal = function() {
        document.getElementById('bookingModal').classList.remove('active');
        document.body.style.overflow = '';
    };
    
    window.updateCounter = function(type, delta) {
        const max = bookingData.maxGuests;
        if (type === 'adults') {
            let v = bookingData.adults + delta;
            if (v >= 1 && (v + bookingData.children) <= max) {
                bookingData.adults = v;
                document.getElementById('adultsCount').textContent = v;
            }
        } else {
            let v = bookingData.children + delta;
            if (v >= 0 && (bookingData.adults + v) <= max) {
                bookingData.children = v;
                document.getElementById('childrenCount').textContent = v;
            }
        }
        calculatePrice();
    };
    
    function calculatePrice() {
        let nights = 0;
        if (bookingData.checkIn && bookingData.checkOut) {
            nights = Math.ceil((bookingData.checkOut - bookingData.checkIn) / (1000 * 60 * 60 * 24));
        }
        bookingData.nights = nights;

        // Calculate extras
        var basePriceFor = bookingData.basePriceFor || 2;
        var extraAdults = Math.max(0, bookingData.adults - basePriceFor);
        var extraChildren = bookingData.children;
        var extras = bookingData.extras || [];

        var adultExtra = extras.find(function(e) { return e.type === 'adult'; });
        var childExtra = extras.find(function(e) { return e.type === 'child'; });

        var extrasPerNight = 0;
        var breakdownHtml = '';

        if (extraAdults > 0 && adultExtra) {
            var amt = adultExtra.price * extraAdults;
            extrasPerNight += amt;
            breakdownHtml += '<div class="price-row"><span class="extra-name"><i class="fa-solid fa-user-plus"></i>' +
                ' ' + extraAdults + ' × ' + adultExtra.name + '</span>' +
                '<span class="extra-amount">+' + formatCurrencyDynamic(amt) + '</span></div>';
        } else if (extraAdults > 0) {
            breakdownHtml += '<div class="price-row text-warning"><span>' + extraAdults + ' доп. взрослый(е)</span><span>—</span></div>';
        }

        if (extraChildren > 0 && childExtra) {
            var amtC = childExtra.price * extraChildren;
            extrasPerNight += amtC;
            breakdownHtml += '<div class="price-row"><span class="extra-name"><i class="fa-solid fa-child"></i>' +
                ' ' + extraChildren + ' × ' + childExtra.name + '</span>' +
                '<span class="extra-amount">+' + formatCurrencyDynamic(amtC) + '</span></div>';
        }

        // Optional selected
        Object.keys(bookingData.selectedOptional || {}).forEach(function(k) {
            var ex = bookingData.selectedOptional[k];
            if (!ex) return;
            extrasPerNight += parseFloat(ex.price);
            breakdownHtml += '<div class="price-row"><span class="extra-name"><i class="fa-solid fa-star"></i> ' +
                ex.name + '</span><span class="extra-amount">+' + formatCurrencyDynamic(ex.price) + '</span></div>';
        });

        var summaryExtras = document.getElementById('summaryExtras');
        if (summaryExtras) summaryExtras.innerHTML = breakdownHtml;

        var totalPerNight = bookingData.pricePerNight + extrasPerNight;
        bookingData.totalPerNight = totalPerNight;
        bookingData.total = nights * totalPerNight;

        document.getElementById('summaryNightPrice').textContent = formatCurrencyDynamic(bookingData.pricePerNight);
        document.getElementById('summaryNights').textContent = nights + ' ' + getNightsWord(nights);

        let gt = bookingData.adults + ' взр.';
        if (bookingData.children > 0) gt += ', ' + bookingData.children + ' дет.';
        document.getElementById('summaryGuests').textContent = gt;
        document.getElementById('summaryTotal').textContent = formatCurrencyDynamic(bookingData.total);
        document.getElementById('confirmBookingBtn').disabled = !(nights > 0);
    }
    
    function getNightsWord(n) {
        if (n === 0) return 'ночей';
        if (n === 1) return 'ночь';
        if (n >= 2 && n <= 4) return 'ночи';
        return 'ночей';
    }
    
    window.confirmBooking = function() {
        if (!bookingData.checkIn || !bookingData.checkOut) {
            alert('Пожалуйста, выберите даты заезда и выезда');
            return;
        }
        const btn = document.getElementById('confirmBookingBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Загрузка...';

        const fmt = d => d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');

        var optionalIds = Object.keys(bookingData.selectedOptional || {})
            .filter(function(k) { return bookingData.selectedOptional[k]; });
        var optParam = optionalIds.length ? '&optional_extras=' + optionalIds.join(',') : '';

        window.location.href = '{{ route("front.tourbooking.guest-booking-form", $service->slug) }}' +
            '?room_id=' + bookingData.roomId +
            '&check_in=' + fmt(bookingData.checkIn) +
            '&check_out=' + fmt(bookingData.checkOut) +
            '&adults=' + bookingData.adults +
            '&children=' + bookingData.children +
            '&nights=' + bookingData.nights +
            '&total=' + bookingData.total +
            optParam;
    };
    
    document.getElementById('bookingModal').addEventListener('click', function(e) { if (e.target === this) closeBookingModal(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeBookingModal(); });
    
    // ========== EXPAND / COLLAPSE ==========
    (function () {
        var done = false;
        function reveal() {
            if (done) return; done = true;
            var sk = document.getElementById('roomSkeleton');
            var ct = document.getElementById('roomListContainer');
            if (sk) sk.style.display = 'none';
            if (ct) ct.classList.remove('rc-pending');
        }
        window.addEventListener('load', function () { setTimeout(reveal, 250); });
        setTimeout(reveal, 4000);
    })();

    window.rcGallery = function(btn, dir) {
        var col = btn.closest('.room-image-col');
        var link = col.querySelector('.room-lightbox-trigger');
        var img = link && link.querySelector('img');
        if (!img) return;
        var list = [];
        try { list = JSON.parse(link.dataset.images || '[]'); } catch (e) {}
        if (list.length < 2) return;
        var i = parseInt(col.dataset.rcIdx || '0', 10);
        i = (i + dir + list.length) % list.length;
        col.dataset.rcIdx = i;
        img.src = list[i];
        rcSync(col, i);
    };
    window.rcGalleryGo = function(btn, i) {
        var col = btn.closest('.room-image-col');
        var link = col.querySelector('.room-lightbox-trigger');
        var img = link && link.querySelector('img');
        var list = [];
        try { list = JSON.parse(link.dataset.images || '[]'); } catch (e) {}
        if (!img || !list[i]) return;
        col.dataset.rcIdx = i;
        img.src = list[i];
        rcSync(col, i);
    };
    function rcSync(col, i) {
        var c = col.querySelector('.rc-counter-cur');
        if (c) c.textContent = i + 1;
        col.querySelectorAll('.rc-thumb').forEach(function(t, k) {
            t.classList.toggle('active', k === i);
            if (k === i) t.scrollIntoView({ block: 'nearest', inline: 'nearest' });
        });
    }

    window.toggleRoomDetails = function(btn) {
        var card = btn.closest('.room-card');
        var label = btn.querySelector('.room-toggle-btn__label');
        if (card.classList.contains('expanded')) {
            card.classList.remove('expanded');
            if (label) label.textContent = 'Подробнее';
        } else {
            card.classList.add('expanded');
            if (label) label.textContent = 'Свернуть';
        }
    };

    // ========== ROOM SELECTION ==========
    function initRoomSelection() {
        document.getElementById('roomListContainer').addEventListener('click', function(e) {
            const btn = e.target.closest('.select-room-btn');
            if (!btn || btn.disabled) return;

            const card = btn.closest('.room-card');
            const roomId = btn.dataset.roomId;
            const roomName = btn.dataset.roomName || card.querySelector('.room-name')?.textContent || 'Номер';
            const price = parseFloat(btn.dataset.roomPrice || card.dataset.price || 0);
            const maxGuests = parseInt(btn.dataset.maxGuests || card.dataset.guests || 4);
            const basePriceFor = parseInt(btn.dataset.basePriceFor || maxGuests);
            let extras = [];
            try { extras = JSON.parse(btn.dataset.extras || '[]'); } catch (err) { extras = []; }
            let roomThumb = '';
            const thumbImg = card.querySelector('.room-image-col img');
            if (thumbImg) roomThumb = thumbImg.src;

            openBookingModal(roomId, roomName, roomThumb, price, maxGuests, basePriceFor, extras);
        });
    }
    
    // ========== DESKTOP FILTERS ==========
    window.applyFilters = function() {
        const sortBy = document.getElementById('sortSelect').value;
        const minPrice = parseFloat(document.getElementById('priceMin').value) || 0;
        const maxPrice = parseFloat(document.getElementById('priceMax').value) || Infinity;
        
        const selectedGuests = [];
        const selectedBeds = [];
        
        document.querySelectorAll('.filter-cb:checked').forEach(function(cb) {
            if (cb.dataset.filter === 'guests') selectedGuests.push(parseInt(cb.value));
            if (cb.dataset.filter === 'beds') selectedBeds.push(cb.value);
        });
        
        filterAndSort(sortBy, minPrice, maxPrice, selectedGuests, selectedBeds);
    };
    
    window.clearFilters = function() {
        document.querySelectorAll('.filter-cb').forEach(cb => cb.checked = false);
        document.getElementById('priceMin').value = '';
        document.getElementById('priceMax').value = '';
        applyFilters();
    };

    // ========== MOBILE FILTERS ==========
    window.openMobileFilterSheet = function() {
        document.getElementById('mFilterSheet').classList.add('open');
        document.getElementById('mFilterSheet').style.display = 'block';
        document.getElementById('mFilterBackdrop').classList.add('open');
        document.body.style.overflow = 'hidden';
        // Small delay for animation
        requestAnimationFrame(() => {
            document.getElementById('mFilterSheet').classList.add('open');
        });
    };

    window.closeMobileFilterSheet = function() {
        document.getElementById('mFilterSheet').classList.remove('open');
        document.getElementById('mFilterBackdrop').classList.remove('open');
        document.body.style.overflow = '';
        setTimeout(() => {
            document.getElementById('mFilterSheet').style.display = 'none';
        }, 350);
    };

    window.applyMobileFilters = function() {
        const minPrice = parseFloat(document.getElementById('mPriceMin').value) || 0;
        const maxPrice = parseFloat(document.getElementById('mPriceMax').value) || Infinity;
        
        const selectedGuests = [];
        document.querySelectorAll('.m-filter-cb:checked').forEach(cb => {
            if (cb.dataset.filter === 'guests') selectedGuests.push(parseInt(cb.value));
        });

        const selectedBeds = [];
        document.querySelectorAll('.mobile-bed-chip.active').forEach(chip => {
            selectedBeds.push(chip.dataset.bed);
        });

        const sortBy = document.getElementById('sortSelect').value;
        filterAndSort(sortBy, minPrice, maxPrice, selectedGuests, selectedBeds);
        closeMobileFilterSheet();
    };

    window.clearMobileFilters = function() {
        document.querySelectorAll('.m-filter-cb').forEach(cb => cb.checked = false);
        document.getElementById('mPriceMin').value = '';
        document.getElementById('mPriceMax').value = '';
        document.querySelectorAll('.mobile-bed-chip').forEach(c => c.classList.remove('active'));
        applyMobileFilters();
    };

    window.toggleMobileBedFilter = function(chip) {
        chip.classList.toggle('active');
        applyMobileFilters();
    };

    // Sort toggle for mobile
    let mobileSortOptions = ['default', 'price_asc', 'price_desc', 'size_desc'];
    let mobileSortLabels = ['По умолчанию', 'Цена ↑', 'Цена ↓', 'Площадь ↓'];
    let mobileSortIndex = 0;

    window.toggleMobileSort = function() {
        mobileSortIndex = (mobileSortIndex + 1) % mobileSortOptions.length;
        document.getElementById('sortSelect').value = mobileSortOptions[mobileSortIndex];
        document.getElementById('mSortChip').innerHTML = '<i class="fa-solid fa-arrow-down-wide-short"></i> ' + mobileSortLabels[mobileSortIndex];
        applyFilters();
    };
    
    // ========== SHARED FILTER ENGINE ==========
    function filterAndSort(sortBy, minPrice, maxPrice, selectedGuests, selectedBeds) {
        const cards = document.querySelectorAll('.room-card');
        let arr = [];
        let visible = 0;
        
        cards.forEach(function(card) {
            const price = parseFloat(card.dataset.price) || 0;
            const guests = parseInt(card.dataset.guests) || 0;
            const size = parseInt(card.dataset.size) || 0;
            const bed = card.dataset.bed || '';
            
            let show = true;
            if (price < minPrice || price > maxPrice) show = false;
            if (selectedGuests.length > 0 && !selectedGuests.includes(guests)) show = false;
            if (selectedBeds.length > 0 && !selectedBeds.some(b => bed.includes(b))) show = false;
            
            arr.push({ el: card, price, guests, size, show });
        });
        
        arr.sort(function(a, b) {
            switch(sortBy) {
                case 'price_asc': return a.price - b.price;
                case 'price_desc': return b.price - a.price;
                case 'size_desc': return b.size - a.size;
                case 'guests_desc': return b.guests - a.guests;
                default: return 0;
            }
        });
        
        const container = document.getElementById('roomListContainer');
        arr.forEach(function(item) {
            item.el.style.display = item.show ? '' : 'none';
            if (item.show) visible++;
            container.appendChild(item.el);
        });
        
        document.getElementById('resultsCount').textContent = visible + ' номеров найдено';
        
        let noEl = document.getElementById('noResultsMsg');
        if (visible === 0) {
            if (!noEl) {
                noEl = document.createElement('div');
                noEl.id = 'noResultsMsg';
                noEl.className = 'no-results';
                noEl.innerHTML = '<i class="fa-solid fa-search"></i><h4>Номера не найдены</h4><p>Попробуйте изменить параметры фильтров</p>';
                container.appendChild(noEl);
            }
        } else if (noEl) {
            noEl.remove();
        }
    }
    
    // ========== FILTER SIDEBAR TOGGLE ==========
    (function () {
        var btn = document.getElementById('filterToggleBtn');
        var sidebar = document.getElementById('filterSidebarCol');
        var roomCol = document.getElementById('roomListCol');
        var backdrop = document.getElementById('filterBackdrop');
        if (!btn || !sidebar || !roomCol) return;

        function isMobile() { return window.innerWidth < 992; }

        function open() {
            sidebar.classList.remove('is-collapsed');
            sidebar.classList.add('is-open');
            btn.classList.add('is-active');
            roomCol.classList.remove('is-wide');
            if (isMobile() && backdrop) backdrop.classList.add('is-visible');
        }
        function close() {
            sidebar.classList.add('is-collapsed');
            sidebar.classList.remove('is-open');
            btn.classList.remove('is-active');
            roomCol.classList.add('is-wide');
            if (backdrop) backdrop.classList.remove('is-visible');
        }
        function toggle() {
            if (sidebar.classList.contains('is-collapsed')) open(); else close();
        }
        btn.addEventListener('click', toggle);
        if (backdrop) backdrop.addEventListener('click', close);

        // Update badge based on active filters
        window.updateFilterBadge = function () {
            var badge = document.getElementById('filterBadge');
            if (!badge) return;
            var n = document.querySelectorAll('.filter-cb:checked').length;
            var minP = parseFloat(document.getElementById('priceMin')?.value || 0);
            var maxP = parseFloat(document.getElementById('priceMax')?.value || 0);
            if (minP > 0 || maxP > 0) n++;
            if (n > 0) { badge.style.display = ''; badge.textContent = n; }
            else { badge.style.display = 'none'; }
        };
        document.addEventListener('change', function (e) {
            if (e.target.matches('.filter-cb, #priceMin, #priceMax, #sortSelect')) updateFilterBadge();
        });
        updateFilterBadge();
    })();

    // ========== LIGHTBOX ==========
    let lbImages = [], lbIndex = 0, lbVideoUrl = '', lbRoomName = '';
    
    function initLightbox() {
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.room-lightbox-trigger');
            if (trigger) { e.preventDefault(); openLightbox(trigger); }
        });
        
        document.getElementById('closeLightbox').addEventListener('click', closeLightbox);
        document.getElementById('roomLightboxModal').addEventListener('click', function(e) { if (e.target === this) closeLightbox(); });
        document.getElementById('lightboxPrev').addEventListener('click', function() { navigateLightbox(-1); });
        document.getElementById('lightboxNext').addEventListener('click', function() { navigateLightbox(1); });
        
        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('roomLightboxModal').classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') navigateLightbox(-1);
            if (e.key === 'ArrowRight') navigateLightbox(1);
        });

        // Swipe support for mobile lightbox
        if (isMobile) {
            let startX = 0;
            const body = document.querySelector('.lightbox-body');
            if (body) {
                body.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
                body.addEventListener('touchend', e => {
                    const diff = startX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 60) navigateLightbox(diff > 0 ? 1 : -1);
                }, { passive: true });
            }
        }
    }
    
    function openLightbox(trigger) {
        lbRoomName = trigger.dataset.roomName || '';
        lbVideoUrl = trigger.dataset.videoUrl || '';
        try { lbImages = JSON.parse(trigger.dataset.images || '[]'); } catch(e) { lbImages = [trigger.href]; }
        if (lbImages.length === 0 && trigger.href) lbImages = [trigger.href];
        
        lbIndex = 0;
        buildThumbnails();
        showItem(0);
        document.getElementById('roomLightboxModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeLightbox() {
        document.getElementById('roomLightboxModal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('lightboxVideo').src = '';
    }
    
    function buildThumbnails() {
        const c = document.getElementById('lightboxThumbnails');
        c.innerHTML = '';
        lbImages.forEach(function(img, i) {
            const t = document.createElement('img');
            t.src = img;
            t.className = 'lightbox-thumb' + (i === 0 ? ' active' : '');
            t.addEventListener('click', function() { showItem(i); });
            c.appendChild(t);
        });
        if (lbVideoUrl) {
            const vt = document.createElement('div');
            vt.className = 'lightbox-thumb video-thumb';
            vt.innerHTML = '<i class="fa-solid fa-play"></i>';
            vt.addEventListener('click', showVideo);
            c.appendChild(vt);
        }
    }
    
    function showItem(i) {
        lbIndex = i;
        document.getElementById('lightboxVideoContainer').style.display = 'none';
        document.getElementById('lightboxImageContainer').style.display = 'block';
        document.getElementById('lightboxImage').src = lbImages[i];
        document.getElementById('lightboxTitle').textContent = lbRoomName;
        document.getElementById('lightboxCounter').textContent = (i+1) + ' / ' + lbImages.length;
        document.querySelectorAll('.lightbox-thumb').forEach((t, idx) => t.classList.toggle('active', idx === i));
    }
    
    function showVideo() {
        if (!lbVideoUrl) return;
        document.getElementById('lightboxImageContainer').style.display = 'none';
        document.getElementById('lightboxVideoContainer').style.display = 'block';
        document.getElementById('lightboxVideo').src = getEmbed(lbVideoUrl);
        document.getElementById('lightboxTitle').textContent = lbRoomName + ' - Видео';
        document.getElementById('lightboxCounter').textContent = 'Видео';
        document.querySelectorAll('.lightbox-thumb').forEach(t => t.classList.remove('active'));
        const vt = document.querySelector('.lightbox-thumb.video-thumb');
        if (vt) vt.classList.add('active');
        lbIndex = -1;
    }
    
    function navigateLightbox(dir) {
        if (lbIndex === -1) { showItem(dir < 0 ? lbImages.length - 1 : 0); return; }
        let n = lbIndex + dir;
        if (n < 0) { lbVideoUrl ? showVideo() : showItem(lbImages.length - 1); }
        else if (n >= lbImages.length) { lbVideoUrl ? showVideo() : showItem(0); }
        else showItem(n);
    }
    
    function getEmbed(url) {
        if (!url) return '';
        let m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\?]+)/);
        if (m) return 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1';
        m = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
        if (m) return 'https://player.vimeo.com/video/' + m[1] + '?autoplay=1';
        return url;
    }
})();
</script>
@endpush