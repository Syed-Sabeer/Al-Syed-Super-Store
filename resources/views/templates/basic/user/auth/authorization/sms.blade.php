@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="py-60">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <div class="mb-4">
                        <h5 class="mb-2 lh-1">@lang('Verify Mobile Number')</h5>
                        <p class="short_desc">@lang('A 6 digit verification code sent to your mobile number') : +{{ showMobileNumber(auth()->user()->mobile) }}</p>
                    </div>
                    <form action="{{ route('user.verify.mobile') }}" method="POST" class="submit-form">
                        @csrf
                        @include($activeTemplate . 'partials.verification_code')
                        <div class="mb-3">
                            <button type="submit" class="btn btn--base w-100 h-45">@lang('Submit')</button>
                        </div>
                        <div class="form-group">
                            <p>
                                @lang('If you don\'t get any code'), <span class="countdown-wrapper">@lang('try again after') <span id="countdown" class="fw-bold">--</span> @lang('seconds')</span> <a href="{{ route('user.send.verify.code', 'sms') }}" class="try-again-link d-none"> @lang('Try again')</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var distance = Number("{{ @$user->ver_code_send_at->addMinutes(2)->timestamp - time() }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush
