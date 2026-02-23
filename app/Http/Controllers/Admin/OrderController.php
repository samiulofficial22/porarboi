<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Notification;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'book']);

        if ($request->has('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->latest()->paginate(10);
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
            'order_status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        // Step 6: Stock Management
        // Reduce stock when confirmed for the first time
        if ($oldStatus === 'pending' && $newStatus === 'confirmed') {
            if ($order->selected_format === 'hardcopy') {
                if ($order->book->stock_quantity < $order->quantity) {
                    return back()->with('error', 'Cannot confirm order. Insufficient stock.');
                }
                $order->book->decrement('stock_quantity', $order->quantity);
            }
        }

        // If cancelled from a confirmed state, return stock
        if (in_array($oldStatus, ['confirmed', 'shipped', 'delivered']) && $newStatus === 'cancelled') {
            if ($order->selected_format === 'hardcopy') {
                $order->book->increment('stock_quantity', $order->quantity);
            }
        }

        // Auto change payment_status = paid when delivered (Step 5)
        $paymentStatus = $request->payment_status;
        if ($newStatus === 'delivered' && $order->payment_method === 'cod') {
            $paymentStatus = 'paid';
        }

        $order->update([
            'order_status' => $newStatus,
            'payment_status' => $paymentStatus,
            'approved_at' => ($paymentStatus === 'paid') ? ($order->approved_at ?? now()) : null,
        ]);

        // Notifications
        if ($newStatus === 'confirmed') {
            $msg = "Your order for '{$order->book->title}' has been confirmed.";
            if ($order->selected_format !== 'hardcopy')
                $msg .= " You can now download it from your library.";

            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order_approved',
                'message' => $msg,
            ]);
        }
        elseif ($newStatus === 'shipped') {
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order_shipped',
                'message' => "Your physical book '{$order->book->title}' has been shipped!",
            ]);
        }

        return back()->with('success', 'Order status and stock updated successfully.');
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
