<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockLog;

class ProductStockController extends Controller
{
    public function stockLogByProduct($id)
    {
        $product = Product::findOrFail($id);
        $logs = StockLog::where('product_id', $product->id)->dateFilter()->with('order:id,order_number')->orderBy('id', 'desc')->paginate(getPaginate());
        $pageTitle = 'Stock Log 0f ' . $product->name;

        return view('admin.product.stock_log', compact('logs', 'pageTitle'));
    }

    public function stockLogByVariant($id)
    {
        $productVariant = ProductVariant::with('product:id,name')->findOrFail($id);
        $logs = StockLog::where('product_variant_id', $productVariant->id)->dateFilter()->with('order:id,order_number')->orderBy('id', 'desc')->paginate(getPaginate());
        $pageTitle = 'Stock Log 0f ' . $productVariant->product->name . ' (' . $productVariant->name . ')';

        return view('admin.product.stock_log', compact('logs', 'pageTitle'));
    }
}
