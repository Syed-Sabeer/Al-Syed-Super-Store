@php
    $topSellingProducts = \App\Models\Product::topSales(12);
@endphp

@if ($topSellingProducts->count())
    @php
        $content = getContent('top_selling_products.content', true);
    @endphp
    <section class="site-section best-selling-section my-60">
        <div class="container">
            <div class="section-header left-style">
                <h5 class="title">{{ __(@$content->data_values->title) }}</h5>
            </div>
            <div class="top-selling-products">
                @foreach ($topSellingProducts as $product)
                    <div class="best-sell-item">
                        <div class="best-sell-inner d-flex flex-wrap">
                            <div class="thumb">
                                <a href="{{ $product->link() }}">
                                    <img src="{{ getImage(null) }}" data-src="{{ $product->mainImage() }}" class="lazyload" alt="products">
                                </a>
                            </div>

                            <div class="content">
                                <h6 class="title">
                                    <a href="{{ $product->link() }}">{{ __($product->name) }}</a>
                                </h6>

                                @if(gs('product_review'))
                                <div class="ratings-area mb-1">
                                    <span class="ratings">
                                        @php echo displayRating($product->reviews_avg_rating) @endphp
                                    </span>
                                    @if($product->reviews_count)
                                    <span>({{ $product->reviews_count }})</span>
                                    @endif
                                </div>
                                @endif

                                <div class="price fw-500">
                                    @php
                                        echo $product->formattedPrice();
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
