@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">Manajemen Ulasan</h2>
            <p class="text-secondary mb-0">Pantau dan kelola semua ulasan pengguna.</p>
        </div>
        <a href="{{ route('admin.reviews.export') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export All
        </a>
    </div>

    <!-- Search/Filter -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama pelanggan, kantin, atau menu..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kantin - Menu</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4 text-secondary small">{{ $review->created_at->format('d M Y') }}</td>
                            <td class="fw-medium">{{ $review->user->name ?? 'Guest' }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $review->menu->tenant->name ?? 'All' }}</div>
                                <div class="small text-secondary">{{ $review->menu->name ?? 'Unknown' }}</div>
                            </td>
                            <td>
                                <div class="text-warning small">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>{{ Str::limit($review->comment, 60) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">Tidak ada data ulasan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
