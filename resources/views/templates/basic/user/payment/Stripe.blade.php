@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <form role="form" class="disableSubmission payment appPayment" id="payment-form" method="{{ $data->method }}" action="{{ $data->url }}">
        <div class="card custom--card">
            <div class="card-body">
                <h5 class="card-title">@lang('Stripe Hosted')</h5>
                <div class="card-wrapper mb-3"></div>

                @csrf
                <input type="hidden" value="{{ $data->track }}" name="track">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Name on Card')</label>
                            <div class="input-group">
                                <input type="text" class="form-control form--control" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus />
                                <span class="input-group-text"><i class="fa fa-font"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Card Number')</label>
                            <div class="input-group">
                                <input type="tel" class="form-control form--control" name="cardNumber" autocomplete="off" value="{{ old('cardNumber') }}" required autofocus />
                                <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">@lang('Expiration Date')</label>
                            <input type="tel" class="form-control form--control" name="cardExpiry" value="{{ old('cardExpiry') }}" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label class="form-label">@lang('CVC Code')</label>
                            <input type="tel" class="form-control form--control" name="cardCVC" value="{{ old('cardCVC') }}" autocomplete="off" required />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 text-end">
            <button class="btn btn--base h-45" type="submit"> @lang('Confirm Payment')</button>
        </div>
    </form>
@endsection

@push('script')
    <script src="{{ asset('assets/global/js/card.js') }}"></script>

    <script>
        (function($) {
            "use strict";
            var card = new Card({
                form: '#payment-form',
                container: '.card-wrapper',
                formSelectors: {
                    numberInput: 'input[name="cardNumber"]',
                    expiryInput: 'input[name="cardExpiry"]',
                    cvcInput: 'input[name="cardCVC"]',
                    nameInput: 'input[name="name"]'
                }
            });

            @if ($deposit->from_api)
                $('.appPayment').on('submit', function() {
                    $(this).find('[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                })
            @endif
        })(jQuery);
    </script>
@endpush
