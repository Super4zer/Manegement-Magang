@extends('layouts.app')

@section('title', 'Penilaian')

@section('content')
<div class="slide-up">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="margin-bottom: 4px;">Penilaian Pekerjaan</h2>
            <p class="text-muted">Evaluasi performa siswa magang</p>
        </div>
        <a href="{{ route('assessments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Penilaian
        </a>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('assessments.index') }}" method="GET" class="filter-bar">
        <div class="filter-group" style="max-width: 250px;">
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
        <div class="filter-group" style="max-width: 120px; display: flex; align-items: flex-end;">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <div class="card">
        @if($assessments->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h4 class="empty-state-title">Belum Ada Penilaian</h4>
                <p class="empty-state-text">Mulai dengan memberikan penilaian untuk siswa magang.</p>
                <a href="{{ route('assessments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Penilaian
                </a>
            </div>
        @else
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tugas</th>
                            <th>Kualitas</th>
                            <th>Kecepatan</th>
                            <th>Inisiatif</th>
                            <th>Rata-rata</th>
                            <th>Grade</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessments as $assessment)
                        <tr>
                            <td>
                                <div class="d-flex align-center gap-2">
                                    <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                        {{ strtoupper(substr($assessment->intern->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                    {{ $assessment->intern->user->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ Str::limit($assessment->task->title ?? 'Penilaian Umum', 25) }}</td>
                            <td>
                                <div class="progress" style="width: 60px; height: 6px;">
                                    <div class="progress-bar" style="width: {{ $assessment->quality_score }}%;"></div>
                                </div>
                                <span style="font-size: 12px;">{{ $assessment->quality_score }}</span>
                            </td>
                            <td>
                                <div class="progress" style="width: 60px; height: 6px;">
                                    <div class="progress-bar" style="width: {{ $assessment->speed_score }}%; background: var(--success);"></div>
                                </div>
                                <span style="font-size: 12px;">{{ $assessment->speed_score }}</span>
                            </td>
                            <td>
                                <div class="progress" style="width: 60px; height: 6px;">
                                    <div class="progress-bar" style="width: {{ $assessment->initiative_score }}%; background: var(--warning);"></div>
                                </div>
                                <span style="font-size: 12px;">{{ $assessment->initiative_score }}</span>
                            </td>
                            <td>
                                <strong style="font-size: 18px;">{{ $assessment->average_score }}</strong>
                            </td>
                            <td>
                                <span class="badge badge-{{ $assessment->grade_color }}" style="font-size: 16px; padding: 8px 16px;">
                                    {{ $assessment->grade }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('assessments.destroy', $assessment) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penilaian ini?')">
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
                {{ $assessments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
