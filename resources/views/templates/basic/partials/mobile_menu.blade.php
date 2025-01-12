<ul class="list list--row mobile-menu-icons justify-content-center justify-content-md-end option-list d-lg-none d-flex">
    <li>
        <a href="{{ route('categories') }}" class="ecommerce" id="cate-button">
            <span class="ecommerce__icon">
                <img src="{{ svg('category') }}" alt="">
            </span>
            <span class="ecommerce__text">@lang('Category')</span>
        </a>
    </li>

    @if (gs('product_wishlist'))
        <li>
            <a href="javascript:void(0)" class="ecommerce wish-button">
                <span class="ecommerce__icon">
                    <img src="{{ svg('wishlist') }}" alt="">
                    <span class="ecommerce__is wishlist-count d-none"></span>
                </span>
                <span class="ecommerce__text">@lang('Wishlist')</span>
            </a>
        </li>
    @endif

    @if (gs('product_compare'))
        <li>
            <a href="{{ route('compare.all') }}" class="ecommerce">
                <span class="ecommerce__icon">
                    <img src="{{ svg('compare') }}" alt="">
                    <span class="ecommerce__is compare-count d-none"></span>
                </span>
                <span class="ecommerce__text">@lang('Compare')</span>
            </a>
        </li>
    @endif

    <li>
        <a href="javascript:void(0)" class="ecommerce @auth user-account-btn @endauth" id="account-button" @guest data-bs-toggle="modal" data-bs-target="#loginModal" @endguest>
            <span class="ecommerce__icon">
                <img src="{{ svg('my_account') }}" alt="">
            </span>
            <span class="ecommerce__text">@lang('My Account')</span>
        </a>
    </li>
</ul>


<div class="site-sidebar mobile-menu sidebar-nav d-lg-none">
    <button type="button" class="sidebar-close-btn">
        <i class="las la-times"></i>
    </button>

    <div class="mobile-menu-header">
        <div class="d-block d-lg-none">
            @include('Template::partials.menu.language_menu')
        </div>
    </div>
    <div class="mobile-menu-body">
        @include('Template::partials.menu.site_menu')
    </div>
</div>
