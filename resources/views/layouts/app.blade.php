<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TKIT Jamilul Mu\'minin')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8faec;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, #234f1e 0%, #2d6a2e 100%);
            color: white;
            padding: 2rem 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            flex-shrink: 0;
        }

        .sidebar-header-content {
            flex: 1;
        }

        .sidebar-header h2 {
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            color: #ddd;
            margin: 0;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-menu li {
            margin: 0;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            transition: background-color 0.3s;
            border-left: 3px solid transparent;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #4caf50;
        }

        .nav-menu i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }

        .navbar-top {
            background: white;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-top h3 {
            margin: 0;
            color: #234f1e;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .user-profile .logout-btn:hover {
            background-color: #c82333;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #234f1e;
            border-color: #234f1e;
        }

        .btn-primary:hover {
            background-color: #2d6a2e;
            border-color: #2d6a2e;
        }

        .table-dark {
            background-color: #234f1e;
        }

        .badge-success {
            background-color: #4caf50;
        }

        .badge-danger {
            background-color: #f44336;
        }

        .badge-warning {
            background-color: #ff9800;
        }

        .badge-info {
            background-color: #2196f3;
        }

        /* Pagination styling agar selaras dengan tema hijau sekolah */
        .custom-pagination .page-link {
            color: #234f1e;
            border-color: #dde2d2;
            background-color: #ffffff;
        }

        .custom-pagination .page-link:hover {
            color: #2d6a2e;
            background-color: #f2f7ec;
            border-color: #c8d4b5;
        }

        .custom-pagination .page-item.active .page-link {
            color: #ffffff;
            background-color: #234f1e;
            border-color: #234f1e;
        }

        .custom-pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #ffffff;
            border-color: #e5e7eb;
        }

        .custom-pagination .page-link i {
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
                transition: width 0.3s;
            }

            .sidebar.active {
                width: 250px;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @yield('style')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('image/logo putih.jpg') }}" alt="Logo Sekolah" class="sidebar-logo">
            <div class="sidebar-header-content">
                <h2>TKIT Jamilul Mu'minin</h2>
                <p>Sistem Manajemen Sekolah</p>
            </div>
        </div>
        
        <ul class="nav-menu">
            <li><a href="{{ route('dashboard.admin') }}" class="{{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a></li>
            
            <li><a href="{{ route('siswa.index') }}" class="{{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Data Siswa
            </a></li>
            
            <li><a href="{{ route('pembayaran.index') }}" class="{{ request()->routeIs('pembayaran.*') && !request()->routeIs('pembayaran.kwitansi*') && !request()->routeIs('pembayaran.print*') && !request()->routeIs('pembayaran.download*') ? 'active' : '' }}">
                <i class="fas fa-credit-card"></i> Pembayaran
            </a></li>
            
            <li><a href="{{ route('verifikasi.index') }}" class="{{ request()->routeIs('verifikasi.*') ? 'active' : '' }}">
                <i class="fas fa-check-circle"></i> Verifikasi Cashless
            </a></li>
            
            <li><a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Laporan
            </a></li>
            
            <li><a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Pengaturan
            </a></li>
            
            <li><a href="{{ route('backup.index') }}" class="{{ request()->routeIs('backup.*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Backup Data
            </a></li>
            
            <li><a href="{{ route('audit-log.index') }}" class="{{ request()->routeIs('audit-log.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Audit Log
            </a></li>
            
            <li><a href="{{ route('password.form') }}" class="{{ request()->routeIs('password.*') ? 'active' : '' }}">
                <i class="fas fa-key"></i> Ganti Password
            </a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <div class="navbar-top">
            <h3>@yield('page-title', 'Dashboard')</h3>
            <div class="user-profile">
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div>
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('script')
</body>
</html>
