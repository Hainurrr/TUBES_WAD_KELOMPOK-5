@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0 text-dark">Kelola Akun System</h2>
        <a href="{{ route('admin.users.create') }}" class="btn text-white py-2 px-4 shadow-sm fw-bold" style="background-color: #800000;">
            <i class="bi bi-plus-lg me-2"></i>Tambah User
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0">Nama User</th>
                            <th class="py-3 border-0">Email</th>
                            <th class="py-3 border-0">Role</th>
                            <th class="py-3 border-0">Identity Number</th>
                            <th class="py-3 border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3 text-secondary fw-bold" style="width: 40px; height: 40px; border: 1px solid #e9ecef;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="text-secondary">{{ $user->email }}</td>
                            <td>
                                @php
                                    $badgeColor = match($user->role) {
                                        'admin' => 'danger',
                                        'tenant' => 'warning',
                                        'customer' => 'success',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-secondary">{{ $user->identity_number ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
