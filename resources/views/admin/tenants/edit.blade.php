@extends('layouts.admin')

@section('title', 'Ubah Tenant — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-tc-ghost btn-tc-icon-circle" aria-label="Kembali ke daftar tenant">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="tc-display h4 fw-bold mb-0">Ubah Data Tenant</h1>
            <p class="text-secondary small mb-0">Perbarui detail gerai {{ $tenant->name }}.</p>
        </div>
    </div>

    <form action="{{ route('admin.tenants.update', $tenant->id_tenant) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.tenants._form', ['tenant' => $tenant])
    </form>
</div>
@endsection
