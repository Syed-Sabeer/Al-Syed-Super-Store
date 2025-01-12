@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="py-80">
        <div class="container">
            @if ($offer->products->count())
                <h5 class="mb-4">{{ __($pageTitle) }}</h5>
                 <div class="product-wrapper">
                    @foreach ($offer->products as $product)
                        <x-dynamic-component :component="frontendComponent('product-card')" :product="$product" />
                    @endforeach
                </div>
            @else
                <x-dynamic-component :component="frontendComponent('empty-message')" />
            @endif
        </div>
    </div>
@endsection
