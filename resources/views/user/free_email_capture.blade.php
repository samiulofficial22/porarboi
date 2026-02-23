@extends('layouts.app')

@section('title', 'Download ' . $book->title)

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="bg-primary p-4 text-center text-white">
                <i class="fas fa-envelope-open-text fa-3x mb-3 opacity-75"></i>
                <h3 class="fw-bold mb-0">Almost there!</h3>
                <p class="mb-0 opacity-75">Enter your email to get the free download link</p>
            </div>
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                    <img src="{{ Storage::url($book->cover_image) }}" class="rounded shadow-sm me-3" width="50" height="70" style="object-fit: cover;">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $book->title }}</h6>
                        <span class="badge bg-success">Free PDF</span>
                    </div>
                </div>

                <form action="{{ route('free.download', $book) }}" method="GET">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">Your Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-primary"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="name@example.com" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                        <i class="fas fa-download me-2"></i>Download Free PDF
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="text-primary fw-bold">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
