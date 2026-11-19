@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Date Discounts') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Date Discounts') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Date Discounts') }}</p>
@endsection

@push('style_section')
    <style>
        #dateDiscounts .dd-card { background: #fff; border: 1px solid #eef1f6; border-radius: 12px; padding: 16px; }
        #dateDiscounts .dd-list { max-height: 640px; overflow-y: auto; margin: 0; padding: 0; list-style: none; }
        #dateDiscounts .dd-list a { display: flex; justify-content: space-between; align-items: center; gap: 8px;
            padding: 9px 10px; border-radius: 8px; color: #2c3a55; text-decoration: none; font-size: 14px; }
        #dateDiscounts .dd-list a:hover { background: #f4f7fb; }
        #dateDiscounts .dd-list a.is-active { background: #1f4ad7; color: #fff; }
        #dateDiscounts .dd-list a.is-active .badge { background: #fff !important; color: #1f4ad7; }
        #dateDiscounts .dd-hint { color: #6b7790; font-size: 13px; }
        #dateDiscounts table { width: 100%; min-width: 620px; }
        #dateDiscounts td, #dateDiscounts th { vertical-align: middle; padding: 10px 12px; white-space: nowrap; }
        #dateDiscounts .dd-actions { display: flex; gap: 6px; justify-content: flex-end; }
        #dateDiscounts .dd-actions .btn { padding: 6px 10px; font-size: 13px; }
        #dateDiscounts .dd-table-wrap { overflow-x: auto; }
    </style>
@endpush

@section('body-content')
    <section class="crancy-adashboard crancy-show" id="dateDiscounts">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner mg-top-30">
                            @if(! $ready)
                                <div class="alert alert-warning">{{ __('translate.Date discounts are not enabled yet: the database update must be applied.') }}</div>
                            @endif

                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <div class="dd-card">
                                        <h5 class="mb-2">{{ __('translate.Sanatoriums') }}</h5>
                                        <input type="search" id="ddSearch" class="form-control mb-2" placeholder="{{ __('translate.Search') }}...">
                                        <ul class="dd-list" id="ddList">
                                            @foreach($services as $s)
                                                @php $sTitle = $s->translation->title ?? $s->title; @endphp
                                                <li data-title="{{ mb_strtolower($sTitle) }}">
                                                    <a href="{{ route('admin.tourbooking.date-discounts.index', ['service_id' => $s->id]) }}"
                                                       class="{{ $service && $service->id === $s->id ? 'is-active' : '' }}">
                                                        <span>{{ $sTitle }}</span>
                                                        @if($counts->get($s->id))
                                                            <span class="badge bg-success" title="{{ __('translate.Active and upcoming discounts') }}">{{ $counts->get($s->id) }}</span>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    @if(! $service)
                                        <div class="dd-card">
                                            <p class="m-0 dd-hint">{{ __('translate.Choose a sanatorium on the left to add a discount for chosen dates. Inside those dates the room prices of this sanatorium drop by the percent automatically; after the end date the normal prices return.') }}</p>
                                        </div>
                                    @else
                                        @php $title = $service->translation->title ?? $service->title; @endphp
                                        <div class="dd-card mb-3">
                                            <h5 class="mb-1">{{ $title }}</h5>
                                            <p class="dd-hint mb-3">{{ __('translate.Inside these dates every room price of this sanatorium drops by the percent automatically. Other sanatoriums are not affected.') }}</p>

                                            <form method="POST"
                                                  action="{{ $editing ? route('admin.tourbooking.date-discounts.update', $editing) : route('admin.tourbooking.date-discounts.store', $service) }}">
                                                @csrf
                                                @if($editing) @method('PUT') @endif
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-sm-6 col-md-3">
                                                        <label class="form-label">{{ __('translate.Start Date') }} <span class="text-danger">*</span></label>
                                                        <input type="date" name="start_date" class="form-control" required
                                                               value="{{ old('start_date', $editing?->start_date?->format('Y-m-d')) }}">
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <label class="form-label">{{ __('translate.End Date') }} <span class="text-danger">*</span></label>
                                                        <input type="date" name="end_date" class="form-control" required
                                                               value="{{ old('end_date', $editing?->end_date?->format('Y-m-d')) }}">
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <label class="form-label">{{ __('translate.Discount') }} % <span class="text-danger">*</span></label>
                                                        <input type="number" name="percentage" class="form-control" required min="0.01" max="100" step="0.01"
                                                               value="{{ old('percentage', $editing ? rtrim(rtrim((string) $editing->percentage, '0'), '.') : '') }}" placeholder="10">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label class="form-label">{{ __('translate.Note') }}</label>
                                                        <input type="text" name="note" class="form-control" maxlength="255"
                                                               value="{{ old('note', $editing?->note) }}">
                                                    </div>
                                                    <div class="col-12 d-flex flex-wrap align-items-center gap-3">
                                                        <label class="d-inline-flex align-items-center gap-2 m-0">
                                                            <input type="checkbox" name="status" value="1"
                                                                   {{ old('status', $editing ? (int) $editing->status : 1) ? 'checked' : '' }}>
                                                            {{ __('translate.Active') }}
                                                        </label>
                                                        <button type="submit" class="crancy-btn" @disabled(! $ready)>
                                                            <i class="fa {{ $editing ? 'fa-save' : 'fa-plus' }}"></i>
                                                            {{ $editing ? __('translate.Save') : __('translate.Add Discount') }}
                                                        </button>
                                                        @if($editing)
                                                            <a href="{{ route('admin.tourbooking.date-discounts.index', ['service_id' => $service->id]) }}" class="btn btn-light">{{ __('translate.Cancel') }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($errors->any())
                                                    <div class="text-danger mt-2">
                                                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                                                    </div>
                                                @endif
                                            </form>
                                        </div>

                                        <div class="dd-card">
                                            <div class="dd-table-wrap">
                                                <table class="table m-0">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __('translate.Dates') }}</th>
                                                            <th>{{ __('translate.Discount') }}</th>
                                                            <th>{{ __('translate.Status') }}</th>
                                                            <th>{{ __('translate.Note') }}</th>
                                                            <th class="text-end">{{ __('translate.Actions') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($discounts as $d)
                                                            @php $state = $d->state(); @endphp
                                                            <tr>
                                                                <td>{{ $d->start_date->format('d.m.Y') }} — {{ $d->end_date->format('d.m.Y') }}</td>
                                                                <td><span class="badge bg-danger">−{{ rtrim(rtrim((string) $d->percentage, '0'), '.') }}%</span></td>
                                                                <td>
                                                                    @if($state === 'active')
                                                                        <span class="badge bg-success">{{ __('translate.Running now') }}</span>
                                                                    @elseif($state === 'upcoming')
                                                                        <span class="badge bg-primary">{{ __('translate.Upcoming') }}</span>
                                                                    @elseif($state === 'finished')
                                                                        <span class="badge bg-secondary">{{ __('translate.Finished') }}</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">{{ __('translate.Inactive') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ \Illuminate\Support\Str::limit((string) $d->note, 40) }}</td>
                                                                <td>
                                                                    <div class="dd-actions">
                                                                        <a href="{{ route('admin.tourbooking.date-discounts.index', ['service_id' => $service->id, 'edit' => $d->id]) }}"
                                                                           class="btn btn-sm btn-primary" title="{{ __('translate.Edit') }}"><i class="fa fa-edit"></i></a>
                                                                        <form action="{{ route('admin.tourbooking.date-discounts.destroy', $d) }}" method="POST"
                                                                              onsubmit="return confirm('{{ __('translate.Are you sure?') }}')">
                                                                            @csrf @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('translate.Delete') }}"><i class="fa fa-trash"></i></button>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="5" class="text-center text-muted">{{ __('translate.No discounts yet.') }}</td></tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js_section')
<script>
(function () {
    var input = document.getElementById('ddSearch');
    if (!input) return;
    input.addEventListener('input', function () {
        var q = input.value.trim().toLowerCase();
        document.querySelectorAll('#ddList li').forEach(function (li) {
            li.style.display = !q || li.getAttribute('data-title').indexOf(q) !== -1 ? '' : 'none';
        });
    });
    var active = document.querySelector('#ddList a.is-active');
    if (active) active.scrollIntoView({ block: 'nearest' });
})();
</script>
@endpush
