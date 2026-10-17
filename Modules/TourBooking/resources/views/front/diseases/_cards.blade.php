<div class="gs-disease-grid">
    @foreach ($diseases as $disease)
        <a class="gs-disease-card" href="{{ route('diseases.show', ['slug' => $disease->slug]) }}">
            <img src="{{ $disease->image_url }}" alt="{{ $disease->name }}" loading="lazy" onerror="this.style.display='none'">
            <h3 class="gs-disease-card__title">{{ $disease->name }}</h3>
        </a>
    @endforeach
</div>
