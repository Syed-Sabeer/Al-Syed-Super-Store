{% comment %} @extends('Template::layouts.auth')
@section('app')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <div class="auth-form__head pb-0 mb-4">
                        <div class="logo mb-3">
                            <a href="{{ route('home') }}"><img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-2 lh-1">@lang('Verify Email Address')</h5>
                        <p class="short_desc">@lang('A 6 digit verification code sent to your email address') : {{ showEmailAddress($email) }}</p>
                    </div>

                    <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        @include('Template::partials.verification_code')

                        <div class="form-group">
                            <button type="submit" class="btn btn--base h-45 w-100">@lang('Submit')</button>
                        </div>

                        <div class="form-group">
                            @lang('Please check including your Junk/Spam Folder. if not found, you can')
                            <a href="{{ route('user.password.request') }}" class="text--base">@lang('Try to send again')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection {% endcomment %}
