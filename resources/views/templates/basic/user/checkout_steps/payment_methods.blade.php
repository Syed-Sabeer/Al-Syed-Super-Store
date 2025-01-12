@extends($activeTemplate . 'layouts.checkout')

@section('blade')
    <div class="address-wrapper border-0 p-0">
        <div class="row gy-4">
            <div class="col-lg-8">
                <div class="payment-option">
                    <h6 class="payment-option__title mb-4">@lang('Select a Payment Option')</h6>
                    <div class="payment-option__wrapper">
                        @if (gs('cod') && $hasPhysicalProduct)
                            <label class="payment-option-item">
                                <div class="form--check">
                                    <input value="0" class="online_payment form-check-input mt-0" type="radio" name="gateway" data-gateway="cod" form="paymentMethodForm" data-currency="{{ gs('cur_text') }}" required>
                                </div>

                                <span class="payment-option-item-content">
                                    <span class="thumb">
                                        <img src="{{ asset($activeTemplateTrue . 'images/cod.png') }}" class="w-100" alt="image">
                                    </span>
                                    <span class="payment-name">
                                        @lang('Cash On Delivery')
                                    </span>
                                </span>
                            </label>
                            <div class="text-center auth-devide">
                                <span>@lang('Online Payment')</span>
                            </div>
                        @endif

                        <div class="payment-option-wrapper">
                            @foreach ($gatewayCurrencies as $item)
                                <label for="data-{{ $loop->index }}" class="payment-option-item">
                                    <div class="form--check">
                                        <input value="{{ $item->method_code }}" id="data-{{ $loop->index }}" data-gateway="{{ $item }}" class="online_payment form-check-input mt-0" type="radio" name="gateway" form="paymentMethodForm" required>
                                    </div>

                                    <span class="payment-option-item-content">
                                        <span class="thumb">
                                            <img src="{{ getImage(getFilePath('gateway') . '/' . @$item->method->image, getFileSize('gateway')) }}" data-src="{{ getImage(getFilePath('gateway') . '/' . @$item->method->image, getFileSize('gateway')) }}" class="w-100 lazyload" alt="image">
                                        </span>
                                        <span class="payment-name">
                                            {{ __($item->name) }}
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-lg-4">
                <div class="payment-details w-100">
                    <h6 class="title">@lang('Payment Details')</h6>
                    <ul class="gateway-info">
                        <li>
                            <span class="subtitle">@lang('Subtotal')</span>
                            <span id="cartSubtotal">{{ showAmount($subtotal) }}</span>
                        </li>
                        @php
                            $couponAmount = 0;
                        @endphp
                        @isset($coupon)
                            @php
                                $couponAmount = $coupon['amount'] > $subtotal ? $subtotal : $coupon['amount'];

                            @endphp
                            <li>
                                <span class="subtitle">
                                    @lang('Coupon') ({{ $coupon['code'] }})
                                </span>
                                <span id="couponAmount">{{ showAmount($couponAmount) }}</span>
                            </li>
                        @endisset

                        @if ($shippingMethod)
                            <li>
                                <span class="subtitle">@lang('Shipping Charge')</span>
                                <span id="shippingCharge">{{ showAmount($shippingMethod->charge) }}</span>
                            </li>
                        @endif
                        @php
                            $shippingCharge = $shippingMethod->charge ?? 0;
                            $totalAmount = $subtotal + $shippingCharge - $couponAmount;
                        @endphp


                        <li class="deposit-info">
                            <span>@lang('Processing Charge')
                                <span data-bs-toggle="tooltip" title="@lang('Gateway Processing Charge')" class="processing-fee-info"><i class="las la-info-circle"></i> </span>
                            </span>
                            <span>
                                <span class="processing-fee text--color">@lang('0.00')</span>
                                {{ __(gs('cur_text')) }}
                            </span>
                        </li>

                        <li class="deposit-info total-amount">
                            <span>@lang('Total')</span>
                            <span>
                                <span class="final-amount text--color">
                                    <span class="cl-title" id="total">{{ showAmount($totalAmount, currencyFormat: false) }}</span>
                                </span>
                                {{ __(gs('cur_text')) }}
                            </span>
                        </li>
                    </ul>

                    <p class="gateway-conversion mb-0 d-none">
                        <span>@lang('Rate') </span>
                        <span class="exchange_rate fw-semibold"><span class="text"></span></span>
                    </p>

                    <p class="conversion-currency bg-light p-3 mt-3 mb-0 rounded-1 d-none">
                        <span>@lang('The final payable amount is')</span>
                        <span class="whitespace-nowrap">
                            <strong class="in-currency fw-semibold"></strong> <strong class="gateway-currency fw-semibold"></strong>
                        </span>
                    </p>

                    <p class="crypto-message text-muted">
                        <i class="la la-info-circle"></i>
                        <span> @lang('Conversion with') <span class="gateway-currency text--color"></span> @lang('and final value will Show on next step')</span>
                    </p>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('user.checkout.delivery.methods') }}" class="text--base">
                        <i class="la la-angle-left"></i>
                        @lang('Back to Delivery Info')
                    </a>
                    <form action="{{ route('user.checkout.complete') }}" method="POST" id="paymentMethodForm">
                        @csrf
                        <input type="hidden" name="currency">
                        <button type="submit" class="btn btn--base h-45">@lang('Complete Order') <i class="la la-angle-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function() {
            "use strict";
            $('[name=gateway]').on('change', function() {
                let gateway = $(this).data('gateway');
                $('[name=currency]').val(gateway == 'cod' ? "{{ gs('cur_text') }}" : gateway.currency);

                let processingFeeInfo = '';
                if (gateway == 'cod') {
                    processingFeeInfo = `@lang('Gateway Processing Charge')`
                } else {
                    processingFeeInfo = `${parseFloat(gateway.percent_charge).toFixed(2)}% with ${parseFloat(gateway.fixed_charge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`;
                }


                $(".processing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation(gateway);


            });

            function calculation(gateway) {

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;
                let amount = @json($totalAmount);

                if (gateway == 'cod') {
                    gateway = {
                        percent_charge: 0,
                        fixed_charge: 0,
                        currency: "{{ gs('cur_text') }}",
                        method: {
                            crypto: ''
                        }

                    }
                }

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));


                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".button[type=submit]").attr('disabled', true);
                } else {
                    $(".button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.exchange_rate .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ?
                        8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .deposit-info {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 2;
        }

        .auth-devide {
            position: relative;
            margin: 20px 0px;
        }

        .auth-devide::after {
            content: '';
            position: absolute;
            height: 1px;
            width: 100%;
            background-color: hsl(var(--border));
            top: 50%;
            left: 0;
            z-index: 1;
        }

        .auth-devide span {
            background: hsl(var(--white));
            padding: 3px 8px;
            z-index: 2;
            position: relative;
        }
    </style>
@endpush
