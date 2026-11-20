{{-- Task 20: author, medical expert and linked sanatorium of a post --}}
@php
    $gsAuthors = \Modules\Blog\App\Models\BlogAuthor::where('status', 1)->orderByDesc('is_default')->orderBy('name')->get();
    $gsServices = \Modules\TourBooking\App\Models\Service::with('translation')->where('status', 1)->orderBy('id', 'desc')->get();
    $gsPost = $blog ?? null;
@endphp
<div class="col-md-4">
    <div class="crancy__item-form--group mg-top-form-20">
        <label class="crancy__item-label">Автор статьи</label>
        <select class="form-select crancy__item-input" name="author_id">
            <option value="">По умолчанию ({{ \Modules\Blog\App\Models\BlogAuthor::defaultAuthor()?->name ?? '—' }})</option>
            @foreach ($gsAuthors->where('is_medical', false)->merge($gsAuthors->where('is_medical', true)) as $a)
                <option value="{{ $a->id }}" {{ (string) old('author_id', $gsPost?->author_id) === (string) $a->id ? 'selected' : '' }}>{{ $a->name }}{{ $a->role ? ' — ' . $a->role : '' }}</option>
            @endforeach
        </select>
        <small><a href="{{ route('admin.blog-author.index') }}" target="_blank">Авторы и врачи</a></small>
    </div>
</div>
<div class="col-md-4">
    <div class="crancy__item-form--group mg-top-form-20">
        <label class="crancy__item-label">Проверено врачом (медицинский эксперт)</label>
        <select class="form-select crancy__item-input" name="expert_id">
            <option value="">— не указан —</option>
            @foreach ($gsAuthors->where('is_medical', true) as $a)
                <option value="{{ $a->id }}" {{ (string) old('expert_id', $gsPost?->expert_id) === (string) $a->id ? 'selected' : '' }}>{{ $a->name }}{{ $a->specialty ? ', ' . $a->specialty : '' }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-4">
    <div class="crancy__item-form--group mg-top-form-20">
        <label class="crancy__item-label">Статья о санатории (карточка с ценой в статье)</label>
        <select class="form-select crancy__item-input" name="service_id">
            <option value="">— нет (блок «Бесплатный подбор санатория») —</option>
            @foreach ($gsServices as $s)
                <option value="{{ $s->id }}" {{ (string) old('service_id', $gsPost?->service_id) === (string) $s->id ? 'selected' : '' }}>{{ $s->translation?->title ?? $s->title }}</option>
            @endforeach
        </select>
    </div>
</div>
