<table class="table payment-table table--responsive--md">
    <thead>
        <tr>
            <th>@lang('Order ID')</th>
            <th>@lang('Products')</th>
            <th>@lang('Payment')</th>
            <th>@lang('Order')</th>
            <th>@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($orders as $order)
            <tr>
                <td> <a href="{{ route('user.order', $order->order_number) }}">{{ $order->order_number }}</a> </td>
                <td>{{ $order->orderDetail->sum('quantity') }}</td>
                <td>@php echo $order->paymentBadge() @endphp</td>
                <td>@php echo $order->statusBadge() @endphp </td>
                <td>
                    <a href="{{ route('user.order', $order->order_number) }}" class="btn btn-outline--light"> <i class="las la-desktop"></i> @lang('View')</a>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-muted text-center" colspan="100%">@lang('No order yet')</td>
            </tr>
        @endforelse
    </tbody>
</table>
