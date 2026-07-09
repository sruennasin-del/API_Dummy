@extends('admin.layout.app')

@section('title', 'Edit Order ' . $order->order_number . ' - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <a href="{{ url('/admin/orders') }}" class="btn btn-sm btn-outline-secondary rounded-pill mb-2 px-3">
                <i class="ti ti-arrow-left"></i> Back to Orders
            </a>
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Manage Order: {{ $order->order_number }}</h1>
            <p class="text-muted mb-0">Update delivery details, status, and tracking code for this order.</p>
        </div>
    </div>

    {{-- ALERT BANNER FOR REFUND REQUESTS --}}
    @if($order->status === 'refund_requested')
    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 p-4 d-flex flex-wrap justify-content-between align-items-center" style="background-color: #fef3c7; color: #92400e;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-3 rounded-circle bg-white text-warning d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                <i class="ti ti-refresh-alert fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1" style="font-size: 16px;">Return / Refund Request Pending</h4>
                <p class="mb-0 text-muted" style="font-size: 13.5px;">The customer has requested a return/refund for this order. Approving will restore the product stock levels.</p>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3 mt-sm-0">
            <form action="{{ route('admin.orders.accept-refund', $order->id) }}" method="POST" class="confirm-form" data-title="Approve Refund?" data-text="This will return all items in this order to the inventory stock.">
                @csrf
                <button type="button" class="btn btn-success rounded-pill px-4 btn-confirm">
                    <i class="ti ti-check me-1"></i> Accept Refund
                </button>
            </form>
            <form action="{{ route('admin.orders.reject-refund', $order->id) }}" method="POST" class="confirm-form" data-title="Reject Request?" data-text="Are you sure you want to reject this refund request?">
                @csrf
                <button type="button" class="btn btn-outline-danger rounded-pill px-4 bg-white btn-confirm">
                    <i class="ti ti-x me-1"></i> Reject Request
                </button>
            </form>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- LEFT COLUMN: UPDATE ORDER FORM --}}
        <div class="col-lg-7">
            <div class="card-premium mb-4">
                <div class="card-premium-header">
                    <h2 class="card-premium-title">Update Shipment & Status</h2>
                </div>
                
                <div class="card-premium-body">
                    <form action="{{ url('/admin/orders/' . $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="status_select" class="form-label fw-bold">Order Delivery Status</label>
                                <select id="status_select" name="status" class="form-select rounded-3" required>
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending (Order Placed)</option>
                                    <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Processed (Preparing)</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (In Transit)</option>
                                    <option value="enroute" {{ $order->status === 'enroute' ? 'selected' : '' }}>En Route (Local Courier)</option>
                                    <option value="arrived" {{ $order->status === 'arrived' ? 'selected' : '' }}>Arrived at Destination</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered (Complete)</option>
                                    <option value="refund_requested" {{ $order->status === 'refund_requested' ? 'selected' : '' }}>Return Requested</option>
                                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded (Stock Returned)</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="courier_input" class="form-label fw-bold">Courier Partner</label>
                                <input type="text" id="courier_input" name="courier" class="form-control rounded-3" 
                                       value="{{ $order->courier }}" placeholder="e.g. USPS, DHL, J&T Express">
                            </div>

                            <div class="col-md-6">
                                <label for="tracking_number_input" class="form-label fw-bold">Shipment Tracking Number</label>
                                <input type="text" id="tracking_number_input" name="tracking_number" class="form-control rounded-3" 
                                       value="{{ $order->tracking_number }}" placeholder="e.g. 9400100000000000000000">
                            </div>

                            <div class="col-md-6">
                                <label for="eta_input" class="form-label fw-bold">Estimated Time of Arrival (ETA)</label>
                                <input type="text" id="eta_input" name="eta" class="form-control rounded-3" 
                                       value="{{ $order->eta }}" placeholder="e.g. 21/10/2026 or 2 days">
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ url('/admin/orders') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                            <button type="submit" class="btn btn-warning text-white rounded-pill px-4" style="background-color: var(--primary); border-color: var(--primary);">
                                Save Shipment Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- PRODUCTS ORDERED --}}
            <div class="card-premium">
                <div class="card-premium-header">
                    <h2 class="card-premium-title">Products in Order</h2>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product Item</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-3 border overflow-hidden bg-light" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                                @if($item->product_thumbnail)
                                                    <img src="{{ $item->product_thumbnail }}" alt="{{ $item->product_title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="ti ti-package text-muted" style="font-size: 22px;"></i>
                                                @endif
                                            </div>
                                            <span class="fw-semibold text-dark">{{ $item->product_title }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end fw-bold">${{ number_format($item->price * $item->qty, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-premium-body border-top">
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal:</span><strong>${{ number_format($order->subtotal, 2) }}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Service Fee:</span><strong>${{ number_format($order->service_fee, 2) }}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Delivery:</span><strong>${{ number_format($order->delivery_fee, 2) }}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Tax (10%):</span><strong>${{ number_format($order->tax, 2) }}</strong></div>
                            <div class="d-flex justify-content-between pt-2 border-top"><span class="fw-bold">Grand Total:</span><strong class="text-warning">${{ number_format($order->total, 2) }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: CLIENT INFORMATION --}}
        <div class="col-lg-5">
            <div class="card-premium">
                <div class="card-premium-header">
                    <h2 class="card-premium-title">Recipient & Contact Details</h2>
                </div>
                
                <div class="card-premium-body">
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <span class="text-muted d-block" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Full Recipient Name</span>
                            <span class="fs-6 fw-bold text-dark">{{ $order->customer_name }}</span>
                        </div>

                        <div>
                            <span class="text-muted d-block" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Contact Email</span>
                            <span class="fs-6 fw-bold text-dark">{{ $order->customer_email }}</span>
                        </div>

                        <div>
                            <span class="text-muted d-block" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Phone Number</span>
                            <span class="fs-6 fw-bold text-dark">{{ $order->customer_phone ?? 'N/A' }}</span>
                        </div>

                        <div>
                            <span class="text-muted d-block" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Payment Method Selected</span>
                            @if(strtolower($order->payment_method) === 'cash' || strtolower($order->payment_method) === 'cod')
                                <span class="badge bg-success px-3 py-2 fw-semibold"><i class="ti ti-truck me-1"></i> Cash on Delivery</span>
                            @else
                                <span class="badge bg-primary px-3 py-2 fw-semibold"><i class="ti ti-building-bank me-1"></i> Bank Transfer ({{ strtoupper($order->payment_method) }})</span>
                            @endif
                        </div>

                        <hr class="my-2" style="border-color: #eee;">

                        <div>
                            <span class="text-muted d-block" style="font-size: 12px; font-weight: bold; text-transform: uppercase;">Shipping / Delivery Address</span>
                            <p class="fs-6 fw-bold text-dark m-0" style="line-height: 1.5;">{{ $order->customer_address }}</p>
                        </div>
                        
                        @if($order->user_id)
                            <div class="p-3 rounded-3 mt-2" style="background-color: var(--light-bg); border: 1px solid var(--border-color);">
                                <span class="d-block text-muted mb-1" style="font-size: 11px;">Registered Account ID</span>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar" style="width: 30px; height: 30px; font-size: 11px; background-color: #fff; border: 1px solid var(--border-color); color: var(--primary); display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                                        U
                                    </div>
                                    <span class="fw-bold text-dark fs-6">User Account Connected</span>
                                </div>
                            </div>
                        @else
                            <div class="p-3 rounded-3 mt-2 text-center" style="background-color: #f8f9fa; border: 1px dashed #ccc;">
                                <small class="text-muted">Placed as a Guest Checkout</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
