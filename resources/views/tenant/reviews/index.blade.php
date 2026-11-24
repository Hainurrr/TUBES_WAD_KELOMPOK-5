@extends('layouts.tenant')

@section('title', 'Ulasan Pelanggan — Panel Tenant')

@section('content')
<div class="container-fluid px-0">
    <div class="mb-4">
        <h1 class="tc-display h4 fw-bold mb-1">Ulasan Pelanggan</h1>
        <p class="text-secondary small mb-0">Pantau penilaian dan masukan dari pelanggan yang menyelesaikan pesanan di gerai Anda.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="tc-card-dark p-4 d-flex align-items-center gap-3 h-100">
                <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: rgba(245,158,11,0.18); color: #F59E0B;">
                    <i class="bi bi-star-fill fs-4"></i>
                </div>
                <div>
                    <span class="small d-block" style="color: #CBD5E1;">Rata-rata Penilaian</span>
                    <div class="tc-mono fw-bold fs-3 text-white">
                        {{ number_format($reviews->avg('rating') ?? 0, 1) }} <span class="fs-6 fw-normal" style="color: #94A3B8;">/ 5.0</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="tc-card p-4 d-flex align-items-center gap-3 h-100">
                <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: var(--tc-crimson-soft); color: var(--tc-crimson);">
                    <i class="bi bi-chat-square-text-fill fs-4"></i>
                </div>
                <div>
                    <span class="small text-secondary d-block">Total Ulasan Masuk</span>
                    <div class="tc-mono fw-bold fs-3 text-dark">{{ $reviews->total() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="tc-card p-3 p-md-4 mb-4">
        <form action="{{ route('tenant.reviews.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9 col-lg-10">
                <label for="searchMenuReview" class="form-label">Cari Berdasarkan Nama Menu</label>
                <input type="text" id="searchMenuReview" name="menu_name" class="form-control" placeholder="Contoh: Ayam Geprek..." value="{{ request('menu_name') }}">
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
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
                        <th>Menu</th>
                        <th>Rating</th>
                        <th class="pe-4">Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4 text-secondary small tc-num">{{ $review->created_at->format('d M Y') }}</td>
                            <td class="fw-semibold">{{ $review->user->name ?? 'Pelanggan' }}</td>
                            <td><span class="tc-badge tc-badge-neutral">{{ $review->menu->name ?? 'Menu Terhapus' }}</span></td>
                            <td>
                                <span class="d-inline-flex align-items-center gap-1" style="color: var(--tc-amber);">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }} small"></i>
                                    @endfor
                                    <span class="tc-mono small text-dark ms-1">({{ $review->rating }})</span>
                                </span>
                            </td>
                            <td class="pe-4 small text-secondary">{{ \Illuminate\Support\Str::limit($review->comment, 110) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">Belum ada ulasan yang ditemukan.</td>
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
