@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Manage Rooms') }} - {{ $service->title }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ __('translate.Manage Rooms') }} - {{ $service->title }}</h1>
                <div>
                    <a href="{{ route('admin.tourbooking.services.edit', $service->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('translate.Back to Service') }}
                    </a>
                    <a href="{{ route('admin.tourbooking.services.rooms.create', $service->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('translate.Add New Room') }}
                    </a>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                @if ($rooms->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('translate.Image') }}</th>
                                                    <th>{{ __('translate.Room Name') }}</th>
                                                    <th>{{ __('translate.Price/Night') }}</th>
                                                    <th>{{ __('translate.Capacity') }}</th>
                                                    <th>{{ __('translate.Total Rooms') }}</th>
                                                    <th>{{ __('translate.Status') }}</th>
                                                    <th>{{ __('translate.Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sortable-rooms">
                                                @foreach ($rooms as $room)
                                                    <tr data-id="{{ $room->id }}">
                                                        <td>
                                                            <img src="{{ $room->thumbnail ? asset($room->thumbnail->file_path) : asset('frontend/assets/img/shape/placeholder.png') }}"
                                                                alt="{{ $room->name }}" class="img-thumbnail"
                                                                style="width: 80px; height: 60px; object-fit: cover;">
                                                        </td>
                                                        <td>
                                                            <strong>{{ $room->name }}</strong>
                                                            @if ($room->is_featured)
                                                                <span class="badge badge-warning">Featured</span>
                                                            @endif
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ $room->bed_count }} {{ $room->bed_type_display }}
                                                                @if ($room->room_size)
                                                                    | {{ $room->room_size }} m²
                                                                @endif
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @if ($room->discount_price)
                                                                <del class="text-muted">{{ currency($room->price_per_night) }}</del>
                                                                <br>
                                                                <strong class="text-success">{{ currency($room->discount_price) }}</strong>
                                                            @else
                                                                <strong>{{ currency($room->price_per_night) }}</strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <i class="fas fa-user"></i> {{ $room->max_adults }} Adults
                                                            <br>
                                                            <i class="fas fa-child"></i> {{ $room->max_children }} Children
                                                        </td>
                                                        <td>{{ $room->total_rooms }}</td>
                                                        <td>
                                                            @if ($room->status)
                                                                <span class="badge badge-success">{{ __('translate.Active') }}</span>
                                                            @else
                                                                <span class="badge badge-danger">{{ __('translate.Inactive') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.tourbooking.services.rooms.edit', [$service->id, $room->id]) }}"
                                                                class="btn btn-sm btn-primary" title="{{ __('translate.Edit') }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="deleteRoom({{ $room->id }})"
                                                                title="{{ __('translate.Delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                            <form id="delete-room-{{ $room->id }}"
                                                                action="{{ route('admin.tourbooking.services.rooms.destroy', [$service->id, $room->id]) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <img src="{{ asset('admin/assets/img/empty-state.svg') }}" alt="No rooms"
                                            style="max-width: 200px;">
                                        <h4 class="mt-4">{{ __('translate.No Rooms Found') }}</h4>
                                        <p class="text-muted">{{ __('translate.Start by adding your first room to this hotel.') }}</p>
                                        <a href="{{ route('admin.tourbooking.services.rooms.create', $service->id) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-plus"></i> {{ __('translate.Add First Room') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        function deleteRoom(roomId) {
            Swal.fire({
                title: '{{ __('translate.Are you sure?') }}',
                text: '{{ __('translate.This room and all its media will be deleted permanently.') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('translate.Yes, delete it!') }}',
                cancelButtonText: '{{ __('translate.Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-room-' + roomId).submit();
                }
            });
        }
    </script>
@endpush