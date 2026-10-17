@php
    // Table is created by a migration; skip the section until it has been run
    $homeDiseases = \Illuminate\Support\Facades\Schema::hasTable('diseases')
        ? \Modules\TourBooking\App\Models\Disease::active()->ordered()->take(8)->get()
        : collect();
@endphp

@if ($homeDiseases->count() > 0)
    <div class="tg-grey-bg pt-100 pb-100">
        <div class="container">
            <div class="tg-location-section-title text-center mb-40">
                <h2 class="mb-15">{{ \Modules\TourBooking\App\Models\Disease::t('Find a Sanatorium for Your Condition') }}</h2>
            </div>

            @include('tourbooking::front.diseases._cards', ['diseases' => $homeDiseases])

            <div class="gs-disease-more">
                <a href="{{ route('diseases.index') }}" class="tg-btn tg-btn-switch-animation">
                    <span class="btn-text">{{ \Modules\TourBooking\App\Models\Disease::t('View All') }}</span>
                </a>
            </div>
        </div>
    </div>
@endif
