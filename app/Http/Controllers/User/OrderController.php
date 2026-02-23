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

class OrderController extends Controller
{
    public function checkout(Book $book)
    {
        if (Order::where('user_id', Auth::id())->where('book_id', $book->id)->where('status', 'approved')->exists()) {
            return redirect()->route('user.my-books')->with('info', 'You already own this book.');
        }
        return view('user.checkout', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'sender_number' => 'required|string',
            'payment_screenshot' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_screenshot')->store('payment_screenshots', 'public');

        $order = Order::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'transaction_id' => $request->transaction_id,
            'sender_number' => $request->sender_number,
            'amount' => $book->price,
            'payment_screenshot' => $path,
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'book_id' => $book->id,
                'type' => 'new_order',
                'message' => "New order received for '{$book->title}' from " . Auth::user()->name,
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Order placed successfully! Waiting for admin approval.');
    }

    public function myBooks()
    {
        $orders = Auth::user()->orders()->where('status', 'approved')->with('book')->latest()->get();
        return view('user.my_books', compact('orders'));
    }

    public function downloadPdf(Book $book)
    {
        // Check if user purchased the book
        if (!Auth::user()->orders()->where('book_id', $book->id)->where('status', 'approved')->exists()) {
            abort(403, 'You have not purchased this book.');
        }

        if (!$book->pdf_file || !Storage::disk('local')->exists($book->pdf_file)) {
            abort(404, 'PDF file not found.');
        }

        $fileName = Str::slug($book->title) . '.pdf';

        // Increment download count for the specific order
        Auth::user()->orders()->where('book_id', $book->id)->where('status', 'approved')->first()->increment('download_count');

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

        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        if ($order->payment_screenshot) {
            Storage::disk('public')->delete($order->payment_screenshot);
        }

        $order->delete();

        return back()->with('success', 'Order cancelled successfully.');
    }
}
