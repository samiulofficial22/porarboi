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
                    <h4 class="mb-0 text-white">Order #{{ $order->id }}</h4>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm px-3 rounded-pill no-print">
                        <i class="fas fa-print me-1"></i> Print Invoice
                    </button>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline no-print" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm px-3 rounded-pill">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="text-muted small uppercase mb-1 d-block">Customer Details</label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">{{ $order->user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small uppercase mb-1 d-block">Payment Summary</label>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-{{ $order->payment_method === 'cod' ? 'success' : 'info' }} bg-opacity-10 text-{{ $order->payment_method === 'cod' ? 'success' : 'info' }} rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fas fa-{{ $order->payment_method === 'cod' ? 'hand-holding-usd' : 'credit-card' }}"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">{{ strtoupper($order->payment_method) }}</h6>
                            <p class="text-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} small mb-0 fw-bold">{{ strtoupper($order->payment_status) }}</p>
                        </div>
                    </div>
                </div>
                
                @if($order->selected_format === 'hardcopy')
                <div class="col-12 border-top pt-4">
                    <h6 class="text-primary fw-bold mb-3 small uppercase"><i class="fas fa-truck me-2"></i>Shipping Information</h6>
                    <div class="bg-white bg-opacity-5 p-4 rounded-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Recipient</small>
                                <div class="text-white">{{ $order->shipping_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Phone</small>
                                <div class="text-white fw-bold">{{ $order->shipping_phone }}</div>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">Address</small>
                                <div class="text-white">{{ $order->shipping_address }}, {{ $order->shipping_district }} ({{ $order->shipping_postcode }})</div>
                            </div>
                            @if($order->delivery_note)
                            <div class="col-12">
                                <small class="text-muted d-block">Delivery Note</small>
                                <div class="text-white small italic">{{ $order->delivery_note }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="no-print">
                @csrf
                @method('PATCH')
                <div class="bg-white bg-opacity-5 p-4 rounded-4 border border-secondary border-opacity-10">
                    <h6 class="fw-bold mb-3 text-white">Manage Order Status</h6>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Order Status</label>
                            <select name="order_status" class="form-select bg-dark text-white border-0">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Confirmed (Subtracts Stock)</option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered (Marks Paid)</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled (Returns Stock)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Payment Status</label>
                            <select name="payment_status" class="form-select bg-dark text-white border-0">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Update Order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Book Info Card -->
        <div class="admin-card">
            <h5 class="fw-bold mb-4 text-white">Order Details</h5>
            <div class="d-flex gap-4">
                <div class="flex-shrink-0">
                    <img src="{{ Storage::url($order->book->cover_image) }}" class="rounded-4 shadow-sm" style="width: 100px; height: 140px; object-fit: cover;">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0 text-white">{{ $order->book->title }}</h5>
                    </div>
                    <div class="mb-3">
                        <span class="badge {{ $order->selected_format === 'hardcopy' ? 'bg-primary' : 'bg-danger' }} bg-opacity-10 {{ $order->selected_format === 'hardcopy' ? 'text-primary' : 'text-danger' }} small">
                            {{ ucfirst($order->selected_format) }}
                        </span>
                        <span class="ms-2 text-muted x-small">Qty: {{ $order->quantity }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-top border-secondary border-opacity-10 pt-3">
                        <div class="text-muted small">
                            Subtotal: ৳{{ number_format($order->amount, 0) }}<br>
                            Shipping: ৳{{ number_format($order->shipping_charge ?? 0, 0) }}
                        </div>
                        <div class="text-accent h4 fw-bold">৳{{ number_format($order->amount + ($order->shipping_charge ?? 0), 0) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 no-print">
        <!-- Payment Screenshot Card -->
        <div class="admin-card">
            <h5 class="fw-bold mb-4">Payment Proof</h5>
            @if($order->payment_screenshot)
                <div class="position-relative overflow-hidden rounded-4 mb-3 group">
                    <img src="{{ Storage::url($order->payment_screenshot) }}" class="img-fluid w-100 transition" alt="Payment Screenshot" id="screenshotImg" style="cursor: pointer;">
                </div>
                <div class="small mb-3 text-muted">
                    <div class="mb-1"><strong>TXN ID:</strong> <span class="text-info">{{ $order->transaction_id }}</span></div>
                    <div><strong>Sender:</strong> <span class="text-info">{{ $order->sender_number }}</span></div>
                </div>
                <a href="{{ Storage::url($order->payment_screenshot) }}" target="_blank" class="btn btn-outline-light w-100 rounded-pill btn-sm">
                    <i class="fas fa-external-link-alt me-2"></i> View Original
                </a>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                    <p>No proof uploaded<br><small>{{ $order->payment_method === 'cod' ? '(COD Order)' : '' }}</small></p>
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
    .uppercase { text-transform: uppercase; letter-spacing: 1px; }
    .x-small { font-size: 0.7rem; }
    .text-accent { color: #6366f1; }
    @media print {
        .no-print { display: none !important; }
        .admin-card { background: white !important; color: black !important; border: 1px solid #ddd !important; box-shadow: none !important; }
        .text-white { color: black !important; }
        body { background: white !important; }
    }
</style>
<script>
    document.getElementById('screenshotImg')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.getElementById('modalImg').src = this.src;
        modal.show();
    });
</script>
@endsection
