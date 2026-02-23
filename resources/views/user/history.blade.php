@extends('layouts.app')

@section('title', 'Order History')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-0 p-4 d-flex align-items-center gap-3">
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Order History</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Date</th>
                                <th class="border-0">Book</th>
                                <th class="border-0">Transaction ID</th>
                                <th class="border-0">Amount</th>
                                <th class="border-0">Status</th>
                                <th class="border-0 text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-muted small">{{ $order->created_at->format('d M, Y') }}</span><br>
                                        <span class="text-muted smaller">{{ $order->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ Storage::url($order->book->cover_image) }}" class="rounded-2" width="40" height="50" style="object-fit: cover;">
                                            <div>
                                                <span class="fw-bold d-block">{{ $order->book->title }}</span>
                                                <span class="text-muted smaller">Sender: {{ $order->sender_number }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code class="small text-accent">{{ $order->transaction_id }}</code>
                                    </td>
                                    <td class="fw-bold">৳{{ number_format($order->amount, 0) }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $order->status === 'approved' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $order->status === 'approved' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }} px-3">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($order->status === 'pending')
                                            <form action="{{ route('user.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    Cancel
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-history fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">No order history found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($orders->hasPages())
                <div class="card-footer bg-transparent border-0 p-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .smaller { font-size: 0.75rem; }
    .text-accent { color: #6366f1; }
    [data-bs-theme="dark"] .bg-light { background-color: rgba(255, 255, 255, 0.05) !important; }
</style>
@endsection
