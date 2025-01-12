<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $pageTitle = 'Review Products';

        $products = Product::join('order_details', 'products.id', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', 'orders.id')
            ->where('orders.status', Status::ORDER_DELIVERED)
            ->orderBy('orders.created_at', 'DESC')
            ->select('products.*')
            ->where('orders.user_id', auth()->id())
            ->distinct()
            ->paginate(getPaginate());

        return view('Template::user.orders.products_for_review', compact('pageTitle', 'products'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'pid'    => 'required|string',
            'review' => 'nullable|string',
            'rating' => 'required|numeric|in:1,2,3,4,5',
        ]);

        $user = auth()->user();

        OrderDetail::whereHas('order', function ($order) use ($user) {
            $order->where('user_id', $user->id)->where('status', Status::ORDER_DELIVERED);
        })->where('product_id', $request->pid)->firstOrFail();

        $review  = ProductReview::where('user_id', $user->id)->where('product_id', $request->pid)->first();

        if (!$review) {
            $review             = new ProductReview();
            $review->user_id    = $user->id;
            $review->product_id = $request->pid;
            $notification       = 'added';
        } else {
            $notification       = 'updated';
        }

        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        $notify[] = ['success', "Review $notification successfully"];
        return back()->withNotify($notify);
    }
}
