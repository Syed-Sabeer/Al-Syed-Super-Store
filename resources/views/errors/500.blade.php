<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? '500 | Server Error') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    <!-- bootstrap 4  -->
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <!-- dashdoard main css -->
    <link rel="stylesheet" href="{{ asset('assets/errors/css/main.css') }}">
</head>

<body>
    <!-- error-500 start -->
    <div class="error error-500">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-7 text-center">
                    <div class="error-500-thumb">
                        <img src="{{ asset('assets/errors/images/error-500.png') }}" alt="image">
                        <span class="error-500-thumb-cat">
                            <img src="{{ asset('assets/errors/images/cat.gif') }}" alt="image">
                        </span>
                    </div>

                    <h2 class="title"> @lang('INTERNEL SERVER ERROR')</h2>
                    <p class="description">@lang('We’re sorry, but something went wrong on our end. We\'re actively working to identify and fix the issue. Please try refreshing the page, or come back later. Thank you for your patience.')</p>
                   <a href="{{ route('home') }}" class="cmn-btn mt-4">
                        <span class="icon">
                            <svg fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 483.1 483.1" xml:space="preserve">
                                <g>
                                    <path d="M434.55,418.7l-27.8-313.3c-0.5-6.2-5.7-10.9-12-10.9h-58.6c-0.1-52.1-42.5-94.5-94.6-94.5s-94.5,42.4-94.6,94.5h-58.6 c-6.2,0-11.4,4.7-12,10.9l-27.8,313.3c0,0.4,0,0.7,0,1.1c0,34.9,32.1,63.3,71.5,63.3h243c39.4,0,71.5-28.4,71.5-63.3 C434.55,419.4,434.55,419.1,434.55,418.7z M241.55,24c38.9,0,70.5,31.6,70.6,70.5h-141.2C171.05,55.6,202.65,24,241.55,24z M363.05,459h-243c-26,0-47.2-17.3-47.5-38.8l26.8-301.7h47.6v42.1c0,6.6,5.4,12,12,12s12-5.4,12-12v-42.1h141.2v42.1 c0,6.6,5.4,12,12,12s12-5.4,12-12v-42.1h47.6l26.8,301.8C410.25,441.7,389.05,459,363.05,459z" />
                                </g>
                            </svg>

                        </span>
                        <span class="text"> @lang('Back to Shop')</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- error-500 end -->
</body>

</html>
