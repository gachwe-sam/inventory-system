<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventory') — Inventory System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.9.1/dist/css/adminlte.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body.login-page {
    min-height: 100vh;
    background:
        radial-gradient(600px circle at 15% 20%, rgba(255, 255, 255, .12), transparent 60%),
        radial-gradient(550px circle at 88% 12%, rgba(56, 163, 255, .45), transparent 55%),
        radial-gradient(700px circle at 80% 92%, rgba(11, 42, 107, .6), transparent 60%),
        radial-gradient(500px circle at 8% 85%, rgba(30, 80, 200, .5), transparent 55%),
        linear-gradient(135deg, #0b2a6b 0%, #1e50c8 55%, #38a3ff 100%);
        }


        .login-logo a {
            color: #fff;
        }

        .login-logo .bi {
            display: block;
            font-size: 2.5rem;
            margin-bottom: .25rem;
        }

        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .25);
        }
    </style>
    @stack('styles')
</head>
<body class="login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}">
            <i class="bi bi-boxes"></i>
            <b>Inventory</b> System
        </a>
    </div>
    <div class="card auth-card">
        <div class="card-body login-card-body">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
@stack('scripts')
</body>
</html>
