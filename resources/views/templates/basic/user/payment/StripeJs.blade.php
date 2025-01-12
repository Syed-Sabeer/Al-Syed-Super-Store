@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <div class="card custom--card">
        <div class="card-body">
            <h5 class="card-title">@lang('Stripe Storefront')</h5>
            <ul class="list-group list-group-flush text-center list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    @lang('You have to pay '):
                    <span>{{ showAmount($deposit->final_amount) }} {{ __($deposit->method_currency) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    @lang('You will get '):
                    <span>{{ showAmount($deposit->amount) }} {{ __(gs('cur_text')) }}</span>
                </li>
            </ul>
        </div>
    </div>
    <form action="{{ $data->url }}" method="{{ $data->method }}" class="mt-3 text-end">
        <script src="{{ $data->src }}" class="stripe-button" @foreach ($data->val as $key => $value)
        data-{{ $key }}="{{ $value }}" @endforeach></script>
    </form>
@endsection

@push('script-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).find('.stripe-button-el').addClass("btn btn--base h-45").text("Confirm Payment").removeClass('stripe-button-el');
        })(jQuery);
    </script>
@endpush
