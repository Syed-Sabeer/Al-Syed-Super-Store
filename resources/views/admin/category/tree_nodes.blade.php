<li id="{{ $subcategory->id }}">

    {{ __($subcategory->name) }}

    @if ($subcategory->allSubcategories->count() > 0)
        <ul>
            @foreach ($subcategory->allSubcategories as $childCategory)
                @include('admin.category.tree_nodes', ['subcategory' => $childCategory])
            @endforeach
        </ul>
    @endif
</li>
