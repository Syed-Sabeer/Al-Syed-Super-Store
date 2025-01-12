@if ($data && $data->products->count())

    @php
        $offer = $data;
        $startTimestamp = $offer->starts_from->timestamp * 1000;
        $endTimestamp = $offer->ends_at->timestamp * 1000;
    @endphp

    <section class="flash-sell-section my-60" data-starts-at="{{ $startTimestamp }}" data-ends-at="{{ $endTimestamp }}">
        <div class="container">
            <div class="section-header left-style">
                <h5 class="title">{{ __($offer->name) }}</h5>

                @if ((!$offer->banner || $offer->show_banner == Status::NO) && $offer->show_countdown)
                    <div class="section-countdown">
                        <x-dynamic-component :component="frontendComponent('offer-countdown')" />
                    </div>
                @endif
            </div>
            <div class="d-flex flex-wrap offer-wrapper">

                @if ($offer->banner && $offer->show_banner)
                    <div class="offer-banner">
                        @if ($offer->show_countdown)
                            <div class="offer-countdown">
                                <x-dynamic-component :component="frontendComponent('offer-countdown')" />
                            </div>
                        @endif
                        <a href="{{ route('offer.products', encrypt($offer->id)) }}" class="h-100"> <img class="w-100 rounded--5" src="{{ getImage(getFilePath('offerBanner') . '/' . $offer->banner, getFileSize('offerBanner')) }}" alt="offer-banner"></a>
                    </div>

                    <div class="product-slider-wrapper flex-grow-1">
                        <div class="product-with-banner owl-carousel owl-theme">
                            @foreach ($offer->products?->sortByDesc('id')->chunk(2) as $chunk)
                                <div class="offer-product">
                                    @foreach ($chunk as $product)
                                        <x-dynamic-component :component="frontendComponent('product-card')" :product="$product" :showRating="false" :showCartButton="false" />
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="product-wrapper">
                        @foreach ($offer->products?->sortByDesc('id') as $product)
                            <x-dynamic-component :component="frontendComponent('product-card')" :product="$product" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif

