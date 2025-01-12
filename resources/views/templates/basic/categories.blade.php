@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="pt-60 pb-60">
        <div class="container">
            <div class="category-tree">
                <div class="d-flex flex-wrap gap-4">
                    @forelse ($categories as $category)
                        <div class="category-tree-item flex-grow-1 w-50">
                            <div class="parent-category">
                                <x-dynamic-component :component="frontendComponent('category-card')" :category="$category" />
                            </div>

                            @if (!blank($category->allSubcategories))
                                @include($activeTemplate . 'partials.category_tree', ['subcategories' => $category->allSubcategories, 'prefix' => ''])
                            @endif
                        </div>

                    @empty
                        <x-dynamic-component :component="frontendComponent('empty-message')" message="No category found" />
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
