@extends('errors.layout')

@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('code_tone', 'tc-err-tone-crimson')
@section('headline', 'Halaman yang kamu cari tidak tersedia.')
@section('message', 'Tautan mungkin sudah kedaluwarsa, gerai atau menu telah dihapus, atau alamat yang diketik belum tepat. Cari menu langsung di bawah ini atau kembali ke beranda.')
@section('status_label', 'Halaman Tidak Ditemukan')
@section('status_note', 'Alamat tujuan tidak cocok dengan halaman mana pun di TelyuCan.')

@section('code_icon')
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="11" cy="11" r="7" />
    <path d="m20 20-3.5-3.5" />
    <path d="M8.5 11h5" />
</svg>
@endsection

@section('recovery')
    <form action="{{ url('/menus') }}" method="GET" class="tc-err-search" id="tcErrorSearchForm">
        <input type="text" name="q" placeholder="Cari menu atau nama gerai..." aria-label="Cari menu atau nama gerai">
        <button type="submit" class="tc-err-btn tc-err-btn-primary">Cari Menu</button>
    </form>

    <div class="tc-err-actions">
        <a href="{{ url('/') }}" class="tc-err-btn tc-err-btn-ghost">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Kembali ke Beranda
        </a>
        <a href="{{ url('/menus') }}" class="tc-err-btn tc-err-btn-ghost">Buka Katalog Menu</a>
    </div>
@endsection

@section('helper_tile')
    <aside class="tc-err-tile tc-err-helper-emerald">
        <div>
            <h2 class="tc-err-helper-title">Langsung ke Area Kantin</h2>
            <p class="tc-err-helper-copy">Pilih area kantin kampus untuk melihat gerai yang sedang buka.</p>
        </div>

        <div class="tc-err-zone-list">
            <a href="{{ url('/search?location=TULT') }}" class="tc-err-zone-link">
                <span>Kantin Gedung TULT</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M7 17 17 7" />
                    <path d="M7 7h10v10" />
                </svg>
            </a>
            <a href="{{ url('/search?location=GKU') }}" class="tc-err-zone-link">
                <span>Kantin Gedung GKU</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M7 17 17 7" />
                    <path d="M7 7h10v10" />
                </svg>
            </a>
            <a href="{{ url('/search?location=Pujasera') }}" class="tc-err-zone-link">
                <span>Pujasera Kampus</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M7 17 17 7" />
                    <path d="M7 7h10v10" />
                </svg>
            </a>
        </div>
    </aside>
@endsection
