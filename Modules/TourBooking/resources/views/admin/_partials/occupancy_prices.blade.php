{{--
    Room price grid: rows = seasons (+ base price outside seasons), columns = 1/2/3/4 adults.
    Price is per room per night, before the room discount. Empty cell = «Цена по запросу» on the site.
    Vars: $idSuffix ('add' | 'edit'). Values are filled by JS from data-room-occupancy on the edit button.
--}}
@php
    $occReady = \Modules\TourBooking\App\Services\RoomPricing::tableReady();
    $occSeasons = \Modules\TourBooking\App\Models\Season::where('status', true)->orderBy('start_date')->get();
    $occRows = collect([['key' => 'base', 'name' => 'Базовая цена', 'range' => 'даты вне сезонов', 'color' => '#9ca3af']])
        ->concat($occSeasons->map(fn ($s) => [
            'key' => (string) $s->id,
            'name' => $s->name,
            'range' => $s->start_date?->format('d.m.Y') . ' — ' . $s->end_date?->format('d.m.Y'),
            'color' => $s->color ?: '#9ca3af',
        ]));
@endphp
<div class="card mt-3 occ-grid-card" id="occGrid-{{ $idSuffix }}">
    <div class="card-header bg-light">
        <strong>Цены по количеству взрослых</strong>
        <small class="text-muted ms-2">цена за номер за ночь, до скидки; пустая ячейка = «Цена по запросу» на сайте</small>
    </div>
    <div class="card-body">
        @unless($occReady)
            <div class="alert alert-warning mb-0">Таблица цен появится после обновления базы данных (php artisan migrate). Сейчас сайт считает цену за ночь как цену для 2 взрослых.</div>
        @else
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-2">
                    <thead>
                        <tr>
                            <th>Сезон</th>
                            @for($a = 1; $a <= 4; $a++)
                                <th style="width:120px">{{ $a }} {{ $a === 1 ? 'взрослый' : 'взрослых' }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($occRows as $row)
                            <tr>
                                <td>
                                    <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $row['color'] }};vertical-align:middle"></span>
                                    <strong class="ms-1">{{ $row['name'] }}</strong><br>
                                    <small class="text-muted">{{ $row['range'] }}</small>
                                </td>
                                @for($a = 1; $a <= 4; $a++)
                                    <td>
                                        <input type="number" step="0.01" min="0" inputmode="decimal"
                                               class="form-control form-control-sm occ-input"
                                               name="occupancy_prices[{{ $row['key'] }}][{{ $a }}]"
                                               data-occ-key="{{ $row['key'] }}" data-occ-adults="{{ $a }}"
                                               placeholder="—">
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <small class="text-muted d-block">
                Каждая ночь считается по цене своего сезона. Скидка номера (поле «Цена со скидкой») применяется к этим ценам.
                Если таблица пустая, используется «Цена за ночь» как цена для 2 взрослых.
            </small>
        @endunless
    </div>
</div>
