<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller {

    public function index() {
        $pageTitle  = "Product Types";
        $templates = ProductType::latest()->searchable(['name'])->paginate(getPaginate());
        return view('admin.product_type.index', compact('pageTitle', 'templates'));
    }

    public function create() {
        $pageTitle  = "Add New Product Type";
        return view('admin.product_type.form', compact('pageTitle'));
    }

    public function edit($id) {
        $template   = ProductType::find($id);
        $pageTitle  = "Edit Product Type";
        return view('admin.product_type.form', compact('pageTitle', 'template'));
    }

    public function store(Request $request, $id = 0) {
        $request->validate([
            'name'                                => 'required|string|unique:product_types,name,' . $id,
            'specification_group'                 => 'required|array|min:1',
            'specifications.*.name'               => 'required|string',
            'specification_group.*.attributes'    => 'required|array|min:1',
            'specification_group.*.attributes.*'  => 'required|string',
        ]);

        if ($id == 0) {
            $template = new ProductType();
            $notification  = 'New specification template created successfully';
        } else {
            $template = ProductType::findOrFail($id);
            $notification  = 'Specification template updated successfully';
        }

        $template->name          = $request->name;
        $template->specifications = $request->specification_group;
        $template->save();
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
