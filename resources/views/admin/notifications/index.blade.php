@extends('layouts.admin')

@section('page_title', 'All Notifications')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="admin-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="mb-0">Notification History</h4>
                </div>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('user.notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-gradient btn-sm">Mark all as read</button>
                    </form>
                @endif
            </div>
            
            <div class="notification-full-list">
                @forelse($notifications as $notification)
                    <div class="p-4 rounded-4 mb-3 d-block notification-history-item {{ !$notification->is_read ? 'unread' : '' }}" 
                         onclick="markAsReadAdmin(this, {{ $notification->id }})"
                         style="border: 1px solid var(--border-color); cursor: pointer; transition: all 0.3s ease;">
                        <div class="d-flex gap-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.1); color: var(--accent-color);">
                                    <i class="fas {{ $notification->type === 'new_order' ? 'fa-cart-plus' : 'fa-bell' }} fs-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0 {{ $notification->is_read ? 'text-muted' : 'text-white' }}">
                                        {{ $notification->type === 'new_order' ? 'New Order Received' : 'System Notification' }}
                                    </h6>
                                    <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mb-0 {{ $notification->is_read ? 'text-muted' : 'text-white-50' }}">{{ $notification->message }}</p>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                @if(!$notification->is_read)
                                    <span class="badge bg-primary rounded-pill" style="width: 10px; height: 10px; padding: 0;">&nbsp;</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x mb-3 opacity-25"></i>
                        <p class="text-muted">No notifications found.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .notification-history-item:hover {
        background-color: var(--nav-link-hover);
        border-color: var(--accent-color) !important;
        transform: translateX(5px);
    }
    .notification-history-item.unread {
        background-color: rgba(99, 102, 241, 0.05);
        border-left: 5px solid var(--accent-color) !important;
    }
</style>
@endsection
