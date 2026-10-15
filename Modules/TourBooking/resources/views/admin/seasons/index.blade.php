@extends('admin.master_layout')

@section('title')
    <title>{{ __('translate.Seasons') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Seasons') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Seasons') }}</p>
@endsection

@push('style_section')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    <style>
        #seasonsIndexCard #crancy-table__main_wrapper { overflow-x: auto; }
        #seasonsIndexCard table { min-width: 800px; width: 100%; }
        #seasonsIndexCard td, #seasonsIndexCard th { vertical-align: middle; padding: 12px 14px; white-space: nowrap; }
        #seasonsIndexCard td.action-cell { display: flex; gap: 6px; align-items: center; justify-content: flex-end; }
        #seasonsIndexCard td.action-cell .btn { padding: 6px 10px; font-size: 13px; }

        #seasonsCalendarToggle {
            display: inline-flex; align-items: center; gap: 6px;
            border: 1px solid #dde3ec; background: #fff; color: #2c3a55;
            padding: 8px 14px; border-radius: 8px; font-size: 13px; cursor: pointer;
            transition: all .15s;
        }
        #seasonsCalendarToggle:hover { background: #f4f7fb; border-color: #b9c8e3; }
        #seasonsCalendarToggle.is-open { background: #1f4ad7; color: #fff; border-color: #1f4ad7; }

        #seasons-calendar-wrap {
            background: #fff;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #eef1f6;
            margin-top: 12px;
            box-shadow: 0 2px 8px rgba(20, 30, 60, .04);
            max-width: 720px;
        }
        #seasons-calendar { font-size: 12px; }
        #seasons-calendar .fc-toolbar-title { font-size: 16px !important; }
        #seasons-calendar .fc .fc-button { padding: 4px 8px; font-size: 12px; }
        #seasons-calendar .fc-daygrid-day-number { font-size: 12px; padding: 2px 4px; }
        #seasons-calendar .fc-col-header-cell-cushion { font-size: 11px; padding: 4px 0; }
        #seasons-calendar .fc-daygrid-day { min-height: 32px; }
        #seasons-calendar .fc .fc-multimonth-month { min-height: 140px; }
        #seasons-calendar .fc .fc-multimonth-title { font-size: 13px !important; padding: 6px 0 !important; }
        .fc-event { cursor: pointer; border-radius: 4px; font-size: 11px; padding: 1px 4px; }
    </style>
@endpush

@section('body-content')
    <section class="crancy-adashboard crancy-show" id="seasonsIndexCard">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-table crancy-table--v3 mg-top-30">
                                <div class="crancy-customer-filter">
                                    <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                        <h4 class="crancy-product-card__title">{{ __('translate.All Seasons') }}</h4>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <button type="button" id="seasonsCalendarToggle">
                                                <i class="fa fa-calendar"></i>
                                                <span class="btn-label-show">{{ __('translate.Show Calendar') }}</span>
                                                <span class="btn-label-hide" style="display:none">{{ __('translate.Hide Calendar') }}</span>
                                            </button>
                                            <a href="{{ route('admin.tourbooking.seasons.create') }}" class="crancy-btn">
                                                <i class="fa fa-plus"></i> {{ __('translate.Add Season') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div id="seasons-calendar-wrap" style="display:none">
                                    <div id="seasons-calendar"></div>
                                </div>

                                <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer">
                                    <table class="crancy-table__main crancy-table__main-v3 no-footer" id="dataTable">
                                        <thead class="crancy-table__head">
                                            <tr>
                                                <th>ID</th>
                                                <th>{{ __('translate.Name') }}</th>
                                                <th>{{ __('translate.Start Date') }}</th>
                                                <th>{{ __('translate.End Date') }}</th>
                                                <th>{{ __('translate.Percentage') }}</th>
                                                <th>{{ __('translate.Color') }}</th>
                                                <th>{{ __('translate.Status') }}</th>
                                                <th class="text-end">{{ __('translate.Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($seasons as $season)
                                                <tr>
                                                    <td>{{ $season->id }}</td>
                                                    <td><strong>{{ $season->name }}</strong></td>
                                                    <td>{{ $season->start_date->format('Y-m-d') }}</td>
                                                    <td>{{ $season->end_date->format('Y-m-d') }}</td>
                                                    <td>
                                                        <span class="badge {{ $season->percentage >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $season->percentage >= 0 ? '+' : '' }}{{ $season->percentage }}%
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span style="display:inline-block;width:18px;height:18px;border-radius:4px;background:{{ $season->color }};vertical-align:middle"></span>
                                                        <span class="ms-1">{{ $season->color }}</span>
                                                    </td>
                                                    <td>
                                                        @if($season->status)
                                                            <span class="badge bg-success">{{ __('translate.Active') }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ __('translate.Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="action-cell">
                                                        <a href="{{ route('admin.tourbooking.seasons.edit', $season) }}" class="btn btn-sm btn-primary">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.tourbooking.seasons.destroy', $season) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('translate.Are you sure?') }}')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="8" class="text-center text-muted">{{ __('translate.No seasons yet.') }}</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
(function () {
    var calendar = null;

    function buildCalendar() {
        var el = document.getElementById('seasons-calendar');
        if (!el || calendar || typeof FullCalendar === 'undefined') return;
        calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,multiMonthYear'
            },
            multiMonthMaxColumns: 2,
            height: 'auto',
            contentHeight: 380,
            events: '{{ route('admin.tourbooking.seasons.calendar') }}',
            eventClick: function (info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            }
        });
        calendar.render();
    }

    function init() {
        var btn = document.getElementById('seasonsCalendarToggle');
        var wrap = document.getElementById('seasons-calendar-wrap');
        if (!btn || !wrap) return;

        btn.addEventListener('click', function () {
            var willShow = wrap.style.display === 'none' || wrap.style.display === '';
            if (willShow) {
                wrap.style.display = 'block';
                btn.classList.add('is-open');
                btn.querySelector('.btn-label-show').style.display = 'none';
                btn.querySelector('.btn-label-hide').style.display = '';
                buildCalendar();
                if (calendar) setTimeout(function () { calendar.updateSize(); }, 50);
            } else {
                wrap.style.display = 'none';
                btn.classList.remove('is-open');
                btn.querySelector('.btn-label-show').style.display = '';
                btn.querySelector('.btn-label-hide').style.display = 'none';
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
