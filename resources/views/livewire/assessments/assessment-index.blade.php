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
    <div class="filter-bar">
        <div class="filter-group" style="max-width: 250px;">
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
    </div>

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
                            <tr wire:key="assessment-{{ $assessment->id }}">
                                <td>
                                    @if($assessment->intern)
                                        <div class="d-flex align-center gap-2">
                                            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 12px;">
                                                {{ strtoupper(substr($assessment->intern->user->name ?? 'N', 0, 1)) }}
                                            </div>
                                            {{ $assessment->intern->user->name ?? 'N/A' }}
                                        </div>
                                    @else
                                        <span class="badge badge-secondary" style="font-size: 11px;">
                                            <i class="fas fa-user-slash"></i> Siswa Dihapus
                                        </span>
                                    @endif
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
                                        <div class="progress-bar"
                                            style="width: {{ $assessment->speed_score }}%; background: var(--success);"></div>
                                    </div>
                                    <span style="font-size: 12px;">{{ $assessment->speed_score }}</span>
                                </td>
                                <td>
                                    <div class="progress" style="width: 60px; height: 6px;">
                                        <div class="progress-bar"
                                            style="width: {{ $assessment->initiative_score }}%; background: var(--warning);">
                                        </div>
                                    </div>
                                    <span style="font-size: 12px;">{{ $assessment->initiative_score }}</span>
                                </td>
                                <td>
                                    <strong style="font-size: 18px;">{{ $assessment->average_score }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $assessment->grade_color }}"
                                        style="font-size: 16px; padding: 8px 16px;">
                                        {{ $assessment->grade }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('assessments.show', $assessment) }}" class="btn btn-sm btn-info"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('assessments.edit', $assessment) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button wire:click="deleteAssessment({{ $assessment->id }})"
                                            wire:confirm="Yakin ingin menghapus penilaian ini?" class="btn btn-sm btn-danger"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
