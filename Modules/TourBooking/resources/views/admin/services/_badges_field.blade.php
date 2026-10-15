@php
    // Validasiya xətasından sonra köhnə input-u qaytar; yoxdursa saxlanmış dəyər
    $oldTexts = old('label_texts');
    if (is_array($oldTexts)) {
        $oldColors = (array) old('label_colors', []);
        $existingBadges = [];
        foreach ($oldTexts as $i => $t) {
            $existingBadges[] = ['text' => $t, 'color' => $oldColors[$i] ?? ''];
        }
    } else {
        $existingBadges = $existingBadges ?? [];
    }
    if (empty($existingBadges)) {
        $existingBadges = [['text' => '', 'color' => '']];
    }

    $badgeColorOptions = [
        ''        => 'По умолчанию (оранжевый)',
        '#f97316' => 'Оранжевый',
        '#560ce3' => 'Фиолетовый',
        '#16a34a' => 'Зелёный',
        '#dc2626' => 'Красный',
        '#0ea5e9' => 'Голубой',
        '#111827' => 'Чёрный',
    ];
@endphp

<div class="row mg-top-30">
    <div class="col-12">
        <label class="crancy__item-label" style="font-weight:600;">Этикетки на карточке (можно добавить несколько)</label>

        <div id="tg-badges-wrap">
            @foreach ($existingBadges as $b)
                <div class="tg-badge-row" style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">
                    <input class="crancy__item-input" type="text" name="label_texts[]" maxlength="50"
                        value="{{ $b['text'] ?? '' }}" placeholder="Текст (напр. Хит, Скидка, Новинка)" style="flex:1;">
                    <select class="crancy__item-input" name="label_colors[]" style="flex:0 0 210px;">
                        @foreach ($badgeColorOptions as $val => $label)
                            <option value="{{ $val }}" {{ ($b['color'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="tg-badge-remove" title="Удалить"
                        style="flex:0 0 auto; border:none; background:#fee2e2; color:#dc2626; width:40px; height:40px; border-radius:8px; font-size:20px; line-height:1; cursor:pointer;">&times;</button>
                </div>
            @endforeach
        </div>

        <button type="button" id="tg-badge-add" class="crancy-btn" style="margin-top:4px; padding:8px 18px;">+ Добавить этикетку</button>
        <small style="display:block; margin-top:8px; color:#8a8a8a;">
            Пусто = стандартная «Популярное» (если включён «Featured»).
        </small>
    </div>
</div>

<template id="tg-badge-row-tpl">
    <div class="tg-badge-row" style="display:flex; gap:10px; align-items:center; margin-bottom:10px;">
        <input class="crancy__item-input" type="text" name="label_texts[]" maxlength="50"
            placeholder="Текст (напр. Хит, Скидка, Новинка)" style="flex:1;">
        <select class="crancy__item-input" name="label_colors[]" style="flex:0 0 210px;">
            @foreach ($badgeColorOptions as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <button type="button" class="tg-badge-remove" title="Удалить"
            style="flex:0 0 auto; border:none; background:#fee2e2; color:#dc2626; width:40px; height:40px; border-radius:8px; font-size:20px; line-height:1; cursor:pointer;">&times;</button>
    </div>
</template>

<script>
    (function () {
        var wrap = document.getElementById('tg-badges-wrap');
        var addBtn = document.getElementById('tg-badge-add');
        var tpl = document.getElementById('tg-badge-row-tpl');
        if (!wrap || !addBtn || !tpl) return;

        addBtn.addEventListener('click', function () {
            wrap.appendChild(tpl.content.cloneNode(true));
        });

        wrap.addEventListener('click', function (e) {
            if (!e.target.classList.contains('tg-badge-remove')) return;
            var rows = wrap.querySelectorAll('.tg-badge-row');
            var row = e.target.closest('.tg-badge-row');
            if (rows.length > 1) {
                row.remove();
            } else {
                // Son sətir qalıbsa — silmə, sadəcə təmizlə
                row.querySelector('input').value = '';
                row.querySelector('select').selectedIndex = 0;
            }
        });
    })();
</script>
