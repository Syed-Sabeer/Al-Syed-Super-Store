<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Class CartManager
 *
 * This class is responsible for managing cart-related operations.
 */
class CartManager
{

    private function userCartQuery()
    {
        $cartData = Cart::hasProduct();

        if (auth()->id()) {
            return $cartData->where('user_id', auth()->id());
        }

        return $cartData->where('session_id', getSessionId());
    }

    public function setCartCount()
    {
        return $this->userCartQuery()->count();
    }

    public function getCart()
    {
        $eagerLoadableRelations = [
            'product',
            'product.activeOffer',
            'product.brand:id,name',
            'product.categories:name',
            'productVariant',
        ];

        return $this->userCartQuery()->with($eagerLoadableRelations)->latest()->get();
    }

    public function getCartCategories($cartData = null)
    {
        $cartData    = $cartData ?? $this->getCart();
        $productIds  = $cartData->pluck('product_id')->unique()->toArray();

        return Category::whereHas('products', function ($product) use ($productIds) {
            $product->whereIn('products.id', $productIds);
        })->get('id');
    }

    public function insertUserToCart()
    {
        $cartItems = Cart::where('session_id', getSessionId())->where('user_id', 0)->get();

        foreach ($cartItems as $cartItem) {
            $existingCart = Cart::where('user_id', auth()->id())
                ->where('product_id', $cartItem->product_id)
                ->where('product_variant_id', $cartItem->product_variant_id)
                ->first();

            if ($existingCart && !$existingCart->product->is_downloadable) {
                $existingCart->quantity += $cartItem->quantity;
                $existingCart->save();
                $cartItem->delete();
            }
        }

        Cart::where('session_id', getSessionId())->update(['user_id' => auth()->id()]);
    }

    public function getSingleCartItem($id)
    {
        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())->find($id);
        }

        return Cart::where('session_id', getSessionId())->find($id);
    }

    public function subtotal($cartData = null)
    {
        $subtotal = 0;
        $cartData = $cartData ?? $this->getCart();

        if ($cartData->count()) {
            foreach ($cartData as $cart) {
                $productPrice  = $cart->product->prices($cart->productVariant);
                $subtotal      += $productPrice->sale_price * $cart->quantity;
            }
        }

        return $subtotal;
    }

    public function getCartItemSubtotal($cart)
    {
        $productPrice = $cart->product->prices($cart->productVariant);
        return ($productPrice->sale_price ?? $productPrice->regular_price)  * $cart->quantity;
    }

    public function isValidCoupon($coupon, $cartTotal, $cartsData = null)
    {

        $general      = gs();
        $minimumSpend = $coupon->minimum_spend;
        $maximumSpend = $coupon->maximum_spend;

        // Check Minimum Subtotal
        if ($minimumSpend && $cartTotal < $minimumSpend) {
            return ['error' => " You have to order a minimum amount of $minimumSpend $general->cur_text to avail yourself of this coupon."];
        }

        // Check Maximum Subtotal
        if ($maximumSpend && $cartTotal > $maximumSpend) {
            return ['error' => " You have to order a minimum amount of $maximumSpend $general->cur_text to avail yourself of this coupon."];
        }

        //Check Limit Per Coupon
        if ($coupon->usage_limit_per_coupon && $coupon->applied_coupons_count >= $coupon->usage_limit_per_coupon) {
            return ['error' => "This coupon has exceeded the maximum limit for usage"];
        }

        //Check Limit Per User
        if ($coupon->usage_limit_per_coupon && $coupon->user_applied_count >= $coupon->usage_limit_per_user) {
            return ['error' => "You have already reached the maximum usage limit for this coupon"];
        }

        if (!$cartsData) {
            $cartsData = cartManager()->getCart();
        }

        $couponCategories  = $coupon->categories->pluck('id')->toArray();
        $couponProducts    = $coupon->products->pluck('id')->toArray();

        $cartProducts      = $cartsData->pluck('product_id')->unique()->toArray();
        $missingProducts   = array_diff($cartProducts, $couponProducts);
        $missingProducts   = Product::with('categories')->whereIn('id', $missingProducts)->get();

        if ($missingProducts) {
            foreach ($missingProducts as $product) {
                $categories = $product->categories->pluck('id')->toArray();
                if (!array_intersect($categories, $couponCategories)) {
                    return ['error' => 'The coupon is not available for some products on your cart'];
                }
            }
        }

        // check exclude sale item products
        if ($coupon->exclude_sale_items) {
            $cartVariants      = $cartsData->whereNotNull('product_variant_id')->pluck('product_variant_id')->unique()->toArray();
            $invalidProducts = Product::whereIn('id', $cartProducts)
                ->where(function ($query) use ($cartVariants) {
                    $query->has('activeOffer')
                        ->orWhereRaw('sale_price > regular_price')
                        ->orWhereHas('productVariants', function ($q2) use ($cartVariants) {
                            $q2->whereIn('id', $cartVariants)
                                ->whereRaw('sale_price > regular_price');
                        });
                })->select('products.id', 'products.name')
                ->get();

            if ($invalidProducts->isNotEmpty()) {
                return ['error' => "The coupon is not available for some products on your cart"];
            }
        }

        return true;
    }


    public function getSingleCartItemByProduct($productId, $variantId)
    {
        $userId    = auth()->id() ?? 0;
        $cart      = Cart::where('product_id', $productId)->where('product_variant_id', $variantId);
        if ($userId) {
            $cart->where('user_id', $userId);
        } else {
            $cart->where('session_id', getSessionId());
        }

        return $cart->first();
    }

    public function getVariantDetails(Request $request, $product)
    {
        $variant = null;
        $error = null;

        if ($request->has('attribute_values')) {
            $attrValues = $request->attribute_values;
            sort($attrValues);
            $variant = $product->productVariants->where('attribute_values', $attrValues)->first();

            if (!$variant) {
                $error =  'Invalid product variant selected';
            }
        }

        return [$variant, $error];
    }

    public function checkCartQuantity($product, $variant, $stockQuantity, $cartQuantity)
    {
        if ($product->is_downloadable && $cartQuantity > 1) {
            return ['error' => 'Downloadable products can only be added once'];
        }

        if ($product->trackInventory($variant) && $cartQuantity > $stockQuantity) {
            return ['error' => 'Requested quantity is not available in our stock'];
        }
    }

    public function updateOrCreateCartItem($cartItem, $quantity, $productId = null, $variantId = null)
    {

        if (!$cartItem) {
            $cartItem                     = new Cart();
            $cartItem->product_id         = $productId;
            $cartItem->product_variant_id = $variantId;
            $cartItem->user_id            = auth()->id() ?? 0;
            $cartItem->session_id         = getSessionId();
        }

        $cartItem->quantity  = $quantity;
        $cartItem->save();

        $this->removeCouponFromSession();
    }

    public function removeCouponFromSession()
    {
        if (session()->has('coupon')) {
            session()->forget('coupon');
        }
    }

    public function getCouponByCode(string $code)
    {
        return Coupon::activeAndValid()->matchCode($code)
            ->with(['categories', 'products'])
            ->withCount('appliedCoupons')
            ->withCount(['appliedCoupons as user_applied_count' => function ($appliedCoupon) {
                $appliedCoupon->where('user_id', auth()->id());
            }])->first();
    }

    public function clearUserCart($userId)
    {
        Cart::where('user_id', $userId)->delete();
    }

    public function checkProductsPrice($cartData = null)
    {
        $cartData    = $cartData ?? $this->getCart();

        foreach ($cartData as $cartItem) {
            if (!$cartItem->product->prices($cartItem->productVariant)->regular_price) {
                return [
                    'status' => false,
                    'message' => 'Some of the products on your cart is not ready to sell yet'
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'Ok'
        ];
    }

    public function checkPhysicalProductExistence()
    {
        return $this->userCartQuery()->whereHas('product', function ($query) {
            $query->where('is_downloadable', Status::NO);
        })->exists();
    }
}
