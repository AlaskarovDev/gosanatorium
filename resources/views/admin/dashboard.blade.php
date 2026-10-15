@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Dashboard') }}</title>
@endsection
@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Dashboard') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Dashboard') }} >> {{ __('translate.Dashboard') }}</p>
@endsection
@push('style_section')
    <link rel="stylesheet" href="{{ asset('backend/css/charts.min.css') }}">
@endpush
@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <div class="row">
                                <div class="col-xxl-3 col-md-6 col-12 mg-top-30">
                                    <div class="crancy-ecom-card crancy-ecom-card__v2">
                                        <div class="flex-main">
                                            <span>
                                                @include('svg.total_sale')
                                            </span>
                                            <div class="flex-1">
                                                <div class="crancy-ecom-card__heading">
                                                    <div class="crancy-ecom-card__icon">
                                                        <h4 class="crancy-ecom-card__title">{{ __('translate.Total Sale') }}
                                                        </h4>
                                                    </div>

                                                </div>
                                                <div class="crancy-ecom-card__content">
                                                    <div class="crancy-ecom-card__camount">
                                                        <div class="crancy-ecom-card__camount__inside">
                                                            <h3 class="crancy-ecom-card__amount">
                                                                {{ currency($total_income) }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xxl-3 col-md-6 col-12 mg-top-30">
                                    <div class="crancy-ecom-card crancy-ecom-card__v2">
                                        <div class="flex-main">
                                            <span>
                                                @include('svg.net_earning')
                                            </span>
                                            <div class="flex-1">
                                                <div class="crancy-ecom-card__heading">
                                                    <div class="crancy-ecom-card__icon">
                                                        <h4 class="crancy-ecom-card__title">
                                                            {{ __('translate.Admin Earnings') }} </h4>
                                                    </div>

                                                </div>
                                                <div class="crancy-ecom-card__content">
                                                    <div class="crancy-ecom-card__camount">
                                                        <div class="crancy-ecom-card__camount__inside">
                                                            <h3 class="crancy-ecom-card__amount">
                                                                {{ currency($total_commission) }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xxl-3 col-md-6 col-12 mg-top-30">
                                    <div class="crancy-ecom-card crancy-ecom-card__v2">
                                        <div class="flex-main">
                                            <span>
                                                @include('svg.instructor_earning')
                                            </span>
                                            <div class="flex-1">
                                                <div class="crancy-ecom-card__heading">
                                                    <div class="crancy-ecom-card__icon">
                                                        <h4 class="crancy-ecom-card__title">
                                                            {{ __('translate.Seller Earnings') }} </h4>
                                                    </div>

                                                </div>
                                                <div class="crancy-ecom-card__content">
                                                    <div class="crancy-ecom-card__camount">
                                                        <div class="crancy-ecom-card__camount__inside">
                                                            <h3 class="crancy-ecom-card__amount">
                                                                {{ currency($net_income) }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-md-6 col-12 mg-top-30">
                                    <div class="crancy-ecom-card crancy-ecom-card__v2">
                                        <div class="flex-main">
                                            <span>
                                                @include('svg.total_sold')
                                            </span>
                                            <div class="flex-1">
                                                <div class="crancy-ecom-card__heading">
                                                    <div class="crancy-ecom-card__icon">
                                                        <h4 class="crancy-ecom-card__title">
                                                            {{ __('translate.Total Sold') }} </h4>
                                                    </div>

                                                </div>
                                                <div class="crancy-ecom-card__content">
                                                    <div class="crancy-ecom-card__camount">
                                                        <div class="crancy-ecom-card__camount__inside">
                                                            <h3 class="crancy-ecom-card__amount">{{ $total_sold }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row crancy-gap-30">
                                <div class="col-12">
                                    <!-- Charts One -->
                                    <div class="charts-main charts-home-one mg-top-30">
                                        <!-- Top Heading -->
                                        <div class="charts-main__heading  mg-btm-20">
                                            <h4 class="charts-main__title">{{ __('translate.Booking Statistics') }}</h4>

                                        </div>
                                        <div class="charts-main__one">
                                            <div class="tab-content" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="crancy-chart__s1" role="tabpanel"
                                                    aria-labelledby="crancy-chart__s1">
                                                    <div class="crancy-chart__inside crancy-chart__three">
                                                        <!-- Chart One -->
                                                        <canvas id="myChart_recent_statics"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Charts One -->
                                </div>
                            </div>

                            <div class="crancy-table crancy-table--v3 mg-top-30">

                                <div class="crancy-customer-filter">
                                    <div
                                        class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div
                                            class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Latest Bookings') }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <div id="crancy-table__main_wrapper" class=" dt-bootstrap5 no-footer">
                                    <table class="crancy-table__main crancy-table__main-v3  no-footer" id="dataTable">
                                        <thead class="crancy-table__head">
                                            <tr>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Booking Code') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Service Title') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Total Amount') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Location') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Status') }}</th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="crancy-table__body">
                                            @foreach ($bookings as $booking)
                                                <tr class="odd">
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        #{{ $booking->booking_code ?? 'N/A' }}</td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ Str::limit($booking->service->title, 50) }}
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ currency($booking->total) }}
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        {{ $booking?->service?->location ?? 'N/A' }}
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <span
                                                            class="crancy-badge crancy-table__status--paid">{{ $booking->booking_status }}</span>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <a href="{{ route('admin.tourbooking.bookings.show', $booking) }}"
                                                            class="crancy-action__btn crancy-action__edit crancy-btn"><i
                                                                class="fas fa-eye"></i>
                                                            {{ __('translate.Details') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>


            </div>


        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js_section')
    <script src="{{ asset('backend/js/charts.js') }}"></script>

    <script>
        "use strict";

        let purchase_data = @json($data);
        purchase_data = JSON.parse(purchase_data);

        let date_lable = @json($lable);
        date_lable = JSON.parse(date_lable);

        // Chart Three
        const ctx_myChart_recent_statics = document.getElementById('myChart_recent_statics').getContext('2d');
        const gradientBgs = ctx_myChart_recent_statics.createLinearGradient(400, 100, 100, 400);

        gradientBgs.addColorStop(0, 'rgba(100, 64, 251, 0.1');
        gradientBgs.addColorStop(1, 'rgba(100, 64, 251, 0.5)');

        const myChart_recent_statics = new Chart(ctx_myChart_recent_statics, {
            type: 'line',

            data: {

                labels: date_lable,
                datasets: [{
                    label: 'Sells',
                    data: purchase_data,
                    backgroundColor: gradientBgs,
                    borderColor: '#6440FBFF',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    fillColor: '#fff',
                    fill: 'start',
                    pointRadius: 2,
                }]
            },

            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    x: {
                        ticks: {
                            color: '#6440FBFF',
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                            color: '#E6F3FF',
                        },
                        suggestedMax: 100,
                        suggestedMin: 50,

                    },
                    y: {
                        ticks: {
                            color: '#6440FBFF',
                            callback: function(value, index, values) {
                                return (value / 10) * 10 + '$';
                            }
                        },
                        grid: {
                            drawBorder: false,
                            color: '#D7DCE7',
                            borderDash: [5, 5]
                        },
                    },
                },
                plugins: {
                    tooltip: {
                        padding: 10,
                        displayColors: true,
                        yAlign: 'bottom',
                        backgroundColor: '#fff',
                        titleColor: '#000',
                        titleFont: {
                            weight: 'normal'
                        },
                        bodyColor: '#2F3032',
                        cornerRadius: 12,
                        boxPadding: 3,
                        usePointStyle: true,
                        borderWidth: 0,
                        font: {
                            size: 14
                        },
                        caretSize: 9,
                        bodySpacing: 100,
                    },
                    legend: {
                        position: 'bottom',
                        display: false,
                    },
                    title: {
                        display: false,
                        text: "{{ __('translate.Purchase History') }}"
                    }
                }
            }
        });

        function itemDeleteConfrimation(id) {
            $("#item_delect_confirmation").attr("action", '{{ url('admin/course-enrollment-delete/') }}' + "/" + id)
        }
    </script>
@endpush
