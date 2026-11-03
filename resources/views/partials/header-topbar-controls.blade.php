@php
    $gsTopCurrencies = \Modules\Currency\App\Models\Currency::where('status', 'active')->get();
    $gsSessionCurrency = session('currency');
    $gsCurrencyCode = $gsSessionCurrency ? $gsSessionCurrency['currency_code'] : null;
    if (!$gsCurrencyCode) {
        $gsDefaultCurrency = $gsTopCurrencies->where('is_default', 'yes')->first();
        $gsCurrencyCode = $gsDefaultCurrency ? $gsDefaultCurrency->currency_code : 'USD';
    }
    $gsTopLanguages = \Modules\Language\App\Models\Language::where('status', 1)->get();
    $gsLangCode = session('lang_code', config('app.locale', 'en'));
@endphp
@if($gsTopCurrencies->count() > 1)
                    <select class="currency_code ctrl-btn tg-currency-select notranslate" translate="no" aria-label="{{ __('translate.Currency') }}">
                        @foreach($gsTopCurrencies as $curr)
                            <option value="{{ $curr->currency_code }}" {{ $gsCurrencyCode == $curr->currency_code ? 'selected' : '' }}>
                                {{ $curr->currency_icon }} {{ $curr->currency_code }}
                            </option>
                        @endforeach
                    </select>
                @endif
                @if($gsTopLanguages->count() > 1)
                    <select class="language_code ctrl-btn tg-language-select notranslate" translate="no" aria-label="{{ __('translate.Language') }}">
                        @foreach($gsTopLanguages as $lang)
                            <option value="{{ $lang->lang_code }}" {{ $gsLangCode == $lang->lang_code ? 'selected' : '' }}>
                                {{ strtoupper($lang->lang_code) }}
                            </option>
                        @endforeach
                    </select>
                @endif
                @auth('web')
                    <a class="ctrl-btn" href="{{ Auth::guard('web')->user()->is_seller == 1 ? route('agency.dashboard') : route('user.dashboard') }}">
                        <i class="far fa-user" aria-hidden="true"></i> {{ __('translate.Dashboard') }}
                    </a>
                @endauth
