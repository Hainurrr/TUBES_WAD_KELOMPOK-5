@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark">Edit Profil & Pengaturan</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar && $user->avatar !== 'default_avatar.png' ? '/storage/' . $user->avatar : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                     alt="Avatar" 
                                     class="rounded-circle object-fit-cover border shadow-sm"
                                     style="width: 120px; height: 120px;"
                                     id="avatarPreview">
                                <label for="avatar" class="position-absolute bottom-0 end-0 bg-maroon text-white rounded-circle p-2 shadow-sm" style="cursor: pointer;">
                                    <i class="bi bi-camera-fill"></i>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>
                            <div class="form-text mt-2">Klik ikon kamera untuk mengganti foto (Max 2MB)</div>
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <script>
                            function previewImage(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        document.getElementById('avatarPreview').src = e.target.result;
                                    }
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold small">Nama Lengkap</label>
                                <input type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold small">Email Address</label>
                                <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="identity_number" class="form-label fw-bold small">Nomor Identitas (NIM/NIP)</label>
                                <input type="text" class="form-control rounded-3 @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}">
                                @error('identity_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Role (Jabatan)</label>
                                <input type="text" class="form-control rounded-3 bg-light" value="{{ ucfirst($user->role) }}" readonly disabled>
                                <div class="form-text">Role tidak dapat diubah.</div>
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock me-2"></i>Ganti Password</h6>
                        <div class="alert alert-light border rounded-3 small text-secondary mb-3">
                            <i class="bi bi-info-circle me-1"></i> Kosongkan jika tidak ingin mengganti password.
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold small">Password Baru</label>
                                <input type="password" class="form-control rounded-3 @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold small">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-2">
                            <a href="{{ url()->previous() }}" class="btn btn-light rounded-pill px-4 fw-bold me-2">Batal</a>
                            <button type="submit" class="btn btn-maroon rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
