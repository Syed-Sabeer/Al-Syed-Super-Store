@extends('Template::layouts.user')
@section('panel')
    <div class="notice"></div>
    <div class="row justify-content-center g-2 g-sm-3 g-md-4">
        <div class="col-sm-6 col-xl-4 col-6">
            <div class="dashboard-item">
                <a href="{{ route('user.orders', 'all') }}" class="d-block">
                    <span class="dashboard-icon bg--info">
                        <i class="las la-list-ul"></i>
                    </span>
                    <div class="cont">
                        <div class="dashboard-header">
                            <h2 class="title">{{ $order['total'] }}</h2>
                        </div>
                        <p class="mb-0 dashbaord-item-name"> @lang('All Orders')</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4 col-6">
            <div class="dashboard-item">
                <a href="{{ route('user.orders', 'pending') }}">
                    <span class="dashboard-icon bg--warning">
                        <i class="las la-pause-circle"></i>
                    </span>
                    <div class="cont">
                        <div class="dashboard-header">
                            <h2 class="title">{{ $order['pending'] }}</h2>
                        </div>
                        <p class="mb-0 dashbaord-item-name">
                            @lang('Pending Orders')
                        </p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4 col-6">
            <div class="dashboard-item">
                <a href="{{ route('user.orders', 'processing') }}" class="d-block">
                    <span class="dashboard-icon bg--base">
                        <i class="las la-spinner"></i>
                    </span>
                    <div class="cont">
                        <div class="dashboard-header">
                            <h2 class="title">{{ $order['processing'] }}</h2>
                        </div>
                        <p class="mb-0 dashbaord-item-name"> @lang('Processing Orders')</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4 col-6">
            <div class="dashboard-item">
                <a href="{{ route('user.orders', 'dispatched') }}" class="d-block">
                    <span class="dashboard-icon bg--primary">
                        <i class="las la-shopping-basket"></i>
                    </span>
                    <div class="cont">
                        <div class="dashboard-header">
                            <h2 class="title">{{ $order['dispatched'] }}</h2>
                        </div>
                        <p class="mb-0 dashbaord-item-name">@lang('Dispatched Orders')</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4 col-6">
            <div class="dashboard-item">
                <a href="{{ route('user.orders', 'completed') }}" class="d-block">
                    <span class="dashboard-icon bg--success">
                        <i class="las la-check-circle"></i>
                    </span>
                    <div class="cont">
                        <div class="dashboard-header">
                            <h2 class="title">{{ $order['delivered'] }}</h2>
                        </div>
                        <p class="mb-0 dashbaord-item-name">@lang('Order Completed')</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4 col-6">
            <div class="dashboard-item">
                <a href="{{ route('user.orders', 'canceled') }}">
                    <span class="dashboard-icon bg--danger">
                        <i class="las la-times-circle"></i>
                    </span>
                    <div class="cont">
                        <div class="dashboard-header">
                            <h2 class="title">{{ $order['canceled'] }}</h2>
                        </div>
                        <p class="mb-0 dashbaord-item-name"> @lang('Canceled Orders')</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
    <div class="mt-4">
        <h5 class="title mb-3">@lang('Latest Orders')</h5>
        @include('Template::user.orders.orders_table', ['orders' => $latestOrders])
    </div>
@endsection
