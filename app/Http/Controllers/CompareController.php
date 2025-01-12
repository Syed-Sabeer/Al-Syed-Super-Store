<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class CompareController extends Controller {

    public function __construct() {
        parent::__construct();

        if (!gs('product_compare')) {
            abort(404);
        }
    }

    public function addToCompare(Request $request) {

        $product   = Product::published()->find($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }

        if (!$product->product_type_id) {
            return response()->json(['error' => 'This product cannot be added to the comparison list']);
        }

        $compare = session('compare', []);

        if (isset($compare[$product->id])) {
            unset($compare[$product->id]);
            session()->put('compare', $compare);
            return response()->json(['success' => 'Removed from the comparison list', 'type'=> 'REMOVED']);
        }

        $productIds      = array_keys(session('compare') ?? []);
        $compareProducts = Product::published()->with('categories', 'activeOffer', 'reviews', 'brand')->whereIn('id', $productIds)->get();

        if (!blank($compareProducts)) {
            $firstProduct   = $compareProducts->first();
            if (!$firstProduct) {
                return response()->json(['error' => 'Something went wrong']);
            }
            if ($product->product_type_id != $firstProduct->product_type_id) {
                return response()->json(['error' => 'The product type doesn\'t match', 'type' => 'ADDED']);
            }
        }

        $compare[$product->id] = ['id' => $product->id];
        session()->put('compare', $compare);

        return response()->json(['success' => 'Added to the comparison list', 'type' => 'ADDED']);
    }

    public function compare() {
        $pageTitle       = 'Product Comparison';
        $productIds      = array_keys(session('compare') ?? []);
        $compareProducts = Product::published()->with('categories', 'activeOffer', 'reviews', 'brand')->whereIn('id', $productIds)->get();
        $specificationTemplate = ProductType::find(@$compareProducts->first()?->product_type_id);
        return view('Template::compare', compact('pageTitle', 'compareProducts', 'specificationTemplate'));
    }

    public function compareProductsCount() {
        if (!session('compare')) {
            return response(['total' => 0]);
        }

        $productIds   = array_keys(session('compare', []));
        $productCount = Product::published()->whereIn('id', $productIds)->count();
        return response(['total' => $productCount]);
    }

    public function removeFromCompare($id = null) {

        $compare = session('compare');

        if (!$id) {
            session()->forget('compare');
            $notify[] = ['success', 'Product comparison cleared successfully'];
            return back()->withNotify($notify);
        }

        if (isset($compare[$id])) {
            unset($compare[$id]);
            session()->put('compare', $compare);

            $notify[] = ['success', 'Product removed successfully'];
            return back()->withNotify($notify);
        }

        $notify[] = ['error', 'Something went wrong'];
        return back()->withNotify($notify);
    }
}
