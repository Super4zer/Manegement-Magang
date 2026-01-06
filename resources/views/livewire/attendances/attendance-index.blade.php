<div class="slide-up">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="margin-bottom: 4px;">Presensi Magang</h2>
            <p class="text-muted">Kelola kehadiran siswa magang</p>
        </div>
        @if(auth()->user()->canManage())
            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Presensi
            </a>
        @endif
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group" style="max-width: 200px;">
            <label>Tanggal</label>
            <input type="date" wire:model.live="date" class="form-control">
        </div>
        <div class="filter-group" style="max-width: 200px;">
            <label>Bulan</label>
            <input type="month" wire:model.live="month" class="form-control">
        </div>
        <div class="filter-group" style="max-width: 180px;">
            <label>Status</label>
            <select wire:model.live="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="present">Hadir</option>
                <option value="late">Terlambat</option>
                <option value="absent">Tidak Hadir</option>
                <option value="sick">Sakit</option>
                <option value="permission">Izin</option>
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
        @if($attendances->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Presensi</h4>
                <p class="empty-state-text">Mulai dengan menambahkan data presensi.</p>
                @if(auth()->user()->canManage())
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Presensi
                    </a>
                @endif
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            @if(auth()->user()->canManage())
                                <th>Siswa</th>
                            @endif
                            <th>Tanggal</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr wire:key="attendance-{{ $attendance->id }}">
                                @if(auth()->user()->canManage())
                                    <td>
                                        @if($attendance->intern)
                                            <div class="d-flex align-center gap-2">
                                                <div class="user-avatar" style="width: 28px; height: 28px; font-size: 11px;">
                                                    {{ strtoupper(substr($attendance->intern->user->name ?? 'N', 0, 1)) }}
                                                </div>
                                                <span style="font-size: 13px;">{{ $attendance->intern->user->name ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <span class="badge badge-secondary" style="font-size: 11px;">
                                                <i class="fas fa-user-slash"></i> Siswa Dihapus
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <td>{{ $attendance->date->format('d M Y') }}</td>
                                <td>
                                    @if($attendance->check_in)
                                        <span style="font-size: 13px;">{{ $attendance->check_in->format('H:i') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_out)
                                        <span style="font-size: 13px;">{{ $attendance->check_out->format('H:i') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $attendance->status_color }}">
                                        {{ $attendance->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 12px;">{{ Str::limit($attendance->notes ?? '-', 30) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-sm btn-primary"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->canManage())
                                            <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button wire:click="deleteAttendance({{ $attendance->id }})"
                                                wire:confirm="Yakin ingin menghapus presensi ini?" class="btn btn-sm btn-danger"
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
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>
