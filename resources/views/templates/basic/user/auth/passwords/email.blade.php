@extends('Template::layouts.auth')
@section('app')
    @php
        $content = getContent('forgot_password_page.content', true);
    @endphp


    <div class="container">
        <div class="row g-4 gy-lg-0  @if($content->data_values->image) justify-content-between @else justify-content-center @endif flex-wrap-reverse align-items-center">
            @if($content->data_values->image)
                <div class="col-lg-6 col-xxl-7 d-none d-lg-block">
                    <div class="text-center pe-xl-5">
                        <img src="{{ frontendImage('forgot_password_page', @$content->data_values->image, '600x840') }}" alt="image" class="img-fluid">
                    </div>
                </div>
            @endif

            <div class="@if($content->data_values->image) col-lg-6 col-xxl-5 @else col-xl-5 col-lg-7 col-md-9 @endif">
                <div class="auth-form">
                    <div class="auth-form__head pb-0 mb-4">
                        <div class="logo mb-4">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                        </div>
                    </div>
                    <div class="auth-form__body">

                        <div class="my-4">
                            <h5 class="mb-2 lh-1">{{ __($content->data_values->title) }}</h5>
                            <p class="short_desc">{{ __($content->data_values->description) }}</p>
                        </div>

                        <form method="POST" action="{{ route('user.password.email') }}" class="verify-gcaptcha">
                            @csrf

                            <div class="row">
                                <div class="col-12 form-group">
                                    <label for="account-identifier" class="form-label">@lang('Email or Username')</label>
                                    <input class="form-control form--control" type="text" name="value" value="{{ old('value') }}" autocomplete="email" id="account-identifier" placeholder="@lang('Enter your email or username')" required autofocus>
                                </div>

                                <div class="col-12">
                                    <x-captcha />
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn--base h-45 w-100">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
