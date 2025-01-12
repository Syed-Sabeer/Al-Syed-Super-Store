<div class="xzoom-container product-gallery">
    <img class="@if ($product->is_downloadable) downloadable_image @else xzoom5 @endif" id="xzoom-magnific" src="{{ $images->first()?->full_url }}" xoriginal="{{ $images->first()?->full_url }}" />

    <div class="d-none">
        @foreach ($images as $image)
            <img class="zoom-image" src="{{ $image->full_url }}" data-zoom-image="{{ $image->full_url }}" alt="Image 1">
        @endforeach
    </div>

    <div class="xzoom-thumbs">
        <div class="quick-view-slider owl-carousel owl-theme">
            @foreach ($images as $image)
                <a href="{{ $image->full_url }}" class="media-item-nav" data-media_id="{{ $image->id }}">
                    <img class="@if ($product->is_downloadable) downloadable_gallery @else xzoom-gallery5 @endif" width="80" src="{{ getImage($image->path . '/thumb_' . $image->file_name) }}" xpreview="{{ $image->full_url }}">
                </a>
            @endforeach
        </div>
    </div>
</div>
