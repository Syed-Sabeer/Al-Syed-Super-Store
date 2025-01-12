<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\ProductManager;
use App\Models\DigitalFile;
use App\Models\Order;
use App\Models\UserNotification;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function ordered()
    {
        $pageTitle = "All Orders";
        $orders    = $this->orderData();
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function codOrders()
    {
        $pageTitle = "COD Orders";
        $orders    = $this->orderData('cod');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function pending()
    {
        $pageTitle = "Pending Orders";
        $orders    = $this->orderData('pending');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function onProcessing()
    {
        $pageTitle = "Orders on Processing";
        $orders    = $this->orderData('processing');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function dispatched()
    {
        $pageTitle = "Orders Dispatched";
        $orders    = $this->orderData('dispatched');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function canceledOrders()
    {
        $pageTitle = "Canceled Orders";
        $orders    = $this->orderData('canceled');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function returned()
    {
        $pageTitle = "Returned Orders";
        $orders    = $this->orderData('returned');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    public function deliveredOrders()
    {
        $pageTitle = "Delivered Orders";
        $orders    = $this->orderData('delivered');
        return view('admin.order.all', compact('pageTitle', 'orders'));
    }

    private function orderData($scope = null)
    {
        $orders = Order::isValidOrder();

        if($scope) {
            $orders->$scope();
        }
        return $orders->searchable(['order_number', 'user:username'], false)
            ->with([
                'user',
                'deposit',
                'deposit.gateway',
                'afterSaleDownloadableProducts:id,name,is_downloadable,delivery_type'
            ])
            ->orderBy('id', 'DESC')
            ->paginate(getPaginate());
    }

    public function orderDetails($id)
    {
        $pageTitle = 'Order Details';
        $order     = Order::isValidOrder()->where('id', $id)->with('user', 'deposit', 'deposit.gateway', 'orderDetail.product', 'orderDetail.productVariant', 'appliedCoupon')->firstOrFail();

        return view('admin.order.detail', compact('order', 'pageTitle'));
    }

    public function changeStatus(Request $request, $id)
    {
        $order = Order::isValidOrder()->with('afterSaleDownloadableProducts', 'orderDetail', 'user', 'deposit')->findOrFail($id);
        if ($order->status == Status::ORDER_DELIVERED) {
            $notify[] = ['error', 'This order has already been delivered'];
            return back()->withNotify($notify);
        }

        // if order has downloadable product, then admin have to upload file
        if ($order->status == Status::ORDER_PENDING && $order->afterSaleDownloadableProducts->count()) {
            $this->validateAfterSaleDownloadableFiles($request, $order->afterSaleDownloadableProducts);
        }

        if ($order->status == Status::ORDER_PENDING && !$order->hasPhysicalProduct()) {
            $order->status = Status::ORDER_DELIVERED;
            if ($order->afterSaleDownloadableProducts->count()) {
                $this->saveAfterSaleDownloadableFiles($request, $order); //while physical product not exist in the order
            }
        } else {
            $order->status += 1;
        }

        $order->save();

        if ($order->status == Status::ORDER_PROCESSING) {
            $action = 'Processing';
        } elseif ($order->status == Status::ORDER_DISPATCHED) {
            $action = 'Dispatched';
        } elseif ($order->status == Status::ORDER_DELIVERED) {
            $action = 'Delivered';

            if ($order->is_cod) {
                $order->payment_status = Status::PAYMENT_SUCCESS;
                $order->save();

                $deposit = $order->deposit;
                $deposit->status = Status::PAYMENT_SUCCESS;
                $deposit->save();
            }
        }

        $this->sendOrderMail($order);

        // while physical product and after sale downloadable product are together
        if ($order->status == Status::ORDER_PROCESSING  && $order->afterSaleDownloadableProducts->count() && $order->hasPhysicalProduct()) {
            $this->saveAfterSaleDownloadableFiles($request, $order);
        }

        if ($order->hasDownloadableProduct() && $order->status == Status::ORDER_DELIVERED) {
            notify($order->user, 'DOWNLOAD_DIGITAL_PRODUCT');
        }

        $notify[] = ['success', 'Order status changed to ' . strtolower($action)];
        return back()->withNotify($notify);
    }

    private function validateAfterSaleDownloadableFiles($request, $products)
    {
        $rules = ['download_file' => 'required|array'];
        foreach ($products as $product) {
            $rules["download_file.$product->id"] = ['required', new FileTypeValidate(['zip'])];
        }

        $messages = [
            'download_file.required' => 'The downloadable file field is required',
            'download_file.*required' => 'The downloadable file field is required'
        ];

        $request->validate($rules, $messages);
    }

    private function saveAfterSaleDownloadableFiles($request, $order)
    {
        foreach ($order->afterSaleDownloadableProducts as $key => $downloadProduct) {
            try {
                $orderDetail = $order->orderDetail->where('product_id', $downloadProduct->id)->first();
                $file = $request->download_file[$downloadProduct->id];

                // store downloadable file
                $digitalFile = $orderDetail->digitalFile ?? new DigitalFile();
                $digitalFile->name = fileUploader($file, getFilePath('digitalProductFile'), old: @$orderDetail->digitalFile->name);
                $orderDetail->digitalFile()->save($digitalFile);
            } catch (\Throwable $th) {
                Log::error("Error in saveAfterSaleDownloadableFiles: " . $th->getMessage());
            }
        }
    }

    public function cancelStatus($id)
    {
        $order = Order::isValidOrder()->with('orderDetail', 'orderDetail.product', 'orderDetail.productVariant', 'appliedCoupon')->findOrFail($id);
        if ($order->status != Status::ORDER_PENDING && $order->status != Status::ORDER_PROCESSING) {
            $notify[] = ['error', 'You can\'t cancel the order'];
            return back()->withNotify($notify);
        }

        // update stock
        $productManager = new ProductManager();
        foreach ($order->orderDetail as $key => $orderDetail) {
            $description = "Canceled order of $orderDetail->quantity " . Str::plural('product', $orderDetail->quantity);
            $productManager->createStockLog($orderDetail->product, $orderDetail->quantity, $description, $orderDetail->productVariant, '+');
        }

        if ($order->appliedCoupon) {
            $order->appliedCoupon->delete();
        }

        $order->status = Status::ORDER_CANCELED;
        $order->save();

        $this->sendOrderMail($order);

        $notify[] = ['success', 'Order status changed to canceled'];
        return back()->withNotify($notify);
    }

    public function return($id)
    {
        $order = Order::isValidOrder()->dispatched()->with('orderDetail', 'orderDetail.product', 'orderDetail.productVariant')->findOrFail($id);

        foreach ($order->orderDetail as $orderDetail) {
            $product = $orderDetail->product;
            $productVariant = $orderDetail->productVariant;
            if ($productVariant) {
                if ($productVariant->manage_stock && $productVariant->track_inventory) {
                    $productVariant->in_stock += $orderDetail->quantity;
                    $productVariant->save();
                } elseif ($product->track_inventory) {
                    $product->in_stock += $orderDetail->quantity;
                    $product->save();
                }
            } else {
                if ($product->track_inventory) {
                    $product->in_stock += $orderDetail->quantity;
                    $product->save();
                }
            }
        }

        $order->status = Status::ORDER_RETURNED;
        $order->save();

        $notify[] = ['success', 'Order return processed successfully'];
        return back()->withNotify($notify);
    }

    private function sendOrderMail($order)
    {
        $shortCode = [
            'site_name' => gs('sitename'),
            'order_id'  => $order->order_number,
        ];

        $userNotification = new UserNotification();
        $userNotification->user_id = $order->id;
        $title = 'Order #' . $order->order_number;

        if ($order->status == Status::ORDER_PROCESSING) {
            $template = 'ORDER_ON_PROCESSING_CONFIRMATION';
            $title .= ' is processing';
        } elseif ($order->status == Status::ORDER_DISPATCHED) {
            $template = 'ORDER_DISPATCHED_CONFIRMATION';
            $title .= ' has been dispatched';
        } elseif ($order->status == Status::ORDER_DELIVERED) {
            $template = 'ORDER_DELIVERY_CONFIRMATION';
            $title .= ' has been delivered';
        } elseif ($order->status == Status::ORDER_CANCELED) {
            $template = 'ORDER_CANCELLATION_CONFIRMATION';
            $title .= ' has been cancelled';
        }

        $userNotification->title = $title;
        $userNotification->click_url = urlPath('user.order', $order->order_number);
        $userNotification->save();

        notify($order->user, $template, $shortCode);
    }
}
