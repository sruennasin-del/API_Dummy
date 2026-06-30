@extends('admin.layout.app')
@section('title', 'Promo Banners - ZestShop')
@section('content')

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Promo Banners</h1>
        <p class="text-muted mb-0">Manage your homepage hero slider slides.</p>
    </div>
    <div class="col-auto">
        <a href="{{ url('/admin/banners/create') }}" class="btn rounded-pill px-4 text-white" style="background:var(--primary);">
            <i class="ti ti-plus me-1"></i> Add Banner
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
    <div class="table-responsive">
        <table class="table table-premium align-middle">
            <thead>
                <tr>
                    <th style="width:60px;">Order</th>
                    <th>Preview</th>
                    <th>Title / Tag</th>
                    <th>Buttons</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td class="text-muted fw-bold">#{{ $banner->sort_order }}</td>
                    <td>
                        @if($banner->image)
                            <img src="{{ Storage::url($banner->image) }}" alt="{{ $banner->title }}"
                                 style="width:100px;height:55px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                        @else
                            <div style="width:100px;height:55px;border-radius:8px;background:{{ $banner->bg_gradient ?? 'linear-gradient(130deg,#FF6B1A,#FFD6BB)' }};display:flex;align-items:center;justify-content:center;">
                                <i class="ti ti-photo text-white fs-4"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $banner->title }}</div>
                        @if($banner->subtitle)<div class="text-muted" style="font-size:12px;">{{ $banner->subtitle }}</div>@endif
                        @if($banner->tag)<span class="badge bg-warning-subtle text-warning mt-1" style="font-size:10px;">{{ $banner->tag }}</span>@endif
                    </td>
                    <td>
                        @if($banner->btn_primary_label)
                            <span class="badge bg-dark me-1" style="font-size:10px;">{{ $banner->btn_primary_label }}</span>
                        @endif
                        @if($banner->btn_secondary_label)
                            <span class="badge bg-secondary" style="font-size:10px;">{{ $banner->btn_secondary_label }}</span>
                        @endif
                    </td>
                    <td>
                        @if($banner->status === 'active')
                            <span class="badge rounded-pill" style="background:#f0fdf4;color:#15803d;font-size:11px;border:1px solid #bbf7d0;">● Active</span>
                        @else
                            <span class="badge rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:11px;border:1px solid #fecaca;">● Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ url('/admin/banners/'.$banner->id.'/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" title="Edit">
                            <i class="ti ti-pencil fs-5"></i>
                        </a>
                        <form action="{{ url('/admin/banners/'.$banner->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this banner?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="ti ti-photo fs-1 d-block mb-2 opacity-50"></i>
                        No banners yet. <a href="{{ url('/admin/banners/create') }}">Add one now →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($banners->hasPages())
        <div class="card-premium-body border-top pt-3">{{ $banners->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
