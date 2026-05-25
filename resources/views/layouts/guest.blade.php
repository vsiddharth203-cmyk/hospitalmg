<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hospital Management') }} — Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f0f4f8; font-family: 'Figtree', sans-serif; }
        .login-card { max-width: 420px; margin: 80px auto; border-radius: 16px; border: none; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .login-header { background: linear-gradient(135deg, #0066cc, #0044aa); color: white; border-radius: 16px 16px 0 0; padding: 2rem; text-align: center; }
        .form-control:focus { border-color: #0066cc; box-shadow: 0 0 0 3px rgba(0,102,204,0.1); }
        .btn-primary { background: #0066cc; border-color: #0066cc; }
        .btn-primary:hover { background: #0052a3; border-color: #0052a3; }
    </style>
</head>
<body>
    <div class="login-card card mx-auto">
        <div class="login-header">
            <i class="bi bi-hospital" style="font-size: 2.5rem;"></i>
            <h4 class="mt-2 mb-0 fw-700">Hospital Management</h4>
            <small class="opacity-75">Sign in to your account</small>
        </div>
        <div class="card-body p-4">
            {{ $slot }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>