<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'book'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'book']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'approved') {
            $data['approved_at'] = now();
        }
        else {
            $data['approved_at'] = null;
        }

        $order->update($data);

        if ($request->status === 'approved') {
            Notification::create([
                'user_id' => $order->user_id,
                'book_id' => $order->book_id,
                'type' => 'order_approved',
                'message' => "Your order for '{$order->book->title}' has been approved! You can now download it from your library.",
            ]);
        }
        elseif ($request->status === 'rejected') {
            Notification::create([
                'user_id' => $order->user_id,
                'book_id' => $order->book_id,
                'type' => 'order_rejected',
                'message' => "Sorry, your order for '{$order->book->title}' was rejected. Please contact support for more details.",
            ]);
        }

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        if ($order->payment_screenshot) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_screenshot);
        }
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
