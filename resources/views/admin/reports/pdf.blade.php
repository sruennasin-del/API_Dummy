<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report - {{ $date }} - ZestShop</title>
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --orange: #ff7a00;
            --orange-light: #fff5eb;
            --dark: #1e293b;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            color: #334155;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .report-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }
        .report-header {
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 24px;
            margin-bottom: 30px;
        }
        .brand-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 28px;
            color: var(--orange);
            text-decoration: none;
        }
        .brand-logo span {
            color: var(--dark);
        }
        .report-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .stat-box {
            background-color: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }
        .stat-box:hover {
            border-color: var(--orange);
            box-shadow: 0 4px 15px rgba(255, 122, 0, 0.05);
        }
        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 22px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.1;
        }
        .table-report th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        .table-report td {
            font-size: 13.5px;
            padding: 14px 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .item-row {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 4px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .report-card {
                border: none;
                box-shadow: none;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
        @media (max-width: 768px) {
            body {
                padding: 15px 0;
            }
            .report-card {
                padding: 20px;
            }
            .report-stats-grid {
                gap: 10px;
            }
            .stat-box {
                padding: 12px 10px;
                gap: 8px;
                flex-direction: column;
                text-align: center;
            }
            .stat-icon-wrapper {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }
            .stat-value {
                font-size: 16px;
            }
            .stat-label {
                font-size: 9px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print" style="max-width: 1000px; margin: auto;">
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="ti ti-arrow-left me-1"></i> Back to Reports
        </a>
        <button onclick="window.print()" class="btn btn-warning rounded-pill px-4 text-white fw-bold" style="background-color: var(--orange); border-color: var(--orange);">
            <i class="ti ti-printer me-1"></i> Print / Save PDF
        </button>
    </div>

    <div class="report-card">
        <div class="report-header d-flex justify-content-between align-items-end">
            <div>
                <div class="brand-logo mb-2">Zest<span>Shop</span></div>
                <h1 class="h4 fw-bold m-0" style="font-family:'Syne',sans-serif; color: var(--dark);">
                    {{ $date === 'All-Time' ? 'Overall Sales & Orders Report' : 'Daily Sales & Orders Report' }}
                </h1>
                <span class="text-muted" style="font-size: 13px;">
                    {{ $date === 'All-Time' ? 'Cumulative All-Time Report' : 'Date: ' . \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                </span>
            </div>
            <div class="text-end">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold fs-7">SYSTEM COMPILING: OK</span>
            </div>
        </div>

        <div class="report-stats-grid mb-5">
            <div class="stat-box">
                <div class="stat-icon-wrapper text-success bg-success bg-opacity-10">
                    <i class="ti ti-currency-dollar"></i>
                </div>
                <div>
                    <div class="stat-label">TOTAL REVENUE</div>
                    <div class="stat-value text-success">${{ number_format($totalSales, 2) }}</div>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-icon-wrapper text-warning bg-warning bg-opacity-10">
                    <i class="ti ti-shopping-cart"></i>
                </div>
                <div>
                    <div class="stat-label">TOTAL ORDERS</div>
                    <div class="stat-value text-warning">{{ $totalOrders }}</div>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-icon-wrapper text-primary bg-primary bg-opacity-10">
                    <i class="ti ti-box"></i>
                </div>
                <div>
                    <div class="stat-label">TOTAL ITEMS SOLD</div>
                    <div class="stat-value text-primary">{{ $totalItems }}</div>
                </div>
            </div>
        </div>

        <h3 class="h5 fw-bold mb-3" style="color: var(--dark); font-family:'Syne',sans-serif;">Order Details Breakdown</h3>
        <div class="table-responsive">
            <table class="table table-report align-middle">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Customer Details</th>
                        <th>Payment Method</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td style="font-weight: 600; color: var(--dark); font-size: 12px; white-space: nowrap;">#{{ $order->order_number }}</td>
                        <td>
                            <div class="fw-bold">{{ $order->customer_name }}</div>
                            <div class="text-muted" style="font-size: 11px;">{{ $order->customer_phone }} | {{ $order->customer_email }}</div>
                            <div class="text-muted" style="font-size: 11px; max-width: 250px;" class="text-truncate">{{ $order->customer_address }}</div>
                        </td>
                        <td>
                            @if(strtolower($order->payment_method) === 'cash' || strtolower($order->payment_method) === 'cod')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size:11px;">
                                    <i class="ti ti-truck me-1"></i> Cash on Delivery
                                </span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1" style="font-size:11px;">
                                    <i class="ti ti-building-bank me-1"></i> Bank Transfer ({{ strtoupper($order->payment_method) }})
                                </span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <span class="badge rounded-pill px-2 py-1 text-uppercase fw-semibold" style="font-size: 10px; 
                                @if($order->status === 'Completed') background:#def7ec; color:#03543f;
                                @elseif($order->status === 'Pending') background:#fef3c7; color:#92400e;
                                @else background:#f1f5f9; color:#475569; @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="py-2 px-4 bg-light bg-opacity-25" style="border-bottom: 2px solid #f1f5f9;">
                            <div class="text-muted fw-bold mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">Purchased Items:</div>
                            <div class="row g-2">
                                @foreach($order->items as $item)
                                <div class="col-md-6 d-flex align-items-center gap-2">
                                    <img src="{{ $item->product_thumbnail ?? ($item->product ? $item->product->image : 'https://via.placeholder.com/50x50') }}" 
                                         alt="{{ $item->product_title }}" 
                                         style="width: 32px; height: 32px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 11.5px;">{{ $item->product_title }}</div>
                                        <div class="text-muted" style="font-size: 10.5px;">Qty: {{ $item->qty }} &times; ${{ number_format($item->price, 2) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No orders matching this date.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5 pt-4 text-center border-top text-muted" style="font-size: 11.5px;">
            Daily Report Generated automatically on {{ now()->format('d M Y, h:i A') }} for ZestShop Admin. All values verified in system ledger.
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
