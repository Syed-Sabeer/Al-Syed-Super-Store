@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card summary-card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Summary')</h5>
                    <div class="row g-0">
                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border-card h-100">
                                <small class="text-muted">@lang('Total Sales')</small>
                                <h6>{{ showAmount($deposit['total_deposit_amount']) }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border-card h-100">
                                <small class="text-muted">@lang('Payment Pending')</small>
                                <h6>{{ showAmount($deposit['total_deposit_pending']) }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border-card h-100">
                                <small class="text-muted">@lang('Rejected Payment')</small>
                                <h6>{{ $deposit['total_deposit_rejected'] }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border-card h-100">
                                <small class="text-muted">@lang('Payment Charge')</small>
                                <h5>{{ showAmount($deposit['total_deposit_charge']) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body p-0">
                    <h5 class="card-title pt-3 ps-3">@lang('Orders')</h5>
                    <ul class="list-group list-group-flush custom-list-group">
                        @foreach ($widget['orders'] as $key => $order)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                                <span>{{ __(keyToTitle($key)) }}</span>
                                <a href="{{ route($order['route']) }}">
                                    <span class="fw-bold badge {{ $order['color'] }} text-white">
                                        {{ $order['count'] }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Attention Required')</h5>
                    <div class="row gy-3 counting-widget">
                        <div class="col-12">
                            <x-widget value="{{ $deposit['total_deposit_pending'] }}" title="Pending Payment" style="2" bg="white" color="danger" icon="las la-money-bill-wave" link="{{ route('admin.deposit.pending') }}" icon_style="solid" overlay_icon=0 />
                        </div>
                        <div class="col-12">
                            <x-widget value="{{ $widget['pending_tickets'] }}" title="Pending Tickets" style="2" bg="white" color="warning" icon="las la-ticket-alt" link="{{ route('admin.ticket.pending') }}" icon_style="solid" overlay_icon=0 />
                        </div>
                        <div class="col-12">
                            <x-widget value="{{ $widget['low_stock_products'] }}" title="Low Stock Product" style="2" bg="white" color="brown" icon="las la-layer-group" link="{{ route('admin.products.low.stock') }}" icon_style="solid" overlay_icon=0 />
                        </div>
                        <div class="col-12">
                            <x-widget value="{{ $widget['out_of_stock_products'] }}" title="Out Of Stock Product" style="2" bg="white" color="red" icon="lab la-dropbox" link="{{ route('admin.products.out.of.stock') }}" icon_style="solid" overlay_icon=0 />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">@lang('Customers')</h5>

                    <div class="row g-3 account-widget">

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['total_users'] }}" title="Total Registered" style="2" bg="white" color="info" icon="la la-users" link="{{ route('admin.users.all') }}" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['profile_completed'] }}" title="Profile Completed" style="2" bg="white" color="success" icon="la la-user-check" link="{{ route('admin.users.profile.completed') }}" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['active_users'] }}" title="Active" style="2" bg="white" color="green" icon="la la-user-check" link="{{ route('admin.users.active') }}" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['banned_users'] }}" title="Banned" style="2" bg="white" color="danger" icon="la la-user-slash" link="{{ route('admin.users.banned') }}" icon_style="solid" overlay_icon=0 />
                        </div>

                        {{-- <div class="col-sm-6">
                            <x-widget value="{{ $widget['email_unverified_users'] }}" title="Email Unverified" style="2" bg="white" color="5" icon="la la-envelope" link="{{ route('admin.users.email.unverified') }}" icon_style="solid" overlay_icon=0 />
                        </div>

                        <div class="col-sm-6">
                            <x-widget value="{{ $widget['mobile_unverified_users'] }}" title="Mobile Unverified" style="2" bg="white" color="2" icon="la la-mobile" link="{{ route('admin.users.mobile.unverified') }}" icon_style="solid" overlay_icon=0 />
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-1 gy-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">@lang('Sales Report')</h5>
                        <div id="salesDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>
                    <div id="salesReportChart"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-1 justify-content-between align-items-center">
                        <h5 class="card-title">@lang('Top Selling Products')</h5>
                        <a href="{{ route('admin.products.top.selling') }}" class="t-link">@lang('View All')</a>
                    </div>
                    @foreach ($topSellingProducts as $product)
                        @php
                            $salePrice = $product->salePrice();
                            $price = $product->regular_price;
                        @endphp

                        <div class="mt-3 top-selling-product">
                            <a href="{{ $product->link() }}" target="_blank" data-bs-placement="bottom" title="@lang('View As Customer')" class="text-center top-selling-link">
                                <img src="{{ $product->mainImage() }}" alt="image" class="top-selling-img">
                            </a>
                            <div class="description">
                                <div class="d-flex justify-content-between flex-wap gap-1">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" title="@lang('Edit')" class="color--blue d-inline-block mb-2">{{ __($product->name) }}</a>
                                </div>
                                <p>{{ $product->total . ' ' . Str::plural('sale', $product->total) }}</p>
                                <p>{{ strLimit(__($product->summary), 120) }}</p>
                                <p class="mt-1">
                                    <span>@lang('Price'):</span>
                                    @if ($price != $salePrice)
                                        <span class="ms-1">{{ showAmount($salePrice) }}</span>
                                        <del class="ms-2 text--danger">{{ showAmount($price) }}</del>
                                    @else
                                        <span class="ms-1">{{ showAmount($price) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div><!-- media end-->
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-1">
        <div class="col-12">
            <div class="row gy-4">
                <div class="col-xxl-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title">@lang('Latest Customer')</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table--light style--two table">
                                    <thead>
                                        <tr>
                                            <th>@lang('User')</th>
                                            <th>@lang('Username')</th>
                                            <th>@lang('Email')</th>
                                            <th>@lang('Order')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @forelse($latestUser as $user)
                                            <tr>
                                                <td>{{ $user->fullname }}</td>
                                                <td><a href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a></td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->orders->count() }}</td>
                                                <td>
                                                    <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-outline--primary btn-sm">
                                                        <i class="las la-desktop"></i> @lang('Details')
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title">@lang('Latest Order')</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Customer')</th>
                                            <th>@lang('Order Id')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Shipping Charge')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    @if ($order->user)
                                                        <a href="{{ route('admin.users.detail', @$order->user->id) }}">{{ $order->user->fullname }}</a>
                                                    @else
                                                        @php $shippingAddress = json_decode($order->shipping_address) @endphp
                                                        {{ $shippingAddress->firstname . ' ' . $shippingAddress->lastname }}
                                                    @endif
                                                </td>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ showAmount($order->amount) }}</td>
                                                <td>{{ showAmount($order->shipping_charge) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.order.details', $order->id) }}" class="btn btn-outline--primary btn-sm">
                                                        <i class="las la-desktop"></i> @lang('Details')
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-4 mt-1">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        "use strict";

        const start = moment().subtract(14, 'days');
        const end = moment();

        const dateRangeOptions = {
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            maxDate: moment()
        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        }

        let salesBarChart = barChart(
            document.querySelector("#salesReportChart"),
            @json(__(gs('cur_text'))),
            [{
                name: 'Ordered',
                data: []
            }],
            [],
        );

        const salesChart = (startDate, endDate) => {

            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }

            const url = @json(route('admin.chart.sales'));

            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        salesBarChart.updateSeries(data.data);
                        salesBarChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        $('#salesDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#salesDatePicker span', start, end));

        changeDatePickerText('#salesDatePicker span', start, end);

        salesChart(start, end);

        $('#salesDatePicker').on('apply.daterangepicker', (event, picker) => salesChart(picker.startDate, picker.endDate));

        piChart(
            document.getElementById('userBrowserChart'),
            @json(@$chart['user_browser_counter']->keys()),
            @json(@$chart['user_browser_counter']->flatten())
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(@$chart['user_os_counter']->keys()),
            @json(@$chart['user_os_counter']->flatten())
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(@$chart['user_country_counter']->keys()),
            @json(@$chart['user_country_counter']->flatten())
        );
    </script>
@endpush
@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }

        .custom-list-group .list-group-item {
            padding: 16px 0px;
        }

        .account-widget .widget-two,
        .counting-widget .widget-two {
            border: 1px solid #eee !important;
            box-shadow: none !important;
        }

        .summary-card .border-card {
            box-shadow: 0 0 0 1px #eee;
            background-color: white
        }

        .counting-widget .widget-two {
            padding: 10px;
        }

        .counting-widget .widget-two__icon {
            width: 46px;
            height: 46px;
        }

        .counting-widget .widget-two__icon i {
            font-size: 32px;
        }

        .counting-widget .widget-two__content {
            width: 100%;
            flex: 1;
        }

        .counting-widget .widget-two__content h3 {
            font-size: 1.125rem;
        }

        .counting-widget .widget-two__content p {
            font-size: 0.8rem;
        }
    </style>
@endpush
