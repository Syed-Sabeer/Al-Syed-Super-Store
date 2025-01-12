<div class="row g-3" id="grid-view">
    @foreach ($products as $product)
        <div class="col-6 grid-control col-md-4 col-lg-3">
            <x-dynamic-component :component="frontendComponent('product-card')" :product="$product" />
        </div>
    @endforeach

    @if ($products->count() == 0)
        <div class="col-lg-12">
            <div class="empty-message text-center">
                <img src="{{ getImage('assets/images/empty.png') }}" alt="empty">
                <h5 class="mt-3 text-muted">@lang('No Products Found')</h5>
                @if (request()->has('search'))
                    <p class="message">
                        @lang('Your search didn\'t match any products.')
                        <br>
                        @lang('Please try again.')
                    </p>
                @elseif(Route::is('product.by.category'))
                    <p class="message">
                        @lang('Sorry, we currently do not have any products available in this category.')
                    </p>
                @endif
            </div>
        </div>
    @endif
</div>

@if ($products->hasPages())
    <div class="mt-4 d-sm-block d-flex justify-content-end">
        {{ paginateLinks($products) }}
    </div>
@endif
