@extends('layouts.app')

@section('title', 'Pesanan Saya — TelyuCan')

@section('content')
<x-page-banner
    title="Pesanan Saya"
    subtitle="Pantau status pesanan aktif, cetak bukti pengambilan, dan beri ulasan untuk menu selesai."
    :back-url="route('home')"
/>

<section class="tc-operate-sheet">
    <div class="container">
        <x-flash-alerts />

        @if($activeOrders->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="tc-display h5 fw-bold mb-0">Pesanan Aktif ({{ $activeOrders->count() }})</h2>
                </div>

                <div class="row row-cols-1 row-cols-lg-2 g-4">
                    @foreach($activeOrders as $order)
                        <div class="col">
                            @include('orders.partials.active-order-card', ['order' => $order])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <h2 class="tc-display h5 fw-bold mb-3">Riwayat Transaksi</h2>
            @if($pastOrders->isEmpty())
                <x-empty-state
                    icon="bi-clock-history"
                    heading-class="h6"
                    title="Belum ada riwayat pesanan selesai"
                    description="Pesanan yang selesai atau dibatalkan akan tampil di sini."
                    :action-url="route('menus.index')"
                    action-label="Mulai Pesan"
                />
            @else
                <div class="d-md-none d-flex flex-column gap-3">
                    @foreach($pastOrders as $order)
                        @php
                            $allReviewed = $order->orderDetails->every(
                                fn ($detail) => (bool) $order->reviews->firstWhere('id_menu', $detail->id_menu)
                            );
                        @endphp
                        <article class="tc-card p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark lh-sm">{{ $order->tenant->name }}</div>
                                    <small class="text-secondary tc-num">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                </div>
                                @if($order->status === 'completed')
                                    <span class="tc-badge tc-badge-emerald flex-shrink-0">Selesai</span>
                                @else
                                    <span class="tc-badge tc-badge-danger flex-shrink-0">Dibatalkan</span>
                                @endif
                            </div>

                            <ul class="list-unstyled mb-3 p-2 px-3 rounded-3 small text-secondary" style="background-color: var(--tc-surface-muted);">
                                @foreach($order->orderDetails as $detail)
                                    <li><span class="tc-mono fw-semibold text-dark">{{ $detail->qty }}x</span> {{ $detail->menu->name }}</li>
                                @endforeach
                            </ul>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('orders.print', $order->id_order) }}" target="_blank" class="btn btn-tc-ghost btn-sm px-3 text-decoration-none">
                                    <i class="bi bi-printer me-1"></i>Cetak Bukti
                                </a>

                                @if($order->status == 'completed')
                                    @if(!$allReviewed)
                                        <button type="button" class="btn btn-tc-outline btn-sm px-3 flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#reviewModal"
                                                data-review-create
                                                data-order-id="{{ $order->id_order }}">
                                            Beri Ulasan
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-tc-ghost btn-sm px-3 flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editReviewListModal_{{ $order->id_order }}">
                                            <i class="bi bi-check2-circle text-success me-1"></i>Ulasan Saya
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="tc-table-wrap d-none d-md-block">
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
                                    @php
                                        $allReviewed = $order->orderDetails->every(
                                            fn ($detail) => (bool) $order->reviews->firstWhere('id_menu', $detail->id_menu)
                                        );
                                    @endphp
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

                                                @if($order->status == 'completed')
                                                    @if(!$allReviewed)
                                                        <button type="button" class="btn btn-tc-outline btn-sm px-3"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#reviewModal"
                                                                data-review-create
                                                                data-order-id="{{ $order->id_order }}">
                                                            Beri Ulasan
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-tc-ghost btn-sm px-3"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editReviewListModal_{{ $order->id_order }}">
                                                            <i class="bi bi-check2-circle text-success me-1"></i>Ulasan Saya
                                                        </button>
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

                @foreach($pastOrders as $order)
                    @php
                        $allReviewed = $order->orderDetails->every(
                            fn ($detail) => (bool) $order->reviews->firstWhere('id_menu', $detail->id_menu)
                        );
                    @endphp
                    @if($order->status == 'completed' && $allReviewed)
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
                                                            data-review-edit
                                                            data-review-url="{{ url('reviews/' . $rev->id_review) }}"
                                                            data-review-menu="{{ $rev->menu->name ?? '' }}"
                                                            data-review-rating="{{ $rev->rating }}"
                                                            data-review-comment="{{ $rev->comment }}">
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
                @endforeach
            @endif
        </div>
    </div>
</section>

@include('orders.partials.review-modals')

@php
    $pastOrderMenus = $pastOrders->mapWithKeys(function ($order) {
        return [$order->id_order => $order->orderDetails->map(function ($detail) {
            return ['id' => $detail->menu->id_menu, 'name' => $detail->menu->name];
        })->values()];
    });
@endphp
<script type="application/json" id="tcPastOrderMenus">@json($pastOrderMenus)</script>
@endsection
