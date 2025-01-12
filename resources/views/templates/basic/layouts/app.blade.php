<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link type="image/x-icon" href="{{ siteFavicon() }}" rel="shortcut icon">

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/line-awesome.min.css') }}" rel="stylesheet" />

    @stack('style-lib')

    <link href="{{ asset($activeTemplateTrue . 'css/main.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset($activeTemplateTrue . 'css/color.php?color=' . gs('base_color')) }}" rel="stylesheet">

    @stack('style')
</head>

@php echo loadExtension('google-analytics') @endphp

<body>

    @yield('app')

    <a class="scrollToTop" href="javascript:void(0)"><i class="las la-angle-up"></i></a>
    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card text-center hide">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a href="{{ route('cookie.policy') }}" target="_blank" class="text--base">@lang('Learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a class="btn btn--base w-100 policy h-45" href="javascript:void(0)">@lang('Allow')</a>
            </div>
        </div>
    @endif

    @stack('modal')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.validate.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/lazyload.js') }}"></script>
    @stack('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <script>
        'use strict';
        $('.policy').on('click', function() {
            $.get("{{ route('cookie.accept') }}", function(response) {
                $('.cookies-card').addClass('d-none');
            });
        });
    </script>
    <x-frontend.visermart-script />

    @php echo loadExtension('tawk-chat') @endphp
    @include('partials.notify')
    @if (gs('pn'))
        @include('partials.push_script')
    @endif
    @stack('script')
</body>

</html>
