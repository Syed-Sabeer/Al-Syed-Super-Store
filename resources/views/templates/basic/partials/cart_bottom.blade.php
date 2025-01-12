<div class="coupon-content py-3">
    @auth
        <div class="coupon-content__form">
            <div class="applyCouponContainer">
                <div class="apply-coupon-code">
                    <input class="form-control form--control coupon" name="coupon_code" type="text" placeholder="@lang('Enter Coupon Code')" value="{{ session('coupon')['code'] ?? '' }}">
                    <button id="applyCoupon" type="button" @disabled(session()->has('coupon'))>@lang('Apply')</button>
                </div>
            </div>
        </div>
    @endauth
    <div class="subtotal-wrapper gap-4">
        <span class="fs-16">@lang('Subtotal :') </span>
        <strong>
            {{ gs('cur_sym') }}<span class="cartSubtotal">{{ getAmount($subtotal) }}</span>
        </strong>
    </div>
</div>

@php
    // session()->put('coupon', ['code' => 'VISERMART', 'amount' => 30]);
@endphp

<div class="couponContent @if (!session()->has('coupon')) d-none @endif mb-2">
    <div class="d-flex gap-3 justify-content-end align-items-center coupon-inner">
        <div>
            <button type="button" class="text-danger remove-coupon ps-0 removeCoupon">
                <i class="la la-times-circle"></i>
            </button>
            <small>@lang('Coupon')
                (<span class="couponCode fw-bold">{{ session('coupon')['code'] ?? '' }}</span>)
                :
            </small>
        </div>
        <strong class="amount fs-16 d-block">
            - {{ gs('cur_sym') }}<span id="couponAmount">{{ getAmount(session('coupon')['amount'] ?? 0) }}</span>
        </strong>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end cart-total-wrapper">
    <span>@lang('Total :')</span>
    <strong class="amount d-block ">{{ gs('cur_sym') }}<span id="finalTotal">{{ getAmount($subtotal - (session('coupon')['amount'] ?? 0)) }}</span></strong>
</div>
