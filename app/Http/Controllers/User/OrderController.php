<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    public function orders($type)
    {
        $scopes = ['all', 'pending', 'processing', 'dispatched', 'completed', 'canceled'];
        if (!in_array($type, $scopes)) {
            abort(404);
        }

        $pageTitle = ucfirst($type) . ' Orders';

        $orders    = Order::isValidOrder()->where('user_id', auth()->id());

        if ($type == 'pending') {
            $orders->where('status', Status::ORDER_PENDING);
        } elseif ($type == 'processing') {
            $orders->where('status', Status::ORDER_PROCESSING);
        } elseif ($type == 'dispatched') {
            $orders->where('status', Status::ORDER_DISPATCHED);
        } elseif ($type == 'completed') {
            $orders->where('status', Status::ORDER_DELIVERED);
        } elseif ($type == 'canceled') {
            $orders->where('status', Status::ORDER_CANCELED);
        }

        $orders = $orders->with('orderDetail')->latest()->paginate(getPaginate());
        return view('Template::user.orders.index', compact('pageTitle', 'orders', 'type'));
    }

    public function orderDetails($orderNumber)
    {
        $pageTitle = 'Order Details';
        $order     = Order::isValidOrder()->where('order_number', $orderNumber)->where('user_id', auth()->id())->with('deposit', 'orderDetail.product',  'orderDetail.productVariant', 'appliedCoupon')->first();

        return view('Template::user.orders.details', compact('order', 'pageTitle'));
    }

    public function printInvoice($order)
    {
        $pageTitle = 'Print Invoice';
        $order     = Order::isValidOrder()->with('orderDetail.product', 'orderDetail.productVariant')->where('user_id', auth()->id())->findOrFail($order);

        return view('invoice.print', compact('pageTitle', 'order'));
    }

    public function download($id) {
        try {
            $id = decrypt($id);
            $orderDetail = OrderDetail::whereHas('order', function ($query) {
                $query->delivered()->where('payment_status', Status::PAYMENT_SUCCESS)->where('user_id', auth()->id());
            })->with('digitalFile.fileable', 'product.digitalFile')->findOrFail($id);

            $digitalFile = $orderDetail->digitalFile ?? $orderDetail->product->digitalFile;

            if (!$digitalFile) {
                $notify[] = ['error', 'The file you are looking for does not exist.'];
                return back()->withNotify($notify);
            }

            $fullPath = getFilePath('digitalProductFile') . '/' . $digitalFile->name;

            $mimetype = mime_content_type($fullPath);
            header('Content-Disposition: attachment; filename="' . slug($orderDetail->product->name) . '.' . pathinfo($digitalFile->name, PATHINFO_EXTENSION) . '";');
            header("Content-Type: " . $mimetype);
            return readfile($fullPath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'The file you are looking for does not exist.'];
            return back()->withNotify($notify);
        }
    }
}
