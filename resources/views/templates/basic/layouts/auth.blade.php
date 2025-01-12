<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet" />

    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/color.php?color=' . gs('base_color')) }}" rel="stylesheet">

    <link type="image/x-icon" href="{{ siteFavicon() }}" rel="shortcut icon">
    @stack('style-lib')
    @stack('style')
</head>

<body class="pb-0">
    @include('Template::partials.preloader')
    @php
        $bgImage = getContent('auth_pages_bg.content', true);
    @endphp

    <div class="auth-section auth-section--light py-60" @if(@$bgImage->data_values->image) style="background-image: url('{{ frontendImage('auth_pages_bg', @$bgImage->data_values->image) }}');" @endif>
        @yield('app')
    </div>

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    @include('partials.notify')
    @stack('script-lib')
    @stack('script')
</body>

</html>
