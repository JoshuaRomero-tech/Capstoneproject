<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CiviTrack</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 30% 20%, rgba(79,70,229,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 80%, rgba(129,140,248,0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        .login-wrapper {
            display: flex; align-items: stretch; max-width: 900px; width: 100%;
            border-radius: 24px; overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
            position: relative; z-index: 1;
        }
        .login-left {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff; padding: 3rem 2.5rem; flex: 1;
            display: flex; flex-direction: column; justify-content: center;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .login-left .brand-icon {
            width: 60px; height: 60px; border-radius: 16px;
            background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem; margin-bottom: 1.5rem;
        }
        .login-left h2 { font-weight: 800; font-size: 1.75rem; margin-bottom: 0.75rem; position: relative; }
        .login-left p { opacity: 0.8; font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem; position: relative; }
        .login-left .feature-list { list-style: none; padding: 0; margin: 0; position: relative; }
        .login-left .feature-list li {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.4rem 0; font-size: 0.85rem; opacity: 0.85;
        }
        .login-left .feature-list li i {
            width: 28px; height: 28px; border-radius: 8px;
            background: rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; flex-shrink: 0;
        }
        .login-right {
            background: #fff; padding: 3rem 2.5rem; flex: 1;
            display: flex; flex-direction: column; justify-content: center;
        }
        .login-right h3 { font-weight: 700; font-size: 1.5rem; color: #0f172a; margin-bottom: 0.25rem; }
        .login-right .subtitle { color: #94a3b8; font-size: 0.85rem; margin-bottom: 2rem; }
        .form-control {
            border-radius: 12px; border: 1.5px solid #e2e8f0; padding: 0.7rem 1rem;
            font-size: 0.9rem; transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        }
        .input-group-text {
            border-radius: 12px 0 0 12px; border: 1.5px solid #e2e8f0;
            border-right: none; background: #f8fafc; color: #94a3b8;
        }
        .input-group .form-control { border-left: none; }
        .input-group:focus-within .input-group-text { border-color: #818cf8; color: #4f46e5; }
        .form-label { font-weight: 600; font-size: 0.8rem; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none; padding: 0.8rem; font-weight: 600; font-size: 0.95rem;
            border-radius: 12px; transition: all 0.3s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #3730a3, #6d28d9);
            box-shadow: 0 8px 20px rgba(79,70,229,0.35);
            transform: translateY(-1px);
        }
        .form-check-input:checked { background-color: #4f46e5; border-color: #4f46e5; }
        .alert { border-radius: 12px; border: none; font-size: 0.85rem; }
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-wrapper { max-width: 420px; border-radius: 20px; }
            .login-right { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-left">
            <div class="brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <h2>CiviTrack</h2>
            <p>A comprehensive management platform for efficient barangay operations and community records.</p>
            <ul class="feature-list">
                <li><i class="bi bi-people-fill"></i> Resident Records Management</li>
                <li><i class="bi bi-house-door-fill"></i> Household Profiling</li>
                <li><i class="bi bi-file-earmark-text-fill"></i> Certificate Issuance</li>
                <li><i class="bi bi-journal-text"></i> Blotter Recording</li>
            </ul>
        </div>
        <div class="login-right">
            <h3>Welcome back</h3>
            <div class="subtitle">Sign in to your account to continue</div>

            @if($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    @foreach($errors->all() as $error)
                        <small>{{ $error }}</small><br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@barangay.com">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                    Sign In <i class="bi bi-arrow-right ms-1"></i>
                </button>
                <div class="text-center mt-3">
                    <a href="{{ route('public.home') }}" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Back to Community Portal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
