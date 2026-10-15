{{-- 
    DEBUG: Room şəkillərinin görünməsi üçün bu faylı istifadə edin.
    Bu faylı əsas _room_list.blade.php-nin əvvəlinə əlavə edin ki, 
    room data strukturunu görəsiniz.
    
    İstifadə:
    @include('tourbooking::front.rooms._debug_rooms', ['rooms' => $rooms])
--}}

@if(config('app.debug'))
<div style="background: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
    <h5 style="color: #856404; margin-bottom: 10px;">🔍 Room Debug Info (only visible in debug mode)</h5>
    
    @foreach($rooms->take(2) as $room)
    <div style="background: #fff; padding: 10px; margin-bottom: 10px; border-radius: 4px; font-size: 12px;">
        <strong>Room ID: {{ $room->id }}</strong><br>
        
        <strong>Name fields:</strong><br>
        - name: {{ $room->name ?? 'NULL' }}<br>
        - room_name: {{ $room->room_name ?? 'NULL' }}<br>
        
        <strong>Thumbnail fields:</strong><br>
        - thumbnail: {{ $room->thumbnail ?? 'NULL' }}<br>
        - image: {{ $room->image ?? 'NULL' }}<br>
        
        @if(isset($room->media))
        <strong>Media relationship:</strong><br>
        - Count: {{ $room->media->count() }}<br>
        @foreach($room->media->take(2) as $media)
            - Media #{{ $media->id }}: {{ $media->file_path ?? 'no path' }} ({{ $media->media_type ?? 'no type' }})<br>
        @endforeach
        @else
        <strong>Media relationship:</strong> NOT LOADED<br>
        @endif
        
        @if(isset($room->gallery))
        <strong>Gallery relationship:</strong><br>
        - Count: {{ $room->gallery->count() }}<br>
        @else
        <strong>Gallery relationship:</strong> NOT LOADED<br>
        @endif
        
        <strong>All attributes:</strong><br>
        <code style="font-size: 10px; word-break: break-all;">{{ json_encode($room->getAttributes()) }}</code>
    </div>
    @endforeach
    
    <p style="color: #856404; margin: 0; font-size: 11px;">
        ⚠️ Bu debug panel yalnız APP_DEBUG=true olduqda görünür. Production-da görünməyəcək.
    </p>
</div>
@endif