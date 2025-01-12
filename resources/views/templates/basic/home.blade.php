@extends('Template::layouts.master')
@section('content')
    @include('Template::sections.banner')

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @if (View::exists('Template::sections.' . $sec))
                @include('Template::sections.' . $sec)
            @else
                @php
                    $sectionData = getSectionData($sec, isFrontend: true);
                @endphp

                @if ($sectionData && array_key_exists('section', $sectionData))
                    @include($sectionData['section'], ['data' => @$sectionData['data']])
                @endif
            @endif
        @endforeach
    @endif

@endsection

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/owl.carousel.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/xzoom/xzoom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/xzoom/magnific-popup.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/owl-carousel.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/xzoom.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/magnific-popup.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/setup.js') }}"></script>
@endpush
