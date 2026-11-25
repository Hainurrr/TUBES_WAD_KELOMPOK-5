<div class="modal fade tc-notice-modal" id="academicNoticeModal" data-academic-notice data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="academicNoticeTitle" aria-describedby="academicNoticeDesc" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content tc-notice-content">
            <div class="tc-notice-stage">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <div class="tc-brand" style="font-size: 1.15rem;">
                        <x-brand-logo :size="30" />
                        <span>TelyuCan</span>
                    </div>
                    <span class="tc-notice-icon" aria-hidden="true">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </span>
                </div>

                <h2 class="tc-notice-title" id="academicNoticeTitle">Website Simulasi Tugas Kuliah</h2>
                <p class="tc-notice-lead" id="academicNoticeDesc">
                    Situs ini merupakan proyek akademik mahasiswa, bukan website pemesanan kantin asli Telkom University.
                </p>
            </div>

            <div class="tc-notice-body">
                <ul class="tc-notice-list">
                    <li>
                        <span class="tc-notice-bullet" aria-hidden="true"><i class="bi bi-mortarboard-fill"></i></span>
                        <div>
                            <strong>Keperluan Tugas Kuliah:</strong> Dikembangkan oleh Kelompok 5 untuk Tugas Besar mata kuliah Web Application Development (WAD).
                        </div>
                    </li>
                    <li>
                        <span class="tc-notice-bullet" aria-hidden="true"><i class="bi bi-shop-window"></i></span>
                        <div>
                            <strong>Bukan Pemesanan Sungguhan:</strong> Daftar gerai, menu, dan pesanan hanya untuk simulasi serta tidak diteruskan ke kantin fisik.
                        </div>
                    </li>
                    <li>
                        <span class="tc-notice-bullet" aria-hidden="true"><i class="bi bi-shield-exclamation"></i></span>
                        <div>
                            <strong>Mode Pengujian:</strong> Jangan gunakan data pribadi sensitif maupun melakukan transaksi pembayaran asli pada situs ini.
                        </div>
                    </li>
                </ul>

                <button type="button" class="btn btn-tc-primary w-100 py-3" id="academicNoticeAckBtn" data-academic-notice-ack>
                    Saya Mengerti, Lanjutkan
                </button>
                <p class="tc-notice-footnote">Pemberitahuan ini hanya ditampilkan sekali setiap sesi kunjungan.</p>
            </div>
        </div>
    </div>
</div>
