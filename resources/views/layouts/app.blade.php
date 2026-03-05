<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barangay Profiling System')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(255,255,255,0.06);
            --sidebar-active: rgba(79,70,229,0.25);
            --body-bg: #f1f5f9;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --card-shadow-hover: 0 10px 25px rgba(0,0,0,0.08);
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: #1e293b;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh;
            background: var(--sidebar-bg);
            color: #fff; z-index: 1000; overflow-y: auto; transition: all 0.3s;
            display: flex; flex-direction: column;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        .sidebar .brand {
            padding: 1.5rem 1.25rem; display: flex; align-items: center; gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar .brand-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; flex-shrink: 0;
        }
        .sidebar .brand-text h4 { margin: 0; font-weight: 700; font-size: 1rem; line-height: 1.2; }
        .sidebar .brand-text small { opacity: 0.5; font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase; }

        .sidebar .nav-section {
            padding: 1rem 1.25rem 0.4rem; font-size: 0.65rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.3);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.55); padding: 0.6rem 1rem;
            border-radius: 10px; margin: 1px 12px; font-size: 0.85rem; font-weight: 500;
            transition: all 0.2s; display: flex; align-items: center; gap: 0.75rem;
        }
        .sidebar .nav-link:hover {
            background: var(--sidebar-hover); color: rgba(255,255,255,0.9);
        }
        .sidebar .nav-link.active {
            background: var(--sidebar-active); color: #fff;
        }
        .sidebar .nav-link.active i { color: var(--primary-light); }
        .sidebar .nav-link i { font-size: 1.1rem; width: 22px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            margin-top: auto; padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-footer .user-pill {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.5rem 0.75rem; border-radius: 10px;
            background: rgba(255,255,255,0.04);
        }
        .sidebar-footer .user-avatar {
            width: 34px; height: 34px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-footer .user-info { overflow: hidden; }
        .sidebar-footer .user-name { font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-footer .user-role { font-size: 0.65rem; color: rgba(255,255,255,0.4); text-transform: capitalize; }

        /* ── Main Content ── */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }

        .top-navbar {
            background: #fff; padding: 0.85rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 999;
            backdrop-filter: blur(8px); background: rgba(255,255,255,0.85);
        }
        .top-navbar .page-title { font-weight: 700; font-size: 1.15rem; color: #0f172a; }
        .top-navbar .breadcrumb-hint { font-size: 0.75rem; color: #94a3b8; }

        .content-wrapper { padding: 1.75rem 2rem; }

        /* ── Stat Cards ── */
        .stat-card {
            background: #fff; border-radius: 16px; padding: 1.35rem;
            box-shadow: var(--card-shadow); border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
            position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            opacity: 0; transition: opacity 0.25s;
        }
        .stat-card:hover { box-shadow: var(--card-shadow-hover); transform: translateY(-3px); }
        .stat-card:hover::before { opacity: 1; }
        .stat-card .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .stat-card .stat-value { font-size: 1.85rem; font-weight: 800; letter-spacing: -0.5px; }
        .stat-card .stat-label { font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

        /* ── Cards & Tables ── */
        .card {
            border: 1px solid #e2e8f0; border-radius: 16px;
            box-shadow: var(--card-shadow); overflow: hidden;
        }
        .card-header {
            background: #fff; border-bottom: 1px solid #f1f5f9;
            font-weight: 600; padding: 1rem 1.25rem; font-size: 0.9rem;
        }
        .table th {
            font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.75px;
            color: #94a3b8; border-top: none; font-weight: 600;
            padding: 0.85rem 1rem; background: #f8fafc;
        }
        .table td { padding: 0.85rem 1rem; vertical-align: middle; color: #334155; font-size: 0.875rem; }
        .table-hover tbody tr:hover { background-color: #f8fafc; }
        .badge { font-weight: 600; padding: 0.35em 0.75em; border-radius: 6px; font-size: 0.75rem; }

        /* ── Buttons ── */
        .btn { border-radius: 10px; font-weight: 500; font-size: 0.875rem; padding: 0.5rem 1rem; transition: all 0.2s; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
        .btn-outline-primary { color: var(--primary-color); border-color: var(--primary-color); }
        .btn-outline-primary:hover { background-color: var(--primary-color); border-color: var(--primary-color); }

        /* ── Forms ── */
        .form-control, .form-select {
            border-radius: 10px; border: 1px solid #cbd5e1; padding: 0.6rem 0.85rem;
            font-size: 0.875rem; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        .form-label { font-weight: 500; font-size: 0.85rem; color: #475569; }

        /* ── Alerts ── */
        .alert { border: none; border-radius: 12px; font-size: 0.875rem; }

        /* ── Pagination ── */
        .pagination .page-link { border-radius: 8px; margin: 0 2px; font-size: 0.85rem; color: var(--primary-color); }
        .pagination .page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .content-wrapper { padding: 1rem; }
            .top-navbar { padding: 0.75rem 1rem; }
        }
        @media print {
            .sidebar, .top-navbar, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-icon">
                <i class="bi bi-building"></i>
            </div>
            <div class="brand-text">
                <h4>Barangay Profiling</h4>
                <small>Management System</small>
            </div>
        </div>

        <div class="nav-section">Main</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
            </li>
        </ul>

        <div class="nav-section">Records</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('residents*') ? 'active' : '' }}" href="{{ route('residents.index') }}">
                    <i class="bi bi-people-fill"></i> Residents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('households*') ? 'active' : '' }}" href="{{ route('households.index') }}">
                    <i class="bi bi-house-door-fill"></i> Households
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('officials*') ? 'active' : '' }}" href="{{ route('officials.index') }}">
                    <i class="bi bi-person-badge-fill"></i> Officials
                </a>
            </li>
        </ul>

        <div class="nav-section">Services</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('certificates*') ? 'active' : '' }}" href="{{ route('certificates.index') }}">
                    <i class="bi bi-file-earmark-text-fill"></i> Certificates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('blotters*') ? 'active' : '' }}" href="{{ route('blotters.index') }}">
                    <i class="bi bi-journal-text"></i> Blotter Records
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="user-pill">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-sm p-0 border-0 text-white" style="opacity:0.4;" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-light d-md-none me-1" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <div class="page-title">@yield('page-title', 'Dashboard')</div>
                    <div class="breadcrumb-hint">@yield('breadcrumb', 'Welcome back!')</div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark fw-normal" style="font-size:0.8rem;">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('M d, Y') }}
                </span>
            </div>
        </div>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
