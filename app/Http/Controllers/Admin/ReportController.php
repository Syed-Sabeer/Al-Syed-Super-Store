<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\StockLog;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function  salesReport()
    {
        $pageTitle = 'Sales Report';
        $logs = Order::isValidOrder()->delivered()->orderBy('id', 'desc')->searchable(['user:username'])->dateFilter()->withSum('orderDetail as total_product', 'quantity')->with('user')->paginate(getPaginate());

        $totalSalesProduct = OrderDetail::whereHas('order', function ($query) {
            $query->where('status', Status::ORDER_DELIVERED);
        })->sum('quantity');

        $totalSalesAmount = Order::isValidOrder()->delivered()->sum('subtotal');
        $totalShippingCharge = Order::isValidOrder()->delivered()->sum('shipping_charge');
        $totalAmount = Order::isValidOrder()->delivered()->sum('total_amount');

        return view('admin.reports.sales', compact('pageTitle', 'logs', 'totalSalesProduct', 'totalSalesAmount', 'totalShippingCharge', 'totalAmount'));
    }
}
