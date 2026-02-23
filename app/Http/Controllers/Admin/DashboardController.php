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
        $totalSales = Order::where('status', 'approved')->count();
        $totalRevenue = Order::where('status', 'approved')->sum('amount');
        $totalUsers = User::where('role', 'user')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Monthly sales data for Chart
        $salesData = Order::select(
            DB::raw('sum(amount) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%M') as months")
        )
            ->where('status', 'approved')
            ->whereYear('created_at', date('Y'))
            ->groupBy('months')
            ->get();

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('totalSales', 'totalRevenue', 'totalUsers', 'pendingOrders', 'salesData', 'notifications'));
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }
}
