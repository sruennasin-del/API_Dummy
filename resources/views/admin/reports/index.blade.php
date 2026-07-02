@extends('admin.layout.app')
@section('title', 'Sales Reports - ZestShop')
@section('content')

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Sales Reports</h1>
        <p class="text-muted mb-0">Track and monitor your daily store operations, order volumes, items sold, and generated sales revenue.</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('admin.reports.all') }}" target="_blank" class="btn rounded-pill px-4 text-white fw-semibold" style="background:var(--primary);">
            <i class="ti ti-file-analytics me-1"></i> All-Time Sales Report
        </a>
    </div>
</div>

<div class="card-premium">
    <div class="card-premium-header flex-column flex-sm-row gap-3">
        <div>
            <h2 class="card-premium-title">Daily Sales Summary</h2>
            <p class="text-muted mb-0" style="font-size:12px;">{{ $rawReports->total() }} reporting days found</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-premium align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total Orders</th>
                    <th>Total Items Sold</th>
                    <th>Total Revenue</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rawReports as $report)
                @php
                    $itemsCount = $itemsSold->get($report->date, 0);
                @endphp
                <tr>
                    <td>
                        <div class="fw-bold" style="font-size:15px; color:var(--dark);">
                            {{ \Carbon\Carbon::parse($report->date)->format('l, d M Y') }}
                        </div>
                        <small class="text-muted">{{ $report->date }}</small>
                    </td>
                    <td>
                        <span class="badge rounded-pill px-3 py-1 fw-bold" style="background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;font-size:12px;">
                            {{ $report->total_orders }} {{ Str::plural('Order', $report->total_orders) }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-semibold text-muted">
                            {{ $itemsCount }} {{ Str::plural('Item', $itemsCount) }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-success" style="font-size: 15px;">
                            ${{ number_format($report->total_sales, 2) }}
                        </div>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.reports.pdf', $report->date) }}" target="_blank" class="btn btn-sm btn-outline-orange rounded-pill px-3 fw-semibold">
                            <i class="ti ti-file-export me-1"></i> Export PDF/Print
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        No orders recorded in the system yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rawReports->hasPages())
    <div class="p-3 border-top">
        {{ $rawReports->links() }}
    </div>
    @endif
</div>
@endsection
