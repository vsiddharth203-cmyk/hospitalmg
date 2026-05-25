<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HMS') — Hospital Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0a1628;
            --sidebar-accent: #1e3a5f;
            --primary: #0066cc;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f0f4f8; min-height: 100vh; }

        #sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
        }
        #sidebar .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        #sidebar .sidebar-brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: 1.1rem; }
        #sidebar .sidebar-brand small { color: rgba(255,255,255,0.4); font-size: 0.7rem; }
        #sidebar .nav-section {
            padding: 1rem 1.25rem 0.25rem;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            display: block;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,0.65);
            padding: 0.6rem 1.25rem;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            transition: all 0.2s;
            margin: 0.1rem 0.5rem;
            border-radius: 8px;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            color: #fff;
            background: var(--sidebar-accent);
        }
        #sidebar .nav-link.active {
            background: var(--primary);
            box-shadow: 0 4px 15px rgba(0,102,204,0.3);
        }
        #sidebar .nav-link i { font-size: 1rem; width: 20px; }

        #main-content { margin-left: var(--sidebar-width); min-height: 100vh; }

        .top-navbar {
            background: #fff;
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e9ef;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-content { padding: 1.5rem; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        .stat-card { border-radius: 12px; padding: 1.25rem; color: #fff; position: relative; overflow: hidden; }
        .stat-card::after { content:''; position:absolute; right:-20px; top:-20px; width:100px; height:100px; border-radius:50%; background:rgba(255,255,255,0.1); }
        .stat-card .stat-icon { font-size: 2rem; opacity: 0.8; }
        .stat-card .stat-value { font-size: 2rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: 0.8rem; opacity: 0.85; }
        .table { font-size: 0.88rem; }
        .table th { font-weight: 600; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .badge { font-weight: 500; font-size: 0.75rem; }
        .form-control, .form-select { border-radius: 8px; border-color: #e5e9ef; font-size: 0.9rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(0,102,204,0.1); }
        .form-label { font-weight: 500; font-size: 0.85rem; color: #374151; }
        .btn { border-radius: 8px; font-size: 0.88rem; font-weight: 500; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .page-header { margin-bottom: 1.5rem; }
        .page-header h4 { font-weight: 700; color: #1e293b; margin: 0; }
        .page-header p { color: #64748b; font-size: 0.875rem; margin: 0; }
        .alert { border-radius: 10px; border: none; font-size: 0.875rem; }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:#0066cc;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-hospital text-white"></i>
            </div>
            <div>
                <h5>HMS</h5>
                <small>Hospital Management</small>
            </div>
        </div>
    </div>

    <div class="py-2">
        <span class="nav-section">Main</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <span class="nav-section">Patients & Doctors</span>
        <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Patients
        </a>
        <a href="{{ route('doctors.index') }}" class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Doctors
        </a>

        <span class="nav-section">Clinical</span>
        <a href="{{ route('appointments.index') }}" class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-door-open"></i> Admissions
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-capsule"></i> Pharmacy
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-activity"></i> Lab Tests
        </a>

        <span class="nav-section">Finance</span>
        <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Billing
        </a>

        <span class="nav-section">Administration</span>
        <a href="#" class="nav-link">
            <i class="bi bi-people-fill"></i> Staff
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-building"></i> Wards & Rooms
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-gear"></i> Settings
        </a>
    </div>
</nav>

<div id="main-content">
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-link p-0 text-muted d-md-none"
                onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-link p-0 text-muted dropdown-toggle d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown">
                    <div style="width:34px;height:34px;background:#e8f4ff;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person text-primary"></i>
                    </div>
                    <span class="d-none d-md-inline text-dark" style="font-size:0.9rem;">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>