@extends('layouts.welcome')
@section('content')
<div class="container my-4">

    {{-- header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">

        <h1 class="cart-title">
            Your order
        </h1>

        @if(count($cart) > 0)

        <form action="/cart/clear" method="POST">
            @csrf

            <button type="submit" class="btn-clear">
                <i class="bi bi-trash"></i>
                Clear all
            </button>
        </form>

        @endif

    </div>


    @php

        $subtotal = collect($cart)->sum(function($item){
            return ($item['price'] ?? 2.50) * $item['qty'];
        });

        $service = 1.50;
        $delivery = 2.00;
        $tax = $subtotal * 0.10;

        $appliedCoupon = session('applied_coupon');
        $couponDiscount = $appliedCoupon ? $appliedCoupon['discount'] : 0;

        $grandTotal = max(0, $subtotal + $service + $delivery + $tax - $couponDiscount);

    @endphp


    @if(count($cart) === 0)

        {{-- empty --}}
        <div class="cart-empty">

            <i class="bi bi-cart2"></i>

            <p>
                Your order is empty
            </p>

            <a href="/" class="btn-back">
                Browse menu
            </a>

        </div>

    @else

    <form id="checkout-form" action="{{ url('/checkout') }}" method="POST">
        @csrf
        <div class="cart-layout">

        {{-- LEFT --}}
        <div class="cart-left">

            <div class="cart-list">

                @foreach($cart as $index => $item)

                <div class="cart-item">

                    {{-- number --}}
                    <div class="cart-item-left">

                        <div class="cart-item-num">
                            {{ $loop->iteration }}
                        </div>

                    </div>


                    {{-- info --}}
                    <div class="cart-item-info">
                        <div class="cart-item-name d-flex align-items-center gap-3">
                            <img width="80px" class="rounded-3" src="{{ $item['thumbnail'] }}" alt="{{ $item['title'] }}">
                            <div>
                                <p class="mb-1" style="font-size: 15px;">{{ $item['title'] }}</p>
                                @if(isset($item['color_name']) || isset($item['size_name']))
                                <div class="text-muted mb-1" style="font-size: 13px;">
                                    @if(isset($item['color_name'])) <span>Color: {{ $item['color_name'] }}</span> @endif
                                    @if(isset($item['color_name']) && isset($item['size_name'])) <span class="mx-1">|</span> @endif
                                    @if(isset($item['size_name'])) <span>Size: {{ $item['size_name'] }}</span> @endif
                                </div>
                                @endif
                                <div class="fw-bold" style="font-size: 14px; color: var(--orange);">${{ number_format($item['price'], 2) }}</div>
                            </div>
                        </div>
                    </div>


                    {{-- qty --}}
                    <div class="cart-qty-box">

                        {{-- decrease --}}      
                        <button type="submit" form="form-decrease-{{ $index }}" class="qty-btn">
                            -
                        </button>

                        <span class="qty-text">
                            {{ $item['qty'] }}
                        </span>

                        {{-- increase --}}
                        <button type="submit" form="form-increase-{{ $index }}" class="qty-btn">
                            +
                        </button>

                    </div>


                    {{-- remove --}}
                    <button type="submit" form="form-remove-{{ $index }}" class="cart-item-remove">
                        <i class="bi bi-trash"></i>
                    </button>

                </div>

                @endforeach



            </div>

            {{-- Delivery details --}}
            <div class="delivery-details-card mt-4">
                <h4 class="delivery-title mb-3">
                    <i class="bi bi-truck me-2 text-warning" style="color: var(--orange) !important;"></i> Delivery Information
                </h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label text-muted">Full Name</label>
                        <input type="text" class="form-control rounded-3" id="customer_name" name="customer_name" 
                               value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="e.g. John Doe">
                    </div>
                    <div class="col-md-6">
                        <label for="customer_email" class="form-label text-muted">Email Address</label>
                        <input type="email" class="form-control rounded-3" id="customer_email" name="customer_email" 
                               value="{{ Auth::check() ? Auth::user()->email : '' }}" placeholder="e.g. john@example.com">
                    </div>
                    <div class="col-md-6">
                        <label for="customer_phone" class="form-label text-muted">Phone Number</label>
                        <input type="tel" class="form-control rounded-3" id="customer_phone" name="customer_phone" 
                               placeholder="e.g. 012345678">
                    </div>
                    <div class="col-md-6">
                        <label for="payment_display" class="form-label text-muted">Selected Payment</label>
                        <input type="text" class="form-control rounded-3 bg-light" id="payment_display" readonly value="ABA Bank">
                    </div>
                    <div class="col-12">
                        <label for="customer_address" class="form-label text-muted">Shipping Address</label>
                        <textarea class="form-control rounded-3" id="customer_address" name="customer_address" 
                                  rows="3" placeholder="Street Name, Apartment, City, etc."></textarea>
                    </div>
                </div>
            </div>

        </div>


        {{-- RIGHT --}}
        <div class="cart-right  sticky-top" style="top: 90px">

            <div class="summary-card">

                <h4 class="summary-title">
                    Order Summary
                </h4>


                <div class="summary-row">
                    <span>Total items</span>

                    <span>
                        {{ collect($cart)->sum('qty') }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Subtotal</span>

                    <span>
                        ${{ number_format($subtotal, 2) }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Service fee</span>

                    <span>
                        ${{ number_format($service, 2) }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Delivery</span>

                    <span>
                        ${{ number_format($delivery, 2) }}
                    </span>
                </div>


                <div class="summary-row">
                    <span>Tax</span>

                    <span>
                        ${{ number_format($tax, 2) }}
                    </span>
                </div>

                {{-- Coupon discount row --}}
                <div class="summary-row" id="coupon-discount-row" style="{{ $couponDiscount > 0 ? '' : 'display:none;' }} color:#16a34a;">
                    <span><i class="ti ti-discount-2 me-1"></i>Coupon <span id="coupon-code-label">{{ $appliedCoupon['code'] ?? '' }}</span></span>
                    <span id="coupon-discount-value">-${{ number_format($couponDiscount, 2) }}</span>
                </div>


                <div class="summary-divider"></div>


                <div class="summary-total">

                    <span>Total</span>

                    <span id="grand-total-value">
                        ${{ number_format($grandTotal, 2) }}
                    </span>

                </div>


                {{-- payment --}}
                <div class="payment-section">

                    <h5 class="payment-title">
                        Payment Method
                    </h5>


                    <div class="payment-list">

                        {{-- ABA --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="ABA"
                                   checked>

                            <div class="payment-content">

                                <img src="https://www.ababank.com/typo3conf/ext/boxmodel/Resources/Private/Templates/ABA/images/aba-web-top-logo.png">

                                <div>
                                    <div class="payment-name">
                                        ABA Bank
                                    </div>

                                    <div class="payment-desc">
                                        Pay with ABA Mobile
                                    </div>
                                </div>

                            </div>

                        </label>


                        {{-- ACLEDA --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="ACLEDA">

                            <div class="payment-content">

                                <img src="https://companieslogo.com/img/orig/ABC.KH-3aa8d94f.png?t=1720244490">

                                <div>
                                    <div class="payment-name">
                                        ACLEDA
                                    </div>

                                    <div class="payment-desc">
                                        ACLEDA payment
                                    </div>
                                </div>

                            </div>

                        </label>


                        {{-- Wing --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="Wing">

                            <div class="payment-content">

                                <img src="https://eu-images.contentstack.com/v3/assets/blt7dacf616844cf077/bltf5f103d9d932958a/67998395c3d5893a67957346/Wing-Bank.png.jpg?width=960&auto=webp&quality=80&format=jpg&disable=upscale">

                                <div>
                                    <div class="payment-name">
                                        Wing Bank
                                    </div>

                                    <div class="payment-desc">
                                        Wing transfer
                                    </div>
                                </div>

                            </div>

                        </label>


                        {{-- Cash --}}
                        <label class="payment-card">

                            <input type="radio"
                                   name="payment_method"
                                   value="Cash">

                            <div class="payment-content">

                                <div class="cash-icon">
                                    <i class="bi bi-cash-stack"></i>
                                </div>

                                <div>

                                    <div class="payment-name">
                                        Cash
                                    </div>

                                    <div class="payment-desc">
                                        Pay on delivery
                                    </div>

                                </div>

                            </div>

                        </label>

                    </div>

                </div>


                {{-- Coupon Code --}}
                <div class="mt-4 mb-3" id="coupon-section">
                    {{-- Applied coupon banner --}}
                    <div id="coupon-applied-banner" class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-2" style="background:#f0fdf4;border:1px solid #bbf7d0;{{ ($appliedCoupon && $couponDiscount > 0) ? '' : 'display:none;' }}">
                        <div>
                            <i class="ti ti-discount-2 text-success me-1"></i>
                            <strong class="text-success" id="applied-code-text" style="font-family:monospace;letter-spacing:1px;">{{ $appliedCoupon['code'] ?? '' }}</strong>
                            <span class="text-success ms-2">— You save <span id="applied-save-text">${{ number_format($couponDiscount, 2) }}</span></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" id="remove-coupon-btn" style="font-size:12px;">Remove</button>
                    </div>
                    <input type="hidden" name="coupon_code" id="hidden-coupon-code" value="{{ $appliedCoupon['code'] ?? '' }}">
                    <input type="hidden" name="coupon_discount" id="hidden-coupon-discount" value="{{ $couponDiscount }}">

                    {{-- Coupon input --}}
                    <div id="coupon-input-area" style="{{ ($appliedCoupon && $couponDiscount > 0) ? 'display:none;' : '' }}">
                        <label class="form-label text-muted" style="font-size:13px;font-weight:600;">Have a Coupon?</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="coupon-input" class="form-control rounded-3" placeholder="Enter coupon code..." style="font-family:monospace;letter-spacing:1px;text-transform:uppercase;">
                            <button type="button" id="apply-coupon-btn" class="btn rounded-3 text-white px-4 fw-semibold" style="background:var(--orange);white-space:nowrap;">Apply</button>
                        </div>
                        <div id="coupon-message" class="mt-2" style="font-size:13px;display:none;"></div>
                    </div>
                </div>

                {{-- button --}}
                @auth
                <button type="submit" class="btn-place-order">
                    <i class="bi bi-check-circle"></i>
                    Place order · <span id="btn-total-text">${{ number_format($grandTotal, 2) }}</span>
                </button>
                @else
                <button type="button" onclick="window.location.href='{{ url('/login') }}'" class="btn-place-order">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login to place order
                </button>
                @endauth

            </div>

        </div>

    </form>

    {{-- Hidden forms for quantity and remove actions to avoid nested forms --}}
    @foreach($cart as $index => $item)
        <form id="form-decrease-{{ $index }}" action="{{ url('/cart/decrease') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="index" value="{{ $index }}">
        </form>
        <form id="form-increase-{{ $index }}" action="{{ url('/cart/increase') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="index" value="{{ $index }}">
        </form>
        <form id="form-remove-{{ $index }}" action="{{ url('/cart/remove') }}" method="POST" style="display:none;">
            @csrf
            <input type="hidden" name="index" value="{{ $index }}">
        </form>
    @endforeach

    @endif

</div>

<!-- QR Code Payment Modal -->
<div class="modal fade" id="qrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
      <div class="modal-body text-center p-5">
        
        <!-- Bank Logo / Header -->
        <div class="mb-4 d-flex justify-content-center align-items-center gap-3">
            <h4 class="fw-bold m-0" id="qr-bank-name" style="font-size: 22px; color: var(--text-dark);">Digital Payment</h4>
        </div>
        
        <p class="text-muted mb-4" style="font-size: 14.5px;">Please scan the QR code with your mobile banking app to complete your payment of <strong style="color: var(--orange); font-size: 16px;" id="qr-total-amount">${{ number_format($grandTotal ?? 0, 2) }}</strong></p>
        
        <!-- QR Code Wrapper -->
        <div class="d-inline-block p-3 bg-white rounded-4 shadow-sm mb-4 border" style="border-color: #eee !important;">
            <img id="qr-image" src="{{ asset('images/aba_qr.jpg') }}" alt="QR Code" width="220" style="border-radius: 8px; object-fit: contain;">
        </div>
        
        <!-- Timer -->
        <div class="payment-timer bg-light py-3 px-4 rounded-pill d-inline-flex align-items-center mb-2" style="border: 1px solid #eaeaea;">
            <div class="spinner-border spinner-border-sm text-primary me-3" role="status" style="color: var(--orange) !important;"></div>
            <span class="fw-medium text-dark" style="font-size: 14px;">Waiting for payment... <strong id="countdown" class="ms-1" style="color: var(--orange); font-size: 16px;">30</strong>s</span>
        </div>
        
        <!-- Cancel Action -->
        <div class="mt-3">
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Cancel Payment</button>
        </div>
        
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {

        // Current totals for dynamic updates
        const BASE_SUBTOTAL = {{ $subtotal }};
        const SERVICE = {{ $service }};
        const DELIVERY = {{ $delivery }};
        const TAX = {{ $tax }};
        let currentDiscount = {{ $couponDiscount }};

        function updateSummaryUI(discount, code) {
            currentDiscount = discount;
            const newTotal = Math.max(0, BASE_SUBTOTAL + SERVICE + DELIVERY + TAX - discount);

            // Update discount row
            if (discount > 0) {
                $('#coupon-discount-row').show();
                $('#coupon-code-label').text(code);
                $('#coupon-discount-value').text('-$' + discount.toFixed(2));
            } else {
                $('#coupon-discount-row').hide();
            }

            // Update grand total
            $('#grand-total-value').text('$' + newTotal.toFixed(2));
            $('#btn-total-text').text('$' + newTotal.toFixed(2));

            // Update QR modal total
            $('#qr-total-amount').text('$' + newTotal.toFixed(2));
        }
        
        // ─── COUPON APPLY ──────────────────────────────────────────
        $('#apply-coupon-btn').on('click', function() {
            const code = $('#coupon-input').val().trim();
            if (!code) return;

            const btn = $(this);
            btn.prop('disabled', true).text('Applying...');

            $.ajax({
                url: '{{ route("coupon.apply") }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', code: code, subtotal: BASE_SUBTOTAL },
                success: function(res) {
                    const msg = $('#coupon-message');
                    msg.show();
                    if (res.success) {
                        msg.html('<span class="text-success"><i class="ti ti-circle-check me-1"></i>' + res.message + '</span>');

                        // Update UI dynamically
                        updateSummaryUI(res.discount, res.code);

                        // Toggle input/banner
                        $('#coupon-input-area').hide();
                        $('#applied-code-text').text(res.code);
                        $('#applied-save-text').text('$' + res.discount.toFixed(2));
                        $('#coupon-applied-banner').show();

                        // Set hidden fields
                        $('#hidden-coupon-code').val(res.code);
                        $('#hidden-coupon-discount').val(res.discount);
                    } else {
                        msg.html('<span class="text-danger"><i class="ti ti-circle-x me-1"></i>' + res.message + '</span>');
                    }
                    btn.prop('disabled', false).text('Apply');
                },
                error: function() {
                    btn.prop('disabled', false).text('Apply');
                }
            });
        });

        // ─── COUPON REMOVE ─────────────────────────────────────────
        $('#remove-coupon-btn').on('click', function() {
            $.post('{{ route("coupon.remove") }}', { _token: '{{ csrf_token() }}' }, function() {
                updateSummaryUI(0, '');

                // Toggle banner/input
                $('#coupon-applied-banner').hide();
                $('#coupon-input-area').show();
                $('#coupon-input').val('');
                $('#coupon-message').hide();

                // Clear hidden fields
                $('#hidden-coupon-code').val('');
                $('#hidden-coupon-discount').val(0);
            });
        });
        
        // Function to toggle required fields based on payment method
        function toggleRequiredFields(paymentMethod) {
            const fields = ['#customer_name', '#customer_email', '#customer_phone', '#customer_address'];
            
            if (paymentMethod === 'Cash') {
                // If Cash, require delivery info
                fields.forEach(field => $(field).prop('required', true));
            } else {
                // If Bank, delivery info is optional
                fields.forEach(field => $(field).prop('required', false));
            }
        }
        
        // Initial setup on page load (ABA is checked by default)
        toggleRequiredFields($('input[name="payment_method"]:checked').val());

        $('input[name="payment_method"]').on('change', function() {
            var val = $(this).val();
            var displayText = 'ABA Bank';
            if (val === 'ACLEDA') displayText = 'ACLEDA';
            else if (val === 'Wing') displayText = 'Wing Bank';
            else if (val === 'Cash') displayText = 'Cash on Delivery';
            $('#payment_display').val(displayText);
            
            // Toggle required constraints when payment method changes
            toggleRequiredFields(val);
        });

        // Checkout Form Interceptor
        let paymentTimer;
        
        $('#checkout-form').on('submit', function(e) {
            var paymentMethod = $('input[name="payment_method"]:checked').val();
            
            if (paymentMethod !== 'Cash') {
                e.preventDefault(); // Stop normal submission
                
                // Update Modal UI with selected bank name
                var bankText = paymentMethod === 'ABA' ? 'ABA Bank' : (paymentMethod === 'ACLEDA' ? 'ACLEDA' : 'Wing Bank');
                $('#qr-bank-name').text(bankText);
                
                // Dynamically update the QR Code Image based on the bank selected!
                if (paymentMethod === 'ABA') {
                    $('#qr-image').attr('src', '{{ asset("images/aba_qr.jpg") }}');
                } else if (paymentMethod === 'ACLEDA') {
                    $('#qr-image').attr('src', '{{ asset("images/acleda_qr.jpg") }}'); // Placeholder for future
                } else if (paymentMethod === 'Wing') {
                    $('#qr-image').attr('src', '{{ asset("images/wing_qr.jpg") }}'); // Placeholder for future
                }
                
                // Show the modal
                var qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
                qrModal.show();
                
                // Start 30 second countdown
                let timeLeft = 30;
                $('#countdown').text(timeLeft);
                
                paymentTimer = setInterval(function() {
                    timeLeft--;
                    $('#countdown').text(timeLeft);
                    
                    if (timeLeft <= 0) {
                        clearInterval(paymentTimer);
                        // Once 30s is over, simulate successful payment and submit form
                        $('.payment-timer').html('<i class="bi bi-check-circle-fill text-success me-2" style="font-size: 18px;"></i> <span class="fw-bold text-success">Payment Received! Processing...</span>');
                        
                        setTimeout(() => {
                            document.getElementById('checkout-form').submit();
                        }, 1500);
                    }
                }, 1000);
            }
        });
        
        // Handle Cancel Button (Reset modal when it is closed)
        $('#qrModal').on('hidden.bs.modal', function () {
            clearInterval(paymentTimer); // Stop the countdown
            
            // Reset the HTML back to its original state so it's ready for the next time
            $('.payment-timer').html('<div class="spinner-border spinner-border-sm text-primary me-3" role="status" style="color: var(--orange) !important;"></div><span class="fw-medium text-dark" style="font-size: 14px;">Waiting for payment... <strong id="countdown" class="ms-1" style="color: var(--orange); font-size: 16px;">30</strong>s</span>');
        });
    });
</script>

@push('css')
    

<style>

.delivery-details-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 14px;
    padding: 1.5rem;
    text-align: left;
}
.delivery-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-dark);
}
.form-label {
    font-size: 13px;
    font-weight: 600;
}
.form-control:focus {
    border-color: #D85A30;
    box-shadow: 0 0 0 0.25rem rgba(216, 90, 48, 0.25);
}

/* layout */
.cart-layout{
    display:grid;
    grid-template-columns:1fr 340px;
    gap:24px;
    align-items:start;
}

/* title */
.cart-title{
    font-size:22px;
    font-weight:700;
}

/* clear */
.btn-clear{
    border:none;
    background:#FCEBEB;
    color:#E24B4A;
    padding:10px 16px;
    border-radius:10px;
    cursor:pointer;
    font-size:13px;
}

/* empty */
.cart-empty{
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:14px;
    padding:6rem 1rem;
}

.cart-empty i{
    font-size:60px;
    color:#ddd;
}

.cart-empty p{
    color:#999;
}

.btn-back{
    padding:10px 20px;
    border:1px solid #D85A30;
    border-radius:10px;
    text-decoration:none;
    color:#D85A30;
}

/* list */
.cart-list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

/* item */
.cart-item{
    background:#fff;
    border:1px solid #eee;
    border-radius:14px;
    padding:1rem;
    display:flex;
    align-items:center;
    gap:14px;
}

/* number */
.cart-item-num{
    width:36px;
    height:36px;
    border-radius:50%;
    background:#FAECE7;
    color:#993C1D;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:13px;
    font-weight:600;
}

/* info */
.cart-item-info{
    flex:1;
}

.cart-item-name{
    font-size:15px;
    font-weight:600;
    margin-bottom:6px;
}

.cart-item-meta{
    display:flex;
    gap:6px;
}

.meta-badge{
    background:#FAECE7;
    color:#993C1D;
    padding:4px 9px;
    border-radius:6px;
    font-size:11px;
}

/* qty */
.cart-qty-box{
    display:flex;
    align-items:center;
    gap:8px;
}

.qty-btn{
    width:30px;
    height:30px;
    border:none;
    border-radius:8px;
    background:#FAECE7;
    color:#993C1D;
    cursor:pointer;
    font-size:16px;
    font-weight:700;
}

.qty-btn:hover{
    background:#D85A30;
    color:#fff;
}

.qty-text{
    min-width:24px;
    text-align:center;
    font-weight:600;
}

/* remove */
.cart-item-remove{
    width:34px;
    height:34px;
    border:none;
    border-radius:50%;
    background:#f5f5f5;
    cursor:pointer;
}

.cart-item-remove:hover{
    background:#FCEBEB;
    color:#E24B4A;
}

/* right */
.summary-card{
    background:#fff;
    border:1px solid #eee;
    border-radius:16px;
    padding:1.5rem;
    position:sticky;
    top:20px;
}

.summary-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:1.2rem;
}

.summary-row{
    display:flex;
    justify-content:space-between;
    padding:8px 0;
    font-size:14px;
    color:#666;
}

.summary-divider{
    height:1px;
    background:#eee;
    margin:14px 0;
}

.summary-total{
    display:flex;
    justify-content:space-between;
    font-size:18px;
    font-weight:700;
    margin-bottom:1rem;
}

/* payment */
.payment-section{
    margin-bottom:1.5rem;
}

.payment-title{
    font-size:15px;
    font-weight:600;
    margin-bottom:12px;
}

.payment-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.payment-card{
    border:1px solid #eee;
    border-radius:12px;
    padding:12px;
    cursor:pointer;
    transition:.2s;
}

/* hover */
.payment-card:hover{
    border-color:#D85A30;
    background:#fff8f5;
}

/* active selected card */
.payment-card:has(input:checked){
    border:1px solid #D85A30;
    background:#fff8f5;
}

/* text color */
.payment-card input:checked + .payment-content .payment-name{
    color:#D85A30;
}


.payment-card input{
    display:none;
}

.payment-content{
    display:flex;
    align-items:center;
    gap:12px;
}

.payment-content img{
    width:42px;
    height:42px;
    object-fit:contain;
}

.payment-name{
    font-size:14px;
    font-weight:600;
}

.payment-desc{
    font-size:12px;
    color:#888;
}

.cash-icon{
    width:42px;
    height:42px;
    border-radius:10px;
    background:#FAECE7;
    color:#D85A30;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

/* button */
.btn-place-order{
    width:100%;
    border:none;
    background:#D85A30;
    color:#fff;
    padding:14px;
    border-radius:12px;
    cursor:pointer;
    font-size:15px;
    font-weight:600;
}

.btn-place-order:hover{
    background:#c24b24;
}

/* mobile */
@media(max-width:991px){

    .cart-layout{
        grid-template-columns:1fr;
    }

    .summary-card{
        position:static;
    }
    .cart-item-name img{
        width: 34px;
        display: block;
    }
    .cart-item-name p{
        font-size: 10px;      
    }


}

</style>
@endpush

@endsection