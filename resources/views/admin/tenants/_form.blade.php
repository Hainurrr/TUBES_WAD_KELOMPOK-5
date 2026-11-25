@php
    $isEdit = isset($tenant);
    $staticCats = collect(['Kantin TULT', 'Kantin GKU', 'Kantin Asrama', 'Kantin Open Library', 'Kantin Fakultas']);
    $mergedCategories = $categories->merge($staticCats)->unique();
    $currentCategory = old('category_select', $isEdit ? $tenant->category : '');
    $isKnown = $mergedCategories->contains($currentCategory);
    $isOther = !$isKnown && !empty($currentCategory);
    $showCustom = $isOther || old('category_select') === 'Lainnya';
@endphp

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
                        <option value="{{ $user->id_user }}" {{ old('id_user', $tenant->id_user ?? '') == $user->id_user ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('id_user') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if(!$isEdit)
                    <div class="form-text">Akun terpilih akan mendapatkan akses ke Panel Tenant.</div>
                @endif
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="owner_name" class="form-label">Nama Lengkap Pemilik</label>
                    <input type="text" class="form-control @error('owner_name') is-invalid @enderror" id="owner_name" name="owner_name" value="{{ old('owner_name', $tenant->owner_name ?? '') }}" required placeholder="Nama lengkap pemilik gerai">
                    @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Nomor WhatsApp / HP</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background: var(--tc-surface-muted); border-color: var(--tc-border); border-radius: 14px 0 0 14px;">+62</span>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $tenant->phone ?? '') }}" required placeholder="812xxxx">
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
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tenant->name ?? '') }}" required placeholder="Contoh: Ayam Geprek Bu Rini">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="category_select" class="form-label">Lokasi Area Kantin</label>
                    <select class="form-select @error('category_select') is-invalid @enderror" id="category_select" name="category_select">
                        <option value="">Pilih lokasi...</option>
                        @foreach($mergedCategories as $cat)
                            <option value="{{ $cat }}" {{ $currentCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                        <option value="Lainnya" {{ $showCustom ? 'selected' : '' }}>Lainnya (Tulis Baru)</option>
                    </select>
                    @error('category_select') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <div class="mt-2 {{ $showCustom ? '' : 'd-none' }}" id="custom_category_wrapper">
                        <input type="text" class="form-control" id="category_custom" name="category_custom" value="{{ old('category_custom', $isOther ? $currentCategory : '') }}" placeholder="Masukkan nama lokasi baru">
                    </div>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi Singkat</label>
                    <textarea class="form-control" id="description" name="description" rows="2" placeholder="Menu andalan atau informasi singkat gerai">{{ old('description', $tenant->description ?? '') }}</textarea>
                    <div class="form-text">Maksimal 100 karakter.</div>
                </div>
            </div>
        </div>

        <div class="tc-card p-4">
            <h2 class="tc-display h6 fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-image text-maroon"></i>Foto Banner Gerai
            </h2>
            @if($isEdit && $tenant->banner_image)
                <div class="mb-3">
                    <img src="{{ \Illuminate\Support\Str::startsWith($tenant->banner_image, 'http') ? $tenant->banner_image : asset('storage/' . $tenant->banner_image) }}"
                         alt="Banner {{ $tenant->name }}" class="rounded-4 object-fit-cover border w-100" style="height: 160px;">
                </div>
            @endif
            <input class="form-control" type="file" id="banner_image" name="banner_image" accept="image/*">
            <div class="form-text">
                {{ $isEdit ? 'Unggah file baru jika ingin mengganti banner saat ini.' : 'Format JPG, PNG, atau GIF (maks. 2 MB).' }}
            </div>
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

            <input type="hidden" name="city" id="finalCity" value="{{ old('city', $tenant->city ?? '') }}">

            <div class="mb-4">
                <label for="address" class="form-label">Detail Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Nama gedung, lantai, atau detail alamat" required>{{ old('address', $tenant->address ?? '') }}</textarea>
            </div>

            <div class="d-flex flex-column gap-2">
                <button type="submit" class="btn btn-tc-primary w-100 py-3">
                    <i class="bi bi-check2-circle me-2"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Tenant' }}
                </button>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-tc-ghost w-100 py-2 text-decoration-none text-center">Batal</a>
            </div>
        </div>
    </div>
</div>
