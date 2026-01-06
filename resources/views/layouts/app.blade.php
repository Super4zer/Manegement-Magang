<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Magang Management') - InternHub</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Theme Variables */
        :root, [data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-tertiary: #1a1a25;
            --bg-card: rgba(26, 26, 37, 0.8);
            --bg-hover: rgba(255, 255, 255, 0.05);
            
            --accent-primary: #a78bfa; /* Sweet Lavender Solid */
            --accent-secondary: #c084fc;
            --accent-tertiary: #e879f9;
            --accent-gradient: #a78bfa; /* Solid Sweet Color, No Gradient */
            
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --text-muted: #71717a;
            
            --border-color: rgba(255, 255, 255, 0.1);
            --border-hover: rgba(255, 255, 255, 0.2);
            
            --success: #4ade80; /* Pastel Green */
            --warning: #fbbf24; /* Pastel Orange */
            --danger: #fb7185; /* Pastel Red */
            --info: #38bdf8; /* Pastel Blue */
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.5);
            --shadow-glow: none; /* No glow gradient */
        }

        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f5f9;
            --bg-card: rgba(255, 255, 255, 0.9);
            --bg-hover: rgba(0, 0, 0, 0.03);
            
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            
            --border-color: rgba(0, 0, 0, 0.1);
            --border-hover: rgba(0, 0, 0, 0.2);
            
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            --shadow-glow: none;
        }

        /* Common Variables */
        :root {
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Animated Background Removed for Clean Look */
        .bg-animation {
            display: none;
        }

        /* Layout */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            padding: 24px 16px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            transition: transform var(--transition-normal), background var(--transition-normal);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
        }

        .sidebar-brand-icon {
            width: 48px;
            height: 48px;
            background: var(--accent-gradient);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: var(--shadow-glow);
        }

        .sidebar-brand-text h1 {
            font-size: 20px;
            font-weight: 700;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar-brand-text span {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-nav {
            list-style: none;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--accent-gradient);
            transform: scaleY(0);
            transition: transform var(--transition-fast);
        }

        .nav-link:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.15);
            color: var(--text-primary);
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: var(--bg-secondary);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 16px 32px;
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background var(--transition-normal);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Theme Toggle */
        .theme-toggle {
            width: 44px;
            height: 44px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
            color: var(--text-secondary);
            font-size: 18px;
        }

        .theme-toggle:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
            border-color: var(--accent-primary);
        }

        [data-theme="dark"] .theme-toggle .fa-sun { display: none; }
        [data-theme="dark"] .theme-toggle .fa-moon { display: block; }
        [data-theme="light"] .theme-toggle .fa-sun { display: block; }
        [data-theme="light"] .theme-toggle .fa-moon { display: none; }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: var(--bg-tertiary);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
        }

        .user-menu:hover {
            background: var(--bg-hover);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition-fast);
            box-shadow: var(--shadow-lg);
        }

        .user-menu:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .dropdown-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 8px 0;
        }

        /* Content Area */
        .content {
            padding: 32px;
            padding-bottom: 80px; /* Extra padding at bottom to prevent UI cut-off */
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            backdrop-filter: blur(10px);
            transition: all var(--transition-normal);
        }

        .card:hover {
            border-color: var(--border-hover);
            box-shadow: var(--shadow-md);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
        }

        /* Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: all var(--transition-normal);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent-gradient);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-glow);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .stat-icon.primary { background: rgba(99, 102, 241, 0.2); color: var(--accent-primary); }
        .stat-icon.success { background: rgba(34, 197, 94, 0.2); color: var(--success); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.2); color: var(--warning); }
        .stat-icon.danger { background: rgba(239, 68, 68, 0.2); color: var(--danger); }
        .stat-icon.info { background: rgba(6, 182, 212, 0.2); color: var(--info); }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 4px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: var(--radius-md);
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-fast);
            text-decoration: none;
        }

        .btn-primary {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
        }

        .btn-secondary {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-hover);
            border-color: var(--border-hover);
        }

        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-info { background: var(--info); color: white; }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            justify-content: center;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-md);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background: var(--bg-tertiary);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
        }

        tr {
            transition: all var(--transition-fast);
        }

        tbody tr:hover {
            background: var(--bg-hover);
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 14px;
            transition: all var(--transition-fast);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success { background: rgba(34, 197, 94, 0.2); color: var(--success); }
        .badge-warning { background: rgba(245, 158, 11, 0.2); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.2); color: var(--danger); }
        .badge-info { background: rgba(6, 182, 212, 0.2); color: var(--info); }
        .badge-primary { background: rgba(99, 102, 241, 0.2); color: var(--accent-primary); }
        .badge-secondary { background: rgba(161, 161, 170, 0.2); color: var(--text-secondary); }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--success);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--danger);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: var(--warning);
        }

        /* Pagination */
        .pagination {
            display: flex;
            gap: 6px;
            justify-content: center;
            list-style: none; /* Remove bullets */
            padding-left: 0;
            margin-top: 24px;
        }

        .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-fast);
            font-size: 14px;
        }

        .page-item:first-child .page-link, 
        .page-item:last-child .page-link {
            border-radius: var(--radius-md); /* Override bootstrap defaults if any */
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--bg-secondary);
        }

        .page-item .page-link:hover {
            background: var(--bg-hover);
            border-color: var(--accent-primary);
            color: var(--text-primary);
            z-index: 2;
        }

        .page-item.active .page-link {
            background: var(--accent-gradient);
            border-color: transparent;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
        
        /* Hide SVG icons or text if needed, but styling page-link usually handles it */
        .page-link svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: var(--bg-tertiary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--text-muted);
        }

        .empty-state-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .empty-state-text {
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        /* Utilities */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-muted { color: var(--text-muted); }
        .text-success { color: var(--success); }
        .text-warning { color: var(--warning); }
        .text-danger { color: var(--danger); }
        .text-primary { color: var(--accent-primary); }

        .mb-4 { margin-bottom: 16px; }
        .mb-6 { margin-bottom: 24px; }
        .mt-4 { margin-top: 16px; }
        .mt-6 { margin-top: 24px; }

        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-4 { gap: 16px; }

        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-up {
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Progress Bar */
        .progress {
            height: 8px;
            background: var(--bg-tertiary);
            border-radius: 50px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: var(--accent-gradient);
            border-radius: 50px;
            transition: width 1s ease;
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .search-input {
            position: relative;
        }

        .search-input input {
            padding-left: 44px;
        }

        .search-input i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .menu-toggle {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="sidebar-brand-text">
                    <h1>InternHub</h1>
                    <span>Management System</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Menu Utama</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        
                        @if(auth()->user()->canManage())
                        <li class="nav-item">
                            <a href="{{ route('interns.index') }}" class="nav-link {{ request()->routeIs('interns.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                <span>Anggota Magang</span>
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item">
                            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                                <i class="fas fa-tasks"></i>
                                <span>Daftar Pekerjaan</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                <span>Presensi</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @if(auth()->user()->canManage())
                <div class="nav-section">
                    <div class="nav-section-title">Evaluasi</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i>
                                <span>Laporan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('assessments.index') }}" class="nav-link {{ request()->routeIs('assessments.*') ? 'active' : '' }}">
                                <i class="fas fa-star"></i>
                                <span>Penilaian</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                @if(auth()->user()->role === 'admin')
                <div class="nav-section">
                    <div class="nav-section-title">Sistem</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                <div class="nav-section">
                    <div class="nav-section-title">Akun</div>
                    <ul>
                        <li class="nav-item">
                            <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <i class="fas fa-user-circle"></i>
                                <span>Profil @if(auth()->user()->isIntern())& Statistik @endif</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="d-flex align-center gap-4">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="header-title">@yield('title', 'Dashboard')</h2>
                </div>

                <div class="header-actions">
                    <!-- Theme Toggle -->
                    <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Theme">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                    </button>

                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->role }}</div>
                        </div>
                        <i class="fas fa-chevron-down" style="color: var(--text-muted); font-size: 12px;"></i>

                        <div class="dropdown-menu">
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <!-- Content -->
            <div class="content fade-in">
                <!-- Alerts handled by SweetAlert2 -->
                
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Load saved theme
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
        
        loadTheme();

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // SweetAlert2 Integration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                background: '#fff',
                color: '#1e293b'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444',
                background: '#fff',
                color: '#1e293b'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: "{{ session('warning') }}",
                confirmButtonColor: '#f59e0b',
                background: '#fff',
                color: '#1e293b'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Ada Kesalahan Input',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#ef4444',
                background: '#fff',
                color: '#1e293b'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
