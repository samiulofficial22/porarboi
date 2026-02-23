@extends('layouts.app')

@section('title', ($book->price == 0 ? '[Free] ' : '') . ($book->seo_title ?? $book->title))
@section('meta_description', ($book->price == 0 ? 'Free PDF Download: ' : '') . ($book->seo_description ?? Str::limit($book->description, 160)))
@section('meta_keywords', $book->seo_keywords . ($book->price == 0 ? ', free download, free pdf' : ''))
@section('og_image', $book->og_image ? Storage::url($book->og_image) : Storage::url($book->cover_image))

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Book",
  "name": "{{ $book->title }}",
  "description": "{{ Str::limit($book->description, 200) }}",
  "image": "{{ url(Storage::url($book->cover_image)) }}",
  "offers": {
    "@type": "Offer",
    "price": "{{ $book->price }}",
    "priceCurrency": "BDT",
    "availability": "https://schema.org/InStock"
  }
}
</script>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 rounded-5 shadow-lg overflow-hidden book-detail-card">
            <div class="row g-0">
                <!-- Book Cover -->
                <div class="col-md-4">
                    <div class="book-detail-cover position-relative h-100" style="min-height: 450px;">
                        @if(!$book->cover_image || $book->cover_image == 'covers/dummy.jpg')
                            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 h-100">
                                <i class="fas fa-book fa-5x text-primary opacity-25"></i>
                            </div>
                        @else
                            <img src="{{ Storage::url($book->cover_image) }}"
                                 class="img-fluid w-100 h-100 object-fit-cover"
                                 alt="{{ $book->title }}"
                                 style="object-fit: cover;">
                        @endif
                        
                        <!-- Price overlay -->
                        <div class="position-absolute top-0 end-0 m-3">
                            @if($book->price == 0)
                                <span class="badge bg-success px-4 py-2 rounded-pill fw-bold fs-6 shadow">
                                    <i class="fas fa-gift me-2"></i>FREE
                                </span>
                            @else
                                <span class="badge price-badge-lg px-4 py-2 rounded-pill fw-bold fs-6 shadow">
                                    {{ setting('currency_symbol', '৳') }}{{ number_format($book->price, 0) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Book Details -->
                <div class="col-md-8">
                    <div class="card-body p-5 d-flex flex-column h-100">
                        <!-- Category & Tags -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            @if($book->category)
                                <span class="badge category-badge px-3 py-2 rounded-pill fw-semibold">
                                    <i class="fas fa-tag me-1"></i>{{ $book->category->name }}
                                </span>
                            @endif
                            @if($book->price == 0)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold">
                                    <i class="fas fa-check me-1"></i>Free PDF Download
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="fw-bold mb-3" style="font-size: 2rem; line-height: 1.3;">{{ $book->title }}</h1>

                        <!-- Price Display -->
                        <div class="mb-4 d-flex align-items-center gap-3">
                            @if($book->price == 0)
                                <span class="price-display fw-bold text-success">FREE</span>
                            @else
                                <span class="price-display fw-bold">{{ setting('currency_symbol', '৳') }}{{ number_format($book->price, 0) }}</span>
                            @endif
                            <span class="badge available-badge px-3 py-2 rounded-pill">
                                <i class="fas fa-check-circle me-1"></i>Available
                            </span>
                        </div>

                        <hr class="my-3 opacity-10">

                        <!-- Description -->
                        <div class="mb-4 flex-grow-1">
                            <h6 class="fw-bold text-muted text-uppercase small mb-2 tracking-wider">Description</h6>
                            <p class="text-muted lh-lg">{{ $book->description }}</p>
                        </div>

                        <!-- Feature Badges -->
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="feature-badge"><i class="fas fa-download me-1"></i>Instant Download</span>
                            <span class="feature-badge"><i class="fas fa-mobile-alt me-1"></i>Read Anywhere</span>
                            <span class="feature-badge"><i class="fas fa-infinity me-1"></i>Lifetime Access</span>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="d-grid gap-3">
                            @if($book->price == 0)
                                <a href="{{ route('free.download', $book) }}"
                                   class="btn btn-success btn-lg rounded-pill py-3 fw-bold shadow-sm">
                                    <i class="fas fa-download me-2"></i>Download Free PDF
                                </a>
                                <p class="text-center text-muted small mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    @if(setting('allow_guest_free_download', false))
                                        Instant download after providing your email.
                                    @else
                                        Instant free download after login.
                                    @endif
                                </p>
                            @else
                                @if(Auth::check())
                                    @if(Auth::user()->role === 'user')
                                        <div class="d-flex gap-2">
                                            <button onclick="addToCart({{ $book->id }})" class="btn btn-view-details btn-lg rounded-pill py-3 fw-semibold flex-grow-1">
                                                <i class="fas fa-shopping-cart me-2"></i>
                                                {{ app()->getLocale() == 'bn' ? 'কার্টে যোগ করুন' : 'Add to Cart' }}
                                            </button>
                                            <a href="{{ route('user.checkout', $book) }}"
                                               class="btn btn-buy-now btn-lg rounded-pill py-3 fw-bold shadow-sm px-4">
                                                <i class="fas fa-bolt me-2"></i>{{ app()->getLocale() == 'bn' ? 'এখনইকিনুন' : 'Buy Now' }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-info rounded-4 border-0 text-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            You are logged in as Admin. Purchase is not available for admin accounts.
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="btn btn-buy-now btn-lg rounded-pill py-3 fw-bold shadow-sm">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        {{ app()->getLocale() == 'bn' ? 'লগইন করে কিনুন' : 'Login to Buy Now' }}
                                    </a>
                                    <a href="{{ route('register') }}"
                                       class="btn btn-view-details btn-lg rounded-pill py-3 fw-semibold">
                                        <i class="fas fa-user-plus me-2"></i>
                                        {{ app()->getLocale() == 'bn' ? 'নতুন অ্যাকাউন্ট তৈরি করুন' : 'Create Free Account' }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .book-detail-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color) !important;
    }

    [data-bs-theme="light"] .book-detail-card {
        box-shadow: 0 25px 60px rgba(0,0,0,0.08) !important;
    }

    .price-badge-lg {
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
    }

    .category-badge {
        background: #6366f1;
        color: #ffffff !important;
        font-size: 0.85rem;
    }

    [data-bs-theme="light"] .category-badge {
        background: #ede9fe;
        color: #4f46e5 !important;
        border: 1px solid rgba(99,102,241,0.25);
    }

    .available-badge {
        background: #16a34a;
        color: #ffffff !important;
        font-size: 0.85rem;
    }

    [data-bs-theme="light"] .available-badge {
        background: #dcfce7;
        color: #15803d !important;
        border: 1px solid rgba(22,163,74,0.25);
    }

    .price-display {
        font-size: 2rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .feature-badge {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary-color);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    .tracking-wider { letter-spacing: 0.08em; }

    .btn-buy-now {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.35);
    }

    .btn-buy-now:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.45);
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

    [data-bs-theme="light"] .btn-view-details {
        border-color: #cbd5e1;
        color: #475569;
    }
</style>
@endsection
