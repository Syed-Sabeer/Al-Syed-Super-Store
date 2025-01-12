@php
    $elements = getContent('services.element', orderById: true);
@endphp

@if($elements->count())
<section class="py-60 footer-top-bg">
    <div class="container">
        <div class="services-wrapper">
            @foreach ($elements as $element)
                <div class="services-card">
                    <div class="services-card__icon">
                        <img src="{{ getImage('assets/images/frontend/services/' . $element->data_values->image, '60x50') }}" alt="">
                    </div>
                    <div class="services-card__content">
                        <h5 class="services-card__text mb-1">{{ __($element->data_values->title) }}</h5>
                        <span class="sub-title">{{ __($element->data_values->subtitle) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
