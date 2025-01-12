@extends('admin.layouts.app')
@section('panel')
    <form method="post" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" id="mediaUploadForm" class="mb-3">
        @csrf
        <div class="mb-3 text-end">
            <button type="button" class="btn btn--dark uploaderCancelBtn">@lang('Delete All')</button>
            <button type="submit" class="btn btn--primary uploaderUploadButton">@lang('Upload')</button>
        </div>

        <div class="input-images"></div>
    </form>
    <div class="media-content">

        @foreach ($mediaFiles as $file)
            <div class="media-item">

                <img src="{{ $file->thumb_url }}" class="card-img-top" alt="image">

                @php
                    $totalUsed = $file->products_count + $file->product_images_count + $file->product_variants_count + $file->product_variant_images_count;
                @endphp

                <div class="d-flex flex-column media-info">
                    <button class="btn btn--light delete-btn confirmationBtn" data-question="@lang('Are you sure to delete this file permanently?')" data-action="{{ route('admin.media.delete', $file->id) }}"><i class="las la-trash m-0"></i></button>
                    <small class="flex-shrink-0">
                        @lang('Total Uses'): {{ $totalUsed }}
                    </small>

                    @if ($file->products_count)
                        <small class="flex-shrink-0">
                            @lang('Product Main Image'): {{ $file->products_count }}
                        </small>
                    @endif

                    @if ($file->product_images_count)
                        <small class="flex-shrink-0">
                            @lang('Product Gallery Image'): {{ $file->product_images_count }}
                        </small>
                    @endif
                    @if ($file->product_variants_count)
                        <small class="flex-shrink-0">
                            @lang('Variant Main Image'): {{ $file->product_variants_count }}
                        </small>
                    @endif
                    @if ($file->product_variant_images_count)
                        <small class="flex-shrink-0">
                            @lang('Variant Gallery Image'): {{ $file->product_variant_images_count }}
                        </small>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if ($mediaFiles->hasPages())
        <div class="py-4">
            {{ paginateLinks($mediaFiles) }}
        </div>
    @endif

    <x-confirmation-modal />
@endsection

@pushOnce('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/image-uploader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/media-uploader.css') }}">
@endPushOnce

@pushOnce('script-lib')
    <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
@endPushOnce

@push('script')
    <script>
        (function($) {
            'use strict';

            let isSubmitting = false;

            const toggleFormButtons = () => {
                const fileInput = $('.input-images input[type="file"]')[0];
                const hasFiles = fileInput && fileInput.files.length > 0;
                // Toggle the buttons based on whether files are selected
                if (hasFiles) {
                    $('.uploaderCancelBtn, .uploaderUploadButton').parent().show();
                } else {
                    $('.uploaderCancelBtn, .uploaderUploadButton').parent().hide();
                }
            }

            const uploader = $('.input-images').fileUploader({
                filesName: 'photos',
                preloadedInputName: 'old',
                maxFiles: 20,
                onSelect: toggleFormButtons,
                onRemove: toggleFormButtons,
                label: 'Drag & drop files here to upload new images',
            });

            const clearUploader = () => {
                $('.input-images').find(".delete-file-button").each((i, e) => e.click())
            }

            const handleFormSubmission = (response) => {
                notify(response.status, response.message);
                if (response.status == 'success') {
                    window.location.reload();
                }
            }

            toggleFormButtons();

            const handleMediaFormSubmit = (e) => {
                e.preventDefault();

                if (isSubmitting) {
                    return;
                }

                isSubmitting = true;

                const form = $('#mediaUploadForm');

                let btn = form.find('button[type=submit]');
                btn.prop('disabled', true);
                btn.html('<i class="fa fa-circle-notch fa-spin" aria-hidden="true"></i>');
                let formData = new FormData(form[0]);
                formData.append('files_for', 'product');


                $.ajax({
                    url: form.prop('action'),
                    type: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: handleFormSubmission
                }).always((response) => {
                    isSubmitting = false;
                    btn.prop('disabled', false);
                    btn.text(`@lang('Submit')`);
                });
            }


            $(document).on('submit', '#mediaUploadForm', (e) => handleMediaFormSubmit(e));
            $(document).on('click', '.uploaderCancelBtn', clearUploader);
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .file-uploader {
            border-radius: 5px;
            background-color: #fff;
            border: 1px solid #ebebeb;
        }

        .media-content {
            display: grid;
            gap: 0.5rem;
            grid-template-columns: repeat(8, 1fr);
        }

        @media (max-width: 768px) {
            .media-content {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 575px) {
            .media-content {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .media-item {
            position: relative;
            background-color: #fff;
            border: 1px solid #ebebeb;
            border-radius: 5px;
            overflow: hidden;
            aspect-ratio: 1 / 1;
        }

        .media-item img {
            height: 100%;
            object-fit: cover;
        }

        .media-item .delete-btn {
            position: absolute;
            top: 8px;
            right: 10px;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .btn--light {
            background-color: #ffffff !important;
        }

        .media-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 100%;
            background: #000000b4;
            color: #eeeeee;
            display: flex;
            justify-content: end;
            padding: 1rem;
            transition: all 0.2s ease-in-out;
            background: rgb(0, 0, 0);
            background: linear-gradient(2deg, rgb(0 0 0) 0%, rgb(255 255 255 / 0%) 100%);
            font-size: 0.8125rem;
            visibility: hidden;
            opacity: 0;
        }

        .media-item:hover .media-info {
            opacity: 1;
            visibility: visible;
        }

        .card-body small {
            font-size: 0.75rem
        }
    </style>
@endpush
