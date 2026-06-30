@extends('admin.layout.app')

@section('title', 'Edit Collection - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Edit Collection</h1>
            <p class="text-muted mb-0">Update collection details.</p>
        </div>
        <div class="col-auto">
            <a href="{{ url('/admin/collections') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 14px; font-weight: 600;">
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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Section -->
    <div class="row">
        <div class="col-lg-8 col-xl-6">
            <div class="card-premium">
                <div class="card-premium-header">
                    <h2 class="card-premium-title">Collection Details</h2>
                </div>
                <div class="card-premium-body">
                    <form action="{{ url('/admin/collections/' . $collection->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-dark fs-6">Collection Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $collection->name) }}" class="form-control rounded-3 py-2.5 @error('name') is-invalid @enderror" placeholder="e.g., New Arrivals" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold text-dark fs-6">Slug (URL Keyword)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3" style="font-size: 13.5px; border-color: var(--border-color);">admin/collections/</span>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $collection->slug) }}" class="form-control py-2.5 rounded-end-3 @error('slug') is-invalid @enderror" placeholder="e.g., new-arrivals (leave blank to auto-generate)" style="border-color: var(--border-color); font-size: 14.5px;">
                            </div>
                            <small class="text-muted d-block mt-1">URL-friendly version of the name.</small>
                            @error('slug')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold text-dark fs-6">Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control rounded-3 py-2.5 @error('description') is-invalid @enderror" placeholder="Describe this collection..." style="border-color: var(--border-color); font-size: 14.5px;">{{ old('description', $collection->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold text-dark fs-6">Status</label>
                            <select name="status" id="status" class="form-select rounded-3 py-2.5 @error('status') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="active" {{ old('status', $collection->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $collection->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-warning text-white rounded-pill px-5 py-2.5 fw-bold" style="background-color: var(--primary); border-color: var(--primary); font-size: 15px;">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
