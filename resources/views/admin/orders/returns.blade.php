@extends('admin.layout.app')

@section('title', 'Manage Returns & Refunds - ZestShop')

@section('content')
<!-- Header -->
<div class="row align-items-center mb-4">
    <div class="col-md-12">
        <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Returns & Refunds Management</h1>
        <p class="text-muted mb-0">Approve or reject customer return requests, and automatically update inventory stock levels.</p>
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
            <h2 class="card-premium-title">Return & Refund Requests</h2>
            <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $orders->total() }} records found</p>
        </div>

        <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
            <form action="{{ route('admin.returns.index') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto">
                <div class="position-relative w-100 w-sm-auto" style="min-width: 220px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search order, client..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
                    <i class="ti ti-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                </div>

                <select name="status" onchange="this.form.submit()" class="form-select rounded-pill px-3 text-muted" style="width: 180px; font-size: 13.5px; border-color: var(--border-color);">
                    <option value="">All Return Statuses</option>
                    <option value="refund_requested" {{ request('status') === 'refund_requested' ? 'selected' : '' }}>Return Requested</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>

                @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Clear Filters" style="font-size: 13px;">
                    <i class="ti ti-refresh"></i>
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table View -->
    <div class="table-responsive">
        <table class="table table-premium align-middle">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items Ordered</th>
                    <th>Total Refund</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Last Updated</th>
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
                    <td>
                        <div class="d-flex flex-column" style="max-width: 250px;">
                            @foreach($order->items as $item)
                                <div class="text-truncate" style="font-size: 12.5px;">
                                    <span class="badge bg-secondary-subtle text-secondary rounded px-1.5 py-0.5" style="font-size: 10px;">x{{ $item->qty }}</span>
                                    {{ $item->product_title }}
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="fw-bold text-danger">${{ number_format($order->total, 2) }}</td>
                    <td>
                        @if(strtolower($order->payment_method) === 'cash' || strtolower($order->payment_method) === 'cod')
                            <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 11px; font-weight: 600; color: #15803d !important; background-color: #f0fdf4 !important;">
                                <i class="ti ti-truck me-1"></i> Cash
                            </span>
                        @else
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-size: 11px; font-weight: 600; color: #1d4ed8 !important; background-color: #eff6ff !important;">
                                <i class="ti ti-building-bank me-1"></i> Bank ({{ strtoupper($order->payment_method) }})
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($order->status === 'refund_requested')
                        <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: #b45309 !important; background-color: #fef3c7 !important;">
                            Return Requested
                        </span>
                        @elseif($order->status === 'refunded')
                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: #047857 !important; background-color: #d1fae5 !important;">
                            Refunded
                        </span>
                        @endif
                    </td>
                    <td class="text-muted" style="font-size: 12.5px;">{{ $order->updated_at ? $order->updated_at->format('M d, Y h:i A') : 'N/A' }}</td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-2">
                            @if($order->status === 'refund_requested')
                            <form action="{{ route('admin.orders.accept-refund', $order->id) }}" method="POST" class="confirm-form" data-title="Approve Refund?" data-text="This will return the products back to inventory stock." style="display: inline-block;">
                                @csrf
                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 btn-confirm">
                                    <i class="ti ti-check me-1"></i> Approve
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.orders.reject-refund', $order->id) }}" method="POST" class="confirm-form" data-title="Reject Request?" data-text="Are you sure you want to reject this refund request?" style="display: inline-block;">
                                @csrf
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-confirm">
                                    <i class="ti ti-x me-1"></i> Reject
                                </button>
                            </form>
                            @else
                            <span class="text-success fw-bold" style="font-size: 13px;"><i class="ti ti-circle-check me-1"></i> Completed</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="ti ti-refresh-alert fs-1 mb-2 d-block text-secondary opacity-50"></i>
                        <span class="d-block fw-semibold mb-1">No Refund Requests</span>
                        <span>There are no returns or refund requests at this time.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if($orders->hasPages())
    <div class="card-premium-body border-top pt-3 pb-1 px-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
