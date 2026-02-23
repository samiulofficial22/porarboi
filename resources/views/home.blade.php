@extends('layouts.app')

@section('title', app()->getLocale() == 'bn' ? 'বইয়ের জগৎ' : 'Future of Reading')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden text-center mb-5 rounded-5">
    <!-- Animated Background -->
    <div class="hero-bg-gradient"></div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>
    <div class="hero-grid-overlay"></div>

    <div class="container position-relative z-1 py-5 py-lg-6">
        <!-- Badge -->
        <div class="mb-4 animate-up" style="--delay:0s">
            <span class="hero-badge px-4 py-2 rounded-pill fw-semibold">
                <i class="fas fa-star me-2"></i>
                {{ app()->getLocale() == 'bn' ? 'বাংলাদেশের সেরা ডিজিটাল বুক স্টোর' : 'Bangladesh\'s Best Digital Book Store' }}
            </span>
        </div>

        <!-- Main Heading -->
        <h1 class="hero-title fw-bold mb-4 animate-up" style="--delay:0.1s">
            @lang('messages.hero_title')
        </h1>

        <!-- Subtitle -->
        <p class="hero-subtitle mx-auto mb-5 animate-up" style="--delay:0.2s">
            @lang('messages.hero_subtitle')
        </p>

        <!-- CTA Buttons -->
        <div class="d-flex gap-3 justify-content-center flex-wrap mb-5 animate-up" style="--delay:0.3s">
            @guest
                <a href="{{ route('register') }}" class="btn btn-hero-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                    <i class="fas fa-rocket me-2"></i>@lang('messages.get_started')
                </a>
                <a href="{{ route('login') }}" class="btn btn-hero-outline btn-lg px-5 py-3 rounded-pill fw-bold">
                    <i class="fas fa-sign-in-alt me-2"></i>@lang('messages.login')
                </a>
            @else
                <a href="#books" class="btn btn-hero-primary btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                    <i class="fas fa-book-open me-2"></i>@lang('messages.browse_collection')
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-hero-outline btn-lg px-5 py-3 rounded-pill fw-bold">
                    <i class="fas fa-layer-group me-2"></i>@lang('messages.categories')
                </a>
            @endguest
        </div>

        <!-- Stats Row -->
        <div class="row justify-content-center gap-0 animate-up" style="--delay:0.4s">
            @php
                $totalBooks = \App\Models\Book::where('is_active', true)->count();
                $totalCats  = \App\Models\Category::count();
            @endphp
            <div class="col-auto">
                <div class="hero-stat-card">
                    <div class="hero-stat-number">{{ $totalBooks }}+</div>
                    <div class="hero-stat-label">{{ app()->getLocale() == 'bn' ? 'বই' : 'Books' }}</div>
                </div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="col-auto">
                <div class="hero-stat-card">
                    <div class="hero-stat-number">{{ $totalCats }}+</div>
                    <div class="hero-stat-label">{{ app()->getLocale() == 'bn' ? 'ক্যাটাগরি' : 'Categories' }}</div>
                </div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="col-auto">
                <div class="hero-stat-card">
                    <div class="hero-stat-number">24/7</div>
                    <div class="hero-stat-label">{{ app()->getLocale() == 'bn' ? 'সাপোর্ট' : 'Support' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Books Section -->
<div id="books" class="py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">@lang('messages.featured_collections')</h2>
            <p class="text-muted mb-0 small">{{ app()->getLocale() == 'bn' ? 'আমাদের সেরা বইগুলো আবিষ্কার করুন' : 'Discover our best curated books' }}</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-outline-secondary rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-filter me-2"></i>@lang('messages.filter_category')
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="{{ route('home') }}">@lang('messages.all_categories')</a></li>
                @foreach(\App\Models\Category::all() as $category)
                    <li><a class="dropdown-item" href="?category={{ $category->slug }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row g-4">
        @forelse($books as $book)
            <div class="col-md-4 col-lg-3">
                @include('layouts._book_card', ['book' => $book])
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4"><i class="fas fa-search fa-4x text-muted opacity-25"></i></div>
                <h3 class="text-muted">@lang('messages.no_books')</h3>
            </div>
        @endforelse
    </div>

    {{-- Category-wise Sections --}}
    @if(!request()->has('category'))
        @foreach($categorySections as $section)
            <div class="mt-5 pt-5 pb-3">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">{{ $section->name }}</h2>
                        <p class="text-muted mb-0 small">{{ $section->books->count() }} {{ app()->getLocale() == 'bn' ? 'টি বই পাওয়া গেছে' : 'books available' }}</p>
                    </div>
                    <a href="?category={{ $section->slug }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                        {{ app()->getLocale() == 'bn' ? 'সবগুলো দেখুন' : 'View All' }} <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                
                <div class="row g-4 mb-4">
                    @foreach($section->books as $book)
                        <div class="col-md-4 col-lg-3">
                            @include('layouts._book_card', ['book' => $book])
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@section('styles')
<style>
    /* ===================== HERO SECTION ===================== */
    .hero-section {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .py-lg-6 { padding-top: 5rem !important; padding-bottom: 5rem !important; }

    /* Dark mode hero */
    .hero-bg-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        z-index: 0;
    }

    /* Light mode hero */
    [data-bs-theme="light"] .hero-bg-gradient {
        background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 40%, #f5f0ff 100%);
    }

    /* Animated glowing orbs */
    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        animation: floatOrb 8s ease-in-out infinite alternate;
        z-index: 0;
    }

    .hero-orb-1 {
        width: 500px; height: 500px;
        background: rgba(99, 102, 241, 0.25);
        top: -150px; left: -100px;
        animation-delay: 0s;
    }

    .hero-orb-2 {
        width: 400px; height: 400px;
        background: rgba(168, 85, 247, 0.2);
        bottom: -100px; right: -80px;
        animation-delay: 2s;
    }

    .hero-orb-3 {
        width: 300px; height: 300px;
        background: rgba(59, 130, 246, 0.15);
        top: 30%; left: 50%;
        animation-delay: 4s;
    }

    [data-bs-theme="light"] .hero-orb-1 { background: rgba(99, 102, 241, 0.12); }
    [data-bs-theme="light"] .hero-orb-2 { background: rgba(168, 85, 247, 0.10); }
    [data-bs-theme="light"] .hero-orb-3 { background: rgba(59, 130, 246, 0.08); }

    @keyframes floatOrb {
        0%   { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, 20px) scale(1.1); }
    }

    /* Subtle grid/dot overlay */
    .hero-grid-overlay {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
        background-size: 30px 30px;
        z-index: 0;
    }

    [data-bs-theme="light"] .hero-grid-overlay {
        background-image: radial-gradient(rgba(99,102,241,0.06) 1px, transparent 1px);
    }

    /* Badge */
    .hero-badge {
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        border: 1px solid rgba(99, 102, 241, 0.3);
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    [data-bs-theme="light"] .hero-badge {
        background: rgba(99, 102, 241, 0.08);
        color: #6366f1;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    /* Main title */
    .hero-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        line-height: 1.1;
        background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 50%, #c084fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    [data-bs-theme="light"] .hero-title {
        background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 50%, #7c3aed 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Subtitle */
    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255,255,255,0.65);
        max-width: 620px;
        line-height: 1.8;
    }

    [data-bs-theme="light"] .hero-subtitle {
        color: #475569;
    }

    /* Buttons */
    .btn-hero-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px -5px rgba(99, 102, 241, 0.5);
    }

    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -5px rgba(99, 102, 241, 0.6);
        color: white;
    }

    .btn-hero-outline {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.15);
        transform: translateY(-3px);
        color: white;
    }

    [data-bs-theme="light"] .btn-hero-outline {
        background: rgba(255,255,255,0.8);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: #4f46e5;
    }

    [data-bs-theme="light"] .btn-hero-outline:hover {
        background: white;
        color: #4f46e5;
        border-color: #6366f1;
    }

    /* Stats */
    .hero-stat-card {
        padding: 16px 28px;
        text-align: center;
    }

    .hero-stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        line-height: 1;
    }

    [data-bs-theme="light"] .hero-stat-number {
        background: linear-gradient(to right, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-stat-label {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    [data-bs-theme="light"] .hero-stat-label { color: #94a3b8; }

    .hero-stat-divider {
        width: 1px;
        background: rgba(255,255,255,0.15);
        margin: 10px 0;
        align-self: stretch;
    }

    [data-bs-theme="light"] .hero-stat-divider { background: rgba(0,0,0,0.1); }

    /* Animate up on load */
    .animate-up {
        animation: slideUp 0.7s ease both;
        animation-delay: var(--delay, 0s);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ===================== BOOK CARDS ===================== */
    .book-card {
        background: var(--card-bg);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .book-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px -10px rgba(0, 0, 0, 0.25) !important; }
    .book-card:hover .book-cover-img { transform: scale(1.05); }

    .book-cover-wrap { overflow: hidden; }
    .book-cover-img { transition: transform 0.5s ease; }

    .book-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .book-card:hover .book-overlay { opacity: 1; }

    .price-badge {
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        font-size: 0.9rem;
    }

    .btn-buy-now {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(99, 102,241, 0.3);
    }

    .btn-buy-now:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102,241, 0.4);
    }

    .btn-view-details {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-color);
        transition: all 0.3s ease;
    }

    .btn-view-details:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .book-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

</style>
@endsection
