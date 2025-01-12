@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $request = request();

        $currentRoute = Request::route();
        $currentRouteName = $currentRoute->getName();

        if ($currentRouteName == 'product.by.category' || $currentRouteName == 'product.by.brand') {
            $routeParameters = $currentRoute->parameters();
            $firstParameter = reset($routeParameters);
            $baseURL = route($currentRouteName, $firstParameter);
        } else {
            $baseURL = route($currentRouteName);
        }
        $brand = $request->has('brand') ? explode(',', $request->brand) : [];
        $attributeValues = $request->has('attribute_values') ? explode(',', $request->attribute_values) : [];
        $rating = $request->has('rating');
    @endphp

    <!-- Category Section Starts Here -->



    <div class="py-60">
        <div class="container">

            @if (!blank($breadcrumbs))
                <ul class="mb-4 breadcrumb">
                    @foreach ($breadcrumbs as $key => $link)
                        <li class="breadcrumb-item">
                            @if (!$loop->last)
                                <a href="{{ $link }}">{{ __($key) }}</a>
                            @else
                                <span>
                                    {{ __($key) }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="product-shop">
                <aside class="category-sidebar">
                    <button type="button" class="close-sidebar text--secondary d-xl-none"><i class="las la-times"></i></button>
                    <form action="" method="get" id="filterForm" class="product-filter-form">

                        @if (isset($categories) && $categories->count())
                            <div class="widget">
                                <h5 class="title">@lang('Categories')</h5>
                                <div class="widget-body">
                                    <ul class="filter-category filter-overflow">
                                        @foreach ($categories as $item)
                                            <li>
                                                <a href="{{ route('product.by.category', $item->slug) }}" class="" data-slug="{{ $item->slug }}"><i class="las la-angle-right"></i>
                                                    {{ __($item->name) }} </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if (isset($brands) && $brands->count())
                            <div class="widget">
                                <h5 class="title">@lang('Filter by Brand')</h5>
                                <div class="widget-body filter-overflow">
                                    @foreach ($brands as $item)
                                        <div class="widget-check-group brand-filter">
                                            <input type="checkbox" class="form-check-input brand-item" value="{{ $item->slug }}" name="brand[]" id="brand-{{ $loop->iteration }}" @checked(in_array($item->slug, $brand))>
                                            <label for="brand-{{ $loop->iteration }}">{{ __($item->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="widget">
                            <h5 class="title">@lang('Filter by Price')</h5>
                            <div class="widget-body">
                                <div class="price-range d-flex flex-wrap mb-4">
                                    <div class="widget-check-group">
                                        <input type="checkbox" class="form-check-input" id="priceCheck" @checked($request->has('min_price') || $request->has('max_price'))>
                                        <label class="form-check-label" for="priceCheck">@lang('Price Filter')</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" min="0" name="min_price" value="{{ getAmount($minPrice) }}" disabled>
                                        <input type="number" step="any" class="form-control" min="0" name="max_price" value="{{ getAmount($maxPrice) }}" disabled>
                                    </div>
                                </div>

                                <div id="slider-range"></div>

                            </div>
                        </div>

                        @foreach ($attributes as $attribute)
                            <div class="widget">
                                <h5 class="title">{{ __($attribute->name) }}</h5>
                                <div class="widget-body">
                                    @if ($attribute->type == Status::ATTRIBUTE_TYPE_COLOR)
                                        <ul class="list list--row flex-wrap">
                                            @foreach ($attribute->values as $attributeValue)
                                                <li>
                                                    <label class="select-color" for="attr-color-val-{{ $attributeValue->id }}" style="color:#{{ $attributeValue->value }}; background-color:#{{ $attributeValue->value }}" data-bs-title="{{ __($attributeValue->name) }}">
                                                        <input class="select-color__input attributes" type="checkbox" name="attribute_values[]" data-type="{{ $attribute->type }}" value="{{ $attributeValue->id }}" id="attr-color-val-{{ $attributeValue->id }}" @checked(in_array($attributeValue->id, $attributeValues))>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="product-size-filter">
                                            @foreach ($attribute->values as $attributeValue)
                                                <div class="widget-check-group">
                                                    <input class="form-check-input attributes" type="checkbox" data-type="{{ $attribute->type }}" name="attribute_values[]" value="{{ $attributeValue->id }}" id="attr-value-{{ $attributeValue->id }}" @checked(in_array($attributeValue->id, $attributeValues))>
                                                    <label class="form-check-label" for="attr-value-{{ $attributeValue->id }}">
                                                        {{ __($attributeValue->name) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="widget">
                            <h5 class="title">@lang('Ratings')</h5>
                            <div class="widget-body">
                                <div class="product-filter__card">
                                    <ul class="list">
                                        <li>
                                            <div class="widget-check-group">
                                                <input class="form-check-input rating" name="rating" value="5" type="radio" id="five-star" @checked($rating == 5)>
                                                <label class="form-check-label" for="five-star">
                                                    <span class="list list--row rating-list">
                                                        @php echo formattedFilterParameterRatings(5) @endphp
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-check-group">
                                                <input class="form-check-input rating" name="rating" value="4" type="radio" id="four-star" @checked($rating == 4)>
                                                <label class="form-check-label" for="four-star">
                                                    <span class="list list--row rating-list">
                                                        @php echo formattedFilterParameterRatings(4) @endphp
                                                        <span class="rating-up-text">@lang('and Up')</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-check-group">
                                                <input class="form-check-input rating" name="rating" value="3" type="radio" id="three-star" @checked($rating == 3)>
                                                <label class="form-check-label" for="three-star">
                                                    <span class="list list--row rating-list">
                                                        @php echo formattedFilterParameterRatings(3) @endphp
                                                        <span class="rating-up-text">@lang('and Up')</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-check-group">
                                                <input class="form-check-input rating" name="rating" value="2" type="radio" id="two-star" @checked($rating == 2)>
                                                <label class="form-check-label" for="two-star">
                                                    <span class="list list--row rating-list">
                                                        @php echo formattedFilterParameterRatings(2) @endphp
                                                        <span class="rating-up-text">@lang('and Up')</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="widget-check-group">
                                                <input class="form-check-input rating" name="rating" value="1" type="radio" id="one-star" @checked($rating == 1)>
                                                <label class="form-check-label" for="one-star">
                                                    <span class="list list--row rating-list">
                                                        @php echo formattedFilterParameterRatings(1) @endphp
                                                        <span class="rating-up-text">@lang('and up')</span>
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </aside>

                <div class="products-container w-100">
                    
                    <div class="filter-category-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex gap-2 align-items-center flex-grow-1 flex-md-grow-0 justify-content-between">
                            <div class="filter-select-item d-xl-none pl-0 ">
                                <button class="filter-in flex-shrink-0">
                                    <i class="las la-sliders-h"></i> @lang('Filter')
                                </button>
                            </div>
                            <span class="small text-muted flex-shrink-0">
                                @lang('Total')
                                <span class="fw-semibold totalProducts">{{ $products->total() }}</span>
                                {{ Str::plural('product', $products->total()) }} @lang('found')
                            </span>
                        </div>

                        <div class="d-flex align-items-center gap-3 filter-select-right flex-grow-1">

                            <div class="d-flex gap-2 justify-content-between">
                                <div class="filter-select-item d-flex gap-1 align-items-center">
                                    <p class="mb-0">@lang('Sort By') : </p>
                                    <div class="select-item">
                                        <select class="form--control form--select" name="sort_by" form="#filterForm">
                                            <option value="" selected>@lang('Default') </option>
                                            <option value="price_htl" @selected($request->sort_by == 'price_htl')>@lang('Price Hight to Low') </option>
                                            <option value="price_lth" @selected($request->sort_by == 'price_lth')>@lang('Price Low to High') </option>
                                            <option value="latest" @selected($request->sort_by == 'latest')>@lang('Latest') </option>
                                            <option value="oldest" @selected($request->sort_by == 'oldest')>@lang('Oldest') </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="filter-select-item d-flex gap-1 align-items-center">
                                    <p class="mb-0">@lang('Show') : </p>
                                    <div class="select-item">
                                        <select class="form--control form--select" name="per_page" form="#filterForm">
                                            <option value="4" @selected($request->per_page == 4)>4</option>
                                            <option value="8" @selected($request->per_page == 8)>8</option>
                                            <option value="16" @selected($request->per_page == 16)>16</option>
                                            <option value="24" @selected(!$request->per_page || $request->per_page == 24)>24</option>
                                            <option value="32" @selected($request->per_page == 32)>32</option>
                                            <option value="64" @selected($request->per_page == 64)>64</option>
                                            <option value="80" @selected($request->per_page == 80)>80</option>
                                            <option value="100" @selected($request->per_page == 100)>100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-select-item">
                                <ul class="view-style d-flex gap-2  align-items-center">
                                    <li>
                                        <a href="javascript:void(0)" class="active view-grid-style"><i class="las la-th-large"></i></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="view-list-style"><i class="las la-list-ul"></i></a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="position-relative">
                        <div id="overlay">
                            <div class="cv-spinner">
                                <span class="spinner"></span>
                            </div>
                        </div>
                        <div class="overlay-2" id="overlay2"></div>
                        <div class="page-main-content">
                            @include('Template::partials.products_filter')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Category Section Ends Here -->
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/owl-carousel.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/xzoom.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/magnific-popup.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/xzoom/setup.js') }}"></script>
@endpush

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue . 'css/owl.carousel.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/xzoom/xzoom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/xzoom/magnific-popup.css') }}">
@endpush

@push('script')
    <script>
        (function($) {

            $(".close-sidebar").on("click", (function() {
                $(".category-sidebar").removeClass("active");
                $(".body-overlay").removeClass("active")
            }));

            $(".filter-in").on("click", (function() {
                $(".category-sidebar").addClass("active");
                $(".body-overlay").addClass("active")
            }));

            $(".view-list-style").on("click", (function() {
                $(".view-grid-style").removeClass("active");
                $(this).addClass("active");
                $("#grid-view .grid-control").addClass("list-view-active");
                $("#grid-view .grid-control .single_content").show()
            }));

            $(".view-grid-style").on("click", (function() {
                $(".view-list-style").removeClass("active");
                $(this).addClass("active");
                $("#grid-view .grid-control").removeClass("list-view-active");
                $("#grid-view .grid-control .single_content").hide()
            }));

            var min = '{{ $minPrice }}';
            var max = '{{ $maxPrice }}';
            var search = `{{ $request->search }}`;

            $(document).on('change', '.brand-item, .attributes, .rating', function() {
                fetchProducts();
            });


            $("#slider-range").slider({
                range: true,
                min: {{ $minPrice ?? 0 }},
                max: {{ $maxPrice ?? 0 }},
                values: [{{ $minPrice }}, {{ $maxPrice }}],
                slide: function(event, ui) {
                    $('input[name=min_price]').val(ui.values[0]);
                    $('input[name=max_price]').val(ui.values[1]);
                },
                change: function(event, ui) {
                    min = ui.values[0];
                    max = ui.values[1];
                    fetchProducts();
                }
            });

            $('[name=sort_by], [name=per_page]').on('change', function() {
                fetchProducts();
            });

            $('#filterForm [name=min_price], #filterForm [name=max_price]').on('focusout', function() {
                fetchProducts();
            })

            $('#priceCheck').on('change', function() {
                if ($(this).prop('checked')) {
                    $('#filterForm [name=min_price], #filterForm [name=max_price]').attr('disabled', false);
                    $("#slider-range").slider("enable")
                } else {
                    $('#filterForm [name=min_price], #filterForm [name=max_price]').attr('disabled', true);
                    $("#slider-range").slider("disable")
                }
            }).change();


            $(document).on('click', 'a.page-link', function(e) {
                e.preventDefault();
                fetchProducts(this.href);
            });


            function getFormDataAsQuery() {
                let formData = $('#filterForm').serializeArray();

                let queryParams = {};

                formData.forEach(function(field) {

                    if (field.name.endsWith('[]')) {
                        var fieldName = field.name.replace(/\[\]$/, '');
                        queryParams[fieldName] = queryParams[fieldName] || [];
                        queryParams[fieldName].push(field.value);
                    } else {
                        queryParams[field.name] = field.value;
                    }
                });

                for (const [key, value] of Object.entries(queryParams)) {
                    if (Array.isArray(value)) {
                        queryParams[key] = queryParams[key].join(',');
                    }
                }

                let queryString = $.param(queryParams);
                return queryString;
            }

            function decodeHtmlEntities(str) {
                let txt = document.createElement('textarea');
                txt.innerHTML = str;
                return txt.value;
            }


            function fetchProducts(url = `{{ $request->fullUrl() }}`, data = null) {
                $("#overlay, #overlay2").fadeIn(300);

                url = decodeHtmlEntities(url);
                let formData = getFormDataAsQuery();


                let urlObject = new URL(url);
                let params = new URLSearchParams(urlObject.search);

                if (formData) {
                    let newParams = new URLSearchParams(formData);
                    for (let [key, value] of newParams.entries()) {
                        params.set(key, decodeHtmlEntities(value));
                    }
                }

                if ($('[name=sort_by]').val()) {
                    params.set('sort_by', decodeHtmlEntities($('[name=sort_by]').val()));
                }

                if ($('[name=per_page]').val()) {
                    params.set('per_page', decodeHtmlEntities($('[name=per_page]').val()));
                }

                urlObject.search = params.toString();
                url = urlObject.toString();

                $.ajax({
                    url: url,
                    method: "get",
                    success: function(response) {
                        $('.totalProducts').text(response.total_products);
                        $('.page-main-content').html(response.html);
                        window.history.pushState(null, null, url);
                        lazyload();
                        scrollToTop();

                    }
                }).done(function() {
                    $('.ajax-preloader').addClass('d-none');
                    setTimeout(function() {
                        $("#overlay, #overlay2").fadeOut(300);
                    }, 500);
                });
            }

            function scrollToTop() {
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
            }
        })(jQuery)
    </script>
@endpush
