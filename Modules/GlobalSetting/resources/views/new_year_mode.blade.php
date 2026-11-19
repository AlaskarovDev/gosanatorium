@extends('admin.master_layout')
@section('title')
    <title>Новогодний режим</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Новогодний режим</h3>
    <p class="crancy-header__text">{{ __('translate.Website Setup') }} >> Новогодний режим</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.new-year-mode-update') }}" method="POST" id="ny-form">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">Новогоднее оформление сайта</h4>
                                            <p class="mg-top-10" style="color:#64748b">
                                                Полоса над шапкой, гирлянда под шапкой, снег и бейдж «🎁 Новогоднее предложение» на акциях.
                                                «Авто» — включено с даты начала 00:00 до даты окончания 23:59 по времени Баку (оба дня включительно).
                                                Когда режим не активен, новогодние стили и скрипты на сайт не загружаются.
                                            </p>
                                            <p class="mg-top-10" id="ny-status">
                                                Сейчас на сайте:
                                                @if ($active)
                                                    <strong style="color:#15803d">включено</strong> — «{{ $barText }}»
                                                @else
                                                    <strong style="color:#b91c1c">выключено</strong>
                                                @endif
                                            </p>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label" for="ny_mode">Режим</label>
                                                        <select class="form-select crancy__item-input" id="ny_mode" name="ny_mode">
                                                            @foreach (['auto' => 'Авто (по датам)', 'on' => 'Включено', 'off' => 'Выключено'] as $k => $label)
                                                                <option value="{{ $k }}" {{ old('ny_mode', $values['ny_mode']) === $k ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('ny_mode')<span class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label" for="ny_start">Дата начала</label>
                                                        <input class="crancy__item-input" type="date" id="ny_start" name="ny_start" value="{{ old('ny_start', $values['ny_start']) }}">
                                                        @error('ny_start')<span class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label" for="ny_end">Дата окончания</label>
                                                        <input class="crancy__item-input" type="date" id="ny_end" name="ny_end" value="{{ old('ny_end', $values['ny_end']) }}">
                                                        @error('ny_end')<span class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label" for="ny_link">Ссылка в верхней полосе</label>
                                                        <input class="crancy__item-input" type="text" id="ny_link" name="ny_link" value="{{ old('ny_link', $values['ny_link']) }}">
                                                        @error('ny_link')<span class="text-danger">{{ $message }}</span>@enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mg-top-15" style="color:#64748b">
                                                Бейдж «Новогоднее предложение» включается галочкой в каждой акции (Акции → Редактировать).
                                            </p>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
