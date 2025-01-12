@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card border">
                <div class="card-body">
                    <h5 class="card-title">@lang('Sales Summary')</h5>
                    <div class="row g-0">
                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100">
                                <small class="text-muted">@lang('Total Sales Product')</small>
                                <h6>{{ $totalSalesProduct }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100">
                                <small class="text-muted">@lang('Total Shipping Charge')</small>
                                <h6>{{ showAmount($totalShippingCharge) }}</h6>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100">
                                <small class="text-muted">@lang('Total Sales Amount')</small>
                                <h6>{{ showAmount($totalSalesAmount) }}</h6>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <div class="p-3 border h-100">
                                <small class="text-muted">@lang('Total Amount')</small>
                                <h6>{{ showAmount($totalAmount) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Order No.')</th>
                                <th>@lang('Customer')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Total Product')</th>
                                <th>@lang('Shipping Charge')</th>
                                <th>@lang('Subtotal')</th>
                                <th>@lang('Total')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td><a href="{{ route('admin.order.details', $log->id) }}">{{ $log->order_number }}</a></td>
                                        <td><a href="{{ route('admin.users.detail', @$log->user->id) }}">{{ @$log->user->username }}</a></td>
                                        <td>{{ showDateTime($log->created_at, 'd M, Y') }}</td>
                                        <td>{{ $log->total_product }}</td>
                                        <td>{{ showAmount($log->shipping_charge) }}</td>
                                        <td>{{ showAmount($log->subtotal) }}</td>
                                        <td>{{ showAmount($log->total_amount) }}</td>
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
                @if($logs->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($logs) }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form dateSearch="yes" />
@endpush

