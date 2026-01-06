@extends('layouts.app')

@section('title', 'Detail Anggota Magang')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('interns.index') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="margin-bottom: 4px;">{{ $intern->user->name }}</h2>
            <p class="text-muted">{{ $intern->school }} - {{ $intern->department }}</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-value">{{ $stats['completedTasks'] }} / {{ $stats['totalTasks'] }}</div>
            <div class="stat-label">Tugas Selesai</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-value">{{ $stats['attendancePercentage'] }}%</div>
            <div class="stat-label">Tingkat Kehadiran</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <div class="stat-value">{{ $stats['averageSpeed'] }}%</div>
            <div class="stat-label">Kecepatan Kerja</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-value">{{ $stats['overallScore'] }}</div>
            <div class="stat-label">Skor Rata-rata</div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Profile Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Informasi Profil</h3>
                <a href="{{ route('interns.edit', $intern) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
            
            <div style="display: grid; gap: 16px;">
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Email</label>
                    <strong>{{ $intern->user->email }}</strong>
                </div>
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">NIS</label>
                    <strong>{{ $intern->nis ?? '-' }}</strong>
                </div>
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">No. Telepon</label>
                    <strong>{{ $intern->phone ?? '-' }}</strong>
                </div>
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Alamat</label>
                    <strong>{{ $intern->address ?? '-' }}</strong>
                </div>
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Pembimbing</label>
                    <strong>{{ $intern->supervisor->name ?? '-' }}</strong>
                </div>
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Periode Magang</label>
                    <strong>{{ $intern->start_date->format('d M Y') }} - {{ $intern->end_date->format('d M Y') }}</strong>
                </div>
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Status</label>
                    @if($intern->status === 'active')
                        <span class="badge badge-success">Aktif</span>
                    @elseif($intern->status === 'completed')
                        <span class="badge badge-primary">Selesai</span>
                    @else
                        <span class="badge badge-danger">Dibatalkan</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Task Status Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status Tugas</h3>
            </div>
            <div class="chart-container" style="height: 250px;">
                <canvas id="taskChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid-2 mt-6">
        <!-- Attendance Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Status Kehadiran</h3>
            </div>
            <div class="chart-container" style="height: 250px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt"></i> Aksi Cepat</h3>
            </div>
            <div class="d-flex gap-4" style="flex-direction: column;">
                <a href="{{ route('tasks.create') }}?intern_id={{ $intern->id }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Tugas
                </a>
                <a href="{{ route('assessments.create') }}?intern_id={{ $intern->id }}" class="btn btn-warning">
                    <i class="fas fa-star"></i> Beri Penilaian
                </a>
                <a href="{{ route('reports.create') }}?intern_id={{ $intern->id }}" class="btn btn-info">
                    <i class="fas fa-file-alt"></i> Buat Laporan
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Task Status Chart
    const taskCtx = document.getElementById('taskChart').getContext('2d');
    new Chart(taskCtx, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Dalam Proses', 'Menunggu', 'Revisi'],
            datasets: [{
                data: [{{ $taskStatusData['completed'] }}, {{ $taskStatusData['in_progress'] }}, {{ $taskStatusData['pending'] }}, {{ $taskStatusData['revision'] }}],
                backgroundColor: ['#22c55e', '#6366f1', '#71717a', '#f59e0b'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#a1a1aa', font: { family: 'Inter' } }
                }
            },
            cutout: '60%'
        }
    });

    // Attendance Chart
    const attCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Tidak Hadir', 'Sakit', 'Izin'],
            datasets: [{
                data: [{{ $attendanceData['present'] }}, {{ $attendanceData['late'] }}, {{ $attendanceData['absent'] }}, {{ $attendanceData['sick'] }}, {{ $attendanceData['permission'] }}],
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444', '#06b6d4', '#6366f1'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#a1a1aa', font: { family: 'Inter' } }
                }
            },
            cutout: '60%'
        }
    });
</script>
@endpush
@endsection
