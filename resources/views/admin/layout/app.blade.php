<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="no-referrer">
    <title>@yield('title', 'ZestShop Admin')</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"/>
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css"/>
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet"/>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <style>
        :root {
            --primary: #FF6B1A;
            --primary-hover: #E05510;
            --primary-light: #FFF0E8;
            --primary-pale: #FFF8F4;
            --primary-border: #FFD6BB;
            --dark: #1E293B;
            --light-bg: #F8FAFC;
            --border-color: #E2E8F0;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Layout Structure */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background-color: #ffffff;
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary), #FF9C5B);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 20px;
            font-family: 'Syne', sans-serif;
            box-shadow: 0 4px 12px rgba(255, 107, 26, 0.25);
        }

        .brand-text {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.5px;
        }

        .brand-text span {
            color: var(--primary);
        }

        .sidebar-menu {
            padding: 24px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94A3B8;
            margin-bottom: 12px;
            padding-left: 12px;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 10px;
            color: #64748B;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-item-link i {
            font-size: 20px;
            transition: transform 0.2s ease;
        }

        .nav-item-link:hover {
            color: var(--primary);
            background-color: var(--primary-pale);
        }

        .nav-item-link:hover i {
            transform: translateX(2px);
        }

        .nav-item-link.active {
            color: var(--primary);
            background-color: var(--primary-light);
            border-left: 4px solid var(--primary);
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        /* Submenu Styling */
        .submenu-list {
            list-style: none;
            padding: 0 0 0 32px;
            margin: 4px 0 0 0;
            display: none;
            flex-direction: column;
            gap: 4px;
        }

        .submenu-link {
            display: block;
            padding: 8px 12px;
            border-radius: 8px;
            color: #64748B;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .submenu-link:hover {
            color: var(--primary);
            background-color: var(--primary-pale);
        }

        .submenu-link.active {
            color: var(--primary);
            font-weight: 600;
        }

        .submenu-arrow {
            font-size: 14px;
            transition: transform 0.2s ease;
        }

        .has-submenu.open .submenu-arrow {
            transform: rotate(180deg);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            background-color: #FCFDFE;
        }

        /* User Profile in Sidebar */
        .user-widget {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #FF9C5B);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 8px rgba(255, 107, 26, 0.15);
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 11px;
            color: #64748B;
            margin: 0;
        }

        /* Main Content Container */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Top Header Styling */
        .admin-header {
            height: 70px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .toggle-sidebar-btn {
            display: none;
            background: none;
            border: 1px solid var(--border-color);
            width: 38px;
            height: 38px;
            border-radius: 8px;
            color: #64748B;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .toggle-sidebar-btn:hover {
            color: var(--primary);
            border-color: var(--primary-border);
            background-color: var(--primary-pale);
        }

        .header-search {
            position: relative;
            width: 300px;
        }

        .header-search input {
            width: 100%;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 8px 16px 8px 40px;
            font-size: 13.5px;
            font-family: inherit;
            outline: none;
            transition: all 0.2s ease;
        }

        .header-search input:focus {
            background-color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 107, 26, 0.15);
        }

        .header-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 18px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-action-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748B;
            position: relative;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .header-action-btn:hover {
            background-color: var(--primary-pale);
            border-color: var(--primary-border);
            color: var(--primary);
        }

        .badge-dot {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background-color: var(--primary);
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        /* Page Content Area */
        .content-body {
            padding: 32px;
            flex: 1;
        }

        /* Footer */
        .admin-footer {
            padding: 20px 32px;
            background-color: #ffffff;
            border-top: 1px solid var(--border-color);
            font-size: 13px;
            color: #94A3B8;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .admin-footer a:hover {
            text-decoration: underline;
        }

        /* General Premium UI Elements */
        .card-premium {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.05);
            transition: all 0.25s ease;
            overflow: hidden;
        }

        .card-premium:hover {
            box-shadow: 0 10px 25px -5px rgba(255, 107, 26, 0.08);
            border-color: rgba(255, 107, 26, 0.2);
        }

        .card-premium-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-premium-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .card-premium-body {
            padding: 24px;
        }

        /* Table Styling */
        .table-responsive {
            scrollbar-width: thin;
        }

        .table-premium {
            width: 100%;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table-premium th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94A3B8;
            background-color: #FCFDFE;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-premium td {
            font-size: 13.5px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            color: #475569;
        }

        .table-premium tr:last-child td {
            border-bottom: none;
        }

        .table-premium tr {
            transition: background-color 0.2s ease;
        }

        .table-premium tr:hover {
            background-color: var(--primary-pale);
        }

        /* Badges */
        .badge-premium {
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            line-height: 1;
        }

        .badge-premium-success {
            background-color: #DEF7EC;
            color: #03543F;
        }

        .badge-premium-warning {
            background-color: #FEF08A;
            color: #713F12;
        }

        .badge-premium-info {
            background-color: #E0F2FE;
            color: #0369A1;
        }

        .badge-premium-danger {
            background-color: #FDE8E8;
            color: #9B1C1C;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .toggle-sidebar-btn {
                display: flex;
            }
            .admin-header {
                padding: 0 20px;
            }
            .content-body {
                padding: 20px;
            }
        }

        @media (max-width: 575.98px) {
            .header-search {
                display: none;
            }
            .header-right {
                gap: 8px;
            }
        }
    </style>
    @stack('css')
</head>
<body>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="adminSidebar">
            <a href="{{ url('/admin') }}" class="sidebar-brand">
                <div class="brand-logo">Z</div>
                <div class="brand-text">Zest<span>Shop</span></div>
            </a>

            @include('admin.layout.sidebar_menu')

            <div class="sidebar-footer">
                @auth
                    <div class="user-widget">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div class="user-info">
                            <p class="user-name">{{ Auth::user()->name }}</p>
                            <p class="user-role">{{ Auth::user()->is_admin ? 'Administrator' : 'User' }}</p>
                        </div>
                    </div>
                @else
                    <div class="user-widget">
                        <div class="user-avatar">AD</div>
                        <div class="user-info">
                            <p class="user-name">Guest Admin</p>
                            <p class="user-role">Administrator</p>
                        </div>
                    </div>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="toggle-sidebar-btn" id="sidebarToggle" aria-label="Toggle Navigation">
                        <i class="ti ti-menu-2"></i>
                    </button>
                    <div class="header-search">
                        <i class="ti ti-search"></i>
                        <input type="text" placeholder="Search anything...">
                    </div>
                </div>

                <div class="header-right">
                    <a href="#" class="header-action-btn" aria-label="View notifications">
                        <i class="ti ti-bell"></i>
                        <span class="badge-dot"></span>
                    </a>
                    <a href="#" class="header-action-btn" aria-label="Settings">
                        <i class="ti ti-settings"></i>
                    </a>
                    
                    @auth
                        <div class="dropdown">
                            <button class="border-0 bg-transparent p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                                <div class="user-widget">
                                    <div class="user-avatar" style="width:36px; height:36px; cursor: pointer;">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                                </div>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 mt-2" style="border-radius:12px; min-width:180px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;">
                                <li class="px-3 py-2">
                                    <p class="user-name text-dark fw-bold mb-0" style="font-size:13.5px; line-height: 1.2;">{{ Auth::user()->name }}</p>
                                    <p class="user-role text-muted mb-0" style="font-size:11px;">Administrator</p>
                                </li>
                                <li><hr class="dropdown-divider bg-light"></li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2" href="{{ url('/') }}">
                                        <i class="ti ti-shopping-bag me-2"></i> View Shop
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-3 py-2 text-danger" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('admin-logout-form-header').submit();">
                                        <i class="ti ti-logout me-2"></i> Log Out
                                    </a>
                                    <form id="admin-logout-form-header" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="user-widget">
                            <div class="user-avatar" style="width:36px; height:36px;">AD</div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Content Area -->
            <main class="content-body">
                <div class="container-fluid p-0">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="admin-footer">
                <div>&copy; {{ date('Y') }} <a href="{{ url('/') }}">ZestShop</a>. All rights reserved.</div>
                <div>Handcrafted with <i class="ti ti-heart-filled text-danger"></i> & Orange Juice</div>
            </footer>
        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // SweetAlert2 Confirmation Dialog Handler
            $(document).on('click', '.btn-confirm', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var $form = $btn.closest('form');
                var title = $form.data('title') || 'Are you sure?';
                var text = $form.data('text') || 'Do you want to proceed?';
                var icon = $form.data('icon') || 'warning';
                var confirmText = $form.data('confirm-text') || 'Yes, proceed';
                
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#FF6B1A',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-4 border-0 shadow-lg',
                        confirmButton: 'btn btn-warning rounded-pill px-4 text-white',
                        cancelButton: 'btn btn-outline-secondary rounded-pill px-4 ms-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        $form.submit();
                    }
                });
            });

            // Toggle sidebar on mobile
            $('#sidebarToggle').on('click', function(e) {
                e.stopPropagation();
                $('#adminSidebar').toggleClass('show');
            });

            // Toggle submenus with slide animation
            $('.has-submenu > a').on('click', function(e) {
                e.preventDefault();
                var $parent = $(this).parent('.has-submenu');
                
                // Toggle current submenu
                $parent.toggleClass('open');
                $parent.find('.submenu-list').slideToggle(200);
                
                // Collapse sibling submenus (accordion style)
                $parent.siblings('.has-submenu.open').each(function() {
                    $(this).removeClass('open');
                    $(this).find('.submenu-list').slideUp(200);
                });
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() < 992) {
                    if (!$(e.target).closest('#adminSidebar').length && !$(e.target).closest('#sidebarToggle').length) {
                        $('#adminSidebar').removeClass('show');
                    }
                }
            });
        });
    </script>
    @stack('js')
</body>
</html>
