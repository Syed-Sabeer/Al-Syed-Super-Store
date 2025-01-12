<?php

namespace App\Lib;

use App\Models\Wishlist;

/**
 * Class WishlistManager
 *
 * This class is responsible for managing wishlist-related operations.
 */
class WishlistManager {

    public function getWishlistItemById($id) {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())->where('id', $id)->first();
        }
        return Wishlist::where('session_id', getSessionId())->where('id', $id)->first();
    }

    public function isProductExistInWishlist($productId) {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->exists();
        }
        return Wishlist::where('session_id', getSessionId())->where('product_id', $productId)->exists();
    }

    public function userWishlistQuery($checkProduct = true) {
        $wishlistData = Wishlist::query();

        if ($checkProduct) {
            $wishlistData->hasProduct();
        }

        if (auth()->check()) {
            return $wishlistData->where('user_id', auth()->id());
        }

        return $wishlistData->where('session_id', getSessionId());
    }

    public function getWishlistCount() {
        return $this->userWishlistQuery()->count();
    }

    public function getWishlist($limit = null, $pagination = false) {
        $eagerLoadableRelations = [
            'product',
            'product.productVariants',
            'product.brand',
            'product.categories'
        ];

        $wishlist = $this->userWishlistQuery()->with($eagerLoadableRelations)->orderBy('id', 'desc');

        if ($limit) {
            $wishlist->limit($limit);
        }

        if ($pagination) {
            return $wishlist->paginate(getPaginate());
        }

        return $wishlist->get();
    }

    public function insertUserToWishlist() {
        $wishlistItems = Wishlist::where('session_id', getSessionId())->where('user_id', 0)->get();

        foreach ($wishlistItems as $wishlistItem) {
            Wishlist::where('user_id', auth()->id())->where('product_id', $wishlistItem->product_id)->delete();
        }

        Wishlist::where('session_id', getSessionId())->update(['user_id' => auth()->id()]);
    }

    public function getSingleWishlistItem($id) {
        if (auth()->check()) {
            return Wishlist::where('user_id', auth()->id())->find($id);
        }

        return Wishlist::where('session_id', getSessionId())->find($id);
    }
}
