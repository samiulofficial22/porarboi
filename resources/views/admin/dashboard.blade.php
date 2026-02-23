@extends('layouts.admin')

@section('page_title', 'Dashboard Overview')

@section('content')
<div class="row g-4">
    <!-- Total Sales -->
    <div class="col-md-3">
        <div class="admin-card">
            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3 class="fw-bold text-white mb-1">{{ $totalSales }}</h3>
            <p class="text-muted mb-0">Total Sales</p>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="col-md-3">
        <div class="admin-card">
            <div class="stats-icon bg-success bg-opacity-10 text-success">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3 class="fw-bold text-white mb-1">${{ number_format($totalRevenue, 2) }}</h3>
            <p class="text-muted mb-0">Total Revenue</p>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-md-3">
        <div class="admin-card">
            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="fw-bold text-white mb-1">{{ $pendingOrders }}</h3>
            <p class="text-muted mb-0">Pending Orders</p>
        </div>
    </div>

    <!-- Total Users -->
    <div class="col-md-3">
        <div class="admin-card">
            <div class="stats-icon bg-info bg-opacity-10 text-info">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="fw-bold text-white mb-1">{{ $totalUsers }}</h3>
            <p class="text-muted mb-0">Total Users</p>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-7">
        <div class="admin-card">
            <h4 class="mb-4">Revenue Analytics</h4>
            <div style="height: 400px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Admin Notifications Feed -->
    <div class="col-md-5">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Recent Activities</h4>
                <div class="d-flex gap-3">
                    @if($notifications->where('is_read', false)->count() > 0)
                        <form action="{{ route('user.notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm text-accent p-0 border-0 small fw-bold">Mark all as read</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.notifications.index') }}" class="text-accent small fw-bold text-decoration-none">View All</a>
                </div>
            </div>
            <div class="notification-feed-admin" style="max-height: 400px; overflow-y: auto;">
                @forelse($notifications as $notification)
                    <div class="notification-item-admin rounded-3 mb-2 {{ !$notification->is_read ? 'bg-unread-admin' : '' }}" 
                         onclick="markAsReadAdmin(this, {{ $notification->id }})"
                         style="border: 1px solid var(--border-color);">
                        <div class="d-flex gap-3 p-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <i class="fas {{ $notification->type === 'new_order' ? 'fa-cart-plus' : 'fa-bell' }} small"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 small text-white">{{ $notification->message }}</p>
                                <span class="text-muted smaller" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-bell-slash fa-2x mb-3 opacity-25"></i>
                        <p class="mb-0">No recent notifications</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesData);
    
    const labels = salesData.length ? salesData.map(data => data.months) : ['No Data'];
    const data = salesData.length ? salesData.map(data => data.sums) : [0];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: data,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#9ca3af'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#9ca3af'
                    }
                }
            }
        }
    });
</script>
@endsection
