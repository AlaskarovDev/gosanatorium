@php
    $disease = $disease ?? null;
    $assigned = old('sanatoriums', $assigned ?? []);
@endphp

<div class="row mg-top-30">
    @foreach (['ru' => 'RU', 'az' => 'AZ', 'en' => 'EN'] as $code => $label)
        <div class="col-12 col-md-4">
            <div class="crancy__item-form--group mg-top-form-20">
                <label class="crancy__item-label">{{ __('translate.Name') }} ({{ $label }}) @if ($code === 'ru')<span class="text-danger">*</span>@endif</label>
                <input class="crancy__item-input" type="text" name="name_{{ $code }}" value="{{ old('name_' . $code, $disease?->{'name_' . $code}) }}" maxlength="255" @if ($code === 'ru') required @endif>
                @error('name_' . $code) <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    @endforeach

    @foreach (['ru' => 'RU', 'az' => 'AZ', 'en' => 'EN'] as $code => $label)
        <div class="col-12 col-md-4">
            <div class="crancy__item-form--group mg-top-form-20">
                <label class="crancy__item-label">{{ __('translate.Description') }} ({{ $label }})</label>
                <textarea class="crancy__item-input crancy__item-textarea" name="description_{{ $code }}" rows="4" maxlength="5000">{{ old('description_' . $code, $disease?->{'description_' . $code}) }}</textarea>
                @error('description_' . $code) <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
    @endforeach

    <div class="col-12 col-md-4">
        <div class="crancy__item-form--group mg-top-form-20">
            <label class="crancy__item-label">{{ __('translate.Slug') }} <span class="text-muted">({{ __('translate.Generated automatically if empty') }})</span></label>
            <input class="crancy__item-input" type="text" name="slug" value="{{ old('slug', $disease?->slug) }}" maxlength="255">
            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="crancy__item-form--group mg-top-form-20">
            <label class="crancy__item-label">{{ __('translate.Sort Order') }}</label>
            <input class="crancy__item-input" type="number" min="0" name="sort_order" value="{{ old('sort_order', $disease?->sort_order ?? 0) }}">
            @error('sort_order') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="crancy__item-form--group mg-top-form-20">
            <label class="crancy__item-label">{{ __('translate.Status') }}</label>
            <div class="crancy-ptabs__notify-switch">
                <label class="crancy__item-switch">
                    <input name="is_active" type="checkbox" value="1" {{ old('is_active', $disease?->is_active ?? true) ? 'checked' : '' }}>
                    <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="crancy__item-form--group mg-top-form-20">
            <label class="crancy__item-label">{{ __('translate.Image') }}</label>
            @if ($disease?->image)
                <div class="mb-2"><img src="{{ asset($disease->image) }}" alt="{{ $disease->name_ru }}" width="120"></div>
            @endif
            <input class="crancy__item-input" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="crancy__item-form--group mg-top-form-20">
            <label class="crancy__item-label">{{ __('translate.Sanatoriums') }}</label>
            <div style="max-height: 320px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px;">
                @forelse ($services as $service)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sanatoriums[]" value="{{ $service->id }}" id="sanatorium_{{ $service->id }}"
                            {{ in_array($service->id, array_map('intval', (array) $assigned), true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sanatorium_{{ $service->id }}">{{ $service->title }}</label>
                    </div>
                @empty
                    <span class="text-muted">{{ __('translate.No Data Found') }}</span>
                @endforelse
            </div>
            @error('sanatoriums') <span class="text-danger">{{ $message }}</span> @enderror
            @error('sanatoriums.*') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
