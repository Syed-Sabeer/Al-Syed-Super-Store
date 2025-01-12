<div class="cart-items-wrapper">
    @forelse ($wishlist as $wishlistitem)
        <div class="single-product-item">
            <div class="thumb d-block">
                <img src="{{ getImage(null) }}" data-src="{{ $wishlistitem->product->mainImage() }}" class="lazyload" alt="product-image">
            </div>

            <div class="content">
                <div class="content-top">
                    <div class="content-top-left">
                        <a class="title d-block" href="{{ $wishlistitem->product->link() }}">{{ strLimit(__($wishlistitem->product->name), 24) }}</a>

                        <div class="text-muted cart-item-price">
                            <span>@lang('Price'):</span>
                            <span class="text">@php echo $wishlistitem->product->formattedPrice();  @endphp</span>
                        </div>
                    </div>


                    <div class="cart-item-action">
                        <button class="removeWishlist text-muted" data-page="0" data-id="{{ $wishlistitem->id }}" data-pid="{{ $wishlistitem->product->id }}"><i class="las la-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="single-product-item no_data">
            <div class="no_data-thumb text-center">
                <img src="{{ getImage('assets/images/empty_wishlist.png') }}" alt="">
            </div>
            <h6 class="mt-2">@lang('Your wishlist is empty')</h6>
        </div>
    @endforelse
    @if ($wishlistCount && $wishlistCount > 9)
        <span class="btn-wrapper text-center d-block mt-3">
            <a href="{{ route('wishlist.page') }}" class="btn btn--sm btn--base">@lang('And')
                {{ $wishlistCount - 9 }} @lang('More')</a>
        </span>
    @endif
</div>
