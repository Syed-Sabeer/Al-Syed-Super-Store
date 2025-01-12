@php
    $sliders = getContent('banner.element');
@endphp

@if (!blank($sliders))
    <div class="slider-wrapper overflow-hidden rounded--5">
        <div class="banner-slider owl-theme owl-carousel">
            @foreach ($sliders as $slider)
                <div class="slide-item">
                    <a href="{{ @$slider->data_values->link }}" class="d-block">
                        <img src="{{  frontendImage('banner', @$slider->data_values->slider, '990x480') }}" alt="slider-image">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif


@push('script')
    <script>
        (function($) {
            "use strict";
            $(".banner-slider").owlCarousel({
                items: 1,
                loop: true,
                autoplay: 1,
                nav: false,
                dots: false,
                animateOut: 'fadeOut'
            });
        })(jQuery);
    </script>
@endpush
