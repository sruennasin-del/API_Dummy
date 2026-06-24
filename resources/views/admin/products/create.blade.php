@extends('admin.layout.app')

@section('title', 'Add Product - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Add New Product</h1>
            <p class="text-muted mb-0">Create a new product in the store inventory.</p>
        </div>
        <div class="col-auto">
            <a href="{{ url('/admin/products') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 14px; font-weight: 600;">
                <i class="ti ti-arrow-narrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Error Validation Alert -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 p-3" role="alert" style="background-color: #FDE8E8; color: #9B1C1C;">
            <div class="d-flex align-items-center mb-2">
                <i class="ti ti-alert-circle-filled me-2 fs-4" style="color: #F05252;"></i>
                <div class="fw-bold">Please check the form for errors:</div>
            </div>
            <ul class="mb-0 ps-4" style="font-size: 13.5px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(16%) sepia(85%) saturate(3195%) hue-rotate(345deg) brightness(85%) contrast(98%);"></button>
        </div>
    @endif

    <!-- Form Section -->
    <form action="{{ url('/admin/products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Basic Info & Variations -->
            <div class="col-lg-8">
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">General Information</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Product Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold text-dark fs-6">Product Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control rounded-3 py-2.5 @error('title') is-invalid @enderror" placeholder="e.g., iPhone 15 Pro Max, Smart Watch Series 9" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold text-dark fs-6">Slug (URL Keyword)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3" style="font-size: 13.5px; border-color: var(--border-color);">admin/products/</span>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control py-2.5 rounded-end-3 @error('slug') is-invalid @enderror" placeholder="e.g., iphone-15-pro (leave blank to auto-generate)" style="border-color: var(--border-color); font-size: 14.5px;">
                            </div>
                            <small class="text-muted d-block mt-1">URL-friendly slug. Lowercase letters, numbers, and hyphens only.</small>
                            @error('slug')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-2">
                            <label for="description" class="form-label fw-semibold text-dark fs-6">Description</label>
                            <textarea name="description" id="description" rows="6" class="form-control rounded-3 py-2.5 @error('description') is-invalid @enderror" placeholder="Enter product specifications, features, and details..." style="border-color: var(--border-color); font-size: 14.5px;">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Variations Card -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Product Variations</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Colors Select -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark fs-6 d-block">Available Colors <span class="text-muted" style="font-size:12px; font-weight: normal;">(Optional)</span></label>
                            <div class="row g-3">
                                @forelse($colors as $color)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-check p-2.5 rounded-3 border d-flex align-items-center gap-2" style="border-color: var(--border-color); background-color: var(--light-bg);">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="colors[]" value="{{ $color->id }}" id="color_{{ $color->id }}" {{ is_array(old('colors')) && in_array($color->id, old('colors')) ? 'checked' : '' }}>
                                            <div class="rounded-circle" style="width: 18px; height: 18px; background-color: {{ $color->code }}; border: 1px solid var(--border-color);"></div>
                                            <label class="form-check-label fw-semibold text-dark" for="color_{{ $color->id }}" style="font-size: 13.5px; cursor: pointer;">
                                                {{ $color->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted" style="font-size:13.5px; font-style: italic;">
                                        No active colors found. Click <a href="{{ url('/admin/colors/create') }}">here</a> to add colors.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Sizes Select -->
                        <div>
                            <label class="form-label fw-semibold text-dark fs-6 d-block">Available Sizes <span class="text-muted" style="font-size:12px; font-weight: normal;">(Optional)</span></label>
                            <div class="row g-3">
                                @forelse($sizes as $size)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="form-check p-2.5 rounded-3 border d-flex align-items-center gap-2" style="border-color: var(--border-color); background-color: var(--light-bg);">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size_{{ $size->id }}" {{ is_array(old('sizes')) && in_array($size->id, old('sizes')) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark" for="size_{{ $size->id }}" style="font-size: 13.5px; cursor: pointer;">
                                                {{ $size->name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted" style="font-size:13.5px; font-style: italic;">
                                        No active sizes found. Click <a href="{{ url('/admin/sizes/create') }}">here</a> to add sizes.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Classification & Settings -->
            <div class="col-lg-4">
                <!-- Pricing & Stock Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Pricing & Stock</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Price -->
                        <div class="mb-4">
                            <label for="price" class="form-label fw-semibold text-dark fs-6">Unit Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" class="form-control rounded-3 py-2.5 @error('price') is-invalid @enderror" placeholder="0.00" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Level -->
                        <div>
                            <label for="stock" class="form-label fw-semibold text-dark fs-6">Stock Level <span class="text-danger">*</span></label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" class="form-control rounded-3 py-2.5 @error('stock') is-invalid @enderror" placeholder="0" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Classification -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Classification</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-semibold text-dark fs-6">Category</label>
                            <select name="category_id" id="category_id" class="form-select rounded-3 py-2.5 @error('category_id') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label fw-semibold text-dark fs-6">Status</label>
                            <select name="status" id="status" class="form-select rounded-3 py-2.5 @error('status') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <small class="text-muted d-block mt-1">Inactive products will be hidden from customer storefronts.</small>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Product Image</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="image_file" class="form-label fw-semibold text-dark fs-6">Upload Image</label>
                            <input type="file" name="image_file" id="image_file" class="form-control rounded-3 py-2 @error('image_file') is-invalid @enderror" style="border-color: var(--border-color); font-size: 13.5px;">
                            <small class="text-muted d-block mt-1">PNG, JPG, JPEG only. Max size 2MB.</small>
                            @error('image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image URL -->
                        <div>
                            <label for="image" class="form-label fw-semibold text-dark fs-6">Or Image URL</label>
                            <input type="text" name="image" id="image" value="{{ old('image') }}" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror" placeholder="https://example.com/product.jpg" style="border-color: var(--border-color); font-size: 13.5px;">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Product Gallery Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Product Gallery <span class="text-muted" style="font-size:12px; font-weight: normal;">(3 Detail Images)</span></h2>
                    </div>
                    <div class="card-premium-body">
                        @for($i = 0; $i < 3; $i++)
                            <div class="mb-4 pb-3 border-bottom {{ $i == 2 ? 'border-bottom-0 pb-0 mb-0' : '' }}">
                                <h5 class="fw-bold text-dark mb-2" style="font-size: 13.5px;">Detail Image {{ $i + 1 }}</h5>
                                <div class="mb-2">
                                    <label for="detail_image_file_{{ $i }}" class="form-label text-muted mb-1" style="font-size: 12px; font-weight: 500;">Upload File</label>
                                    <input type="file" name="detail_image_file_{{ $i }}" id="detail_image_file_{{ $i }}" class="form-control rounded-3 py-1.5 @error('detail_image_file_' . $i) is-invalid @enderror" style="border-color: var(--border-color); font-size: 13px;">
                                    @error('detail_image_file_' . $i)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="detail_image_{{ $i }}" class="form-label text-muted mb-1" style="font-size: 12px; font-weight: 500;">Or Image URL</label>
                                    <input type="text" name="detail_image_{{ $i }}" id="detail_image_{{ $i }}" value="{{ old('detail_image_' . $i) }}" class="form-control rounded-3 py-1.5 @error('detail_image_' . $i) is-invalid @enderror" placeholder="https://example.com/detail.jpg" style="border-color: var(--border-color); font-size: 13px;">
                                    @error('detail_image_' . $i)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Submit Area -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning text-white rounded-pill py-2.5 fw-bold" style="background-color: var(--primary); border-color: var(--primary); font-size: 15px;">
                        <i class="ti ti-circle-check me-1"></i> Save Product
                    </button>
                    <a href="{{ url('/admin/products') }}" class="btn btn-light border rounded-pill py-2.5 fw-semibold text-dark" style="font-size: 15px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('js')
    <script>
        $(document).ready(function() {
            // Auto generate slug from title
            $('#title').on('input', function() {
                var title = $(this).val();
                var slug = title.toLowerCase()
                                .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
                                .replace(/[\s_]+/g, '-')      // Replace spaces/underscores with hyphens
                                .replace(/^-+|-+$/g, '');     // Trim leading/trailing hyphens
                $('#slug').val(slug);
            });
        });
    </script>
    @endpush
@endsection
