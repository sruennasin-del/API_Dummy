@extends('admin.layout.app')

@section('title', 'Stock Inventory - ZestShop')

@section('content')
<!-- Header -->
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Stock Inventory Manager</h1>
        <p class="text-muted mb-0">Track and manage your catalog inventory levels, check low stock status, and adjust stock in bulk.</p>
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

<!-- Stock Stats Row -->
<div class="row g-3 mb-4">
    <!-- Total Products -->
    <div class="col-6 col-lg-2.4 col-xl-2.4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: var(--primary-light); color: var(--primary);">
                <i class="ti ti-box"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Catalog</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <!-- In Stock -->
    <div class="col-6 col-lg-2.4 col-xl-2.4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #DEF7EC; color: #0E9F6E;">
                <i class="ti ti-circle-check-filled"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">In Stock</div>
                <div class="stat-value">{{ $stats['in_stock'] }}</div>
            </div>
        </div>
    </div>
    <!-- Low Stock -->
    <div class="col-6 col-lg-2.4 col-xl-2.4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #FEF9C3; color: #D97706;">
                <i class="ti ti-alert-triangle-filled"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Low Stock</div>
                <div class="stat-value">{{ $stats['low_stock'] }}</div>
            </div>
        </div>
    </div>
    <!-- Out of Stock -->
    <div class="col-6 col-lg-2.4 col-xl-2.4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #FDE8E8; color: #EF4444;">
                <i class="ti ti-circle-x-filled"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Out of Stock</div>
                <div class="stat-value">{{ $stats['out_of_stock'] }}</div>
            </div>
        </div>
    </div>
    <!-- Disabled -->
    <div class="col-12 col-lg-2.4 col-xl-2.4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #F1F5F9; color: #64748B;">
                <i class="ti ti-eye-off"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Inactive</div>
                <div class="stat-value">{{ $stats['inactive'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card-premium">
    <!-- Table Search Toolbar -->
    <div class="card-premium-header flex-column flex-sm-row gap-3">
        <div>
            <h2 class="card-premium-title">Stock Inventory Levels</h2>
            <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $products->total() }} matching products listed</p>
        </div>

        <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
            <form action="{{ url('/admin/inventory') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto flex-wrap">
                <div class="position-relative w-100 w-sm-auto" style="min-width: 180px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search title or ID..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
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
                    <option value="">All Statuses</option>
                    <option value="In Stock" {{ request('status') === 'In Stock' ? 'selected' : '' }}>In Stock (>5)</option>
                    <option value="Low Stock" {{ request('status') === 'Low Stock' ? 'selected' : '' }}>Low Stock (1-5)</option>
                    <option value="Out of Stock" {{ request('status') === 'Out of Stock' ? 'selected' : '' }}>Out of Stock (0)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                @if(request()->anyFilled(['search', 'category_id', 'status']))
                <a href="{{ url('/admin/inventory') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Clear Filters" style="font-size: 13px;">
                    <i class="ti ti-refresh"></i>
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Bulk Action Form Wrapper -->
    <form id="bulkActionForm" action="{{ route('inventory.bulk-update') }}" method="POST">
        @csrf

        <!-- Collapsible Bulk Actions Panel -->
        <div id="bulkActionsPanel" class="collapse border-bottom bg-light p-3" style="background-color: var(--primary-pale) !important; border-color: var(--primary-border) !important;">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-white rounded-pill px-3 py-1.5 fs-7" id="checkedCountBadge">0 Selected</span>
                    <span class="text-muted fw-semibold" style="font-size: 13px;">Apply bulk changes to checked products.</span>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <select name="action_type" class="form-select rounded-pill px-3 py-1.5" style="width: 170px; font-size: 13px; border-color: var(--primary-border);" required>
                        <option value="" disabled selected>Select Action...</option>
                        <option value="set">Set stock level to</option>
                        <option value="increase">Increase stock by</option>
                        <option value="decrease">Decrease stock by</option>
                    </select>
                    <div style="width: 100px;">
                        <input type="number" name="value" class="form-control rounded-pill px-3 py-1.5 text-center" min="0" placeholder="Value" style="font-size: 13px; border-color: var(--primary-border);" required>
                    </div>
                    <button type="submit" class="btn btn-warning text-white rounded-pill px-4 py-1.5" style="background-color: var(--primary); border-color: var(--primary); font-size: 13px; font-weight: 600;">
                        Apply Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table table-premium align-middle">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">
                            <input type="checkbox" id="selectAllCheckbox" class="form-check-input shadow-none">
                        </th>
                        <th style="width: 100px;">Product ID</th>
                        <th style="width: 60px;">Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th style="min-width: 250px;">Stock Control (AJAX)</th>
                        <th>Status Badge</th>
                        <th style="width: 140px;">Last Adjusted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr id="product-row-{{ $product->id }}">
                        <td class="text-center">
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="form-check-input product-select-checkbox shadow-none">
                        </td>
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
                            <span class="fw-bold text-dark fs-6 d-block">{{ $product->title }}</span>
                            <span class="text-muted" style="font-size: 11px;">Slug: {{ $product->slug }}</span>
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
                            <div class="d-flex align-items-center">
                                <!-- Stock Adjustment Controls -->
                                <div class="input-group input-group-sm" style="width: 140px;">
                                    <button type="button" class="btn btn-outline-secondary btn-stock-adjust px-2.5" data-action="decrement" data-id="{{ $product->id }}">
                                        <i class="ti ti-minus" style="font-size: 11px;"></i>
                                    </button>
                                    <input type="number" class="form-control text-center input-stock-value fw-bold shadow-none"
                                        id="stock-input-{{ $product->id }}"
                                        value="{{ $product->stock }}"
                                        min="0"
                                        data-id="{{ $product->id }}"
                                        style="font-size: 13.5px; border-color: var(--border-color);">
                                    <button type="button" class="btn btn-outline-secondary btn-stock-adjust px-2.5" data-action="increment" data-id="{{ $product->id }}">
                                        <i class="ti ti-plus" style="font-size: 11px;"></i>
                                    </button>
                                </div>

                                <!-- Save Icon Button -->
                                <button type="button" class="btn btn-warning btn-stock-save text-white ms-2 rounded-circle p-1.5 d-flex align-items-center justify-content-center shadow-sm"
                                    data-id="{{ $product->id }}"
                                    title="Save stock value"
                                    style="width: 32px; height: 32px; background-color: var(--primary); border-color: var(--primary);">
                                    <i class="ti ti-device-floppy fs-6"></i>
                                </button>

                                <!-- Saved Success Checkmark/Spinner -->
                                <div class="stock-status-spinner ms-2" id="spinner-{{ $product->id }}" style="display: none;">
                                    <div class="spinner-border spinner-border-sm text-warning" role="status" style="width: 1rem; height: 1rem; border-width: 0.15em;"></div>
                                </div>
                                <div class="stock-status-success ms-2 text-success" id="success-{{ $product->id }}" style="display: none;">
                                    <i class="ti ti-circle-check-filled fs-5"></i>
                                </div>
                            </div>
                        </td>
                        <td id="status-badge-{{ $product->id }}">
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
                        <td class="text-muted" style="font-size: 12.5px;" id="updated-at-{{ $product->id }}">
                            {{ $product->updated_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="ti ti-archive fs-1 mb-2 d-block text-secondary opacity-50"></i>
                            <span class="d-block fw-semibold mb-1">No Inventory Found</span>
                            <span>Try adjusting your filters or search criteria.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <!-- Pagination Footer -->
    @if($products->hasPages())
    <div class="card-premium-body border-top pt-3 pb-1 px-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Sleek Toast Notification Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
    <div id="stockToast" class="toast border-0 shadow rounded-4" role="alert" aria-live="assertive" aria-atomic="true" style="display: none; background: #ffffff;">
        <div class="d-flex align-items-center p-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; background-color: #DEF7EC; color: #0E9F6E;" id="toastIconContainer">
                <i class="ti ti-circle-check-filled fs-5" id="toastIcon"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold text-dark fs-7" id="toastTitle">Success</h6>
                <p class="mb-0 text-muted fs-8" id="toastMessage" style="font-size: 12px;"></p>
            </div>
            <button type="button" class="btn-close ms-2" onclick="$('#stockToast').fadeOut(200)" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* Stats widgets styles */
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
        border-color: rgba(255, 107, 26, 0.2);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-info {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1;
    }

    /* Grid adjustments for stats */
    @media (min-width: 992px) {
        .col-lg-2\.4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }

    /* Input number styling to remove default arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Checkbox custom outline */
    .product-select-checkbox:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Setup CSRF headers for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Slide open or close bulk action drawer based on checked checkboxes
        function toggleBulkPanel() {
            var checkedCheckboxes = $('.product-select-checkbox:checked');
            var count = checkedCheckboxes.length;

            if (count > 0) {
                $('#checkedCountBadge').text(count + ' Selected');
                $('#bulkActionsPanel').slideDown(250);
            } else {
                $('#bulkActionsPanel').slideUp(200);
            }
        }

        // Checkbox select all event
        $('#selectAllCheckbox').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.product-select-checkbox').prop('checked', isChecked);
            toggleBulkPanel();
        });

        // Individual checkbox change event
        $(document).on('change', '.product-select-checkbox', function() {
            // If one checkbox is unchecked, selectAll should be unchecked
            if (!$(this).is(':checked')) {
                $('#selectAllCheckbox').prop('checked', false);
            } else {
                // Check if all are checked
                var total = $('.product-select-checkbox').length;
                var checkedCount = $('.product-select-checkbox:checked').length;
                if (total === checkedCount) {
                    $('#selectAllCheckbox').prop('checked', true);
                }
            }
            toggleBulkPanel();
        });

        // Increment/Decrement stock buttons
        $('.btn-stock-adjust').on('click', function() {
            var action = $(this).data('action');
            var productId = $(this).data('id');
            var $input = $('#stock-input-' + productId);
            var currentVal = parseInt($input.val()) || 0;

            if (action === 'increment') {
                $input.val(currentVal + 1);
            } else if (action === 'decrement') {
                $input.val(Math.max(0, currentVal - 1));
            }

            // Auto-save the adjusted value
            saveStock(productId, $input.val());
        });

        // Save when clicking Save button
        $('.btn-stock-save').on('click', function() {
            var productId = $(this).data('id');
            var stockVal = $('#stock-input-' + productId).val();
            saveStock(productId, stockVal);
        });

        // Save on enter keypress inside the input
        $('.input-stock-value').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                var productId = $(this).data('id');
                var stockVal = $(this).val();
                saveStock(productId, stockVal);
                $(this).blur();
            }
        });

        // Save on change (if manually typed and focus lost)
        $('.input-stock-value').on('change', function() {
            var productId = $(this).data('id');
            var stockVal = $(this).val();
            saveStock(productId, stockVal);
        });

        // Core AJAX function to save stock
        function saveStock(productId, stockValue) {
            var $spinner = $('#spinner-' + productId);
            var $success = $('#success-' + productId);

            $success.hide();
            $spinner.show();

            $.ajax({
                url: "{{ route('inventory.update-stock') }}",
                method: "POST",
                data: {
                    product_id: productId,
                    stock: stockValue
                },
                success: function(response) {
                    $spinner.hide();
                    if (response.success) {
                        // Update values
                        $('#stock-input-' + productId).val(response.stock);
                        $('#status-badge-' + productId).html(response.badge);
                        $('#updated-at-' + productId).text(response.updated_at);

                        // Success check animation
                        $success.fadeIn(150).delay(1000).fadeOut(150);

                        // Show toast notification
                        showToast('Stock Updated', 'Stock for Product #' + productId + ' updated to ' + response.stock + '.', 'success');
                    } else {
                        showToast('Error', 'Failed to update stock. Please try again.', 'error');
                    }
                },
                error: function(xhr) {
                    $spinner.hide();
                    var errorMsg = 'Failed to update stock.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast('Error', errorMsg, 'error');
                }
            });
        }

        // Function to display premium toast notifications
        function showToast(title, message, type) {
            var $toast = $('#stockToast');
            var $iconContainer = $('#toastIconContainer');
            var $icon = $('#toastIcon');

            $('#toastTitle').text(title);
            $('#toastMessage').text(message);

            if (type === 'success') {
                $iconContainer.css({
                    'background-color': '#DEF7EC',
                    'color': '#0E9F6E'
                });
                $icon.attr('class', 'ti ti-circle-check-filled fs-5');
            } else {
                $iconContainer.css({
                    'background-color': '#FDE8E8',
                    'color': '#EF4444'
                });
                $icon.attr('class', 'ti ti-circle-x-filled fs-5');
            }

            $toast.fadeIn(250).delay(3500).fadeOut(250);
        }
    });
</script>
@endpush