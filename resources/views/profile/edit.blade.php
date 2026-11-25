@extends('layouts.app')

@section('title', 'Profil & Pengaturan — TelyuCan')

@section('content')
@php
    $roleLabel = match ($user->role) {
        'customer' => 'Pelanggan',
        'tenant' => 'Tenant',
        default => 'Admin',
    };
@endphp

<x-page-banner
    title="Profil & Pengaturan Akun"
    subtitle="Perbarui informasi profil, foto akun, atau kata sandi Anda."
    :back-url="url()->previous()"
    back-label="Kembali"
/>

<section class="tc-operate-sheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="tc-card p-4 p-md-5">
                    <x-flash-alerts />

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="d-flex flex-column flex-sm-row align-items-center gap-4 pb-4 mb-4 border-bottom">
                            <div class="position-relative flex-shrink-0">
                                <x-user-avatar :user="$user" :size="104" rounded="rounded-4" id="avatarPreview" class="border" />
                                <label for="avatar" class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                                       style="width: 34px; height: 34px; background: var(--tc-obsidian); cursor: pointer;" title="Ganti foto profil">
                                    <i class="bi bi-camera-fill small"></i>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" data-image-preview="#avatarPreview">
                                </label>
                            </div>
                            <div class="text-center text-sm-start">
                                <h2 class="tc-display h5 fw-bold mb-1">{{ $user->name }}</h2>
                                <p class="text-secondary small mb-2">Klik ikon kamera untuk mengganti foto profil (JPG/PNG, maks. 2 MB).</p>
                                <span class="tc-badge tc-badge-neutral">Peran: {{ $roleLabel }}</span>
                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
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
                                <label for="identity_number" class="form-label">Nomor Identitas (NIM / NIP)</label>
                                <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}">
                                @error('identity_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Peran Akun</label>
                                <input type="text" class="form-control" style="background: var(--tc-surface-muted);" value="{{ $roleLabel }}" readonly disabled>
                                <div class="form-text">Peran akun dikelola oleh administrator.</div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h3 class="tc-display h6 fw-bold mb-1">Ubah Kata Sandi</h3>
                        <p class="text-secondary small mb-3">Biarkan kosong jika tidak ingin mengganti kata sandi.</p>

                        <div class="row g-3">
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

                        <div class="d-flex flex-column-reverse flex-sm-row justify-content-sm-end gap-2 mt-4 pt-2">
                            <a href="{{ url()->previous() }}" class="btn btn-tc-ghost px-4 text-decoration-none text-center">Batal</a>
                            <button type="submit" class="btn btn-tc-primary px-4">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
