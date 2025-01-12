@props(['oldImages' => []])

<div class="input-images"></div>

@once
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close m-3 ms-auto" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
                <div class="modal-body text-center">
                    <i class="las la-times-circle f-size--100 color--danger mb-15"></i>
                    <h3 class="color--danger mb-15">@lang('Maximum 6 images are allowed!')</h3>
                    <p class="mb-15">@lang('The rest of the images you have selected are removed')</p>
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Continue')</button>
                </div>
            </div>
        </div>
    </div>

    @push('style-lib')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/image-uploader.min.css') }}">
    @endpush

    @push('script-lib')
        <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
    @endpush

    @push('script')
        <script>
            (function($) {
                "use strict";
                $('.input-images').each((i, element) => {
                    const data = $(element).parent().data();

                    $(element).fileUploader({
                        preloaded: data.images,
                        filesName: 'photos',
                        preloadedInputName: 'old',
                        maxFiles: data.max_files
                    });
                });
            })
            (jQuery);
        </script>
    @endpush
@endonce
