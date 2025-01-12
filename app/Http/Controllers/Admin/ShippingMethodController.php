<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller {

    public function index() {
        $pageTitle       = 'Shipping Method';
        $shippingMethods = ShippingMethod::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.shipping_method.index', compact('pageTitle', 'shippingMethods'));
    }

    public function create() {
        $pageTitle = 'Create New Shipping Method';
        return view('admin.shipping_method.create', compact('pageTitle'));
    }

    public function edit($id) {
        $shippingMethod = ShippingMethod::findOrFail($id);
        $pageTitle      = 'Edit Shipping Method';
        return view('admin.shipping_method.create', compact('pageTitle', 'shippingMethod'));
    }

    public function store(Request $request, $id = 0) {
        $this->validation();

        if ($id == 0) {
            $shippingMethod = new ShippingMethod();
            $notification   = 'created';
        } else {
            $shippingMethod = ShippingMethod::findOrFail($id);
            $notification   = 'updated';
        }
        $shippingMethod->name          = $request->name;
        $shippingMethod->charge        = $request->charge;
        $shippingMethod->shipping_time = $request->deliver_in;
        $shippingMethod->description   = $request->description;
        $shippingMethod->save();
        $notify[] = ['success', "Shipping method $notification successfully"];
        return back()->withNotify($notify);
    }

    private function validation() {
        request()->validate([
            'name'        => 'required|string',
            'charge'      => 'required|numeric|min:0',
            'deliver_in'  => 'nullable|integer',
            'description' => 'nullable|string|',
        ]);
    }

    public function changeStatus(Request $request) {
        $method         = ShippingMethod::findOrFail($request->id);
        $method->status = !$method->status;
        $method->save();
        $message = $method->status ? 'Shipping method activated successfully' : 'Shipping method deactivated successfully';
        return response()->json(['success' => true, 'message' => $message]);
    }
}
