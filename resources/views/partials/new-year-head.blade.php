{{-- NEW YEAR MODE: printed only while the mode is active (Admin → Website Setup → New Year mode) --}}
@if (\App\Services\NewYearMode::isActive())
    <script>document.documentElement.classList.add('ny-on')</script>
    <link rel="stylesheet" href="{{ \App\Services\NewYearMode::asset('newyear/ny.css') }}">
    <script src="{{ \App\Services\NewYearMode::asset('newyear/ny.js') }}" defer @if (\App\Services\NewYearMode::mode() === 'auto') data-end="{{ \App\Services\NewYearMode::end()->toDateString() }}" @endif></script>
@endif
