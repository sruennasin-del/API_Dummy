@extends('admin.layout.app')

@section('title', 'Manage Collections - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Product Collections</h1>
            <p class="text-muted mb-0">Manage special groupings like New Arrivals, Summer Promos, etc.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ url('/admin/collections/create') }}" class="btn btn-warning text-white rounded-pill px-4 py-2" style="background-color: var(--primary); border-color: var(--primary);">
                <i class="ti ti-plus me-1"></i> Add Collection
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3 d-flex align-items-center" role="alert" style="background-color: #DEF7EC; color: #03543F;">
            <i class="ti ti-circle-check-filled me-2 fs-4" style="color: #0E9F6E;"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Collections Card -->
    <div class="card-premium">
        <!-- Table Search Toolbar -->
        <div class="card-premium-header flex-column flex-sm-row gap-3">
            <div>
                <h2 class="card-premium-title">All Collections</h2>
                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $collections->total() }} collections defined</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
                <form action="{{ url('/admin/collections') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto">
                    <div class="position-relative w-100 w-sm-auto" style="min-width: 200px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search collections..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
                        <i class="ti ti-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="form-select rounded-pill px-3" style="width: 140px; font-size: 13.5px; border-color: var(--border-color);">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table table-premium">
                <thead>
                    <tr>
                        <th style="width: 120px;">ID</th>
                        <th>Collection Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                        <tr>
                            <td class="text-muted" style="font-size: 12.5px;">#COL-{{ sprintf('%03d', $collection->id) }}</td>
                            <td>
                                <span class="fw-bold text-dark fs-6">{{ $collection->name }}</span>
                                @if($collection->description)
                                    <p class="text-muted mb-0 mt-1" style="font-size: 11px;">{{ \Illuminate\Support\Str::limit($collection->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="text-muted font-monospace" style="font-size: 12px;">{{ $collection->slug }}</td>
                            <td>
                                @if($collection->status === 'active')
                                    <span class="badge-premium badge-premium-success">
                                        <i class="ti ti-circle-check-filled"></i> Active
                                    </span>
                                @else
                                    <span class="badge-premium badge-premium-danger">
                                        <i class="ti ti-circle-x-filled"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ url('/admin/collections/' . $collection->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" style="color: #64748B;" title="Edit Collection">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ url('/admin/collections/' . $collection->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this collection? This action cannot be undone.');" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" style="color: #EF4444;" title="Delete Collection">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="ti ti-tags fs-1 mb-2 d-block text-secondary opacity-50"></i>
                                <span class="d-block fw-semibold mb-1">No Collections Found</span>
                                <span>Add a new collection to start grouping your products.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($collections->hasPages())
            <div class="card-premium-body border-top pt-3 pb-1 px-4">
                {{ $collections->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
