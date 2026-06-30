@extends('admin.layout.app')

@section('title', 'Add Category - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Add New Category</h1>
            <p class="text-muted mb-0">Create a new sub-category linked to a main category.</p>
        </div>
        <div class="col-auto">
            <a href="{{ url('/admin/categories') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 14px; font-weight: 600;">
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
    <form action="{{ url('/admin/categories') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Basic Info -->
            <div class="col-lg-8">
                <div class="card-premium">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">General Information</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-dark fs-6">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded-3 py-2.5 @error('name') is-invalid @enderror" placeholder="e.g., T-Shirts, Jeans, Dresses" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold text-dark fs-6">Slug (URL Keyword)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3" style="font-size: 13.5px; border-color: var(--border-color);">admin/categories/</span>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="form-control py-2.5 rounded-end-3 @error('slug') is-invalid @enderror" placeholder="e.g., t-shirts (leave blank to auto-generate)" style="border-color: var(--border-color); font-size: 14.5px;">
                            </div>
                            <small class="text-muted d-block mt-1">URL-friendly version of the name. Must contain lowercase letters, numbers, and hyphens only.</small>
                            @error('slug')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-2">
                            <label for="description" class="form-label fw-semibold text-dark fs-6">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control rounded-3 py-2.5 @error('description') is-invalid @enderror" placeholder="Describe the products in this category..." style="border-color: var(--border-color); font-size: 14.5px;">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Classification & Images -->
            <div class="col-lg-4">
                <!-- Classification Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Classification</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Parent Category (Main Category) -->
                        <div class="mb-4">
                            <label for="parent_id" class="form-label fw-semibold text-dark fs-6">Main Category <span class="text-danger">*</span></label>
                            <select name="parent_id" id="parent_id" class="form-select rounded-3 py-2.5 @error('parent_id') is-invalid @enderror" required style="border-color: var(--border-color); font-size: 14px;">
                                <option value="" disabled {{ old('parent_id') ? '' : 'selected' }}>-- Select Main Category --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">Assign to a top-level Main Category.</small>
                            @error('parent_id')
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
                            <small class="text-muted d-block mt-1">Inactive categories are hidden from client stores.</small>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Settings -->
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Category Media</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="image_file" class="form-label fw-semibold text-dark fs-6">Upload Image</label>
                            <input type="file" name="image_file" id="image_file" class="form-control rounded-3 py-2 @error('image_file') is-invalid @enderror" style="border-color: var(--border-color); font-size: 13.5px;">
                            <small class="text-muted d-block mt-1">PNG, JPG, JPEG files only. Max size 2MB.</small>
                            @error('image_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image URL alternative -->
                        <div>
                            <label for="image" class="form-label fw-semibold text-dark fs-6">Or Image URL</label>
                            <input type="text" name="image" id="image" value="{{ old('image') }}" class="form-control rounded-3 py-2 @error('image') is-invalid @enderror" placeholder="https://example.com/image.jpg" style="border-color: var(--border-color); font-size: 13.5px;">
                            <small class="text-muted d-block mt-1">Direct link to an external image asset.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button Area -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning text-white rounded-pill py-2.5 fw-bold" style="background-color: var(--primary); border-color: var(--primary); font-size: 15px;">
                        <i class="ti ti-circle-check me-1"></i> Save Category
                    </button>
                    <a href="{{ url('/admin/categories') }}" class="btn btn-light border rounded-pill py-2.5 fw-semibold text-dark" style="font-size: 15px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('js')
    <script>
        $(document).ready(function() {
            // Auto generate slug from category name
            $('#name').on('input', function() {
                var name = $(this).val();
                var slug = name.toLowerCase()
                               .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
                               .replace(/[\s_]+/g, '-')      // Replace spaces/underscores with hyphens
                               .replace(/^-+|-+$/g, '');     // Trim leading/trailing hyphens
                $('#slug').val(slug);
            });
        });
    </script>
    @endpush
@endsection
