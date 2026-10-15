@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('translate.Name') }} <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $season->name ?? '') }}" required>
        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('translate.Start Date') }} <span class="text-danger">*</span></label>
        <input type="date" name="start_date" id="season_start_date" class="form-control"
               value="{{ old('start_date', isset($season) ? $season->start_date->format('Y-m-d') : '') }}" required>
        @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('translate.End Date') }} <span class="text-danger">*</span></label>
        <input type="date" name="end_date" id="season_end_date" class="form-control"
               value="{{ old('end_date', isset($season) ? $season->end_date->format('Y-m-d') : '') }}" required>
        @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('translate.Percentage') }} (%) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="percentage" class="form-control"
               value="{{ old('percentage', $season->percentage ?? 0) }}" required>
        <small class="text-muted">{{ __('translate.Positive = increase, negative = decrease.') }}</small>
        @error('percentage')<small class="text-danger d-block">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">{{ __('translate.Color') }}</label>
        <input type="color" name="color" class="form-control form-control-color"
               value="{{ old('color', $season->color ?? '#3788d8') }}">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="status" name="status" value="1" class="form-check-input"
                   @checked(old('status', $season->status ?? true))>
            <label for="status" class="form-check-label">{{ __('translate.Active') }}</label>
        </div>
    </div>

    <div class="col-12">
        <div id="overlap-warning" class="alert alert-warning d-none">
            <strong>{{ __('translate.Warning') }}:</strong>
            <span id="overlap-text"></span>
        </div>
    </div>
</div>

<div class="mt-3 d-flex gap-2">
    <button type="submit" class="crancy-btn">{{ __('translate.Save') }}</button>
    <a href="{{ route('admin.tourbooking.seasons.index') }}" class="btn btn-secondary">{{ __('translate.Cancel') }}</a>
</div>

<script>
(function () {
    var startEl = document.getElementById('season_start_date');
    var endEl = document.getElementById('season_end_date');
    var warn = document.getElementById('overlap-warning');
    var warnText = document.getElementById('overlap-text');
    if (!startEl || !endEl) return;
    var url = '{{ route('admin.tourbooking.seasons.check-overlap') }}';
    var token = '{{ csrf_token() }}';
    var currentId = {{ isset($season) && $season->exists ? $season->id : 'null' }};

    function check() {
        if (!startEl.value || !endEl.value) return;
        fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json'},
            body: JSON.stringify({start_date: startEl.value, end_date: endEl.value, id: currentId})
        }).then(r => r.json()).then(d => {
            if (!warn || !warnText) return;
            if (d.overlap) {
                warn.classList.remove('d-none');
                warnText.textContent = '"' + d.overlap.name + '" (' + d.overlap.from + ' — ' + d.overlap.to + ') {{ __('translate.season overlaps with the dates you selected.') }}';
            } else {
                warn.classList.add('d-none');
            }
        }).catch(() => {});
    }
    startEl.addEventListener('change', check);
    endEl.addEventListener('change', check);
})();
</script>
