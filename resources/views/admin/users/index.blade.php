@extends('layouts.admin')

@section('title', 'Kelola Pengguna — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="tc-display h4 fw-bold mb-1">Kelola Pengguna Sistem</h1>
            <p class="text-secondary small mb-0">Atur akun pelanggan, pengelola tenant, dan administrator.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-tc-primary btn-sm px-4 py-2 text-decoration-none">
            <i class="bi bi-plus-lg me-1"></i>Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <div class="tc-table-wrap">
        <div class="table-responsive">
            <table class="table tc-table">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Pengguna</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Nomor Identitas</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0" style="width: 38px; height: 38px; background: var(--tc-obsidian);">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-bold text-dark">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-secondary small">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="tc-badge tc-badge-crimson">Admin</span>
                                @elseif($user->role === 'tenant')
                                    <span class="tc-badge tc-badge-amber">Tenant</span>
                                @else
                                    <span class="tc-badge tc-badge-emerald">Pelanggan</span>
                                @endif
                            </td>
                            <td class="text-secondary small tc-mono">{{ $user->identity_number ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-tc-ghost btn-sm px-3" title="Ubah">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
