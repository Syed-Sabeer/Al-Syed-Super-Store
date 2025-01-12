@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Charge')</th>
                                    <th>@lang('Delivery In')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shippingMethods as $shippingMethod)
                                    <tr>
                                        <td>{{ __($shippingMethod->name) }}</td>
                                        <td>{{ showAmount($shippingMethod->charge) }}</td>
                                        <td>{{ $shippingMethod->shipping_time }} @lang('Days')</td>
                                        <td>
                                            <x-toggle-switch name="top" value="1" :checked="$shippingMethod->status == Status::ENABLE" class="status-change" data-id="{{ $shippingMethod->id }}" />
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.shipping.methods.edit', $shippingMethod->id) }}" class="btn btn-outline--primary btn-sm"><i class="la la-pencil"></i>@lang('Edit')</a>
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
                    @if ($shippingMethods->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($shippingMethods) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.shipping.methods.create') }}" class="btn btn-outline--primary btn-sm">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.status-change').on('change', function() {
                const data = {
                    '_token': `{{ csrf_token() }}`
                };

                $.post(`{{ route('admin.shipping.methods.status.switch', '') }}/${$(this).data('id')}`, data,
                    function(response) {
                        notify('success', response.message);
                    }
                );
            });
        })(jQuery)
    </script>
@endpush
