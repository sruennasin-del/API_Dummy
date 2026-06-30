@extends('admin.layout.app')

@section('title', 'Manage Colors - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Product Colors</h1>
            <p class="text-muted mb-0">Manage the color palette available for product variations.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ url('/admin/colors/create') }}" class="btn btn-warning text-white rounded-pill px-4 py-2" style="background-color: var(--primary); border-color: var(--primary);">
                <i class="ti ti-plus me-1"></i> Add Color
            </a>
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

    <!-- Colors Card -->
    <div class="card-premium">
        <!-- Table Search Toolbar -->
        <div class="card-premium-header flex-column flex-sm-row gap-3">
            <div>
                <h2 class="card-premium-title">All Colors</h2>
                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $colors->total() }} colors defined</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
                <form action="{{ url('/admin/colors') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto">
                    <div class="position-relative w-100 w-sm-auto" style="min-width: 200px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search colors..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
                        <i class="ti ti-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="form-select rounded-pill px-3" style="width: 140px; font-size: 13.5px; border-color: var(--border-color);">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ url('/admin/colors') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Clear Filters" style="font-size: 13px;">
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
                        <th style="width: 120px;">ID</th>
                        <th style="width: 100px;">Preview</th>
                        <th>Color Name</th>
                        <th>HEX Code</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($colors as $color)
                        <tr>
                            <td class="text-muted" style="font-size: 12.5px;">#COL-{{ sprintf('%03d', $color->id) }}</td>
                            <td>
                                <div class="rounded-circle shadow-sm" style="width: 32px; height: 32px; background-color: {{ $color->code }}; border: 2px solid #ffffff; box-shadow: 0 0 0 1px var(--border-color);"></div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark fs-6">{{ $color->name }}</span>
                            </td>
                            <td class="font-monospace text-muted" style="font-size: 12px; text-transform: uppercase;">{{ $color->code }}</td>
                            <td>
                                @if($color->status === 'active')
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
                                    <a href="{{ url('/admin/colors/' . $color->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" style="color: #64748B;" title="Edit Color">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ url('/admin/colors/' . $color->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this color? This action cannot be undone.');" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" style="color: #EF4444;" title="Delete Color">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="ti ti-palette fs-1 mb-2 d-block text-secondary opacity-50"></i>
                                <span class="d-block fw-semibold mb-1">No Colors Found</span>
                                <span>Try adjusting your search filters or add a new color.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($colors->hasPages())
            <div class="card-premium-body border-top pt-3 pb-1 px-4">
                {{ $colors->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
