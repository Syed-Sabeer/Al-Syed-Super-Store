@php
    $headerTwo = App\Models\Frontend::where('data_keys', 'header_two.content')->first()?->data_values;
    $headerTwoKeys = array_keys((array) $headerTwo->group);
    $firstLetters = array_map(function ($key) {
        return $key[0];
    }, $headerTwoKeys);

    $headerTwoLayoutClass = 'middle-menu-' . implode('', $firstLetters);
@endphp

@if (@$headerTwo->status == 'on')
    <div class="header-middle">
        <div class="container">
            <div class="d-flex justify-content-between header-wrapper {{ $headerTwoLayoutClass }}">
                @foreach ($headerTwo->group as $key => $group)
                    @if ($key == 'logo_widget' && isset($group->status) && $group->status == 'on')
                        <x-site-logo type="dark" />
                    @endif
                    @if ($key == 'search_widget' && isset($group->status) && $group->status == 'on')
                        <div class="header-search-wrapper">
                            <form action="{{ route('product.all') }}" method="GET" class="header-search-form me-auto @if (!request()->routeIs('home')) w-100 @endif">
                                <div class="header-form-group">
                                    <button type="button" class="search-close-btn"><i class="las la-arrow-up"></i></button>
                                    <input type="text" class="form--control" name="search" value="{{ request()->search }}" placeholder="@lang('I am shopping for')...">
                                </div>
                                <button class="icon" type="submit"><i class="las la-search"></i></button>
                            </form>
                        </div>
                        <button type="button" class="header-search-btn"><i class="las la-search"></i></button>
                    @endif
                    @if ($key == 'widgets')
                        @php
                            $widgets = collect($group)->where('status', 'on');
                        @endphp

                        @if ($widgets->count())
                            <ul class="list list--row option-list-wrapper justify-content-center justify-content-md-end option-list d-flex align-items-center">
                                @foreach ($widgets as $widget)
                                    @if (gs('product_compare') && $widget->key == 'compare' && @$widget->status == 'on')
                                        <li class="d-none d-lg-block">
                                            <a href="{{ route('compare.all') }}" class="ecommerce">
                                                <span class="ecommerce__icon">
                                                    <i class="las la-exchange-alt"></i>
                                                    <span class="ecommerce__is compare-count d-none"></span>
                                                </span>
                                                <span class="ecommerce__text">@lang('Compare')</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (gs('product_wishlist') && $widget->key == 'wishlist' && @$widget->status == 'on')
                                        <li class="d-none d-lg-block">
                                            <a href="javascript:void(0)" class="ecommerce wish-button">
                                                <span class="ecommerce__icon">
                                                    <i class="las la-heart"></i>
                                                    <span class="ecommerce__is wishlist-count d-none"></span>
                                                </span>
                                                <span class="ecommerce__text">@lang('Wishlist')</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if ($widget->key == 'cart' && @$widget->status == 'on')
                                        <li>
                                            <a href="javascript:void(0)" class="ecommerce cart-button">
                                                <span class="ecommerce__icon">
                                                    <i class="las la-shopping-bag"></i>
                                                    <span class="ecommerce__is cartItemCount d-none"></span>
                                                </span>
                                                <span class="ecommerce__text">@lang('Cart')</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($widget->key == 'notifications' && @$widget->status == 'on')
                                        @auth
                                            <li>
                                                <x-user-notification-component />
                                            </li>
                                        @endauth
                                    @endif

                                    @if ($widget->key == 'user_auth' && @$widget->status == 'on')
                                        <li class="d-none d-lg-block">
                                            @include('Template::partials.user_auth_options')
                                        </li>
                                    @endif

                                    @if ($widget->key == 'language' && @$widget->status == 'on')
                                        <li class="d-none d-lg-block">
                                            @include($activeTemplate . 'partials.menu.language_menu')
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif


