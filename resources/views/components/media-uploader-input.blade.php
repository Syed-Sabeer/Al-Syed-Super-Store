@props(['id', 'images' => [], 'for', 'isSingle' => 0, 'inputName' => $inputName])
<div class="gallery-wrapper @if ($isSingle) single-image @endif">
    @php
        if ($isSingle) {
            $value = $images->id ?? '';
        } else {
            $value = implode(',', @$images->pluck('id')->toArray());
        }
    @endphp

    <div class="gallery-images" id="{{ $id }}">
        @if ($isSingle && $images)
            <x-media-uploader-gallery-item :image="$images" />
        @else
            @foreach ($images as $galleryImage)
                <x-media-uploader-gallery-item :image="$galleryImage" />
            @endforeach
        @endif

        @if (!$isSingle)
            <button type="button" class="mediaUploaderBtn multiple" data-preview_element_id="{{ $id }}" data-files_for="{{ $for }}" data-input_field_name="{{ $inputName }}">
                <i class="la la-plus"></i> <br> @lang('Upload')
            </button>
        @endif
    </div>

    @if ($isSingle)
        <button type="button" class="btn btn--dark mediaUploaderBtn" data-single_file="1" data-preview_element_id="{{ $id }}" data-files_for="{{ $for }}" data-input_field_name="{{ $inputName }}"><i class="la la-pencil m-0"></i></button>
    @endif

    <input type="hidden" name="{{ $inputName }}" value="{{ $value }}">
</div>


@pushOnce('style')
    <style>
        .gallery-wrapper {
            border: 1px dashed #ebebeb;
            padding: 1rem;
            border-radius: 8px;
        }

        .gallery-wrapper.single-image {
            width: 200px;
            height: 200px;
            position: relative;
            padding: 1px;
            background: url('{{ getImage(null) }}');
            background-size: cover;
        }

        .gallery-wrapper.single-image .mediaUploaderBtn {
            position: absolute;
            bottom: -6px;
            right: -6px;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .gallery-wrapper.single-image .gallery-item {
            border: 0;
        }

        .gallery-images {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            height: 100%;
        }

        .gallery-item {
            border: 1px solid #ebebeb;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
            aspect-ratio: 1 / 1;
        }

        .gallery-item-remove {
            position: absolute;
            right: 5px;
            top: 5px;
            background-color: #f7f7f7;
            color: #a0a0a0;
            border-radius: 50%;
            justify-content: center;
            align-items: center;
            height: 24px;
            width: 24px;
            font-size: 0.875rem;
            display: none;
        }

        .gallery-item:hover .gallery-item-remove {
            display: flex;
        }

        .gallery-item-remove:hover {
            background-color: #e7e7e7;
            transition: all 0.3ms ease-in-out;
        }
    </style>
@endPushOnce
