@php
    $featuredCategories = \App\Models\Category::where('feature_in_banner', 1)->with('products.brand')->get();

    if (gs('homepage_layout') == 'sidebar_menu') {
        $categoriesToShow = [
            768 => ['items' => 5],
            992 => ['items' => 5],
            1199 => ['items' => 5],
        ];
        if (!$fixedBanner) {
            $categoriesToShow[1399] = ['items' => 6];
        }
    } else {
        $categoriesToShow = [
            768 => ['items' => 6],
            992 => ['items' => 7],
            1199 => ['items' => 9],
        ];
    }
@endphp

@if (!blank($featuredCategories))
    <div class="overflow-hidden">
        <div class="featured-category-slider owl-theme owl-carousel">
            @foreach ($featuredCategories as $category)
                <div class="single-category p-2">
                    <x-dynamic-component :component="frontendComponent('category-card')" :category="$category" />
                </div>
            @endforeach
        </div>
    </div>

    @push('script')
        <script>
            (function($) {
                "use strict";
                const categoriesToShow = @json($categoriesToShow);

                const viewItems = {
                    0: {
                        items: 3,
                        margin: 12,
                    },
                    425: {
                        items: 4,
                        margin: 12,
                    },
                    575: {
                        items: 4,
                        margin: 12,
                    },
                    ...categoriesToShow
                };



                $(".featured-category-slider").owlCarousel({
                    margin: 16,
                    responsiveClass: true,
                    items: 3,
                    nav: false,
                    dots: false,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    loop: true,
                    lazyLoad: true,
                    responsive: viewItems,
                });
            })(jQuery);
        </script>
    @endpush

@endif
