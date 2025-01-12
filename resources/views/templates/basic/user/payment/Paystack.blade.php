@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <div class="card custom--card">
        <div class="card-body">
            <h5 class="card-title">@lang('Paystack')</h5>
            <form action="{{ route('ipn.' . $deposit->gateway->alias) }}" method="POST" class="text-center">
                @csrf
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        @lang('You have to pay '):
                        <span>{{ showAmount($deposit->final_amount) }} {{ __($deposit->method_currency) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        @lang('You will get '):
                        <span>{{ showAmount($deposit->amount) }} {{ __(gs('cur_text')) }}</span>
                    </li>
                </ul>
                <button type="button" class="btn btn--base w-100 mt-3 h-45" id="btn-confirm">@lang('Pay Now')</button>
                <script src="//js.paystack.co/v1/inline.js" data-key="{{ $data->key }}" data-email="{{ $data->email }}" data-amount="{{ round($data->amount) }}" data-currency="{{ $data->currency }}" data-ref="{{ $data->ref }}" data-custom-button="btn-confirm"></script>
            </form>
        </div>
    </div>
@endsection
