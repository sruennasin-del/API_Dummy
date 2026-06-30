@extends('admin.layout.app')

@section('title', 'Manage Products - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Product Inventory</h1>
            <p class="text-muted mb-0">Monitor catalog items, pricing, inventory stock status, and ratings.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ url('/admin/products/create') }}" class="btn btn-warning text-white rounded-pill px-4 py-2" style="background-color: var(--primary); border-color: var(--primary);">
                <i class="ti ti-plus me-1"></i> Add Product
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

    <!-- Inventory Table Card -->
    <div class="card-premium">
        <!-- Table Search Toolbar -->
        <div class="card-premium-header flex-column flex-sm-row gap-3">
            <div>
                <h2 class="card-premium-title">Catalog Inventory</h2>
                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $products->total() }} items defined</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
                <form action="{{ url('/admin/products') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto flex-wrap">
                    <div class="position-relative w-100 w-sm-auto" style="min-width: 180px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search products..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
                        <i class="ti ti-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                    </div>

                    <select name="category_id" onchange="this.form.submit()" class="form-select rounded-pill px-3" style="width: 140px; font-size: 13.5px; border-color: var(--border-color);">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="form-select rounded-pill px-3" style="width: 140px; font-size: 13.5px; border-color: var(--border-color);">
                        <option value="">Stock Status</option>
                        <option value="In Stock" {{ request('status') === 'In Stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="Low Stock" {{ request('status') === 'Low Stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="Out of Stock" {{ request('status') === 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Disabled (Inactive)</option>
                    </select>

                    @if(request()->anyFilled(['search', 'category_id', 'status']))
                        <a href="{{ url('/admin/products') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Clear Filters" style="font-size: 13px;">
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
                        <th style="width: 100px;">Product ID</th>
                        <th style="width: 60px;">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Level</th>
                        <th>Rating</th>
                        <th>Total Sales</th>
                        <th class="text-end" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="text-muted" style="font-size: 12.5px;">#PROD-{{ sprintf('%03d', $product->id) }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->title }}" class="rounded-3" style="width: 36px; height: 36px; object-fit: cover; border: 1px solid var(--border-color);">
                                @else
                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 36px; height: 36px; background-color: var(--light-bg); border: 1px dashed var(--border-color); font-size: 12px;">
                                        {{ strtoupper(substr($product->title, 0, 2)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark fs-6">{{ $product->title }}</span>
                            </td>
                            <td>
                                @if($product->category)
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 500;">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size: 12px; font-style: italic;">Uncategorized</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->status === 'inactive')
                                    <span class="badge-premium badge-premium-danger">
                                        <i class="ti ti-circle-x-filled"></i> Inactive (Disabled)
                                    </span>
                                @elseif($product->stock === 0)
                                    <span class="badge-premium badge-premium-danger">
                                        <i class="ti ti-circle-x-filled"></i> Out of Stock
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span class="badge-premium badge-premium-warning">
                                        <i class="ti ti-alert-triangle-filled"></i> Low Stock ({{ $product->stock }})
                                    </span>
                                @else
                                    <span class="badge-premium badge-premium-success">
                                        <i class="ti ti-circle-check-filled"></i> In Stock ({{ $product->stock }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <i class="ti ti-star-filled text-warning" style="color: #FBBF24 !important;"></i>
                                    <span class="text-dark fw-semibold" style="font-size: 13.5px;">{{ number_format($product->rating, 2) }}</span>
                                </div>
                            </td>
                            <td class="fw-semibold text-dark">{{ $product->sales }} sales</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ url('/admin/products/' . $product->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" style="color: #64748B;" title="Edit Product">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </a>
                                    
                                    <form action="{{ url('/admin/products/' . $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" style="color: #EF4444;" title="Delete Product">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="ti ti-box fs-1 mb-2 d-block text-secondary opacity-50"></i>
                                <span class="d-block fw-semibold mb-1">No Products Found</span>
                                <span>Try adjusting your search filters or add a new product.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($products->hasPages())
            <div class="card-premium-body border-top pt-3 pb-1 px-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
