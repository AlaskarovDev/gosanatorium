@extends('admin.master_layout')
@section('title')
    <title>Отзывы о компании</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Отзывы о компании</h3>
    <p class="crancy-header__text">Страница /ru/otzyvy — модерация</p>
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
                                    <h4 class="crancy-product-card__title">Отзывы о компании @if ($pending)<span class="badge bg-warning text-dark">{{ $pending }} на модерации</span>@endif</h4>
                                    <div>
                                        <a href="{{ route('admin.company-reviews') }}" class="crancy-btn">Все</a>
                                        @foreach (\App\Models\CompanyReview::STATUSES as $k => $l)
                                            <a href="{{ route('admin.company-reviews', ['status' => $k]) }}" class="crancy-btn {{ $status === $k ? '' : 'crancy-btn__nostyle' }}">{{ $l }}</a>
                                        @endforeach
                                    </div>
                                </div>
                                @if (! $ready)
                                    <div class="alert alert-warning mg-top-25">Нужно обновить базу данных (миграция company_reviews).</div>
                                @else
                                    <div class="table-responsive mg-top-25">
                                        <table class="table align-middle">
                                            <thead><tr><th>Дата</th><th>Автор</th><th>Оценка</th><th style="min-width:280px">Отзыв</th><th style="min-width:260px">Статус и ответ</th><th></th></tr></thead>
                                            <tbody>
                                            @forelse ($reviews as $r)
                                                <tr>
                                                    <td>{{ $r->created_at->format('d.m.Y H:i') }}</td>
                                                    <td><strong>{{ $r->name }}</strong><br><small>{{ $r->country }}</small>
                                                        @if ($r->booking_code)<br><small>Код: {{ $r->booking_code }} {!! $r->is_verified ? '<span class="badge bg-success">найден</span>' : '<span class="badge bg-secondary">не найден</span>' !!}</small>@endif
                                                    </td>
                                                    <td style="color:#f59e0b;white-space:nowrap">{{ str_repeat('★', $r->rating) }}</td>
                                                    <td style="white-space:pre-line">{{ $r->text }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.company-reviews.update', $r) }}">
                                                            @csrf @method('PUT')
                                                            <select name="status" class="crancy__item-input mb-2">
                                                                @foreach (\App\Models\CompanyReview::STATUSES as $k => $l)
                                                                    <option value="{{ $k }}" @selected($r->status === $k)>{{ $l }}</option>
                                                                @endforeach
                                                            </select>
                                                            <textarea name="reply" class="crancy__item-input mb-2" rows="2" placeholder="Ответ компании (необязательно)">{{ $r->reply }}</textarea>
                                                            <button class="crancy-btn" type="submit">Сохранить</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.company-reviews.destroy', $r) }}" onsubmit="return confirm('Удалить отзыв?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" type="submit">Удалить</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center text-muted">Отзывов пока нет.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (method_exists($reviews, 'links')){{ $reviews->links() }}@endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
