@php
    $topCategories = \App\Models\Category::featured()->orderBy('id', 'desc')->get();
    $content = getContent('featured_categories.content', true);
@endphp

<section class="my-60">
    <div class="container">
        @if (!blank($topCategories))
            <div class="section-header">
                <h5 class="title">{{ __(@$content->data_values->title) }}</h5>
            </div>

            <div class="category-card-wrapper">
                @foreach ($topCategories as $category)
                    <div class="small-card-item text-center">
                        <x-dynamic-component :component="frontendComponent('category-card')" :category="$category" :lineLimitation="false" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
