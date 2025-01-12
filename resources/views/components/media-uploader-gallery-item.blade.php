@props(['image'])

<div class="gallery-item" data-id="{{ $image->id }}">
    <img src="{{ $image->thumb_url }}" alt="image">
    <button type="button" class="gallery-item-remove"><i class="la la-times"></i></button>
</div>
