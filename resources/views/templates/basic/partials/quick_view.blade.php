<div class="row g-4 g-xl-5 product-details-container">
    <div class="col-md-5" id="variantImages">
        @include($activeTemplate . 'partials.product_images')
    </div>

    <div class="col-md-7">
        <div class="product-details">
            <div class="product-header">
                <h3 class="product-title">{{ __($product->name) }}</h3>

                @if (gs('product_review'))
                    <div class="ratings-area">
                        <span class="ratings">
                            @php echo __(displayRating($product->reviews_avg_rating)) @endphp
                        </span>
                        @if ($product->reviews_count)
                            <span>({{ $product->reviews_count }})</span>
                        @else
                            <span> | @lang('No Review')</span>
                        @endif
                    </div>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-2 product-detail-price">
                    <span class="product-price" id="productPrice">
                        @php echo $product->formattedPrice();  @endphp
                    </span>

                    <span id="stockBadge"></span>
                </div>
            </div>

            @if ($product->summary)
                <div class="product-summary">
                    {{ __($product->summary) }}
                </div>
            @endif

            <div class="product-types d-flex flex-column">
                <span>
                    <b class="product-details-label">@lang('Categories'): </b>
                    @forelse ($product->categories as $category)
                        <a href="{{ $category->shopLink() }}">{{ __($category->name) }}</a>
                        @if (!$loop->last)
                            /
                        @endif
                    @empty
                        @lang('Uncategorized')
                    @endforelse
                </span>

                <span>
                    <b class="product-details-label">@lang('Brand'):</b>
                    @if ($product->brand)
                        <a href="{{ $product->brand->shopLink() }}">{{ __($product->brand->name) }}</a>
                    @else
                        @lang('Non Brand')
                    @endif
                </span>

                <span>
                    <b class="product-details-label">@lang('SKU'):</b> <span id="productSku">{{ $product->sku ?? __('Not available') }}</span>
                </span>

            </div>

            @if ($product->product_type == Status::PRODUCT_TYPE_VARIABLE && $product->attributes->count())
                <div class="product-attribute position-relative">
                    <div class="ajax-preloader d-none"></div>
                    @foreach ($product->attributes as $attribute)
                        @php
                            $attributeValues = $product->attributeValues->where('attribute_id', $attribute->id);
                            $attributeTypeClass = $attribute->type == Status::ATTRIBUTE_TYPE_TEXT ? 'product-size-area' : 'product-color-area';
                        @endphp

                        <div class="attribute-value-wrapper attributeValueArea">
                            <span class="attribute-name fw-600">{{ __(@$attribute->name) }}:</span>

                            @foreach ($attributeValues as $attributeValue)
                                @php
                                    $data = ['id' => $attributeValue->id, 'type' => $attribute->type];
                                @endphp
                                <button class="attribute-value attributeBtn" data-attribute='@json($data)' data-media_id="{{ $attributeValue->pivot->media_id }}">
                                    @if ($attribute->type == Status::ATTRIBUTE_TYPE_TEXT)
                                        <span class="text-attribute">{{ $attributeValue->value }}</span>
                                    @elseif($attribute->type == Status::ATTRIBUTE_TYPE_COLOR)
                                        <span class="color-attribute colorAttribute" data-color="{{ $attributeValue->value }}" style="background:#{{ $attributeValue->value }}"></span>
                                    @else
                                        <span class="color-attribute bg--img" data-media_id="{{ $attributeValue->pivot->media_id }}" data-background="{{ getImage(getFilePath('attribute') . '/' . $attributeValue->value) }}" />
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="d-flex gap-2 flex-column">
                <div class="product-add-to-cart">
                    <x-frontend.quantity-input :isDigital="$product->is_downloadable" data-update="no" />
                    <button class="btn btn--base btn--sm addToCart flex-shrink-0" data-id="{{ $product->id }}" data-product_type="{{ $product->product_type }}" @disabled(!$product->salePrice()) type="button">@lang('Add To Cart')</button>
                </div>
                <div class="product-wishlist d-flex gap-2 mt-3">

                    @if (gs('product_wishlist'))
                        <button class="add-to-wishlist-btn @if (checkWishList($product->id)) active @endif addToWishlist" data-id="{{ $product->id }}">
                            <span class="wish-icon"></span> @lang('Wishlist')
                        </button>
                    @endif

                    @if ($product->product_type_id && gs('product_compare'))
                        <button class="add-to-wishlist-btn  @if (checkCompareList($product->id)) active @endif addToCompare" data-id="{{ $product->id }}">
                            <i class="las la-exchange-alt compare-icon"></i> @lang('Compare')
                        </button>
                    @endif
                </div>

            </div>

            @if ($quickView)
                <div>
                    <a class="btn btn-sm btn--base outline" href="{{ $product->link() }}">@lang('View Details')</a>
                </div>
            @endif

            @if (!$quickView)
                <x-frontend.product-sharer :product="$product" />
            @endif
        </div>
    </div>
</div>

@if (!$quickView)
    @push('script')
    @endif
    <script src="{{ asset($activeTemplateTrue . 'js/product_details.js') }}?{{ time() }}"></script>

    <script>
        "use strict";

        $('.product-details-container').productDetails({
            productId: @json($product->id),
            totalAttributes: @json($product->attributes->count()),
            stockQuantity: @json($product->totalInStock()),
            trackInventory: @json($product->track_inventory == Status::YES),
            showStockQuantity: @json($product->show_stock && $product->track_inventory && $product->product_type == Status::PRODUCT_TYPE_SIMPLE),
            variantImageLoadUrl: "{{ route('product.variant.image', [':productId', ':attributeId']) }}",
            checkStockUrl: "{{ route('product.variant.stock', $product->slug) }}"
        });
    </script>

    @if (!$quickView)
    @endpush
@endif
