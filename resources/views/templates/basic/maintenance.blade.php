<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .maintenance-page {
            display: grid;
            place-content: center;
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>

<body>
    @php
        $content = \App\Models\Frontend::where('data_keys', 'maintenance.data')->first();
    @endphp
    <section class="maintenance-page flex-column justify-content-center">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-8 text-center">
                    <div class="row justify-content-center">
                        <div class="col-sm-6 col-8 col-lg-8">
                            <img class="img-fluid mx-auto mb-5" src="{{ getImage(getFilePath('maintenance') . '/' . @$content->data_values->image, '660x320') }}" alt="@lang('image')">
                        </div>
                    </div>
                    <div>@php echo @$content->data_values->description @endphp</div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
