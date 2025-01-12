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
                                    <th>@lang('Order Date')</th>
                                    <th>@lang('Customer')</th>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('Payment Via')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Payment Status')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ showDateTime($order->created_at, 'd M, Y') }}</td>
                                        <td><a href="{{ route('admin.users.detail', @$order->user->id) }}">{{ @$order->user->username }}</a></td>
                                        <td>{{ $order->order_number }}</td>

                                        <td>
                                            @if ($order->is_cod)
                                                <span class="color--warning" title="@lang('Cash On Delivery')">{{ @$deposit->gateway->name ?? trans('COD') }}</span>
                                            @elseif($order->deposit)
                                                <strong class="text-primary">{{ @$order->deposit->gateway->name }}</strong>
                                            @endif
                                        </td>

                                        <td>
                                            <b>{{ showAmount($order->total_amount) }}</b>
                                        </td>

                                        <td>@php echo $order->paymentBadge() @endphp</td>
                                        <td>@php echo $order->statusBadge() @endphp</td>

                                        <td>

                                            <a href="{{ route('admin.order.details', $order->id) }}" class="btn btn-outline--dark btn-sm">
                                                <i class="la la-desktop"></i>@lang('Details')
                                            </a>

                                            @php
                                                $question = null;
                                                $canCancel = false;
                                                $disabled = false;

                                                if ($order->status == Status::ORDER_PENDING) {
                                                    $canCancel = true;
                                                    $buttonText = 'Processing';
                                                    $question = 'Are you sure to mark the order as processing?';
                                                } elseif ($order->status == Status::ORDER_PROCESSING) {
                                                    $canCancel = true;
                                                    $buttonText = 'Dispatch';
                                                    $question = 'Are you sure to mark the order as dispatched?';
                                                } elseif ($order->status == Status::ORDER_DISPATCHED) {
                                                    $buttonText = 'Deliver';
                                                    $question = 'Are you sure to mark the order as delivered?';
                                                } elseif ($order->status == Status::ORDER_DELIVERED) {
                                                    $disabled = true;
                                                    $buttonText = 'Deliver';
                                                    $question = null;
                                                } elseif ($order->status == Status::ORDER_CANCELED) {
                                                    $disabled = true;
                                                    $buttonText = 'Canceled';
                                                    $question = null;
                                                } elseif ($order->status == Status::ORDER_RETURNED) {
                                                    $disabled = true;
                                                    $buttonText = 'Returned';
                                                    $question = null;
                                                }
                                            @endphp

                                            @if ($order->status == Status::ORDER_PENDING && $order->hasDownloadableProduct())
                                                <button type="button" class="btn btn-outline--success deliverDPBtn mx-1" data-question="{{ __($question) }}" data-action="{{ route('admin.order.status.change', $order->id) }}" data-has_physical_product="{{ $order->hasPhysicalProduct(true) }}" data-after_sale_downloadable_products="{{ $order->afterSaleDownloadableProducts }}" @disabled($disabled)>
                                                    <i class="la la-check"></i>{{ __($buttonText) }}
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline--success confirmationBtn mx-1" data-question="{{ __($question) }}" data-action="{{ route('admin.order.status.change', $order->id) }}" @disabled($disabled)><i class="la la-check"></i>{{ __($buttonText) }}</button>
                                            @endif

                                            @if (Route::is('admin.order.dispatched'))
                                                <button class="btn btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to change status as returned?')" data-action="{{ route('admin.order.return', $order->id) }}">
                                                    <i class="las la-undo-alt"></i>@lang('Return')
                                                </button>
                                            @else
                                                <button class="btn btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to cancel this order?')" data-action="{{ route('admin.order.status.cancel', $order->id) }}" @disabled(!$canCancel)>
                                                    <i class="la la-ban"></i>@lang('Cancel')
                                                </button>
                                            @endif
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

                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="deliverModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Deliver Product')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            $('.deliverDPBtn').on('click', function() {
                let data = $(this).data();

                let modal = $('#deliverModal');
                let html = `<h6 class="question">${data.question}</h6>`;

                if (data.after_sale_downloadable_products.length) {
                    html += `<div class="alert alert-info p-3 my-2">
                            <small class="text--info">@lang('This order has an after sale downloadable product. So, if you want to mark the order as delivered you need to submit the file here.')</small>
                        </div>`;

                    $.each(data.after_sale_downloadable_products, function(index, product) {
                        html += `<div class="form-group">
                                <label class="required">${nameToTitle(product.name)}</label>
                                <input type="file" name="download_file[${product.id}]" accept=".zip" class="form-control" required>
                            </div>`
                    });
                } else if (data.has_physical_product == 0) {
                    html += `<small class="text--info">@lang('Note: This order contains only instant downloadable products. Once processing is complete, the order status will automatically be updated to "Delivered."')</small>`;
                }

                modal.find('form').attr('action', data.action);
                modal.find('.modal-body').html(html);
                modal.modal('show');
            })

            function nameToTitle(name) {
                return name.toLowerCase().split(' ').map(function(word) {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
            }
        })(jQuery);
    </script>
@endpush
