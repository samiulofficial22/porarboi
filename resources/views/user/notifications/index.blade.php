@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="fw-bold mb-0">All Notifications</h4>
                </div>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('user.notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary rounded-pill px-4 btn-sm">Mark all as read</button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="notification-full-list">
                    @forelse($notifications as $notification)
                        <div class="p-4 border-bottom notification-page-item {{ !$notification->is_read ? 'unread' : '' }}" 
                             onclick="markAsRead(this, {{ $notification->id }})" 
                             style="cursor: pointer; transition: background 0.2s;">
                            <div class="d-flex gap-4">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas {{ $notification->type === 'order_approved' ? 'fa-check' : 'fa-bell' }} fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="fw-bold mb-0 {{ $notification->is_read ? 'text-muted' : '' }}">
                                            {{ $notification->type === 'order_approved' ? 'Order Approved' : 'Notification' }}
                                        </h6>
                                        <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-0 {{ $notification->is_read ? 'text-muted' : 'text-dark' }}">{{ $notification->message }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x mb-3 opacity-25"></i>
                            <p class="text-muted">You don't have any notifications yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($notifications->hasPages())
                <div class="card-footer bg-transparent border-0 p-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .notification-page-item:hover {
        background-color: rgba(99, 102, 241, 0.03);
    }
    .notification-page-item.unread {
        background-color: rgba(99, 102, 241, 0.06);
        border-left: 4px solid var(--primary-color);
    }
    [data-bs-theme="dark"] .notification-page-item.unread {
        background-color: rgba(99, 102, 241, 0.1);
    }
    [data-bs-theme="dark"] .notification-page-item.unread p {
        color: #f1f5f9 !important;
    }
    [data-bs-theme="dark"] .notification-page-item p {
        color: #94a3b8;
    }
</style>
@endsection
