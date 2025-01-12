<div class="cart-items-wrapper">
    @forelse ($cartItems as $cartItem)
        <x-dynamic-component :component="frontendComponent('cart-item')" :cartItem="$cartItem" :isCartPage="false" />
    @empty
        <div class="single-product-item no_data">
            <div class="no_data-thumb">
                <img src="{{ getImage('assets/images/empty_cart.png') }}" alt="img">
            </div>
            <h6>@lang('Your cart is empty')</h6>
        </div>
    @endforelse
</div>

@if ($subtotal > 0)
    <div class="cart-bottom">
        @include($activeTemplate . 'partials.cart_bottom')
        @if ($cartItems->count() > 0)
            <div class="btn-wrapper text-end">
                @php
                    $route = cartManager()->checkPhysicalProductExistence() ? route('user.checkout.shipping.info') : route('user.checkout.payment.methods');
                @endphp

                <a class="btn btn--base mt-3" href="{{ $route }}">@lang('Checkout')</a>
            </div>
        @endif
    </div>
@endif
