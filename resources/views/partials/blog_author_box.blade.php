{{-- Task 20: author box under the post --}}
@php($gsA = $blog->displayAuthor())
@if ($gsA)
    <div class="gs-author-box mb-40">
        @if ($gsA->photo)
            <img class="gs-author-box__photo" src="{{ asset($gsA->photo) }}" alt="{{ $gsA->name }}" width="72" height="72" loading="lazy">
        @else
            <span class="gs-author-box__photo gs-post-head__initials" aria-hidden="true">{{ $gsA->initials }}</span>
        @endif
        <div>
            <div class="gs-author-box__label">Автор</div>
            <div class="gs-author-box__name">
                @if ($gsA->profile_url)<a href="{{ $gsA->profile_url }}">{{ $gsA->name }}</a>@else{{ $gsA->name }}@endif
            </div>
            @if ($gsA->role)<div class="gs-author-box__role">{{ $gsA->role }}</div>@endif
            @if ($gsA->bio)<p class="gs-author-box__bio">{{ $gsA->bio }}</p>@endif
        </div>
    </div>
@endif
