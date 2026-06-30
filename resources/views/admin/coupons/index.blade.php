@extends('admin.layout.app')
@section('title', 'Coupon Codes - ZestShop')
@section('content')

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Coupon Codes</h1>
        <p class="text-muted mb-0">Create and manage discount coupon codes for your customers.</p>
    </div>
    <div class="col-auto">
        <a href="{{ url('/admin/coupons/create') }}" class="btn rounded-pill px-4 text-white fw-semibold" style="background:var(--primary);">
            <i class="ti ti-plus me-1"></i> New Coupon
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 d-flex align-items-center" style="background:#DEF7EC;color:#03543F;">
    <i class="ti ti-circle-check-filled me-2 fs-4" style="color:#0E9F6E;"></i>
    <div class="fw-semibold">{{ session('success') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card-premium">
    <div class="card-premium-header flex-column flex-sm-row gap-3">
        <div>
            <h2 class="card-premium-title">All Coupons</h2>
            <p class="text-muted mb-0" style="font-size:12px;">{{ $coupons->total() }} coupons found</p>
        </div>
        <form action="{{ url('/admin/coupons') }}" method="GET" class="d-flex gap-2 ms-sm-auto">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3" placeholder="Search code..." style="font-size:13.5px;min-width:180px;">
            <select name="status" onchange="this.form.submit()" class="form-select rounded-pill px-3" style="width:140px;font-size:13.5px;">
                <option value="">All Status</option>
                <option value="active"   {{ request('status')==='active'   ? 'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}>Inactive</option>
            </select>
            @if(request()->anyFilled(['search','status']))
            <a href="{{ url('/admin/coupons') }}" class="btn btn-outline-secondary rounded-pill px-3"><i class="ti ti-refresh"></i></a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-premium align-middle">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type & Value</th>
                    <th>Min Order</th>
                    <th>Usage</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <div class="fw-bold" style="font-family:monospace;font-size:15px;letter-spacing:1px;color:var(--primary);">{{ $coupon->code }}</div>
                        @if($coupon->description)<small class="text-muted">{{ $coupon->description }}</small>@endif
                    </td>
                    <td>
                        @if($coupon->type === 'percent')
                            <span class="badge rounded-pill px-3 py-1 fw-bold" style="background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;font-size:12px;">
                                {{ $coupon->value }}% OFF
                            </span>
                            @if($coupon->max_discount)
                                <div class="text-muted mt-1" style="font-size:11px;">Max: ${{ number_format($coupon->max_discount,2) }}</div>
                            @endif
                        @else
                            <span class="badge rounded-pill px-3 py-1 fw-bold" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;font-size:12px;">
                                ${{ number_format($coupon->value,2) }} OFF
                            </span>
                        @endif
                    </td>
                    <td class="text-muted">${{ number_format($coupon->min_order,2) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold">{{ $coupon->used_count }}</span>
                            <span class="text-muted">/</span>
                            <span class="text-muted">{{ $coupon->usage_limit ?? '∞' }}</span>
                        </div>
                        @if($coupon->usage_limit)
                        <div class="progress mt-1" style="height:4px;width:70px;">
                            <div class="progress-bar" style="width:{{ min(100, ($coupon->used_count/$coupon->usage_limit)*100) }}%;background:var(--primary);"></div>
                        </div>
                        @endif
                    </td>
                    <td>
                        @if($coupon->starts_at || $coupon->expires_at)
                            <div style="font-size:12px;">
                                @if($coupon->starts_at)<div class="text-muted">From: {{ $coupon->starts_at->format('d M Y') }}</div>@endif
                                @if($coupon->expires_at)
                                    <div class="{{ $coupon->expires_at->isPast() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                        Exp: {{ $coupon->expires_at->format('d M Y') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            <span class="text-muted" style="font-size:12px;">No limit</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $isActive = $coupon->status === 'active'
                                && (!$coupon->expires_at || !$coupon->expires_at->isPast())
                                && (!$coupon->usage_limit || $coupon->used_count < $coupon->usage_limit);
                        @endphp
                        @if($isActive)
                            <span class="badge rounded-pill" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;font-size:11px;">● Active</span>
                        @else
                            <span class="badge rounded-pill" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;font-size:11px;">● Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ url('/admin/coupons/'.$coupon->id.'/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" title="Edit">
                            <i class="ti ti-pencil fs-5"></i>
                        </a>
                        <form action="{{ url('/admin/coupons/'.$coupon->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete coupon {{ $coupon->code }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="ti ti-ticket fs-1 d-block mb-2 opacity-50"></i>
                        No coupons yet. <a href="{{ url('/admin/coupons/create') }}">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
    <div class="card-premium-body border-top pt-3">
        {{ $coupons->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
