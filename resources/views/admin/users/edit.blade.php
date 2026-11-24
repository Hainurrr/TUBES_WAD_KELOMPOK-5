@extends('layouts.admin')

@section('title', 'Ubah Pengguna — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-tc-ghost rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Kembali ke daftar pengguna">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="tc-display h4 fw-bold mb-0">Ubah Data Pengguna</h1>
                    <p class="text-secondary small mb-0">Perbarui data akun {{ $user->name }}.</p>
                </div>
            </div>

            <div class="tc-card p-4 p-md-5">
                <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Peran Akun</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="tenant" {{ old('role', $user->role) == 'tenant' ? 'selected' : '' }}>Tenant</option>
                                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Pelanggan</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="identity_number" class="form-label">Nomor Identitas (NIM / NIP) <span class="text-muted fw-normal">(Opsional)</span></label>
                            <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}">
                            @error('identity_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <div class="p-3 rounded-3 small text-secondary" style="background: var(--tc-surface-muted);">
                                <i class="bi bi-info-circle me-1"></i>Biarkan kolom kata sandi kosong jika tidak ingin mengubah kata sandi pengguna.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Kata Sandi Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-tc-ghost px-4 text-decoration-none">Batal</a>
                        <button type="submit" class="btn btn-tc-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
