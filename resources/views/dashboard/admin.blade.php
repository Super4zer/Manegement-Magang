@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Custom Style for White/Sweet/Acrylic Theme (No Gradient) -->
@push('styles')
<style>
    /* Styling Dasar Dashboard */
    .dashboard-container {
        position: relative;
    }

    /* Acrylic Cards - Solid Feel */
    .card, .stat-card {
        background: rgba(255, 255, 255, 0.85) !important; /* Lebih solid */
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8) !important; /* Light border */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        border-radius: 20px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }

    .card:hover, .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
    }
    
    .card-header {
        border-bottom: 1px solid #f1f5f9 !important;
        padding-bottom: 20px !important;
        margin-bottom: 24px !important;
    }

    /* Sweet Stats Symbols (Solid Colors) */
    .stat-value {
        color: #1e293b; /* Solid Dark Color */
        font-weight: 800 !important;
    }
    
    .stat-label {
        font-weight: 500;
        color: #64748b !important;
        letter-spacing: 0.5px;
    }

    .stat-icon {
        border-radius: 16px !important;
        width: 60px !important;
        height: 60px !important;
        font-size: 24px !important;
        margin-bottom: 20px !important;
        box-shadow: none !important; /* Remove shadow for cleaner look */
    }
    
    /* Solid Pastel Colors */
    .stat-icon.primary { 
        background-color: #ddd6fe !important; /* Violet 200 */
        color: #5b21b6 !important; /* Violet 800 */
    }
    .stat-icon.success { 
        background-color: #bbf7d0 !important; /* Green 200 */
        color: #166534 !important; /* Green 800 */
    }
    .stat-icon.warning { 
        background-color: #fed7aa !important; /* Orange 200 */
        color: #9a3412 !important; /* Orange 800 */
    }
    .stat-icon.info { 
        background-color: #bae6fd !important; /* Sky 200 */
        color: #075985 !important; /* Sky 800 */
    }

    /* Typography */
    h2, h3, .card-title {
        color: #334155;
        letter-spacing: -0.3px;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }

    /* Buttons (Solid) */
    .btn {
        border-radius: 10px !important;
        font-weight: 600 !important;
        letter-spacing: 0.3px;
        box-shadow: none !important;
        border: none !important;
    }
    
    .btn-primary {
        background-color: #8b5cf6 !important; /* Violet 500 */
        color: white !important;
    }
    .btn-primary:hover {
        background-color: #7c3aed !important; /* Violet 600 */
    }
    
    .btn-secondary {
        background-color: #f8fafc !important; /* Slate 50 */
        color: #475569 !important; /* Slate 600 */
        border: 1px solid #e2e8f0 !important;
    }
    .btn-secondary:hover {
        background-color: #f1f5f9 !important; /* Slate 100 */
        color: #1e293b !important;
    }

    /* Tables */
    table {
        border-spacing: 0;
        border-collapse: separate !important;
        width: 100%;
    }
    
    thead th {
        border: none !important;
        background: transparent !important;
        color: #94a3b8 !important;
        text-transform: uppercase;
        font-size: 11px !important;
        letter-spacing: 1px;
        padding-bottom: 12px !important;
    }
    
    tbody tr {
        background: transparent;
        transition: background-color 0.2s ease;
    }
    
    tbody tr:hover {
        background-color: #f8fafc !important;
    }
    
    td {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 16px 12px !important;
    }
    
    /* Badges (Pastel Solid) */
    .badge {
        padding: 6px 12px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        font-size: 11px !important;
    }
    
    /* Warna Badge Custom */
    .badge-success { background-color: #dcfce7 !important; color: #166534 !important; }
    .badge-warning { background-color: #ffedd5 !important; color: #9a3412 !important; }
    .badge-danger { background-color: #fee2e2 !important; color: #991b1b !important; }
    .badge-info { background-color: #e0f2fe !important; color: #075985 !important; }
    .badge-primary { background-color: #ede9fe !important; color: #5b21b6 !important; }
</style>
@endpush

<div class="dashboard-container slide-up">
    <!-- Header tanpa gradien -->
    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 28px; margin-bottom: 8px; font-weight: 700; color: #1e293b;">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
        <p style="color: #64748b; font-size: 15px;">Dashboard ringkas dan bersih untuk memantau aktivitas magang.</p>
    </div>

    <!-- Stats Grid -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-value">{{ $totalInterns }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-value">{{ $completedOnTime }}</div>
            <div class="stat-label">Tepat Waktu</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-value">{{ $completedLate }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-value">{{ $presentToday }} / {{ $totalInterns }}</div>
            <div class="stat-label">Kehadiran</div>
        </div>
    </div>

    <!-- Task Overview Card -->
    <div class="card mb-6 mt-6">
        <div class="card-header border-0">
            <h3 class="card-title"><i class="fas fa-chart-pie" style="color: #8b5cf6; margin-right: 8px;"></i> Statistik Tugas</h3>
            <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Buat Tugas
            </a>
        </div>
        <div class="grid-2" style="align-items: center;">
            <div class="chart-container" style="height: 250px;">
                <canvas id="taskPieChart"></canvas>
            </div>
            <div>
                <div class="task-stat-item" style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px; padding: 12px 16px; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div style="width: 10px; height: 10px; background-color: #4ade80; border-radius: 50%;"></div>
                    <div style="flex: 1; color: #475569; font-weight: 500; font-size: 14px;">Tepat Waktu</div>
                    <strong style="color: #1e293b; font-size: 16px;">{{ $completedOnTime }}</strong>
                </div>
                <div class="task-stat-item" style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px; padding: 12px 16px; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div style="width: 10px; height: 10px; background-color: #fbbf24; border-radius: 50%;"></div>
                    <div style="flex: 1; color: #475569; font-weight: 500; font-size: 14px;">Terlambat</div>
                    <strong style="color: #1e293b; font-size: 16px;">{{ $completedLate }}</strong>
                </div>
                <div class="task-stat-item" style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px; padding: 12px 16px; background-color: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div style="width: 10px; height: 10px; background-color: #a78bfa; border-radius: 50%;"></div>
                    <div style="flex: 1; color: #475569; font-weight: 500; font-size: 14px;">Dalam Proses</div>
                    <strong style="color: #1e293b; font-size: 16px;">{{ $pendingTasks }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Recent Tasks -->
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-tasks" style="color: #f59e0b; margin-right: 8px;"></i> Tugas Terbaru</h3>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-secondary">Semua</a>
            </div>
            
            @if($recentTasks->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada tugas.</p>
                </div>
            @else
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Tugas</th>
                                <th>Siswa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTasks as $task)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #334155;">{{ Str::limit($task->title, 20) }}</div>
                                    @if($task->is_late && $task->status === 'completed')
                                        <span class="badge badge-danger" style="margin-top: 2px;">Late</span>
                                    @endif
                                </td>
                                <td style="color: #64748b; font-size: 13px;">{{ $task->intern->user->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->status_color }}">
                                        {{ $task->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Today's Attendance -->
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title"><i class="fas fa-calendar-check" style="color: #0ea5e9; margin-right: 8px;"></i> Presensi Hari Ini</h3>
                <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-secondary">Semua</a>
            </div>
            
            @if($recentAttendances->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada presensi.</p>
                </div>
            @else
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAttendances as $attendance)
                            <tr>
                                <td style="color: #334155; font-weight: 500;">{{ $attendance->intern->user->name ?? '-' }}</td>
                                <td style="font-family: monospace; color: #64748b;">{{ $attendance->check_in ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $attendance->status_color }}">
                                        {{ $attendance->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="card mt-6">
        <div class="card-header border-0">
            <h3 class="card-title"><i class="fas fa-chart-bar" style="color: #f43f5e; margin-right: 8px;"></i> Performa Siswa</h3>
        </div>
        <div class="chart-container" style="height: 350px;">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Task Pie Chart
    new Chart(document.getElementById('taskPieChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Tepat Waktu', 'Terlambat', 'Dalam Proses'],
            datasets: [{
                data: [{{ $completedOnTime }}, {{ $completedLate }}, {{ $pendingTasks }}],
                // Solid Pastel Colors (No Gradient)
                backgroundColor: ['#4ade80', '#fbbf24', '#a78bfa'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#64748b',
                        font: { family: 'Inter', size: 12 },
                        padding: 20
                    }
                }
            },
            cutout: '70%',
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // Performance Chart
    const interns = [
        @foreach($interns as $intern)
        {
            name: "{{ $intern->user->name }}",
            on_time: {{ $intern->getCompletedOnTimeCount() }},
            late: {{ $intern->getCompletedLateCount() }},
        },
        @endforeach
    ];

    new Chart(document.getElementById('performanceChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: interns.map(i => i.name),
            datasets: [
                {
                    label: 'Tepat Waktu',
                    data: interns.map(i => i.on_time),
                    backgroundColor: '#4ade80',
                    borderRadius: 4,
                },
                {
                    label: 'Terlambat',
                    data: interns.map(i => i.late),
                    backgroundColor: '#fbbf24',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { family: 'Inter' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', borderDash: [5, 5] },
                    ticks: { color: '#64748b', font: { family: 'Inter' } },
                    border: { display: false }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#64748b', usePointStyle: true, padding: 20 }
                }
            }
        }
    });

    // Panggil SweetAlert test jika diperlukan (hanya untuk debug)
    // Swal.fire('Dashboard Siap!', 'Tampilan baru tanpa gradient.', 'success');
</script>
@endpush
@endsection
