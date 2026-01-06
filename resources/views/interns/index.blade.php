@extends('layouts.app')

@section('title', 'Anggota Magang')

@section('content')
<div class="slide-up">
    <!-- Header Actions -->
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="margin-bottom: 4px;">Daftar Anggota Magang</h2>
            <p class="text-muted">Kelola data siswa magang</p>
        </div>
        <a href="{{ route('interns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Anggota
        </a>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('interns.index') }}" method="GET" class="filter-bar">
        <div class="filter-group">
            <label>Cari</label>
            <div class="search-input">
                <input type="text" name="search" class="form-control" placeholder="Nama, email, sekolah..." value="{{ request('search') }}">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="filter-group" style="max-width: 200px;">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <div class="filter-group" style="max-width: 120px; display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="card">
        @if($interns->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Anggota Magang</h4>
                <p class="empty-state-text">Mulai dengan menambahkan anggota magang baru.</p>
                <a href="{{ route('interns.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Anggota
                </a>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Sekolah</th>
                            <th>Jurusan</th>
                            <th>Pembimbing</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interns as $intern)
                        <tr>
                            <td>
                                <div class="d-flex align-center gap-2">
                                    <div class="user-avatar" style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ strtoupper(substr($intern->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $intern->user->name ?? 'N/A' }}</strong>
                                        <div class="text-muted" style="font-size: 12px;">{{ $intern->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $intern->school }}</td>
                            <td>{{ $intern->department }}</td>
                            <td>{{ $intern->supervisor->name ?? '-' }}</td>
                            <td>
                                <div style="font-size: 13px;">
                                    {{ $intern->start_date->format('d M Y') }}
                                    <br>
                                    <span class="text-muted">s/d {{ $intern->end_date->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td>
                                @if($intern->status === 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($intern->status === 'completed')
                                    <span class="badge badge-primary">Selesai</span>
                                @else
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('interns.show', $intern) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('interns.edit', $intern) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('interns.destroy', $intern) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                {{ $interns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
