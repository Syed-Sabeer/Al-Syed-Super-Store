@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <div class="card custom--card">
        <div class="card-body">
            <h5 class="card-title">@lang('Flutterwave')</h5>
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
            <button type="button" class="btn btn--base w-100 h-45 mt-3" id="btn-confirm" onClick="payWithRave()">@lang('Pay Now')</button>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script>
        "use strict"
        var btn = document.querySelector("#btn-confirm");
        btn.setAttribute("type", "button");
        const API_publicKey = "{{ $data->API_publicKey }}";

        function payWithRave() {
            var x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: "{{ $data->customer_email }}",
                amount: "{{ $data->amount }}",
                customer_phone: "{{ $data->customer_phone }}",
                currency: "{{ $data->currency }}",
                txref: "{{ $data->txref }}",
                onclose: function() {},
                callback: function(response) {
                    var txref = response.tx.txRef;
                    var status = response.tx.status;
                    var chargeResponse = response.tx.chargeResponseCode;
                    if (chargeResponse == "00" || chargeResponse == "0") {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    } else {
                        window.location = '{{ url('ipn/flutterwave') }}/' + txref + '/' + status;
                    }
                    // x.close(); // use this to close the modal immediately after payment.
                }
            });
        }
    </script>
@endpush
