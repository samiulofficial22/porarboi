@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <!-- Order Summary -->
        <div class="col-lg-4 order-lg-last">
            <div class="card border-0 rounded-5 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    <div class="cart-items-list mb-4" id="checkout-items">
                        @foreach($cart as $key => $item)
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-10">
                            <img src="{{ Storage::url($item['cover_image']) }}" class="rounded-3 flex-shrink-0" width="50" height="70" style="object-fit: cover;">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="fw-bold mb-0 text-truncate small me-2">{{ $item['title'] }}</h6>
                                    <button type="button"
                                        onclick="removeCheckoutItem('{{ $key }}', this)"
                                        class="remove-item-btn"
                                        title="Remove">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <p class="text-muted mb-0 x-small">{{ ($item['format'] ?? 'pdf') === 'hardcopy' ? 'Physical' : 'PDF' }} x {{ $item['quantity'] ?? 1 }}</p>
                                <div class="text-primary fw-bold small">৳{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal</span>
                        <span class="fw-bold small">৳{{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Shipping</span>
                        <span class="fw-bold small text-success">{{ $shipping > 0 ? '৳' . number_format($shipping, 0) : 'FREE' }}</span>
                    </div>
                    <hr class="my-3 opacity-10">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold">Total</span>
                        <h4 class="fw-bold text-primary mb-0">৳{{ number_format($total, 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Form -->
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Complete Your Order</h2>
            
            <form action="{{ route('user.order.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Shipping Information -->
                @if($hasPhysical)
                <div class="card border-0 rounded-5 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-truck me-2"></i>Shipping Details</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Full Name</label>
                                <input type="text" name="shipping_name" class="form-control rounded-pill" placeholder="Recipient Name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Phone Number</label>
                                <input type="text" name="shipping_phone" class="form-control rounded-pill" placeholder="017XXXXXXXX" value="{{ auth()->user()->phone }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Full Address</label>
                                <textarea name="shipping_address" rows="3" class="form-control rounded-4" placeholder="House/Road/Area details..." required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">District</label>
                                <input type="text" name="shipping_district" class="form-control rounded-pill" placeholder="e.g. Dhaka, Chittagong" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Postcode</label>
                                <input type="text" name="shipping_postcode" class="form-control rounded-pill" placeholder="1200">
                            </div>
                            <div class="col-12">
                                <label class="form-label small text-muted">Delivery Note (Optional)</label>
                                <input type="text" name="delivery_note" class="form-control rounded-pill" placeholder="Instructions for the courier">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Payment Method -->
                <div class="card border-0 rounded-5 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-wallet me-2"></i>Payment Information</h5>
                        
                        <div class="row g-3 mb-4">
                            <!-- Online Payment (Always available) -->
                            <div class="col-md-{{ $isOnlyPhysical ? '6' : '12' }}">
                                <div class="payment-option border rounded-4 p-3 cursor-pointer position-relative h-100 {{ !$isOnlyPhysical ? 'active' : '' }}" id="opt-online">
                                    <input type="radio" name="payment_method" value="online" class="position-absolute opacity-0" checked>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-mobile-alt text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Online Payment</div>
                                            <div class="x-small text-muted text-uppercase fw-semibold">bKash / Rocket / Nagad</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cash on Delivery (Only for purely Physical items) -->
                            @if($isOnlyPhysical)
                            <div class="col-md-6">
                                <div class="payment-option border rounded-4 p-3 cursor-pointer position-relative h-100" id="opt-cod">
                                    <input type="radio" name="payment_method" value="cod" class="position-absolute opacity-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                                            <i class="fas fa-hand-holding-usd text-success"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Cash on Delivery</div>
                                            <div class="x-small text-muted text-uppercase fw-semibold">Pay when you receive</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($hasDigital && $hasPhysical)
                            <div class="col-12 mt-2">
                                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 rounded-4 small mb-0">
                                    <i class="fas fa-exclamation-circle me-2"></i>Cash on Delivery is <strong>unavailable</strong> because your cart contains digital books (PDF). Please pay online to complete your purchase.
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Online Payment Fields -->
                        <div id="online-payment-details">
                            <div class="alert alert-info bg-opacity-10 border-info rounded-4 p-3 mb-4">
                                <div class="d-flex gap-2 mb-2">
                                    <i class="fas fa-info-circle text-info mt-1"></i>
                                    <div class="fw-bold">Payment Instructions</div>
                                </div>
                                <div class="small">
                                    {!! nl2br(e(setting('payment_instructions', 'Please send money to our bKash number.'))) !!}<br>
                                    bKash Number: <strong>{{ setting('bkash_number', '017XXXXXXXX') }}</strong><br>
                                    Total Amount: <strong>৳{{ number_format($total, 0) }}</strong>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Sender Number</label>
                                    <input type="text" name="sender_number" id="sender_number" class="form-control rounded-pill" placeholder="01XXXXXXXXX" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Transaction ID</label>
                                    <input type="text" name="transaction_id" id="transaction_id" class="form-control rounded-pill" placeholder="TRX12345678" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small text-muted">Payment Screenshot (Optional)</label>
                                    <input type="file" name="payment_screenshot" id="payment_screenshot" class="form-control rounded-4" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <!-- COD Details -->
                        <div id="cod-details" class="d-none">
                            <div class="alert alert-success bg-opacity-10 border-success rounded-4 p-3">
                                <div class="d-flex gap-2">
                                    <i class="fas fa-check-circle text-success mt-1"></i>
                                    <div>
                                        <div class="fw-bold">COD Selected</div>
                                        <div class="small">You will pay <strong>৳{{ number_format($total, 0) }}</strong> when you receive your physical book(s).</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid mb-5">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow">
                        <i class="fas fa-lock me-2"></i>Confirm Order & Pay ৳{{ number_format($total, 0) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .payment-option {
        transition: all 0.3s ease;
        border: 2px solid transparent !important;
        cursor: pointer;
    }
    .payment-option:hover {
        border-color: rgba(99, 102, 241, 0.3) !important;
        background: rgba(99, 102, 241, 0.02);
    }
    .payment-option.active {
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, 0.05);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
    }
    .cursor-pointer { cursor: pointer; }
    .x-small { font-size: 0.7rem; }

    /* Remove button on checkout item */
    .remove-item-btn {
        background: none;
        border: none;
        padding: 2px 6px;
        border-radius: 8px;
        color: #9ca3af;
        font-size: 0.85rem;
        line-height: 1;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .remove-item-btn:hover {
        color: #ef4444;
        background: rgba(239,68,68,0.1);
        transform: scale(1.15);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ──── Payment Option Toggle ──── */
    const onlineOpt    = document.getElementById('opt-online');
    const codOpt       = document.getElementById('opt-cod');
    const onlineFields = document.getElementById('online-payment-details');
    const codDetails   = document.getElementById('cod-details');
    const submitBtn    = document.querySelector('button[type="submit"]');
    const senderInput  = document.getElementById('sender_number');
    const txnInput     = document.getElementById('transaction_id');

    function selectOnline() {
        if (!onlineOpt) return;
        onlineOpt.classList.add('active');
        if (codOpt) codOpt.classList.remove('active');

        onlineFields.style.display = '';
        if (codDetails) codDetails.classList.add('d-none');

        if (senderInput) senderInput.required = true;
        if (txnInput)    txnInput.required    = true;

        submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Confirm &amp; Pay ৳{{ number_format($total, 0) }}';
    }

    function selectCod() {
        if (!codOpt) return;
        codOpt.classList.add('active');
        onlineOpt.classList.remove('active');

        // Completely hide bKash section
        onlineFields.style.display = 'none';
        if (codDetails) codDetails.classList.remove('d-none');

        if (senderInput) { senderInput.required = false; senderInput.value = ''; }
        if (txnInput)    { txnInput.required    = false; txnInput.value    = ''; }

        submitBtn.innerHTML = '<i class="fas fa-truck me-2"></i>Place COD Order &mdash; ৳{{ number_format($total, 0) }}';
    }

    if (onlineOpt) {
        onlineOpt.addEventListener('click', function () {
            onlineOpt.querySelector('input[type=radio]').checked = true;
            selectOnline();
        });
    }

    if (codOpt) {
        codOpt.addEventListener('click', function () {
            codOpt.querySelector('input[type=radio]').checked = true;
            selectCod();
        });
    }

    // Initial state — always Online first
    selectOnline();
});

/* ──── Remove Item from Checkout Cart ──── */
function removeCheckoutItem(cartKey, btn) {
    if (!confirm('এই বইটি cart থেকে সরাতে চান?')) return;

    // Visual feedback
    if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').content
                    : '{{ csrf_token() }}';

    fetch('/user/cart/remove/' + cartKey, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(function(res) {
        if (!res.ok) throw new Error('Server error');
        return res.json();
    })
    .then(function(data) {
        if (data.status === 'success') {
            if (data.cart_count === 0) {
                window.location.href = '{{ route("home") }}';
            } else {
                window.location.reload();
            }
        } else {
            alert('Could not remove item. Please try again.');
            if (btn) { btn.innerHTML = '<i class="fas fa-trash-alt"></i>'; btn.disabled = false; }
        }
    })
    .catch(function() {
        window.location.reload();
    });
}
</script>
@endsection
