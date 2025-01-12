<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('Media Contents')</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Main Image')</label>
            </div>
            <div class="col-md-9">
                @php
                    $mainImage = $product ? $product->displayImage : null;
                @endphp
                <x-media-uploader-input id="mainImage" for="product" isSingle="1" :images="$mainImage" inputName="main_image" />
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Gallery Images')</label>
            </div>

            <div class="col-md-9">
                @php
                    $galleryImages = @$product?->galleryImages ?? collect([]);
                @endphp

                <x-media-uploader-input id="galleryImages" for="product" :images="$galleryImages" inputName="gallery_images" />

                @if (Route::is('admin.products.edit') && $product->product_type == Status::PRODUCT_TYPE_VARIABLE)
                    <button type="button" class="btn btn-sm btn--light mt-2 border" id="assignAttributeImagesBtn">@lang('Assign Images to Attribute')</button>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-3">
                <label>@lang('Video')</label>
            </div>
            <div class="col-md-9">
                <input type="text" class="form-control video_link-field" name="video_link" value="{{ $product->video_link ?? '' }}">
                <small class="form-text text-muted">
                    <i class="las la-info-circle"></i>
                    @lang('Only youtube embed link is allowed')
                </small>
            </div>
        </div>
    </div>
</div>

@if (Route::is('admin.products.edit') && $product->product_type == Status::PRODUCT_TYPE_VARIABLE)
    @php
        $mediaAttribute = $product?->attributes->whereIn('type', [Status::ATTRIBUTE_TYPE_COLOR])->first();
    @endphp

    @if ($mediaAttribute)
        @php
            $mediaAttributeValues = $product?->attributeValues->where('attribute_id', $mediaAttribute->id);
        @endphp
        @push('modal')
            <!-- Modal -->
            <div class="modal fade modal-lg" id="assignAttributeImagesModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('Assign Images to') {{ $mediaAttribute->name }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="la la-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form method="post" action="{{ route('admin.products.media.assign', $product->id) }}" id="attributeImagesForm">
                                @csrf
                                <div class="row gy-4">
                                    @foreach ($galleryImages as $galleryImageItem)
                                        @php
                                            $mediaAttribute = $mediaAttributeValues->where('pivot.media_id', $galleryImageItem->id)->first();
                                        @endphp

                                        <div class="col-6 col-md-4">
                                            <div class="card border shadow-none">
                                                <div class="card-body d-flex flex-column gap-2 justify-content-center align-items-center">
                                                    <img src="{{ $galleryImageItem->thumb_url }}" alt="image" width="150">
                                                    <input type="hidden" name="attribute_values[{{ $loop->index }}][media_id]" value="{{ $galleryImageItem->id }}">
                                                    <select name="attribute_values[{{ $loop->index }}][attribute_value_id]" class="form-control">
                                                        <option value="" selected disabled>@lang('Select One')</option>
                                                        @foreach ($mediaAttributeValues as $mediaAttributeValue)
                                                            <option value="{{ $mediaAttributeValue->id }}" @selected($mediaAttribute?->id == $mediaAttributeValue->id)>{{ $mediaAttributeValue->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn--primary w-100 h-45" form="attributeImagesForm">@lang('Submit')</button>
                        </div>
                    </div>
                </div>
            </div>
        @endpush
        @pushOnce('script')
            <script>
                (function($) {
                    "use strict";
                    $('#assignAttributeImagesBtn').on('click', function() {
                        const modal = $('#assignAttributeImagesModal');
                        modal.modal('show');
                    });
                })(jQuery);
            </script>
        @endPushOnce
    @endif
@endif
