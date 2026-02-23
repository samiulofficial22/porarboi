@extends('layouts.admin')

@section('page_title', 'Order Management')

@section('content')
<div class="mb-4 d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('method') ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">All Orders</a>
    <a href="{{ route('admin.orders.index', ['method' => 'cod']) }}" class="btn btn-sm {{ request('method') == 'cod' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">COD Orders</a>
    <a href="{{ route('admin.orders.index', ['method' => 'online']) }}" class="btn btn-sm {{ request('method') == 'online' ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">Online Payments</a>
</div>

<div class="admin-card">
    <h4 class="mb-4">Hybrid Order Tracking</h4>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Product & Format</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>
                        <div class="fw-bold text-emphasis">{{ $order->user->name }}</div>
                        <div class="small text-muted">{{ $order->shipping_phone ?? $order->user->phone }}</div>
                    </td>
                    <td>
                        <div class="small mb-1">{{ Str::limit($order->book->title, 25) }}</div>
                        <span class="badge {{ $order->selected_format === 'hardcopy' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $order->selected_format === 'hardcopy' ? 'text-primary' : 'text-danger' }} x-small">
                            {{ ucfirst($order->selected_format) }} x {{ $order->quantity }}
                        </span>
                    </td>
                    <td>
                        <div class="small mb-1 text-muted">{{ strtoupper($order->payment_method) }}</div>
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success bg-opacity-10 text-success x-small uppercase">Paid</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning x-small uppercase">Unpaid</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-10 text-muted x-small uppercase border border-secondary border-opacity-25">
                            {{ strtoupper($order->order_status) }}
                        </span>
                    </td>
                    <td class="fw-bold text-emphasis">৳{{ number_format($order->amount + ($order->shipping_charge ?? 0), 0) }}</td>
                    <td class="small">{{ $order->created_at->format('M d') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info rounded-pill px-3" title="View Details">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
    .x-small { font-size: 0.7rem; padding: 4px 8px; }
    .uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
</style>
@endsection
