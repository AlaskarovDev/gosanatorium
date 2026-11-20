{{-- Task 20: post header — author, medical reviewer, dates, reading time --}}
@php
    $gsAuthor = $blog->displayAuthor();
    $gsExpert = ($blog->expert && $blog->expert->status) ? $blog->expert : null;
    $gsPublished = $blog->created_at;
    $gsUpdated = ($blog->updated_at && $blog->created_at && $blog->updated_at->toDateString() > $blog->created_at->toDateString()) ? $blog->updated_at : null;
@endphp
<div class="gs-post-head">
    @if ($gsAuthor)
        <div class="gs-post-head__author">
            @if ($gsAuthor->photo)
                <img class="gs-post-head__photo" src="{{ asset($gsAuthor->photo) }}" alt="{{ $gsAuthor->name }}" width="48" height="48">
            @else
                <span class="gs-post-head__photo gs-post-head__initials" aria-hidden="true">{{ $gsAuthor->initials }}</span>
            @endif
            <span class="gs-post-head__who">
                @if ($gsAuthor->profile_url)
                    <a class="gs-post-head__name" href="{{ $gsAuthor->profile_url }}">{{ $gsAuthor->name }}</a>
                @else
                    <span class="gs-post-head__name">{{ $gsAuthor->name }}</span>
                @endif
                @if ($gsAuthor->role)
                    <span class="gs-post-head__role">{{ $gsAuthor->role }}</span>
                @endif
            </span>
        </div>
    @endif
    @if ($gsExpert)
        <div class="gs-post-head__expert">
            <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
            Проверено врачом:
            @if ($gsExpert->profile_url)
                <a href="{{ $gsExpert->profile_url }}">{{ $gsExpert->name }}</a>@else{{ $gsExpert->name }}@endif{{ $gsExpert->specialty ? ', ' . $gsExpert->specialty : '' }}
        </div>
    @endif
    <div class="gs-post-head__meta">
        @if ($gsPublished)
            <span>Опубликовано <time datetime="{{ $gsPublished->toDateString() }}">{{ $gsPublished->format('d.m.Y') }}</time></span>
        @endif
        @if ($gsUpdated)
            <span>Обновлено <time datetime="{{ $gsUpdated->toDateString() }}">{{ $gsUpdated->format('d.m.Y') }}</time></span>
        @endif
        <span>{{ $blog->reading_minutes }} мин чтения</span>
    </div>
</div>
