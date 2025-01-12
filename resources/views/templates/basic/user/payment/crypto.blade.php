@extends($activeTemplate . 'layouts.checkout')
@section('blade')
    <div class="card card-deposit text-center">
        <div class="card-header card-header-bg">
            <h3>@lang('Payment Preview')</h3>
        </div>
        <div class="card-body card-body-deposit text-center">
            <h4 class="my-2"> @lang('PLEASE SEND EXACTLY') <span class="text-success"> {{ $data->amount }}</span> {{ __($data->currency) }}</h4>
            <h5 class="mb-2">@lang('TO') <span class="text-success"> {{ $data->sendto }}</span></h5>
            <img src="{{ getImage(null) }}" data-src="{{ $data->img }}" class="lazyload" alt="@lang('Image')">
            <h4 class="text-white bold my-4">@lang('SCAN TO SEND')</h4>
        </div>
    </div>
@endsection
