<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 28px;">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-4 p-xl-5 text-white" style="background-color: var(--tc-obsidian);">
                    <div>
                        <div class="tc-brand mb-4">
                            <svg width="32" height="32" viewBox="0 0 36 36" fill="none" aria-hidden="true">
                                <rect width="36" height="36" rx="11" fill="#B9112E"/>
                                <path d="M9.5 11.5H26.5V15.5H20.25V26H15.75V15.5H9.5V11.5Z" fill="#FFFFFF"/>
                                <rect x="21.5" y="21.5" width="5" height="4.5" rx="1.5" fill="#F59E0B"/>
                            </svg>
                            <span>TelyuCan</span>
                        </div>
                        <h3 class="tc-display fw-bold h3 mb-3">Pesan cepat dari kelas, ambil tanpa antri.</h3>
                        <p class="small mb-0" style="color: #CBD5E1; line-height: 1.55;">Masuk untuk menyimpan keranjang multi-gerai, membayar lewat Midtrans, dan memantau progres dapur secara langsung.</p>
                    </div>
                    <div class="d-flex flex-column gap-2 small" style="color: #CBD5E1;">
                        <div><i class="bi bi-check2-circle me-2" style="color: #34D399;"></i>Pilih kantin TULT, GKU, dan Pujasera</div>
                        <div><i class="bi bi-check2-circle me-2" style="color: #34D399;"></i>Pembayaran digital QRIS &amp; E-Wallet</div>
                        <div><i class="bi bi-check2-circle me-2" style="color: #34D399;"></i>Notifikasi saat pesanan siap diambil</div>
                    </div>
                </div>

                <div class="col-lg-7 bg-white p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="tc-display fw-bold mb-0" id="authModalLabel">Masuk atau Daftar</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <ul class="nav nav-pills nav-fill mb-4 p-1 rounded-pill" style="background-color: var(--tc-surface-muted);" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold small py-2" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">
                                Masuk
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold small py-2" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">
                                Daftar
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabsContent">
                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="modalLoginEmail" class="form-label">Alamat Email</label>
                                    <input type="email" id="modalLoginEmail" name="email" class="form-control" placeholder="nama@email.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modalLoginPassword" class="form-label">Kata Sandi</label>
                                    <input type="password" id="modalLoginPassword" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" name="remember" id="modalRemember">
                                    <label class="form-check-label small text-secondary" for="modalRemember">Ingat saya di perangkat ini</label>
                                </div>
                                <button type="submit" class="btn btn-tc-primary w-100 py-2">Masuk Sekarang</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="modalRegName" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="modalRegName" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modalRegEmail" class="form-label">Alamat Email</label>
                                    <input type="email" id="modalRegEmail" name="email" class="form-control" placeholder="nama@email.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modalRegIdentity" class="form-label">NIM / NIP <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <input type="text" id="modalRegIdentity" name="identity_number" class="form-control" placeholder="Nomor identitas kampus">
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label for="modalRegPass" class="form-label">Kata Sandi</label>
                                        <input type="password" id="modalRegPass" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="modalRegPassConf" class="form-label">Konfirmasi</label>
                                        <input type="password" id="modalRegPassConf" name="password_confirmation" class="form-control" placeholder="Ulangi sandi" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-tc-primary w-100 py-2">Daftar Akun Baru</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#authTabs .nav-link');
        function syncAuthTabs(activeTab) {
            tabs.forEach(function(t) {
                t.style.backgroundColor = 'transparent';
                t.style.color = '#4B5563';
            });
            if (activeTab) {
                activeTab.style.backgroundColor = '#101820';
                activeTab.style.color = '#FFFFFF';
            }
        }
        tabs.forEach(function(tab) {
            tab.addEventListener('show.bs.tab', function(event) { syncAuthTabs(event.target); });
        });
        syncAuthTabs(document.querySelector('#authTabs .nav-link.active'));
    });
</script>
