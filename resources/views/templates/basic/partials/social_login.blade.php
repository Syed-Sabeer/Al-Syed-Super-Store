@php
    $text = Route::is('user.register') ? 'Register with Your ' : 'Login';
@endphp


@if (@gs('socialite_credentials')->linkedin->status || @gs('socialite_credentials')->facebook->status == Status::ENABLE || @gs('socialite_credentials')->google->status == Status::ENABLE)
    <div class="auth-divide col-12">
        <span class="auth-divide-text">@lang('Or')</span>
    </div>
@endif

<ul class="social-auth-list col-12">
    @if (@gs('socialite_credentials')->google->status == Status::ENABLE)
        <li class="social-auth-item">
            <a href="{{ route('user.social.login', 'google') }}" class="social-auth-link">
                <span class="icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/google.svg') }}" alt="Google">
                </span>
                <span class="text">
                    @lang("$text Google Account")
                </span>
            </a>
        </li>
    @endif
    @if (@gs('socialite_credentials')->facebook->status == Status::ENABLE)
        <li class="social-auth-item">
            <a href="{{ route('user.social.login', 'facebook') }}" class="social-auth-link">
                <span class="icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt="Facebook">
                </span>
                <span class="text">
                    @lang("$text Facebook Account")
                </span>
            </a>
        </li>
    @endif
    @if (@gs('socialite_credentials')->linkedin->status == Status::ENABLE)
        <li class="social-auth-item">
            <a href="{{ route('user.social.login', 'linkedin') }}" class="social-auth-link">
                <span class="icon">
                    <img src="{{ asset($activeTemplateTrue . 'images/linkdin.svg') }}" alt="Linkedin">
                </span>
                <span class="text">
                    @lang("$text Linkedin Account")
                </span>
            </a>
        </li>
    @endif
</ul>

