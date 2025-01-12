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
    <a href="{{ route('user.deposit.history') }}" class="{{ menuActive('user.deposit.history') }}"><i class="las la-money-bill-wave"></i>@lang('Payment Log')</a>
</li>

@if(gs('product_review'))
<li>
    <a href="{{ route('user.review.index') }}" class="{{ menuActive('user.review.index') }}"><i class="lar la-star"></i> @lang('Review Products')</a>
</li>
@endif
<li>
    <a href="{{ route('user.shipping.address') }}" class="{{ menuActive('user.shipping.address') }}"><i class="las la-map-marker-alt"></i> @lang('Shipping Address')</a>
</li>

<li>
    <a href="{{ route('ticket.index') }}" class="{{ menuActive('ticket.*') }}"><i class="la la-ticket"></i> @lang('Support Tickets')</a>
</li>

<li>
    <a href="{{ route('user.notifications') }}" class="{{ menuActive('user.notifications*') }}"><i class="la la-bell"></i> @lang('All Notifications')</a>
</li>

<li>
    <a href="{{ route('user.logout') }}" class="signout-btn"><i class="la la-sign-out"></i>@lang('Sign Out')</a>
</li>
