@extends('layouts.welcome')
@section('content')

<div class="container my-5" style="max-width: 1000px; font-family: 'DM Sans', sans-serif;">

    {{-- ALERT MESSAGES --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 px-4 py-3 mb-4" role="alert" style="background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
            <i class="ti ti-alert-triangle me-2 fs-5 align-middle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 px-4 py-3 mb-4" role="alert" style="background-color: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA;">
            <i class="ti ti-circle-check me-2 fs-5 align-middle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- LEFT COLUMN: TRACKING INFO & ORDER DETAILS --}}
        <div class="col-lg-8">
            
            @if($order)
                {{-- HEADER INFO --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <span class="badge rounded-pill mb-2 px-3 py-2" style="background-color: var(--orange-light); color: var(--orange); font-weight: 600;">
                                {{ ucfirst($order->status) }}
                            </span>
                            <h2 class="m-0 fw-bold" style="font-family: 'Syne', sans-serif; font-size: 26px;">
                                Order <span style="color: var(--orange);">#{{ $order->order_number }}</span>
                            </h2>
                            <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                        <div class="text-lg-end">
                            <div class="mb-1"><small class="text-muted">Courier:</small> <strong>{{ $order->courier ?? 'ZestShop Courier' }}</strong></div>
                            <div class="mb-1"><small class="text-muted">Tracking ID:</small> <code class="text-dark fw-bold">{{ $order->tracking_number ?? 'Assigning soon' }}</code></div>
                            <div><small class="text-muted">ETA:</small> <strong style="color: var(--orange);">{{ $order->eta ?? 'Calculating...' }}</strong></div>
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: #FFE8D8;">

                    {{-- TRACKING STEPS VISUAL --}}
                    <div class="position-relative my-4" style="padding: 0 10px;">
                        {{-- LINE BACKGROUND --}}
                        <div style="position: absolute; top: 20px; left: 4%; right: 4%; height: 5px; background: #e9ecef; border-radius: 10px; z-index: 0;"></div>

                        {{-- LINE PROGRESS (FILLED) --}}
                        @if($order->status !== 'cancelled')
                            <div style="position: absolute; top: 20px; left: 4%; width: {{ $progressWidth }}; height: 5px; background: var(--orange); border-radius: 10px; z-index: 0; transition: width 0.8s ease-in-out;"></div>
                        @endif

                        {{-- STEPS CONTAINER --}}
                        <div class="d-flex justify-content-between align-items-start position-relative" style="z-index: 1;">
                            
                            {{-- STEP 1: Placed --}}
                            <div class="text-center" style="width: 20%;">
                                <div class="step-circle {{ $currentStep >= 1 ? 'active' : '' }}">
                                    ✓
                                </div>
                                <span class="d-block mt-2 fw-semibold" style="font-size: 12px; color: {{ $currentStep >= 1 ? 'var(--text-dark)' : 'var(--text-muted)' }};">Placed</span>
                            </div>

                            {{-- STEP 2: Processed --}}
                            <div class="text-center" style="width: 20%;">
                                <div class="step-circle {{ $currentStep >= 2 ? 'active' : '' }}">
                                    @if($currentStep >= 2) ✓ @else 2 @endif
                                </div>
                                <span class="d-block mt-2 fw-semibold" style="font-size: 12px; color: {{ $currentStep >= 2 ? 'var(--text-dark)' : 'var(--text-muted)' }};">Processed</span>
                            </div>

                            {{-- STEP 3: Shipped --}}
                            <div class="text-center" style="width: 20%;">
                                <div class="step-circle {{ $currentStep >= 3 ? 'active' : '' }}">
                                    @if($currentStep >= 3) ✓ @else 3 @endif
                                </div>
                                <span class="d-block mt-2 fw-semibold" style="font-size: 12px; color: {{ $currentStep >= 3 ? 'var(--text-dark)' : 'var(--text-muted)' }};">Shipped</span>
                            </div>

                            {{-- STEP 4: Enroute --}}
                            <div class="text-center" style="width: 20%;">
                                <div class="step-circle {{ $currentStep >= 4 ? 'active' : '' }}">
                                    🚚
                                </div>
                                <span class="d-block mt-2 fw-semibold" style="font-size: 12px; color: {{ $currentStep >= 4 ? 'var(--text-dark)' : 'var(--text-muted)' }};">En Route</span>
                            </div>

                            {{-- STEP 5: Delivered --}}
                            <div class="text-center" style="width: 20%;">
                                <div class="step-circle {{ $currentStep >= 5 ? 'active' : '' }}">
                                    🏠
                                </div>
                                <span class="d-block mt-2 fw-semibold" style="font-size: 12px; color: {{ $currentStep >= 5 ? 'var(--text-dark)' : 'var(--text-muted)' }};">Delivered</span>
                            </div>

                        </div>
                    </div>

                    {{-- STATUS DESCRIPTION CARD --}}
                    <div class="p-3 rounded-3 mt-4" style="background: var(--orange-pale); border: 1px solid var(--orange-border);">
                        <h5 class="fw-bold m-0 mb-1" style="font-size: 15px; color: var(--orange);">Status Update</h5>
                        <p class="m-0 text-dark" style="font-size: 14px; line-height: 1.5;">
                            @if($order->status === 'pending')
                                Your order has been registered and is waiting to be approved and processed by our staff.
                            @elseif($order->status === 'processed')
                                Your order has been processed and is currently being packed. We will update tracking details as soon as it ships.
                            @elseif($order->status === 'shipped')
                                Your order has been shipped and is in transit. You can track its location with the tracking number provided.
                            @elseif($order->status === 'enroute')
                                Your package is with our local courier and is scheduled to be delivered to your address today.
                            @elseif($order->status === 'delivered')
                                The courier has delivered your order. Thank you for choosing ZestShop!
                            @elseif($order->status === 'cancelled')
                                <span class="text-danger fw-bold">This order has been cancelled.</span> If you believe this is a mistake, please contact customer support.
                            @else
                                Your order is being handled. Current status: {{ $order->status }}.
                            @endif
                        </p>
                    </div>
                </div>

                {{-- ORDER ITEMS --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #fff;">
                    <h4 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;">Items in this Shipment</h4>
                    
                    <div class="d-flex flex-column gap-3">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center justify-content-between gap-3 p-2 rounded-3 border-light border">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 overflow-hidden bg-light border" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                        @if($item->product_thumbnail)
                                            <img src="{{ $item->product_thumbnail }}" alt="{{ $item->product_title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="ti ti-package text-muted" style="font-size: 28px;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="m-0 fw-bold">{{ $item->product_title }}</h6>
                                        <small class="text-muted">${{ number_format($item->price, 2) }} x {{ $item->qty }}</small>
                                    </div>
                                </div>
                                <div class="text-end fw-bold text-dark">
                                    ${{ number_format($item->price * $item->qty, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal:</span><strong>${{ number_format($order->subtotal, 2) }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Service Fee:</span><strong>${{ number_format($order->service_fee, 2) }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Delivery:</span><strong>${{ number_format($order->delivery_fee, 2) }}</strong></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax (10%):</span><strong>${{ number_format($order->tax, 2) }}</strong></div>
                        <div class="d-flex justify-content-between pt-2 border-top"><span class="fw-bold fs-5">Total:</span><strong class="fs-5" style="color: var(--orange);">${{ number_format($order->total, 2) }}</strong></div>
                    </div>
                </div>

                {{-- CUSTOMER DETAILS --}}
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fff;">
                    <h4 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;">Delivery & Payment Information</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Recipient Name</small>
                            <span class="fw-bold">{{ $order->customer_name }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Contact Email</small>
                            <span class="fw-bold">{{ $order->customer_email }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Phone Number</small>
                            <span class="fw-bold">{{ $order->customer_phone ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Payment Method</small>
                            <span class="badge bg-secondary px-3 py-2 fw-semibold">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Shipping Address</small>
                            <span class="fw-bold">{{ $order->customer_address }}</span>
                        </div>
                    </div>
                </div>

            @else
                {{-- NO ORDER ACTIVE --}}
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center" style="background: #fff; border: 1px solid #FFE8D8 !important;">
                    <div class="my-4">
                        <i class="ti ti-package-off text-muted" style="font-size: 70px;"></i>
                    </div>
                    <h3 class="fw-bold" style="font-family: 'Syne', sans-serif;">No active orders found</h3>
                    <p class="text-muted mx-auto" style="max-width: 450px;">
                        To track an order, please enter your Order Number in the search panel or browse our shop to place a new order.
                    </p>
                    <div class="mt-4">
                        <a href="{{ url('/all-sub-products') }}" class="btn btn-warning rounded-pill px-4 py-2 text-white" style="background-color: var(--orange); border-color: var(--orange);">
                            Browse Catalog
                        </a>
                    </div>
                </div>
            @endif

        </div>

        {{-- RIGHT COLUMN: SEARCH & USER ORDERS LIST --}}
        <div class="col-lg-4">
            
            {{-- SEARCH COMPONENT --}}
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #fff;">
                <h5 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;">Track Another Shipment</h5>
                
                <form action="{{ route('delivery.search') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="order_number_input" class="form-label text-muted">Order Number</label>
                        <input type="text" 
                               class="form-control rounded-3" 
                               id="order_number_input" 
                               name="order_number" 
                               required 
                               placeholder="e.g. ORD-AB12CD34" 
                               value="{{ old('order_number') ?: ($orderNumber ?? '') }}">
                    </div>
                    <button type="submit" class="btn btn-warning w-100 rounded-pill text-white py-2" style="background-color: var(--orange); border-color: var(--orange); font-weight: 600;">
                        <i class="ti ti-search me-1"></i> Track Order
                    </button>
                </form>
            </div>

            {{-- LOGGED IN USER ORDERS --}}
            @auth
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fff;">
                    <h5 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;">Your Order History</h5>
                    
                    @if($userOrders->isNotEmpty())
                        <div class="d-flex flex-column gap-2" style="max-height: 400px; overflow-y: auto;">
                            @foreach($userOrders as $historyOrder)
                                <a href="{{ route('delivery.track', ['order_number' => $historyOrder->order_number]) }}" 
                                   class="d-block p-3 rounded-3 border text-decoration-none transition-all {{ $order && $order->id === $historyOrder->id ? 'active-history-item' : 'history-item' }}">
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark" style="font-size: 14px;">#{{ $historyOrder->order_number }}</strong>
                                        <span class="badge" style="font-size: 10px; background-color: {{ $historyOrder->status === 'delivered' ? '#DEF7EC' : ($historyOrder->status === 'cancelled' ? '#FEE2E2' : '#FFF0E8') }}; color: {{ $historyOrder->status === 'delivered' ? '#03543F' : ($historyOrder->status === 'cancelled' ? '#991B1B' : 'var(--orange)') }};">
                                            {{ ucfirst($historyOrder->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $historyOrder->created_at->format('M d, Y') }}</small>
                                        <span class="fw-bold text-dark" style="font-size: 13px;">${{ number_format($historyOrder->total, 2) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted m-0 text-center py-3" style="font-size: 13px;">You have not placed any orders yet.</p>
                    @endif
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background: var(--orange-pale); border: 1px dashed var(--orange-border) !important;">
                    <h6 class="fw-bold mb-2" style="color: var(--orange);">Want to see all your orders?</h6>
                    <p class="text-muted mb-3" style="font-size: 13.5px;">Log in to access your complete purchase history and manage tracking easily.</p>
                    <a href="{{ url('/login') }}" class="btn btn-outline-warning w-100 rounded-pill py-2" style="border-color: var(--orange); color: var(--orange); font-size: 13.5px; font-weight: 600;">
                        Log In
                    </a>
                </div>
            @endauth

        </div>

    </div>

</div>

@push('css')
<style>
    .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e9ecef;
        color: #999;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-weight: bold;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 2px solid #fff;
    }
    .step-circle.active {
        background: var(--orange);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(255, 107, 26, 0.25);
    }
    
    .history-item {
        background: #fff;
        border-color: #eee !important;
        transition: all 0.2s ease;
    }
    .history-item:hover {
        background: var(--orange-pale);
        border-color: var(--orange-border) !important;
    }
    
    .active-history-item {
        background: var(--orange-pale);
        border: 1px solid var(--orange) !important;
    }
</style>
@endpush

@endsection