@extends('layouts.app')

@section('title', 'Edit Presensi')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('attendances.index') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="margin-bottom: 4px;">Edit Presensi</h2>
            <p class="text-muted">{{ $attendance->intern->user->name }} - {{ $attendance->date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('attendances.update', $attendance) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Siswa</label>
                    <input type="text" class="form-control" value="{{ $attendance->intern->user->name }}" disabled>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="text" class="form-control" value="{{ $attendance->date->format('d M Y') }}" disabled>
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Check In</label>
                    <input type="time" name="check_in" class="form-control" value="{{ old('check_in', $attendance->check_in) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Check Out</label>
                    <input type="time" name="check_out" class="form-control" value="{{ old('check_out', $attendance->check_out) }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Status *</label>
                <select name="status" class="form-control" required>
                    <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="sick" {{ old('status', $attendance->status) === 'sick' ? 'selected' : '' }}>Sakit</option>
                    <option value="permission" {{ old('status', $attendance->status) === 'permission' ? 'selected' : '' }}>Izin</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
            </div>
            
            <div class="d-flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
