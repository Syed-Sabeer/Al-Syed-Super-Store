@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <div class="card custom--card">
        <div class="card-body">
            <h5 class="card-title">@lang('Razorpay')</h5>
            <ul class="list-group list-group-flush text-center">
                <li class="list-group-item d-flex justify-content-between">
                    @lang('You have to pay '):
                    <span>{{ showAmount($deposit->final_amount) }} {{ __($deposit->method_currency) }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    @lang('You will get '):
                    <span>{{ showAmount($deposit->amount) }} {{ __(gs('cur_text')) }}</span>
                </li>
            </ul>
            <form action="{{ $data->url }}" method="{{ $data->method }}">
                <input type="hidden" custom="{{ $data->custom }}" name="hidden">
                <script src="{{ $data->checkout_js }}" @foreach ($data->val as $key => $value)
            data-{{ $key }}="{{ $value }}" @endforeach></script>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).find('.razorpay-payment-button').addClass('btn btn--base h-45 w-100').removeClass('razorpay-payment-button');
        })(jQuery);
    </script>
@endpush
