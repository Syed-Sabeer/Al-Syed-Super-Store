@props(['isAdmin' => false, 'allCategories' => null])

@php
    $categories = $allCategories ?? $parentCategories;
@endphp

@foreach ($categories as $category)
    <option value="{{ $isAdmin ? $category->id : $category->slug }}" data-title="{{ __($category->name) }}" @selected($category->id == request()->category)>
        {{ __($category->name) }}
    </option>

    @php $prefix = '--'; @endphp

    @foreach ($category->allSubcategories as $subcategory)
        <option value="{{ $isAdmin ? $subcategory->id : $subcategory->slug }}" data-title="{{ __($subcategory->name) }}" @selected($subcategory->id == request()->category)>
            {{ $prefix }}{{ __($subcategory->name) }}
        </option>
        <x-subcategory-options :subcategory=$subcategory :prefix=$prefix :isAdmin=$isAdmin />
    @endforeach
@endforeach


@pushOnce('script')
    <script>
        (function($) {
            "use strict";

             $('#categoryDropdown').wrap(`<div class="position-relative"></div>`).select2({
                templateSelection: (state) => {
                if (!state.id) {
                    return state.text;
                }
                return state.element.dataset.title;
            },
                dropdownParent: $('#categoryDropdown').parent()
            });
        })
        (jQuery);
    </script>
@endPushOnce
