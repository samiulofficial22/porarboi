@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row g-4">
    <!-- Welcome Section -->
    <div class="col-12">
        <div class="card border-0 rounded-5 overflow-hidden position-relative mb-4" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
            <div class="card-body p-5 position-relative z-1 text-white">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="d-flex align-items-center gap-4 mb-3">
                            @php
                                $dashAvatarUrl = auth()->user()->avatar ? (str_contains(auth()->user()->avatar, 'http') ? auth()->user()->avatar : Storage::url(auth()->user()->avatar)) : null;
                            @endphp
                            @if($dashAvatarUrl)
                                <img src="{{ $dashAvatarUrl }}" class="rounded-circle shadow-lg" width="80" height="80" style="object-fit: cover; border: 3px solid rgba(255,255,255,0.3);">
                            @else
                                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg fw-bold fs-3" style="width: 80px; height: 80px;">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h1 class="fw-bold mb-1">Welcome Back, {{ auth()->user()->name }}! 👋</h1>
                                <p class="mb-0 opacity-75">Your personal dashboard for managing your digital reading collection.</p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('categories.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">Explore More Books</a>
                            <a href="{{ route('user.my-books') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">My Library</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="position-absolute top-0 end-0 p-5 opacity-25">
                <i class="fas fa-book-reader fa-10x"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-book text-primary fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $purchasedBooks }}</h4>
                        <p class="text-muted mb-0 small uppercase letter-spacing-1">Purchased Books</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-wallet text-success fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">৳{{ number_format($totalSpent, 0) }}</h4>
                        <p class="text-muted mb-0 small uppercase letter-spacing-1">Total Spent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 rounded-4 shadow-sm h-100 stat-card-hover">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="fas fa-clock text-warning fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ \App\Models\Order::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h4>
                        <p class="text-muted mb-0 small uppercase letter-spacing-1">Pending Orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Activities</h5>
                <a href="{{ route('user.history') }}" class="text-primary small fw-bold text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0">Book</th>
                                <th class="border-0">Price</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Date</th>
                                <th class="border-0 text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestOrders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ Storage::url($order->book->cover_image) }}" class="rounded-3" width="40" height="40" style="object-fit: cover;">
                                            <span class="fw-bold">{{ $order->book->title }}</span>
                                        </div>
                                    </td>
                                    <td>৳{{ number_format($order->amount, 0) }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $order->status === 'approved' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $order->status === 'approved' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }} px-3">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="text-end pe-4">
                                        @if($order->status === 'pending')
                                            <form action="{{ route('user.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 small fw-bold text-decoration-none">Cancel</button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No recent activities</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Notifications</h5>
                <div class="d-flex gap-3">
                    @if($notifications->where('is_read', false)->count() > 0)
                        <form action="{{ route('user.notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm text-primary p-0">Mark all as read</button>
                        </form>
                    @endif
                    <a href="{{ route('user.notifications.index') }}" class="text-primary small fw-bold text-decoration-none">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="notification-feed p-0">
                    @forelse($notifications as $notification)
                        <div class="p-4 border-bottom notification-item {{ !$notification->is_read ? 'bg-light-blue' : '' }}" 
                             onclick="markAsRead(this, {{ $notification->id }})" 
                             style="cursor: pointer;">
                            <div class="d-flex gap-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas {{ $notification->type === 'order_approved' ? 'fa-check' : 'fa-bell' }} small"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small fw-bold @if($notification->is_read) text-muted @endif">{{ $notification->message }}</p>
                                    <span class="text-muted smaller" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted px-4">
                            <i class="fas fa-bell-slash fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">You have no notifications at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .letter-spacing-1 { letter-spacing: 1px; }
    .uppercase { text-transform: uppercase; }
    .smaller { font-size: 0.75rem; }
    
    .stat-card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    .notification-item {
        transition: background 0.2s;
    }
    .notification-item:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }
    
    .bg-light-blue {
        background-color: rgba(99, 102, 241, 0.06);
    }

    [data-bs-theme="dark"] .table {
        --bs-table-bg: transparent;
        --bs-table-hover-bg: rgba(255, 255, 255, 0.05);
        color: #f1f5f9;
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    [data-bs-theme="dark"] .bg-light {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }

    [data-bs-theme="dark"] .notification-item p {
        color: #f1f5f9;
    }
</style>
@endsection
