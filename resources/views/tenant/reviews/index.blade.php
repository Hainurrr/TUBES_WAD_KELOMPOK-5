@extends('layouts.tenant')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-maroon fw-bold">⭐ Ulasan Pelanggan</h2>
            <p class="text-secondary mb-0">Kelola dan pantau ulasan dari pelanggan Anda.</p>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-maroon text-white h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3 me-3">
                        <i class="bi bi-star-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="opacity-75 mb-1">Rata-rata Rating</h6>
                        <h2 class="fw-bold mb-0">
                            {{ number_format($reviews->avg('rating'), 1) }} 
                            <span class="fs-6 opacity-75">/ 5.0</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-light h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-maroon bg-opacity-10 p-3 me-3">
                        <i class="bi bi-chat-quote-fill fs-3 text-maroon"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary mb-1">Total Ulasan</h6>
                        <h2 class="fw-bold text-dark mb-0">
                            {{ $reviews->total() }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form action="{{ route('tenant.reviews.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-10">
                    <label class="form-label fw-medium">Cari Menu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-secondary"></i></span>
                        <input type="text" name="menu_name" class="form-control border-start-0" placeholder="Nama menu..." value="{{ request('menu_name') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-maroon w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review List -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4">Tanggal</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Menu</th>
                            <th class="py-3">Rating</th>
                            <th class="py-3 pe-4">Komentar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4 text-secondary small">{{ $review->created_at->format('d M Y') }}</td>
                            <td class="fw-medium">{{ $review->user->name ?? 'Guest' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $review->menu->name ?? 'Unknown' }}</span>
                            </td>
                            <td>
                                <div class="text-warning">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                    <span class="text-dark ms-1 small">({{ $review->rating }})</span>
                                </div>
                            </td>
                            <td class="pe-4 text-justify small">{{ Str::limit($review->comment, 100) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="bi bi-chat-square-dots fs-1 mb-3 d-block opacity-25"></i>
                                Belum ada ulasan yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 border-top">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .bg-maroon { background-color: #800000; }
    .text-maroon { color: #800000; }
    .btn-maroon { background-color: #800000; color: white; }
    .btn-maroon:hover { background-color: #600000; color: white; }
</style>
@endsection
