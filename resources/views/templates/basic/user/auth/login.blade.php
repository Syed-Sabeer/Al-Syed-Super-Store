@extends('Template::layouts.auth')
@section('app')
    @php
        $loginContent = getContent('login_page.content', true);
    @endphp
    <div class="container">
        <div class="row g-4 gy-lg-0 @if ($loginContent->data_values->image) justify-content-between @else justify-content-center @endif flex-wrap-reverse align-items-center">
            @if ($loginContent->data_values->image)
                <div class="col-lg-6 col-xxl-7 d-none d-lg-block">
                    <div class="text-center pe-xl-5">
                        <img src="{{ frontendImage('login_page', @$loginContent->data_values->image, '600x840') }}" alt="image" class="img-fluid">
                    </div>
                </div>
            @endif

            <div class="@if ($loginContent->data_values->image) col-lg-6 col-xxl-5 @else col-xl-5 col-lg-7 col-md-9 @endif">
                <div class="auth-form">
                    <div class="auth-form__head text-center">
                        <div class="logo">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                        </div>

                    </div>
                    <div class="auth-form__body">
                        <form method="POST" action="{{ route('user.login') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form--label">@lang('Username')</label>
                                <input class="form--control" type="text" name="username" value="{{ old('username') }}" placeholder="@lang('Username')" required autofocus>
                            </div>
                            <div class="form-group">
                                <label class="form--label" for="password">@lang('Password')</label>
                                <input id="password" type="password" name="password" class="form--control" placeholder="@lang('Enter Your Password')" required autocomplete="current-password">
                            </div>

                            <div class="form-group">
                                <div class="d-flex gap-1 flex-wrap justify-content-between">
                                    <div class="form-check form--check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">
                                            @lang('Remember Me')
                                        </label>
                                    </div>

                                    <a href="{{ route('user.password.request') }}" class="t-link d-block text-end text--base heading-clr sm-text fw-md">
                                        @lang('Forgot Password?')
                                    </a>
                                </div>
                            </div>

                            <x-captcha />

                            <button class="btn btn--md btn--base sm-text h-45 w-100" type="submit" id="recaptcha">@lang('Login Account')</button>

                            @if (Route::has('user.register'))
                                <p class="mt-2 mb-0">
                                    @lang('Don\'t have an account') ? <a href="{{ route('user.register') }}" class="t-link t-link--base text--base">@lang('Create An Account')</a>
                                </p>
                            @endif
                        </form>
                        @include('Template::partials.social_login')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
