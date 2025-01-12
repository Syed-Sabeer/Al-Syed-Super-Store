<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckoutStepMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $module)
    {
        $checkoutData = session('checkout_data');

        $hasPhysicalProduct = cartManager()->checkPhysicalProductExistence();

        if ($module == 'shipping_info') {
            if (!cartManager()->setCartCount()) {
                return to_route('cart.page');
            }
        } elseif ($module == 'delivery_method' && !@$checkoutData['shipping_address_id']) {
            return to_route('user.checkout.shipping.info');
        } elseif ($module == 'payment' && $hasPhysicalProduct && !@$checkoutData['shipping_method_id']) {
            return to_route('user.checkout.delivery.methods');
        }

        return $next($request);
    }
}
