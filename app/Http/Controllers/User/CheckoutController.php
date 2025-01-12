<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\CartManager;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    private $cartManager;

    public function __construct(CartManager $cartManager)
    {
        parent::__construct();
        $this->cartManager = $cartManager;
    }

    //============= checkout step start here ===================//
    public function shippingInfo()
    {
        $pageTitle = 'Shipping Information';

        $shippingAddresses = ShippingAddress::where('user_id', auth()->id())->get();
        $countries         = getCountries();

        return view('Template::user.checkout_steps.shipping_info', compact('pageTitle', 'shippingAddresses', 'countries'));
    }

    public function addShippingInfo(Request $request)
    {
        $ids = ShippingAddress::where('user_id', auth()->id())->pluck('id')->toArray();

        $request->validate([
            'shipping_address_id' => 'required|in:' . implode(',', $ids)
        ], [
            'shipping_address_id.required' => 'Shipping address is required',
            'shipping_address_id.in' => 'Invalid address selected'
        ]);

        $checkoutData = session('checkout_data');
        $checkoutData['shipping_address_id'] = $request->shipping_address_id;

        session()->put('checkout_data', $checkoutData);

        return to_route('user.checkout.delivery.methods');
    }

    public function deliveryMethods()
    {
        $pageTitle = 'Delivery Methods';
        $shippingMethods   = ShippingMethod::active()->get();

        return view('Template::user.checkout_steps.shipping_methods', compact('pageTitle', 'shippingMethods'));
    }

    public function addDeliveryMethod(Request $request)
    {
        $ids = ShippingMethod::active()->pluck('id')->toArray();

        $request->validate([
            'shipping_method_id' => 'required|in:' . implode(',', $ids)
        ], [
            'shipping_method_id.required' => 'Delivery type field is required',
            'shipping_method_id.in'       => 'Invalid delivery type selected'
        ]);

        $checkoutData = session('checkout_data');
        $checkoutData['shipping_method_id'] = $request->shipping_method_id;

        session()->put('checkout_data', $checkoutData);
        return to_route('user.checkout.payment.methods');
    }

    public function confirmation($orderNumber)
    {
        $order  = Order::where('order_number', $orderNumber)->where('user_id', auth()->id())->with('deposit', 'orderDetail.product',  'orderDetail.productVariant', 'appliedCoupon')->first();

        $pageTitle = 'Order Number -' . $order->order_number;

        return view('Template::user.checkout_steps.confirmation', compact('pageTitle', 'order'));
    }

    private function appliedCoupon($cartData, $subtotal)
    {
        $coupon = session('coupon');

        if (!$coupon) {
            return null;
        }

        // Match the coupon code with database and check is exists
        $coupon  = $this->cartManager->getCouponByCode($coupon['code']);

        if (!$coupon) {
            return ['error' => "Applied coupon is invalid or expired"];
        }


        $checkCoupon = $this->cartManager->isValidCoupon($coupon, $subtotal, $cartData);

        if (isset($checkCoupon['error'])) {
            return $checkCoupon;
        }

        $coupon->discount_amount = $coupon->discountAmount($subtotal);

        return $coupon;
    }

}
