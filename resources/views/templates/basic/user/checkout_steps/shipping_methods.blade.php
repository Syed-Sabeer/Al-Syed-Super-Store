@extends($activeTemplate . 'layouts.checkout')

@section('blade')
    <div class="address-wrapper">
        <div class="address-type-content">
            <h6 class="mb-3">@lang('Choose Delivery Type')</h6>

            <div class="delivery-type-wrapper">
                @foreach ($shippingMethods as $item)
                    <label for="method-{{ $item->id }}" class="delivery-type">
                        <span class="form--check d-flex">
                            <input class="form-check-input mt-0" type="radio" name="shipping_method_id" value="{{ $item->id }}" form="deliveryMethodForm" id="method-{{ $item->id }}" data-resource="{{ $item }}" @checked($loop->first) required>
                        </span>

                        <span class="delivery-type-name">
                            {{ __($item->name) }}
                        </span>
                    </label>

                    <div class="methodInfo mt-3 delivery-type-content">
                        <div class="address-item-inner">
                            <span class="address-item-label">@lang('Delivered In')</span>
                            <span class="address-item-value"> <span class="item-devide">:</span> {{ $item->shipping_time }} @lang('Days')</span>
                        </div>

                        <div class="address-item-inner">
                            <span class="address-item-label">@lang('Delivery Charge')</span>
                            <span class="address-item-value">
                                <span class="item-devide">:</span>
                               {{ showAmount($item->charge) }}
                            </span>
                        </div>

                        @if(strip_tags($item->description))
                            <div class="address-item-inner bg-light p-3 rounded-3 ">
                                <p>
                                    @php echo $item->description @endphp
                                </p>
                            </div>
                         @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between mt-4 flex-wrap">
        <a href="{{ route('user.checkout.shipping.info') }}" class="text--base ">
            <i class="las la-angle-left"></i> @lang('Back to Shipping Info')
        </a>
        <form action="{{ route('user.checkout.delivery.method.add') }}" method="POST" id="deliveryMethodForm">
            @csrf
            <button type="submit" class="btn btn--base h-45">@lang('Continue to Next') <i class="la la-angle-right"></i></button>
        </form>
    </div>
@endsection

@push('style')
    <style>
        .address-item-inner p {
            margin-bottom: 0 !important;
        }

        .delivery-type-content{
            display: none;
            order: 2;
        }

        .delivery-type{
            order: 0;
        }

        .delivery-type-wrapper:has(.delivery-type:first-of-type input:checked) .delivery-type-content:first-of-type{
            display: block;
        }

         .delivery-type-wrapper:has(.delivery-type:last-of-type input:checked) .delivery-type-content:last-of-type{
            display: block;
        }
    </style>
@endpush
