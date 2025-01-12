@php
    $content = getContent('about_us.content', true);
@endphp


@if (@$content->data_values->banner_image || @$content->data_values->banner_heading)
    <div class="banner" @if (@$content->data_values->banner_image) style="background-image: url('{{ getImage('assets/images/frontend/about_us/' . @$content->data_values->banner_image, '1900x460') }}');" @endif>
        <div class="banner__content">
            <div class="container">
                @if (@$content->data_values->banner_heading)
                    <div class="row justify-content-center">
                        <div class="col-md-7 col-lg-6 col-xxl-5">
                            <div class="about-banner">
                                <h1 class="about-banner__title">{{ __(@$content->data_values->banner_heading) }}</h1>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<div class="my-60">
    <div class="container">
        <div class="row g-4 justify-content-between align-items-center">
            @if (@$content->data_values->image)
                <div class="col-md-5 col-lg-6">
                    <div class="text-center">
                        <img src="{{ getImage(null) }}" data-src="{{ getImage('assets/images/frontend/about_us/' . @$content->data_values->image, '686x528') }}" alt="image" class="img-fluid lazyload">
                    </div>
                </div>
            @endif

            @if (@$content->data_values->heading || @$content->data_values->subheading || (!blank(strip_tags($content->data_values->description))) || $content->data_values->button_text)
                <div class="@if(@$content->data_values->image) col-md-7 col-lg-6 @else col-12 @endif">
                    <div class="about-description-wrapper">
                        @if (@$content->data_values->heading)
                            <span class="section__subtitle">{{ __(@$content->data_values->heading) }}</span>
                        @endif
                        @if (@$content->data_values->subheading)
                            <h2 class="section__title mt-2">{{ __(@$content->data_values->subheading) }}</h2>
                        @endif
                        @if (!blank(strip_tags(@$content->data_values->description)))
                            <div class="description">
                                @php echo @$content->data_values->description; @endphp
                            </div>
                        @endif
                        @if (@$content->data_values->button_text)
                            <a href="{{ $content->data_values->button_link }}" class="btn btn--base btn--lg sm-text mt-4">{{ $content->data_values->button_text }}</a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
