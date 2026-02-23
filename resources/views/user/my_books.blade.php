@extends('layouts.app')

@section('title', 'My Books')

@section('content')
<h2 class="mb-4">My Purchased Books</h2>
@if($orders->count() > 0)
    <div class="row">
        @foreach($orders as $order)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ Storage::url($order->book->cover_image) }}" class="card-img-top" alt="{{ $order->book->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $order->book->title }}</h5>
                        <a href="{{ route('user.download', $order->book) }}" class="btn btn-primary w-100">
                            <i class="bi bi-download"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">You haven't purchased any books yet.</div>
@endif
@endsection
