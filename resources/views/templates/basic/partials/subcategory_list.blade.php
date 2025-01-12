<li><a href="{{ $childCategory->shopLink() }}">{{ __($childCategory->name) }}</a></li>
@foreach ($childCategory->allSubcategories ?? [] as $childCat)
    @include($activeTemplate . 'partials.subcategory_list', ['childCategory' => $childCat])
@endforeach
