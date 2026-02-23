@extends('layouts.admin')

@section('page_title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Order Info Card -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h4 class="mb-0">Order #{{ $order->id }}</h4>
                </div>
                <div class="d-flex gap-2">
                    @if($order->status == 'pending')
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button name="status" value="approved" class="btn btn-success btn-sm px-3 rounded-pill">
                            <i class="fas fa-check me-1"></i> Approve
                        </button>
                        <button name="status" value="rejected" class="btn btn-outline-danger btn-sm px-3 rounded-pill ms-1">
                            <i class="fas fa-times me-1"></i> Reject
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="text-muted small uppercase mb-1 d-block">Customer Details</label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $order->user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small uppercase mb-1 d-block">Payment Method</label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Manual Payment</h6>
                            <p class="text-muted small mb-0">Sender: {{ $order->sender_number }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small uppercase mb-1 d-block">Transaction ID</label>
                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px dashed var(--border-color);">
                        <span class="fw-mono text-accent">{{ $order->transaction_id }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small uppercase mb-1 d-block">Order Timestamps</label>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Order Placed:</span>
                            <span>{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Approval Date:</span>
                            <span>{{ $order->approved_at ? $order->approved_at->format('M d, Y - h:i A') : 'Pending' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Info Card -->
        <div class="admin-card">
            <h5 class="fw-bold mb-4">Purchased Item</h5>
            <div class="d-flex gap-4">
                <div class="flex-shrink-0">
                    <img src="{{ Storage::url($order->book->cover_image) }}" class="rounded-4 shadow-sm" style="width: 120px; height: 160px; object-fit: cover;">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="fw-bold mb-0 text-white">{{ $order->book->title }}</h4>
                        <span class="h4 fw-bold text-accent">৳{{ number_format($order->amount, 0) }}</span>
                    </div>
                    <p class="text-muted mb-4">{{ Str::limit($order->book->description, 150) }}</p>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary">Digital Product</span>
                        <span class="badge bg-info bg-opacity-10 text-info">Instant Access</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Payment Screenshot Card -->
        <div class="admin-card h-100">
            <h5 class="fw-bold mb-4">Payment Proof</h5>
            @if($order->payment_screenshot)
                <div class="position-relative overflow-hidden rounded-4 mb-3 group">
                    <img src="{{ Storage::url($order->payment_screenshot) }}" class="img-fluid w-100 transition" alt="Payment Screenshot" id="screenshotImg" style="cursor: pointer;">
                    <div class="position-absolute inset-0 bg-dark bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition pointer-events-none">
                        <span class="text-white small fw-bold">Click to view full size</span>
                    </div>
                </div>
                <a href="{{ Storage::url($order->payment_screenshot) }}" target="_blank" class="btn btn-outline-light w-100 rounded-pill btn-sm">
                    <i class="fas fa-external-link-alt me-2"></i> Open in New Tab
                </a>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                    <p>No screenshot provided</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center">
                <img src="" id="modalImg" class="img-fluid rounded-4 shadow-lg">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .fw-mono { font-family: 'Courier New', Courier, monospace; }
    .uppercase { text-transform: uppercase; letter-spacing: 1px; }
    .transition { transition: all 0.3s ease; }
    .group:hover .transition { transform: scale(1.05); }
    .text-accent { color: var(--accent-color); }
</style>
<script>
    document.getElementById('screenshotImg')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImg').src = this.src;
        modal.show();
    });
</script>
@endsection
