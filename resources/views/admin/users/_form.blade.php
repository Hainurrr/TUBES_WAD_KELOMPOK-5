@php $isEdit = isset($user); @endphp

<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Alamat Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="role" class="form-label">Peran Akun</label>
        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            @if(!$isEdit)
                <option value="">Pilih peran...</option>
            @endif
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="tenant" {{ old('role', $user->role ?? '') == 'tenant' ? 'selected' : '' }}>Tenant</option>
            <option value="customer" {{ old('role', $user->role ?? '') == 'customer' ? 'selected' : '' }}>Pelanggan</option>
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="identity_number" class="form-label">Nomor Identitas (NIM / NIP) <span class="text-muted fw-normal">(Opsional)</span></label>
        <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number', $user->identity_number ?? '') }}">
        @error('identity_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @if($isEdit)
        <div class="col-12">
            <div class="p-3 rounded-3 small text-secondary" style="background: var(--tc-surface-muted);">
                <i class="bi bi-info-circle me-1"></i>Biarkan kolom kata sandi kosong jika tidak ingin mengubah kata sandi pengguna.
            </div>
        </div>
    @endif

    <div class="col-md-6">
        <label for="password" class="form-label">{{ $isEdit ? 'Kata Sandi Baru' : 'Kata Sandi' }}</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" {{ $isEdit ? '' : 'required' }}>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">{{ $isEdit ? 'Konfirmasi Kata Sandi Baru' : 'Konfirmasi Kata Sandi' }}</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" {{ $isEdit ? '' : 'required' }}>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4 pt-2">
    <a href="{{ route('admin.users.index') }}" class="btn btn-tc-ghost px-4 text-decoration-none">Batal</a>
    <button type="submit" class="btn btn-tc-primary px-4">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pengguna' }}</button>
</div>
