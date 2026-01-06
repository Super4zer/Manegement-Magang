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
    <div class="filter-bar">
        <div class="filter-group">
            <label>Cari</label>
            <div class="search-input">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                    placeholder="Judul tugas...">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="filter-group" style="max-width: 180px;">
            <label>Status</label>
            <select wire:model.live="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="pending">Belum Mulai</option>
                <option value="in_progress">Dikerjakan</option>
                <option value="completed">Selesai</option>
                <option value="revision">Revisi</option>
            </select>
        </div>
        <div class="filter-group" style="max-width: 150px;">
            <label>Prioritas</label>
            <select wire:model.live="priority" class="form-control">
                <option value="">Semua</option>
                <option value="high">🔴 Tinggi</option>
                <option value="medium">🟡 Sedang</option>
                <option value="low">🟢 Rendah</option>
            </select>
        </div>
        @if(auth()->user()->canManage())
            <div class="filter-group" style="max-width: 200px;">
                <label>Siswa</label>
                <select wire:model.live="intern_id" class="form-control">
                    <option value="">Semua Siswa</option>
                    @foreach($interns as $intern)
                        <option value="{{ $intern->id }}">
                            {{ $intern->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

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
                            <tr wire:key="task-{{ $task->id }}">
                                <td>
                                    <div class="d-flex align-center gap-2">
                                        <strong>{{ Str::limit($task->title, 30) }}</strong>
                                        @if($task->status === 'completed' && $task->is_late)
                                            <span class="badge badge-warning" style="font-size: 10px;"><i
                                                    class="fas fa-clock"></i></span>
                                        @elseif($task->status === 'completed' && !$task->is_late)
                                            <span class="badge badge-success" style="font-size: 10px;"><i
                                                    class="fas fa-check"></i></span>
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
                                        @if($task->intern)
                                            <div class="d-flex align-center gap-2">
                                                <div class="user-avatar" style="width: 28px; height: 28px; font-size: 11px;">
                                                    {{ strtoupper(substr($task->intern->user->name ?? 'N', 0, 1)) }}
                                                </div>
                                                <span style="font-size: 13px;">{{ $task->intern->user->name ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <span class="badge badge-secondary" style="font-size: 11px;">
                                                <i class="fas fa-user-slash"></i> Siswa Dihapus
                                            </span>
                                        @endif
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
                                            <button wire:click="deleteTask({{ $task->id }})"
                                                wire:confirm="Yakin ingin menghapus tugas ini?" class="btn btn-sm btn-danger"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
