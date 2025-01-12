@php
    if ($limit) {
        $parentCategories = $parentCategories->take($limit);
    }
@endphp

<div class="left-site-category active">
    <div class="category-dropdown">
        <ul class="list-inline mega-menu vertical-megamenu">
            @foreach ($parentCategories as $category)
                <li class="fluid-menu {{ $category->allSubcategories->count() && !multiLayerCategory($category) ? 'dropdown  has-submenu' : '' }}">
                    <a href="{{ $category->shopLink() }}" class="nav-link menu-item @if ($category->allSubcategories->count()) dropdown has-submenu @endif">
                        @if ($category->icon)
                            <span class="menu-item-icon">
                                <img src="{{ $category->categoryIcon() }}" alt="icon">
                            </span>
                        @endif
                        {{ __($category->name) }}

                        <span class="menu-item-arrowicon">
                            <i class="las la-angle-right"></i>
                        </span>
                    </a>

                    @if ($category->allSubcategories->count())
                        @if (multiLayerCategory($category))
                            <ul class="list-inline categories__mega-menu-wrap categories__mega-menu d-none d-lg-block">
                                <li>
                                    <div class="categories__mega-menu-content">
                                        @foreach ($category->allSubcategories as $subcategory)
                                            <div class="categories__mega-menu-list">
                                                <h5 class="categories__mega-menu-title">
                                                    <a href="{{ $subcategory->shopLink() }}">{{ __($subcategory->name) }}</a>
                                                </h5>

                                                @if ($subcategory->allSubcategories)
                                                    <ul class="list-inline cate__mega-menu-list">
                                                        @foreach ($subcategory->allSubcategories as $subCat)
                                                            <li><a href="{{ $subCat->shopLink() }}">{{ __($subCat->name) }}</a>
                                                                @if ($subCat->allSubcategories)
                                                                    <ul class="categories__mega-submenu">
                                                                        @foreach ($subCat->allSubcategories as $childCategory)
                                                                            @include($activeTemplate . 'partials.subcategory_list', [
                                                                                'childCategory' => $childCategory,
                                                                            ])
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </li>
                            </ul>
                        @else
                            <ul class="list-inline sub-menu">
                                @foreach ($category->allSubcategories as $subCat)
                                    <li><a href="{{ $subCat->shopLink() }}">{{ __($subCat->name) }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
