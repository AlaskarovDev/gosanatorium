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
        
        {{-- Phone + switchers inside the drawer (the top bar is hidden on mobile) --}}
        <div class="gs-drawer-contact">
            @if(!empty($footer->phone))
                <a class="gs-drawer-contact__phone" href="tel:{{ $footer->phone }}"><small>{{ __('translate.Call Us') }}:</small>{{ $footer->phone }}</a>
            @endif
            <div class="gs-drawer-contact__switchers">
                @php
                    $gsDCur = \Modules\Currency\App\Models\Currency::where('status', 'active')->get();
                    $gsDCurSess = session('currency');
                    $gsDCurCode = $gsDCurSess ? $gsDCurSess['currency_code'] : optional($gsDCur->where('is_default', 'yes')->first())->currency_code ?? 'USD';
                    $gsDLangs = \Modules\Language\App\Models\Language::where('status', 1)->get();
                    $gsDLang = session('lang_code', config('app.locale', 'en'));
                @endphp
                @if($gsDCur->count() > 1)
                    <select class="currency_code notranslate" translate="no" aria-label="{{ __('translate.Currency') }}">
                        @foreach($gsDCur as $curr)
                            <option value="{{ $curr->currency_code }}" {{ $gsDCurCode == $curr->currency_code ? 'selected' : '' }}>{{ $curr->currency_icon }} {{ $curr->currency_code }}</option>
                        @endforeach
                    </select>
                @endif
                @if($gsDLangs->count() > 1)
                    <select class="language_code notranslate" translate="no" aria-label="{{ __('translate.Language') }}">
                        @foreach($gsDLangs as $lang)
                            <option value="{{ $lang->lang_code }}" {{ $gsDLang == $lang->lang_code ? 'selected' : '' }}>{{ strtoupper($lang->lang_code) }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
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