<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category')->latest();

        // Search Filter
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        // Category Filter
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->paginate(10)->withQueryString();
        $categories = Category::all();
        $totalBooks = Book::count();

        return view('admin.books.index', compact('books', 'categories', 'totalBooks'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:digital,physical,both',
            'stock_quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:255|unique:books,sku',
            'shipping_charge' => 'nullable|numeric|min:0',
            'format_price_pdf' => 'nullable|numeric|min:0',
            'format_price_hardcopy' => 'nullable|numeric|min:0',
            'cover_image' => 'required|image|max:2048',
            'pdf_file' => 'nullable|required_if:product_type,digital,both|file|mimes:pdf|max:20480',
            'slug' => 'nullable|string|max:255|unique:books,slug',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:2048',
        ]);

        try {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $pdfPath = $request->hasFile('pdf_file') ? $request->file('pdf_file')->store('books', 'local') : null;
            $ogImagePath = $request->hasFile('og_image') ? $request->file('og_image')->store('seo', 'public') : null;

            Book::create([
                'category_id' => $request->category_id,
                'product_type' => $request->product_type,
                'stock_quantity' => $request->stock_quantity,
                'weight' => $request->weight,
                'sku' => $request->sku,
                'shipping_charge' => $request->shipping_charge ?? 0,
                'format_price_pdf' => $request->format_price_pdf,
                'format_price_hardcopy' => $request->format_price_hardcopy,
                'title' => $request->title,
                'slug' => $request->slug ?: \Illuminate\Support\Str::slug($request->title),
                'description' => $request->description,
                'price' => $request->price,
                'cover_image' => $coverPath,
                'pdf_file' => $pdfPath,
                'is_active' => true,
                'seo_title' => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_keywords' => $request->seo_keywords,
                'og_image' => $ogImagePath,
            ]);

            return redirect()->route('admin.books.index')->with('success', 'Book created successfully.');
        }
        catch (\Exception $e) {
            Log::error('Book Storage Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to save book. Error: ' . $e->getMessage());
        }
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'product_type' => 'required|in:digital,physical,both',
            'stock_quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:255|unique:books,sku,' . $book->id,
            'shipping_charge' => 'nullable|numeric|min:0',
            'format_price_pdf' => 'nullable|numeric|min:0',
            'format_price_hardcopy' => 'nullable|numeric|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'slug' => 'nullable|string|max:255|unique:books,slug,' . $book->id,
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|max:2048',
        ]);

        try {
            $data = $request->only([
                'category_id', 'product_type', 'stock_quantity', 'weight', 'sku',
                'shipping_charge', 'format_price_pdf', 'format_price_hardcopy',
                'title', 'description', 'price'
            ]);

            if ($request->hasFile('cover_image')) {
                if ($book->cover_image && $book->cover_image !== 'covers/dummy.jpg') {
                    Storage::disk('public')->delete($book->cover_image);
                }
                $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
            }

            if ($request->hasFile('pdf_file')) {
                if ($book->pdf_file) {
                    Storage::disk('local')->delete($book->pdf_file);
                }
                $data['pdf_file'] = $request->file('pdf_file')->store('books', 'local');
            }

            if ($request->hasFile('og_image')) {
                if ($book->og_image) {
                    Storage::disk('public')->delete($book->og_image);
                }
                $data['og_image'] = $request->file('og_image')->store('seo', 'public');
            }

            $data['slug'] = $request->slug ?: \Illuminate\Support\Str::slug($request->title);
            $data['seo_title'] = $request->seo_title;
            $data['seo_description'] = $request->seo_description;
            $data['seo_keywords'] = $request->seo_keywords;
            $data['is_active'] = $request->has('is_active');

            $book->update($data);

            return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
        }
        catch (\Exception $e) {
            Log::error('Book Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update book. Error: ' . $e->getMessage());
        }
    }

    public function destroy(Book $book)
    {
        try {
            if ($book->cover_image && $book->cover_image !== 'covers/dummy.jpg') {
                Storage::disk('public')->delete($book->cover_image);
            }

            if ($book->pdf_file) {
                Storage::disk('local')->delete($book->pdf_file);
            }

            $book->delete();

            return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
        }
        catch (\Exception $e) {
            Log::error('Book Deletion Error: ' . $e->getMessage());
            return redirect()->route('admin.books.index')->with('error', 'Failed to delete book. Error: ' . $e->getMessage());
        }
    }
}
