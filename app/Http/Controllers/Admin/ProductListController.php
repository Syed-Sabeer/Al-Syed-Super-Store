<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductListController extends Controller
{

    public function index()
    {
        $pageTitle = "All Products";
        $trashed   = false;
        $products  = Product::searchable(['name', 'slug'])
            ->filter(['is_published', 'brand_id', 'is_showable', 'is_downloadable', 'product_type_id', 'product_type', 'is_published']);


        if (request()->has('category')) {
            $products = $products->whereHas('categories', function ($query) {
                $query->where('categories.id', request()->category);
            });
        }

        if (request()->sort_by) {
            if (request()->sort_by == 'price_htl') {
                $products->orderBy('regular_price', 'DESC');
            } else if (request()->sort_by == 'price_lth') {
                $products->orderBy('regular_price', 'ASC');
            } else if (request()->sort_by == 'oldest') {
                $products->orderBy('id', 'ASC');
            } else if (request()->sort_by == 'latest') {
                $products->orderBy('id', 'DESC');
            }
        } else {
            $products->orderBy('id', 'desc');
        }

        $products = $products->with(['categories', 'brand', 'productVariants'])->paginate(getPaginate());

        return view('admin.product.index', compact('pageTitle', 'products', 'trashed'));
    }

    public function trashed()
    {
        $pageTitle = "Trashed Products";
        $trashed   = true;
        $products  = Product::searchable(['name'])
            ->with(['brand', 'categories'])
            ->onlyTrashed()
            ->orderByDesc('id')
            ->paginate(getPaginate());

        return view('admin.product.index', compact('pageTitle', 'products', 'trashed'));
    }
    public function lowStock()
    {
        $pageTitle = "Low Stock Products";
        $trashed   = false;
        $products  = Product::lowStock()
            ->searchable(['name'])
            ->with(['brand', 'categories'])
            ->orderByDesc('id')
            ->paginate(getPaginate());

        return view('admin.product.index', compact('pageTitle', 'products', 'trashed'));
    }

    public function outOfStock()
    {
        $pageTitle = "Out Of Stock Products";
        $trashed   = false;
        $products  = Product::outOfStock()
            ->searchable(['name'])
            ->with(['brand', 'categories'])
            ->orderByDesc('id')
            ->paginate(getPaginate());

        return view('admin.product.index', compact('pageTitle', 'products', 'trashed'));
    }

    public function topSelling()
    {
        $pageTitle = 'Top Selling Products';
        $trashed = false;
        $products = Product::topSales(paginate: true);
        return view('admin.product.index', compact('pageTitle', 'products', 'trashed'));
    }
}
