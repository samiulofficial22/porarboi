<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Book;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::where('is_active', true);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $books = $query->latest()->get();

        // Fetch categories with their latest books for category-wise sections
        $categorySections = \App\Models\Category::whereHas('books', function ($q) {
            $q->where('is_active', true);
        })->with(['books' => function ($q) {
            $q->where('is_active', true)->latest()->take(8);
        }])->get();

        return view('home', compact('books', 'categorySections'));
    }

    public function show(Book $book)
    {
        if (!$book->is_active) {
            abort(404);
        }
        return view('book.show', compact('book'));
    }
}
