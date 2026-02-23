<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount(['books' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        $query = \App\Models\Book::where('is_active', true);

        if ($request->has('category') && $request->category != 'all') {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $books = $query->latest()->get(); // Using get() instead of paginate for smoother AJAX initial demo, or keep paginate

        if ($request->ajax()) {
            return view('categories._books_list', compact('books'))->render();
        }

        return view('categories.index', compact('categories', 'books'));
    }
}
