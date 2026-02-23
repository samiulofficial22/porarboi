<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Models\Notification;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // General Stats
        $totalSales = Order::where('payment_status', 'paid')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('amount');
        $totalUsers = User::where('role', 'user')->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();

        // Hybrid Stats
        $codOrdersCount = Order::where('payment_method', 'cod')->count();
        $onlineOrdersCount = Order::where('payment_method', 'online')->count();

        $physicalOrdersCount = Order::where('selected_format', 'hardcopy')->count();
        $digitalOrdersCount = Order::where('selected_format', 'pdf')->count();

        // Monthly revenue data for Chart
        $salesData = Order::select(
            DB::raw('sum(amount) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%M') as months")
        )
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('months')
            ->get();

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalRevenue',
            'totalUsers',
            'pendingOrders',
            'salesData',
            'notifications',
            'codOrdersCount',
            'onlineOrdersCount',
            'physicalOrdersCount',
            'digitalOrdersCount'
        ));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }
}
