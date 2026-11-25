@extends('layouts.admin')

@section('title', 'Tambah Tenant — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-tc-ghost btn-tc-icon-circle" aria-label="Kembali ke daftar tenant">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="tc-display h4 fw-bold mb-0">Tambah Tenant Baru</h1>
            <p class="text-secondary small mb-0">Daftarkan gerai kantin baru dan hubungkan dengan akun pengelola tenant.</p>
        </div>
    </div>

    <form action="{{ route('admin.tenants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.tenants._form')
    </form>
</div>
@endsection
