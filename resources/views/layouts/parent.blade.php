<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Orang Tua - TKIT Jamilul Mu\'minin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .parent-layout {
            display: flex;
            min-height: 100vh;
        }

        .parent-sidebar {
            width: 230px;
            background: linear-gradient(135deg, #234f1e 0%, #2d6a2e 100%);
            color: #e5f4e9;
            padding: 1.5rem 1.2rem;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        .parent-sidebar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .parent-sidebar .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.8);
        }

        .parent-sidebar .brand-text h1 {
            font-size: 0.95rem;
            margin: 0;
            color: #fefce8;
        }

        .parent-sidebar .brand-text small {
            font-size: 0.75rem;
            color: #d1fae5;
        }

        .parent-nav {
            list-style: none;
            padding-left: 0;
            margin-top: 0.5rem;
        }

        .parent-nav li {
            margin-bottom: 0.25rem;
        }

        .parent-nav a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 0.65rem;
            border-radius: 8px;
            color: #e5f4e9;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .parent-nav a i {
            width: 18px;
        }

        .parent-nav a.active,
        .parent-nav a:hover {
            background: rgba(255,255,255,0.12);
            color: #fefce8;
        }

        .parent-sidebar-footer {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #d1fae5;
        }

        .parent-main {
            flex: 1;
            padding: 1.5rem 1.75rem;
        }

        .parent-main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .parent-user {
            font-size: 0.85rem;
            color: #64748b;
        }

        .logout-btn {
            border: none;
            background: #dc2626;
            color: #fff;
            border-radius: 999px;
            padding: 0.35rem 0.9rem;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .parent-layout {
                flex-direction: column;
            }

            .parent-sidebar {
                width: 100%;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .parent-nav {
                display: flex;
                gap: 0.5rem;
            }

            .parent-main {
                padding: 1rem;
            }
        }
    </style>

    @yield('style')
</head>
<body>
    <div class="parent-layout">
        <aside class="parent-sidebar">
            <div>
                <a href="{{ route('orangtua.dashboard') }}" class="brand">
                    <img src="{{ asset('image/logo putih.jpg') }}" alt="Logo TKIT" class="brand-logo">
                    <div class="brand-text">
                        <h1>TKIT Jamilul Mu'minin</h1>
                        <small>Portal Orang Tua</small>
                    </div>
                </a>

                <ul class="parent-nav mt-3">
                    <li>
                        <a href="{{ route('orangtua.dashboard') }}" class="{{ request()->routeIs('orangtua.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard Utama</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orangtua.pembayaran') }}" class="{{ request()->routeIs('orangtua.pembayaran*') ? 'active' : '' }}">
                            <i class="fas fa-wallet"></i>
                            <span>Pembayaran SPP</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orangtua.notifikasi') }}" class="{{ request()->routeIs('orangtua.notifikasi') ? 'active' : '' }}">
                            <i class="fas fa-bell"></i>
                            <span>Notifikasi</span>
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge badge-danger ml-2">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('password.form') }}" class="{{ request()->routeIs('password.*') ? 'active' : '' }}">
                            <i class="fas fa-key"></i>
                            <span>Ganti Password</span>
                        </a>
                    </li>
                </ul>

                <div class="parent-sidebar-footer d-none d-md-block">
                    <div><strong>Kontak TU</strong></div>
                    <div>WA: 0812-0000-1234</div>
                    <div>Email: info@tkit-jamilul.sch.id</div>
                </div>
            </div>

            <div class="d-none d-md-block mt-3">
                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="logout-btn w-100 text-left">
                        <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="parent-main">
            <div class="parent-main-header">
                <div>
                    <h5 class="mb-0" style="color:#064e3b;">@yield('page-title', 'Dashboard Orang Tua')</h5>
                </div>
                <div class="parent-user d-flex align-items-center">
                    <i class="fas fa-user-circle mr-1"></i>
                    <span>{{ Auth::user()->name ?? 'Orang Tua' }}</span>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    @yield('script')
</body>
</html>


