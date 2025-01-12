@extends($activeTemplate . 'layouts.' . $layout)

@php
    $sectionName = $layout == 'user' ? 'panel' : 'content';
@endphp

@section($sectionName)
    <div @if ($layout != 'user') class="py-60" @endif>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card custom--card">

                        <div class="card-body ">
                            <div class="d-flex gap-2 justify-content-between align-items-center mb-4 flex-wrap">
                                <h5 class="card-title my-0 fs-14">
                                    @php echo $myTicket->statusBadge; @endphp
                                    [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                                </h5>
                                @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                                    <button class="btn btn-outline--light btn--sm confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}">
                                        <i class="la la-times"></i> @lang('Close This Ticket')
                                    </button>
                                @endif
                            </div>

                            <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-between">
                                    <div class="col-12 form-group">
                                        <label class="form--label">@lang('Message')</label>
                                        <textarea name="message" class="form--control" rows="4" required>{{ old('message') }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex flex-wrap gap-2 justify-content-between mb-2">
                                            <button type="button" class="btn btn-outline--light addAttachment"> <i class="las la-plus"></i> @lang('Add Attachment') </button>

                                            <button class="btn btn--base" type="submit"><i class="la la-undo"></i> @lang('Reply')
                                            </button>
                                        </div>
                                        <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                        <div class="row g-3 fileUploadsContainer mt-3">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4 custom--card">
                        <div class="card-body ">
                            @foreach ($messages as $message)
                                @if ($message->admin_id == 0)
                                    <div class="user-support-ticket">
                                        <div class="row">
                                            <div class="col-md-3 border-right text-right">
                                                <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                            </div>

                                            <div class="col-md-9">
                                                <p class="text-muted mb-3">
                                                    @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                <p>{{ $message->message }}</p>
                                                @if ($message->attachments->count() > 0)
                                                    <div class="mt-2">
                                                        @foreach ($message->attachments as $k => $attachment)
                                                            <a href="{{ route('ticket.download', encrypt($attachment->id)) }}" class="text--muted text-decoration-underline"><i class="la la-file me-1"></i>@lang('Attachment') {{ ++$k }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="admin-support-ticket">
                                        <div class="row">
                                            <div class="col-md-3 border-right text-right">
                                                <h5 class="my-3">@lang('System Reply')</h5>
                                            </div>
                                            <div class="col-md-9">
                                                <p class="text-muted">
                                                    @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                <p>{{ $message->message }}</p>

                                                @if ($message->attachments()->count() > 0)
                                                    <div class="mt-2">
                                                        @foreach ($message->attachments as $k => $attachment)
                                                            <a href="{{ route('ticket.download', encrypt($attachment->id)) }}" class="text--base"><i class="fa fa-file"></i> @lang('Attachment') {{ ++$k }} </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <x-confirmation-modal />
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true);
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-6 removeFileInput">
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form--control form-control custom-file-input" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text border-0 removeFile"><i class="fas fa-times"></i></button>
                            </div>
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
