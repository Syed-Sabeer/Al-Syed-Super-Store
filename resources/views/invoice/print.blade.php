<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $order->order_number }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
    <link rel="shortcut icon" href="{{ getImage('assets/images/logoIcon/favicon.png', '128x128') }}" type="image/x-icon">

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/fontawesome.all.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/invoice.css') }}">
    <link href="{{ asset($activeTemplateTrue . 'css/color.php?color=' . gs('base_color')) }}" rel="stylesheet">

</head>

<body onload="window.print()">
    <!-- Container -->
    <div class="container-fluid invoice-container">
        <div class="container-fluid p-0">
            <div class="card border-0">
                <div class="card-body">
                    <!-- Main content -->
                    <div class="invoice">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="list--row">
                                    <div class="logo-invoice float-left">
                                        <img src="{{ siteLogo('dark') }}" alt="@lang('logo')">
                                    </div>
                                    <ul class="m-0  float-right">
                                        <b>@lang('Order ID'):</b> {{ $order->order_number }}<br>
                                        <b>@lang('Order Date'):</b> {{ showDateTime($order->created_at, 'd/m/Y') }} <br>
                                        <b>@lang('Total Amount'):</b> {{ showAmount($order->total_amount) }}
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row invoice-info">
                            <div class="col-12">
                                <div class="list--row">
                                    <div class="float-left">
                                        <h5 class="mb-2">@lang('User Details')</h5>
                                        <address>
                                            <ul>
                                                <li>@lang('Name'): <strong>{{ @$order->user->fullname }}</strong>
                                                </li>
                                                <li>@lang('Address'): {{ @$order->user->address->address }}</li>
                                                <li>@lang('State'): {{ @$order->user->address->state }}</li>
                                                <li>@lang('City'): {{ @$order->user->address->city }}</li>
                                                <li>@lang('Zip'): {{ @$order->user->address->zip }}</li>
                                                <li>@lang('Country'): {{ @$order->user->address->country }}</li>
                                            </ul>
                                        </address>
                                    </div><!-- /.col -->
                                    @php
                                        $shippingAddress = json_decode($order->shipping_address);
                                    @endphp

                                    @if ($shippingAddress)
                                        <div class="float-right">
                                            <h5 class="mb-2">@lang('Shipping Address')</h5>

                                            <address>
                                                <ul>
                                                    <li>@lang('Name'): <strong>{{ $order->user->firstname }}
                                                            {{ $order->user->lastname }}</strong>
                                                    </li>
                                                    <li>@lang('Address'): {{ $shippingAddress->address }}</li>
                                                    <li>@lang('State'): {{ $shippingAddress->state }}</li>
                                                    <li>@lang('City'): {{ $shippingAddress->city }}</li>
                                                    <li>@lang('Zip'): {{ $shippingAddress->zip }}</li>
                                                    <li>@lang('Country'): {{ $shippingAddress->country }}</li>
                                                </ul>
                                            </address>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <!-- Table row -->

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table print-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>@lang('Product')</th>
                                            <th class="text-end">@lang('Price')</th>
                                            <th class="text-end">@lang('Quantity')</th>
                                            <th class="text-end">@lang('Total Price')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = $order->orderDetail->sum(function ($detail) {
                                                return $detail->price * $detail->quantity;
                                            });

                                            $totalDiscount = $order->orderDetail->sum('discount');
                                            $hasVariant = false;
                                        @endphp

                                        @foreach ($order->orderDetail as $data)
                                            @php
                                                $hasVariant = $hasVariant ?? ($data->hasVariant ? true : false);
                                            @endphp
                                            <tr>
                                                <td>
                                                   <span class="me-2 fw-bold">{{ $loop->iteration }}.</span>
                                                    {{ @$data->product->name }}
                                                    @if ($data->productVariant)
                                                        - {{ @$data->productVariant->name }}
                                                    @endif
                                                </td>
                                                <td class="text-end">{{ showAmount($data->price) }}</td>
                                                <td class="text-end">{{ $data->quantity }}</td></td>
                                                <td class="text-end">{{ showAmount($data->price * $data->quantity) }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <div class="row mt-4">
                            <!-- accepted payments column -->
                            <div class="col-lg-6">
                                @if (isset($order->deposit) && $order->deposit->status != Status::PAYMENT_INITIATE)
                                    <div class="table-responsive">
                                        <table class="table print-payment-table border-0">
                                            <tbody>
                                                <tr>
                                                    <td width="50%">@lang('Payment Method')</td>
                                                    <td width="50%" class="text-end">
                                                        @if ($order->deposit->method_code == 0)
                                                            <span data-bs-toggle="tooltip" title="@lang('Cash On Delivery')">@lang('COD')</span>
                                                        @else
                                                            <span data-bs-toggle="tooltip" title="{{ __(@$order->deposit->gateway->name) }}">{{ __(@$order->deposit->gateway->name) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>@lang('Payment Charge')</td>
                                                    <td class="text-end">
                                                        {{ $charge = getAmount(@$order->deposit->charge) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>@lang('Total Payment Amount') </td>
                                                    <td class="text-end">
                                                        {{ getAmount($order->deposit->amount + $charge) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div><!-- /.col -->
                            <div class="col-lg-6 subtotal-container">
                                <div class="table-responsive">
                                    <table class="table print-payment-table border-0">
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold">@lang('Subtotal')</td>
                                                <td class="text-end" width="50%">{{ showAmount($subtotal) }}</td>
                                            </tr>
                                            @if ($order->appliedCoupon)
                                                <tr>
                                                    <td>(-) @lang('Coupon')
                                                        ({{ $order->appliedCoupon->coupon->coupon_code }})</td>
                                                    <td class="text-end">{{ showAmount($order->appliedCoupon->amount) }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td>(+) @lang('Shipping')</td>
                                                <td class="text-end">{{ showAmount($order->shipping_charge) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">@lang('Total')</td>
                                                <td class="text-end fw-bold">{{ showAmount($order->total_amount) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
