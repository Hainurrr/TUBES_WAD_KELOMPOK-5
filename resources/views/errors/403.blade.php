@extends('errors.layout')

@section('code', '403')
@section('title', 'Akses Ditolak')
@section('code_tone', 'tc-err-tone-crimson')
@section('headline', 'Kamu tidak memiliki izin ke halaman ini.')
@section('message', 'Halaman ini dibatasi untuk peran akun tertentu di TelyuCan. Kembali ke beranda atau masuk menggunakan akun dengan hak akses yang sesuai.')
@section('status_label', 'Akses Dibatasi')
@section('status_note', 'Permintaan ditolak oleh pemeriksaan hak akses peran pengguna.')

@section('code_icon')
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
    <path d="M9 12h6" />
</svg>
@endsection

@section('recovery')
    <div class="tc-err-actions">
        <a href="{{ url('/') }}" class="tc-err-btn tc-err-btn-primary">Kembali ke Beranda</a>
        <a href="{{ url('/login') }}" class="tc-err-btn tc-err-btn-ghost">Masuk ke Akun</a>
        <a href="{{ url('/menus') }}" class="tc-err-btn tc-err-btn-ghost">Lihat Katalog Menu</a>
    </div>
    <p class="tc-err-dock-meta">Akses dipisahkan berdasarkan peran akun: Pelanggan, Tenant, dan Admin TelyuCan.</p>
@endsection

@section('helper_tile')
    <aside class="tc-err-tile tc-err-helper-slate">
        <div>
            <h2 class="tc-err-helper-title">Pemisahan Area Pengguna</h2>
            <p class="tc-err-helper-copy">
                Area pemesanan ditujukan untuk Pelanggan, Layar Dapur untuk akun Tenant terhubung, dan Panel Administrasi khusus untuk Admin.
            </p>
        </div>
        <span class="tc-err-status-pill tc-err-status-pill-crimson">
            <span class="tc-err-dot" style="background-color: #FFFFFF;"></span>
            Hak Akses Peran
        </span>
    </aside>
@endsection
