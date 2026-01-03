@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-light text-secondary me-3 shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0 text-dark">Tambah Tenant Baru</h2>
            </div>

            <form action="{{ route('admin.tenants.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <!-- Left Column: Owner & Basic Info -->
                    <div class="col-lg-8">
                        <!-- Account Section -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold text-maroon"><i class="bi bi-person-badge me-2"></i>Informasi Pemilik</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label for="id_user" class="form-label fw-bold small text-uppercase text-secondary">Link Akun User</label>
                                    <select name="id_user" id="id_user" class="form-select form-select-lg @error('id_user') is-invalid @enderror">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id_user }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text small text-muted"><i class="bi bi-info-circle me-1"></i> User ini akan memiliki akses dashboard tenant.</div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="owner_name" class="form-label">Nama Lengkap Pemilik</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Nama sesuai KTP">
                                        @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Nomor WhatsApp / HP</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">+62</span>
                                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="812xxxx">
                                        </div>
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tenant Details Section -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                             <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold text-maroon"><i class="bi bi-shop me-2"></i>Detail Kantin</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label">Nama Tenant</label>
                                        <input type="text" class="form-control form-control-lg fw-bold" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ayam Geprek Bu Tini">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="category_select" class="form-label">Lokasi Kantin</label>
                                        <select class="form-select" id="category_select" name="category_select">
                                            <option value="">-- Pilih Lokasi --</option>
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
                                        
                                        <!-- Custom Category Input -->
                                        <div class="mt-2 d-none" id="custom_category_wrapper">
                                            <input type="text" class="form-control" id="category_custom" name="category_custom" value="{{ old('category_custom') }}" placeholder="Masukkan Nama Lokasi Baru">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label">Deskripsi Singkat</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="Jualan apa saja?">{{ old('description') }}</textarea>
                                        <div class="form-text small">Maksimal 100 karakter.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Media Section -->
                        <div class="card border-0 shadow-sm rounded-4">
                             <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold text-maroon"><i class="bi bi-card-image me-2"></i>Foto Banner</h6>
                            </div>
                            <div class="card-body p-4 text-center">
                                <div class="border rounded-3 p-4 bg-light mb-3">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-secondary opacity-50"></i>
                                    <p class="text-secondary small mb-0">Upload foto banner agar tenant terlihat menarik</p>
                                </div>
                                <input class="form-control" type="file" id="banner_image" name="banner_image" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Address -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px; z-index: 1;">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold text-maroon"><i class="bi bi-geo-alt me-2"></i>Alamat Asal</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted">Provinsi</label>
                                    <select class="form-select bg-light border-0" id="provinceSelect">
                                        <option value="">Loading...</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted">Kota/Kabupaten</label>
                                    <select class="form-select bg-light border-0" id="regencySelect" disabled>
                                        <option value="">Pilih Provinsi Dulu</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold text-muted">Kecamatan</label>
                                    <select class="form-select bg-light border-0" id="districtSelect" disabled>
                                        <option value="">Pilih Kota Dulu</option>
                                    </select>
                                </div>
                                
                                <input type="hidden" name="city" id="finalCity" value="{{ old('city') }}">
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label small fw-bold text-muted">Detail Alamat</label>
                                    <textarea class="form-control bg-light border-0" id="address" name="address" rows="4" placeholder="Jalan, No. Rumah, RT/RW">{{ old('address') }}</textarea>
                                </div>
                                
                                <hr class="my-4">
                                
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="background-color: #800000; border-color: #800000;">
                                    <i class="bi bi-save me-2"></i>SIMPAN DATA
                                </button>
                                <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-secondary w-100 mt-2 border-0">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<Script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('provinceSelect');
        const regencySelect = document.getElementById('regencySelect');
        const districtSelect = document.getElementById('districtSelect');
        const finalCityInput = document.getElementById('finalCity');
        const addressInput = document.getElementById('address');

        // State variables to hold names
        let selectedProvinceName = "";
        let selectedRegencyName = "";
        let selectedDistrictName = "";

        // API Endpoints
        const baseURL = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        
        // Load Provinces
        fetch(`${baseURL}/provinces.json`)
            .then(res => res.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                data.forEach(prov => {
                    const opt = document.createElement('option');
                    opt.value = prov.id;
                    opt.textContent = prov.name;
                    opt.dataset.name = prov.name;
                    provinceSelect.appendChild(opt);
                });
            });

        // Event: Perubahan Provinsi
        provinceSelect.addEventListener('change', function() {
            const provId = this.value;
            selectedProvinceName = this.options[this.selectedIndex].dataset.name || "";
            
            // Reset elemen anak
            regencySelect.innerHTML = '<option value="">Loading...</option>';
            regencySelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Pilih Kota Dulu</option>';
            districtSelect.disabled = true;

            if (provId) {
                fetch(`${baseURL}/regencies/${provId}.json`)
                    .then(res => res.json())
                    .then(data => {
                        regencySelect.innerHTML = '<option value="">-- Pilih Kota/Kab --</option>';
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

        // Event: Perubahan Kota/Kabupaten
        regencySelect.addEventListener('change', function() {
            const regId = this.value;
            selectedRegencyName = this.options[this.selectedIndex].dataset.name || "";
            
            // Set field kota tersembunyi (Format: "Kota, Provinsi")
            if (selectedRegencyName && selectedProvinceName) {
                finalCityInput.value = `${selectedRegencyName}, ${selectedProvinceName}`;
            }

            // Reset elemen anak
            districtSelect.innerHTML = '<option value="">Loading...</option>';
            districtSelect.disabled = true;

            if (regId) {
                fetch(`${baseURL}/districts/${regId}.json`)
                    .then(res => res.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
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

        // Event: Perubahan Kecamatan
        districtSelect.addEventListener('change', function() {
            selectedDistrictName = this.options[this.selectedIndex].dataset.name || "";
            
            // Isi otomatis alamat jika masih kosong
            if(addressInput.value.trim() === "") {
               addressInput.value = `Kec. ${selectedDistrictName}`;
            }
        });

        // Logika Toggle Kategori
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

        if(categorySelect) {
            categorySelect.addEventListener('change', toggleCustomCategory);
            toggleCustomCategory();
        }
    });
</Script>
@endpush
@endsection
