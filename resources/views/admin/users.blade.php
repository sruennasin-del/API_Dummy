@extends('admin.layout.app')

@section('title', 'Manage Users - ZestShop')

@section('content')
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold mb-1" style="font-family: 'Syne', sans-serif;">User Management</h1>
            <p class="text-muted mb-0">Manage registered customers, administrator permissions, and editor roles.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <button class="btn btn-warning text-white rounded-pill px-4 py-2" style="background-color: var(--primary); border-color: var(--primary);">
                <i class="ti ti-user-plus me-1"></i> Add New User
            </button>
        </div>
    </div>

    <!-- Filters & Table Card -->
    <div class="card-premium">
        <!-- Table Search Toolbar -->
        <div class="card-premium-header flex-column flex-sm-row gap-3">
            <div>
                <h2 class="card-premium-title">User Directory</h2>
                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ count($users) }} accounts total</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
                <select class="form-select rounded-pill px-3 text-muted" style="width: 130px; font-size: 13.5px; border-color: var(--border-color);">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="customer">Customer</option>
                </select>
                <select class="form-select rounded-pill px-3 text-muted" style="width: 130px; font-size: 13.5px; border-color: var(--border-color);">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table table-premium">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Date Registered</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar" style="width: 38px; height: 38px; font-size: 12.5px;">
                                        {{ $user['avatar'] }}
                                    </div>
                                    <span class="fw-bold text-dark fs-6">{{ $user['name'] }}</span>
                                </div>
                            </td>
                            <td>{{ $user['email'] }}</td>
                            <td>
                                @if($user['role'] === 'Administrator')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="ti ti-shield-lock align-middle me-0.5" style="font-size: 13px;"></i> Admin
                                    </span>
                                @elseif($user['role'] === 'Editor')
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600; color: var(--primary) !important;">
                                        <i class="ti ti-edit align-middle me-0.5" style="font-size: 13px;"></i> Editor
                                    </span>
                                @elseif($user['role'] === 'Moderator')
                                    <span class="badge bg-info-subtle text-info rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="ti ti-eye align-middle me-0.5" style="font-size: 13px;"></i> Moderator
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="ti ti-user-circle align-middle me-0.5" style="font-size: 13px;"></i> Customer
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user['status'] === 'Active')
                                    <span class="badge-premium badge-premium-success">
                                        <i class="ti ti-circle-check-filled"></i> Active
                                    </span>
                                @else
                                    <span class="badge-premium badge-premium-danger">
                                        <i class="ti ti-circle-x-filled"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $user['joined_date'] }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <button class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" style="color: #64748B;" title="Edit Account">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" style="color: #EF4444;" title="Suspend User">
                                        <i class="ti ti-trash fs-5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
