@extends('layouts.app')

@section('title', 'My Library')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">My Book Library</h2>
        <p class="text-muted mb-0">Access all your purchased digital books and resources.</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i>Find More
    </a>
</div>

@if($orders->count() > 0)
    <div class="row g-4">
        @foreach($orders as $order)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="book-card-premium h-100 bg-white bg-opacity-5 rounded-5 overflow-hidden border border-white border-opacity-10 transition hover-shadow">
                    <div class="position-relative">
                        <img src="{{ Storage::url($order->book->cover_image) }}" class="w-100" style="aspect-ratio: 3/4; object-fit: cover;" alt="{{ $order->book->title }}">
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-danger bg-opacity-75 backdrop-blur px-3 py-2 rounded-pill small uppercase">PDF</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h6 class="fw-bold text-white mb-3 text-truncate">{{ $order->book->title }}</h6>
                        <a href="{{ route('user.download', $order->book) }}" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                            <i class="fas fa-download me-2"></i>Download
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5 border rounded-5 bg-white bg-opacity-5">
        <div class="mb-4 opacity-25">
            <i class="fas fa-book-open fa-5x"></i>
        </div>
        <h4 class="fw-bold text-white">Your library is empty</h4>
        <p class="text-muted mb-4">Books you purchase as PDF will appear here for instant download.</p>
        <a href="{{ route('home') }}" class="btn btn-gradient px-5 rounded-pill py-3">
            Start Exploring
        </a>
    </div>
@endif
@endsection

@section('styles')
<style>
    .backdrop-blur { backdrop-filter: blur(8px); }
    .uppercase { text-transform: uppercase; letter-spacing: 1px; }
    .book-card-premium { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .book-card-premium:hover { transform: translateY(-10px); background: rgba(255, 255, 255, 0.08); }
</style>
@endsection
