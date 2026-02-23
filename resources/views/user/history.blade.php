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
                                <th class="ps-4 border-0">Order Info</th>
                                <th class="border-0">Book & Format</th>
                                <th class="border-0">Payment Info</th>
                                <th class="border-0">Total Price</th>
                                <th class="border-0">Order Status</th>
                                <th class="border-0 text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <div class="small fw-bold">#{{ $order->id }}</div>
                                        <div class="text-muted smaller">{{ $order->created_at->format('d M, Y (h:i A)') }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ Storage::url($order->book->cover_image) }}" class="rounded-2" width="45" height="60" style="object-fit: cover;">
                                            <div>
                                                <span class="fw-bold d-block small">{{ Str::limit($order->book->title, 30) }}</span>
                                                <span class="badge {{ $order->selected_format === 'hardcopy' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $order->selected_format === 'hardcopy' ? 'text-primary' : 'text-danger' }} x-small">
                                                    {{ ucfirst($order->selected_format) }} x {{ $order->quantity }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="smaller mb-1">{{ strtoupper($order->payment_method) }}</div>
                                        @if($order->payment_status === 'paid')
                                            <span class="text-success fw-bold smaller uppercase"><i class="fas fa-check-circle me-1"></i>Paid</span>
                                        @else
                                            <span class="text-warning fw-bold smaller uppercase"><i class="fas fa-clock me-1"></i>Unpaid</span>
                                        @endif
                                        @if($order->transaction_id)
                                            <div class="smaller text-muted mt-1 fw-mono">{{ $order->transaction_id }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">৳{{ number_format($order->amount * $order->quantity + ($order->shipping_charge ?? 0), 0) }}</div>
                                        @if($order->shipping_charge > 0)
                                            <div class="x-small text-muted">Incl. ৳{{ number_format($order->shipping_charge, 0) }} shipping</div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $color = $statusColors[$order->order_status] ?? 'secondary';
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} px-3 py-2 uppercase x-small border border-{{ $color }} border-opacity-25">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($order->order_status === 'pending')
                                            <form action="{{ route('user.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    Cancel
                                                </button>
                                            </form>
                                        @elseif($order->selected_format === 'pdf' && $order->payment_status === 'paid')
                                            <a href="{{ route('user.my-books') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                <i class="fas fa-book-open me-1"></i>My Library
                                            </a>
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
    .x-small { font-size: 0.65rem; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
    .fw-mono { font-family: 'Courier New', Courier, monospace; }
    [data-bs-theme="dark"] .bg-light { background-color: rgba(255, 255, 255, 0.05) !important; }
</style>
@endsection
