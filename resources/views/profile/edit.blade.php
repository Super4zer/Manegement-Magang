@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="slide-up">
    <div class="d-flex align-center gap-4 mb-6">
        <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-icon">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="margin-bottom: 4px;">Edit Profil</h2>
            <p class="text-muted">Perbarui informasi akun Anda</p>
        </div>
    </div>

    <div class="grid-2">
        <!-- Edit Profile Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit"></i> Informasi Profil</h3>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Avatar (Opsional)</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-lock"></i> Ubah Password</h3>
            </div>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Password Saat Ini *</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password Baru *</label>
                    <input type="password" name="password" class="form-control" required>
                    <small class="text-muted">Minimal 8 karakter</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
