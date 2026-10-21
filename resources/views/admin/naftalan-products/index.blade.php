@extends('admin.master_layout')
@section('title')
    <title>Нафталан продукты</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">Нафталан продукты</h3>
    <p class="crancy-header__text">Manage Content >> Нафталан продукты</p>
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
                                            <h4 class="crancy-product-card__title">Нафталан продукты</h4>
                                            <a href="{{ route('admin.naftalan-products.create') }}" class="crancy-btn"><i class="fa fa-plus"></i> Добавить товар</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    @if ($products->count() > 0)
                                        <table class="crancy-table__main crancy-table__main-v3 no-footer">
                                            <thead class="crancy-table__head">
                                                <tr>
                                                    <th class="crancy-table__column-1 crancy-table__h1">Фото</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Название</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Цена (₼)</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Активен</th>
                                                    <th class="crancy-table__column-2 crancy-table__h2">Порядок</th>
                                                    <th class="crancy-table__column-3 crancy-table__h3">Действия</th>
                                                </tr>
                                            </thead>
                                            <tbody class="crancy-table__body">
                                                @foreach ($products as $item)
                                                    <tr>
                                                        <td class="crancy-table__column-1 crancy-table__data-1">
                                                            <img src="{{ $item->image_url }}" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->name }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->price }} ₼</h4>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <span class="badge toggle-badge {{ $item->is_active ? 'bg-success' : 'bg-danger' }}" id="badge-{{ $item->id }}">{{ $item->is_active ? 'Да' : 'Нет' }}</span>
                                                        </td>
                                                        <td class="crancy-table__column-2 crancy-table__data-2">
                                                            <h4 class="crancy-table__product-title">{{ $item->sort_order }}</h4>
                                                        </td>
                                                        <td class="crancy-table__column-3 crancy-table__data-3">
                                                            <a href="{{ route('admin.naftalan-products.edit', $item->id) }}" class="crancy-btn" style="background:#0d6efd;color:#fff;"><i class="fas fa-edit"></i> Изменить</a>
                                                            <button type="button" class="crancy-btn toggle-btn {{ $item->is_active ? 'crancy-btn__success' : 'crancy-btn__danger' }}" data-url="{{ route('admin.naftalan-products.toggle', $item->id) }}" data-id="{{ $item->id }}">
                                                                <i class="fas fa-power-off"></i> {{ $item->is_active ? 'Скрыть' : 'Показать' }}
                                                            </button>
                                                            <form action="{{ route('admin.naftalan-products.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить товар?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="crancy-btn crancy-btn__danger"><i class="fas fa-trash"></i> Удалить</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="mg-top-30">{{ $products->links() }}</div>
                                    @else
                                        <div class="alert alert-info text-center">
                                            Нет товаров. Добавьте первый продукт.
                                        </div>
                                    @endif
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
        document.querySelectorAll('.toggle-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(function (r) { return r.json(); }).then(function (d) {
                    var badge = document.getElementById('badge-' + btn.dataset.id);
                    badge.className = 'badge toggle-badge ' + (d.is_active ? 'bg-success' : 'bg-danger');
                    badge.textContent = d.is_active ? 'Да' : 'Нет';
                    btn.className = 'crancy-btn toggle-btn ' + (d.is_active ? 'crancy-btn__success' : 'crancy-btn__danger');
                    btn.innerHTML = '<i class="fas fa-power-off"></i> ' + (d.is_active ? 'Скрыть' : 'Показать');
                });
            });
        });
    </script>
@endpush
