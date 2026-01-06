@extends('layouts.app')

@section('title', 'Daftar Pekerjaan')

@section('content')
<div class="slide-up">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="margin-bottom: 4px;">Daftar Pekerjaan</h2>
            <p class="text-muted">Kelola tugas untuk siswa magang</p>
        </div>
        @if(auth()->user()->canManage())
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Tugas Baru
        </a>
        @endif
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('tasks.index') }}" method="GET" class="filter-bar">
        <div class="filter-group">
            <label>Cari</label>
            <div class="search-input">
                <input type="text" name="search" class="form-control" placeholder="Judul tugas..." value="{{ request('search') }}">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="filter-group" style="max-width: 180px;">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Belum Mulai</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Dikerjakan</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="revision" {{ request('status') === 'revision' ? 'selected' : '' }}>Revisi</option>
            </select>
        </div>
        <div class="filter-group" style="max-width: 150px;">
            <label>Prioritas</label>
            <select name="priority" class="form-control">
                <option value="">Semua</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>🔴 Tinggi</option>
                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>🟢 Rendah</option>
            </select>
        </div>
        @if(auth()->user()->canManage())
        <div class="filter-group" style="max-width: 200px;">
            <label>Siswa</label>
            <select name="intern_id" class="form-control">
                <option value="">Semua Siswa</option>
                @foreach($interns as $intern)
                    <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>
                        {{ $intern->user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="filter-group" style="max-width: 120px; display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <div class="card">
        @if($tasks->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Tugas</h4>
                <p class="empty-state-text">Mulai dengan menambahkan tugas baru.</p>
                @if(auth()->user()->canManage())
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Tugas
                </a>
                @endif
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tugas</th>
                            @if(auth()->user()->canManage())
                            <th>Siswa</th>
                            @endif
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>
                                <div class="d-flex align-center gap-2">
                                    <strong>{{ Str::limit($task->title, 30) }}</strong>
                                    @if($task->status === 'completed' && $task->is_late)
                                        <span class="badge badge-warning" style="font-size: 10px;"><i class="fas fa-clock"></i></span>
                                    @elseif($task->status === 'completed' && !$task->is_late)
                                        <span class="badge badge-success" style="font-size: 10px;"><i class="fas fa-check"></i></span>
                                    @elseif($task->isOverdue())
                                        <span class="badge badge-danger" style="font-size: 10px;">Lewat!</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size: 12px; margin-top: 4px;">
                                    <span style="margin-right: 8px;">
                                        @if($task->submission_type === 'github')
                                            <i class="fab fa-github"></i>
                                        @elseif($task->submission_type === 'file')
                                            <i class="fas fa-folder"></i>
                                        @else
                                            <i class="fas fa-layer-group"></i>
                                        @endif
                                    </span>
                                    {{ Str::limit($task->description, 40) }}
                                </div>
                            </td>
                            @if(auth()->user()->canManage())
                            <td>
                                <div class="d-flex align-center gap-2">
                                    <div class="user-avatar" style="width: 28px; height: 28px; font-size: 11px;">
                                        {{ strtoupper(substr($task->intern->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <span style="font-size: 13px;">{{ $task->intern->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            @endif
                            <td>
                                <span class="badge badge-{{ $task->priority_color }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->status_color }}">
                                    {{ $task->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($task->deadline)
                                    <div style="font-size: 13px;">
                                        {{ $task->deadline->format('d M Y') }}
                                        @if($task->deadline_time)
                                            <div class="text-muted" style="font-size: 11px;">{{ $task->deadline_time }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->canManage())
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
