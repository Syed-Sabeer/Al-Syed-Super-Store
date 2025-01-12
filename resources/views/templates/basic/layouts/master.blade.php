@extends('Template::layouts.app')
@section('app')
    <div class="body-overlay" id="body-overlay"></div>
    @include('Template::partials.preloader')
    @include('Template::partials.header')
    <main>
        @yield('content')
    </main>

    @if (!Route::is('cart.page'))
        <div class="site-sidebar cart-sidebar-area" id="cart-sidebar-area">
            <button class="sidebar-close-btn"><i class="las la-times"></i></button>
            <div class="top-content d-flex gap-2">
                <h5 class="cart-sidebar-area__title">@lang('My Cart')</h5> <a href="{{ route('cart.page') }}" class="text-muted text-decoration-underline">@lang('Cart Page')</a>
            </div>
            <div class="cart-products cart--products"></div>
        </div>
    @endif

    @if (gs('product_wishlist'))
        <div class="site-sidebar cart-sidebar-area wishlist-sidebar" id="wish-sidebar-area">
            <button class="sidebar-close-btn"><i class="las la-times"></i></button>
            <div class="top-content d-flex gap-2">
                <h5 class="cart-sidebar-area__title">@lang('My Wishlist')</h5> <a href="{{ route('wishlist.page') }}" class="text-muted text-decoration-underline">@lang('Wishlist Page')</a>
            </div>
            <div class="cart-products wish--products"></div>
        </div>
    @endif

    @auth
        <div class="site-sidebar sidebar-nav" id="authSidebarMenu">
            <button type="button" class="sidebar-close-btn"><i class="las la-times"></i></button>

            <ul class="text--white login-user-menu">
                @include('Template::user.partials.sidebar')
            </ul>
        </div>
    @endauth

    @include('Template::partials.footer')

    @guest
        <!-- Modal -->
        <div class="modal custom--modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="modal-title" id="loginModalTitle">@lang('Login to your account')</h5>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                        <div class="login-wrapper">
                            <form method="POST" action="{{ route('user.login') }}" class="sign-in-form">
                                @csrf
                                <div class="form-group">
                                    <label class="form--label" for="login-username">@lang('Username')</label>
                                    <input type="text" class="form--control" name="username" id="login-username" value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form--label" for="login-pass">@lang('Password')</label>
                                    <input type="password" class="form--control" name="password" id="login-pass">
                                </div>

                                <div class="form-group">
                                    <div class="d-flex gap-1 flex-wrap justify-content-between">
                                        <div class="form-check form--check d-flex gap-1 align-items-center mb-0">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label mb-0 lh-1" for="remember">
                                                @lang('Remember Me')
                                            </label>
                                        </div>

                                        <a href="{{ route('user.password.request') }}" class="t-link d-block text-end text--base heading-clr sm-text fw-md">
                                            @lang('Forgot Password?')
                                        </a>
                                    </div>
                                </div>

                                <x-captcha></x-captcha>


                                <button type="submit" class="btn btn--base w-100 h-45">@lang('Login')</button>

                                <p class="create-accounts mb-0 mt-2">
                                    <span class="text-dark">@lang('Don\'t have an account?') <a href="{{ route('user.register') }}" class="text--base">@lang('Create An Account')</a> </span>
                                </p>
                            </form>

                            @include('Template::partials.social_login')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
@endsection
