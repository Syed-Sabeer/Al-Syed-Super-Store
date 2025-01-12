@props([
    'cartItem' => null,
    'price' => null,
    'isCartPage' => true,
])

@if ($cartItem)
    @php
        $price = $cartItem->product->prices($cartItem->productVariant)->sale_price;
        $mainImage = $cartItem->productVariant && @$cartItem->productVariant->main_image_id ? $cartItem->productVariant->mainImage(true) : @$cartItem->product->mainImage(true);
    @endphp

    <div class="single-product-item cartItem" data-id="{{ @$cartItem->id ?? 0 }}" data-price="{{ $price }}">
        <div class="thumb">
            <img class="lazyload" src="{{ getImage(null) }}" data-src="{{ $mainImage }}" alt="product-image">
        </div>
        <div class="content">
            <div class="content-top">
                <div class="content-top-left">
                    <a class="title" href="{{ $cartItem->product->link() }}">
                        {{ __(@$cartItem->product->name) }}
                    </a>

                    <div class="d-flex flex-column">
                        @if (@$cartItem->product->brand)
                            <span class="text-muted">
                                @lang('Brand'):
                                <span>{{ __(@$cartItem->product->brand->name) }}</span>
                            </span>
                        @endif

                        @if ($cartItem->productVariant)
                            <span class="text-muted">
                                @lang('Variant'):
                                <span>{{ __($cartItem->productVariant->name) }}</span>
                            </span>
                        @endif

                        <div class="text-muted cart-item-price">
                            <span>@lang('Price'):</span>
                            <span class="text">{{ gs('cur_sym') . showAmount($price, currencyFormat: false) }}</span>
                        </div>
                    </div>
                </div>

                <div class="cart-item-action">
                    @if (gs('product_wishlist'))
                    <button @class([
                        'addToWishlist text-muted ',
                        'active' => checkWishList($cartItem?->product->id),
                    ]) data-id="{{ $cartItem?->product->id }}"><i class="lar la-heart"></i></button>
                    @endif

                    <button class="removeCart text-muted" data-id="{{ $cartItem->id }}" data-pid="{{ $cartItem->product->id }}" href="javascript:void(0)"><i class="las la-trash"></i></button>
                </div>
            </div>

            <div class="content-bottom">
                <x-frontend.quantity-input :quantity="$cartItem['quantity'] ?? 1" :isDigital="$cartItem->product->is_downloadable" />
                <div class="d-flex flex-column text-center">
                    <span class="total-price fs-sm text-muted">@lang('Total Price')</span>
                    <span class="total-price-text">
                        {{ gs('cur_sym') }}<span class="totalPrice fw-600">{{ getAmount($price * $cartItem['quantity'] ?? 1) }}</span>
                    </span>
                </div>
            </div>

        </div>
    </div>
@endif
