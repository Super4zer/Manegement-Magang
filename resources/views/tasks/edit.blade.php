@extends('layouts.app')

@section('title', 'Edit Tugas')

@section('content')
    <div class="slide-up">
        <div class="d-flex align-center gap-4 mb-6">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-icon">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 style="margin-bottom: 4px;">Edit Tugas</h2>
                <p class="text-muted">{{ $task->title }}</p>
            </div>
        </div>

        <div class="card" style="max-width: 800px;">
            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Judul Tugas *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control"
                        rows="4">{{ old('description', $task->description) }}</textarea>
                </div>

                <!-- Reassign Intern -->
                <div class="form-group">
                    <label class="form-label">Ditugaskan Kepada</label>
                    <select name="intern_id" class="form-control">
                        <option value="">-- Tidak Ada Siswa (Tugas Kosong) --</option>
                        <option value="all_active" style="font-weight: bold;">
                            🎯 Semua Siswa Aktif ({{ $interns->count() }} siswa)
                        </option>
                        <optgroup label="Pilih Siswa Tertentu">
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}" {{ old('intern_id', $task->intern_id) == $intern->id ? 'selected' : '' }}>
                                    {{ $intern->user->name }} - {{ $intern->school }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    @if($task->intern_id && !$task->intern)
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i> Siswa sebelumnya telah dihapus dari sistem
                        </small>
                    @endif
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>
                                Menunggu</option>
                            <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>
                                Selesai</option>
                            <option value="revision" {{ old('status', $task->status) === 'revision' ? 'selected' : '' }}>
                                Revisi</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Prioritas *</label>
                        <select name="priority" class="form-control" required>
                            <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>🟢 Rendah
                            </option>
                            <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>🟡
                                Sedang</option>
                            <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>🔴 Tinggi
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Deadline Tanggal</label>
                        <input type="date" name="deadline" class="form-control"
                            value="{{ old('deadline', $task->deadline?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deadline Waktu</label>
                        <input type="time" name="deadline_time" class="form-control"
                            value="{{ old('deadline_time', $task->deadline_time ?? '23:59') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Estimasi Waktu (Jam)</label>
                        <input type="number" name="estimated_hours" class="form-control"
                            value="{{ old('estimated_hours', $task->estimated_hours) }}" min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Metode Pengumpulan</label>
                        <select name="submission_type" class="form-control">
                            <option value="github" {{ old('submission_type', $task->submission_type) === 'github' ? 'selected' : '' }}>
                                📌 Link GitHub
                            </option>
                            <option value="file" {{ old('submission_type', $task->submission_type) === 'file' ? 'selected' : '' }}>
                                📁 Upload File
                            </option>
                            <option value="both" {{ old('submission_type', $task->submission_type) === 'both' ? 'selected' : '' }}>
                                📦 Keduanya
                            </option>
                        </select>
                    </div>
                </div>

                @if($task->started_at || $task->completed_at)
                    <div class="card mt-4" style="background: var(--bg-tertiary);">
                        <h4 style="margin-bottom: 12px;"><i class="fas fa-clock"></i> Tracking Waktu</h4>
                        <div class="grid-2">
                            <div>
                                <label class="text-muted" style="font-size: 12px;">Mulai Dikerjakan</label>
                                <div>{{ $task->started_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="text-muted" style="font-size: 12px;">Selesai</label>
                                <div>{{ $task->completed_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                        @if($task->submitted_at)
                            <div class="mt-4">
                                <label class="text-muted" style="font-size: 12px;">Status Pengumpulan</label>
                                <div>
                                    @if($task->is_late)
                                        <span class="badge badge-warning">Terlambat</span>
                                    @else
                                        <span class="badge badge-success">Tepat Waktu</span>
                                    @endif
                                    - {{ $task->submitted_at->format('d M Y H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if($task->github_link || $task->submission_file)
                    <div class="card mt-4" style="background: var(--bg-tertiary);">
                        <h4 style="margin-bottom: 12px;"><i class="fas fa-file-alt"></i> Detail Pengumpulan</h4>
                        @if($task->github_link)
                            <div class="mb-4">
                                <label class="text-muted" style="font-size: 12px;">Link GitHub</label>
                                <div><a href="{{ $task->github_link }}" target="_blank">{{ $task->github_link }}</a></div>
                            </div>
                        @endif
                        @if($task->submission_file)
                            <div>
                                <label class="text-muted" style="font-size: 12px;">File Upload</label>
                                <div><a href="{{ Storage::url('submissions/' . $task->submission_file) }}"
                                        target="_blank">{{ $task->submission_file }}</a></div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="d-flex gap-4 mt-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
