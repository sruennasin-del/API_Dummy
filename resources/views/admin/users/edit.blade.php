@extends('admin.layout.app')

@section('title', 'Edit User - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">Edit User Account</h1>
            <p class="text-muted mb-0">Update account credentials and system roles for {{ $user->name }}.</p>
        </div>
        <div class="col-auto">
            <a href="{{ url('/admin/users') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 14px; font-weight: 600;">
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
    <form action="{{ url('/admin/users/' . $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="card-premium">
                    <div class="card-premium-header">
                        <h2 class="card-premium-title">Account Details</h2>
                    </div>
                    <div class="card-premium-body">
                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold text-dark fs-6">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control rounded-3 py-2.5 @error('name') is-invalid @enderror" placeholder="e.g., John Doe" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold text-dark fs-6">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control rounded-3 py-2.5 @error('email') is-invalid @enderror" placeholder="e.g., john.doe@example.com" required style="border-color: var(--border-color); font-size: 14.5px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-4">
                            <label for="is_admin" class="form-label fw-semibold text-dark fs-6">System Role <span class="text-danger">*</span></label>
                            <select name="is_admin" id="is_admin" class="form-select rounded-3 py-2.5 @error('is_admin') is-invalid @enderror" style="border-color: var(--border-color); font-size: 14px;">
                                <option value="0" {{ old('is_admin', $user->is_admin ? '1' : '0') === '0' ? 'selected' : '' }}>Customer</option>
                                <option value="1" {{ old('is_admin', $user->is_admin ? '1' : '0') === '1' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            <small class="text-muted d-block mt-1">Administrator role grants access to the dashboard and administration tools.</small>
                            @error('is_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4" style="color: var(--border-color);">

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-dark fs-6">Change Password</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 py-2.5 @error('password') is-invalid @enderror" placeholder="Leave blank to keep current password" style="border-color: var(--border-color); font-size: 14.5px;">
                            <small class="text-muted d-block mt-1">Only fill this out if you wish to change this user's password.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark fs-6">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3 py-2.5" placeholder="Repeat the new password" style="border-color: var(--border-color); font-size: 14.5px;">
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ url('/admin/users') }}" class="btn btn-light border rounded-pill px-4 py-2.5 fw-semibold text-dark" style="font-size: 14.5px;">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-warning text-white rounded-pill px-4 py-2.5 fw-bold" style="background-color: var(--primary); border-color: var(--primary); font-size: 14.5px;">
                                <i class="ti ti-circle-check me-1"></i> Update User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
