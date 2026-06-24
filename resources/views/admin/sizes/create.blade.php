@extends('admin.layout.app')

@section('title', 'Add Size - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Add New Size</h1>
            <p class="text-muted mb-0">Create a new size option for product variations.</p>
        </div>
        <div class="col-auto">
            <a href="{{ url('/admin/sizes') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 14px; font-weight: 600;">
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
    <form action="{{ url('/admin/sizes') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Basic Info -->
            <div class="col-lg-8">
                <div class="card-premium">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Size Details</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Size Name -->
                        <div class="mb-2">
                            <label for="name" class="form-label fw-semibold text-dark fs-6">Size Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control rounded-3 py-2.5 @error('name') is-invalid @enderror" placeholder="e.g., S, M, L, XL, 38, 40" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Classification & Settings -->
            <div class="col-lg-4">
                <div class="card-premium mb-4">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Status Settings</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label fw-semibold text-dark fs-6">Status</label>
                            <select name="status" id="status" class="form-select rounded-3 py-2.5 @error('status') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <small class="text-muted d-block mt-1">Inactive sizes won't be selectable for product configurations.</small>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button Area -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning text-white rounded-pill py-2.5 fw-bold" style="background-color: var(--primary); border-color: var(--primary); font-size: 15px;">
                        <i class="ti ti-circle-check me-1"></i> Save Size
                    </button>
                    <a href="{{ url('/admin/sizes') }}" class="btn btn-light border rounded-pill py-2.5 fw-semibold text-dark" style="font-size: 15px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
