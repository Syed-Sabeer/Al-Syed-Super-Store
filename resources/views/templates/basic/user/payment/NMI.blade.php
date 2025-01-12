@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <div class="card custom--card">
        <div class="card-body">
            <h5 class="card-title">@lang('Payment via NMI')</h5>
            <form role="form" class="disableSubmission appPayment" id="payment-form" method="{{$data->method}}" action="{{$data->url}}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">@lang('Card Number')</label>
                        <div class="input-group">
                            <input type="tel" class="form-control form--control" name="billing-cc-number" autocomplete="off" value="{{ old('billing-cc-number') }}" required autofocus />
                            <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <label class="form-label">@lang('Expiration Date')</label>
                        <input type="tel" class="form-control form--control" name="billing-cc-exp" value="{{ old('billing-cc-exp') }}" placeholder="e.g. MM/YY" autocomplete="off" required />
                    </div>
                    <div class="col-md-6 ">
                        <label class="form-label">@lang('CVC Code')</label>
                        <input type="tel" class="form-control form--control" name="billing-cc-cvv" value="{{ old('billing-cc-cvv') }}" autocomplete="off" required />
                    </div>
                </div>
                <br>
                <button class="btn btn--base w-100 h-45" type="submit"> @lang('Submit')</button>
            </form>
        </div>
    </div>
@endsection

@if($deposit->from_api)
    @push('script')
    <script>
        (function($){
            "use strict";
            $('.appPayment').on('submit',function(){
                $(this).find('[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
            })
        })(jQuery);
    </script>
    @endpush
@endif
