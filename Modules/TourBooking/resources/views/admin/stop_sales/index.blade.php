@extends('admin.master_layout')
@section('title')
    <title>Наличие номеров — {{ $service->translation->title ?? $service->title }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Наличие номеров</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> {{ __('translate.Services') }} >> Наличие номеров</p>
@endsection

@push('style_section')
<style>
    .ss-card { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 24px; }
    .ss-card h5 { font-weight: 700; margin-bottom: 14px; }
    .ss-form .row > div { margin-bottom: 12px; }
    .ss-form label { font-weight: 600; font-size: 14px; margin-bottom: 4px; display: block; }
    .ss-rooms { max-height: 180px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; }
    .ss-rooms label { font-weight: 400; display: flex; gap: 8px; align-items: center; margin: 2px 0; }
    .ss-cal-wrap { overflow-x: auto; }
    .ss-cal { border-collapse: collapse; font-size: 12px; }
    .ss-cal th, .ss-cal td { border: 1px solid #e2e8f0; text-align: center; min-width: 28px; height: 30px; padding: 0 2px; }
    .ss-cal th.ss-room { text-align: left; min-width: 180px; max-width: 240px; padding: 0 8px; position: sticky; left: 0; background: #fff; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ss-cal td.is-stop { background: #fecaca; color: #991b1b; font-weight: 700; }
    .ss-cal td.is-left { background: #fef3c7; color: #92400e; font-weight: 700; }
    .ss-cal th.is-we { background: #f8fafc; }
    .ss-legend span { display: inline-flex; align-items: center; gap: 6px; margin-right: 16px; font-size: 13px; }
    .ss-legend i { display: inline-block; width: 16px; height: 16px; border-radius: 3px; }
    .ss-nav { display: flex; gap: 10px; align-items: center; margin-bottom: 12px; }
    .ss-nav strong { font-size: 16px; min-width: 140px; text-align: center; }
</style>
@endpush

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-12 mg-top-30">
                                    <div class="crancy-product-card">
                                        <div class="create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">Наличие номеров: {{ $service->translation->title ?? $service->title }}</h4>
                                            <div>
                                                <a href="{{ route('admin.tourbooking.services.edit', $service->id) }}" class="crancy-btn"><i class="fa fa-edit"></i> {{ __('translate.Edit Service') }}</a>
                                            </div>
                                        </div>

                                        @if (! $ready)
                                            <div class="alert alert-warning mg-top-25">Нужно обновить базу данных (миграция room_stop_sales).</div>
                                        @elseif ($rooms->isEmpty())
                                            <div class="alert alert-info mg-top-25">У этого санатория нет номеров.</div>
                                        @else
                                        <div class="ss-card mg-top-25">
                                            @php
                                                $prev = $start->copy()->subMonth()->format('Y-m');
                                                $next = $start->copy()->addMonth()->format('Y-m');
                                                $months = ['', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
                                            @endphp
                                            <div class="ss-nav">
                                                <a class="crancy-btn" href="?month={{ $prev }}">←</a>
                                                <strong>{{ $months[$start->month] }} {{ $start->year }}</strong>
                                                <a class="crancy-btn" href="?month={{ $next }}">→</a>
                                            </div>
                                            <div class="ss-legend mb-2">
                                                <span><i style="background:#fecaca"></i> Нет мест</span>
                                                <span><i style="background:#fef3c7"></i> Осталось N номеров</span>
                                                <span><i style="border:1px solid #e2e8f0"></i> Свободно</span>
                                            </div>
                                            <div class="ss-cal-wrap">
                                                <table class="ss-cal">
                                                    <thead>
                                                        <tr>
                                                            <th class="ss-room">Номер</th>
                                                            @for ($d = 1; $d <= $start->daysInMonth; $d++)
                                                                <th @class(['is-we' => $start->copy()->day($d)->isWeekend()])>{{ $d }}</th>
                                                            @endfor
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($rooms as $room)
                                                            <tr>
                                                                <th class="ss-room" title="{{ $room->name }}">{{ $room->name }}@if (! $room->status) <small class="text-muted">(выкл.)</small>@endif</th>
                                                                @for ($d = 1; $d <= $start->daysInMonth; $d++)
                                                                    @php $v = $grid[$room->id][$d] ?? null; @endphp
                                                                    <td @class(['is-stop' => $v === 'stop', 'is-left' => is_int($v)]) title="{{ $v === 'stop' ? 'Нет мест' : (is_int($v) ? 'Осталось ' . $v : 'Свободно') }}">{{ $v === 'stop' ? '✕' : (is_int($v) ? $v : '') }}</td>
                                                                @endfor
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-7">
                                                <div class="ss-card">
                                                    <h5>Закрыть продажи / указать остаток</h5>
                                                    <form class="ss-form" method="POST" action="{{ route('admin.tourbooking.services.stop-sales.store', $service) }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label>Номера</label>
                                                                <div class="ss-rooms">
                                                                    @foreach ($rooms as $room)
                                                                        <label><input type="checkbox" name="room_ids[]" value="{{ $room->id }}" @checked($selectedRoom === (int) $room->id || in_array($room->id, (array) old('room_ids', [])))> {{ $room->name }}</label>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>С (первая ночь)</label>
                                                                <input class="crancy__item-input" type="date" name="date_from" value="{{ old('date_from') }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>По (последняя ночь)</label>
                                                                <input class="crancy__item-input" type="date" name="date_to" value="{{ old('date_to') }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Что сделать</label>
                                                                <select class="crancy__item-input" name="type" id="ssType">
                                                                    <option value="stop" @selected(old('type') !== 'left')>Нет мест (stop-sale)</option>
                                                                    <option value="left" @selected(old('type') === 'left')>Осталось номеров</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6" id="ssLeftWrap">
                                                                <label>Сколько осталось</label>
                                                                <input class="crancy__item-input" type="number" min="1" max="999" name="rooms_left" value="{{ old('rooms_left', 1) }}">
                                                                <small class="text-muted">1 → на сайте «Остался 1 номер»</small>
                                                            </div>
                                                            <div class="col-12">
                                                                <label>Комментарий (не виден гостям)</label>
                                                                <input class="crancy__item-input" type="text" maxlength="255" name="note" value="{{ old('note') }}">
                                                            </div>
                                                        </div>
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                                                        @endif
                                                        <button type="submit" class="crancy-btn">Сохранить</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="ss-card">
                                                    <h5>Импорт CSV от санатория</h5>
                                                    <p class="text-muted" style="font-size:13px">Колонки: <code>номер;с;по</code> (и необязательно <code>осталось</code>). Номер — ID или точное название. Даты: 2026-12-01 или 01.12.2026. Без 4-й колонки — «Нет мест».</p>
                                                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.tourbooking.services.stop-sales.import', $service) }}">
                                                        @csrf
                                                        <input class="crancy__item-input mb-2" type="file" name="csv" accept=".csv,text/csv,text/plain" required>
                                                        <button type="submit" class="crancy-btn">Импортировать</button>
                                                    </form>
                                                    <p class="text-muted mt-2 mb-0" style="font-size:12px">ID номеров: @foreach ($rooms as $room){{ $room->name }} = {{ $room->id }}@if (! $loop->last), @endif @endforeach</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ss-card">
                                            <h5>Действующие периоды</h5>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead><tr><th>Номер</th><th>С</th><th>По</th><th>Статус</th><th>Комментарий</th><th></th></tr></thead>
                                                    <tbody>
                                                        @forelse ($periods as $p)
                                                            <tr>
                                                                <td>{{ optional($rooms->firstWhere('id', $p->service_room_id))->name }}</td>
                                                                <td>{{ $p->date_from->format('d.m.Y') }}</td>
                                                                <td>{{ $p->date_to->format('d.m.Y') }}</td>
                                                                <td>{!! $p->isStopSale() ? '<span class="badge bg-danger">Нет мест</span>' : '<span class="badge bg-warning text-dark">Осталось ' . (int) $p->rooms_left . '</span>' !!}</td>
                                                                <td>{{ $p->note }}</td>
                                                                <td>
                                                                    <form method="POST" action="{{ route('admin.tourbooking.services.stop-sales.destroy', [$service, $p]) }}" onsubmit="return confirm('Удалить период?')">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr><td colspan="6" class="text-center text-muted">Пока нет закрытых дат.</td></tr>
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
        </div>
    </section>
@endsection

@push('js_section')
<script>
    (function () {
        var t = document.getElementById('ssType'), w = document.getElementById('ssLeftWrap');
        if (!t || !w) return;
        var f = function () { w.style.display = t.value === 'left' ? '' : 'none'; };
        t.addEventListener('change', f); f();
    })();
</script>
@endpush
