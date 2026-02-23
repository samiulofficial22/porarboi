@extends('layouts.app')

@section('title', app()->getLocale() == 'bn' ? 'সব ক্যাটাগরি' : 'All Categories')

@section('content')
<!-- Hero Section -->
<section class="category-hero text-center py-5 mb-5 rounded-5 shadow-lg overflow-hidden position-relative">
    <div class="hero-overlay"></div>
    <div class="container position-relative z-1">
        <span class="badge hero-badge px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
            {{ app()->getLocale() == 'bn' ? 'আমাদের সংগ্রহ' : 'Our Collection' }}
        </span>
        <h1 class="display-3 fw-bold mb-3 hero-main-title">
            {{ app()->getLocale() == 'bn' ? 'বইয়ের ধরণসমূহ' : 'Explore Categories' }}
        </h1>
        <p class="lead hero-subtitle mb-0 mx-auto" style="max-width: 700px; font-weight: 500;">
            {{ app()->getLocale() == 'bn' 
                ? 'আপনার পছন্দের বিষয় অনুযায়ী সেরা বইগুলো খুঁজে নিন এক জায়গা থেকে।' 
                : 'Find the best books according to your favorite topics, all in one place.' 
            }}
        </p>
    </div>
</section>

<!-- Stats Bar -->
<div class="row justify-content-center mb-5 mt-n4">
    <div class="col-md-5 text-center">
        <div class="bg-glass py-2 px-4 rounded-pill shadow-sm d-inline-block border">
            <span class="fw-bold text-primary">{{ $categories->count() }}</span>
            <span class="text-muted ms-1 small">{{ app()->getLocale() == 'bn' ? 'টি ক্যাটাগরি' : 'Categories' }}</span>
            <span class="mx-3 text-muted opacity-25">|</span>
            <span class="fw-bold text-primary">{{ \App\Models\Book::where('is_active', true)->count() }}</span>
            <span class="text-muted ms-1 small">{{ app()->getLocale() == 'bn' ? 'টি বই' : 'Books Available' }}</span>
        </div>
    </div>
</div>

<!-- Category Grid (Restored Beautiful Design) -->
<div class="row g-4 mb-5 pb-5 border-bottom">
    @php
        $iconMapping = [
            'Programming' => 'fa-code',
            'Business' => 'fa-briefcase',
            'Science' => 'fa-flask',
            'Technology' => 'fa-microchip',
            'Fiction' => 'fa-book-open',
            'Islamic' => 'fa-mosque',
            'Personal Development' => 'fa-user-tie',
            'Design' => 'fa-paint-brush',
            'Marketing' => 'fa-bullhorn',
            'Education' => 'fa-graduation-cap',
            'Novel' => 'fa-feather-alt',
            'History' => 'fa-monument',
        ];
    @endphp

    <!-- All Books Card -->
    <div class="col-sm-6 col-lg-3">
        <div class="category-item active cursor-pointer h-100" data-category="all">
            <div class="card h-100 border-0 rounded-5 shadow-sm category-card text-center p-4 bg-glass border">
                <div class="category-icon-wrapper mb-4 mx-auto">
                    <div class="icon-circle shadow-sm">
                        <i class="fas fa-th-large fa-2x"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-body mb-2">{{ app()->getLocale() == 'bn' ? 'সব বই' : 'All Books' }}</h4>
                <p class="text-muted small mb-3">{{ \App\Models\Book::where('is_active', true)->count() }} {{ app()->getLocale() == 'bn' ? 'টি বই' : 'Books' }}</p>
                <div class="explore-btn text-primary fw-bold small">
                    {{ app()->getLocale() == 'bn' ? 'দেখুন' : 'Explore' }} <i class="fas fa-arrow-right ms-1"></i>
                </div>
            </div>
        </div>
    </div>

    @foreach($categories as $category)
        @php
            $icon = $iconMapping[$category->name] ?? 'fa-layer-group';
        @endphp
        <div class="col-sm-6 col-lg-3">
            <div class="category-item cursor-pointer h-100" data-category="{{ $category->slug }}">
                <div class="card h-100 border-0 rounded-5 shadow-sm category-card text-center p-4 bg-glass border">
                    <div class="category-icon-wrapper mb-4 mx-auto">
                        <div class="icon-circle shadow-sm">
                            <i class="fas {{ $icon }} fa-2x"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-body mb-2">{{ $category->name }}</h4>
                    <p class="text-muted small mb-3">
                        {{ $category->books_count }} {{ app()->getLocale() == 'bn' ? 'টি বই' : 'Books Available' }}
                    </p>
                    <div class="explore-btn text-primary fw-bold small">
                        {{ app()->getLocale() == 'bn' ? 'দেখুন' : 'Explore' }} <i class="fas fa-arrow-right ms-1"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Books Section -->
<div id="books-container-wrapper" class="py-5">
    <div id="books-loader" class="text-center d-none py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div id="books-list-container">
        @include('categories._books_list', ['books' => $books])
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.3);
    }

    .hero-main-title { color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
    .hero-subtitle { color: rgba(255, 255, 255, 0.9); }
    .hero-badge { background: rgba(255, 255, 255, 0.2); color: #ffffff; backdrop-filter: blur(5px); border: 1px solid rgba(255, 255, 255, 0.3); }

    [data-bs-theme="light"] .category-hero {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    [data-bs-theme="light"] .hero-main-title {
        background: linear-gradient(to right, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: none;
    }

    [data-bs-theme="light"] .hero-subtitle { color: #475569 !important; }
    [data-bs-theme="light"] .hero-badge { background: rgba(79, 70, 229, 0.1) !important; color: #4f46e5 !important; border: 1px solid rgba(79, 70, 229, 0.2) !important; }

    .hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1; }

    .bg-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="light"] .bg-glass { background: #ffffff; border: 1px solid rgba(0, 0, 0, 0.05) !important; }

    .cursor-pointer { cursor: pointer; }

    .category-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .category-item:hover .category-card, .category-item.active .category-card {
        transform: translateY(-15px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        border-color: var(--primary-color) !important;
    }

    .category-item.active .category-card {
        background: var(--primary-color) !important;
    }

    .category-item.active .category-card h4, 
    .category-item.active .category-card p, 
    .category-item.active .category-card .explore-btn {
        color: white !important;
    }

    .icon-circle {
        width: 80px;
        height: 80px;
        background: rgba(79, 70, 229, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        transition: all 0.4s ease;
    }

    .category-item:hover .icon-circle, .category-item.active .icon-circle {
        background: var(--primary-color);
        color: white;
        transform: rotate(360deg);
    }

    .category-item.active .icon-circle {
        background: white;
        color: var(--primary-color);
    }

    .explore-btn {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }

    .category-item:hover .explore-btn, .category-item.active .explore-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .book-card { transition: all 0.3s ease; }
    .book-card:hover { transform: translateY(-10px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important; border-color: var(--primary-color) !important; }

    .bg-blur { background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); border: 1px solid rgba(255, 255, 255, 0.1); }
    .text-body { color: var(--text-color) !important; }
    .mt-n4 { margin-top: -30px; }

    .animate-fade-in {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.category-item');
    const booksContainer = document.getElementById('books-list-container');
    const loader = document.getElementById('books-loader');

    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            // Update UI
            categoryItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const category = this.getAttribute('data-category');
            
            // Show loader
            booksContainer.style.opacity = '0.5';
            loader.classList.remove('d-none');

            // Fetch via AJAX
            fetch(`{{ route('categories.index') }}?category=${category}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                booksContainer.innerHTML = html;
                booksContainer.style.opacity = '1';
                loader.classList.add('d-none');
                
                // Optional: Scroll to books start on mobile
                if(window.innerWidth < 768) {
                    document.getElementById('books-container-wrapper').scrollIntoView({ behavior: 'smooth' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loader.classList.add('d-none');
                booksContainer.style.opacity = '1';
            });
        });
    });
});
</script>
@endsection
