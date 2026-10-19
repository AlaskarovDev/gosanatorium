<!-- offCanvas-menu -->
    <div class="offCanvas__info">
        <div class="offCanvas__close-icon menu-close">
            <button><i class="fa-sharp fa-regular fa-xmark"></i></button>
        </div>
        <div class="offCanvas__logo mb-30">
            <a href="{{ route('home') }}"><img src="{{ asset($general_setting?->secondary_logo) }}"
                    alt="Logo"></a>
        </div>
        <div class="offCanvas__side-info mb-30">
            <div class="contact-list mb-30">
                <h4>{{ __('translate.Office Address') }}</h4>
                <p>{{ $footer->address }}</p>
            </div>
            <div class="contact-list mb-30">
                <h4>{{ __('translate.Phone Number') }}</h4>
                <p>{{ $footer->phone }}</p>
            </div>
            <div class="contact-list mb-30">
                <h4>{{ __('translate.Email Address') }}</h4>
                <p>{{ $footer->email }}</p>
            </div>
        </div>

        {{-- ========== SOCIAL CONTACT ICONS (Header ilə eyni) ========== --}}
        @php
            $phoneClean = $footer->phone ? preg_replace('/[^0-9]/', '', $footer->phone) : null;
        @endphp
        <div class="offCanvas__social-icon mt-30">
            @if($phoneClean)
            <a href="https://wa.me/{{ $phoneClean }}" target="_blank" title="WhatsApp" class="offcanvas-social-btn offcanvas-whatsapp">
                <i class="fab fa-whatsapp"></i>
            </a>
            @endif

            @if($phoneClean)
            <a href="https://t.me/+{{ $phoneClean }}" target="_blank" title="Telegram" class="offcanvas-social-btn offcanvas-telegram">
                <i class="fab fa-telegram-plane"></i>
            </a>
            @endif

            <a href="https://vk.com/gosanatorium" target="_blank" rel="noopener" title="VK" class="offcanvas-social-btn offcanvas-vk">
                <i class="fab fa-vk"></i>
            </a>
        </div>
    </div>
    <div class="offCanvas__overly"></div>
    <!-- offCanvas-menu-end -->

    <style>
    /* Offcanvas Social Buttons */
    .offCanvas__social-icon {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .offcanvas-social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        color: #fff !important;
        font-size: 20px;
        transition: all 0.3s ease;
        text-decoration: none !important;
    }
    .offcanvas-social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }
    .offcanvas-whatsapp { background: #25D366; }
    .offcanvas-whatsapp:hover { background: #1da851; }
    .offcanvas-telegram { background: #0088cc; }
    .offcanvas-telegram:hover { background: #006699; }
    .offcanvas-vk { background: #4C75A3; }
    .offcanvas-vk:hover { background: #3b6291; }
    .offcanvas-ok { background: #EE8208; }
    .offcanvas-ok:hover { background: #d67205; }
    .offcanvas-email { background: #EA4335; }
    .offcanvas-email:hover { background: #d33426; }
    </style>