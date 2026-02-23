<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add item to cart with format and quantity support.
     */
    public function add(Request $request, Book $book)
    {
        $format = $request->input('format', $book->product_type === 'physical' ? 'hardcopy' : 'pdf');
        $quantity = (int)$request->input('quantity', 1);

        // Composite Key to allow different formats of the same book
        $cartKey = $book->id . '_' . $format;
        $cart = Session::get('cart', []);

        // Stock check for physical books
        if ($format === 'hardcopy') {
            if ($book->product_type === 'digital') {
                return response()->json(['message' => 'Hardcopy not available for this book', 'status' => 'error'], 400);
            }
            if ($book->stock_quantity < $quantity) {
                return response()->json(['message' => 'Insufficient stock available', 'status' => 'error'], 400);
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            // Re-check stock if adding more
            if ($format === 'hardcopy' && $book->stock_quantity < $cart[$cartKey]['quantity']) {
                $cart[$cartKey]['quantity'] -= $quantity; // Revert
                return response()->json(['message' => 'Cannot add more, stock limit reached', 'status' => 'error'], 400);
            }
        }
        else {
            $price = ($format === 'hardcopy') ? ($book->format_price_hardcopy ?? $book->price) : ($book->format_price_pdf ?? $book->price);

            $cart[$cartKey] = [
                'id' => $book->id,
                'title' => $book->title,
                'price' => $price,
                'cover_image' => $book->cover_image,
                'format' => $format,
                'quantity' => $quantity,
                'shipping_charge' => $book->shipping_charge ?? 0,
                'cartKey' => $cartKey
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'message' => 'Cart updated successfully!',
            'status' => 'success',
            'cart_count' => collect($cart)->sum('quantity')
        ]);
    }

    /**
     * Remove item using the composite cartKey or legacy ID.
     */
    public function remove(Request $request, $cartKey)
    {
        $cart = Session::get('cart', []);

        // Try exact match first (works for new composite keys)
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
        }
        // Try as numeric ID (works for legacy items)
        elseif (isset($cart[(int)$cartKey])) {
            unset($cart[(int)$cartKey]);
        }

        Session::put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart',
            'cart_count' => collect($cart)->sum('quantity')
        ]);
    }

    /**
     * Get all cart items for front-end UI.
     */
    public function getCart()
    {
        $cart = Session::get('cart', []);

        // Sanitize: Ensure every item has a cartKey and proper quantity
        $items = [];
        foreach ($cart as $key => $item) {
            $item['cartKey'] = (string)$key; // Use the actual array key as the cartKey
            $item['quantity'] = $item['quantity'] ?? 1;
            $items[] = $item;
        }

        $total = collect($items)->sum(fn($item) => ($item['price'] ?? 0) * $item['quantity']);

        return response()->json([
            'items' => $items,
            'total' => $total
        ]);
    }
}
