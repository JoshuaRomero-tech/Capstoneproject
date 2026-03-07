<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CiviTrack')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --body-bg: #f1f5f9;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--body-bg);
            color: #1e293b;
        }

        /* ── Navbar ── */
        .public-navbar {
            background: #0f172a;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            position: sticky; top: 0; z-index: 1000;
        }
        .public-navbar .navbar-brand {
            color: #fff; font-weight: 700; font-size: 1.1rem;
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 0;
        }
        .public-navbar .brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff; flex-shrink: 0;
        }
        .public-navbar .nav-link {
            color: rgba(255,255,255,0.65) !important; font-weight: 500; font-size: 0.9rem;
            padding: 1rem 1rem !important; transition: all 0.2s;
            border-bottom: 2px solid transparent;
        }
        .public-navbar .nav-link:hover {
            color: #fff !important; border-bottom-color: rgba(255,255,255,0.3);
        }
        .public-navbar .nav-link.active {
            color: #fff !important; border-bottom-color: var(--primary-light);
        }
        .public-navbar .navbar-toggler { border-color: rgba(255,255,255,0.2); }
        .public-navbar .navbar-toggler-icon { filter: invert(1); }

        /* ── Hero ── */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #fff; padding: 4rem 0; position: relative; overflow: hidden;
        }
        .hero-section::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(79,70,229,0.2) 0%, transparent 50%),
                        radial-gradient(circle at 80% 50%, rgba(129,140,248,0.1) 0%, transparent 50%);
        }
        .hero-section h1 { font-weight: 800; font-size: 2.5rem; position: relative; }
        .hero-section p { opacity: 0.7; font-size: 1.1rem; position: relative; }

        /* ── Cards & Components ── */
        .card {
            border: 1px solid #e2e8f0; border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden;
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

        .btn { border-radius: 10px; font-weight: 500; font-size: 0.875rem; padding: 0.5rem 1rem; transition: all 0.2s; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
        .btn-outline-primary { color: var(--primary-color); border-color: var(--primary-color); }
        .btn-outline-primary:hover { background-color: var(--primary-color); border-color: var(--primary-color); }

        .form-control, .form-select {
            border-radius: 10px; border: 1px solid #cbd5e1; padding: 0.6rem 0.85rem;
            font-size: 0.875rem; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light); box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        .form-label { font-weight: 500; font-size: 0.85rem; color: #475569; }

        .alert { border: none; border-radius: 12px; font-size: 0.875rem; }

        .pagination .page-link { border-radius: 8px; margin: 0 2px; font-size: 0.85rem; color: var(--primary-color); }
        .pagination .page-item.active .page-link { background-color: var(--primary-color); border-color: var(--primary-color); }

        /* ── Service Cards ── */
        .service-card {
            background: #fff; border-radius: 16px; padding: 2rem;
            border: 1px solid #e2e8f0; transition: all 0.3s;
            text-align: center; height: 100%;
        }
        .service-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }
        .service-card .service-icon {
            width: 64px; height: 64px; border-radius: 16px; margin: 0 auto 1.25rem;
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        .service-card h5 { font-weight: 700; font-size: 1rem; color: #0f172a; }
        .service-card p { font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem; }

        /* ── Official Cards ── */
        .official-card {
            background: #fff; border-radius: 16px; padding: 1.5rem;
            border: 1px solid #e2e8f0; text-align: center;
            transition: all 0.3s; height: 100%;
        }
        .official-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
        .official-card .avatar {
            width: 70px; height: 70px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0 auto 1rem;
        }
        .official-card .name { font-weight: 700; font-size: 0.95rem; color: #0f172a; }
        .official-card .position { font-size: 0.8rem; color: var(--primary-color); font-weight: 600; }
        .official-card .committee { font-size: 0.75rem; color: #94a3b8; }

        /* ── Footer ── */
        .public-footer {
            background: #0f172a; color: rgba(255,255,255,0.5);
            padding: 2rem 0; margin-top: 3rem; font-size: 0.85rem;
        }
        .public-footer a { color: var(--primary-light); text-decoration: none; }
        .public-footer a:hover { color: #fff; }

        /* ── Page Banner ── */
        .page-banner {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff; padding: 2.5rem 0; position: relative; overflow: hidden;
        }
        .page-banner::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 30% 50%, rgba(79,70,229,0.15) 0%, transparent 50%);
        }
        .page-banner h2 { font-weight: 800; position: relative; margin-bottom: 0.25rem; }
        .page-banner p { opacity: 0.6; font-size: 0.9rem; position: relative; margin-bottom: 0; }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg public-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.home') }}">
                <div class="brand-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div>CiviTrack</div>
                    <div style="font-size: 0.65rem; opacity: 0.5; font-weight: 400; text-transform: uppercase; letter-spacing: 0.5px;">Community Portal</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">
                            <i class="bi bi-house-door me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.residents*') ? 'active' : '' }}" href="{{ route('public.residents') }}">
                            <i class="bi bi-people me-1"></i> Residents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.officials*') ? 'active' : '' }}" href="{{ route('public.officials') }}">
                            <i class="bi bi-person-badge me-1"></i> Officials
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.services*') ? 'active' : '' }}" href="{{ route('public.services') }}">
                            <i class="bi bi-file-earmark-text me-1"></i> Services
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link" href="{{ route('login') }}" style="color: var(--primary-light) !important;">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Staff Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="public-footer">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                        <i class="bi bi-shield-check" style="color: var(--primary-light);"></i>
                        <span style="color: #fff; font-weight: 600;">CiviTrack</span>
                    </div>
                    <small>&copy; {{ date('Y') }} All rights reserved.</small>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('public.home') }}" class="me-3">Home</a>
                    <a href="{{ route('public.residents') }}" class="me-3">Residents</a>
                    <a href="{{ route('public.officials') }}" class="me-3">Officials</a>
                    <a href="{{ route('public.services') }}">Services</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
