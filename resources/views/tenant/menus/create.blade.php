@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-maroon">
                        {{ isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru' }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ isset($menu) ? route('tenant.menus.update', $menu->id_menu) : route('tenant.menus.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($menu))
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <!-- Image Upload & Preview -->
                            <div class="col-12 text-center mb-3">
                                <div class="position-relative d-inline-block">
                                    <img id="imagePreview" 
                                         src="{{ isset($menu) && $menu->image ? (Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://placehold.co/300x200?text=Upload+Foto' }}" 
                                         class="rounded shadow-sm object-fit-cover border" 
                                         style="width: 300px; height: 200px;" 
                                         alt="Preview">
                                    <label for="image" class="position-absolute bottom-0 end-0 bg-white p-2 rounded-circle shadow border cursor-pointer">
                                        <i class="bi bi-camera-fill text-maroon"></i>
                                    </label>
                                </div>
                                <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewImage(event)">
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Format: JPG, PNG. Maks: 2MB.</div>
                            </div>

                            <!-- Name -->
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nama Menu</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $menu->name ?? '') }}" required placeholder="Contoh: Nasi Goreng Spesial">
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select name="id_category" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id_category }}" {{ (old('id_category', $menu->id_category ?? '') == $category->id_category) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_category')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $menu->price ?? '') }}" required min="0">
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stok Harian</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', $menu->stock ?? '') }}" required min="0">
                                @error('stock')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3" required placeholder="Deskripsi singkat menu...">{{ old('description', $menu->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('tenant.menus.index') }}" class="btn btn-light border">Batal</a>
                                <button type="submit" class="btn btn-primary fw-bold" style="background-color: #800000; border-color: #800000;">
                                    {{ isset($menu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<style>
    .text-maroon { color: #800000; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
