@extends('Template::layouts.auth')
@section('app')
    @php
        $content = getContent('register_page.content', true);
    @endphp

    <div class="container">
        <div class="row g-4 gy-lg-0 @if (@$content->data_values->image) justify-content-between @else justify-content-center @endif align-items-center">

            @if (@$content->data_values->image)
                <div class="col-lg-6 col-xxl-7 d-none d-lg-block">
                    <div class="text-center pe-xl-5">
                        <img src="{{ getImage('assets/images/frontend/register_page/' . @$content->data_values->image, '600x840') }}" alt="image" class="img-fluid">
                    </div>
                </div>
            @endif

            <div class=" @if (@$content->data_values->image) col-lg-6 col-xxl-5 @else col-xl-5 col-lg-7 col-md-9 @endif">
                <div class="auth-form @if (!gs('registration')) form-disabled @endif">
                    @if (!gs('registration'))
                        <div class="form-disabled-text">
                            <img src="{{ svg('register') }}" alt="">
                            <div class="mt-3">
                                <p class="text-danger">@lang('Registration is currently disabled.')</p>
                                <a href="{{ route('home') }}" class="btn btn--sm btn--base"> <i class="las la-arrow-left"></i> @lang('Go to Home')</a>
                            </div>
                        </div>
                    @endif
                    <div class="auth-form__head text-center">
                        <div class="logo mb-3">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                        </div>
                        <p class="text-muted">{{ __($content->data_values->title) }}</p>
                    </div>
                    <div class="auth-form__body">
                        <form action="{{ route('user.register') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form--label">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form--label">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form--label" for="email">@lang('Email')</label>
                                <input id="email" type="email" class="form-control form--control checkUser" name="email" value="{{ old('email') }}" required>
                                <small class="text-danger emailExist"></small>
                            </div>

                            <div class="form-group">
                                <label class="form--label" for="password">@lang('Password')</label>
                                <input id="password" type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" required autocomplete="new-password">
                            </div>

                            <div class="form-group">
                                <label class="form--label" for="password-confirm">@lang('Confirm Password')</label>
                                <input id="password-confirm" class="form-control form--control" type="password" name="password_confirmation" required>
                            </div>

                            <x-captcha />

                            @if (gs('agree'))
                                @php
                                    $policyPages = getContent('policy_pages.element', false, null, true);
                                @endphp
                                <div class="form-group">
                                    <div class="form-check form--check d-flex gap-2">
                                        <input class="form-check-input" type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                                        <label class="form-check-label m-0 p-0" for="agree">
                                            @lang('I agree with')
                                            @foreach ($policyPages as $policy)
                                                <a class="text--base" href="{{ route('policy.pages', $policy->slug) }}" target="_blank">{{ __($policy->data_values->title) }}</a>
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="aurt-form-btn">
                                <button class="btn btn--md btn--base h-45 w-100">@lang('Register')</button>
                            </div>
                            <p class="mt-2 mb-0">
                                @lang('Already have an account?') <a href="{{ route('user.login') }}" class="t-link t-link--base text--base">@lang('Login')</a>
                            </p>

                        </form>
                        @include($activeTemplate . 'partials.social_login')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.emailExist`).text(`@lang('Email already exist')`);
                    } else {
                        $(`.emailExist`).text('');
                    }
                });
            });

            @if (!gs('registration'))
                notify('error', "@lang('Registration is currently disabled')");
            @endif
        })(jQuery);
    </script>
@endpush

@if (!gs('registration'))
    @push('style')
        <style>
            .form-disabled {
                overflow: hidden;
                position: relative;
            }

            .form-disabled::after {
                content: "";
                position: absolute;
                height: 100%;
                width: 100%;
                background-color: rgba(255, 255, 255, 0.2);
                top: 0;
                left: 0;
                backdrop-filter: blur(2px);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                z-index: 99;
            }

            .form-disabled-text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 991;
                font-size: 1rem;
                height: auto;
                width: 100%;
                text-align: center;
                line-height: 1.2;
            }

            .form-disabled__desc {
                font-size: 1.125rem;
                max-width: 450px;
                margin: 0 auto;
                margin-top: 20px;
            }
        </style>
    @endpush
@endif
