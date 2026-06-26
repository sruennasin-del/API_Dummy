@extends('admin.layout.app')

@section('title', 'Manage Orders - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-12">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Order & Shipment Management</h1>
            <p class="text-muted mb-0">Track and update customer order shipment statuses, couriers, and delivery tracking IDs.</p>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" role="alert" style="background-color: #DEF7EC; color: #03543F;">
            <i class="ti ti-circle-check-filled me-2 fs-4" style="color: #0E9F6E;"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(24%) sepia(37%) saturate(3065%) hue-rotate(130deg) brightness(93%) contrast(92%);"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" role="alert" style="background-color: #FDE8E8; color: #9B1C1C;">
            <i class="ti ti-circle-x-filled me-2 fs-4" style="color: #F05252;"></i>
            <div class="fw-semibold">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(16%) sepia(85%) saturate(3195%) hue-rotate(345deg) brightness(85%) contrast(98%);"></button>
        </div>
    @endif

    <!-- Filters & Table Card -->
    <div class="card-premium">
        <!-- Table Search Toolbar -->
        <div class="card-premium-header flex-column flex-sm-row gap-3">
            <div>
                <h2 class="card-premium-title">All Customer Orders</h2>
                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $orders->total() }} orders found</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
                <form action="{{ url('/admin/orders') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto">
                    <div class="position-relative w-100 w-sm-auto" style="min-width: 220px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search order, client..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
                        <i class="ti ti-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="form-select rounded-pill px-3 text-muted" style="width: 150px; font-size: 13.5px; border-color: var(--border-color);">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                        <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="enroute" {{ request('status') === 'enroute' ? 'selected' : '' }}>En Route</option>
                        <option value="arrived" {{ request('status') === 'arrived' ? 'selected' : '' }}>Arrived</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ url('/admin/orders') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Clear Filters" style="font-size: 13px;">
                            <i class="ti ti-refresh"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table table-premium">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Delivery Status</th>
                        <th>Created Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ url('/admin/orders/' . $order->id . '/edit') }}" class="fw-bold text-dark fs-6" style="text-decoration: underline; color: var(--primary) !important;">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark">{{ $order->customer_name }}</span>
                                    <small class="text-muted" style="font-size: 11px;">{{ $order->customer_email }}</small>
                                </div>
                            </td>
                            <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1" style="font-size: 11px;">
                                    {{ strtoupper($order->payment_method) }}
                                </span>
                            </td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: #b25e00 !important; background-color: #fff6eb !important;">
                                        Pending
                                    </span>
                                @elseif($order->status === 'processed')
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                        Processed
                                    </span>
                                @elseif($order->status === 'shipped')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: #0284c7 !important; background-color: #f0f9ff !important;">
                                        Shipped
                                    </span>
                                @elseif($order->status === 'enroute')
                                    <span class="badge bg-info text-white rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; background-color: var(--orange) !important;">
                                        En Route
                                    </span>
                                @elseif($order->status === 'arrived' || $order->status === 'delivered')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: #166534 !important; background-color: #f0fdf4 !important;">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: #991b1b !important; background-color: #fef2f2 !important;">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $order->created_at ? $order->created_at->format('M d, Y h:i A') : 'N/A' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ url('/admin/orders/' . $order->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" style="color: #64748B;" title="Manage Order Details & Status">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ url('/admin/orders/' . $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" style="color: #EF4444;" title="Delete Order">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="ti ti-receipt fs-1 mb-2 d-block text-secondary opacity-50"></i>
                                <span class="d-block fw-semibold mb-1">No Orders Found</span>
                                <span>No orders have been placed yet, or search criteria didn't match.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($orders->hasPages())
            <div class="card-premium-body d-flex justify-content-between align-items-center border-top py-3">
                <div class="text-muted" style="font-size: 13px;">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
