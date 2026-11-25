@extends('errors.layout')

@section('code', '503')
@section('title', 'Layanan Tidak Tersedia')
@section('code_tone', 'tc-err-tone-amber')
@section('headline', 'TelyuCan sedang dalam pemeliharaan singkat.')
@section('message', 'Layanan sementara tidak dapat diakses karena pembaruan sistem atau beban server tinggi. Silakan muat ulang halaman beberapa menit lagi.')
@section('status_label', 'Layanan Tidak Tersedia')
@section('status_note', 'Server sementara belum menerima permintaan baru hingga pemeliharaan selesai.')

@section('code_icon')
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="9" />
    <path d="M12 7v5l3 2" />
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
    <p class="tc-err-dock-meta">Layanan pemesanan kantin TULT, GKU, dan Pujasera kembali normal setelah pemeliharaan selesai.</p>
@endsection

@section('helper_tile')
    <aside class="tc-err-tile tc-err-helper-slate">
        <div>
            <h2 class="tc-err-helper-title">Akan Segera Pulih</h2>
            <p class="tc-err-helper-copy">
                Kami sedang memastikan kestabilan pemesanan lintas kantin TULT, GKU, dan Pujasera. Muat ulang halaman secara berkala.
            </p>
        </div>
        <span class="tc-err-status-pill">
            <span class="tc-err-dot" style="background-color: #F59E0B;"></span>
            Pembaruan Sistem
        </span>
    </aside>
@endsection
