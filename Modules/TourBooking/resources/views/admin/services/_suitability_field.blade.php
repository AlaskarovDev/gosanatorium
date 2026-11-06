@php
    $oldSuit = old('suitable_for_text');
    $oldNot = old('not_suitable_for_text');
    $suitText = is_string($oldSuit) ? $oldSuit : implode("\n", (array) ($existingSuitable ?? []));
    $notText = is_string($oldNot) ? $oldNot : implode("\n", (array) ($existingNotSuitable ?? []));
@endphp
<div class="row mg-top-30">
    <div class="col-md-6">
        <label class="crancy__item-label" style="font-weight:600;">Подходит вам если... (каждый пункт с новой строки)</label>
        <textarea class="crancy__item-input" name="suitable_for_text" rows="6" style="height:auto;">{{ $suitText }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="crancy__item-label" style="font-weight:600;">Возможно, не подходит если... (каждый пункт с новой строки)</label>
        <textarea class="crancy__item-input" name="not_suitable_for_text" rows="6" style="height:auto;">{{ $notText }}</textarea>
    </div>
    <div class="col-12">
        <small style="display:block; margin-top:8px; color:#8a8a8a;">Блок выводится на странице санатория над ценами. Обе колонки пустые = блок не показывается.</small>
    </div>
</div>
