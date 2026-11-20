@extends('admin.master_layout')

@section('title')
    <title>Цены по длительности и скидки</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Цены по длительности и скидки</h3>
    <p class="crancy-header__text">{{ __('translate.Tour Booking') }} >> Цены по длительности</p>
@endsection

@push('style_section')
    <style>
        #stayPricing .sp-card { background: #fff; border: 1px solid #eef1f6; border-radius: 12px; padding: 16px; }
        #stayPricing .sp-list { max-height: 640px; overflow-y: auto; margin: 0; padding: 0; list-style: none; }
        #stayPricing .sp-list a { display: flex; justify-content: space-between; align-items: center; gap: 8px; padding: 9px 10px; border-radius: 8px; color: #2c3a55; text-decoration: none; font-size: 14px; }
        #stayPricing .sp-list a:hover { background: #f4f7fb; }
        #stayPricing .sp-list a.is-active { background: #1f4ad7; color: #fff; }
        #stayPricing .sp-hint { color: #6b7790; font-size: 13px; }
        #stayPricing table { width: 100%; }
        #stayPricing td, #stayPricing th { vertical-align: middle; padding: 8px 10px; white-space: nowrap; }
        #stayPricing .sp-wrap { overflow-x: auto; }
        #stayPricing .sp-gap { color: #b45309; font-size: 13px; }
        #stayPricing input.form-control-sm { min-width: 80px; }
    </style>
@endpush

@section('body-content')
<section class="crancy-adashboard crancy-show" id="stayPricing">
  <div class="container container__bscreen"><div class="row"><div class="col-12"><div class="crancy-body"><div class="crancy-dsinner mg-top-30">
    @if(! $ready)
        <div class="alert alert-warning">Раздел заработает после обновления базы данных (migrate).</div>
    @endif
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif

    <div class="row g-3">
      <div class="col-lg-3">
        <div class="sp-card">
          <h5 class="mb-2">{{ __('translate.Sanatoriums') }}</h5>
          <input type="search" id="spSearch" class="form-control mb-2" placeholder="{{ __('translate.Search') }}...">
          <ul class="sp-list" id="spList">
            @foreach($services as $s)
              @php $sTitle = $s->translation->title ?? $s->title; @endphp
              <li data-title="{{ mb_strtolower($sTitle) }}">
                <a href="{{ route('admin.tourbooking.stay-pricing.index', ['service_id' => $s->id]) }}" class="{{ $service && $service->id === $s->id ? 'is-active' : '' }}">
                  <span>{{ $sTitle }}</span>
                  @if($counts->get($s->id))<span class="badge bg-success" title="Есть цены по длительности или скидки">✓</span>@endif
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="col-lg-9">
        @if(! $service)
          <div class="sp-card"><p class="m-0 sp-hint">Выберите санаторий слева. Здесь задаются цены, которые зависят от количества ночей (например, 3–5 ночей — 300, 7–10 — 250, от 11 — 220 за номер в ночь), и скидки за длительность и раннее бронирование. Отмеченные ✓ санатории уже имеют такие цены или скидки.</p></div>
        @else
          @php
            $title = $service->translation->title ?? $service->title;
            $seasonName = fn ($id) => $id === null ? 'Базовая цена (вне сезонов)' : ($seasons->firstWhere('id', $id)?->name ?? ('Сезон #' . $id));
          @endphp

          {{-- 1. Length-of-stay prices --}}
          <div class="sp-card mb-3">
            <h5 class="mb-1">{{ $title }} — цены по количеству ночей</h5>
            <p class="sp-hint mb-3">Цена за номер за ночь. Диапазон выбирается по общему количеству ночей брони, а каждая ночь считается по своему сезону.
              Если для номера и сезона есть диапазоны, но ни один не подходит (например, 6 ночей при 3–5 и 7–10), сайт пишет «Для 6 ночей цену уточнит менеджер» и предлагает WhatsApp.
              Номера без диапазонов считаются по обычной таблице цен.</p>

            <form method="POST" action="{{ route('admin.tourbooking.stay-pricing.tiers.store', $service) }}">
              @csrf
              <div class="row g-2 align-items-end">
                <div class="col-md-4">
                  <label class="form-label">Номер</label>
                  <select name="room_id" class="form-select" required>
                    @foreach($rooms as $r)<option value="{{ $r->id }}" @selected(old('room_id') == $r->id)>{{ $r->name ?? $r->title ?? ('#' . $r->id) }}</option>@endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Сезон</label>
                  <select name="season_id" class="form-select">
                    <option value="">Базовая цена (вне сезонов)</option>
                    @foreach($seasons as $se)<option value="{{ $se->id }}" @selected(old('season_id') == $se->id)>{{ $se->name }} ({{ $se->start_date?->format('d.m.Y') }} — {{ $se->end_date?->format('d.m.Y') }})</option>@endforeach
                  </select>
                </div>
                <div class="col-6 col-md-2"><label class="form-label">Ночей от</label><input type="number" name="min_nights" min="1" class="form-control" required value="{{ old('min_nights') }}" placeholder="7"></div>
                <div class="col-6 col-md-2"><label class="form-label">до</label><input type="number" name="max_nights" min="1" class="form-control" value="{{ old('max_nights') }}" placeholder="пусто = и больше"></div>
                @foreach([1 => '1 взрослый', 2 => '2 взрослых', 3 => '3 взрослых', 4 => '4 взрослых', 0 => '1 ребёнок'] as $a => $lbl)
                  <div class="col-6 col-md"><label class="form-label">{{ $lbl }}</label><input type="number" step="0.01" min="0" name="price[{{ $a }}]" class="form-control" value="{{ old('price.' . $a) }}" placeholder="—"></div>
                @endforeach
                <div class="col-12"><button class="crancy-btn" @disabled(! $ready)><i class="fa fa-plus"></i> Добавить диапазон</button></div>
              </div>
            </form>
          </div>

          <div class="sp-card mb-3">
            @forelse($rooms as $r)
              @php
                $groups = $r->stayTiers->groupBy(fn ($t) => ($t->season_id ?? 'base') . '|' . $t->min_nights . '|' . ($t->max_nights ?? ''));
                $bySeason = $r->stayTiers->groupBy(fn ($t) => $t->season_id ?? 'base');
              @endphp
              @continue($groups->isEmpty())
              <h6 class="mt-2">{{ $r->name ?? $r->title ?? ('#' . $r->id) }}</h6>
              <div class="sp-wrap">
                <table class="table table-sm">
                  <thead><tr><th>Сезон</th><th>Ночей</th><th>1 взр.</th><th>2 взр.</th><th>3 взр.</th><th>4 взр.</th><th>Ребёнок</th><th></th></tr></thead>
                  <tbody>
                  @foreach($groups->sortBy(fn ($g) => [$g->first()->season_id ?? 0, $g->first()->min_nights]) as $g)
                    @php $f = $g->first(); $byA = $g->keyBy('adults'); @endphp
                    <tr>
                      <td>{{ $seasonName($f->season_id) }}</td>
                      <td><strong>{{ $f->rangeLabel() }}</strong></td>
                      @foreach([1, 2, 3, 4, 0] as $a)<td>{{ $byA->has($a) ? number_format((float) $byA[$a]->price, 2, '.', ' ') : '—' }}</td>@endforeach
                      <td class="text-end">
                        <form method="POST" action="{{ route('admin.tourbooking.stay-pricing.tiers.destroy', $service) }}" onsubmit="return confirm('{{ __('translate.Are you sure?') }}')">
                          @csrf @method('DELETE')
                          <input type="hidden" name="room_id" value="{{ $r->id }}"><input type="hidden" name="season_id" value="{{ $f->season_id }}">
                          <input type="hidden" name="min_nights" value="{{ $f->min_nights }}"><input type="hidden" name="max_nights" value="{{ $f->max_nights }}">
                          <button class="btn btn-sm btn-danger" title="{{ __('translate.Delete') }}"><i class="fa fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              @foreach($bySeason as $sKey => $list)
                @php
                  // Gaps between ranges (shown as a warning, the site sends these to the manager)
                  $ranges = $list->unique(fn ($t) => $t->min_nights . '-' . $t->max_nights)->sortBy('min_nights')->values();
                  $gaps = []; $next = 1;
                  foreach ($ranges as $t) { if ($t->min_nights > $next) { $gaps[] = $next === $t->min_nights - 1 ? (string) $next : $next . '–' . ($t->min_nights - 1); } $next = $t->max_nights === null ? PHP_INT_MAX : max($next, $t->max_nights + 1); }
                  $openEnd = $next !== PHP_INT_MAX;
                @endphp
                @if($gaps || $openEnd)
                  <div class="sp-gap mb-2">⚠ {{ $seasonName($sKey === 'base' ? null : (int) $sKey) }}: без цены — {{ implode(', ', array_merge($gaps, $openEnd ? [$next . '+'] : [])) }} ночей (цену уточнит менеджер).</div>
                @endif
              @endforeach
            @empty
              <p class="sp-hint m-0">У санатория нет номеров.</p>
            @endforelse
            @if($rooms->every(fn ($r) => $r->stayTiers->isEmpty()))
              <p class="sp-hint m-0">Цен по количеству ночей пока нет — номера считаются по обычной таблице цен.</p>
            @endif
          </div>

          {{-- 2. Stay discount rules --}}
          <div class="sp-card">
            <h5 class="mb-1">Скидки за длительность и раннее бронирование</h5>
            <p class="sp-hint mb-3">Скидка применяется к сумме проживания. Все подходящие скидки складываются: например, 5% за 6–12 ночей + 5% за бронирование за 30+ дней = 10%.</p>
            <form method="POST" action="{{ route('admin.tourbooking.stay-pricing.rules.store', $service) }}">
              @csrf
              <div class="row g-2 align-items-end">
                <div class="col-6 col-md-2"><label class="form-label">Ночей от</label><input type="number" name="min_nights" min="1" class="form-control" value="{{ old('min_nights') }}"></div>
                <div class="col-6 col-md-2"><label class="form-label">до</label><input type="number" name="max_nights" min="1" class="form-control" value="{{ old('max_nights') }}" placeholder="пусто = и больше"></div>
                <div class="col-6 col-md-3"><label class="form-label">Бронь за N+ дней до заезда</label><input type="number" name="min_days_before" min="1" class="form-control" value="{{ old('min_days_before') }}" placeholder="30"></div>
                <div class="col-6 col-md-2"><label class="form-label">Скидка %</label><input type="number" name="percentage" step="0.01" min="0.01" max="100" class="form-control" required value="{{ old('percentage') }}" placeholder="5"></div>
                <div class="col-md-3"><label class="form-label">{{ __('translate.Note') }}</label><input type="text" name="note" maxlength="255" class="form-control" value="{{ old('note') }}"></div>
                <div class="col-12"><button class="crancy-btn" @disabled(! $ready)><i class="fa fa-plus"></i> Добавить скидку</button></div>
              </div>
            </form>
            <div class="sp-wrap mt-3">
              <table class="table table-sm m-0">
                <thead><tr><th>Условие</th><th>Скидка</th><th>{{ __('translate.Status') }}</th><th>{{ __('translate.Note') }}</th><th></th></tr></thead>
                <tbody>
                @forelse($rules as $rule)
                  <tr>
                    <td>{{ $rule->label() }}</td>
                    <td><span class="badge bg-danger">−{{ rtrim(rtrim((string) $rule->percentage, '0'), '.') }}%</span></td>
                    <td>
                      <form method="POST" action="{{ route('admin.tourbooking.stay-pricing.rules.toggle', $rule) }}">@csrf @method('PATCH')
                        <button class="btn btn-sm {{ $rule->status ? 'btn-success' : 'btn-secondary' }}">{{ $rule->status ? __('translate.Active') : __('translate.Inactive') }}</button>
                      </form>
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit((string) $rule->note, 40) }}</td>
                    <td class="text-end">
                      <form method="POST" action="{{ route('admin.tourbooking.stay-pricing.rules.destroy', $rule) }}" onsubmit="return confirm('{{ __('translate.Are you sure?') }}')">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted">Скидок пока нет.</td></tr>
                @endforelse
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div></div></div></div></div>
</section>
@endsection

@push('js_section')
<script>
(function () {
    var input = document.getElementById('spSearch');
    if (!input) return;
    input.addEventListener('input', function () {
        var q = input.value.trim().toLowerCase();
        document.querySelectorAll('#spList li').forEach(function (li) { li.style.display = !q || li.getAttribute('data-title').indexOf(q) !== -1 ? '' : 'none'; });
    });
})();
</script>
@endpush
