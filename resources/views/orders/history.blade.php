@extends('layouts.app')

@section('title', 'Pesanan Saya — TelyuCan')

@section('content')
<section class="tc-page-banner">
    <div class="container">
        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none small fw-semibold d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i>Kembali ke Beranda
        </a>
        <h1 class="mb-1" style="font-size: clamp(1.65rem, 2.8vw, 2.25rem);">Pesanan Saya</h1>
        <p class="mb-0" style="color: #CBD5E1;">Pantau status pesanan aktif, cetak bukti pengambilan, dan beri ulasan untuk menu selesai.</p>
    </div>
</section>

<div class="container py-4 py-lg-5">
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- 1. Active Orders --}}
    @if($activeOrders->isNotEmpty())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="tc-display h5 fw-bold mb-0">Pesanan Aktif ({{ $activeOrders->count() }})</h2>
            </div>

            <div class="row row-cols-1 row-cols-lg-2 g-4">
                @foreach($activeOrders as $order)
                    @php
                        $isUnpaid = $order->payment_status == 'unpaid';
                        $stepWidth = $order->status == 'ready' ? '100%' : ($order->status == 'cooking' ? '50%' : '0%');
                    @endphp
                    <div class="col">
                        <article class="tc-card h-100 d-flex flex-column overflow-hidden">
                            <div class="px-4 py-3 d-flex justify-content-between align-items-center gap-2" style="background-color: var(--tc-obsidian); color: #FFFFFF;">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <i class="bi bi-shop" style="color: #FCA5A5;"></i>
                                    <span class="fw-bold text-truncate">{{ $order->tenant->name }}</span>
                                </div>
                                <span class="tc-mono small" style="color: #CBD5E1;">#{{ $order->transaction_group }}</span>
                            </div>

                            <div class="p-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                    <div>
                                        <div class="tc-display h5 fw-bold mb-1">
                                            @if($isUnpaid)
                                                Menunggu Pembayaran
                                            @elseif($order->status == 'pending')
                                                Menunggu Konfirmasi Dapur
                                            @elseif($order->status == 'cooking')
                                                Sedang Dimasak
                                            @elseif($order->status == 'ready')
                                                Siap Diambil di Konter
                                            @else
                                                Sedang Diproses
                                            @endif
                                        </div>
                                        @if($isUnpaid)
                                            <span class="tc-badge tc-badge-amber">Belum Dibayar</span>
                                        @elseif($order->status == 'ready')
                                            <span class="tc-badge tc-badge-emerald-solid">Tunjukkan Bukti di Konter</span>
                                        @elseif($order->status == 'cooking')
                                            <span class="tc-badge tc-badge-amber">Dapur Sedang Menyiapkan</span>
                                        @else
                                            <span class="tc-badge tc-badge-neutral">Terbayar · Antre Konfirmasi</span>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted small d-block">No. Pesanan</span>
                                        <span class="tc-mono fw-bold fs-5">{{ str_pad($order->id_order, 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>

                                {{-- Order Items List --}}
                                <ul class="list-unstyled mb-4 p-3 rounded-3 small" style="background-color: var(--tc-surface-muted);">
                                    @foreach($order->orderDetails as $detail)
                                        <li class="d-flex justify-content-between {{ !$loop->last ? 'mb-1' : '' }}">
                                            <span><strong class="tc-mono">{{ $detail->qty }}x</strong> {{ $detail->menu->name ?? 'Menu' }}</span>
                                            <span class="tc-mono text-secondary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Progress Bar --}}
                                <div class="position-relative d-flex justify-content-between align-items-center text-center px-2 mb-1">
                                    <div class="position-absolute start-0 end-0" style="top: 13px; height: 3px; background: var(--tc-border); z-index: 0;"></div>
                                    <div class="position-absolute start-0" style="top: 13px; height: 3px; background: var(--tc-emerald); width: {{ $isUnpaid ? '0%' : $stepWidth }}; z-index: 0;"></div>

                                    <div class="position-relative z-1">
                                        <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center"
                                             style="width: 28px; height: 28px; background: {{ !$isUnpaid ? 'var(--tc-emerald)' : 'var(--tc-border)' }}; color: #fff;">
                                            <i class="bi bi-receipt small"></i>
                                        </div>
                                        <span class="d-block fw-semibold" style="font-size: 0.75rem;">Pesanan</span>
                                    </div>
                                    <div class="position-relative z-1">
                                        <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center"
                                             style="width: 28px; height: 28px; background: {{ in_array($order->status, ['cooking', 'ready']) ? 'var(--tc-emerald)' : 'var(--tc-border)' }}; color: #fff;">
                                            <i class="bi bi-fire small"></i>
                                        </div>
                                        <span class="d-block fw-semibold" style="font-size: 0.75rem;">Dimasak</span>
                                    </div>
                                    <div class="position-relative z-1">
                                        <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center"
                                             style="width: 28px; height: 28px; background: {{ $order->status == 'ready' ? 'var(--tc-emerald)' : 'var(--tc-border)' }}; color: #fff;">
                                            <i class="bi bi-bag-check small"></i>
                                        </div>
                                        <span class="d-block fw-semibold" style="font-size: 0.75rem;">Siap Ambil</span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-4 py-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2 bg-white">
                                <div>
                                    <span class="text-muted small d-block">Total</span>
                                    <span class="tc-mono fw-bold fs-6 text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($isUnpaid && $order->snap_token)
                                        <a href="{{ route('payment.page', ['transactionGroup' => $order->transaction_group]) }}" class="btn btn-tc-primary btn-sm px-3">
                                            <i class="bi bi-wallet2 me-1"></i>Bayar Sekarang
                                        </a>
                                    @endif
                                    @if($order->status == 'pending')
                                        <form action="{{ route('orders.cancel', $order->id_order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('orders.print', $order->id_order) }}" target="_blank" class="btn btn-tc-ghost btn-sm px-3 text-decoration-none">
                                        <i class="bi bi-printer me-1"></i>Cetak Bukti
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- 2. Past Orders --}}
    <div>
        <h2 class="tc-display h5 fw-bold mb-3">Riwayat Transaksi</h2>
        @if($pastOrders->isEmpty())
            <div class="tc-card p-5 text-center">
                <i class="bi bi-clock-history fs-1 text-muted d-block mb-2"></i>
                <h3 class="tc-display h6 fw-bold mb-1">Belum ada riwayat pesanan selesai</h3>
                <p class="text-secondary small mb-3">Pesanan yang selesai atau dibatalkan akan tampil di sini.</p>
                <a href="{{ route('menus.index') }}" class="btn btn-tc-primary btn-sm px-4">Mulai Pesan</a>
            </div>
        @else
            <div class="tc-table-wrap">
                <div class="table-responsive">
                    <table class="table tc-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Kantin</th>
                                <th>Menu</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pastOrders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $order->tenant->name }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small text-secondary">
                                            @foreach($order->orderDetails as $detail)
                                                <li><span class="tc-mono">{{ $detail->qty }}x</span> {{ $detail->menu->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="small text-secondary tc-num">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if($order->status === 'completed')
                                            <span class="tc-badge tc-badge-emerald">Selesai</span>
                                        @else
                                            <span class="tc-badge tc-badge-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <a href="{{ route('orders.print', $order->id_order) }}" target="_blank" class="btn btn-tc-ghost btn-sm px-3" title="Cetak bukti">
                                                <i class="bi bi-printer"></i>
                                            </a>

                                            @php
                                                $allReviewed = true;
                                                foreach($order->orderDetails as $detail) {
                                                    if(!$order->reviews->where('id_menu', $detail->id_menu)->first()) {
                                                        $allReviewed = false;
                                                        break;
                                                    }
                                                }
                                            @endphp

                                            @if($order->status == 'completed')
                                                @if(!$allReviewed)
                                                    <button type="button" class="btn btn-tc-outline btn-sm px-3"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#reviewModal"
                                                            data-order-id="{{ $order->id_order }}"
                                                            onclick="prepareReviewModal({{ $order->id_order }})">
                                                        Beri Ulasan
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-tc-ghost btn-sm px-3"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editReviewListModal_{{ $order->id_order }}">
                                                        <i class="bi bi-check2-circle text-success me-1"></i>Ulasan Saya
                                                    </button>

                                                    <div class="modal fade text-start" id="editReviewListModal_{{ $order->id_order }}" tabindex="-1">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 p-2" style="border-radius: 24px;">
                                                                <div class="modal-header border-0 pb-0">
                                                                    <h5 class="tc-display fw-bold mb-0">Ulasan Anda</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @foreach($order->reviews as $rev)
                                                                        <div class="p-3 rounded-4 mb-2" style="background: var(--tc-surface-muted);">
                                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                                <strong>{{ $rev->menu->name ?? 'Menu' }}</strong>
                                                                                <span class="fw-bold small" style="color: var(--tc-amber-ink);"><i class="bi bi-star-fill text-warning me-1"></i>{{ $rev->rating }}</span>
                                                                            </div>
                                                                            <p class="small text-secondary mb-2">{{ $rev->comment }}</p>
                                                                            <div class="d-flex gap-2 justify-content-end">
                                                                                <button type="button" class="btn btn-tc-ghost btn-sm"
                                                                                        onclick="openEditModal({{ $rev->id_review }}, '{{ addslashes($rev->menu->name ?? '') }}', {{ $rev->rating }}, '{{ addslashes($rev->comment) }}')">
                                                                                    <i class="bi bi-pencil me-1"></i>Ubah
                                                                                </button>
                                                                                <form action="{{ route('reviews.destroy', $rev->id_review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?');" class="m-0">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3"><i class="bi bi-trash"></i></button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Create Review Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-2" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="tc-display fw-bold mb-0" id="reviewModalLabel">Beri Ulasan Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm" onsubmit="return validateReview()">
                @csrf
                <input type="hidden" name="id_order" id="modalOrderId">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalMenuSelect" class="form-label">Pilih Menu</label>
                        <select class="form-select" name="id_menu" id="modalMenuSelect" required></select>
                    </div>

                    <div class="mb-3 text-center">
                        <div class="small fw-semibold text-secondary mb-2">Pilih Penilaian</div>
                        <div class="rating-stars fs-2" style="color: var(--tc-amber); cursor: pointer;">
                            <i class="bi bi-star star-input" data-value="1"></i>
                            <i class="bi bi-star star-input" data-value="2"></i>
                            <i class="bi bi-star star-input" data-value="3"></i>
                            <i class="bi bi-star star-input" data-value="4"></i>
                            <i class="bi bi-star star-input" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <div id="ratingError" class="text-danger small mt-1 d-none">Silakan pilih jumlah bintang terlebih dahulu.</div>
                    </div>

                    <div class="mb-2">
                        <label for="comment" class="form-label">Komentar</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Bagikan pengalaman rasa atau kecepatan layanan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-tc-ghost px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tc-primary px-4">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Review Modal --}}
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 p-2" style="border-radius: 24px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="tc-display fw-bold mb-0">Ubah Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="" method="POST" id="editReviewForm">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editMenuName" class="form-label">Menu</label>
                        <input type="text" class="form-control" id="editMenuName" disabled>
                    </div>

                    <div class="mb-3 text-center">
                        <div class="small fw-semibold text-secondary mb-2">Ubah Penilaian</div>
                        <div class="rating-stars fs-2" style="color: var(--tc-amber); cursor: pointer;">
                            <i class="bi bi-star star-input" data-value="1"></i>
                            <i class="bi bi-star star-input" data-value="2"></i>
                            <i class="bi bi-star star-input" data-value="3"></i>
                            <i class="bi bi-star star-input" data-value="4"></i>
                            <i class="bi bi-star star-input" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="editRatingInput" required>
                    </div>

                    <div class="mb-2">
                        <label for="editComment" class="form-label">Komentar</label>
                        <textarea class="form-control" id="editComment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-tc-ghost px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tc-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const stars = document.querySelectorAll('#reviewModal .star-input');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            ratingInput.value = value;
            updateStars(value);
        });
    });

    function updateStars(value) {
        stars.forEach(star => {
            const starValue = star.getAttribute('data-value');
            star.classList.toggle('bi-star-fill', starValue <= value);
            star.classList.toggle('bi-star', starValue > value);
        });
    }

    const editStars = document.querySelectorAll('#editReviewModal .star-input');
    const editRatingInput = document.getElementById('editRatingInput');

    editStars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            editRatingInput.value = value;
            updateEditStars(value);
        });
    });

    function updateEditStars(value) {
        editStars.forEach(star => {
            const starValue = star.getAttribute('data-value');
            star.classList.toggle('bi-star-fill', starValue <= value);
            star.classList.toggle('bi-star', starValue > value);
        });
    }

    const orderData = @json($pastOrders->mapWithKeys(function($order) {
        return [$order->id_order => $order->orderDetails->map(function($detail) {
            return ['id' => $detail->menu->id_menu, 'name' => $detail->menu->name];
        })];
    }));

    function prepareReviewModal(orderId) {
        document.getElementById('modalOrderId').value = orderId;
        const menuSelect = document.getElementById('modalMenuSelect');
        menuSelect.innerHTML = '';

        if (orderData[orderId]) {
            orderData[orderId].forEach(menu => {
                const option = document.createElement('option');
                option.value = menu.id;
                option.text = menu.name;
                menuSelect.appendChild(option);
            });
        }

        ratingInput.value = '';
        document.getElementById('comment').value = '';
        updateStars(0);
        document.getElementById('ratingError').classList.add('d-none');
    }

    function openEditModal(reviewId, menuName, rating, comment) {
        const form = document.getElementById('editReviewForm');
        form.action = "{{ url('reviews') }}/" + reviewId;

        document.getElementById('editMenuName').value = menuName;
        document.getElementById('editRatingInput').value = rating;
        document.getElementById('editComment').value = comment;
        updateEditStars(rating);

        document.querySelectorAll('.modal').forEach(modal => {
            const instance = bootstrap.Modal.getInstance(modal);
            if (instance) instance.hide();
        });

        new bootstrap.Modal(document.getElementById('editReviewModal')).show();
    }

    function validateReview() {
        if (!ratingInput.value) {
            document.getElementById('ratingError').classList.remove('d-none');
            return false;
        }
        document.getElementById('ratingError').classList.add('d-none');
        return true;
    }
</script>
@endpush
