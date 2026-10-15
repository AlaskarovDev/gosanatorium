{{--
    Reusable seasonal-pricing block.
    Required vars: $entity (model with HasSeasonalPricing trait, may be null on create form).
--}}
@php
    $allSeasons = \Modules\TourBooking\App\Models\Season::where('status', true)
        ->orderBy('start_date')->get();

    $existing = collect();
    if (isset($entity) && $entity && method_exists($entity, 'seasonPrices')) {
        $existing = $entity->seasonPrices->keyBy('season_id');
    }
@endphp

<div class="card mt-3">
    <div class="card-header bg-light">
        <strong>{{ __('translate.Seasonal Pricing') }}</strong>
        <small class="text-muted ms-2">
            {{ __('translate.Choose how each season affects this price.') }}
        </small>
    </div>
    <div class="card-body">
        @if($allSeasons->isEmpty())
            <div class="alert alert-info mb-0">
                {{ __('translate.No seasons defined yet.') }}
                <a href="{{ route('admin.tourbooking.seasons.create') }}">{{ __('translate.Add a season') }}</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('translate.Season') }}</th>
                            <th>{{ __('translate.Date Range') }}</th>
                            <th style="width:200px">{{ __('translate.Mode') }}</th>
                            <th style="width:180px">{{ __('translate.Value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSeasons as $season)
                            @php
                                $rule = $existing->get($season->id);
                                $mode = old("season_prices.{$season->id}.mode", $rule->mode ?? 'default');
                                $cp = old("season_prices.{$season->id}.custom_percentage", $rule->custom_percentage ?? '');
                                $cpr = old("season_prices.{$season->id}.custom_price", $rule->custom_price ?? '');
                            @endphp
                            <tr>
                                <td>
                                    <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:{{ $season->color }};vertical-align:middle"></span>
                                    <strong class="ms-1">{{ $season->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ __('translate.Default') }}: {{ $season->percentage >= 0 ? '+' : '' }}{{ $season->percentage }}%</small>
                                </td>
                                <td>
                                    <small>{{ $season->start_date->format('Y-m-d') }} — {{ $season->end_date->format('Y-m-d') }}</small>
                                </td>
                                <td>
                                    <select name="season_prices[{{ $season->id }}][mode]" class="form-select form-select-sm season-mode">
                                        <option value="default" @selected($mode==='default')>{{ __('translate.Use season default') }}</option>
                                        <option value="percentage" @selected($mode==='percentage')>{{ __('translate.Custom percentage') }}</option>
                                        <option value="fixed" @selected($mode==='fixed')>{{ __('translate.Fixed price') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01"
                                           name="season_prices[{{ $season->id }}][custom_percentage]"
                                           class="form-control form-control-sm sp-percentage"
                                           placeholder="%" value="{{ $cp }}"
                                           data-mode-target="percentage">
                                    <input type="number" step="0.01"
                                           name="season_prices[{{ $season->id }}][custom_price]"
                                           class="form-control form-control-sm mt-1 sp-fixed"
                                           placeholder="{{ __('translate.Price') }}" value="{{ $cpr }}"
                                           data-mode-target="fixed">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    .season-mode-row .sp-percentage,
    .season-mode-row .sp-fixed { transition: opacity .15s; }
    .season-mode-row[data-mode="default"]    .sp-percentage,
    .season-mode-row[data-mode="default"]    .sp-fixed,
    .season-mode-row[data-mode="percentage"] .sp-fixed,
    .season-mode-row[data-mode="fixed"]      .sp-percentage { opacity: .35; background: #f5f5f5; }
</style>
<script>
(function () {
    function bindRow(sel) {
        if (sel.dataset.bound) return;
        sel.dataset.bound = '1';
        var row = sel.closest('tr');
        if (!row) return;
        row.classList.add('season-mode-row');
        row.setAttribute('data-mode', sel.value);
        sel.addEventListener('change', function () {
            row.setAttribute('data-mode', sel.value);
        });
    }
    function init() {
        document.querySelectorAll('.season-mode').forEach(bindRow);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
