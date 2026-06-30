<div class="sidebar-menu">
    <div class="menu-title">Sales Analytics</div>
    <ul class="nav-list">
        <li>
            <a href="{{ url('/admin') }}" class="nav-item-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i>
                <span>Dashboard Overview</span>
            </a>
        </li>
    </ul>

    <div class="menu-title mt-4">Store Management</div>
    <ul class="nav-list">
        <!-- Catalog Submenu -->
        <li class="has-submenu {{ request()->is('admin/products*') || request()->is('admin/main-categories*') || request()->is('admin/categories*') || request()->is('admin/collections*') || request()->is('admin/colors*') || request()->is('admin/sizes*') || request()->is('admin/inventory*') ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nav-item-link">
                <i class="ti ti-box"></i>
                <span>Product Catalog</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu-list" style="{{ request()->is('admin/products*') || request()->is('admin/main-categories*') || request()->is('admin/categories*') || request()->is('admin/collections*') || request()->is('admin/colors*') || request()->is('admin/sizes*') || request()->is('admin/inventory*') ? 'display: block;' : '' }}">
                <li>
                    <a href="{{ url('/admin/products') }}" class="submenu-link {{ (request()->is('admin/products') || (request()->is('admin/products/*') && !request()->is('admin/products/create'))) ? 'active' : '' }}">
                        <i class="ti ti-list me-1" style="font-size:12px;"></i> All Products
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/products/create') }}" class="submenu-link {{ request()->is('admin/products/create') ? 'active' : '' }}">
                        <i class="ti ti-plus me-1" style="font-size:12px;"></i> Add Product
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/main-categories') }}" class="submenu-link {{ request()->is('admin/main-categories*') ? 'active' : '' }}">
                        <i class="ti ti-category-2 me-1" style="font-size:12px;"></i> Main Categories
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/categories') }}" class="submenu-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                        <i class="ti ti-category me-1" style="font-size:12px;"></i> Product Categories
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/inventory') }}" class="submenu-link {{ request()->is('admin/inventory*') ? 'active' : '' }}">
                        <i class="ti ti-archive me-1" style="font-size:12px;"></i> Stock Inventory
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/collections') }}" class="submenu-link {{ request()->is('admin/collections*') ? 'active' : '' }}">
                        <i class="ti ti-tags me-1" style="font-size:12px;"></i> Product Collections
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/colors') }}" class="submenu-link {{ request()->is('admin/colors*') ? 'active' : '' }}">
                        <i class="ti ti-palette me-1" style="font-size:12px;"></i> Product Colors
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/sizes') }}" class="submenu-link {{ request()->is('admin/sizes*') ? 'active' : '' }}">
                        <i class="ti ti-ruler-2 me-1" style="font-size:12px;"></i> Product Sizes
                    </a>
                </li>
            </ul>
        </li>

        <!-- Orders Submenu -->
        <li class="has-submenu {{ request()->is('admin/orders*') ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nav-item-link">
                <i class="ti ti-shopping-cart"></i>
                <span>Orders & Sales</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu-list" style="{{ request()->is('admin/orders*') ? 'display: block;' : '' }}">
                <li>
                    <a href="{{ url('/admin/orders') }}" class="submenu-link {{ request()->is('admin/orders') || (request()->is('admin/orders/*') && !request()->is('admin/orders/create')) ? 'active' : '' }}">
                        <i class="ti ti-receipt me-1" style="font-size:12px;"></i> All Orders
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/orders?status=pending') }}" class="submenu-link {{ request()->fullUrlIs(url('/admin/orders?status=pending')) ? 'active' : '' }}">
                        <i class="ti ti-clock me-1" style="font-size:12px;"></i> Pending Orders
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-file-invoice me-1" style="font-size:12px;"></i> Invoices
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-truck me-1" style="font-size:12px;"></i> Shipments
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-refresh-alert me-1" style="font-size:12px;"></i> Returns/Refunds
                    </a>
                </li>
            </ul>
        </li>

        <!-- Customers Submenu -->
        <li class="has-submenu {{ request()->is('admin/users*') ? 'open' : '' }}">
            <a href="javascript:void(0)" class="nav-item-link">
                <i class="ti ti-users"></i>
                <span>Customers</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu-list" style="{{ request()->is('admin/users*') ? 'display: block;' : '' }}">
                <li>
                    <a href="{{ url('/admin/users') }}" class="submenu-link {{ request()->is('admin/users') ? 'active' : '' }}">
                        <i class="ti ti-user me-1" style="font-size:12px;"></i> All Customers
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-star me-1" style="font-size:12px;"></i> Reviews & Ratings
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-users-group me-1" style="font-size:12px;"></i> Customer Groups
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="menu-title mt-4">Marketing & Promotion</div>
    <ul class="nav-list">
        <!-- Promotions Submenu -->
        <li class="has-submenu">
            <a href="javascript:void(0)" class="nav-item-link">
                <i class="ti ti-discount-2"></i>
                <span>Campaigns</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu-list">
                <li>
                    <a href="{{ url('/admin/coupons') }}" class="submenu-link {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                        <i class="ti ti-ticket me-1" style="font-size:12px;"></i> Coupon Codes
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-speakerphone me-1" style="font-size:12px;"></i> Flash Sales
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/banners') }}" class="submenu-link {{ request()->is('admin/banners*') ? 'active' : '' }}">
                        <i class="ti ti-photo me-1" style="font-size:12px;"></i> Promo Banners
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-percentage me-1" style="font-size:12px;"></i> Promotions / Offers
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-sparkles me-1" style="font-size:12px;"></i> New Arrivals Settings
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="menu-title mt-4">System Setup</div>
    <ul class="nav-list">
        <!-- Reports Submenu -->
        <li class="has-submenu">
            <a href="javascript:void(0)" class="nav-item-link">
                <i class="ti ti-report-analytics"></i>
                <span>Reports & KPI</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu-list">
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-trending-up me-1" style="font-size:12px;"></i> Sales Report
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-package me-1" style="font-size:12px;"></i> Inventory Report
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-user-exclamation me-1" style="font-size:12px;"></i> Customer Insights
                    </a>
                </li>
            </ul>
        </li>

        <!-- Settings Submenu -->
        <li class="has-submenu">
            <a href="javascript:void(0)" class="nav-item-link">
                <i class="ti ti-settings"></i>
                <span>Store Settings</span>
                <i class="ti ti-chevron-down ms-auto submenu-arrow"></i>
            </a>
            <ul class="submenu-list">
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-home-cog me-1" style="font-size:12px;"></i> Store Profiles
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-credit-card me-1" style="font-size:12px;"></i> Payment Methods
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-truck-delivery me-1" style="font-size:12px;"></i> Shipping Rates
                    </a>
                </li>
                <li>
                    <a href="#" class="submenu-link">
                        <i class="ti ti-shield me-1" style="font-size:12px;"></i> Tax Configurations
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="menu-title mt-4">Shortcut</div>
    <ul class="nav-list">
        <li>
            <a href="{{ url('/') }}" class="nav-item-link">
                <i class="ti ti-shopping-bag"></i>
                <span>Customer Store</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="nav-item-link text-danger" style="color: #EF4444;">
                <i class="ti ti-logout" style="color: #EF4444;"></i>
                <span>Log Out</span>
            </a>
            <form id="admin-logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
