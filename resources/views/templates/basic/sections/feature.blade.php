@php
    $content = getContent('feature.content', true);
    $elements = getContent('feature.element');
    $firstHalf = [];
    $secondHalf = [];

    if ($elements->count() > 0) {
        $elements = $elements->chunk(round($elements->count() / 2));
        $firstHalf = $elements[0];
        $secondHalf = $elements[1];
    }
@endphp

<div class="my-60">
    <div class="section__head">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center">
                        <span class="section__subtitle">{{ __(@$content->data_values->heading) }}</span>
                        <h2 class="section__title my-3 text-center justify-content-center">{{ __(@$content->data_values->subheading) }}</h2>
                        <p class="sm-text text-center mb-0">{{ __(@$content->data_values->description) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row gy-3 justify-content-center">
            <div class="@if($content->data_values->image) col-lg-4 @else col-lg-6 @endif">
                <ul class="list feature-card--list">
                    @foreach ($firstHalf as $item)
                        @include('Template::partials.feature_item')
                    @endforeach
                </ul>
            </div>
            @if($content->data_values->image)
            <div class="col-lg-4 order-3 order-lg-2 d-none d-lg-block">
                <div class="text-center">
                    <img src="{{ getImage(null) }}" data-src="{{ getImage('assets/images/frontend/feature/' . @$content->data_values->image, '300x410') }}" alt="image" class="img-fluid lazyload">
                </div>
            </div>
            @endif
            <div class="@if($content->data_values->image) col-lg-4 order-2 order-lg-3 @else col-lg-6 @endif">
                <ul class="list feature-card--list">
                    @foreach ($secondHalf as $item)
                        @include('Template::partials.feature_item')
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
