<div class="position-relative user-dropdown-wrapper">
    <button type="button" class="user-account @guest login-btn @else user-dropdown-btn @endguest button" @guest data-bs-toggle="modal" data-bs-target="#loginModal" @endguest>
        <span class="icon">
            <i class="las la-user"></i>
        </span>
        @auth
            <span>@lang('My Account') <span class="ms-1"><i class="la la-angle-down"></i></span> </span>
        @else
            <span>@lang('My Account')</span>
        @endauth
    </button>

    @auth
        <div class="before-dropdown-menu">
            <ul class="login-user-menu">
                <li>
                    <a href="{{ route('user.home') }}" class="{{ menuActive('user.home') }}"> <i class="las la-home"></i>@lang('Dashboard')</a>
                </li>

                <li>
                    <a href="{{ route('user.orders', 'all') }}" class="{{ menuActive('user.orders') }}"><i class="las la-list"></i>@lang('Orders')</a>
                </li>

                <li>
                    <a href="{{ route('user.profile.setting') }}" class="{{ menuActive('user.profile.setting') }}"><i class="las la-user-alt"></i>@lang('Profile')</a>
                </li>

                <li>
                    <a href="{{ route('user.change.password') }}" class="{{ menuActive('user.change.password') }}"><i class="las la-key"></i>@lang('Change Password')</a>
                </li>

                <li>
                    <a href="{{ route('user.logout') }}"><i class="la la-sign-out"></i>@lang('Sign Out')</a>
                </li>
            </ul>
        </div>
    @endauth
</div>
