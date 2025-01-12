@props(['categories' => [], 'form' => ''])
@if (!blank($categories))
    <div class="parent">
        @foreach ($categories as $category)
            <div class="child ms-3">
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="categories[]" id="category-{{ $category->id }}" value="{{ $category->id }}" @if ($form) form="{{ $form }}" @endif> <span>{{ $category->name }}</span> </label>
                </div>
                <x-category-checkbox :categories="$category->allSubcategories" form="{{ $form }}" />
            </div>
        @endforeach
    </div>
@endif
