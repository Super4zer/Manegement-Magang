@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div style="flex: 1;">
            <div class="d-flex align-center gap-4" style="flex-wrap: wrap;">
                <h2 style="margin-bottom: 4px;">{{ $task->title }}</h2>
                <span class="badge badge-{{ $task->priority_color }}">{{ ucfirst($task->priority) }}</span>
                <span class="badge badge-{{ $task->status_color }}">{{ $task->status_label }}</span>
                @if($task->is_late && $task->status === 'completed')
                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Terlambat</span>
                @endif
            </div>
            <p class="text-muted">Diberikan oleh {{ $task->assignedBy->name }}</p>
        </div>
        @if(auth()->user()->canManage())
        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endif
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Detail Tugas</h3>
            </div>
            
            <div style="display: grid; gap: 20px;">
                <div>
                    <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Deskripsi</label>
                    <p style="line-height: 1.7;">{{ $task->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                
                <div class="grid-2">
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Siswa</label>
                        <div class="d-flex align-center gap-2">
                            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                {{ strtoupper(substr($task->intern->user->name ?? 'N', 0, 1)) }}
                            </div>
                            <strong>{{ $task->intern->user->name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Deadline</label>
                        @if($task->deadline)
                            <strong>{{ $task->deadline->format('d M Y') }}</strong>
                            @if($task->deadline_time)
                                <span class="text-muted">pukul {{ $task->deadline_time }}</span>
                            @endif
                            @if($task->isOverdue())
                                <span class="badge badge-danger" style="margin-left: 8px;">Lewat!</span>
                            @endif
                        @else
                            <span class="text-muted">Tidak ada deadline</span>
                        @endif
                    </div>
                </div>
                
                <div class="grid-2">
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Estimasi Waktu</label>
                        <strong>{{ $task->estimated_hours ?? '-' }} Jam</strong>
                    </div>
                    <div>
                        <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Metode Pengumpulan</label>
                        <strong>
                            @if($task->submission_type === 'github')
                                <i class="fab fa-github"></i> Link GitHub
                            @elseif($task->submission_type === 'file')
                                <i class="fas fa-folder"></i> Upload File
                            @else
                                <i class="fas fa-layer-group"></i> GitHub / File
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock"></i> Timeline</h3>
            </div>
            
            <div style="position: relative; padding-left: 30px;">
                <div style="position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: var(--border-color);"></div>
                
                <div style="margin-bottom: 24px; position: relative;">
                    <div style="position: absolute; left: -26px; width: 14px; height: 14px; background: #6366f1; border-radius: 50%;"></div>
                    <div class="text-muted" style="font-size: 12px;">Dibuat</div>
                    <strong>{{ $task->created_at->format('d M Y H:i') }}</strong>
                </div>
                
                @if($task->started_at)
                <div style="margin-bottom: 24px; position: relative;">
                    <div style="position: absolute; left: -26px; width: 14px; height: 14px; background: #06b6d4; border-radius: 50%;"></div>
                    <div class="text-muted" style="font-size: 12px;">Mulai Dikerjakan</div>
                    <strong>{{ $task->started_at->format('d M Y H:i') }}</strong>
                </div>
                @endif
                
                @if($task->submitted_at)
                <div style="margin-bottom: 24px; position: relative;">
                    <div style="position: absolute; left: -26px; width: 14px; height: 14px; background: {{ $task->is_late ? '#f59e0b' : '#22c55e' }}; border-radius: 50%;"></div>
                    <div class="text-muted" style="font-size: 12px;">Dikumpulkan</div>
                    <strong>{{ $task->submitted_at->format('d M Y H:i') }}</strong>
                    @if($task->is_late)
                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 10px;">Terlambat</span>
                    @else
                        <span class="badge badge-success" style="margin-left: 8px; font-size: 10px;">Tepat Waktu</span>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Submission Section for Interns -->
    @if(auth()->user()->isIntern() && $task->status !== 'completed')
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-paper-plane"></i> Kumpulkan Tugas</h3>
        </div>
        
        @if($task->isOverdue())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian!</strong> Deadline sudah lewat. Pengumpulan akan dihitung terlambat.
        </div>
        @endif
        
        <form action="{{ route('tasks.submit', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if(in_array($task->submission_type, ['github', 'both']))
            <div class="form-group">
                <label class="form-label">
                    <i class="fab fa-github"></i> Link GitHub Repository
                    @if($task->submission_type === 'github') * @endif
                </label>
                <input type="url" name="github_link" class="form-control" value="{{ old('github_link') }}" 
                    placeholder="https://github.com/username/repository"
                    {{ $task->submission_type === 'github' ? 'required' : '' }}>
                <small class="text-muted">Contoh: https://github.com/nama-anda/nama-repo</small>
            </div>
            @endif
            
            @if(in_array($task->submission_type, ['file', 'both']))
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-upload"></i> Upload File
                    @if($task->submission_type === 'file') * @endif
                </label>
                <input type="file" name="submission_file" class="form-control" 
                    {{ $task->submission_type === 'file' ? 'required' : '' }}>
                <small class="text-muted">Format: ZIP, RAR, PDF, DOC, atau file lainnya. Maksimal 50MB.</small>
            </div>
            @endif
            
            <div class="form-group">
                <label class="form-label"><i class="fas fa-comment"></i> Catatan Pengumpulan</label>
                <textarea name="submission_notes" class="form-control" rows="3" placeholder="Tambahkan catatan atau keterangan...">{{ old('submission_notes') }}</textarea>
            </div>
            
            <div class="d-flex gap-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i> Kumpulkan Tugas
                </button>
                <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-play"></i> Mulai Kerjakan
                    </button>
                </form>
            </div>
        </form>
    </div>
    @endif

    <!-- Submitted Task Info -->
    @if($task->status === 'completed')
    <div class="card mt-6" style="background: {{ $task->is_late ? 'rgba(245, 158, 11, 0.1)' : 'rgba(34, 197, 94, 0.1)' }}; border-color: {{ $task->is_late ? 'rgba(245, 158, 11, 0.3)' : 'rgba(34, 197, 94, 0.3)' }};">
        <div class="d-flex align-center gap-4 mb-4">
            <div style="font-size: 48px;">
                @if($task->is_late)
                    <i class="fas fa-clock" style="color: var(--warning);"></i>
                @else
                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                @endif
            </div>
            <div>
                <h3 style="margin-bottom: 4px;">{{ $task->is_late ? 'Dikumpulkan Terlambat' : 'Dikumpulkan Tepat Waktu' }}</h3>
                <p class="text-muted">
                    Dikumpulkan: {{ $task->submitted_at?->format('d M Y H:i') ?? $task->completed_at?->format('d M Y H:i') }}
                </p>
            </div>
        </div>
        
        <!-- Submission Details -->
        @if($task->github_link || $task->submission_file || $task->submission_notes)
        <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-top: 16px;">
            <h4 style="margin-bottom: 16px;"><i class="fas fa-file-alt"></i> Detail Pengumpulan</h4>
            
            @if($task->github_link)
            <div class="mb-4">
                <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Link GitHub</label>
                <a href="{{ $task->github_link }}" target="_blank" class="btn btn-sm btn-secondary">
                    <i class="fab fa-github"></i> {{ $task->github_link }}
                </a>
            </div>
            @endif
            
            @if($task->submission_file)
            <div class="mb-4">
                <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">File Upload</label>
                <a href="{{ Storage::url('submissions/' . $task->submission_file) }}" target="_blank" class="btn btn-sm btn-secondary">
                    <i class="fas fa-download"></i> {{ $task->submission_file }}
                </a>
            </div>
            @endif
            
            @if($task->submission_notes)
            <div>
                <label class="text-muted" style="font-size: 12px; display: block; margin-bottom: 4px;">Catatan</label>
                <p style="line-height: 1.7;">{{ $task->submission_notes }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif

    @if($task->assessment)
    <div class="card mt-6">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-star"></i> Penilaian</h3>
        </div>
        <div class="grid-3">
            <div class="text-center">
                <div style="font-size: 48px; font-weight: 800; color: var(--accent-primary);">{{ $task->assessment->average_score }}</div>
                <div class="text-muted">Skor Rata-rata</div>
            </div>
            <div class="text-center">
                <div style="font-size: 48px; font-weight: 800;">
                    <span class="badge badge-{{ $task->assessment->grade_color }}" style="font-size: 32px; padding: 16px 32px;">
                        {{ $task->assessment->grade }}
                    </span>
                </div>
                <div class="text-muted">Grade</div>
            </div>
            <div class="text-center">
                <div class="text-muted">Dinilai oleh</div>
                <strong>{{ $task->assessment->assessedBy->name }}</strong>
            </div>
        </div>
    </div>
    @elseif(auth()->user()->canManage() && $task->status === 'completed')
    <div class="card mt-6" style="text-align: center; padding: 40px;">
        <h4 style="margin-bottom: 16px;">Tugas Selesai - Berikan Penilaian</h4>
        <a href="{{ route('assessments.create') }}?task_id={{ $task->id }}&intern_id={{ $task->intern_id }}" class="btn btn-primary">
            <i class="fas fa-star"></i> Beri Penilaian
        </a>
    </div>
    @endif
</div>
@endsection
