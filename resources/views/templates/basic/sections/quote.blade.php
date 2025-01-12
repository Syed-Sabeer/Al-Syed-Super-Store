@php
    $content = getContent('quote.content', true);
@endphp

<section class="testimonial">
    <div class="container justify-content-center">
        <div class="row align-items-center">
            @if (@$content->data_values->image)
                <div class="col-md-4">
                    <div class="text-center d-none d-md-block">
                        <img src="{{ frontendImage('quote', @$content->data_values->image, '265x600') }}" alt="image" class="img-fluid testimonial__img" />
                    </div>
                </div>
            @endif
            <div class="col-md-7">
                <div class="testimonial__content py-5">
                    @if (@$content->data_values->quote)
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                            <g>
                                <path d="M26 110a2.16 2.16 0 0 1-.56-.08A2 2 0 0 1 24 108V69.13H2a2 2 0 0 1-2-2V20a2 2 0 0 1 2-2h50a2 2 0 0 1 2 2v47.09a2 2 0 0 1-.31 1.07l-26 40.91A2 2 0 0 1 26 110zM4 65.09h22a2 2 0 0 1 2 2v34L50 66.5V22H4zM100 110a2.16 2.16 0 0 1-.56-.08A2 2 0 0 1 98 108V69.13H76a2 2 0 0 1-2-2V20a2 2 0 0 1 2-2h50a2 2 0 0 1 2 2v47.09a2 2 0 0 1-.31 1.07l-26 40.91a2 2 0 0 1-1.69.93zM78 65.09h22a2 2 0 0 1 2 2v34l22-34.59V22H78z" fill="hsl(var(--base))" opacity="1" data-original="#000000" class=""></path>
                            </g>
                        </svg>
                        <p class="testimonial__text">{{ __(@$content->data_values->quote) }}</p>
                    @endif
                    
                    @if (@$content->data_values->name || @$content->data_values->designation)
                        <div class="testimonial__footer">
                            @if ($content->data_values->name)
                                <h2 class="testimonial__title">{{ __(@$content->data_values->name) }}</h2>
                            @endif
                            @if ($content->data_values->designation)
                                <span class="testimonial__cite"> {{ __(@$content->data_values->designation) }} </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
