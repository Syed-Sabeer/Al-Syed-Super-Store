@foreach ($categories as $category)
    @include('admin.category.tree_nodes', ['subcategory' => $category])
@endforeach
