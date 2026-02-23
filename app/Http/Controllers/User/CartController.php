<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request, Book $book)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$book->id])) {
            return response()->json(['message' => 'Book already in cart', 'status' => 'exists']);
        }

        $cart[$book->id] = [
            'id' => $book->id,
            'title' => $book->title,
            'price' => $book->price,
            'cover_image' => $book->cover_image,
        ];

        Session::put('cart', $cart);

        return response()->json([
            'message' => 'Book added to cart!',
            'status' => 'success',
            'cart_count' => count($cart)
        ]);
    }

    public function remove(Request $request, $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from cart',
                'cart_count' => count($cart)
            ]);
        }

        return back()->with('success', 'Item removed from cart');
    }

    public function getCart()
    {
        $cart = Session::get('cart', []);
        return response()->json([
            'items' => array_values($cart),
            'total' => collect($cart)->sum('price')
        ]);
    }
}
