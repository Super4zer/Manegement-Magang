@extends('layouts.app')

@section('title', 'Detail Penilaian')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('assessments.index') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div style="flex: 1;">
            <h2 style="margin-bottom: 4px;">Penilaian - {{ $assessment->intern->user->name }}</h2>
            <p class="text-muted">{{ $assessment->task->title ?? 'Penilaian Umum' }} | {{ $assessment->created_at->format('d M Y') }}</p>
        </div>
        <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>

    <div class="grid-2">
        <!-- Score Card -->
        <div class="card text-center">
            <div style="font-size: 72px; font-weight: 800; margin-bottom: 8px;">
                <span class="badge badge-{{ $assessment->grade_color }}" style="font-size: 64px; padding: 24px 48px;">
                    {{ $assessment->grade }}
                </span>
            </div>
            <div style="font-size: 32px; font-weight: 700; color: var(--accent-primary);">{{ $assessment->average_score }}</div>
            <div class="text-muted">Skor Rata-rata</div>
            
            <div style="margin-top: 24px; text-align: left;">
                <div class="text-muted" style="font-size: 12px; margin-bottom: 8px;">Dinilai oleh</div>
                <div class="d-flex align-center gap-2">
                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                        {{ strtoupper(substr($assessment->assessedBy->name ?? 'N', 0, 1)) }}
                    </div>
                    <strong>{{ $assessment->assessedBy->name }}</strong>
                </div>
            </div>
        </div>

        <!-- Radar Chart -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-radar"></i> Radar Penilaian</h3>
            </div>
            <div class="chart-container" style="height: 280px;">
                <canvas id="radarChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list-alt"></i> Detail Skor</h3>
        </div>
        
        <div style="display: grid; gap: 20px;">
            <div>
                <div class="d-flex justify-between mb-2">
                    <span>Kualitas Kerja</span>
                    <strong>{{ $assessment->quality_score }}/100</strong>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" style="width: {{ $assessment->quality_score }}%;"></div>
                </div>
            </div>
            
            <div>
                <div class="d-flex justify-between mb-2">
                    <span>Kecepatan</span>
                    <strong>{{ $assessment->speed_score }}/100</strong>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" style="width: {{ $assessment->speed_score }}%; background: linear-gradient(90deg, #22c55e, #16a34a);"></div>
                </div>
            </div>
            
            <div>
                <div class="d-flex justify-between mb-2">
                    <span>Inisiatif</span>
                    <strong>{{ $assessment->initiative_score }}/100</strong>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" style="width: {{ $assessment->initiative_score }}%; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                </div>
            </div>
            
            <div>
                <div class="d-flex justify-between mb-2">
                    <span>Kerjasama Tim</span>
                    <strong>{{ $assessment->teamwork_score }}/100</strong>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" style="width: {{ $assessment->teamwork_score }}%; background: linear-gradient(90deg, #06b6d4, #0891b2);"></div>
                </div>
            </div>
            
            <div>
                <div class="d-flex justify-between mb-2">
                    <span>Komunikasi</span>
                    <strong>{{ $assessment->communication_score }}/100</strong>
                </div>
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" style="width: {{ $assessment->communication_score }}%; background: linear-gradient(90deg, #a855f7, #9333ea);"></div>
                </div>
            </div>
        </div>
    </div>

    @if($assessment->strengths || $assessment->improvements || $assessment->comments)
    <div class="grid-2 mt-6">
        @if($assessment->strengths)
        <div class="card" style="background: rgba(34, 197, 94, 0.1); border-color: rgba(34, 197, 94, 0.3);">
            <h4 style="color: var(--success); margin-bottom: 12px;"><i class="fas fa-thumbs-up"></i> Kelebihan</h4>
            <p style="line-height: 1.7;">{{ $assessment->strengths }}</p>
        </div>
        @endif
        
        @if($assessment->improvements)
        <div class="card" style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.3);">
            <h4 style="color: var(--warning); margin-bottom: 12px;"><i class="fas fa-arrow-up"></i> Area Perbaikan</h4>
            <p style="line-height: 1.7;">{{ $assessment->improvements }}</p>
        </div>
        @endif
    </div>
    
    @if($assessment->comments)
    <div class="card mt-6">
        <h4 style="margin-bottom: 12px;"><i class="fas fa-comment"></i> Komentar Tambahan</h4>
        <p style="line-height: 1.7;">{{ $assessment->comments }}</p>
    </div>
    @endif
    @endif
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('radarChart').getContext('2d');
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Kualitas', 'Kecepatan', 'Inisiatif', 'Kerjasama', 'Komunikasi'],
            datasets: [{
                label: 'Skor',
                data: [
                    {{ $assessment->quality_score }},
                    {{ $assessment->speed_score }},
                    {{ $assessment->initiative_score }},
                    {{ $assessment->teamwork_score }},
                    {{ $assessment->communication_score }}
                ],
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(99, 102, 241, 1)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        color: '#71717a'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    angleLines: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    pointLabels: {
                        color: '#a1a1aa',
                        font: {
                            family: 'Inter',
                            size: 12
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection
