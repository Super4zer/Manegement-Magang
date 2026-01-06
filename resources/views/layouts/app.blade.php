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

    <!-- Google Fonts - Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- Vite CSS -->
    @vite(["resources/css/app.css", "resources/js/app.js"])

    @livewireStyles
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
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @if(auth()->user()->canManage())
                            <li class="nav-item">
                                <a href="{{ route('interns.index') }}"
                                    class="nav-link {{ request()->routeIs('interns.*') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    <span>Anggota Magang</span>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="{{ route('tasks.index') }}"
                                class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                                <i class="fas fa-tasks"></i>
                                <span>Daftar Pekerjaan</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}"
                                class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
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
                                <a href="{{ route('reports.index') }}"
                                    class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Laporan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('assessments.index') }}"
                                    class="nav-link {{ request()->routeIs('assessments.*') ? 'active' : '' }}">
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
                                <a href="{{ route('settings.index') }}"
                                    class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
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
                            <a href="{{ route('profile.show') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
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
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item text-danger">
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

                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
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
        document.addEventListener('click', function (e) {
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
    @livewireScripts
    @stack('scripts')
</body>

</html>
