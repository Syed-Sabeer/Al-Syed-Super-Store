@extends('Template::layouts.auth')
@section('app')
    @php
        $content = getContent('reset_password_page.content', true);
    @endphp


    <div class="container">
        <div class="row g-4 gy-lg-0 @if (@$content->data_values->image) justify-content-between @else justify-content-center @endif flex-wrap-reverse align-items-center">
            @if (@$content->data_values->image)
                <div class="col-lg-6 col-xxl-7 d-none d-lg-block">
                    <div class="text-center pe-xl-5">
                        <img src="{{ frontendImage('reset_password_page', @$content->data_values->image, '600x840') }}" alt="@lang('login-bg')">
                    </div>
                </div>
            @endif

            <div class="@if(@$content->data_values->image) col-lg-6 col-xxl-5 @else col-xl-5 col-lg-7 col-md-9 @endif">
                <div class="auth-form">
                    <div class="auth-form__head pb-0 mb-4">
                        <div class="logo mb-3">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                        </div>
                    </div>

                    <div class="auth-form__body">
                        <div class="my-4">
                            <h5 class="mb-2 lh-1">{{ __($content->data_values->title) }}</h5>
                            <p class="short_desc">{{ __($content->data_values->description) }}</p>
                        </div>

                        <form method="POST" action="{{ route('user.password.update') }}" class="contact-form">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label class="form-label">@lang('Password')</label>
                                <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" placeholder="@lang('Enter password')" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">@lang('Confirm Password')</label>
                                <input type="password" class="form-control form--control" name="password_confirmation" placeholder="@lang('Enter confirmation password')" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100 h-45"> @lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            @if (gs('secure_password'))
                $('input[name=password]').on('input', function() {
                    secure_password($(this));
                });

                $('[name=password]').focus(function() {
                    $(this).closest('.form-group').addClass('hover-input-popup');
                });

                $('[name=password]').focusout(function() {
                    $(this).closest('.form-group').removeClass('hover-input-popup');
                });
            @endif
        })(jQuery);
    </script>
@endpush
