@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="py-60">
        <div class="container">
            @if ($wishlists->count() > 1)
                <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between mb-4">
                    <h4 class="mb-0">@lang('Wishlist Product')</h4>
                    <button class="btn btn-outline--light removeAllBtn" data-bs-toggle="modal" data-bs-target="#deleteModal"> <i class="las la-trash-alt"></i> @lang('Remove All')</button>
                </div>
            @endif
            <div class="row gy-3 gx-3 wishlist-row">
                @forelse ($wishlists as $wishlist)
                    <div class="col-xxl-2 col-lg-3 col-md-4 col-6 grid-control wishlistItem">
                        <x-dynamic-component :component="frontendComponent('product-card')" :product="$wishlist->product" :wishlist="$wishlist" />
                    </div>
                @empty
                    <div class="single-product-item no_data empty-cart__page">
                        <div class="no_data-thumb text-center mb-4">
                            <img src="{{ getImage('assets/images/empty_wishlist.png') }}" alt="empty wishlit">
                        </div>
                        <h6>@lang('Your wishlit is empty')</h6>

                        <a href="{{route('home')}}" class="btn btn-outline--light">@lang('Browse Products')</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="question">@lang('Are you sure to remove all product from wishlist?')</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark btn--sm" data-bs-dismiss="modal" type="button">@lang('No')</button>
                    <button class="btn btn--base btn--sm removeWishlist" data-bs-dismiss="modal" data-id="0" data-page="1" type="submit">@lang('Yes')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
