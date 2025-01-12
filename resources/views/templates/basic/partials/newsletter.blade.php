@if (gs('subscriber_module'))
    @php
        $newsletter = getContent('newsletter.content', true);
    @endphp

    <div class="newsletter-widget text-center">
        <div class="newsletter-widget-header">
            <h6 class="title">{{ __($newsletter->data_values->heading) }}</h6>
            <p class="fs-14">{{ __($newsletter->data_values->description) }}</p>
        </div>
        <form action="#" class="subscribe-form" method="POST">
            <input type="email" placeholder="@lang('Your Email Address')..." name="email">
            <button type="submit" class="subscribe-btn"><span>@lang('Subscribe')</span> <i class="las la-paper-plane"></i></button>
        </form>
    </div>

    @push('script')
        <script>
            'use strict';
            (function($) {
                $(document).on('submit', '.subscribe-form', function(e) {
                    e.preventDefault();

                    let data = {
                        email: $('input[name="email"]').val(),
                        _token: `{{ csrf_token() }}`,
                    };

                    if (!data.email) {
                        notify('error', `@lang('The email field is required')`);
                        return false;
                    }

                    $.post("{{ route('subscribe') }}", data,
                        function(response) {
                            $('.subscribe-btn').removeAttr('disabled');
                            if (response.status) {
                                notify('success', response.message);
                            } else {
                                notify('error', response.message);
                            }

                            $('[name=email]').val('');
                        },
                    );
                });
            })
            (jQuery)
        </script>
    @endpush
@endif
