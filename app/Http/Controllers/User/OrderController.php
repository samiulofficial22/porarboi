<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Order;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Handle single item buy now redirect.
     * It populates the cart with one item and redirects to proper checkout.
     */
    public function checkout(Request $request, Book $book)
    {
        $format = $request->input('format', $book->product_type === 'physical' ? 'hardcopy' : 'pdf');

        // Clear existing cart and add only this item for "Buy Now"
        $cart = [
            $book->id . '_' . $format => [
                'id' => $book->id,
                'title' => $book->title,
                'price' => $format === 'hardcopy' ? ($book->format_price_hardcopy ?? $book->price) : ($book->format_price_pdf ?? $book->price),
                'cover_image' => $book->cover_image,
                'format' => $format,
                'quantity' => 1,
                'shipping_charge' => $book->shipping_charge ?? 0,
                'cartKey' => $book->id . '_' . $format
            ]
        ];

        Session::put('cart', $cart);

        return redirect()->route('user.cart.checkout');
    }

    /**
     * Unified checkout page for multiple cart items.
     */
    public function cartCheckout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('home')->with('info', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = 0;
        $shipping = 0;
        $hasPhysical = false;
        $hasDigital = false;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            if ($item['format'] === 'hardcopy') {
                $hasPhysical = true;
                $shipping += $item['shipping_charge'] * $item['quantity'];
            }
            else {
                $hasDigital = true;
            }
        }

        $total = $subtotal + $shipping;
        $isOnlyPhysical = $hasPhysical && !$hasDigital;

        return view('user.checkout', compact('cart', 'subtotal', 'shipping', 'total', 'hasPhysical', 'hasDigital', 'isOnlyPhysical'));
    }

    /**
     * Store orders for all items in cart.
     */
    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Cart is empty.');
        }

        // Determine cart composition
        $hasPhysical = false;
        $hasDigital = false;
        foreach ($cart as $item) {
            if (($item['format'] ?? 'pdf') === 'hardcopy')
                $hasPhysical = true;
            else
                $hasDigital = true;
        }
        $isOnlyPhysical = $hasPhysical && !$hasDigital;

        // COD is ONLY allowed for pure physical orders
        if ($request->payment_method === 'cod' && !$isOnlyPhysical) {
            return back()->with('error', 'Cash on Delivery is only available for physical book orders. Please use online payment for digital books.');
        }

        $rules = [
            'payment_method' => 'required|in:online,cod',
        ];

        // Online payment requires transaction proof
        if ($request->payment_method === 'online') {
            $rules['transaction_id'] = 'required|string|max:100';
            $rules['sender_number'] = 'required|string|max:20';
        // Screenshot is optional but accepted
        }

        // Shipping required only if physical books exist
        if ($hasPhysical) {
            $rules['shipping_name'] = 'required|string|max:255';
            $rules['shipping_phone'] = 'required|string|max:20';
            $rules['shipping_address'] = 'required|string';
            $rules['shipping_district'] = 'required|string';
        }

        $request->validate($rules);

        $payment_path = null;
        if ($request->hasFile('payment_screenshot')) {
            $payment_path = $request->file('payment_screenshot')->store('payment_screenshots', 'public');
        }

        foreach ($cart as $item) {
            $book = Book::find($item['id']);

            // Stock check again
            if ($item['format'] === 'hardcopy' && $book->stock_quantity < $item['quantity']) {
                return back()->with('error', "Insufficient stock for '{$book->title}'. Please update your cart.");
            }

            Order::create([
                'user_id' => Auth::id(),
                'book_id' => $item['id'],
                'selected_format' => $item['format'],
                'quantity' => $item['quantity'],
                'amount' => $item['price'],
                'shipping_charge' => $item['format'] === 'hardcopy' ? $item['shipping_charge'] : 0,
                'transaction_id' => $request->transaction_id,
                'sender_number' => $request->sender_number,
                'payment_screenshot' => $payment_path,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_district' => $request->shipping_district,
                'shipping_postcode' => $request->shipping_postcode,
                'delivery_note' => $request->delivery_note,
            ]);
        }

        // Clear cart after order
        Session::forget('cart');

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'new_order',
                'message' => "New order (#" . Auth::user()->name . ") received for " . count($cart) . " items.",
            ]);
        }

        return redirect()->route('user.history')->with('success', 'Orders placed successfully!');
    }

    public function myBooks()
    {
        $orders = Auth::user()->orders()->where('order_status', 'approved')->orWhere('payment_status', 'paid')->with('book')->latest()->get();
        // Simple logic: if paid OR it was a digital order approved
        return view('user.my_books', compact('orders'));
    }

    public function downloadPdf(Book $book)
    {
        // Must be paid or digital approved
        $hasAccess = Auth::user()->orders()
            ->where('book_id', $book->id)
            ->where(function ($q) {
            $q->where('order_status', 'delivered')
                ->orWhere('payment_status', 'paid')
                ->orWhere('order_status', 'confirmed'); // Digital books usually mark paid on confirm
        })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Payment required to download.');
        }

        if (!$book->pdf_file || !Storage::disk('local')->exists($book->pdf_file)) {
            abort(404, 'PDF file not found.');
        }

        $fileName = Str::slug($book->title) . '.pdf';
        return Storage::disk('local')->download($book->pdf_file, $fileName);
    }

    public function history()
    {
        $orders = Auth::user()->orders()->with('book')->latest()->paginate(10);
        return view('user.history', compact('orders'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->order_status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->delete();

        return back()->with('success', 'Order cancelled successfully.');
    }
}
