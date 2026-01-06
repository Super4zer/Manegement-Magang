@extends('layouts.app')

@section('title', 'Tambah Tugas')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="margin-bottom: 4px;">Buat Tugas Baru</h2>
            <p class="text-muted">Berikan tugas untuk siswa magang</p>
        </div>
    </div>

    <div class="card" style="max-width: 900px;">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Judul Tugas *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Contoh: Membuat halaman login">
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail tugas yang harus dikerjakan...">{{ old('description') }}</textarea>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Prioritas *</label>
                    <select name="priority" class="form-control" required>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🟢 Rendah</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🔴 Tinggi</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Estimasi Waktu (Jam)</label>
                    <input type="number" name="estimated_hours" class="form-control" value="{{ old('estimated_hours') }}" min="1" placeholder="Contoh: 8">
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Deadline Tanggal</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deadline Waktu</label>
                    <input type="time" name="deadline_time" class="form-control" value="{{ old('deadline_time', '23:59') }}">
                </div>
            </div>

            <hr style="border-color: var(--border-color); margin: 32px 0;">

            <!-- Submission Type Section -->
            <h3 style="margin-bottom: 20px;"><i class="fas fa-upload"></i> Metode Pengumpulan</h3>
            
            <div class="form-group">
                <label class="form-label">Cara Pengumpulan Tugas *</label>
                <div class="d-flex gap-4" style="flex-wrap: wrap;">
                    <label class="radio-card" style="flex: 1; min-width: 180px;">
                        <input type="radio" name="submission_type" value="github" {{ old('submission_type') === 'github' ? 'checked' : '' }}>
                        <div class="radio-card-content">
                            <i class="fab fa-github"></i>
                            <span>Link GitHub</span>
                            <small>Siswa submit via GitHub link</small>
                        </div>
                    </label>
                    <label class="radio-card" style="flex: 1; min-width: 180px;">
                        <input type="radio" name="submission_type" value="file" {{ old('submission_type') === 'file' ? 'checked' : '' }}>
                        <div class="radio-card-content">
                            <i class="fas fa-folder"></i>
                            <span>Upload File</span>
                            <small>Siswa upload file/folder</small>
                        </div>
                    </label>
                    <label class="radio-card" style="flex: 1; min-width: 180px;">
                        <input type="radio" name="submission_type" value="both" {{ old('submission_type', 'both') === 'both' ? 'checked' : '' }}>
                        <div class="radio-card-content">
                            <i class="fas fa-layer-group"></i>
                            <span>Keduanya</span>
                            <small>GitHub atau File</small>
                        </div>
                    </label>
                </div>
            </div>

            <hr style="border-color: var(--border-color); margin: 32px 0;">

            <!-- Assignment Section -->
            <h3 style="margin-bottom: 20px;"><i class="fas fa-users"></i> Penugasan Siswa</h3>
            
            <div class="form-group">
                <label class="form-label">Berikan Tugas Kepada *</label>
                <div class="d-flex gap-4" style="flex-wrap: wrap;">
                    <label class="radio-card" style="flex: 1; min-width: 200px;">
                        <input type="radio" name="assign_to" value="selected" checked onchange="toggleInternSelection()">
                        <div class="radio-card-content">
                            <i class="fas fa-user-check"></i>
                            <span>Siswa Tertentu</span>
                            <small>Pilih siswa yang akan mendapat tugas</small>
                        </div>
                    </label>
                    <label class="radio-card" style="flex: 1; min-width: 200px;">
                        <input type="radio" name="assign_to" value="all" onchange="toggleInternSelection()">
                        <div class="radio-card-content">
                            <i class="fas fa-users"></i>
                            <span>Semua Siswa Aktif</span>
                            <small>Kirim ke {{ $interns->count() }} siswa</small>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group" id="internSelectionGroup">
                <label class="form-label">Pilih Siswa (bisa lebih dari satu) *</label>
                <div class="intern-selection-grid">
                    @foreach($interns as $intern)
                    <label class="intern-checkbox">
                        <input type="checkbox" name="intern_ids[]" value="{{ $intern->id }}" {{ in_array($intern->id, old('intern_ids', [])) ? 'checked' : '' }}>
                        <div class="intern-checkbox-content">
                            <div class="user-avatar" style="width: 40px; height: 40px; font-size: 16px;">
                                {{ strtoupper(substr($intern->user->name ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $intern->user->name }}</strong>
                                <div class="text-muted" style="font-size: 12px;">{{ $intern->school }}</div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="selectAllInterns()">
                        <i class="fas fa-check-double"></i> Pilih Semua
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAllInterns()">
                        <i class="fas fa-times"></i> Hapus Semua
                    </button>
                </div>
            </div>
            
            <div class="d-flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Kirim Tugas
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .radio-card {
        cursor: pointer;
    }
    
    .radio-card input {
        display: none;
    }
    
    .radio-card-content {
        background: var(--bg-tertiary);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .radio-card-content i {
        font-size: 32px;
        margin-bottom: 12px;
        color: var(--text-muted);
    }
    
    .radio-card-content span {
        display: block;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .radio-card-content small {
        color: var(--text-muted);
        font-size: 12px;
    }
    
    .radio-card input:checked + .radio-card-content {
        border-color: var(--accent-primary);
        background: rgba(99, 102, 241, 0.1);
    }
    
    .radio-card input:checked + .radio-card-content i {
        color: var(--accent-primary);
    }
    
    .intern-selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        max-height: 400px;
        overflow-y: auto;
        padding: 4px;
    }
    
    .intern-checkbox {
        cursor: pointer;
    }
    
    .intern-checkbox input {
        display: none;
    }
    
    .intern-checkbox-content {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: var(--bg-tertiary);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        transition: all 0.2s ease;
    }
    
    .intern-checkbox input:checked + .intern-checkbox-content {
        border-color: var(--accent-primary);
        background: rgba(99, 102, 241, 0.15);
    }
    
    .intern-checkbox:hover .intern-checkbox-content {
        border-color: var(--border-hover);
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleInternSelection() {
        const assignTo = document.querySelector('input[name="assign_to"]:checked').value;
        const group = document.getElementById('internSelectionGroup');
        
        if (assignTo === 'all') {
            group.style.display = 'none';
        } else {
            group.style.display = 'block';
        }
    }
    
    function selectAllInterns() {
        document.querySelectorAll('.intern-checkbox input').forEach(cb => cb.checked = true);
    }
    
    function deselectAllInterns() {
        document.querySelectorAll('.intern-checkbox input').forEach(cb => cb.checked = false);
    }
    
    // Initialize
    toggleInternSelection();
</script>
@endpush
@endsection
