@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="order-track-section py-60">
        <div class="container">
            <h5 class="title mb-3 text-center">@lang('Track Your Order')</h5>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-7 col-md-9 col-xl-6">
                    <form class="order-track-form" id="order-track">
                        @csrf
                        <div class="order-track-form-group">
                            <input type="text" name="order_number" placeholder="@lang('Enter Your Order ID')">
                            <button type="submit" class="track-btn">@lang('Track Now')</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="order-track-wrapper d-flex flex-wrap justify-content-center">
                        <div class="confirm-state order-track-item">
                            <div class="thumb">
                                <i class="las la-check-square"></i>
                            </div>
                            <div class="content">
                                <h6 class="title">@lang('Confirmed')</h6>
                            </div>
                        </div>

                        <div class="order-track-item processing-state">
                            <div class="thumb">
                                <i class="las la-sync-alt"></i>
                            </div>
                            <div class="content">
                                <h6 class="title">@lang('Processing')</h6>
                            </div>
                        </div>

                        <div class="order-track-item dispatched-state">
                            <div class="thumb">
                                <i class="las la-truck-pickup"></i>
                            </div>
                            <div class="content">
                                <h6 class="title">@lang('Dispatched')</h6>
                            </div>
                        </div>

                        <div class="order-track-item delivered-state">
                            <div class="thumb">
                                <i class="las la-map-signs"></i>
                            </div>
                            <div class="content">
                                <h6 class="title">@lang('Delivered')</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        (function($) {
            $(document).on('submit', '#order-track', function(e) {
                e.preventDefault();
                let data = {
                    '_token': `{{ csrf_token() }}`,
                    'order_number': $('input[name=order_number]').val()
                };

                $.post("{{ route('order.track') }}", data,
                    function(response) {
                        if (response.success) {
                            if (response.status == 4) {
                                $('.confirm-state, .processing-state, .dispatched-state, .delivered-state').removeClass('active');
                                notify('error', 'This order is canceled by admin');
                            }
                            if (response.payment_status != '{{ Status::ORDER_INITIATED }}' && response.status != '{{ Status::ORDER_CANCELED }}') {

                                response.status >= '{{ Status::ORDER_PENDING }}' ? $('.confirm-state').addClass('active') : $('.confirm-state').removeClass('active');

                                response.status >= '{{ Status::ORDER_PROCESSING }}' ? $('.processing-state').addClass('active') : $('.processing-state').removeClass('active');

                                response.status >= '{{ Status::ORDER_DISPATCHED }}' ? $('.dispatched-state').addClass('active') : $('.dispatched-state').removeClass('active');

                                response.status >= '{{ Status::ORDER_DELIVERED }}' ? $('.delivered-state').addClass('active') : $('.delivered-state').removeClass('active');
                            }
                        } else {
                            $('.confirm-state, .processing-state, .dispatched-state, .delivered-state').removeClass('active');
                            notify('error', response.error);
                        }

                        $('.track-btn').attr('disabled', false);
                    },
                );


            });
        })(jQuery)
    </script>
@endpush

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/order-track.css') }}" rel="stylesheet">
@endpush
