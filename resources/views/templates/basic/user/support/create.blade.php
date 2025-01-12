@extends('Template::layouts.user')

@section('panel')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">{{ __($pageTitle) }}</h5>

                        <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data" class="disableSubmission contact-form">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form--label" for="website">@lang('Subject')</label>
                                    <input type="text" class="form--control" name="subject" value="{{ old('subject') }}" placeholder="@lang('Subject')" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form--label" for="website">@lang('Priority')</label>
                                    <select name="priority" class="form--select select-select">
                                        <option value="3">@lang('High')</option>
                                        <option value="2">@lang('Medium')</option>
                                        <option value="1">@lang('Low')</option>
                                    </select>
                                </div>

                                <div class="col-12 form-group">
                                    <label class="form--label">@lang('Message')</label>
                                    <textarea name="message" class="form--control" required>{{ old('message') }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex flex-wrap gap-2 justify-content-between mb-2">
                                        <button type="button" class="btn btn-sm btn-outline--light addAttachment"> <i class="las la-plus"></i> @lang('Add Attachment') </button>
                                        <button class="btn btn--base" type="submit"><i class="las la-paper-plane"></i> @lang('Submit')
                                        </button>
                                    </div>
                                    <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                    <div class="row gy-3 fileUploadsContainer">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-6 removeFileInput">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form--control form-control custom-file-input" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text border-0 removeFile"><i class="fas fa-times"></i></button>
                            </div>
                    </div>
                `)
            });

            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
