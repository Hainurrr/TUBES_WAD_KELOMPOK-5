@extends('layouts.admin')

@section('title', 'Manajemen Ulasan — Panel Admin')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="tc-display h4 fw-bold mb-1">Manajemen Ulasan</h1>
            <p class="text-secondary small mb-0">Moderasi seluruh ulasan pelanggan dan ekspor rekap ke format CSV.</p>
        </div>
        <a href="{{ route('admin.reviews.export') }}" class="btn btn-tc-emerald btn-sm px-4 py-2 text-decoration-none">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Ekspor CSV
        </a>
    </div>

    <x-flash-alerts />

    <div class="tc-card p-3 p-md-4 mb-4">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9 col-lg-10">
                <label for="adminReviewSearch" class="form-label">Cari Ulasan</label>
                <input type="text" id="adminReviewSearch" name="search" class="form-control" placeholder="Cari nama pelanggan atau nama menu..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-lg-2">
                <button type="submit" class="btn btn-tc-primary w-100">Cari</button>
            </div>
        </form>
    </div>

    <div class="tc-table-wrap">
        <div class="table-responsive">
            <table class="table tc-table">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Kantin &amp; Menu</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4 text-secondary small tc-num">{{ $review->created_at->format('d M Y') }}</td>
                            <td class="fw-semibold">{{ $review->user->name ?? 'Pelanggan' }}</td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $review->menu->tenant->name ?? 'Tenant Dihapus' }}</div>
                                <div class="small text-secondary">{{ $review->menu->name ?? 'Menu Dihapus' }}</div>
                            </td>
                            <td><x-rating-stars :rating="$review->rating" size-class="small" /></td>
                            <td class="small text-secondary">{{ \Illuminate\Support\Str::limit($review->comment, 75) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus ulasan ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary">Tidak ada data ulasan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
