@extends('layouts.admin')

@section('title', 'Tambah Pengguna — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-tc-ghost btn-tc-icon-circle" aria-label="Kembali ke daftar pengguna">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="tc-display h4 fw-bold mb-0">Tambah Pengguna Baru</h1>
                    <p class="text-secondary small mb-0">Buat akun baru untuk peran Admin, Tenant, atau Pelanggan.</p>
                </div>
            </div>

            <div class="tc-card p-4 p-md-5">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    @include('admin.users._form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
