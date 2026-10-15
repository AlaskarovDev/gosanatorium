{{--
    Reusable per-room extras editor.
    Vars:
      $extras       — collection of RoomExtra (may be empty)
      $namePrefix   — input name prefix, e.g. "extras" (so inputs become extras[0][name] etc.)
      $idSuffix     — unique suffix to keep DOM ids unique between Add and Edit modals
--}}
@php
    $extras   = $extras   ?? collect();
    $namePrefix = $namePrefix ?? 'extras';
    $idSuffix = $idSuffix ?? 'add';
@endphp

<div class="room-extras-block" data-prefix="{{ $namePrefix }}" data-suffix="{{ $idSuffix }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>{{ __('translate.Room Extras') }}
            <small class="text-muted ms-1">({{ __('translate.Extra adult / child / optional add-ons') }})</small>
        </strong>
        <button type="button" class="btn btn-sm btn-outline-primary add-extra-row" data-suffix="{{ $idSuffix }}">
            <i class="fa fa-plus"></i> {{ __('translate.Add Extra') }}
        </button>
    </div>

    <div class="extras-list" id="extras-list-{{ $idSuffix }}">
        @forelse($extras as $i => $extra)
            <div class="row g-2 align-items-end mb-2 extra-row">
                <div class="col-md-5">
                    <label class="form-label small mb-1">{{ __('translate.Name') }}</label>
                    <input type="text" class="form-control form-control-sm" name="{{ $namePrefix }}[{{ $i }}][name]"
                           value="{{ $extra->name }}" placeholder="{{ __('translate.Extra adult') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">{{ __('translate.Type') }}</label>
                    <select name="{{ $namePrefix }}[{{ $i }}][type]" class="form-select form-select-sm">
                        <option value="adult"    @selected($extra->type === 'adult')>{{ __('translate.Per extra adult') }}</option>
                        <option value="child"    @selected($extra->type === 'child')>{{ __('translate.Per extra child') }}</option>
                        <option value="optional" @selected($extra->type === 'optional')>{{ __('translate.Optional add-on') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">{{ __('translate.Price') }}</label>
                    <input type="number" step="0.01" min="0" class="form-control form-control-sm"
                           name="{{ $namePrefix }}[{{ $i }}][price]" value="{{ $extra->price }}">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-extra-row" title="{{ __('translate.Remove') }}">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="text-muted small empty-extras-msg">{{ __('translate.No extras yet. Click \'Add Extra\'.') }}</div>
        @endforelse
    </div>
</div>

{{-- Template row used by JS when adding new extras --}}
<template id="extra-row-template-{{ $idSuffix }}">
    <div class="row g-2 align-items-end mb-2 extra-row">
        <div class="col-md-5">
            <label class="form-label small mb-1">{{ __('translate.Name') }}</label>
            <input type="text" class="form-control form-control-sm" data-name="name" placeholder="{{ __('translate.Extra adult') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">{{ __('translate.Type') }}</label>
            <select data-name="type" class="form-select form-select-sm">
                <option value="adult">{{ __('translate.Per extra adult') }}</option>
                <option value="child">{{ __('translate.Per extra child') }}</option>
                <option value="optional">{{ __('translate.Optional add-on') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">{{ __('translate.Price') }}</label>
            <input type="number" step="0.01" min="0" class="form-control form-control-sm" data-name="price">
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger remove-extra-row">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
(function () {
    var suffix = @json($idSuffix);
    var prefix = @json($namePrefix);
    var list = document.getElementById('extras-list-' + suffix);
    var tpl = document.getElementById('extra-row-template-' + suffix);
    var addBtn = document.querySelector('.add-extra-row[data-suffix="' + suffix + '"]');
    if (!list || !tpl || !addBtn) return;

    function nextIndex() {
        return list.querySelectorAll('.extra-row').length;
    }

    function bindRemove(row) {
        var btn = row.querySelector('.remove-extra-row');
        if (btn) {
            btn.addEventListener('click', function () {
                row.remove();
                renumber();
                toggleEmpty();
            });
        }
    }

    function renumber() {
        list.querySelectorAll('.extra-row').forEach(function (row, i) {
            row.querySelectorAll('[data-name], input[name], select[name]').forEach(function (el) {
                var key = el.dataset.name || (el.name && el.name.match(/\[(\w+)\]$/) || [])[1];
                if (!key) return;
                el.name = prefix + '[' + i + '][' + key + ']';
            });
        });
    }

    function toggleEmpty() {
        var msg = list.querySelector('.empty-extras-msg');
        var hasRows = list.querySelectorAll('.extra-row').length > 0;
        if (hasRows && msg) msg.remove();
        if (!hasRows && !msg) {
            var d = document.createElement('div');
            d.className = 'text-muted small empty-extras-msg';
            d.textContent = '{{ __('translate.No extras yet. Click \'Add Extra\'.') }}';
            list.appendChild(d);
        }
    }

    addBtn.addEventListener('click', function () {
        var msg = list.querySelector('.empty-extras-msg');
        if (msg) msg.remove();
        var clone = tpl.content.firstElementChild.cloneNode(true);
        list.appendChild(clone);
        bindRemove(clone);
        renumber();
    });

    list.querySelectorAll('.extra-row').forEach(bindRemove);
})();
</script>
