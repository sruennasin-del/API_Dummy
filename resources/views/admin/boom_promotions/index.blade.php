@extends('admin.layout.app')
@section('title', 'Boom Promotions - ZestShop')
@section('content')

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 fw-bold mb-1" style="font-family:'Syne',sans-serif;">Boom Promotion</h1>
        <p class="text-muted mb-0">Manage overlay ads and absolute-positioned notifications across the website.</p>
    </div>
    <div class="col-auto">
        <a href="{{ url('/admin/boom-promotions/create') }}" class="btn rounded-pill px-4 text-white" style="background:var(--primary);">
            <i class="ti ti-plus me-1"></i> Add Promotion
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
                    <th style="width:120px;">Image</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Shape</th>
                    <th>Link URL</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promo)
                <tr>
                    <td>
                        @if($promo->image)
                            <img src="{{ Storage::url($promo->image) }}" alt="{{ $promo->title }}"
                                 style="width:90px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                        @else
                            <div style="width:90px;height:60px;border-radius:8px;background:var(--orange-pale);display:flex;align-items:center;justify-content:center;border:1px solid var(--orange-border);">
                                <i class="ti ti-speakerphone fs-3" style="color:var(--orange) !important;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $promo->title }}</div>
                    </td>
                    <td>
                        <span class="text-muted">{{ $promo->subtitle ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark text-capitalize border" style="font-size: 11px;">
                            {{ $promo->shape ?? 'starburst' }}
                        </span>
                    </td>
                    <td>
                        <code class="text-secondary">{{ $promo->link_url ?? '-' }}</code>
                    </td>
                    <td>
                        @if($promo->status === 'active')
                            <span class="badge rounded-pill" style="background:#f0fdf4;color:#15803d;font-size:11px;border:1px solid #bbf7d0;">● Active</span>
                        @else
                            <span class="badge rounded-pill" style="background:#fef2f2;color:#dc2626;font-size:11px;border:1px solid #fecaca;">● Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ url('/admin/boom-promotions/'.$promo->id.'/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" title="Edit">
                            <i class="ti ti-pencil fs-5"></i>
                        </a>
                        <form action="{{ url('/admin/boom-promotions/'.$promo->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this promotion?');">
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
                        <i class="ti ti-speakerphone fs-1 d-block mb-2 opacity-50" style="color:var(--orange);"></i>
                        No promotions yet. <a href="{{ url('/admin/boom-promotions/create') }}">Add one now →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($promotions->hasPages())
        <div class="card-premium-body border-top pt-3">{{ $promotions->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
