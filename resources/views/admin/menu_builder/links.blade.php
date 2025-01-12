<ul class="list-group list-group-flush">

    @foreach ($links as $link)
        <li class="list-group-item d-flex flex-wrap gap-1 px-0 flex-column">
            <span class="fw-semibold">{{ $link->name }} <a href="{{ url($link->uri) }}" title="@lang('Open Link')" class="ms-1 text-primary fw-normal" target="blank"><i class="la la-external-link"></i></a></span>

            <div>
                <span class="text--primary">{{ $link->uri }}</span>
                <button class="copy-btn ms-1 bg-transparent text-muted" data-uri="{{ $link->uri }}">
                    <i class="la la-copy"></i>
                </button>
                <small class="copied-text bg-dark px-1 text-white ms-2 d-none rounded">@lang('Copied')</small>
            </div>
        </li>
    @endforeach
</ul>

@push('script')
    <script>
        (function($) {
            "use strict";
            document.addEventListener('DOMContentLoaded', function() {
                const copyButtons = document.querySelectorAll('.copy-btn');
                copyButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const uri = this.dataset.uri;
                        button.classList.add('d-none');
                        const copiedText = this.nextElementSibling;

                        copiedText.classList.remove('d-none');
                        navigator.clipboard.writeText(uri).then(() => {
                            setTimeout(() => {
                                copiedText.classList.add('d-none');
                                button.classList.remove('d-none');
                            }, 500);
                        }).catch(err => {
                            console.error('Failed to copy text: ', err);
                        });
                    });
                });
            });
        })(jQuery);
    </script>
@endpush
