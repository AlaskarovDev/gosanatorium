@extends('admin.master_layout')
@section('title')
    <title>Авторы и врачи блога</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Авторы и врачи блога</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Blog') }} >> Авторы и врачи</p>
@endsection

@section('body-content')
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-table crancy-table--v3 mg-top-30">
                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">Авторы и врачи</h4>
                                            <a href="{{ route('admin.blog-author.create') }}" class="crancy-btn">+ {{ __('translate.Create New') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <p class="mg-top-20" style="color:#667085">Автор показывается в шапке статьи. Если у статьи автор не выбран, показывается автор «по умолчанию». Врач (галочка «Медицинский эксперт») выбирается в статье в поле «Проверено врачом».</p>
                                <table class="crancy-table__main crancy-table__main-v3 mg-top-20" style="width:100%">
                                    <thead class="crancy-table__head">
                                        <tr>
                                            <th class="crancy-table__column-2 crancy-table__h2">Фото</th>
                                            <th class="crancy-table__column-2 crancy-table__h2">Имя</th>
                                            <th class="crancy-table__column-2 crancy-table__h2">Роль / специальность</th>
                                            <th class="crancy-table__column-2 crancy-table__h2">Тип</th>
                                            <th class="crancy-table__column-2 crancy-table__h2">Статей</th>
                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Status') }}</th>
                                            <th class="crancy-table__column-2 crancy-table__h2">{{ __('translate.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="crancy-table__body">
                                        @forelse ($authors as $author)
                                            <tr>
                                                <td class="crancy-table__column-2 crancy-table__data-2">
                                                    @if ($author->photo)
                                                        <img src="{{ asset($author->photo) }}" alt="" style="width:44px;height:44px;border-radius:50%;object-fit:cover">
                                                    @else
                                                        <span style="display:inline-flex;width:44px;height:44px;border-radius:50%;background:#e6f4f1;color:#0f766e;font-weight:700;align-items:center;justify-content:center">{{ $author->initials }}</span>
                                                    @endif
                                                </td>
                                                <td class="crancy-table__column-2 crancy-table__data-2">
                                                    {{ $author->name }}
                                                    @if ($author->is_default)<br><small style="color:#0f766e">по умолчанию</small>@endif
                                                </td>
                                                <td class="crancy-table__column-2 crancy-table__data-2">{{ $author->role }}@if($author->specialty)<br><small>{{ $author->specialty }}</small>@endif</td>
                                                <td class="crancy-table__column-2 crancy-table__data-2">{{ $author->is_medical ? 'Врач (эксперт)' : 'Автор' }}</td>
                                                <td class="crancy-table__column-2 crancy-table__data-2">{{ (int) ($counts[$author->id] ?? 0) }} / проверено: {{ (int) ($expertCounts[$author->id] ?? 0) }}</td>
                                                <td class="crancy-table__column-2 crancy-table__data-2">{{ $author->status ? 'Активен' : 'Скрыт' }}</td>
                                                <td class="crancy-table__column-2 crancy-table__data-2">
                                                    <a href="{{ route('admin.blog-author.edit', $author->id) }}" class="crancy-btn"><i class="fas fa-edit"></i> {{ __('translate.Edit') }}</a>
                                                    <form action="{{ route('admin.blog-author.destroy', $author->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Удалить автора? В статьях он будет заменён автором по умолчанию.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="crancy-btn delete_danger_btn"><i class="fas fa-trash"></i> {{ __('translate.Delete') }}</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="crancy-table__column-2 crancy-table__data-2">Авторов пока нет.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
