@props([
    'subcategory' => [],
    'prefix',
    'isAdmin',
])

@php $prefix .='|--'  @endphp

@foreach ($subcategory->allSubcategories ?? [] as $childCategory)
    <option value="{{ $isAdmin ? $childCategory->id : $childCategory->slug }}" data-title="{{ __($childCategory->name) }}"@selected($childCategory->id == request()->category)>
        {{ $prefix }} {{ __($childCategory->name) }}
    </option>
    <x-subcategory-options :subcategory=$childCategory :prefix=$prefix :isAdmin=$isAdmin />
@endforeach
