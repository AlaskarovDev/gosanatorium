@extends('admin.master_layout')
@section('title')
    <title>Заявки</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Заявки</h3>
    <p class="crancy-header__text">Формы сайта: контакты, консультация и др.</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-product-card mg-top-30">
                                <div class="create_new_btn_inline_box">
                                    <h4 class="crancy-product-card__title">Заявки @if ($newCount)<span class="badge bg-danger">{{ $newCount }} новых</span>@endif</h4>
                                    <form method="POST" action="{{ route('admin.leads.test-telegram') }}">
                                        @csrf
                                        <button class="crancy-btn" type="submit">Проверить Telegram</button>
                                    </form>
                                </div>
                                @unless ($telegramReady)
                                    <div class="alert alert-info mg-top-25">Telegram-уведомления выключены: укажите токен бота и ID группы в <a href="{{ route('admin.company-facts') }}">Company facts</a>. E-mail уведомления работают.</div>
                                @endunless
                                <div class="mg-top-25 d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.leads') }}" class="crancy-btn {{ ! $type && ! $status ? '' : 'crancy-btn__nostyle' }}">Все</a>
                                    @foreach (\App\Models\Lead::TYPES as $k => $l)
                                        <a href="{{ route('admin.leads', ['type' => $k]) }}" class="crancy-btn {{ $type === $k ? '' : 'crancy-btn__nostyle' }}">{{ $l }}</a>
                                    @endforeach
                                    <a href="{{ route('admin.leads', ['status' => 'new']) }}" class="crancy-btn {{ $status === 'new' ? '' : 'crancy-btn__nostyle' }}">Новые</a>
                                </div>
                                @if (! $ready)
                                    <div class="alert alert-warning mg-top-25">Нужно обновить базу данных (миграция leads).</div>
                                @else
                                    <div class="table-responsive mg-top-25">
                                        <table class="table align-middle">
                                            <thead><tr><th>Дата</th><th>Тип</th><th>Клиент</th><th style="min-width:280px">Детали</th><th>Уведомления</th><th>Статус</th><th></th></tr></thead>
                                            <tbody>
                                            @forelse ($leads as $l)
                                                @php $wa = 'https://wa.me/' . preg_replace('/\D/', '', $l->phone); @endphp
                                                <tr @if ($l->status === 'new') style="background:#fffbeb" @endif>
                                                    <td style="white-space:nowrap">{{ $l->created_at->format('d.m.Y H:i') }}</td>
                                                    <td>{{ \App\Models\Lead::TYPES[$l->type] ?? $l->type }}</td>
                                                    <td><strong>{{ $l->name }}</strong><br><a href="tel:{{ preg_replace('/[^\d+]/', '', $l->phone) }}">{{ $l->phone }}</a><br>
                                                        <a href="{{ $wa }}" target="_blank" rel="noopener">WhatsApp</a>
                                                        @if ($l->contact_method)<br><small>Связь: {{ \App\Models\Lead::METHODS[$l->contact_method] ?? $l->contact_method }}</small>@endif
                                                    </td>
                                                    <td>
                                                        @if ($l->service_title)<div>Санаторий: <b>{{ $l->service_title }}</b></div>@endif
                                                        @if ($l->dates)<div>Даты: {{ $l->dates }}</div>@endif
                                                        @foreach ((array) $l->payload as $pk => $pv)<div>{{ $pk }}: {{ $pv }}</div>@endforeach
                                                        @if ($l->message)<div style="white-space:pre-line">{{ $l->message }}</div>@endif
                                                        @if ($l->source_url)<small class="text-muted" style="word-break:break-all">{{ $l->source_url }}</small>@endif
                                                    </td>
                                                    <td style="white-space:nowrap"><small>E-mail: {{ $l->email_sent ? '✓' : '—' }}<br>Telegram: {{ $l->telegram_sent ? '✓' : '—' }}</small></td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.leads.update', $l) }}">
                                                            @csrf @method('PUT')
                                                            <select name="status" class="crancy__item-input" onchange="this.form.submit()">
                                                                @foreach (\App\Models\Lead::STATUSES as $k => $lbl)
                                                                    <option value="{{ $k }}" @selected($l->status === $k)>{{ $lbl }}</option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.leads.destroy', $l) }}" onsubmit="return confirm('Удалить заявку?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" type="submit">Удалить</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="text-center text-muted">Заявок пока нет.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (method_exists($leads, 'links')){{ $leads->links() }}@endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
