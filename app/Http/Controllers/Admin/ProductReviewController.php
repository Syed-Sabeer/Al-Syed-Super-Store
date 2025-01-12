<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{

    public function reviews(Request $request)
    {
        $pageTitle = "All Product Reviews";
        $reviews   = ProductReview::searchable(['review', 'rating', 'product:name', 'user:username'])
            ->with(['product', 'user'])
            ->whereHas('product')
            ->whereHas('user');

        if ($request->has('is_viewed') && $request->is_viewed != null) {
            $reviews = $reviews->where('is_viewed', $request->is_viewed);
        }

        $reviews = $reviews->latest()
            ->paginate(getPaginate());

        return view('admin.product.review.index', compact('pageTitle', 'reviews'));
    }

    public function trashedReviews()
    {
        $pageTitle = "All Product Reviews";
        $reviews   = ProductReview::onlyTrashed()
            ->with(['product', 'user'])
            ->whereHas('product')
            ->whereHas('user')
            ->latest()
            ->paginate(getPaginate());

        return view('admin.product.review.index', compact('pageTitle', 'reviews'));
    }

    public function reviewDelete($id)
    {
        $review  = ProductReview::where('id', $id)->withTrashed()->first();
        $product = $review->product;
        if ($review->trashed()) {
            $newReview = ProductReview::where('user_id', $review->user_id)->where('product_id', $review->product_id)->first();

            if ($newReview) {
                $notify[] = ['error', 'User already submitted another review'];
                return back()->withNotify($notify);
            }

            $review->restore();
            $notify[] = ['success', 'Review restored successfully'];
        } else {
            $review->delete();
            $notify[] = ['success', 'Review deleted successfully'];
        }

        $product->save();
        return back()->withNotify($notify);
    }

    public function view($id)
    {
        $review = ProductReview::find($id);
        $review->is_viewed = 1;
        $review->save();

        return response()->json([
            'status' => true
        ]);
    }
}
