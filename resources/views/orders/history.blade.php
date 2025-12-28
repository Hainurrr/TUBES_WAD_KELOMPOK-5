@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12">
            <a href="{{ route('home') }}" class="text-decoration-none text-secondary mb-3 d-inline-block hover-opacity">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
            </a>
            <h2 class="fw-bold text-dark">Pesanan Saya</h2>
            <p class="text-secondary">Pantau status pesanan aktif dan riwayat transaksimu.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 1. ACTIVE ORDERS SECTION -->
    @if($activeOrders->isNotEmpty())
        <div class="mb-5">
            <h4 class="fw-bold mb-4 text-maroon"><i class="bi bi-hourglass-split me-2"></i>Pesanan Aktif</h4>
            <div class="row row-cols-1 row-cols-lg-2 g-4">
                @foreach($activeOrders as $order)
                    <div class="col" data-aos="fade-up">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-body p-0">
                                <div class="row g-0">
                                    <!-- Left: Illustration & Status -->
                                    <div class="col-8 p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rounded-circle bg-maroon text-white d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 48px; height: 48px;">
                                                <i class="bi bi-shop fs-4"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $order->tenant->name }}</h6>
                                                <small class="text-secondary">#{{ $order->transaction_group }}</small>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h5 class="fw-bold text-dark mb-1">
                                                @if($order->status == 'pending') Menunggu Konfirmasi
                                                @elseif($order->status == 'cooking') Sedang Dimasak
                                                @elseif($order->status == 'ready') Siap Diambil
                                                @else Diproses @endif
                                            </h5>
                                            <small class="text-secondary">Estimasi selesai: 15-20 menit</small>
                                        </div>

                                        <!-- Progress Steps -->
                                        <div class="d-flex position-relative align-items-center justify-content-between text-center" style="font-size: 10px;">
                                            <div class="position-absolute top-50 start-0 w-100 translate-middle-y bg-light" style="height: 4px; z-index: 0;"></div>
                                            <div class="position-absolute top-50 start-0 translate-middle-y bg-success" 
                                                 style="height: 4px; z-index: 0; width: {{ $order->status == 'ready' ? '100%' : ($order->status == 'cooking' ? '66%' : '33%') }}; transition: width 0.5s;"></div>

                                            <div class="position-relative z-1">
                                                <div class="rounded-circle {{ in_array($order->status, ['pending', 'cooking', 'ready']) ? 'bg-success' : 'bg-light' }} text-white d-flex align-items-center justify-content-center mx-auto mb-1" style="width: 24px; height: 24px;">
                                                    <i class="bi bi-receipt"></i>
                                                </div>
                                                <span class="d-block text-secondary fw-bold">Order</span>
                                            </div>
                                            <div class="position-relative z-1">
                                                <div class="rounded-circle {{ in_array($order->status, ['cooking', 'ready']) ? 'bg-success' : 'bg-light' }} text-white d-flex align-items-center justify-content-center mx-auto mb-1" style="width: 24px; height: 24px;">
                                                    <i class="bi bi-fire"></i>
                                                </div>
                                                <span class="d-block text-secondary fw-bold">Masak</span>
                                            </div>
                                            <div class="position-relative z-1">
                                                <div class="rounded-circle {{ $order->status == 'ready' ? 'bg-success' : 'bg-light' }} text-white d-flex align-items-center justify-content-center mx-auto mb-1" style="width: 24px; height: 24px;">
                                                    <i class="bi bi-bag-check"></i>
                                                </div>
                                                <span class="d-block text-secondary fw-bold">Siap</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Illustration Image -->
                                    <div class="col-4 bg-light d-flex align-items-center justify-content-center p-3">
                                        <img src="https://cdn-icons-png.flaticon.com/512/3448/3448608.png" alt="Cooking" class="img-fluid" style="max-height: 100px; opacity: 0.8;">
                                    </div>
                                </div>

                                <!-- Action Footer -->
                                <div class="p-3 bg-white border-top d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-secondary d-block">Total Pembayaran</small>
                                        <span class="fw-bold text-dark fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($order->status == 'pending')
                                            <form action="{{ route('orders.cancel', $order->id_order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm d-flex align-items-center">
                                                    <i class="bi bi-x-circle me-2"></i> Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('orders.print', $order->id_order) }}" target="_blank" class="btn btn-outline-secondary rounded-pill btn-sm d-flex align-items-center">
                                            <i class="bi bi-printer me-2"></i> Cetak Bukti
                                        </a>
                                        @if($order->status == 'ready')
                                            <button class="btn btn-success rounded-pill btn-sm px-3 fw-bold">Ambil</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 2. PAST ORDERS SECTION -->
    <div>
        <h4 class="fw-bold mb-3 text-secondary">Riwayat Pesanan</h4>
        @if($pastOrders->isEmpty())
            <div class="text-center py-5 bg-light rounded-4">
                <i class="bi bi-clock-history fs-1 text-secondary mb-3"></i>
                <h5 class="text-secondary">Belum ada riwayat pesanan selesai.</h5>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 ps-4">Kantin</th>
                                    <th class="py-3">Menu</th>
                                    <th class="py-3">Tanggal</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pastOrders as $order)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $order->tenant->name }}</td>
                                    <td>
                                        <ul class="list-unstyled mb-0 small text-secondary">
                                            @foreach($order->orderDetails as $detail)
                                                <li>{{ $detail->qty }}x {{ $detail->menu->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="small text-secondary">{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill border">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('orders.print', $order->id_order) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill me-2">
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
                                                <button class="btn btn-sm btn-outline-maroon rounded-pill" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal"
                                                        data-order-id="{{ $order->id_order }}"
                                                        onclick="prepareReviewModal({{ $order->id_order }})">
                                                    Beri Ulasan
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-link text-decoration-none dropdown-toggle text-success fw-bold" data-bs-toggle="dropdown">
                                                    <i class="bi bi-check-circle-fill"></i> Diulas
                                                </button>
                                                <ul class="dropdown-menu">
                                                    {{-- Menampilkan opsi untuk melihat dan mengedit ulasan --}}
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#editReviewListModal_{{$order->id_order}}">
                                                           Lihat & Edit
                                                        </a>
                                                    </li>
                                                </ul>
                                                
                                                <!-- Modal for Listing Reviews to Edit/Delete -->
                                                <div class="modal fade" id="editReviewListModal_{{$order->id_order}}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Ulasan Anda</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @foreach($order->reviews as $rev)
                                                                    <div class="card mb-2 border-0 bg-light">
                                                                        <div class="card-body p-3">
                                                                            <div class="d-flex justify-content-between">
                                                                                <strong>{{ $rev->menu->name ?? 'Menu' }}</strong>
                                                                                <span class="text-warning"><i class="bi bi-star-fill"></i> {{ $rev->rating }}</span>
                                                                            </div>
                                                                            <p class="small text-secondary mb-2">{{ $rev->comment }}</p>
                                                                            <div class="d-flex gap-2 justify-content-end">
                                                                                 <button class="btn btn-sm btn-outline-primary"
                                                                                         onclick="openEditModal({{ $rev->id_review }}, '{{ $rev->menu->name ?? '' }}', {{ $rev->rating }}, '{{ addslashes($rev->comment) }}')">
                                                                                     <i class="bi bi-pencil"></i> Edit
                                                                                 </button>
                                                                                 <form action="{{ route('reviews.destroy', $rev->id_review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?');">
                                                                                     @csrf
                                                                                     @method('DELETE')
                                                                                     <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                                                 </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


{{-- Review Modal (Create) --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="reviewModalLabel">Beri Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm" onsubmit="return validateReview()">
                @csrf
                <input type="hidden" name="id_order" id="modalOrderId">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Menu</label>
                        <select class="form-select" name="id_menu" id="modalMenuSelect" required>
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <p class="text-secondary mb-3">Bagaimana rasanya?</p>
                    
                    <div class="mb-3 text-center">
                        <div class="rating-stars fs-2 text-warning pointer">
                            <i class="bi bi-star star-input" data-value="1"></i>
                            <i class="bi bi-star star-input" data-value="2"></i>
                            <i class="bi bi-star star-input" data-value="3"></i>
                            <i class="bi bi-star star-input" data-value="4"></i>
                            <i class="bi bi-star star-input" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <div id="ratingError" class="text-danger small mt-1 d-none">Silakan pilih bintang!</div>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label fw-medium">Komentar</label>
                        <textarea class="form-control bg-light border-0" id="comment" name="comment" rows="3" placeholder="Ceritakan pengalamanmu..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Review Modal --}}
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editReviewForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Menu</label>
                        <input type="text" class="form-control" id="editMenuName" disabled>
                    </div>

                    <p class="text-secondary mb-3">Bagaimana rasanya?</p>
                    
                    <div class="mb-3 text-center">
                        <div class="rating-stars fs-2 text-warning pointer">
                            <i class="bi bi-star star-input" data-value="1"></i>
                            <i class="bi bi-star star-input" data-value="2"></i>
                            <i class="bi bi-star star-input" data-value="3"></i>
                            <i class="bi bi-star star-input" data-value="4"></i>
                            <i class="bi bi-star star-input" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="editRatingInput" required>
                    </div>

                    <div class="mb-3">
                        <label for="editComment" class="form-label fw-medium">Komentar</label>
                        <textarea class="form-control bg-light border-0" id="editComment" name="comment" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-maroon rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logika Rating Bintang untuk Membuat Ulasan
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
            if (starValue <= value) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            }
        });
    }
    
    // Logika Rating Bintang untuk Edit
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
            if (starValue <= value) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            }
        });
    }

    // Pengaturan Data Modal (Buat Ulasan)
    const orderData = @json($pastOrders->mapWithKeys(function($order) {
        return [$order->id_order => $order->orderDetails->map(function($detail) {
            return ['id' => $detail->menu->id_menu, 'name' => $detail->menu->name];
        })];
    }));

    function prepareReviewModal(orderId) {
        document.getElementById('modalOrderId').value = orderId;
        const menuSelect = document.getElementById('modalMenuSelect');
        menuSelect.innerHTML = '';
        
        if(orderData[orderId]) {
            orderData[orderId].forEach(menu => {
                const option = document.createElement('option');
                option.value = menu.id;
                option.text = menu.name;
                menuSelect.appendChild(option);
            });
        }

        // Reset
        ratingInput.value = '';
        document.getElementById('comment').value = '';
        updateStars(0);
        document.getElementById('ratingError').classList.add('d-none');
    }

    // Buka Modal Edit
    function openEditModal(reviewId, menuName, rating, comment) {
        // Atur action form
        const form = document.getElementById('editReviewForm');
        form.action = "{{ url('reviews') }}/" + reviewId;

        // Atur nilai
        document.getElementById('editMenuName').value = menuName;
        document.getElementById('editRatingInput').value = rating;
        document.getElementById('editComment').value = comment;
        
        updateEditStars(rating);
        
        // Hide list modal
        const listModals = document.querySelectorAll('.modal');
        listModals.forEach(modal => {
            const instance = bootstrap.Modal.getInstance(modal);
            if(instance) instance.hide();
        });

        // Show edit modal
        new bootstrap.Modal(document.getElementById('editReviewModal')).show();
    }

    function validateReview() {
        const rating = ratingInput.value;
        let isValid = true;
        document.getElementById('ratingError').classList.add('d-none');
        if (!rating) {
            document.getElementById('ratingError').classList.remove('d-none');
            isValid = false;
        }
        return isValid;
    }
</script>
@endpush
