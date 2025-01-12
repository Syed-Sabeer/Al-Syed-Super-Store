@php
    $content = getContent('banner.content', true);
@endphp

<div class="container banner-container @if (gs('homepage_layout') == 'full_width_banner') single-banner @endif">
    <div class="hero-section mb-60">
        @if (gs('homepage_layout') == 'sidebar_menu')
            @include('Template::partials.left_category_menu', ['limit' => 13])
        @endif

        <div class="hero-slider">
            @include('Template::partials.banner_sliders')
            @include('Template::partials.banner_categories', ['fixedBanner' => $content->data_values?->fixed_banner])
        </div>

        @if (gs('homepage_layout') == 'sidebar_menu' && @$content->data_values->fixed_banner)
            <div class="hero-banner">
                <a href="{{ @$content->data_values->fixed_banner_link }}" class="d-inline"><img class="w-100 h-100" src="{{ frontendImage('banner', @$content->data_values->fixed_banner, '300x510') }}" alt=""></a>
            </div>
        @endif
    </div>
</div>

@push('style')
    <style>
        .left-site-category {
            height: 100%;
        }
    </style>
@endpush


