<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lotte Mart Recruitment')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 80px;
            background: linear-gradient(180deg, #dc3545 0%, #c82333 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .sidebar.expanded {
            width: 250px;
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-text {
            color: white;
            font-weight: bold;
            font-size: 18px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar.expanded .brand-text {
            opacity: 1;
        }

        .sidebar-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 5px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background: rgba(255,255,255,0.1);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            margin-top: 20px;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            white-space: nowrap;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
        }

        .sidebar-menu i {
            font-size: 18px;
            min-width: 30px;
            text-align: center;
        }

        .sidebar-menu .menu-text {
            margin-left: 15px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar.expanded .menu-text {
            opacity: 1;
        }

        .recruitment-text {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%) rotate(-90deg);
            color: white;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 2px;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar.expanded .recruitment-text {
            opacity: 0;
        }

        .social-icons {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .social-icons a {
            color: white;
            font-size: 18px;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: rgba(255,255,255,0.1);
        }

        .sidebar.expanded .social-icons {
            width: 250px;
            flex-direction: row;
            justify-content: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 80px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 250px;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-brand .logo {
            width: 35px;
            height: 35px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 25px;
            transition: background 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .user-dropdown:hover {
            background: #f8f9fa;
            color: #333;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Original Page Styles - Keep your existing styles */
        .hero-section {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .search-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }

        .job-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .job-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-2px);
            border-color: #dc3545;
        }

        .job-header {
            background: linear-gradient(45deg, #f8f9fa, #ffffff);
            padding: 20px;
            border-bottom: 1px solid #eef2f7;
        }

        .job-body {
            padding: 20px;
        }

        .job-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 8px;
            text-decoration: none;
        }

        .job-title:hover {
            color: #dc3545;
            text-decoration: none;
        }

        .company-badge {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .job-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 15px 0;
        }

        .meta-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-type {
            background: #e9ecef;
            color: #495057;
        }

        .badge-role {
            background: #f8f9fa;
            color: #6c757d;
        }

        .badge-exp {
            background: #e9ecef;
            color: #495057;
        }

        .badge-location {
            background: #f8f9fa;
            color: #6c757d;
        }

        .salary-info {
            background: linear-gradient(45deg, #6c757d, #495057);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }

        .btn-apply {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
            border-radius: 25px;
            padding: 10px 24px;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-apply:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .btn-view {
            border: 2px solid #dc3545;
            color: #dc3545;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-1px);
        }

        .btn-applied {
            background: #6c757d;
            border: none;
            border-radius: 25px;
            padding: 10px 24px;
            color: white;
            font-weight: 500;
        }

        .search-form .input-group-text {
            background: #dc3545;
            color: white;
            border: none;
        }

        .search-form .form-control,
        .search-form .form-select {
            border: 2px solid #e9ecef;
            border-radius: 0;
        }

        .search-form .form-control:focus,
        .search-form .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .filter-btn {
            background: linear-gradient(45deg, #dc3545, #c82333);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
            font-weight: 500;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #eef2f7;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Mobile Responsiveness */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 18px;
        }

        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.expanded {
                transform: translateX(0);
                width: 250px;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .hero-section {
                padding: 40px 0;
            }

            .job-header, .job-body {
                padding: 15px;
            }

            .job-meta {
                justify-content: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-text">Lotte Mart</div>
        </div>

        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-times"></i>
        </button>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Beranda</span>
                </a>
            </li>
            <li>
                <a href="#" class="">
                    <i class="fas fa-users"></i>
                    <span class="menu-text">Tentang HR</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lowongan.index') }}" class="{{ request()->routeIs('lowongan.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span class="menu-text">Lowongan</span>
                </a>
            </li>
            <li>
                <a href="#" class="">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-text">FAQ</span>
                </a>
            </li>
        </ul>

        {{-- <div class="recruitment-text">
            RECRUITMENT INFOMEDIA NUSANTARA
        </div> --}}

        <div class="social-icons">
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-brand">
                <div class="logo">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <span class="text-danger fw-bold">Lotte Mart</span>
                </div>
            </div>

            <div class="user-menu">
                <div class="dropdown">
                    <a class="user-dropdown dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        @if(auth()->user()->userDetail && auth()->user()->userDetail->foto)
                            <img src="{{ asset('storage/' . auth()->user()->userDetail->foto) }}"
                                 alt="Profile"
                                 class="user-avatar">
                        @else
                            <i class="fas fa-user-circle fa-lg"></i>
                        @endif
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user.applications') }}">
                                <i class="fas fa-file-alt me-2"></i>Lamaran Saya
                            </a>
                        </li>
                        <!-- Tambahkan di sidebar user -->
                        <li>
                            <a href="{{ route('user.absensi.index') }}" class="{{ request()->routeIs('user.absensi.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span class="menu-text">Absensi Magang</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileToggle = document.getElementById('mobileToggle');
            const mobileOverlay = document.getElementById('mobileOverlay');

            // Desktop sidebar toggle
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('expanded');
                mainContent.classList.toggle('expanded');

                const icon = this.querySelector('i');
                if (sidebar.classList.contains('expanded')) {
                    icon.className = 'fas fa-times';
                } else {
                    icon.className = 'fas fa-bars';
                }
            });

            // Mobile sidebar toggle
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.add('expanded');
                mobileOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Close mobile sidebar
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('expanded');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close sidebar on mobile when clicking a menu item
            const menuLinks = document.querySelectorAll('.sidebar-menu a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('expanded');
                        mobileOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
