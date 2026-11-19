@if ($general_setting->cookie_consent_status == 1)
    <!-- common-modal start  -->
    <div class="common-modal cookie_consent_modal d-none bg-white">
        <button type="button" class="btn-close cookie_consent_close_btn" aria-label="Close"></button>

        <div class="gs-h5 cookie-title">{{ __('translate.Cookies') }}</div>
        <p>{{ $general_setting->cookie_consent_message }}</p>


        <button type="button"
            class="td_btn td_style_1 td_type_3 td_radius_30 td_medium td_fs_14 report-modal-btn cookie_consent_accept_btn">
            <span class="td_btn_in td_accent_color">
                <span>{{ __('translate.Accept') }}</span>
            </span>
        </button>

    </div>
    <!-- common-modal end  -->
@endif
