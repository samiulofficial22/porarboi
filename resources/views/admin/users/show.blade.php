@extends('layouts.admin')

@section('page_title', 'User Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i>Back to User List
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="admin-card text-center py-5">
            <div class="mb-4">
                @php
                    $avatarUrl = $user->avatar ? (str_contains($user->avatar, 'http') ? $user->avatar : Storage::url($user->avatar)) : null;
                @endphp

                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" class="rounded-circle shadow-sm" width="100" height="100" style="object-fit: cover; border: 3px solid rgba(99, 102, 241, 0.2);">
                @else
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; border: 3px solid rgba(99, 102, 241, 0.2);">
                        <i class="fas fa-user fa-3x text-primary opacity-50"></i>
                    </div>
                @endif
            </div>
            <h4 class="fw-bold text-white mb-1">{{ $user->name }}</h4>
            <p class="text-muted small mb-4">{{ $user->email }}</p>
            <div class="badge bg-dark border border-secondary px-3 py-2 rounded-pill fs-6 mb-4">
                <i class="fas fa-phone me-2 text-primary"></i>{{ $user->phone ?? 'No Phone' }}
            </div>
            
            <hr class="my-4 opacity-10">
            
            <div class="row g-0">
                <div class="col-6 border-end border-secondary border-opacity-25">
                    <h5 class="fw-bold text-white mb-0">{{ $totalOrders }}</h5>
                    <small class="text-muted uppercase tracking-wider small">Orders</small>
                </div>
                <div class="col-6">
                    <h5 class="fw-bold text-white mb-0">{{ setting('currency_symbol', '৳') }}{{ number_format($totalSpent, 0) }}</h5>
                    <small class="text-muted uppercase tracking-wider small">Spent</small>
                </div>
            </div>
        </div>

        <div class="admin-card mt-4">
            <h6 class="fw-bold text-muted text-uppercase small mb-3 tracking-wider">Account Information</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Role:</span>
                <span class="badge bg-primary rounded-pill">{{ ucfirst($user->role) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Joined:</span>
                <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Google ID:</span>
                <span class="text-white">{{ $user->google_id ?: 'Not Linked' }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card">
            <h5 class="fw-bold text-white mb-4">Order History</h5>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Book</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $order)
                        <tr>
                            <td class="small">#{{ $order->id }}</td>
                            <td class="text-white fw-semibold">{{ $order->book->title }}</td>
                            <td>{{ setting('currency_symbol', '৳') }}{{ number_format($order->amount, 0) }}</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'approved' => 'bg-success',
                                        'pending' => 'bg-warning',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} rounded-pill px-3">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="small text-muted">{{ $order->created_at->format('d M, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No orders found for this user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
