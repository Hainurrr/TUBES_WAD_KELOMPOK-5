@extends('errors.layout')

@section('code', '500')
@section('title', 'Gangguan Server')
@section('code_tone', 'tc-err-tone-amber')
@section('headline', 'Terjadi kendala saat memproses permintaanmu.')
@section('message', 'Sistem mengalami kesalahan tak terduga di sisi server. Coba muat ulang halaman dalam beberapa saat atau kembali ke beranda untuk melanjutkan pemesanan.')
@section('status_label', 'Gangguan Server Internal')
@section('status_note', 'Permintaan gagal diselesaikan di server; bukan kesalahan dari perangkatmu.')

@section('code_icon')
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 9v4" />
    <path d="M12 17h.01" />
    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
</svg>
@endsection

@section('recovery')
    <div class="tc-err-actions">
        <button type="button" class="tc-err-btn tc-err-btn-primary" onclick="window.location.reload()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 12a9 9 0 1 1-2.64-6.36" />
                <path d="M21 3v6h-6" />
            </svg>
            Muat Ulang Halaman
        </button>
        <a href="{{ url('/') }}" class="tc-err-btn tc-err-btn-ghost">Kembali ke Beranda</a>
    </div>
    <p class="tc-err-dock-meta">Jika kendala masih berlanjut, tunggu beberapa saat lalu coba muat ulang kembali.</p>
@endsection

@section('helper_tile')
    <aside class="tc-err-tile tc-err-helper-slate">
        <div>
            <h2 class="tc-err-helper-title">Data Transaksi Tetap Aman</h2>
            <p class="tc-err-helper-copy">
                Pesanan yang sudah selesai dibayar tetap tersimpan di sistem. Setelah halaman pulih, cek status pesanan melalui Riwayat Pesanan.
            </p>
        </div>
        <span class="tc-err-status-pill">
            <span class="tc-err-dot" style="background-color: #F59E0B;"></span>
            Status Layanan
        </span>
    </aside>
@endsection
