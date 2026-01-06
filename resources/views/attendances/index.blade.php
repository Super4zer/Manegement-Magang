@extends('layouts.app')

@section('title', 'Presensi Magang')

@section('content')
<div class="slide-up">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="margin-bottom: 4px;">Presensi Magang</h2>
            <p class="text-muted">Rekap kehadiran siswa magang</p>
        </div>
        @if(auth()->user()->canManage())
        <a href="{{ route('attendances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Presensi
        </a>
        @endif
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('attendances.index') }}" method="GET" class="filter-bar">
        <div class="filter-group" style="max-width: 200px;">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="filter-group" style="max-width: 200px;">
            <label>Bulan</label>
            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
        </div>
        <div class="filter-group" style="max-width: 180px;">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir</option>
                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                <option value="permission" {{ request('status') === 'permission' ? 'selected' : '' }}>Izin</option>
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
        @if($attendances->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Presensi</h4>
                <p class="empty-state-text">Belum ada data presensi yang tercatat.</p>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            @if(auth()->user()->canManage())
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>
                                <strong>{{ $attendance->date->format('d M Y') }}</strong>
                                <div class="text-muted" style="font-size: 12px;">{{ $attendance->date->format('l') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-center gap-2">
                                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                        {{ strtoupper(substr($attendance->intern->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    {{ $attendance->intern->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                @if($attendance->check_in)
                                    <span style="color: var(--success);">{{ $attendance->check_in }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_out)
                                    <span style="color: var(--info);">{{ $attendance->check_out }}</span>
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
                                <span class="text-muted">{{ Str::limit($attendance->notes, 30) ?? '-' }}</span>
                            </td>
                            @if(auth()->user()->canManage())
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus presensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
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
@endsection
