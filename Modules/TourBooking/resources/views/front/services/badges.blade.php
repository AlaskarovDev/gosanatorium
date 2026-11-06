@php
    $__sbList = array_slice(gscardBadges($service), 0, (int) ($limit ?? 4));
@endphp
@if (count($__sbList))
    <div class="gs-sbadges {{ $class ?? '' }}">
        @foreach ($__sbList as $__sb)
            <span class="gs-sbadge">{{ $__sb }}</span>
        @endforeach
    </div>
@endif
