<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FreeDownloadController extends Controller
{
    /**
     * Process free download request.
     */
    public function process(Request $request, Book $book)
    {
        if ($book->price > 0) {
            return redirect()->route('book.show', $book)->with('error', 'This is not a free book.');
        }

        // Check if registration/login is required
        if (!setting('allow_guest_free_download', false) && !Auth::check()) {
            return redirect()->route('register')->with('info', 'Please create an account to download this free book.');
        }

        $userId = Auth::id() ?: null;
        $userEmail = Auth::check() ?Auth::user()->email : $request->email;

        // If guest download allowed but email required
        if (!Auth::check() && setting('require_email_for_free', false)) {
            if (!$request->has('email')) {
                return view('user.free_email_capture', compact('book'));
            }
            $request->validate(['email' => 'required|email']);
        }

        // Create or find order
        $order = Order::where('book_id', $book->id)
            ->where(function ($q) use ($userId, $userEmail) {
            if ($userId) {
                $q->where('user_id', $userId);
            }
            else if ($userEmail) {
                $q->where('sender_number', 'EXT-' . $userEmail); // Using sender_number field to store guest info for free books
            }
        })
            ->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'transaction_id' => 'FREE-' . strtoupper(Str::random(10)),
                'sender_number' => $userId ? (Auth::user()->phone ?? 'N/A') : 'EXT-' . ($userEmail ?? 'GUEST'),
                'amount' => 0,
                'status' => 'approved', // Free books are auto-approved
            ]);
        }

        // Track order ID in session for guests if needed
        if (!Auth::check()) {
            session(['free_order_' . $book->id => $order->id]);
        }

        return $this->generateDownloadResponse($order, $book);
    }

    /**
     * Generate signed download URL and redirect.
     */
    protected function generateDownloadResponse($order, $book)
    {
        $expiryMinutes = (int)setting('free_download_expiry_minutes', 30);

        $downloadUrl = URL::temporarySignedRoute(
            'free.download.file',
            now()->addMinutes($expiryMinutes),
        ['order' => $order->id, 'book' => $book->id]
        );

        return redirect($downloadUrl);
    }

    /**
     * Actual file download (Signed Route).
     */
    public function downloadFile(Request $request, Order $order, Book $book)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This download link has expired or is invalid.');
        }

        // Check if the order belongs to this book and is approved
        if ($order->status !== 'approved' || $order->book_id !== $book->id) {
            abort(403, 'Unauthorized access.');
        }

        // Download limit check
        $limit = (int)setting('free_download_limit', 5);
        if ($order->download_count >= $limit) {
            abort(403, 'Maximum download limit reached for this link.');
        }

        if (!$book->pdf_file || !Storage::disk('local')->exists($book->pdf_file)) {
            abort(404, 'File not found. Please contact support.');
        }

        // Increment download count
        $order->increment('download_count');

        Log::info("Free download: Book {$book->id}, Order {$order->id}, User " . ($order->user_id ?: 'Guest') . ", IP " . $request->ip());

        $fileName = Str::slug($book->title) . '-free.pdf';
        return Storage::disk('local')->download($book->pdf_file, $fileName);
    }
}
