{{-- Reusable Book Card Partial --}}
{{-- Usage: @include('layouts._book_card', ['book' => $book]) --}}
<div class="card h-100 border-0 rounded-5 shadow-sm overflow-hidden book-card position-relative">

    {{-- Cover Image --}}
    <div class="book-cover-wrap position-relative overflow-hidden">
        @if(!$book->cover_image || $book->cover_image == 'covers/dummy.jpg')
            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="height: 300px;">
                <i class="fas fa-book fa-5x text-primary opacity-25"></i>
            </div>
        @else
            <img src="{{ Storage::url($book->cover_image) }}"
                 class="card-img-top book-cover-img"
                 alt="{{ $book->title }}"
                 style="height: 300px; object-fit: cover; transition: transform 0.5s ease;">
        @endif

        {{-- Price Badge --}}
        <div class="position-absolute top-0 end-0 m-3">
            @if($book->price == 0)
                <span class="badge bg-success fw-bold px-3 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-gift me-1"></i>FREE
                </span>
            @else
                <span class="badge price-badge fw-bold px-3 py-2 rounded-pill shadow-sm">
                    {{ setting('currency_symbol', '৳') }}{{ number_format($book->price, 0) }}
                </span>
            @endif
        </div>

        {{-- Category Badge --}}
        @if($book->category)
            <div class="position-absolute top-0 start-0 m-3">
                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm small">
                    <i class="fas fa-tag me-1"></i>{{ $book->category->name }}
                </span>
            </div>
        @endif

        {{-- Hover Overlay with Quick Actions --}}
        <div class="book-overlay position-absolute inset-0 d-flex align-items-center justify-content-center gap-2">
            <a href="{{ route('book.show', $book) }}" 
               class="btn btn-sm btn-light rounded-pill px-3 shadow-sm fw-semibold">
                <i class="fas fa-eye me-1"></i> Preview
            </a>
            @auth
                @if(Auth::user()->role === 'user')
                    <button onclick="addToCart({{ $book->id }})" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm fw-semibold">
                        <i class="fas fa-cart-plus me-1"></i> Cart
                    </button>
                @endif
            @endauth
        </div>
    </div>

    {{-- Card Body --}}
    <div class="card-body p-4 d-flex flex-column">
        <h5 class="card-title fw-bold mb-1 book-title">{{ $book->title }}</h5>
        <p class="card-text text-muted small mb-3 flex-grow-1">{{ Str::limit($book->description, 70) }}</p>

        {{-- Quick Action Button (Conditional for Free/Paid) --}}
        <div class="d-grid gap-2 mt-auto">
            @if($book->price == 0)
                <a href="{{ route('free.download', $book) }}"
                   class="btn btn-success rounded-pill py-2 fw-bold shadow-sm">
                    <i class="fas fa-download me-2"></i>{{ app()->getLocale() == 'bn' ? 'ফ্রি ডাউনলোড' : 'Download Free' }}
                </a>
                @if(!Auth::check() || Auth::user()->role === 'user')
                    <small class="text-center text-muted mt-1" style="font-size: 0.7rem;">
                        <i class="fas fa-info-circle me-1"></i>{{ app()->getLocale() == 'bn' ? 'লগইন প্রয়োজন হতে পারে' : 'Login may be required' }}
                    </small>
                @endif
            @else
                @auth
                    @if(Auth::user()->role === 'user')
                        <div class="d-flex gap-2">
                            <button onclick="addToCart({{ $book->id }})" class="btn btn-view-details rounded-pill py-2 fw-semibold flex-grow-1">
                                <i class="fas fa-shopping-cart me-2"></i>{{ app()->getLocale() == 'bn' ? 'কার্টে যোগ করুন' : 'Add to Cart' }}
                            </button>
                            <a href="{{ route('user.checkout', $book) }}"
                               class="btn btn-buy-now rounded-pill py-2 fw-bold px-4">
                                <i class="fas fa-bolt"></i>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('book.show', $book) }}"
                           class="btn btn-buy-now rounded-pill py-2 fw-bold">
                            <i class="fas fa-eye me-2"></i>{{ app()->getLocale() == 'bn' ? 'বিস্তারিত' : 'View Details' }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="btn btn-buy-now rounded-pill py-2 fw-bold">
                        <i class="fas fa-bolt me-2"></i>{{ app()->getLocale() == 'bn' ? 'এখনই কিনুন' : 'Buy Now' }}
                    </a>
                @endauth
            @endif
        </div>
    </div>
</div>
