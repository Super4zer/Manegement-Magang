<div class="slide-up">
    <div class="d-flex justify-between align-center mb-6">
        <div>
            <h2 style="margin-bottom: 4px;">{{ $pageTitle }}</h2>
            <p class="text-muted">Isi data lengkap anggota magang baru</p>
        </div>
        <a href="{{ route('interns.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form wire:submit="save">
            <!-- Akun User -->
            <div class="mb-4">
                <h4 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wide">Informasi Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" wire:model="name"
                            class="form-control @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Ahmad Fauzi">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" wire:model="email"
                            class="form-control @error('email') border-red-500 @enderror"
                            placeholder="email@sekolah.com">
                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if(!$isEditing)
                    <div class="form-group mt-4">
                        <label class="form-label">Password</label>
                        <input type="password" wire:model="password"
                            class="form-control @error('password') border-red-500 @enderror" placeholder="Min. 8 karakter">
                        @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                @endif
            </div>

            <hr class="my-6 border-gray-200">

            <!-- Data Magang -->
            <div class="mb-4">
                <h4 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wide">Data Magang</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">NIS (Nomor Induk Siswa)</label>
                        <input type="text" wire:model="nis" class="form-control @error('nis') border-red-500 @enderror">
                        @error('nis') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" wire:model="phone"
                            class="form-control @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="form-group">
                        <label class="form-label">Asal Sekolah</label>
                        <input type="text" wire:model="school"
                            class="form-control @error('school') border-red-500 @enderror">
                        @error('school') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jurusan</label>
                        <input type="text" wire:model="department"
                            class="form-control @error('department') border-red-500 @enderror"
                            placeholder="Contoh: RPL, TKJ">
                        @error('department') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea wire:model="address" class="form-control @error('address') border-red-500 @enderror"
                        rows="3"></textarea>
                    @error('address') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <!-- Periode & Pembimbing -->
            <div class="mb-6">
                <h4 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wide">Periode & Pembimbing</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" wire:model="start_date"
                            class="form-control @error('start_date') border-red-500 @enderror">
                        @error('start_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" wire:model="end_date"
                            class="form-control @error('end_date') border-red-500 @enderror">
                        @error('end_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="form-group">
                        <label class="form-label">Pembimbing Lapangan</label>
                        <select wire:model="supervisor_id"
                            class="form-control @error('supervisor_id') border-red-500 @enderror">
                            <option value="">Pilih Pembimbing</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                            @endforeach
                        </select>
                        @error('supervisor_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($isEditing)
                        <div class="form-group">
                            <label class="form-label">Status Magang</label>
                            <select wire:model="status" class="form-control">
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan/Berhenti</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('interns.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <i class="fas fa-save"></i> {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Data' }}
                    </span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
