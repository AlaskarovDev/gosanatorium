{{--
    Task 32: one lead form for the contact page and all «Бесплатная консультация» blocks.
    Params (all optional): $lfType (contact|consult), $lfId, $lfTitle, $lfSub, $lfButton,
    $lfServiceId (preselected sanatorium), $lfDark (bool, light text on dark background).
--}}
@php
    $lfType = $lfType ?? 'contact';
    $lfId = $lfId ?? ('lf' . substr(md5($lfType . microtime()), 0, 6));
    $lfServices = \Illuminate\Support\Facades\Cache::remember('gs_lead_form_services', now()->addHour(), function () {
        return \Modules\TourBooking\App\Models\Service::where('status', true)->with('translation')->get()
            ->map(fn ($s) => ['id' => $s->id, 'title' => gs_display_title($s->translation?->title ?: $s->title)])
            ->sortBy('title')->values()->all();
    });
    $lfCodes = ['+7' => '🇰🇿🇷🇺 +7', '+998' => '🇺🇿 +998', '+994' => '🇦🇿 +994', '+995' => '🇬🇪 +995', '+375' => '🇧🇾 +375', '+996' => '🇰🇬 +996', '+992' => '🇹🇯 +992', '+993' => '🇹🇲 +993', '+380' => '🇺🇦 +380', '+972' => '🇮🇱 +972', '+49' => '🇩🇪 +49', '+90' => '🇹🇷 +90', '+1' => '🇺🇸 +1', '+44' => '🇬🇧 +44'];
@endphp
<div class="gs-lf {{ ! empty($lfDark) ? 'gs-lf--dark' : '' }}" id="{{ $lfId }}">
    <form class="gs-lf__form" method="POST" action="{{ route('lead.store') }}" novalidate data-gs-lead>
        @csrf
        <input type="hidden" name="type" value="{{ $lfType }}">
        <input type="hidden" name="_ts" value="">
        <input type="hidden" name="source_url" value="">
        @if (! empty($lfTitle))<h3 class="gs-lf__title">{{ $lfTitle }}</h3>@endif
        @if (! empty($lfSub))<p class="gs-lf__sub">{{ $lfSub }}</p>@endif

        <div class="gs-lf__row">
            <div class="gs-lf__field">
                <label for="{{ $lfId }}-name">Имя *</label>
                <input id="{{ $lfId }}-name" type="text" name="name" maxlength="120" required autocomplete="name">
                <span class="gs-lf__err" data-err="name"></span>
            </div>
            <div class="gs-lf__field">
                <label for="{{ $lfId }}-phone">Телефон *</label>
                <div class="gs-lf__phone">
                    <select name="phone_code" aria-label="Код страны" data-gs-code>
                        @foreach ($lfCodes as $c => $l)<option value="{{ $c }}">{{ $l }}</option>@endforeach
                    </select>
                    <input id="{{ $lfId }}-phone" type="tel" name="phone" maxlength="30" required autocomplete="tel-national" inputmode="tel" placeholder="700 123 45 67">
                </div>
                <span class="gs-lf__err" data-err="phone"></span>
            </div>
        </div>

        <fieldset class="gs-lf__field gs-lf__methods">
            <legend>Удобный способ связи</legend>
            @foreach (\App\Models\Lead::METHODS as $mk => $ml)
                <label class="gs-lf__pill"><input type="radio" name="contact_method" value="{{ $mk }}" @checked($mk === 'whatsapp')><span>{{ $ml }}</span></label>
            @endforeach
        </fieldset>

        <div class="gs-lf__row">
            <div class="gs-lf__field">
                <label for="{{ $lfId }}-svc">Санаторий <small>(необязательно)</small></label>
                <select id="{{ $lfId }}-svc" name="service_id">
                    <option value="">Помогите выбрать</option>
                    @foreach ($lfServices as $s)
                        <option value="{{ $s['id'] }}" @selected((int) ($lfServiceId ?? 0) === (int) $s['id'])>{{ $s['title'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="gs-lf__field">
                <label for="{{ $lfId }}-dates">Даты <small>(необязательно)</small></label>
                <input id="{{ $lfId }}-dates" type="text" name="dates" maxlength="120" placeholder="напр. 10–24 марта">
            </div>
        </div>

        <div class="gs-lf__field">
            <label for="{{ $lfId }}-msg">Сообщение</label>
            <textarea id="{{ $lfId }}-msg" name="message" rows="3" maxlength="3000" placeholder="Диагноз, пожелания, бюджет…"></textarea>
        </div>

        <div class="gs-lf__hp" aria-hidden="true"><label>Сайт<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

        <label class="gs-lf__consent">
            <input type="checkbox" name="consent" value="1" checked>
            <span>Согласен(на) на обработку персональных данных согласно <a href="{{ route('privacy-policy') }}" target="_blank">политике конфиденциальности</a></span>
        </label>
        <span class="gs-lf__err" data-err="consent"></span>

        <button type="submit" class="gs-lf__submit">{{ $lfButton ?? 'Отправить заявку' }}</button>
        <p class="gs-lf__note">{{ company_fact('working_hours_text') }} · Без предоплаты · Бесплатно</p>
    </form>
    <div class="gs-lf__done" hidden role="status" tabindex="-1">
        <div class="gs-lf__done-icon" aria-hidden="true">✓</div>
        <p class="gs-lf__done-text"></p>
        <a class="gs-lf__done-wa" href="{{ company_fact('whatsapp_url') }}" target="_blank" rel="noopener">Написать в WhatsApp сейчас</a>
    </div>
</div>

@once
<style>
    .gs-lf { background: #fff; border: 1px solid #e8eef4; border-radius: 16px; padding: 22px; box-shadow: 0 4px 24px rgba(15,23,42,.06); text-align: left; color: #1a2540; }
    .gs-lf__title { font-size: 22px; font-weight: 800; margin: 0 0 4px; color: #1a2540; }
    .gs-lf__sub { color: #64748b; font-size: 14px; margin: 0 0 12px; }
    .gs-lf__row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .gs-lf__field { margin: 0 0 12px; border: 0; padding: 0; min-width: 0; }
    .gs-lf__field label, .gs-lf__field legend { display: block; font-size: 14px; font-weight: 600; margin: 0 0 4px; color: #1a2540; float: none; width: auto; }
    .gs-lf__field small { color: #94a3b8; font-weight: 400; }
    .gs-lf input[type=text], .gs-lf input[type=tel], .gs-lf select, .gs-lf textarea { width: 100%; height: 46px; border: 1px solid #d5dee8; border-radius: 10px; padding: 0 12px; font-size: 16px; background: #fff; color: #1a2540; }
    .gs-lf textarea { height: auto; min-height: 90px; padding: 10px 12px; resize: vertical; }
    .gs-lf input:focus, .gs-lf select:focus, .gs-lf textarea:focus { outline: none; border-color: #1a9e87; box-shadow: 0 0 0 3px rgba(26,158,135,.15); }
    .gs-lf__phone { display: flex; gap: 6px; }
    .gs-lf__phone select { flex: 0 0 112px; width: 112px; padding: 0 6px; }
    .gs-lf__methods { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
    .gs-lf__methods legend { width: 100%; }
    .gs-lf__pill { margin: 0; cursor: pointer; }
    .gs-lf__pill input { position: absolute; opacity: 0; pointer-events: none; }
    .gs-lf__pill span { display: inline-block; padding: 8px 14px; border: 1px solid #d5dee8; border-radius: 20px; font-size: 14px; font-weight: 600; color: #334155; }
    .gs-lf__pill input:checked + span { background: #1a9e87; border-color: #1a9e87; color: #fff; }
    .gs-lf__pill input:focus-visible + span { outline: 2px solid #1a9e87; outline-offset: 2px; }
    .gs-lf__hp { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }
    .gs-lf__consent { display: flex; gap: 8px; align-items: flex-start; font-size: 13px; color: #64748b; margin: 4px 0; cursor: pointer; }
    .gs-lf__consent input { margin-top: 3px; width: 16px; height: 16px; accent-color: #1a9e87; flex-shrink: 0; }
    .gs-lf__consent a { color: #1a9e87; text-decoration: underline; }
    .gs-lf__err { display: block; color: #b91c1c; font-size: 13px; min-height: 0; }
    .gs-lf__submit { width: 100%; height: 50px; border: 0; border-radius: 10px; background: #1a9e87; color: #fff; font-weight: 700; font-size: 16px; margin-top: 10px; cursor: pointer; }
    .gs-lf__submit:hover { background: #158573; }
    .gs-lf__submit[disabled] { opacity: .7; cursor: wait; }
    .gs-lf__note { font-size: 12px; color: #94a3b8; text-align: center; margin: 8px 0 0; }
    .gs-lf__done { text-align: center; padding: 30px 10px; }
    .gs-lf__done-icon { width: 64px; height: 64px; border-radius: 50%; background: #dcfce7; color: #16a34a; font-size: 34px; font-weight: 800; line-height: 64px; margin: 0 auto 14px; }
    .gs-lf__done-text { font-size: 18px; font-weight: 600; color: #1a2540; margin: 0 0 16px; }
    .gs-lf__done-wa { display: inline-block; background: #25d366; color: #fff; border-radius: 10px; padding: 10px 18px; font-weight: 700; }
    .gs-lf__done-wa:hover { color: #fff; opacity: .9; }
    @media (max-width: 575px) { .gs-lf { padding: 16px; } .gs-lf__row { grid-template-columns: 1fr; gap: 0; } }
</style>
<script>
(function () {
    function countryCode() {
        var tz = '';
        try { tz = Intl.DateTimeFormat().resolvedOptions().timeZone || ''; } catch (e) {}
        if (/^Asia\/(Tashkent|Samarkand)$/.test(tz)) return '+998';
        if (/^Asia\/Baku$/.test(tz)) return '+994';
        if (/^Asia\/Tbilisi$/.test(tz)) return '+995';
        if (/^Europe\/Minsk$/.test(tz)) return '+375';
        if (/^Asia\/Bishkek$/.test(tz)) return '+996';
        return '+7';
    }
    function init(form) {
        if (form.dataset.ready) return; form.dataset.ready = '1';
        form.elements['_ts'].value = Math.floor(Date.now() / 1000);
        form.elements['source_url'].value = location.href.split('#')[0];
        var code = form.querySelector('[data-gs-code]'); if (code) code.value = countryCode();
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = form.querySelector('.gs-lf__submit'), txt = btn.textContent;
            form.querySelectorAll('[data-err]').forEach(function (s) { s.textContent = ''; });
            var bad = false;
            if (!form.elements['name'].value.trim()) { form.querySelector('[data-err=name]').textContent = 'Укажите имя.'; bad = true; }
            if (form.elements['phone'].value.replace(/\D/g, '').length < 5) { form.querySelector('[data-err=phone]').textContent = 'Укажите телефон.'; bad = true; }
            if (!form.elements['consent'].checked) { form.querySelector('[data-err=consent]').textContent = 'Нужно согласие на обработку данных.'; bad = true; }
            if (bad) return;
            btn.disabled = true; btn.textContent = 'Отправляем…';
            fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
                .then(function (res) {
                    if (res.ok && res.d.ok) {
                        var box = form.parentNode.querySelector('.gs-lf__done');
                        box.querySelector('.gs-lf__done-text').textContent = res.d.message;
                        form.hidden = true; box.hidden = false; box.focus();
                        if (window.gsGoal) window.gsGoal('lead_sent', { form: form.elements['type'].value });
                    } else {
                        var errs = (res.d && res.d.errors) || { phone: ['Не удалось отправить. Напишите нам в WhatsApp.'] };
                        Object.keys(errs).forEach(function (k) { var s = form.querySelector('[data-err="' + k + '"]') || form.querySelector('[data-err=phone]'); s.textContent = errs[k][0]; });
                    }
                })
                .catch(function () { form.querySelector('[data-err=phone]').textContent = 'Нет связи. Попробуйте ещё раз или напишите в WhatsApp.'; })
                .finally(function () { btn.disabled = false; btn.textContent = txt; });
        });
    }
    function all() { document.querySelectorAll('form[data-gs-lead]').forEach(init); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', all); else all();
})();
</script>
@endonce
