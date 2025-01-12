<div class="modal fade" id="mediaUploaderModal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mediaUploaderModal" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="mediaUploaderModalLabel">@lang('Media Uploader')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="la la-times"></i></button>
            </div>

            <div class="modal-body">
                <form method="post" action="{{ route('admin.media.upload') }}" enctype="multipart/form-data" id="mediaUploadForm" class="mb-3">
                    @csrf
                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn--dark uploaderCancelBtn">@lang('Delete All')</button>
                        <button type="submit" class="btn btn--primary uploaderUploadButton">@lang('Upload')</button>
                    </div>

                    <div class="input-images"></div>
                </form>

                <div class="text-end">
                    <button type="button" class="btn btn--primary mt-3 mb-3" disabled id="useSelectedFilesBtn">@lang('Use Selected')</button>
                </div>

                <div class="media-container-parent text-center">
                    <div class="media-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            "use strict";
            const mediaContainer = $('.media-container');
            const mediaContainerParent = $('.media-container-parent');
            const mediaUploaderModal = $('#mediaUploaderModal');

            let previewElement;
            let filesFor;
            let inputField;
            let mediaFilesLoaded = false;
            let singleFile = false;

            const changeSelectedButtonState = () => {
                if ($(mediaContainerParent).find('.media-body.active').not('.disabled').length) {
                    $('#useSelectedFilesBtn').attr('disabled', false);
                } else {
                    $('#useSelectedFilesBtn').attr('disabled', true);
                }
            }

            const markSelectedFiles = () => {
                let selectedFiles = inputField[0].value ? inputField[0].value.split(",") : [];

                const mediaItems = $('.media-container-parent .media-body');

                mediaItems.each((_, item) => {
                    const element = $(item);
                    if (selectedFiles.includes(element.data('id').toString())) {
                        element.addClass('active disabled');
                    } else {
                        element.removeClass('active disabled');
                    }
                });
                changeSelectedButtonState();
            }

            const showMediaUploaderModal = (target) => {
                const data = $(target).data();
                filesFor = data.files_for;
                singleFile = data.single_file == 1 ? true : false;
                previewElement = $(`#${data.preview_element_id}`);

                inputField = previewElement.siblings(`[name="${data.input_field_name}"]`);

                if (!mediaFilesLoaded) {
                    fetchMediaFiles();
                }

                markSelectedFiles();
                mediaUploaderModal.modal('show');
            }

            mediaUploaderModal.on('hidden.bs.modal', function(e) {
                mediaUploaderModal.find('.media-body.active').removeClass('active');
            });

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

            toggleFormButtons();

            const uploader = $('.input-images').fileUploader({
                filesName: 'photos',
                preloadedInputName: 'old',
                maxFiles: 20,
                onSelect: toggleFormButtons,
                onRemove: toggleFormButtons,
                label: 'Drag & drop files here to upload new images',
            });

            let isSubmitting = false;

            const assetPath = `{{ asset(null) }}`;
            const defaultImage = `{{ getImage(null) }}`;

            const clearUploader = () => {
                $('.input-images').find(".delete-file-button").each((i, e) => e.click())
                toggleFormButtons();
            }

            const mediaComponent = (files, activeClass = '') => {
                let html = ``;

                files.forEach((element, index) => {
                    let appliedClass = activeClass;

                    if (singleFile) {
                        appliedClass = index == 0 ? activeClass : '';
                        mediaContainerParent.find('.media-body').removeClass('active').removeClass('disabled');
                    }

                    html += `<div class="media-body ${appliedClass}" data-id="${element.id}">
                    <img src="${element.thumb_url}" alt="image">
                 </div>`;
                });

                return html;
            };

            const handleFormSubmission = (response) => {
                notify(response.status, response.message);
                if (response.status == 'success') {
                    const mediaHtml = mediaComponent(response.uploaded, 'active');
                    $(mediaHtml).prependTo(mediaContainer);
                    clearUploader();
                    changeSelectedButtonState();
                }
            }

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

                formData.append('files_for', filesFor);

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

            const useSelectedFiles = () => {
                const selectedFiles = $('.media-body.active');

                const files = singleFile ? [] : inputField.val() ? inputField.val().split(",") : [];


                if (singleFile) {
                    $(previewElement).find('.gallery-item').remove();
                    $(previewElement).parent().css({
                        'background': 'none'
                    });
                }

                selectedFiles.each((index, element) => {
                    const dataId = $(element).data('id');
                    const isAlreadyInPreview = previewElement.find(`[data-id="${dataId}"]`).length > 0;
                    if (!isAlreadyInPreview) {
                        const clonedElement = $(element).clone();
                        clonedElement.append(
                            '<button type="button" class="gallery-item-remove"><i class="la la-times"></i></button>'
                        );
                        clonedElement.removeClass('media-body active').addClass('gallery-item').prependTo(
                            previewElement);
                        files.push(dataId);
                    }
                    $(element).removeClass('active');
                });

                inputField.val(files.join(','));

                mediaUploaderModal.modal('hide');
            };
            const loadMoreBtn = () => {
                return `<a href="#" class="btn btn--dark mt-4 loadMoreBtn me-auto">@lang('Load More')</a>`
            }

            const fetchMediaFiles = (url = `{{ route('admin.media.files') }}`) => {
                let loadMoreButton = mediaContainerParent.find('.loadMoreBtn');

                $.get(url)
                    .done((response) => {
                        const mediaHtml = mediaComponent(response.data);
                        $(mediaHtml).appendTo(mediaContainer);

                        if (response.next_page_url) {
                            if (!loadMoreButton.length) {
                                loadMoreButton = loadMoreBtn();
                                mediaContainerParent.append(loadMoreButton);
                            }

                            mediaContainerParent.find('.loadMoreBtn').attr('href', response.next_page_url);
                        } else {
                            $(loadMoreButton).remove();
                        }
                        mediaFilesLoaded = true;

                        markSelectedFiles();

                        if (loadMoreButton.length) {
                            $(loadMoreButton).removeClass('disabled').text(`@lang('Load More')`);
                        }
                    })
                    .fail((jqXHR, textStatus, errorThrown) => {
                        console.error('Error fetching media files:', textStatus, errorThrown);
                        if (loadMoreButton.length) {
                            loadMoreButton.removeClass('disabled').text(`@lang('Load More')`);
                        }
                    });
            };

            const handleMediaBodyClick = (e) => {
                if (singleFile) {
                    $(mediaContainerParent).find('.media-body').not($(e.currentTarget)).removeClass('active');
                }

                $(e.currentTarget).toggleClass('active');

                changeSelectedButtonState();
            }

            const handleLoadMoreBtnClick = (e) => {
                e.preventDefault();
                $(e.currentTarget).addClass('disabled').html('<i class="fa fa-spinner fa-spin"></i>');
                fetchMediaFiles(e.target.href);
            }

            const handleGalleryItemRemove = (e) => {
                const galleryWrapper = $(e.currentTarget).closest('.gallery-wrapper');
                let inputField = galleryWrapper.find('input');

                const perentId = $(e.currentTarget).parent().data('id');
                mediaContainer.find(`.media-body[data-id="${perentId}"]`).removeClass('disabled')

                $(e.currentTarget).parent().remove();

                if (singleFile) {

                    $(galleryWrapper).css({
                        'background': "url({{ getImage(null) }})",
                        'background-size': 'cover'
                    });
                }

                const selectedFiles = galleryWrapper.find('.gallery-images .gallery-item');

                const files = [];

                selectedFiles.each((index, element) => {
                    const dataId = $(element).data('id');
                    if (dataId) {
                        files.push(dataId);
                    }
                });

                inputField.val(files.join(','));
            };

            $(document).on('click', '.gallery-item-remove', (e) => handleGalleryItemRemove(e));
            $(document).on('click', '.loadMoreBtn', handleLoadMoreBtnClick);
            $(document).on('submit', '#mediaUploadForm', (e) => handleMediaFormSubmit(e));
            $(document).on('click', '.uploaderCancelBtn', clearUploader);
            $(document).on('click', '.media-body', (e) => handleMediaBodyClick(e));
            $(document).on('click', '#useSelectedFilesBtn', useSelectedFiles);
            $(document).on('click', '.mediaUploaderBtn', (e) => showMediaUploaderModal(e.currentTarget));
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .media-body {
            aspect-ratio: 1 / 1;
        }

        .media-body img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }

        .media-container-parent {
            padding-right: 5px;
        }

        .gallery-item img {
            width: 100%;
            object-fit: cover;
        }

        .single-image img {
            width: 100% !important;
            object-fit: cover;
        }
    </style>
@endpush
