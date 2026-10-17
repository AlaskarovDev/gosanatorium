<div class="tgmobile__menu">
    <nav class="tgmobile__menu-box">
        <div class="close-btn"><i class="fa-solid fa-xmark"></i></div>
        <div class="nav-logo">
            <a href="{{ route('home') }}"><img src="{{ asset($general_setting->secondary_logo) }}"
                    alt="logo"></a>
        </div>
        <div class="tgmobile__menu-outer">
            <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
        </div>
        
        {{-- ========== SOCIAL CONTACT ICONS (Header ilə eyni) ========== --}}
        @php
            $phoneClean = $footer->phone ? preg_replace('/[^0-9]/', '', $footer->phone) : null;
        @endphp
        <div class="social-links">
            <ul class="list-wrap mobile-social-icons">
                @if($phoneClean)
                <li><a href="https://wa.me/{{ $phoneClean }}" target="_blank" class="mobile-social-whatsapp"><i class="fab fa-whatsapp"></i></a></li>
                @endif
                @if($phoneClean)
                <li><a href="https://t.me/+{{ $phoneClean }}" target="_blank" class="mobile-social-telegram"><i class="fab fa-telegram-plane"></i></a></li>
                @endif
                <li><a href="https://vk.com/gosanatorium" target="_blank" rel="noopener" class="mobile-social-vk"><i class="fab fa-vk"></i></a></li>
                <li><a href="https://ok.ru/gosanatorium" target="_blank" rel="noopener" class="mobile-social-ok"><i class="fab fa-odnoklassniki"></i></a></li>
                @if($footer->email ?? false)
                <li><a href="mailto:{{ $footer->email }}" class="mobile-social-email"><i class="fas fa-envelope"></i></a></li>
                @endif
            </ul>
        </div>
    </nav>
</div>
<div class="tgmobile__menu-backdrop"></div>

<style>
.mobile-social-icons {
    display: flex;
    justify-content: center;
    gap: 10px;
}
.mobile-social-icons li a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    color: #fff !important;
    font-size: 18px;
    transition: all 0.3s ease;
}
.mobile-social-whatsapp { background: #25D366 !important; }
.mobile-social-telegram { background: #0088cc !important; }
.mobile-social-vk { background: #4C75A3 !important; }
.mobile-social-ok { background: #EE8208 !important; }
.mobile-social-email { background: #EA4335 !important; }
</style>