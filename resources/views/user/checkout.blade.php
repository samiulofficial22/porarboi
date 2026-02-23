@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">Checkout</div>
            <div class="card-body">
                <h5>Book: {{ $book->title }}</h5>
                <p class="text-muted">Price: {{ setting('currency_symbol', '৳') }}{{ number_format($book->price, 2) }}</p>
                <hr>
                <div class="alert alert-info">
                    <strong>Payment Instructions:</strong><br>
                    {!! nl2br(e(setting('payment_instructions', 'Please send money to our bKash number.'))) !!}<br><br>
                    bKash Number: <strong>{{ setting('bkash_number', '017XXXXXXXX') }}</strong><br>
                    Amount: <strong>{{ setting('currency_symbol', '৳') }}{{ number_format($book->price, 2) }}</strong><br>
                    Use Reference: <strong>REF-{{ auth()->id() }}-{{ $book->id }}</strong>
                </div>

                <form action="{{ route('user.order.store', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Transaction ID</label>
                        <input type="text" name="transaction_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Sender Number</label>
                        <input type="text" name="sender_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Payment Screenshot</label>
                        <input type="file" name="payment_screenshot" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
