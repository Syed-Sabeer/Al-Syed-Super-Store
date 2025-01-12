@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive-md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Change Qty')</th>
                                    <th>@lang('Post Qty')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($logs as $item)
                                    <tr>
                                        <td>{{ showDateTime($item->created_at, 'd M, Y H:i A') }}</td>
                                        <td>{{ $item->change_quantity }}</td>
                                        <td>{{ $item->post_quantity }}</td>
                                        <td>
                                            @if ($item->remark == '+')
                                                <span class="text--success">{{ trans('Stock-In') }}</span>
                                            @else
                                                <span class="text--danger">{{ trans('Stock-Out') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->order)
                                                <a href="{{ route('admin.order.details', $item->order_id) }}">{{ $item->order->order_number }}</a>
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>{{ __($item->description) }}</td>
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
                @if ($logs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form keySearch='no' dateSearch='yes' />
@endpush
