@extends('layouts.app')

@section('title', 'Tambah Anggota Magang')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('interns.index') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="margin-bottom: 4px;">Tambah Anggota Magang</h2>
            <p class="text-muted">Isi data lengkap siswa magang baru</p>
        </div>
    </div>

    <div class="card" style="max-width: 800px;">
        <form action="{{ route('interns.store') }}" method="POST">
            @csrf
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">NIS (Opsional)</label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Asal Sekolah *</label>
                    <input type="text" name="school" class="form-control" value="{{ old('school') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Jurusan *</label>
                    <input type="text" name="department" class="form-control" value="{{ old('department') }}" required>
                </div>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Pembimbing</label>
                    <select name="supervisor_id" class="form-control">
                        <option value="">-- Pilih Pembimbing --</option>
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->name }} ({{ $supervisor->role }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
            </div>
            
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai *</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai *</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                </div>
            </div>
            
            <div class="d-flex gap-4 mt-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('interns.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
