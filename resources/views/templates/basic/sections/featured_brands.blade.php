@php
    $topBrands = \App\Models\Brand::featured()->get();
    $content = getContent('featured_brands.content', true);
@endphp

<section class="my-60">
    <div class="container">
        @if (!blank($topBrands))
            <div class="section-header">
                <h5 class="title">{{ __(@$content->data_values->title) }}</h5>
            </div>

            <div class="small-card">
                @foreach ($topBrands as $brand)
                    <div class="small-card-item text-center">
                        <x-dynamic-component :component="frontendComponent('brand-card')" :brand="$brand" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
