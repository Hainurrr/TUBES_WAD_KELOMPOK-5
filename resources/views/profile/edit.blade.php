@extends('layouts.app')

@section('title', 'Profil & Pengaturan — TelyuCan')

@section('content')
<section class="tc-page-banner">
    <div class="container">
        <a href="{{ url()->previous() }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i>Kembali
        </a>
        <h1 class="mb-1" style="font-size: clamp(1.6rem, 2.6vw, 2.1rem);">Profil &amp; Pengaturan Akun</h1>
        <p class="mb-0" style="color: #CBD5E1;">Perbarui informasi profil, foto akun, atau kata sandi Anda.</p>
    </div>
</section>

<div class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="tc-card p-4 p-md-5">
                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-4 alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="d-flex flex-column flex-sm-row align-items-center gap-4 pb-4 mb-4 border-bottom">
                        <div class="position-relative flex-shrink-0">
                            <img src="{{ $user->avatar && $user->avatar !== 'default_avatar.png' ? '/storage/' . $user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=B9112E&color=fff&size=128' }}"
                                 alt="Foto profil {{ $user->name }}"
                                 class="rounded-4 object-fit-cover border"
                                 style="width: 104px; height: 104px;"
                                 id="avatarPreview">
                            <label for="avatar" class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                                   style="width: 34px; height: 34px; background: var(--tc-obsidian); cursor: pointer;" title="Ganti foto profil">
                                <i class="bi bi-camera-fill small"></i>
                                <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        <div class="text-center text-sm-start">
                            <h2 class="tc-display h5 fw-bold mb-1">{{ $user->name }}</h2>
                            <p class="text-secondary small mb-2">Klik ikon kamera untuk mengganti foto profil (JPG/PNG, maks. 2 MB).</p>
                            <span class="tc-badge tc-badge-neutral">
                                Peran: {{ $user->role === 'customer' ? 'Pelanggan' : ($user->role === 'tenant' ? 'Tenant' : 'Admin') }}
                            </span>
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
                            <input type="text" class="form-control" style="background: var(--tc-surface-muted);" value="{{ $user->role === 'customer' ? 'Pelanggan' : ($user->role === 'tenant' ? 'Tenant' : 'Admin') }}" readonly disabled>
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

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-2">
                        <a href="{{ url()->previous() }}" class="btn btn-tc-ghost px-4 text-decoration-none">Batal</a>
                        <button type="submit" class="btn btn-tc-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
