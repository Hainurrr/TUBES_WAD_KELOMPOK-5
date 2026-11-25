@extends('layouts.admin')

@section('title', 'Ubah Pengguna — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-tc-ghost btn-tc-icon-circle" aria-label="Kembali ke daftar pengguna">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h1 class="tc-display h4 fw-bold mb-0">Ubah Data Pengguna</h1>
                    <p class="text-secondary small mb-0">Perbarui data akun {{ $user->name }}.</p>
                </div>
            </div>

            <div class="tc-card p-4 p-md-5">
                <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.users._form', ['user' => $user])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
