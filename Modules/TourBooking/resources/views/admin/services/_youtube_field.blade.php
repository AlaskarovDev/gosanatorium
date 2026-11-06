<div class="row mg-top-30">
    <div class="col-12">
        <label class="crancy__item-label" style="font-weight:600;">Видео санатория на YouTube (ссылка)</label>
        <input class="crancy__item-input" type="text" name="youtube_url" maxlength="255"
            value="{{ old('youtube_url', $existingYoutubeUrl ?? '') }}"
            placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX">
        @error('youtube_url')
            <span class="text-danger">Укажите корректную ссылку на YouTube</span>
        @enderror
        <small style="display:block; margin-top:8px; color:#8a8a8a;">Блок «Санаторий в видео — смотрите вживую» выводится на странице санатория после галереи. Пусто = блок не показывается.</small>
    </div>
</div>
