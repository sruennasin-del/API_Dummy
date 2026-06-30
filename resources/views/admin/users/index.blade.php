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
            <a href="{{ url('/admin/users/create') }}" class="btn btn-warning text-white rounded-pill px-4 py-2" style="background-color: var(--primary); border-color: var(--primary);">
                <i class="ti ti-user-plus me-1"></i> Add New User
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

    <!-- Filters & Table Card -->
    <div class="card-premium">
        <!-- Table Search Toolbar -->
        <div class="card-premium-header flex-column flex-sm-row gap-3">
            <div>
                <h2 class="card-premium-title">User Directory</h2>
                <p class="text-muted mb-0" style="font-size: 12px; margin-top: 2px;">{{ $users->total() }} accounts total</p>
            </div>
            
            <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-sm-end">
                <form action="{{ url('/admin/users') }}" method="GET" class="d-flex gap-2 w-100 w-sm-auto">
                    <div class="position-relative w-100 w-sm-auto" style="min-width: 200px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill px-3 py-1.5" placeholder="Search users..." style="font-size: 13.5px; border-color: var(--border-color); padding-left: 35px !important;">
                        <i class="ti ti-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 15px;"></i>
                    </div>

                    <select name="role" onchange="this.form.submit()" class="form-select rounded-pill px-3 text-muted" style="width: 140px; font-size: 13.5px; border-color: var(--border-color);">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>

                    @if(request()->anyFilled(['search', 'role']))
                        <a href="{{ url('/admin/users') }}" class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center" title="Clear Filters" style="font-size: 13px;">
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
                        <th>User</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th>Date Registered</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar" style="width: 38px; height: 38px; font-size: 12.5px; background-color: var(--light-bg); border: 1px solid var(--border-color); color: var(--primary); display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="fw-bold text-dark fs-6">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="ti ti-shield-lock align-middle me-0.5" style="font-size: 13px;"></i> Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="ti ti-user-circle align-middle me-0.5" style="font-size: 13px;"></i> Customer
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-outline-secondary border-0 p-2 rounded-circle" style="color: #64748B;" title="Edit Account">
                                        <i class="ti ti-pencil fs-5"></i>
                                    </a>
                                    
                                    @if(Auth::id() !== $user->id)
                                        <form action="{{ url('/admin/users/' . $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-2 rounded-circle" style="color: #EF4444;" title="Suspend User">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="ti ti-users fs-1 mb-2 d-block text-secondary opacity-50"></i>
                                <span class="d-block fw-semibold mb-1">No Users Found</span>
                                <span>Try adjusting your search filters or add a new user.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($users->hasPages())
            <div class="card-premium-body border-top pt-3 pb-1 px-4">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
