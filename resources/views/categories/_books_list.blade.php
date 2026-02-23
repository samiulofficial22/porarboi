<div class="row g-4 animate-fade-in">
    @forelse($books as $book)
        <div class="col-md-6 col-lg-3">
            @include('layouts._book_card', ['book' => $book])
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="p-5 rounded-5 bg-glass border">
                <i class="fas fa-book-open fa-3x text-muted opacity-25 mb-4"></i>
                <h4>{{ app()->getLocale() == 'bn' ? 'এই ক্যাটাগরিতে কোনো বই নেই' : 'No books available' }}</h4>
            </div>
        </div>
    @endforelse
</div>

<style>
    .book-card {
        background: var(--card-bg);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .book-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px -10px rgba(0,0,0,0.25) !important; }
    .book-card:hover .book-cover-img { transform: scale(1.05); }
    .book-cover-img { transition: transform 0.5s ease; }
    .book-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.3s ease; z-index: 2; }
    .book-card:hover .book-overlay { opacity: 1; }
    .price-badge { background: rgba(0,0,0,0.55); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); color: white; font-size: 0.9rem; }
    .btn-buy-now { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(99,102,241,0.3); }
    .btn-buy-now:hover { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.4); }
    .btn-view-details { background: transparent; border: 1px solid var(--border-color); color: var(--text-color); transition: all 0.3s ease; }
    .btn-view-details:hover { background: var(--primary-color); border-color: var(--primary-color); color: white; }
    .book-title { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .animate-fade-in { animation: fadeIn 0.5s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .bg-glass { background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); }
    [data-bs-theme="light"] .bg-glass { background: #ffffff; border: 1px solid rgba(0,0,0,0.05) !important; }
</style>
