<?php

namespace App\Http\Controllers;

use App\Lib\WishlistManager;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller {
    private $wishlistManager;

    public function __construct(WishlistManager $wishlistManager) {
        parent::__construct();

        if(!gs('product_wishlist')){
            abort(404);
        }

        $this->wishlistManager = $wishlistManager;
    }

    public function partialWishlist() {
        $wishlist      = $this->wishlistManager->getWishlist(9);
        $wishlistCount = $this->wishlistManager->getWishlistCount();
        return view('Template::partials.wishlist', compact('wishlist', 'wishlistCount'));
    }

    public function wishlistItemsCount() {
        return response($this->wishlistManager->getWishlistCount());
    }

    public function wishList() {
        $pageTitle = 'My Wishlist';
        $wishlists = $this->wishlistManager->getWishlist();
        return view('Template::wishlist', compact('pageTitle', 'wishlists'));
    }

    public function addToWishList(int $productId) {

        $product = Product::published()->where('id', $productId)->exists();

        if (!$product) {
            return errorResponse('Product not found');
        }

        $sessionId = getSessionId();
        $userId = auth()->id() ?? 0;
        $wishlist = Wishlist::where('product_id', $productId);

        $userId ? $wishlist->where('user_id', $userId) : $wishlist->where('session_id', $sessionId);
        $wishlist = $wishlist->first();

        if ($wishlist) {
            $wishlist->delete();
            return successResponse('Removed from wishlist', [
                'listed' => false,
                'title' => trans('Add To Wishlist')
            ]);
        }

        $wishlist             = new Wishlist();
        $wishlist->user_id    = $userId;
        $wishlist->session_id = $sessionId;
        $wishlist->product_id = $productId;
        $wishlist->save();
        return successResponse('Added to wishlist', [
            "listed" => true,
            'title' => trans('Remove From Wishlist')
        ]);
    }

    public function remove($id) {
        if (!$id) {
            $wishlist = $this->wishlistManager->userWishlistQuery(false)->delete();
            $message = 'All items removed from wishlist';
        } else {
            $wishlist = $this->wishlistManager->getWishlistItemById($id);
            if (!$wishlist) {
                return response()->json(['error' => 'This product isn\'t available in your wishlist']);
            }
            $wishlist->delete();
            $message = 'Product removed from wishlist';
        }

        return successResponse($message);
    }
}
