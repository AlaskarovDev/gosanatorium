@extends('layout_inner_page')

@section('title')
<title>{{ $custom_page->page_name }}</title>
@endsection

@push('style_section')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>

:root {
    --cp-ink: #0b0f1a;
    --cp-ink-soft: #1c2333;
    --cp-ink-muted: #3a4158;
    --cp-slate: #6e7590;
    --cp-silver: #a0a6b8;
    --cp-cloud: #eceef3;
    --cp-snow: #f6f7fa;
    --cp-white: #ffffff;
    --cp-indigo: #4338ca;
    --cp-indigo-light: #6366f1;
    --cp-indigo-bright: #818cf8;
    --cp-indigo-pale: #e0e7ff;
    --cp-coral: #f43f5e;
    --cp-coral-soft: #fda4af;
    --cp-amber: #f59e0b;
    --cp-emerald: #10b981;
    --cp-font-display: 'Instrument Serif', Georgia, serif;
    --cp-font-body: 'Plus Jakarta Sans', system-ui, sans-serif;
    --cp-ease: cubic-bezier(0.33, 1, 0.68, 1);
    --cp-ease-back: cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* =============================================
   TRANSFER HERO - FULL IMAGE + SEARCH OVERLAY
   ============================================= */
.th-hero {
    position: relative;
    min-height: 520px;
    display: flex;
    align-items: center;
    overflow: hidden;
}
.th-hero__bg {
    position: absolute;
    inset: 0;
    z-index: 0;
}
.th-hero__bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.th-hero__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.15) 55%, rgba(0,0,0,0.05) 100%);
    z-index: 1;
}

.th-hero__inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
    padding: 60px 0;
    width: 100%;
}

/* Hero title */
.th-hero__text {
    flex: 1;
    max-width: 560px;
}
.th-hero__title {
    font-family: var(--cp-font-display) !important;
    font-size: 52px !important;
    font-weight: 400 !important;
    color: #fff !important;
    line-height: 1.15 !important;
    margin: 0 0 16px !important;
    text-shadow: 0 2px 20px rgba(0,0,0,0.3);
}
.th-hero__desc {
    font-family: var(--cp-font-body) !important;
    font-size: 17px !important;
    color: rgba(255,255,255,0.85) !important;
    line-height: 1.7 !important;
    margin: 0 !important;
    text-shadow: 0 1px 8px rgba(0,0,0,0.2);
}

/* Search form card on hero */
.th-form-card {
    width: 380px;
    flex-shrink: 0;
    background: rgba(67, 56, 202, 0.92);
    backdrop-filter: blur(16px);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.th-form-card__head {
    padding: 22px 28px;
    font-family: var(--cp-font-body);
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
    line-height: 1.3;
}
.th-form-card__body {
    padding: 0 28px 28px;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.th-form-field select,
.th-form-field input[type="date"],
.th-form-field input[type="number"] {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    font-family: var(--cp-font-body);
    font-size: 15px;
    color: var(--cp-ink);
    background: #fff;
    outline: none;
    transition: all 0.3s;
    appearance: none;
    -webkit-appearance: none;
}
.th-form-field select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234338ca' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
    cursor: pointer;
}
.th-form-field select:focus,
.th-form-field input:focus {
    border-color: #fff;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.2);
}

.th-form-btn {
    width: 100%;
    padding: 16px;
    background: #ef4444;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: var(--cp-font-body);
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}
.th-form-btn:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(239,68,68,0.4);
}

/* =============================================
   FEATURES STRIP
   ============================================= */
.th-features {
    background: var(--cp-white);
    border-bottom: 1px solid var(--cp-cloud);
    padding: 0;
}
.th-features__inner {
    display: flex;
    align-items: stretch;
    justify-content: center;
}
.th-feat {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 28px 24px;
    border-right: 1px solid var(--cp-cloud);
    transition: background 0.3s;
}
.th-feat:last-child { border-right: none; }
.th-feat:hover { background: var(--cp-snow); }

.th-feat__icon {
    width: 52px; height: 52px;
    border-radius: 50%;
    border: 2px solid var(--cp-coral);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--cp-coral);
    font-size: 20px;
}
.th-feat__text {
    display: flex;
    flex-direction: column;
}
.th-feat__text strong {
    font-family: var(--cp-font-body);
    font-size: 15px;
    font-weight: 700;
    color: var(--cp-ink);
    line-height: 1.3;
}
.th-feat__text span {
    font-family: var(--cp-font-body);
    font-size: 13px;
    color: var(--cp-slate);
    line-height: 1.5;
    margin-top: 2px;
}

/* =============================================
   CONTENT SECTION
   ============================================= */
.tf-content {
    padding: 40px 0 20px;
    background: var(--cp-white);
}
.tf-content__body {
    font-family: var(--cp-font-body);
    font-size: 17px;
    line-height: 1.9;
    color: var(--cp-ink-muted);
    max-width: 780px;
    margin: 0 auto;
}
.tf-content__body h1, .tf-content__body h2, .tf-content__body h3, .tf-content__body h4 {
    font-family: var(--cp-font-display);
    color: var(--cp-ink);
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.tf-content__body img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    margin: 1.5rem 0;
}
.tf-content__body a {
    color: var(--cp-indigo);
    text-decoration: underline;
    text-underline-offset: 3px;
}

/* =============================================
   TRANSFER BOOKING SECTION (Search + Vehicles + Checkout)
   ============================================= */
.tb-section {
    padding: 0;
    background: var(--cp-snow);
    position: relative;
}
.tb-section.has-results {
    padding: 40px 0 60px;
}

/* Section heading */
.tb-heading {
    text-align: center;
    margin-bottom: 32px;
}
.tb-heading__icon {
    width: 64px; height: 64px;
    background: var(--cp-indigo-pale);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--cp-indigo);
}
.tb-heading h2 {
    font-family: var(--cp-font-display) !important;
    font-size: 36px !important;
    font-weight: 400 !important;
    color: var(--cp-ink) !important;
    margin-bottom: 8px !important;
}
.tb-heading p {
    font-family: var(--cp-font-body) !important;
    font-size: 15px !important;
    color: var(--cp-slate) !important;
}
.tb-heading__line {
    width: 40px; height: 3px;
    background: var(--cp-indigo);
    border-radius: 2px;
    margin: 16px auto 0;
}

/* =============================================
   VEHICLE CARDS
   ============================================= */
.tb-vehicles-title {
    font-family: var(--cp-font-display);
    font-size: 28px;
    color: var(--cp-ink);
    margin-bottom: 28px;
}
.tb-vehicles-title span {
    color: var(--cp-indigo);
    font-weight: 400;
}

.tb-vehicle {
    background: var(--cp-white);
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 24px rgba(0,0,0,0.05);
    border: 2px solid var(--cp-cloud);
    overflow: hidden;
    margin-bottom: 20px;
    transition: all 0.4s var(--cp-ease);
    cursor: pointer;
}
.tb-vehicle:hover {
    border-color: var(--cp-indigo-bright);
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(67,56,202,0.12);
}
.tb-vehicle.selected {
    border-color: var(--cp-indigo);
    box-shadow: 0 0 0 3px rgba(67,56,202,0.15), 0 12px 40px rgba(67,56,202,0.12);
}

.tb-vehicle__inner {
    display: flex;
    align-items: stretch;
}

.tb-vehicle__img {
    width: 280px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}
.tb-vehicle__img img {
    width: 100%;
    height: 100%;
    min-height: 200px;
    object-fit: cover;
}
.tb-vehicle__img-placeholder {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--cp-snow), var(--cp-cloud));
}
.tb-vehicle__img-placeholder i {
    font-size: 48px;
    color: var(--cp-silver);
}

.tb-vehicle__info {
    flex: 1;
    padding: 28px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.tb-vehicle__name {
    font-family: var(--cp-font-display);
    font-size: 22px;
    color: var(--cp-ink);
    margin-bottom: 12px;
}
.tb-vehicle__specs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}
.tb-vehicle__spec {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--cp-indigo-pale);
    color: var(--cp-indigo);
    padding: 6px 14px;
    border-radius: 20px;
    font-family: var(--cp-font-body);
    font-size: 13px;
    font-weight: 600;
}
.tb-vehicle__features {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 10px;
}
.tb-vehicle__feature {
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: var(--cp-font-body);
    font-size: 13px;
    color: var(--cp-slate);
}
.tb-vehicle__feature i {
    color: var(--cp-emerald);
    font-size: 11px;
}
.tb-vehicle__desc {
    font-family: var(--cp-font-body);
    font-size: 13px;
    color: var(--cp-silver);
    line-height: 1.6;
    margin: 0;
}

.tb-vehicle__price {
    width: 200px;
    flex-shrink: 0;
    padding: 28px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    background: var(--cp-snow);
    border-left: 1px solid var(--cp-cloud);
}
.tb-vehicle__price-label {
    font-family: var(--cp-font-body);
    font-size: 12px;
    font-weight: 600;
    color: var(--cp-silver);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
}
.tb-vehicle__price-val {
    font-family: var(--cp-font-body);
    font-size: 28px;
    font-weight: 800;
    color: var(--cp-indigo);
    line-height: 1;
    margin-bottom: 16px;
}
.tb-vehicle__select-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 20px;
    background: var(--cp-indigo);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-family: var(--cp-font-body);
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s var(--cp-ease);
    text-decoration: none;
}
.tb-vehicle__select-btn:hover {
    background: #3730a3;
    color: #fff;
    transform: translateY(-2px);
}
.tb-vehicle.selected .tb-vehicle__select-btn {
    background: var(--cp-emerald);
}

/* Empty / Loading states */
.tb-empty {
    text-align: center;
    padding: 60px 20px;
    background: var(--cp-white);
    border-radius: 20px;
    border: 1px solid var(--cp-cloud);
}
.tb-empty__icon {
    width: 80px; height: 80px;
    background: #fef3c7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}
.tb-empty__icon i { font-size: 32px; color: var(--cp-amber); }
.tb-empty h4 {
    font-family: var(--cp-font-display);
    font-size: 22px;
    color: var(--cp-ink);
    margin-bottom: 8px;
}
.tb-empty p {
    font-family: var(--cp-font-body);
    font-size: 14px;
    color: var(--cp-slate);
}

.tb-loading {
    text-align: center;
    padding: 60px 20px;
}
.tb-loading .spinner {
    width: 40px; height: 40px;
    border: 4px solid var(--cp-cloud);
    border-top-color: var(--cp-indigo);
    border-radius: 50%;
    animation: cpSpin 0.8s linear infinite;
    margin: 0 auto 16px;
}

/* =============================================
   CHECKOUT FORM (appears after vehicle selection)
   ============================================= */
.tb-checkout {
    margin-top: 32px;
    display: none;
}
.tb-checkout.active {
    display: block;
    animation: cpReveal 0.6s var(--cp-ease) both;
}

.tb-checkout-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

.tb-card {
    background: var(--cp-white);
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 24px rgba(0,0,0,0.05);
    border: 1px solid var(--cp-cloud);
    overflow: hidden;
    margin-bottom: 24px;
}
.tb-card__head {
    background: var(--cp-snow);
    padding: 18px 24px;
    border-bottom: 1px solid var(--cp-cloud);
    display: flex;
    align-items: center;
    gap: 10px;
}
.tb-card__head h4 {
    margin: 0;
    font-family: var(--cp-font-body);
    font-size: 16px;
    font-weight: 700;
    color: var(--cp-ink);
}
.tb-card__head i {
    color: var(--cp-indigo);
}
.tb-card__body {
    padding: 24px;
}

/* Form fields inside checkout */
.tb-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.tb-form-row.full {
    grid-template-columns: 1fr;
}

.tb-form-group {
    margin-bottom: 20px;
}
.tb-form-group label {
    display: block;
    font-family: var(--cp-font-body);
    font-size: 13px;
    font-weight: 700;
    color: var(--cp-ink-soft);
    margin-bottom: 8px;
}
.tb-form-group input,
.tb-form-group textarea,
.tb-form-group select {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid var(--cp-cloud);
    border-radius: 12px;
    font-family: var(--cp-font-body);
    font-size: 14px;
    color: var(--cp-ink);
    background: var(--cp-snow);
    transition: all 0.3s;
    outline: none;
}
.tb-form-group input:focus,
.tb-form-group textarea:focus,
.tb-form-group select:focus {
    border-color: var(--cp-indigo);
    background: var(--cp-white);
    box-shadow: 0 0 0 4px rgba(67,56,202,0.06);
}
.tb-form-group textarea {
    resize: vertical;
    min-height: 80px;
}
.tb-form-group .is-invalid {
    border-color: var(--cp-coral) !important;
}
.tb-form-group .invalid-feedback {
    color: var(--cp-coral);
    font-size: 12px;
    margin-top: 4px;
}

/* Summary sidebar */
.tb-summary {
    position: sticky;
    top: 100px;
}

.tb-summary-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid var(--cp-cloud);
    font-family: var(--cp-font-body);
    font-size: 14px;
    color: var(--cp-slate);
}
.tb-summary-item:last-child { border-bottom: none; }

.tb-summary-total {
    display: flex;
    justify-content: space-between;
    padding: 16px 0;
    font-family: var(--cp-font-body);
    font-size: 22px;
    font-weight: 800;
    color: var(--cp-indigo);
    border-top: 2px solid var(--cp-cloud);
    margin-top: 8px;
}

.tb-submit-btn {
    position: relative;
    width: 100%;
    padding: 18px 32px;
    background: var(--cp-indigo);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-family: var(--cp-font-body);
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s var(--cp-ease);
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(67,56,202,0.25);
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.tb-submit-btn:hover {
    background: #3730a3;
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(67,56,202,0.35);
}
.tb-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.tb-payment-note {
    text-align: center;
    font-family: var(--cp-font-body);
    font-size: 13px;
    color: var(--cp-silver);
    margin-top: 14px;
}

/* Create account toggle */
.tb-create-account {
    border: 1px solid var(--cp-cloud);
    border-radius: 12px;
    padding: 16px;
    background: var(--cp-snow);
    margin-top: 10px;
}

/* =============================================
   DEFAULT PAGE - CONTENT
   ============================================= */
.def-content {
    background: var(--cp-white);
}
.def-content__inner {
    position: relative;
    padding-left: 32px;
}
.def-content__inner::before {
    content: '';
    position: absolute;
    left: 0; top: 0;
    width: 3px; height: 56px;
    background: linear-gradient(180deg, var(--cp-indigo), var(--cp-indigo-bright));
    border-radius: 2px;
}

/* =============================================
   DEFAULT PAGE - PREMIUM BANNER
   ============================================= */
.def-banner {
    position: relative;
    overflow: hidden;
}
.def-banner__bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
}
.def-banner__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(11,15,26,0.92) 0%, rgba(67,56,202,0.7) 50%, rgba(11,15,26,0.95) 100%);
}
.def-banner__mesh {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 30%, rgba(244,63,94,0.08) 0%, transparent 40%);
}
.def-banner__dots {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
}
.def-banner__dots span {
    position: absolute;
    width: 4px; height: 4px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    animation: cpDot 20s ease-in-out infinite;
}
.def-banner__dots span:nth-child(1) { left: 12%; top: 25%; }
.def-banner__dots span:nth-child(2) { left: 28%; top: 72%; animation-delay: 3s; }
.def-banner__dots span:nth-child(3) { left: 52%; top: 18%; animation-delay: 6s; }
.def-banner__dots span:nth-child(4) { left: 78%; top: 58%; animation-delay: 2s; }
.def-banner__dots span:nth-child(5) { left: 90%; top: 32%; animation-delay: 5s; }
@keyframes cpDot {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.2; }
    50% { transform: translateY(-20px) scale(1.8); opacity: 0.7; }
}

.def-banner__grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1.2fr 0.8fr 1fr;
    gap: 44px;
    align-items: center;
    padding: 80px 0;
}
.def-banner__text { color: #fff; }

.def-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.07);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 10px 20px;
    border-radius: 50px;
    margin-bottom: 24px;
}
.def-badge__icon {
    width: 32px; height: 32px;
    background: var(--cp-indigo);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.def-badge__icon svg { width: 15px; height: 15px; }
.def-badge__name {
    font-family: var(--cp-font-body);
    font-size: 14px;
    font-weight: 600;
    color: #fff;
}

.def-banner__headline {
    font-family: var(--cp-font-display) !important;
    font-size: 48px !important;
    font-weight: 400 !important;
    color: #fff !important;
    line-height: 1.12 !important;
    margin-bottom: 20px !important;
}
.def-banner__desc {
    font-family: var(--cp-font-body) !important;
    font-size: 17px !important;
    color: rgba(255,255,255,0.75) !important;
    line-height: 1.8 !important;
    margin-bottom: 32px !important;
}

.def-stats {
    display: flex;
    align-items: center;
    gap: 24px;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,0.1);
}
.def-stat { text-align: center; }
.def-stat__num {
    font-family: var(--cp-font-body);
    font-size: 30px;
    font-weight: 800;
    color: var(--cp-indigo-bright) !important;
    line-height: 1;
}
.def-stat__lbl {
    font-family: var(--cp-font-body);
    font-size: 11px;
    font-weight: 600;
    color: rgba(255,255,255,0.5) !important;
    margin-top: 5px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
}
.def-stat__sep {
    width: 1px; height: 36px;
    background: rgba(255,255,255,0.12);
}

/* Video block */
.def-video { text-align: center; }
.def-video__wrap {
    position: relative;
    width: 120px; height: 120px;
    margin: 0 auto;
}
.def-video__ripple {
    position: absolute;
    inset: 0;
    border: 2px solid rgba(255,255,255,0.15);
    border-radius: 50%;
    animation: cpRing 2.5s ease-out infinite;
}
.def-video__ripple--d { animation-delay: 1.25s; }
.def-video__btn {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 80px; height: 80px;
    border-radius: 50%;
    background: var(--cp-white);
    border: none;
    cursor: pointer;
    transition: all 0.4s var(--cp-ease-back);
    box-shadow: 0 12px 40px rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
}
.def-video__btn svg { margin-left: 3px; color: var(--cp-indigo); }
.def-video__btn:hover {
    transform: translate(-50%, -50%) scale(1.1);
    box-shadow: 0 18px 50px rgba(0,0,0,0.3);
}
.def-video__label {
    font-family: var(--cp-font-body) !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: rgba(255,255,255,0.6) !important;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-top: 20px !important;
}

/* Doctor image */
.def-doctor { display: flex; justify-content: flex-end; }
.def-doctor__frame {
    position: relative;
    max-width: 280px;
}
.def-doctor__frame img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.25);
    position: relative;
    z-index: 1;
}
.def-doctor__glow {
    position: absolute;
    inset: -16px;
    background: radial-gradient(circle, rgba(99,102,241,0.2), transparent 70%);
    border-radius: 50%;
    z-index: 0;
    filter: blur(30px);
}
.def-doctor__corner {
    position: absolute;
    width: 40px; height: 40px;
    z-index: 2;
}
.def-doctor__corner--tl { top: -10px; left: -10px; border-top: 2px solid var(--cp-indigo-bright); border-left: 2px solid var(--cp-indigo-bright); border-radius: 6px 0 0 0; }
.def-doctor__corner--br { bottom: -10px; right: -10px; border-bottom: 2px solid var(--cp-coral); border-right: 2px solid var(--cp-coral); border-radius: 0 0 6px 0; }

/* Video modal */
.cp-modal .modal-content {
    background: var(--cp-ink);
    border: none;
    border-radius: 24px;
    overflow: hidden;
}
.cp-modal__close {
    position: absolute;
    top: -48px; right: 0;
    width: 40px; height: 40px;
    background: rgba(255,255,255,0.08);
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cp-modal__close:hover { background: rgba(255,255,255,0.15); transform: rotate(90deg); }
.cp-video-wrap {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
}
.cp-video-wrap iframe {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    border-radius: 24px;
}

/* =============================================
   CONSULTATION FORM
   ============================================= */
.cp-form-section {
    position: relative;
    padding: 48px 0 60px;
    background: var(--cp-snow);
    overflow: hidden;
}
.cp-form-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--cp-cloud), transparent);
}

.cp-form-card {
    position: relative;
    background: var(--cp-white);
    border-radius: 24px;
    padding: 60px 68px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 48px rgba(0,0,0,0.06);
    border: 1px solid var(--cp-cloud);
}

/* Top accent */
.cp-form-card::after {
    content: '';
    position: absolute;
    top: 0; left: 48px; right: 48px;
    height: 3px;
    background: linear-gradient(90deg, var(--cp-indigo), var(--cp-indigo-bright), var(--cp-coral-soft));
    border-radius: 0 0 3px 3px;
}

.cp-form-head {
    text-align: center;
    margin-bottom: 48px;
}
.cp-form-head__icon {
    width: 64px; height: 64px;
    background: var(--cp-indigo-pale);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: var(--cp-indigo);
}
.cp-form-head h2 {
    font-family: var(--cp-font-display) !important;
    font-size: 34px !important;
    font-weight: 400 !important;
    color: var(--cp-ink) !important;
    margin-bottom: 8px !important;
}
.cp-form-head p {
    font-family: var(--cp-font-body) !important;
    font-size: 15px !important;
    color: var(--cp-slate) !important;
}
.cp-form-head__line {
    width: 40px; height: 3px;
    background: var(--cp-indigo);
    border-radius: 2px;
    margin: 20px auto 0;
}

/* Grid */
.cp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.cp-field.cp-full { grid-column: 1 / -1; }

.cp-field > label {
    display: flex; align-items: center; gap: 8px;
    font-family: var(--cp-font-body) !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: var(--cp-ink-soft) !important;
    margin-bottom: 8px !important;
    letter-spacing: 0.3px !important;
}
.cp-label-dot {
    width: 6px; height: 6px;
    background: var(--cp-indigo);
    border-radius: 50%;
    flex-shrink: 0;
}

/* Inputs */
.cp-input-box { position: relative; }
.cp-input-box input,
.cp-input-box textarea {
    width: 100%;
    padding: 16px 20px;
    border: 1.5px solid var(--cp-cloud);
    border-radius: 14px;
    font-family: var(--cp-font-body);
    font-size: 15px;
    color: var(--cp-ink);
    background: var(--cp-snow);
    transition: all 0.3s var(--cp-ease);
    outline: none;
}
.cp-input-box input:focus,
.cp-input-box textarea:focus {
    border-color: var(--cp-indigo);
    background: var(--cp-white);
    box-shadow: 0 0 0 4px rgba(67,56,202,0.06);
}
.cp-input-box input::placeholder,
.cp-input-box textarea::placeholder { color: var(--cp-silver); }
.cp-input-box textarea { resize: vertical; min-height: 130px; }

.cp-input-bar {
    position: absolute;
    bottom: 0; left: 50%;
    width: 0; height: 2px;
    background: var(--cp-indigo);
    border-radius: 0 0 14px 14px;
    transition: width 0.3s var(--cp-ease);
    transform: translateX(-50%);
}
.cp-input-box input:focus ~ .cp-input-bar,
.cp-input-box textarea:focus ~ .cp-input-bar {
    width: calc(100% - 3px);
}

/* Phone */
.cp-phone { display: flex; gap: 12px; }
.cp-phone select {
    min-width: 165px;
    padding: 16px 14px;
    border: 1.5px solid var(--cp-cloud);
    border-radius: 14px;
    font-family: var(--cp-font-body);
    font-size: 14px;
    color: var(--cp-ink);
    background: var(--cp-snow);
    cursor: pointer;
    outline: none;
    transition: all 0.3s;
}
.cp-phone select:focus {
    border-color: var(--cp-indigo);
    box-shadow: 0 0 0 4px rgba(67,56,202,0.06);
}
.cp-phone__num {
    flex: 1;
    display: flex;
    border: 1.5px solid var(--cp-cloud);
    border-radius: 14px;
    overflow: hidden;
    background: var(--cp-snow);
    transition: all 0.3s;
}
.cp-phone__num:focus-within {
    border-color: var(--cp-indigo);
    background: var(--cp-white);
    box-shadow: 0 0 0 4px rgba(67,56,202,0.06);
}
.cp-phone__code {
    display: flex;
    align-items: center;
    padding: 0 16px;
    background: var(--cp-indigo);
    color: #fff;
    font-family: var(--cp-font-body);
    font-size: 15px;
    font-weight: 700;
    min-width: 68px;
    justify-content: center;
}
.cp-phone__num input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    padding-left: 16px !important;
    flex: 1;
}

/* Checkbox */
.cp-check {
    display: flex; align-items: flex-start; gap: 14px;
    cursor: pointer; font-weight: 400 !important;
}
.cp-check input { display: none; }
.cp-check__box {
    width: 22px; height: 22px; min-width: 22px;
    border: 2px solid var(--cp-cloud);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.25s; margin-top: 2px;
    background: var(--cp-snow);
}
.cp-check__box svg { opacity: 0; transform: scale(0.5); transition: all 0.2s; color: #fff; }
.cp-check input:checked + .cp-check__box { background: var(--cp-indigo); border-color: var(--cp-indigo); }
.cp-check input:checked + .cp-check__box svg { opacity: 1; transform: scale(1); }
.cp-check__txt {
    font-family: var(--cp-font-body) !important;
    font-size: 13px !important;
    color: var(--cp-slate) !important;
    line-height: 1.65 !important;
}

/* Submit */
.cp-submit {
    position: relative;
    width: 100%;
    padding: 20px 32px;
    background: var(--cp-indigo);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-family: var(--cp-font-body);
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s var(--cp-ease);
    overflow: hidden;
    box-shadow: 0 6px 24px rgba(67,56,202,0.25);
    letter-spacing: 0.3px;
}
.cp-submit:hover {
    background: #3730a3;
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(67,56,202,0.35);
}
.cp-submit__main { display: flex; align-items: center; justify-content: center; gap: 10px; }
.cp-submit__icon { transition: transform 0.3s; }
.cp-submit:hover .cp-submit__icon { transform: translateX(4px); }
.cp-submit__loading { display: none; align-items: center; justify-content: center; gap: 10px; }
.cp-submit__loading .cp-spin { animation: cpSpin 1s linear infinite; }
@keyframes cpSpin { to { transform: rotate(360deg); } }
.cp-submit__shine {
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
    transition: left 0.6s;
}
.cp-submit:hover .cp-submit__shine { left: 100%; }

/* =============================================
   ANIMATIONS
   ============================================= */
@keyframes cpReveal {
    from { opacity: 0; transform: translateY(28px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes cpSlideLeft {
    from { opacity: 0; transform: translateX(44px); }
    to { opacity: 1; transform: translateX(0); }
}

/* =============================================
   RESPONSIVE
   ============================================= */
@media (max-width: 1199px) {
    .def-banner__grid { grid-template-columns: 1fr 1fr; }
    .def-doctor { display: none; }
    .def-banner__headline { font-size: 40px !important; }
    .tb-checkout-grid { grid-template-columns: 1fr; }
    .th-hero__title { font-size: 42px !important; }
    .th-form-card { width: 340px; }
}
@media (max-width: 991px) {
    .th-hero__inner { flex-direction: column; text-align: center; padding: 40px 0; }
    .th-hero__text { max-width: 100%; }
    .th-hero__title { font-size: 36px !important; }
    .th-form-card { width: 100%; max-width: 420px; }
    .th-features__inner { flex-wrap: wrap; }
    .th-feat { flex: 0 0 50%; border-bottom: 1px solid var(--cp-cloud); }
    .th-feat:nth-child(2n) { border-right: none; }
    .cp-form-card { padding: 44px 32px; }
    .def-banner__grid { grid-template-columns: 1fr; text-align: center; gap: 44px; }
    .def-banner__headline { font-size: 36px !important; }
    .def-stats { justify-content: center; }
    .def-badge { justify-content: center; }
    .tb-vehicle__inner { flex-direction: column; }
    .tb-vehicle__img { width: 100%; }
    .tb-vehicle__img img { min-height: 180px; max-height: 240px; }
    .tb-vehicle__price { width: 100%; border-left: none; border-top: 1px solid var(--cp-cloud); flex-direction: row; gap: 20px; padding: 20px 28px; }
    .tb-vehicle__price-label { margin-bottom: 0; }
    .tb-vehicle__price-val { margin-bottom: 0; }
    .tb-checkout-grid { grid-template-columns: 1fr; }
}
@media (max-width: 767px) {
    .th-hero { min-height: 420px; }
    .th-hero__title { font-size: 28px !important; }
    .th-form-card__head { font-size: 16px; padding: 18px 22px; }
    .th-form-card__body { padding: 0 22px 22px; gap: 12px; }
    .th-features__inner { flex-direction: column; }
    .th-feat { flex: none; border-right: none; border-bottom: 1px solid var(--cp-cloud); }
    .th-feat:last-child { border-bottom: none; }
    .cp-grid { grid-template-columns: 1fr; }
    .cp-phone { flex-direction: column; }
    .cp-phone select { min-width: auto; }
    .cp-form-card { padding: 32px 24px; border-radius: 20px; }
    .cp-form-head h2 { font-size: 28px !important; }
    .def-banner__grid { padding: 48px 0; }
    .def-banner__headline { font-size: 28px !important; }
    .def-stats { flex-wrap: wrap; gap: 18px; }
    .def-stat__sep { display: none; }
    .def-stat { flex: 1; min-width: 80px; }
    .tb-form-row { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .th-hero__title { font-size: 24px !important; }
    .th-hero__inner { padding: 30px 0; }
    .th-form-card { border-radius: 12px; }
}

.td_height_100 { height: 100px; }
.td_height_120 { height: 120px; }
@media (max-width: 991px) {
    .td_height_100 { height: 60px; }
    .td_height_120 { height: 70px; }
}
</style>
@endpush

@push('style_section')
<style>
.gscon-hero{background:linear-gradient(135deg,#0d7a68 0%,#1a9e87 50%,#2ec4a9 100%) !important;padding:80px 0 100px !important;position:relative !important;overflow:hidden !important}
.gscon-hero::before{content:'' !important;position:absolute !important;top:-80px !important;right:-80px !important;width:400px !important;height:400px !important;background:rgba(255,255,255,0.06) !important;border-radius:50% !important}
.gscon-hero-label{font-size:13px !important;font-weight:700 !important;color:rgba(255,255,255,0.8) !important;text-transform:uppercase !important;letter-spacing:2px !important;margin-bottom:16px !important}
.gscon-hero-title{font-size:44px !important;font-weight:900 !important;color:#ffffff !important;line-height:1.15 !important;margin-bottom:20px !important;text-transform:none !important;letter-spacing:-1px !important}
.gscon-hero-subtitle{font-size:18px !important;color:rgba(255,255,255,0.85) !important;margin-bottom:40px !important;line-height:1.6 !important;text-transform:none !important}
.gscon-trust-row{display:flex !important;flex-wrap:wrap !important;gap:16px !important}
.gscon-trust-pill{display:flex !important;align-items:center !important;gap:8px !important;background:rgba(255,255,255,0.15) !important;border:1px solid rgba(255,255,255,0.25) !important;border-radius:50px !important;padding:10px 20px !important;color:#ffffff !important;font-size:14px !important;font-weight:600 !important}
.gscon-examples{margin-top:32px !important;display:flex !important;flex-wrap:wrap !important;gap:10px !important}
.gscon-example-tag{background:rgba(255,255,255,0.12) !important;border:1px solid rgba(255,255,255,0.2) !important;border-radius:8px !important;padding:8px 16px !important;color:rgba(255,255,255,0.9) !important;font-size:13px !important;text-transform:none !important}
.gscon-doctor{padding:80px 0 !important;background:#ffffff !important}
.gscon-section-underline{width:60px !important;height:4px !important;background:linear-gradient(135deg,#1a9e87,#2ec4a9) !important;border-radius:2px !important;margin-bottom:16px !important}
.gscon-section-label{font-size:13px !important;font-weight:700 !important;color:#1a9e87 !important;text-transform:uppercase !important;letter-spacing:2px !important;margin-bottom:8px !important}
.gscon-section-title{font-size:34px !important;font-weight:800 !important;color:#1a1a2e !important;margin-bottom:40px !important;text-transform:none !important;letter-spacing:-0.5px !important}
.gscon-doctor-card{background:#f8fafb !important;border-radius:24px !important;padding:48px !important;display:grid !important;grid-template-columns:280px 1fr !important;gap:48px !important;align-items:start !important}
.gscon-doctor-photo{width:100% !important;border-radius:18px !important;object-fit:cover !important;aspect-ratio:3/4 !important;box-shadow:0 8px 32px rgba(0,0,0,0.12) !important}
.gscon-doctor-name{font-size:26px !important;font-weight:800 !important;color:#1a1a2e !important;margin-bottom:6px !important;text-transform:none !important}
.gscon-doctor-role{font-size:15px !important;color:#1a9e87 !important;font-weight:600 !important;margin-bottom:4px !important;text-transform:none !important}
.gscon-doctor-edu{font-size:14px !important;color:#6b7280 !important;margin-bottom:24px !important}
.gscon-doctor-stats{display:grid !important;grid-template-columns:repeat(3,1fr) !important;gap:16px !important;margin-bottom:28px !important}
.gscon-stat-box{background:#ffffff !important;border-radius:14px !important;padding:18px 14px !important;text-align:center !important;border:1px solid #eef1f4 !important}
.gscon-stat-num{font-size:28px !important;font-weight:900 !important;color:#1a9e87 !important;line-height:1 !important;margin-bottom:4px !important}
.gscon-stat-lbl{font-size:12px !important;color:#6b7280 !important;line-height:1.4 !important;text-transform:none !important}
.gscon-doctor-desc{font-size:15px !important;color:#4b5563 !important;line-height:1.7 !important;margin-bottom:24px !important;text-transform:none !important}
.gscon-spec-list{display:flex !important;flex-wrap:wrap !important;gap:8px !important;margin-bottom:28px !important}
.gscon-spec-tag{background:rgba(26,158,135,0.1) !important;border:1px solid rgba(26,158,135,0.25) !important;border-radius:8px !important;padding:6px 14px !important;font-size:13px !important;font-weight:600 !important;color:#1a9e87 !important;text-transform:none !important}
.gscon-video-btn{display:inline-flex !important;align-items:center !important;gap:10px !important;padding:14px 28px !important;background:linear-gradient(135deg,#1a9e87,#2ec4a9) !important;color:#ffffff !important;font-size:15px !important;font-weight:700 !important;border-radius:50px !important;text-decoration:none !important;box-shadow:0 6px 20px rgba(26,158,135,0.35) !important;transition:all 0.3s !important;border:none !important;cursor:pointer !important}
.gscon-form-section{padding:80px 0 !important;background:linear-gradient(135deg,#f0fdf9 0%,#e8f7f5 100%) !important}
.gscon-form-wrap{background:#ffffff !important;border-radius:24px !important;padding:56px !important;box-shadow:0 8px 40px rgba(0,0,0,0.08) !important;max-width:760px !important;margin:0 auto !important}
.gscon-form-grid{display:grid !important;grid-template-columns:1fr 1fr !important;gap:20px !important}
.gscon-form-group{display:flex !important;flex-direction:column !important;gap:6px !important}
.gscon-form-group.full{grid-column:1/-1 !important}
.gscon-label{font-size:13px !important;font-weight:600 !important;color:#374151 !important}
.gscon-input,.gscon-select,.gscon-textarea{width:100% !important;padding:14px 16px !important;border:1.5px solid #e5e7eb !important;border-radius:12px !important;font-size:15px !important;color:#1a1a2e !important;background:#f9fafb !important;transition:all 0.2s !important;outline:none !important;font-family:inherit !important}
.gscon-input:focus,.gscon-select:focus,.gscon-textarea:focus{border-color:#1a9e87 !important;background:#ffffff !important;box-shadow:0 0 0 3px rgba(26,158,135,0.12) !important}
.gscon-textarea{min-height:120px !important;resize:vertical !important}
.gscon-phone-row{display:flex !important;gap:8px !important}
.gscon-phone-select{width:160px !important;flex-shrink:0 !important}
.gscon-phone-input{flex:1 !important}
.gscon-consent{display:flex !important;align-items:flex-start !important;gap:10px !important;font-size:13px !important;color:#6b7280 !important;line-height:1.5 !important;margin-top:8px !important}
.gscon-submit{width:100% !important;padding:16px !important;background:linear-gradient(135deg,#1a9e87,#2ec4a9) !important;color:#ffffff !important;font-size:17px !important;font-weight:700 !important;border:none !important;border-radius:14px !important;cursor:pointer !important;margin-top:24px !important;box-shadow:0 6px 20px rgba(26,158,135,0.35) !important;transition:all 0.3s !important;font-family:inherit !important}
.gscon-guarantee-row{display:flex !important;justify-content:center !important;gap:24px !important;margin-top:24px !important;flex-wrap:wrap !important}
.gscon-guarantee-item{display:flex !important;align-items:center !important;gap:6px !important;font-size:13px !important;color:#6b7280 !important}
.gscon-guarantee-item i{color:#1a9e87 !important}
@media(max-width:768px){.gscon-hero-title{font-size:28px !important}.gscon-doctor-card{grid-template-columns:1fr !important;padding:32px 24px !important}.gscon-form-wrap{padding:32px 24px !important}}
@media(max-width:576px){.gscon-form-grid{grid-template-columns:1fr !important}}
</style>
@endpush

@section('front-content')
<div class="custom-page-wrapper">

    {{-- BREADCRUMB --}}
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
                            <li><span>{{ $custom_page->page_name }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- TRANSFER PAGE --}}
    {{-- ================================================================ --}}
    @if($custom_page->slug == 'transfer')
        <style>
        *{box-sizing:border-box}
        .gstr-hero{position:relative;height:500px;overflow:hidden;display:flex;align-items:center}
        .gstr-hero-bg{position:absolute;inset:0;background-image:url('https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=1400&q=80');background-size:cover;background-position:center}
        .gstr-hero-overlay{position:absolute;inset:0;background:linear-gradient(135deg,rgba(10,60,52,0.88) 0%,rgba(26,158,135,0.72) 100%)}
        .gstr-hero-content{position:relative;z-index:2;width:100%}
        .gstr-hero-badge{display:inline-block;background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.35);border-radius:50px;padding:7px 18px;font-size:13px;font-weight:700;color:#fff;letter-spacing:1px;text-transform:uppercase;margin-bottom:20px}
        .gstr-hero-title{font-size:50px;font-weight:900;color:#fff;line-height:1.1;margin-bottom:16px;text-transform:none;letter-spacing:-1px}
        .gstr-hero-sub{font-size:17px;color:rgba(255,255,255,0.85);margin-bottom:36px;line-height:1.6;text-transform:none;max-width:500px}
        .gstr-hero-pills{display:flex;flex-wrap:wrap;gap:10px}
        .gstr-hero-pill{background:rgba(255,255,255,0.14);border:1px solid rgba(255,255,255,0.28);border-radius:50px;padding:8px 18px;color:#fff;font-size:13px;font-weight:600}
        .gstr-routes-sec{padding:72px 0;background:#fff}
        .gstr-uline{width:56px;height:4px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);border-radius:2px;margin-bottom:14px}
        .gstr-slabel{font-size:12px;font-weight:700;color:#1a9e87;text-transform:uppercase;letter-spacing:2px;margin-bottom:8px}
        .gstr-stitle{font-size:34px;font-weight:800;color:#1a1a2e;margin-bottom:44px;text-transform:none;letter-spacing:-0.5px}
        .gstr-rgrid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
        .gstr-rcard{background:#f8fafb;border-radius:18px;padding:28px 20px;border:2px solid transparent;transition:all 0.3s}
        .gstr-rcard:hover{border-color:#1a9e87;background:#fff;box-shadow:0 10px 32px rgba(26,158,135,0.13);transform:translateY(-5px)}
        .gstr-ricon{width:50px;height:50px;border-radius:13px;background:linear-gradient(135deg,#e8f7f5,#d0f0eb);display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:16px}
        .gstr-rfrom{font-size:11px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.8px;margin-bottom:5px}
        .gstr-rto{font-size:16px;font-weight:800;color:#1a1a2e;margin-bottom:14px;text-transform:none}
        .gstr-rprice{font-size:28px;font-weight:900;color:#1a9e87;line-height:1}
        .gstr-rper{font-size:11px;color:#9ca3af;margin-top:3px}
        .gstr-feat-sec{padding:72px 0;background:#f8fafb}
        .gstr-fgrid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
        .gstr-frow{display:flex;align-items:flex-start;gap:18px;background:#fff;border-radius:16px;padding:26px;box-shadow:0 2px 12px rgba(0,0,0,0.06);transition:all 0.3s}
        .gstr-frow:hover{box-shadow:0 8px 26px rgba(26,158,135,0.12);transform:translateY(-3px)}
        .gstr-ficon{width:50px;height:50px;border-radius:13px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .gstr-ficon i{color:#fff;font-size:19px}
        .gstr-ftitle{font-size:15px;font-weight:700;color:#1a1a2e;margin-bottom:5px;text-transform:none}
        .gstr-fdesc{font-size:13px;color:#6b7280;line-height:1.6;text-transform:none}
        .gstr-form-sec{padding:80px 0;background:linear-gradient(135deg,#0d7a68,#1a9e87 60%,#2ec4a9);position:relative;overflow:hidden}
        .gstr-form-sec::before{content:'';position:absolute;top:-120px;right:-120px;width:480px;height:480px;background:rgba(255,255,255,0.05);border-radius:50%}
        .gstr-fwrap{background:#fff;border-radius:24px;padding:50px;box-shadow:0 24px 80px rgba(0,0,0,0.18);max-width:780px;margin:0 auto;position:relative;z-index:2}
        .gstr-ftop-title{font-size:26px;font-weight:800;color:#1a1a2e;margin-bottom:6px;text-transform:none}
        .gstr-ftop-sub{font-size:15px;color:#6b7280;margin-bottom:32px}
        .gstr-fform-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
        .gstr-fgroup{display:flex;flex-direction:column;gap:5px}
        .gstr-fgroup.full{grid-column:1/-1}
        .gstr-flbl{font-size:13px;font-weight:600;color:#374151}
        .gstr-inp,.gstr-sel,.gstr-ta{width:100%;padding:13px 15px;border:1.5px solid #e5e7eb;border-radius:11px;font-size:14px;color:#1a1a2e;background:#f9fafb;outline:none;font-family:inherit;transition:all 0.2s}
        .gstr-inp:focus,.gstr-sel:focus,.gstr-ta:focus{border-color:#1a9e87;background:#fff;box-shadow:0 0 0 3px rgba(26,158,135,0.1)}
        .gstr-ta{min-height:90px;resize:vertical}
        .gstr-sbtn{width:100%;padding:17px;background:linear-gradient(135deg,#1a9e87,#2ec4a9);color:#fff;font-size:16px;font-weight:700;border:none;border-radius:13px;cursor:pointer;box-shadow:0 6px 20px rgba(26,158,135,0.38);transition:all 0.3s;font-family:inherit;margin-top:8px}
        .gstr-sbtn:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(26,158,135,0.48)}
        .gstr-sbtn:disabled{opacity:.7;cursor:not-allowed;transform:none}
        .gstr-gnotes{display:flex;justify-content:center;gap:24px;margin-top:18px;flex-wrap:wrap}
        .gstr-gnote{display:flex;align-items:center;gap:6px;font-size:13px;color:#9ca3af}
        .gstr-gnote i{color:#1a9e87}
        @media(max-width:1100px){.gstr-rgrid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:768px){.gstr-hero-title{font-size:32px!important}.gstr-hero{height:420px!important}.gstr-fgrid{grid-template-columns:1fr!important}.gstr-fwrap{padding:32px 20px!important}}
        @media(max-width:576px){.gstr-rgrid{grid-template-columns:1fr!important}.gstr-fform-grid{grid-template-columns:1fr!important}}
        </style>


        <section class="gstr-hero">
            <div class="gstr-hero-bg"></div>
            <div class="gstr-hero-overlay"></div>
            <div class="gstr-hero-content"><div class="container"><div class="row"><div class="col-lg-7">
                <div class="gstr-hero-badge">🚗 Трансфер GoSanatorium</div>
                <h1 class="gstr-hero-title">Комфортный трансфер<br>до санатория</h1>
                <p class="gstr-hero-sub">Встретим в аэропорту с табличкой и доставим до санатория — пунктуально и в комфорте</p>
                <div class="gstr-hero-pills">
                    <span class="gstr-hero-pill">✓ Без предоплаты</span>
                    <span class="gstr-hero-pill">✓ Мониторинг рейса</span>
                    <span class="gstr-hero-pill">✓ Ответ за 15 мин</span>
                    <span class="gstr-hero-pill">✓ Бесплатная отмена</span>
                </div>
            </div></div></div></div>
        </section>

        <section class="gstr-routes-sec">
            <div class="container">
                <div class="gstr-uline"></div>
                <div class="gstr-slabel">Популярные маршруты</div>
                <h2 class="gstr-stitle">Цены на трансфер</h2>
                <div class="gstr-rgrid">
                    <div class="gstr-rcard"><div class="gstr-ricon">✈️</div><div class="gstr-rfrom">Аэропорт Гейдар Алиев</div><div class="gstr-rto">→ Нафталан</div><div class="gstr-rprice">49 ₼</div><div class="gstr-rper">за автомобиль</div></div>
                    <div class="gstr-rcard"><div class="gstr-ricon">✈️</div><div class="gstr-rfrom">Аэропорт Гейдар Алиев</div><div class="gstr-rto">→ Ленкорань</div><div class="gstr-rprice">49 ₼</div><div class="gstr-rper">за автомобиль</div></div>
                    <div class="gstr-rcard"><div class="gstr-ricon">🏙️</div><div class="gstr-rfrom">Аэропорт Гейдар Алиев</div><div class="gstr-rto">→ Баку (город)</div><div class="gstr-rprice">15 ₼</div><div class="gstr-rper">за автомобиль</div></div>
                    <div class="gstr-rcard"><div class="gstr-ricon">✈️</div><div class="gstr-rfrom">Аэропорт Гянджа</div><div class="gstr-rto">→ Нафталан</div><div class="gstr-rprice" style="font-size:18px">По запросу</div><div class="gstr-rper">уточните у менеджера</div></div>
                </div>
            </div>
        </section>

        <section class="gstr-feat-sec">
            <div class="container">
                <div class="text-center" style="margin-bottom:44px"><div class="gstr-uline" style="margin:0 auto 14px"></div><h2 class="gstr-stitle" style="margin-bottom:0">Почему выбирают нас</h2></div>
                <div class="gstr-fgrid">
                    <div class="gstr-frow"><div class="gstr-ficon"><i class="fas fa-clock"></i></div><div><div class="gstr-ftitle">Пунктуальность</div><div class="gstr-fdesc">Мониторинг рейсов — встретим точно по прилёту, даже при задержке</div></div></div>
                    <div class="gstr-frow"><div class="gstr-ficon"><i class="fas fa-id-card"></i></div><div><div class="gstr-ftitle">Встреча с табличкой</div><div class="gstr-fdesc">Водитель встретит с именной табличкой у выхода и поможет с багажом</div></div></div>
                    <div class="gstr-frow"><div class="gstr-ficon"><i class="fas fa-car"></i></div><div><div class="gstr-ftitle">Комфортные авто</div><div class="gstr-fdesc">Автомобили бизнес-класса с кондиционером, водой и зарядкой</div></div></div>
                    <div class="gstr-frow"><div class="gstr-ficon"><i class="fas fa-ban"></i></div><div><div class="gstr-ftitle">Без предоплаты</div><div class="gstr-fdesc">Оплата только по прилёту. Бесплатная отмена за 24 часа</div></div></div>
                </div>
            </div>
        </section>

        <section class="gstr-form-sec">
            <div class="container" style="position:relative;z-index:2">
                <div class="gstr-fwrap">
                    <h2 class="gstr-ftop-title">Забронировать трансфер</h2>
                    <p class="gstr-ftop-sub">Заполните форму — ответим в течение 15 минут</p>
                    @if(session('transfer_success'))
                        <div style="background:#e8f7f5;color:#0d7a68;border-radius:11px;padding:14px 18px;margin-bottom:20px;font-weight:600">{{ session('transfer_success') }}</div>
                    @endif
                    @if(session('transfer_error'))
                        <div style="background:#fef2f2;color:#b91c1c;border-radius:11px;padding:14px 18px;margin-bottom:20px;font-weight:600">{{ session('transfer_error') }}</div>
                    @endif
                    @if($errors->any())
                        <div style="background:#fef2f2;color:#b91c1c;border-radius:11px;padding:14px 18px;margin-bottom:20px;font-size:14px">
                            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('transfer-inquiry') }}" id="transferForm">
                        @csrf
                        <div class="gstr-fform-grid">
                            <div class="gstr-fgroup full"><label class="gstr-flbl">Маршрут *</label>
                            <select name="route" class="gstr-sel" required>
                                <option value="">— Выберите маршрут —</option>
                                <option value="airport_naftalan" @selected(old('route') == 'airport_naftalan')>Аэропорт Гейдар Алиев → Нафталан (49 ₼)</option>
                                <option value="airport_lankaran" @selected(old('route') == 'airport_lankaran')>Аэропорт Гейдар Алиев → Ленкорань (49 ₼)</option>
                                <option value="airport_baku" @selected(old('route') == 'airport_baku')>Аэропорт Гейдар Алиев → Баку (15 ₼)</option>
                                <option value="ganja_naftalan" @selected(old('route') == 'ganja_naftalan')>Аэропорт Гянджа → Нафталан (по запросу)</option>
                            </select></div>
                            <div class="gstr-fgroup"><label class="gstr-flbl">Дата *</label><input type="date" name="date" class="gstr-inp" value="{{ old('date') }}" required></div>
                            <div class="gstr-fgroup"><label class="gstr-flbl">Время прилёта</label><input type="time" name="transfer_time" class="gstr-inp" value="{{ old('transfer_time') }}"></div>
                            <div class="gstr-fgroup"><label class="gstr-flbl">Номер рейса</label><input type="text" name="flight_number" class="gstr-inp" placeholder="J2 123" value="{{ old('flight_number') }}"></div>
                            <div class="gstr-fgroup"><label class="gstr-flbl">Имя и фамилия *</label><input type="text" name="full_name" class="gstr-inp" placeholder="Ваше имя" value="{{ old('full_name') }}" required></div>
                            <div class="gstr-fgroup"><label class="gstr-flbl">Телефон *</label><input type="tel" name="phone" class="gstr-inp" placeholder="+994 55 ..." value="{{ old('phone') }}" required></div>
                            <div class="gstr-fgroup"><label class="gstr-flbl">Email</label><input type="email" name="email" class="gstr-inp" placeholder="your@email.com" value="{{ old('email') }}"></div>
                            <div class="gstr-fgroup full"><label class="gstr-flbl">Особые пожелания</label><textarea name="special_requests" class="gstr-ta" placeholder="Детское кресло, количество пассажиров...">{{ old('special_requests') }}</textarea></div>
                        </div>
                        <button type="submit" class="gstr-sbtn" id="submitBtn">Забронировать трансфер →</button>
                    </form>
                    <div class="gstr-gnotes">
                        <span class="gstr-gnote"><i class="fas fa-ban"></i> Без предоплаты</span>
                        <span class="gstr-gnote"><i class="fas fa-shield-alt"></i> Бесплатная отмена</span>
                        <span class="gstr-gnote"><i class="fas fa-lock"></i> Данные защищены</span>
                    </div>
                </div>
            </div>
        </section>
        <script>document.getElementById('transferForm').addEventListener('submit',function(){var b=document.getElementById('submitBtn');b.textContent='Отправка...';b.disabled=true;});</script>


    {{-- ================================================================ --}}
    {{-- VIDEO GALLERY PAGE --}}
    {{-- ================================================================ --}}
    @elseif($custom_page->slug == 'video')

        <style>
        .vg-section { padding: 60px 0 80px; background: var(--cp-white); }
        .vg-heading { text-align: center; margin-bottom: 48px; }
        .vg-heading h2 { font-family: var(--cp-font-display) !important; font-size: 40px !important; font-weight: 400 !important; color: var(--cp-ink) !important; margin-bottom: 12px !important; }
        .vg-heading p { font-family: var(--cp-font-body); font-size: 16px; color: var(--cp-slate); max-width: 500px; margin: 0 auto; }
        .vg-heading__line { width: 50px; height: 3px; background: linear-gradient(90deg, var(--cp-indigo), var(--cp-indigo-bright)); border-radius: 2px; margin: 20px auto 0; }
        .vg-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 36px 28px; }
        .vg-card { text-align: center; cursor: pointer; transition: transform 0.4s var(--cp-ease); }
        .vg-card:hover { transform: translateY(-8px); }
        .vg-card__thumb { position: relative; width: 200px; height: 200px; margin: 0 auto 16px; border-radius: 50%; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.1); transition: all 0.4s var(--cp-ease); }
        .vg-card:hover .vg-card__thumb { box-shadow: 0 16px 50px rgba(67,56,202,0.2); }
        .vg-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s var(--cp-ease); }
        .vg-card:hover .vg-card__thumb img { transform: scale(1.1); }
        .vg-card__play { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(67,56,202,0.0); transition: all 0.4s; border-radius: 50%; }
        .vg-card:hover .vg-card__play { background: rgba(67,56,202,0.35); }
        .vg-card__play-icon { width: 56px; height: 56px; background: var(--cp-white); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(0,0,0,0.2); opacity: 0; transform: scale(0.7); transition: all 0.4s var(--cp-ease-back); }
        .vg-card:hover .vg-card__play-icon { opacity: 1; transform: scale(1); }
        .vg-card__play-icon i { font-size: 18px; color: var(--cp-indigo); margin-left: 3px; }
        .vg-card__title { font-family: var(--cp-font-body); font-size: 15px; font-weight: 700; color: var(--cp-ink); line-height: 1.4; margin: 0; padding: 0 8px; transition: color 0.3s; }
        .vg-card:hover .vg-card__title { color: var(--cp-indigo); }
        .vg-modal .modal-content { background: #000; border: none; border-radius: 16px; overflow: hidden; }
        .vg-modal__close { position: absolute; top: -44px; right: 0; width: 36px; height: 36px; background: rgba(255,255,255,0.1); border: none; border-radius: 50%; color: #fff; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .vg-modal__close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }
        .vg-modal__video { position: relative; padding-bottom: 56.25%; height: 0; }
        .vg-modal__video iframe { position: absolute; inset: 0; width: 100%; height: 100%; }
        .vg-empty { text-align: center; padding: 60px 20px; }
        .vg-empty i { font-size: 48px; color: var(--cp-cloud); margin-bottom: 16px; }
        .vg-empty p { font-family: var(--cp-font-body); font-size: 16px; color: var(--cp-silver); }
        @media (max-width: 1199px) { .vg-card__thumb { width: 180px; height: 180px; } }
        @media (max-width: 991px) { .vg-grid { grid-template-columns: repeat(3, 1fr); gap: 28px 24px; } .vg-card__thumb { width: 170px; height: 170px; } }
        @media (max-width: 767px) { .vg-grid { grid-template-columns: repeat(2, 1fr); gap: 24px 16px; } .vg-card__thumb { width: 150px; height: 150px; } .vg-heading h2 { font-size: 30px !important; } }
        @media (max-width: 480px) { .vg-card__thumb { width: 130px; height: 130px; } .vg-card__play-icon { width: 44px; height: 44px; } .vg-card__title { font-size: 13px; } }
        </style>

        <section class="vg-section">
            <div class="container">

                @if(isset($custom_page->videos) && $custom_page->videos->where('status', true)->count() > 0)
                    <div class="vg-grid">
                        @foreach($custom_page->videos->where('status', true)->sortBy('sort_order') as $video)
                        <div class="vg-card" data-video-url="{{ $video->embed_url }}">
                            <div class="vg-card__thumb">
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                                <div class="vg-card__play">
                                    <div class="vg-card__play-icon">
                                        <i class="fas fa-play"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="vg-card__title">{{ $video->title }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="vg-empty">
                        <i class="fas fa-video"></i>
                        <p>Видео скоро будут добавлены</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- Video Player Modal --}}
        <div class="modal fade vg-modal" id="vgVideoModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="vg-modal__close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                    <div class="modal-body p-0">
                        <div class="vg-modal__video">
                            <iframe id="vgVideoIframe" src="" frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cards = document.querySelectorAll('.vg-card');
            var modal = document.getElementById('vgVideoModal');
            var iframe = document.getElementById('vgVideoIframe');
            if (!modal || !iframe) return;
            cards.forEach(function(card) {
                card.addEventListener('click', function() {
                    var url = this.getAttribute('data-video-url');
                    if (url) {
                        iframe.src = url + '?autoplay=1&rel=0';
                        var bsModal = new bootstrap.Modal(modal);
                        bsModal.show();
                    }
                });
            });
            modal.addEventListener('hide.bs.modal', function() { iframe.src = ''; });
        });
        </script>

    {{-- ================================================================ --}}
    {{-- DEFAULT PAGE --}}
    {{-- ================================================================ --}}
    @else
        @if($custom_page->show_video_banner)
        <section class="gscon-hero">
            <div class="container" style="position:relative;z-index:1">
                <div class="row"><div class="col-lg-8">
                    <div class="gscon-hero-label">🩺 Бесплатная консультация</div>
                    <h1 class="gscon-hero-title">Какой санаторий<br>подойдёт именно вам?</h1>
                    <p class="gscon-hero-subtitle">Наш врач изучит ваш диагноз и подберёт лучшее лечение — бесплатно, без обязательств, за 15 минут</p>
                    <div class="gscon-trust-row">
                        <div class="gscon-trust-pill"><i class="fas fa-check-circle"></i> Бесплатно</div>
                        <div class="gscon-trust-pill"><i class="fas fa-clock"></i> Ответ за 15 минут</div>
                        <div class="gscon-trust-pill"><i class="fas fa-shield-alt"></i> Без обязательств</div>
                    </div>
                    <div class="gscon-examples">
                        <span class="gscon-example-tag">🫧 Псориаз → Нафталан</span>
                        <span class="gscon-example-tag">🫀 ЖКТ → Боржоми</span>
                        <span class="gscon-example-tag">🦴 Суставы → Цхалтубо</span>
                        <span class="gscon-example-tag">💧 Почки → Карловы Вары</span>
                    </div>
                </div></div>
            </div>
        </section>

        <section class="gscon-doctor">
            <div class="container">
                <div class="gscon-section-underline"></div>
                <div class="gscon-section-label">Ваш врач-консультант</div>
                <h2 class="gscon-section-title">Знакомьтесь с доктором</h2>
                <div class="gscon-doctor-card">
                    <div><img src="{{ $custom_page->doctor_image ? asset($custom_page->doctor_image) : asset('images/doctor-rena.jpg') }}" alt="Рена Кенгерли" class="gscon-doctor-photo"></div>
                    <div>
                        <div class="gscon-doctor-name">Рена Кенгерли</div>
                        <div class="gscon-doctor-role">Главный врач · Дерматовенеролог</div>
                        <div class="gscon-doctor-edu">Азербайджанский Медицинский Университет, 2006</div>
                        <div class="gscon-doctor-stats">
                            <div class="gscon-stat-box"><div class="gscon-stat-num">15+</div><div class="gscon-stat-lbl">лет опыта</div></div>
                            <div class="gscon-stat-box"><div class="gscon-stat-num">5 000+</div><div class="gscon-stat-lbl">пациентов</div></div>
                            <div class="gscon-stat-box"><div class="gscon-stat-num">98%</div><div class="gscon-stat-lbl">успешных случаев</div></div>
                        </div>
                        <p class="gscon-doctor-desc">Специализируется на подборе санаторного лечения для пациентов с кожными заболеваниями — псориаз, экзема, атопический дерматит. Помогает найти оптимальный курорт с учётом диагноза, бюджета и предпочтений.</p>
                        <div class="gscon-spec-list">
                            <span class="gscon-spec-tag">Псориаз</span>
                            <span class="gscon-spec-tag">Экзема</span>
                            <span class="gscon-spec-tag">Атопический дерматит</span>
                            <span class="gscon-spec-tag">Нафталанотерапия</span>
                            <span class="gscon-spec-tag">Бальнеология</span>
                        </div>
                        @if($custom_page->video_url)
                        <a href="#" class="gscon-video-btn" data-bs-toggle="modal" data-bs-target="#videoModal"><i class="fas fa-play-circle"></i> Посмотреть видео</a>
                        @else
                        <a href="#" class="gscon-video-btn"><i class="fas fa-play-circle"></i> Посмотреть видео</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @if($custom_page->video_url)
        <div class="modal fade cp-modal" id="videoModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="cp-modal__close" data-bs-dismiss="modal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    <div class="modal-body p-0"><div class="cp-video-wrap"><iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe></div></div>
                </div>
            </div>
        </div>
        @endif
        @else

        <section class="def-content">
            <div class="td_height_100"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="def-content__inner">{!! clean($custom_page->description) !!}</div>
                    </div>
                </div>
            </div>
            <div class="td_height_100"></div>
        </section>

        @if($custom_page->show_video_banner)
        <section class="def-banner">
            <div class="def-banner__bg" style="background-image: url('{{ asset($custom_page->banner_image) }}');"></div>
            <div class="def-banner__overlay"></div>
            <div class="def-banner__mesh"></div>
            <div class="def-banner__dots"><span></span><span></span><span></span><span></span><span></span></div>
            <div class="container">
                <div class="def-banner__grid">
                    <div class="def-banner__text">
                        @if($custom_page->doctor_name)
                        <div class="def-badge">
                            <div class="def-badge__icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                            <span class="def-badge__name">{{ $custom_page->doctor_name }}</span>
                        </div>
                        @endif
                        @if($custom_page->banner_title)<h1 class="def-banner__headline">{{ $custom_page->banner_title }}</h1>@endif
                        @if($custom_page->banner_description)<p class="def-banner__desc">{{ $custom_page->banner_description }}</p>@endif
                        <div class="def-stats">
                            <div class="def-stat"><span class="def-stat__num">15+</span><span class="def-stat__lbl">лет опыта</span></div>
                            <div class="def-stat__sep"></div>
                            <div class="def-stat"><span class="def-stat__num">5000+</span><span class="def-stat__lbl">пациентов</span></div>
                            <div class="def-stat__sep"></div>
                            <div class="def-stat"><span class="def-stat__num">98%</span><span class="def-stat__lbl">успешности</span></div>
                        </div>
                    </div>
                    <div class="def-video">
                        <div class="def-video__wrap">
                            <div class="def-video__ripple"></div><div class="def-video__ripple def-video__ripple--d"></div>
                            <button class="def-video__btn" data-bs-toggle="modal" data-bs-target="#videoModal"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></button>
                        </div>
                        <p class="def-video__label">Посмотреть видео</p>
                    </div>
                    @if($custom_page->doctor_image)
                    <div class="def-doctor">
                        <div class="def-doctor__frame">
                            <div class="def-doctor__corner def-doctor__corner--tl"></div>
                            <div class="def-doctor__corner def-doctor__corner--br"></div>
                            <img src="{{ asset($custom_page->doctor_image) }}" alt="{{ $custom_page->doctor_name }}">
                            <div class="def-doctor__glow"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        <div class="td_height_120"></div>
        <div class="modal fade cp-modal" id="videoModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <button type="button" class="cp-modal__close" data-bs-dismiss="modal"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                    <div class="modal-body p-0"><div class="cp-video-wrap"><iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe></div></div>
                </div>
            </div>
        </div>
        @endif

        @endif
    @endif

    {{-- ================================================================ --}}
    {{-- CONSULTATION FORM --}}
    {{-- ================================================================ --}}
    @if($custom_page->show_consultation_form)
    @if($custom_page->show_video_banner)
    <section class="gscon-form-section" id="consultation-form">
        <div class="container">
            <div class="text-center mb-4">
                <div class="gscon-section-underline" style="margin:0 auto 16px"></div>
                <h2 class="gscon-section-title" style="margin-bottom:8px">Задайте вопрос врачу</h2>
                <p style="color:#6b7280;font-size:16px">Ответим за 15 минут — бесплатно и без обязательств</p>
            </div>
            <div class="gscon-form-wrap">
                <form action="{{ route('consultation.store') }}" method="POST" id="consultForm">
                    @csrf
                    <input type="hidden" name="custom_page_id" value="{{ $custom_page->id }}">
                    <div class="gscon-form-grid">
                        <div class="gscon-form-group">
                            <label class="gscon-label">Имя *</label>
                            <input type="text" name="first_name" class="gscon-input" placeholder="Ваше имя" required>
                        </div>
                        <div class="gscon-form-group">
                            <label class="gscon-label">Фамилия *</label>
                            <input type="text" name="last_name" class="gscon-input" placeholder="Ваша фамилия" required>
                        </div>
                        <div class="gscon-form-group">
                            <label class="gscon-label">Email *</label>
                            <input type="email" name="email" class="gscon-input" placeholder="your@email.com" required>
                        </div>
                        <div class="gscon-form-group">
                            <label class="gscon-label">Телефон *</label>
                            <div class="gscon-phone-row">
                                <select name="country" class="gscon-select gscon-phone-select">
                                    <option value="AZ">🇦🇿 +994</option>
                                    <option value="RU">🇷🇺 +7</option>
                                    <option value="UZ">🇺🇿 +998</option>
                                    <option value="KZ">🇰🇿 +7</option>
                                    <option value="GE">🇬🇪 +995</option>
                                    <option value="BY">🇧🇾 +375</option>
                                    <option value="UA">🇺🇦 +380</option>
                                    <option value="IL">🇮🇱 +972</option>
                                    <option value="DE">🇩🇪 +49</option>
                                </select>
                                <input type="tel" name="phone" class="gscon-input gscon-phone-input" placeholder="555 12 34 56" required>
                            </div>
                        </div>
                        <div class="gscon-form-group full">
                            <label class="gscon-label">Ваш вопрос / диагноз *</label>
                            <textarea name="message" class="gscon-textarea" placeholder="Опишите ваш диагноз или вопрос..." required></textarea>
                        </div>
                    </div>
                    <label class="gscon-consent">
                        <input type="checkbox" name="marketing_consent" value="1" required>
                        <span>Я согласен(а) на обработку персональных данных</span>
                    </label>
                    <button type="submit" class="gscon-submit" id="gsconSubmitBtn">Отправить вопрос доктору</button>
                    <div class="gscon-guarantee-row">
                        <div class="gscon-guarantee-item"><i class="fas fa-lock"></i> Данные защищены</div>
                        <div class="gscon-guarantee-item"><i class="fas fa-clock"></i> Ответ за 15 минут</div>
                        <div class="gscon-guarantee-item"><i class="fas fa-ban"></i> Без спама</div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
    document.getElementById('consultForm').addEventListener('submit',function(){
        var b=document.getElementById('gsconSubmitBtn');
        b.textContent='Отправка...';b.disabled=true;
    });
    </script>
    @else
    <section class="cp-form-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="cp-form-card">
                        <div class="cp-form-head">
                            <div class="cp-form-head__icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                            </div>
                            <h2>{{ $custom_page->form_title ?? 'Задайте вопрос доктору' }}</h2>
                            @if($custom_page->form_description)<p>{{ $custom_page->form_description }}</p>@endif
                            <div class="cp-form-head__line"></div>
                        </div>

                        <form action="{{ route('consultation.store') }}" method="POST" id="consultationForm">
                            @csrf
                            <input type="hidden" name="custom_page_id" value="{{ $custom_page->id }}">
                            <div class="cp-grid">
                                <div class="cp-field">
                                    <label><span class="cp-label-dot"></span>Имя</label>
                                    <div class="cp-input-box"><input type="text" name="first_name" placeholder="Иван" required><div class="cp-input-bar"></div></div>
                                </div>
                                <div class="cp-field">
                                    <label><span class="cp-label-dot"></span>Фамилия</label>
                                    <div class="cp-input-box"><input type="text" name="last_name" placeholder="Петров" required><div class="cp-input-bar"></div></div>
                                </div>
                                <div class="cp-field">
                                    <label><span class="cp-label-dot"></span>Email</label>
                                    <div class="cp-input-box"><input type="email" name="email" placeholder="example@mail.com" required><div class="cp-input-bar"></div></div>
                                </div>
                                <div class="cp-field">
                                    <label><span class="cp-label-dot"></span>Страна и телефон</label>
                                    <div class="cp-phone">
                                        <select name="country" id="countrySelector">
                                            <option value="AZ" data-code="+994">🇦🇿 Азербайджан</option>
                                            <option value="RU" data-code="+7">🇷🇺 Россия</option>
                                            <option value="TR" data-code="+90">🇹🇷 Турция</option>
                                            <option value="UA" data-code="+380">🇺🇦 Украина</option>
                                            <option value="KZ" data-code="+7">🇰🇿 Казахстан</option>
                                            <option value="GE" data-code="+995">🇬🇪 Грузия</option>
                                            <option value="BY" data-code="+375">🇧🇾 Беларусь</option>
                                            <option value="UZ" data-code="+998">🇺🇿 Узбекистан</option>
                                            <option value="DE" data-code="+49">🇩🇪 Германия</option>
                                            <option value="IL" data-code="+972">🇮🇱 Израиль</option>
                                        </select>
                                        <div class="cp-phone__num">
                                            <span class="cp-phone__code" id="phoneCodeDisplay">+994</span>
                                            <input type="tel" name="phone" placeholder="50 123 45 67" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="cp-field cp-full">
                                    <label><span class="cp-label-dot"></span>Сообщение</label>
                                    <div class="cp-input-box"><textarea name="message" rows="5" placeholder="Ваш вопрос доктору" required></textarea><div class="cp-input-bar"></div></div>
                                </div>
                                <div class="cp-field cp-full">
                                    <label class="cp-check">
                                        <input type="checkbox" name="marketing_consent" value="1">
                                        <span class="cp-check__box"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></span>
                                        <span class="cp-check__txt">Я согласен на использование моих персональных данных. Вы можете отписаться в любое время.</span>
                                    </label>
                                </div>
                                <div class="cp-field cp-full">
                                    <button type="submit" class="cp-submit" id="submitBtn">
                                        <span class="cp-submit__main">
                                            <span>Отправить вопрос доктору</span>
                                            <span class="cp-submit__icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                                        </span>
                                        <span class="cp-submit__loading"><svg class="cp-spin" width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="60" stroke-linecap="round"/></svg>Отправка...</span>
                                        <div class="cp-submit__shine"></div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @endif
</div>
@endsection

@push('js_section')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // CONSULTATION FORM - Country selector + Video modal + Submit
    // ============================================================
    var cs = document.getElementById('countrySelector');
    var pc = document.getElementById('phoneCodeDisplay');
    if (cs && pc) cs.addEventListener('change', function() { pc.textContent = this.options[this.selectedIndex].getAttribute('data-code'); });

    var vu = "{{ $custom_page->video_url ?? '' }}";
    var vm = document.getElementById('videoModal');
    var vi = document.getElementById('videoIframe');
    if (vm) {
        vm.addEventListener('show.bs.modal', function() { if (vi && vu) vi.src = toEmbed(vu); });
        vm.addEventListener('hide.bs.modal', function() { if (vi) vi.src = ''; });
    }
    function toEmbed(u) {
        if (u.indexOf('youtube.com/watch')>-1){var i=u.split('v=')[1];if(i){var a=i.indexOf('&');if(a>-1)i=i.substring(0,a);return'https://www.youtube.com/embed/'+i+'?autoplay=1&rel=0';}}
        if (u.indexOf('youtu.be/')>-1){var i=u.split('youtu.be/')[1];if(i){var q=i.indexOf('?');if(q>-1)i=i.substring(0,q);return'https://www.youtube.com/embed/'+i+'?autoplay=1&rel=0';}}
        if (u.indexOf('vimeo.com/')>-1){var i=u.split('vimeo.com/')[1];if(i){var q=i.indexOf('?');if(q>-1)i=i.substring(0,q);return'https://player.vimeo.com/video/'+i+'?autoplay=1';}}
        return u;
    }

    var f = document.getElementById('consultationForm');
    var b = document.getElementById('submitBtn');
    if (f && b) f.addEventListener('submit', function() {
        var m = b.querySelector('.cp-submit__main');
        var l = b.querySelector('.cp-submit__loading');
        if (m) m.style.display = 'none';
        if (l) l.style.display = 'flex';
        b.disabled = true;
    });

    // ============================================================
    // TRANSFER BOOKING - Search, Vehicle Selection, Checkout
    // ============================================================
    var searchBtn = document.getElementById('tbSearchBtn');
    var routeSelect = document.getElementById('tbRouteSelect');
    var dateInput = document.getElementById('tbDateInput');
    var passengersInput = document.getElementById('tbPassengers');
    var vehiclesArea = document.getElementById('tbVehiclesArea');
    var vehiclesList = document.getElementById('tbVehiclesList');
    var vehiclesTitle = document.getElementById('tbVehiclesTitle');
    var loadingArea = document.getElementById('tbLoadingArea');
    var checkoutDiv = document.getElementById('tbCheckout');
    var tbSection = document.getElementById('transferBookingSection');

    // State
    var selectedRouteData = null;
    var selectedVehicleData = null;
    var allVehiclesData = [];

    if (!searchBtn) return; // Not on transfer page

    // Search button click
    searchBtn.addEventListener('click', function() {
        var routeId = routeSelect.value;
        var date = dateInput.value;
        var passengers = parseInt(passengersInput.value) || 2;

        if (!routeId) {
            routeSelect.style.borderColor = '#ef4444';
            routeSelect.focus();
            return;
        }
        routeSelect.style.borderColor = '';

        if (!date) {
            dateInput.style.borderColor = '#ef4444';
            dateInput.focus();
            return;
        }
        dateInput.style.borderColor = '';

        // Get route data from select option
        var opt = routeSelect.options[routeSelect.selectedIndex];
        selectedRouteData = {
            id: routeId,
            from: opt.getAttribute('data-from'),
            to: opt.getAttribute('data-to'),
            price: parseFloat(opt.getAttribute('data-price')) || 0,
            duration: opt.getAttribute('data-duration')
        };

        // Hide checkout & show loading
        checkoutDiv.classList.remove('active');
        vehiclesArea.style.display = 'none';
        loadingArea.style.display = 'block';
        selectedVehicleData = null;
        if (tbSection) tbSection.classList.add('has-results');

        // Fetch vehicles via AJAX
        fetch('/api/transfer/vehicles')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                loadingArea.style.display = 'none';

                if (!data.success || !data.vehicles) {
                    vehiclesList.innerHTML = '<div class="tb-empty"><div class="tb-empty__icon"><i class="fas fa-exclamation-triangle"></i></div><h4>Ошибка загрузки</h4></div>';
                    vehiclesArea.style.display = 'block';
                    return;
                }

                // Filter by passenger capacity
                var filtered = data.vehicles.filter(function(v) {
                    return v.passenger_capacity >= passengers;
                });

                allVehiclesData = filtered;

                if (filtered.length === 0) {
                    vehiclesList.innerHTML = '<div class="tb-empty"><div class="tb-empty__icon"><i class="fas fa-exclamation-triangle"></i></div><h4>Транспорт не найден</h4><p>Для выбранного количества пассажиров нет доступного транспорта (' + passengers + ')</p></div>';
                    vehiclesTitle.innerHTML = '';
                    vehiclesArea.style.display = 'block';
                    return;
                }

                vehiclesTitle.innerHTML = 'Доступный транспорт <span>(' + filtered.length + ')</span>';

                var html = '';
                filtered.forEach(function(v) {
                    var totalPrice = (parseFloat(selectedRouteData.price) + parseFloat(v.price)).toFixed(2);
                    var featuresHtml = '';
                    if (v.features && v.features.length > 0) {
                        v.features.forEach(function(f) {
                            featuresHtml += '<span class="tb-vehicle__feature"><i class="fas fa-check"></i> ' + f + '</span>';
                        });
                    }

                    html += '<div class="tb-vehicle" data-vehicle-id="' + v.id + '" data-vehicle-name="' + v.name + '" data-vehicle-price="' + v.price + '">';
                    html += '<div class="tb-vehicle__inner">';

                    // Image
                    html += '<div class="tb-vehicle__img">';
                    if (v.image) {
                        html += '<img src="' + v.image + '" alt="' + v.name + '">';
                    } else {
                        html += '<div class="tb-vehicle__img-placeholder"><i class="fas fa-car"></i></div>';
                    }
                    html += '</div>';

                    // Info
                    html += '<div class="tb-vehicle__info">';
                    html += '<h4 class="tb-vehicle__name">' + v.name + '</h4>';
                    html += '<div class="tb-vehicle__specs">';
                    html += '<span class="tb-vehicle__spec"><i class="fas fa-users"></i> ' + v.passenger_capacity + ' пассажиров</span>';
                    html += '<span class="tb-vehicle__spec"><i class="fas fa-suitcase"></i> ' + v.luggage_capacity + ' багаж</span>';
                    html += '</div>';
                    if (featuresHtml) {
                        html += '<div class="tb-vehicle__features">' + featuresHtml + '</div>';
                    }
                    html += '</div>';

                    // Price
                    html += '<div class="tb-vehicle__price">';
                    html += '<div class="tb-vehicle__price-label">Итого</div>';
                    html += '<div class="tb-vehicle__price-val">' + totalPrice + ' ₼</div>';
                    html += '<button type="button" class="tb-vehicle__select-btn"><i class="fas fa-check"></i> Выбрать</button>';
                    html += '</div>';

                    html += '</div></div>';
                });

                vehiclesList.innerHTML = html;
                vehiclesArea.style.display = 'block';

                // Scroll to results section
                if (tbSection) tbSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

                // Add click handlers for vehicle selection
                document.querySelectorAll('.tb-vehicle').forEach(function(card) {
                    card.addEventListener('click', function() {
                        selectVehicle(this);
                    });
                });
            })
            .catch(function(err) {
                loadingArea.style.display = 'none';
                vehiclesList.innerHTML = '<div class="tb-empty"><div class="tb-empty__icon"><i class="fas fa-exclamation-triangle"></i></div><h4>Ошибка соединения</h4><p>' + err.message + '</p></div>';
                vehiclesArea.style.display = 'block';
            });
    });

    // Vehicle selection
    function selectVehicle(cardEl) {
        // Remove previous selection
        document.querySelectorAll('.tb-vehicle').forEach(function(c) { c.classList.remove('selected'); });
        cardEl.classList.add('selected');

        var vehicleId = cardEl.getAttribute('data-vehicle-id');
        var vehicleName = cardEl.getAttribute('data-vehicle-name');
        var vehiclePrice = parseFloat(cardEl.getAttribute('data-vehicle-price')) || 0;

        selectedVehicleData = {
            id: vehicleId,
            name: vehicleName,
            price: vehiclePrice
        };

        var routePrice = parseFloat(selectedRouteData.price) || 0;
        var totalPrice = (routePrice + vehiclePrice).toFixed(2);

        // Fill hidden fields
        document.getElementById('tbFormRouteId').value = selectedRouteData.id;
        document.getElementById('tbFormVehicleId').value = vehicleId;
        document.getElementById('tbFormDate').value = dateInput.value;
        document.getElementById('tbFormPassengers').value = passengersInput.value;

        // Fill display fields
        document.getElementById('tbDisplayRoute').textContent = selectedRouteData.from + ' → ' + selectedRouteData.to;
        var dateObj = new Date(dateInput.value);
        document.getElementById('tbDisplayDate').textContent = dateObj.toLocaleDateString('ru-RU', {day: '2-digit', month: '2-digit', year: 'numeric'});

        // Fill summary
        document.getElementById('tbSummaryRoutePrice').textContent = routePrice.toFixed(2) + ' ₼';
        document.getElementById('tbSummaryVehicleName').textContent = vehicleName;
        document.getElementById('tbSummaryVehiclePrice').textContent = vehiclePrice.toFixed(2) + ' ₼';
        document.getElementById('tbSummaryTotal').textContent = totalPrice + ' ₼';

        // Show checkout
        checkoutDiv.classList.add('active');

        // Scroll to checkout
        setTimeout(function() {
            checkoutDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }

    // Create account toggle
    var createAccountCb = document.getElementById('tbCreateAccount');
    var passwordField = document.getElementById('tbPasswordField');
    if (createAccountCb && passwordField) {
        createAccountCb.addEventListener('change', function() {
            passwordField.style.display = this.checked ? 'block' : 'none';
            var pwInput = passwordField.querySelector('input');
            if (this.checked) {
                pwInput.setAttribute('required', 'required');
            } else {
                pwInput.removeAttribute('required');
            }
        });
    }

    // Form submit loading state
    var checkoutForm = document.getElementById('tbCheckoutForm');
    var submitBtn = document.getElementById('tbSubmitBtn');
    if (checkoutForm && submitBtn) {
        checkoutForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="cp-spin" width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="60" stroke-linecap="round"/></svg> Обработка...';
        });
    }
});
</script>
@endpush