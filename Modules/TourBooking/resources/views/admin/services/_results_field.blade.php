@php
    $oldResults = old('results_text');
    $resultsText = is_string($oldResults) ? $oldResults : implode("\n", (array) ($existingResults ?? []));
@endphp
<div class="row mg-top-30">
    <div class="col-12">
        <label class="crancy__item-label" style="font-weight:600;">Наши гости после курса (3–4 пункта, каждый с новой строки)</label>
        <textarea class="crancy__item-input" name="results_text" rows="5" style="height:auto;"
            placeholder="Псориаз — улучшение заметно с 5–7-го дня курса&#10;Дети принимаются с 6 лет">{{ $resultsText }}</textarea>
        <small style="display:block; margin-top:8px; color:#8a8a8a;">Блок выводится на странице санатория под описанием. Пусто = блок не показывается.</small>
    </div>
</div>
