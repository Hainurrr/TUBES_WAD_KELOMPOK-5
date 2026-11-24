@extends('layouts.admin')

@section('title', 'Tambah Tenant — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-tc-ghost rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" aria-label="Kembali ke daftar tenant">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="tc-display h4 fw-bold mb-0">Tambah Tenant Baru</h1>
            <p class="text-secondary small mb-0">Daftarkan gerai kantin baru dan hubungkan dengan akun pengelola tenant.</p>
        </div>
    </div>

    <form action="{{ route('admin.tenants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="tc-card p-4 mb-4">
                    <h2 class="tc-display h6 fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge text-maroon"></i>Informasi Pemilik &amp; Akun
                    </h2>

                    <div class="mb-3">
                        <label for="id_user" class="form-label">Hubungkan Akun Pengguna</label>
                        <select name="id_user" id="id_user" class="form-select @error('id_user') is-invalid @enderror">
                            <option value="">Pilih akun pengguna...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id_user }}" {{ old('id_user') == $user->id_user ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Akun terpilih akan mendapatkan akses ke Panel Tenant.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="owner_name" class="form-label">Nama Lengkap Pemilik</label>
                            <input type="text" class="form-control @error('owner_name') is-invalid @enderror" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Nama lengkap pemilik gerai">
                            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Nomor WhatsApp / HP</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: var(--tc-surface-muted); border-color: var(--tc-border); border-radius: 14px 0 0 14px;">+62</span>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="812xxxx">
                            </div>
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="tc-card p-4 mb-4">
                    <h2 class="tc-display h6 fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-shop text-maroon"></i>Detail Gerai Kantin
                    </h2>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Nama Gerai / Tenant</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ayam Geprek Bu Rini">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_select" class="form-label">Lokasi Area Kantin</label>
                            <select class="form-select" id="category_select" name="category_select">
                                <option value="">Pilih lokasi...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category_select') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="Kantin TULT" {{ old('category_select') == 'Kantin TULT' ? 'selected' : '' }}>Kantin TULT</option>
                                <option value="Kantin GKU" {{ old('category_select') == 'Kantin GKU' ? 'selected' : '' }}>Kantin GKU</option>
                                <option value="Kantin Asrama" {{ old('category_select') == 'Kantin Asrama' ? 'selected' : '' }}>Kantin Asrama</option>
                                <option value="Kantin Open Library" {{ old('category_select') == 'Kantin Open Library' ? 'selected' : '' }}>Kantin Open Library</option>
                                <option value="Kantin Fakultas" {{ old('category_select') == 'Kantin Fakultas' ? 'selected' : '' }}>Kantin Fakultas</option>
                                <option value="Lainnya" {{ old('category_select') == 'Lainnya' ? 'selected' : '' }}>Lainnya (Tulis Baru)</option>
                            </select>

                            <div class="mt-2 d-none" id="custom_category_wrapper">
                                <input type="text" class="form-control" id="category_custom" name="category_custom" value="{{ old('category_custom') }}" placeholder="Masukkan nama lokasi baru">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Menu andalan atau informasi singkat gerai">{{ old('description') }}</textarea>
                            <div class="form-text">Maksimal 100 karakter.</div>
                        </div>
                    </div>
                </div>

                <div class="tc-card p-4">
                    <h2 class="tc-display h6 fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-image text-maroon"></i>Foto Banner Gerai
                    </h2>
                    <input class="form-control" type="file" id="banner_image" name="banner_image" accept="image/*">
                    <div class="form-text">Format JPG, PNG, atau GIF (maks. 2 MB). Diunggah ke Cloudinary.</div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="tc-card p-4 sticky-top" style="top: 86px; z-index: 1;">
                    <h2 class="tc-display h6 fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt text-maroon"></i>Alamat &amp; Wilayah
                    </h2>

                    <div class="mb-3">
                        <label for="provinceSelect" class="form-label">Provinsi</label>
                        <select class="form-select" id="provinceSelect">
                            <option value="">Memuat provinsi...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="regencySelect" class="form-label">Kota / Kabupaten</label>
                        <select class="form-select" id="regencySelect" disabled>
                            <option value="">Pilih provinsi terlebih dahulu</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="districtSelect" class="form-label">Kecamatan</label>
                        <select class="form-select" id="districtSelect" disabled>
                            <option value="">Pilih kota terlebih dahulu</option>
                        </select>
                    </div>

                    <input type="hidden" name="city" id="finalCity" value="{{ old('city') }}">

                    <div class="mb-4">
                        <label for="address" class="form-label">Detail Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Nama gedung, lantai, atau detail alamat" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-tc-primary w-100 py-3">
                            <i class="bi bi-check2-circle me-2"></i>Simpan Tenant
                        </button>
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-tc-ghost w-100 py-2 text-decoration-none text-center">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('provinceSelect');
        const regencySelect = document.getElementById('regencySelect');
        const districtSelect = document.getElementById('districtSelect');
        const finalCityInput = document.getElementById('finalCity');
        const addressInput = document.getElementById('address');

        let selectedProvinceName = "";
        let selectedRegencyName = "";
        let selectedDistrictName = "";

        const baseURL = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        fetch(`${baseURL}/provinces.json`)
            .then(res => res.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                data.forEach(prov => {
                    const opt = document.createElement('option');
                    opt.value = prov.id;
                    opt.textContent = prov.name;
                    opt.dataset.name = prov.name;
                    provinceSelect.appendChild(opt);
                });
            })
            .catch(() => { provinceSelect.innerHTML = '<option value="">Gagal memuat wilayah</option>'; });

        provinceSelect.addEventListener('change', function() {
            const provId = this.value;
            selectedProvinceName = this.options[this.selectedIndex].dataset.name || "";
            regencySelect.innerHTML = '<option value="">Memuat...</option>';
            regencySelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Pilih kota terlebih dahulu</option>';
            districtSelect.disabled = true;

            if (provId) {
                fetch(`${baseURL}/regencies/${provId}.json`)
                    .then(res => res.json())
                    .then(data => {
                        regencySelect.innerHTML = '<option value="">Pilih Kota / Kabupaten</option>';
                        data.forEach(reg => {
                            const opt = document.createElement('option');
                            opt.value = reg.id;
                            opt.textContent = reg.name;
                            opt.dataset.name = reg.name;
                            regencySelect.appendChild(opt);
                        });
                        regencySelect.disabled = false;
                    });
            }
        });

        regencySelect.addEventListener('change', function() {
            const regId = this.value;
            selectedRegencyName = this.options[this.selectedIndex].dataset.name || "";
            if (selectedRegencyName && selectedProvinceName) {
                finalCityInput.value = `${selectedRegencyName}, ${selectedProvinceName}`;
            }

            districtSelect.innerHTML = '<option value="">Memuat...</option>';
            districtSelect.disabled = true;

            if (regId) {
                fetch(`${baseURL}/districts/${regId}.json`)
                    .then(res => res.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.forEach(dist => {
                            const opt = document.createElement('option');
                            opt.value = dist.id;
                            opt.textContent = dist.name;
                            opt.dataset.name = dist.name;
                            districtSelect.appendChild(opt);
                        });
                        districtSelect.disabled = false;
                    });
            }
        });

        districtSelect.addEventListener('change', function() {
            selectedDistrictName = this.options[this.selectedIndex].dataset.name || "";
            if (addressInput.value.trim() === "" && selectedDistrictName) {
                addressInput.value = `Kec. ${selectedDistrictName}`;
            }
        });

        const categorySelect = document.getElementById('category_select');
        const customCategoryWrapper = document.getElementById('custom_category_wrapper');
        const customCategoryInput = document.getElementById('category_custom');

        function toggleCustomCategory() {
            if (categorySelect && categorySelect.value === 'Lainnya') {
                customCategoryWrapper.classList.remove('d-none');
                customCategoryInput.required = true;
            } else if (categorySelect) {
                customCategoryWrapper.classList.add('d-none');
                customCategoryInput.required = false;
            }
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', toggleCustomCategory);
            toggleCustomCategory();
        }
    });
</script>
@endpush
@endsection
