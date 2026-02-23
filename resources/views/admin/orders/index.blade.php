@extends('layouts.admin')

@section('page_title', 'Order Management')

@section('content')
<div class="admin-card">
    <h4 class="mb-4">Recent Orders</h4>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Book</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Approve Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td class="text-white">{{ $order->user->name }}</td>
                    <td>{{ $order->book->title }}</td>
                    <td class="fw-bold">৳{{ number_format($order->amount, 0) }}</td>
                    <td>
                        @if($order->status == 'approved')
                            <span class="badge bg-success bg-opacity-10 text-success p-2 rounded px-3">Approved</span>
                        @elseif($order->status == 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded px-3">Pending</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger p-2 rounded px-3">Rejected</span>
                        @endif
                    </td>
                    <td class="small">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="small">{{ $order->approved_at ? $order->approved_at->format('M d, Y') : '-' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($order->status == 'pending')
                            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button name="status" value="approved" class="btn btn-sm btn-gradient py-1 px-3">Approve</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
