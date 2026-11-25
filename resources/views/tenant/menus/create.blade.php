@extends('layouts.tenant')

@section('title', (isset($menu) ? 'Ubah Menu' : 'Tambah Menu') . ' — Panel Tenant')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('tenant.menus.index') }}" class="btn btn-tc-ghost btn-tc-icon-circle" aria-label="Kembali ke daftar menu">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="tc-display h4 fw-bold mb-0">{{ isset($menu) ? 'Ubah Data Menu' : 'Tambah Menu Baru' }}</h1>
                    <p class="text-secondary small mb-0">Lengkapi nama, kategori, harga, stok, dan foto hidangan gerai Anda.</p>
                </div>
            </div>

            <div class="tc-card p-4 p-md-5">
                <form action="{{ isset($menu) ? route('tenant.menus.update', $menu->id_menu) : route('tenant.menus.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($menu))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-12 mb-2">
                            <label class="form-label d-block">Foto Hidangan</label>
                            <div class="d-flex flex-column flex-sm-row align-items-center gap-4 p-3 rounded-4 border" style="background: var(--tc-surface-muted);">
                                <img id="imagePreview"
                                     src="{{ isset($menu) && $menu->image ? (\Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://placehold.co/300x200/101820/FFFFFF?text=Pratinjau+Foto' }}"
                                     class="rounded-3 object-fit-cover border flex-shrink-0"
                                     style="width: 200px; height: 135px;"
                                     alt="Pratinjau foto menu">
                                <div class="flex-grow-1 w-100">
                                    <input type="file" id="image" name="image" class="form-control mb-2" accept="image/*" data-image-preview="#imagePreview" {{ isset($menu) ? '' : 'required' }}>
                                    <div class="form-text mb-0">Format JPG, PNG, atau GIF. Ukuran maksimal 2 MB.</div>
                                    @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="menuName" class="form-label">Nama Menu</label>
                            <input type="text" id="menuName" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $menu->name ?? '') }}" required placeholder="Contoh: Nasi Goreng Spesial">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="menuCat" class="form-label">Kategori</label>
                            <select id="menuCat" name="id_category" class="form-select @error('id_category') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id_category }}" {{ (old('id_category', $menu->id_category ?? '') == $category->id_category) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="menuPrice" class="form-label">Harga (Rp)</label>
                            <input type="number" id="menuPrice" name="price" class="form-control tc-mono @error('price') is-invalid @enderror" value="{{ old('price', $menu->price ?? '') }}" required min="0">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="menuStock" class="form-label">Stok Harian</label>
                            <input type="number" id="menuStock" name="stock" class="form-control tc-mono @error('stock') is-invalid @enderror" value="{{ old('stock', $menu->stock ?? '') }}" required min="0">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="menuDesc" class="form-label">Deskripsi Singkat</label>
                            <textarea id="menuDesc" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required placeholder="Jelaskan bahan utama, tingkat kepedasan, atau porsi...">{{ old('description', $menu->description ?? '') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('tenant.menus.index') }}" class="btn btn-tc-ghost px-4 text-decoration-none">Batal</a>
                            <button type="submit" class="btn btn-tc-primary px-4">
                                {{ isset($menu) ? 'Simpan Perubahan' : 'Simpan Menu Baru' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
