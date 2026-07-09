@extends('admin.layout.app')

@section('title', 'Admin Dashboard - ZestShop')

@section('content')
    <!-- Dashboard Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Dashboard Overview</h1>
            <p class="text-muted mb-0">Welcome back, Sophea! Here is what is happening with your store today.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <button class="btn btn-dark btn-sm rounded-pill px-3 py-2 me-2">
                <i class="ti ti-download me-1"></i> Export Report
            </button>
            <a href="{{ url('/') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 py-2" style="color: var(--primary); border-color: var(--primary);">
                <i class="ti ti-arrow-up-right me-1"></i> View Shop
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="row g-4 mb-4">
        @foreach($stats as $stat)
            <div class="col-sm-6 col-xl-3">
                <div class="card-premium h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fw-semibold" style="font-size: 13.5px;">{{ $stat['title'] }}</span>
                        <div class="p-2 rounded-3" style="background-color: var(--primary-pale); color: var(--primary);">
                            <i class="{{ $stat['icon'] }} fs-4"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        <span class="fs-2 fw-bold text-dark">{{ $stat['value'] }}</span>
                        <span class="badge-premium {{ $stat['trend'] === 'up' ? 'badge-premium-success' : 'badge-premium-danger' }}" style="font-size: 11px;">
                            <i class="ti {{ $stat['trend'] === 'up' ? 'ti-trending-up' : 'ti-trending-down' }}"></i>
                            {{ $stat['change'] }}
                        </span>
                    </div>
                    <span class="text-muted" style="font-size: 11.5px;">{{ $stat['desc'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Recent Orders & Activity Feed -->
    <div class="row g-4 mb-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card-premium h-100">
                <div class="card-premium-header">
                    <div>
                        <h2 class="card-premium-title">Recent Orders</h2>
                        <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">Review latest customer purchases</p>
                    </div>
                    <a href="{{ url('/admin/products') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold p-0" style="color: var(--primary);">
                        View All Orders <i class="ti ti-chevron-right align-middle"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $order['id'] }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $order['customer'] }}</span>
                                            <span class="text-muted" style="font-size: 11.5px;">{{ $order['email'] }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $order['product'] }}</td>
                                    <td class="fw-bold text-dark">{{ $order['amount'] }}</td>
                                    <td>
                                        @if($order['status'] === 'delivered')
                                            <span class="badge-premium badge-premium-success">
                                                <i class="ti ti-circle-check-filled"></i> Delivered
                                            </span>
                                        @elseif(in_array($order['status'], ['processed', 'shipped', 'enroute', 'arrived']))
                                            <span class="badge-premium badge-premium-info">
                                                <i class="ti ti-loader"></i> {{ ucfirst($order['status']) }}
                                            </span>
                                        @elseif($order['status'] === 'pending')
                                            <span class="badge-premium badge-premium-warning">
                                                <i class="ti ti-hourglass-empty"></i> Pending
                                            </span>
                                        @elseif($order['status'] === 'refund_requested')
                                            <span class="badge-premium badge-premium-warning" style="background-color: #fef3c7 !important; color: #d97706 !important;">
                                                <i class="ti ti-refresh-alert"></i> Return Requested
                                            </span>
                                        @elseif($order['status'] === 'refunded')
                                            <span class="badge-premium badge-premium-success" style="background-color: #d1fae5 !important; color: #065f46 !important;">
                                                <i class="ti ti-refresh"></i> Refunded
                                            </span>
                                        @else
                                            <span class="badge-premium badge-premium-danger">
                                                <i class="ti ti-circle-x-filled"></i> Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted" style="font-size: 12px;">{{ $order['date'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity Timeline -->
        <div class="col-lg-4">
            <div class="card-premium h-100">
                <div class="card-premium-header">
                    <div>
                        <h2 class="card-premium-title">System Activity</h2>
                        <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">Real-time event log</p>
                    </div>
                </div>
                <div class="card-premium-body">
                    <div class="timeline">
                        @foreach($activities as $activity)
                            <div class="d-flex gap-3 mb-4 last-no-margin">
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 38px; height: 38px; 
                                                @if($activity['color'] === 'orange')
                                                    background-color: var(--primary-pale); color: var(--primary);
                                                @elseif($activity['color'] === 'blue')
                                                    background-color: #E0F2FE; color: #0284C7;
                                                @elseif($activity['color'] === 'red')
                                                    background-color: #FDE8E8; color: #DC2626;
                                                @else
                                                    background-color: #DEF7EC; color: #16A34A;
                                                @endif">
                                        <i class="{{ $activity['icon'] }} fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <p class="text-dark fw-semibold mb-1" style="font-size: 13.5px; line-height: 1.4;">
                                        {{ $activity['message'] }}
                                    </p>
                                    <span class="text-muted" style="font-size: 11.5px;">{{ $activity['time'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Products & Action Cards -->
    <div class="row g-4">
        <!-- Popular Products -->
        <div class="col-md-12">
            <div class="card-premium">
                <div class="card-premium-header">
                    <div>
                        <h2 class="card-premium-title">High-Selling Products</h2>
                        <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">Top items sorted by sales volume</p>
                    </div>
                    <a href="{{ url('/admin/products') }}" class="btn btn-sm btn-link text-decoration-none fw-semibold p-0" style="color: var(--primary);">
                        Manage Products <i class="ti ti-chevron-right align-middle"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Sales Volume</th>
                                <th>Avg Rating</th>
                                <th>Stock Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popularProducts as $product)
                                <tr>
                                    <td>#PROD-00{{ $product['id'] }}</td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $product['title'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 11.5px;">
                                            {{ $product['category'] }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold text-dark">{{ $product['price'] }}</td>
                                    <td class="fw-bold">{{ $product['sales'] }} sales</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1 text-warning">
                                            <i class="ti ti-star-filled" style="color: #FBBF24;"></i>
                                            <span class="text-dark fw-semibold" style="font-size: 13px;">{{ $product['rating'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product['stock'] === 0)
                                            <span class="badge-premium badge-premium-danger">Out of stock</span>
                                        @elseif($product['stock'] <= 5)
                                            <span class="badge-premium badge-premium-warning">Low Stock ({{ $product['stock'] }})</span>
                                        @else
                                            <span class="badge-premium badge-premium-success">In Stock ({{ $product['stock'] }})</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline .last-no-margin:last-child {
            margin-bottom: 0 !important;
        }
    </style>
@endsection
