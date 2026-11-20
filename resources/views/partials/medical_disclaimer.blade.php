{{-- Task 27: medical disclaimer (text and link: Admin → Company facts). Small grey text, not a popup. --}}
@php
    $gsDiscRu = app()->getLocale() === 'ru';
    $gsDiscText = $gsDiscRu
        ? company_fact('medical_disclaimer')
        : 'The information on this website is for general guidance only and is not medical advice. Treatment results vary from person to person. Please consult your doctor before the trip.';
    $gsDiscLink = company_fact('medical_disclaimer_link');
    if ($gsDiscLink !== '' && !preg_match('#^https?://#i', $gsDiscLink)) {
        $gsDiscLink = url('/' . ltrim($gsDiscLink, '/'));
    }
@endphp
@if ($gsDiscText !== '')
    <p class="gs-med-disclaimer{{ isset($gsDiscClass) ? ' ' . $gsDiscClass : '' }}">
        <i class="fa-regular fa-circle-info" aria-hidden="true"></i>
        <span>{{ $gsDiscText }}@if ($gsDiscLink !== '') <a href="{{ $gsDiscLink }}">{{ $gsDiscRu ? 'Противопоказания' : 'Contraindications' }} →</a>@endif</span>
    </p>
@endif
