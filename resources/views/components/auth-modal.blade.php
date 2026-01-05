<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="row g-0">
                <div class="col-lg-6 d-none d-lg-block position-relative bg-dark" style="min-height: 500px;">
                    <img src="https://images.unsplash.com/photo-1638569099509-2f46eb4bb94e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
                         alt="Food Background" 
                         class="w-100 h-100 object-fit-cover position-absolute top-0 start-0 opacity-75">
                    
                    <div class="position-absolute bottom-0 start-0 p-5 text-white z-1">
                        <h3 class="fw-bold display-6 mb-2">Nikmati Kuliner Kampus</h3>
                        <p class="fs-5 text-white-50">Pesan makanan favoritmu tanpa antri, langsung dari kelas.</p>
                    </div>
                </div>

                <div class="col-lg-6 bg-white p-4 p-md-5">
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="text-center mb-4">
                        <h4 class="fw-bold mb-1" id="modalTitle">Selamat Datang!</h4>
                        <p class="text-secondary small">Silakan masuk atau daftar untuk melanjutkan.</p>
                    </div>

                    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-pill" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold small py-2" id="login-tab" data-bs-toggle="tab" 
                                    data-bs-target="#login" type="button" role="tab">
                                Masuk
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold small py-2" id="register-tab" data-bs-toggle="tab" 
                                    data-bs-target="#register" type="button" role="tab">
                                Daftar
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabsContent">
                        <div class="tab-pane fade show active" id="login" role="tabpanel">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Nomor Identitas</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-person"></i></span>
                                        <input type="text" name="identity" class="form-control bg-light border-start-0 ps-0" placeholder="NIM / NIP / No. HP" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control bg-light border-start-0 ps-0" placeholder="••••••••" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember">
                                        <label class="form-check-label small text-secondary" for="remember">Ingat Saya</label>
                                    </div>
                                    <a href="#" class="text-decoration-none small fw-bold text-maroon">Lupa Password?</a>
                                </div>
                                <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold rounded-3 shadow-sm">
                                    Masuk Sekarang
                                </button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control bg-light" placeholder="Contoh: Budi Santoso" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Nomor Identitas</label>
                                    <input type="text" name="identity" class="form-control bg-light" placeholder="NIM / NIP / No. HP" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-secondary">Password</label>
                                    <input type="password" name="password" class="form-control bg-light" placeholder="Minimal 8 karakter" required>
                                </div>
                                <button type="submit" class="btn btn-maroon w-100 py-2 fw-bold rounded-3 shadow-sm">
                                    Daftar Akun Baru
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        const tabs = document.querySelectorAll('#authTabs .nav-link');
        
        tabs.forEach(tab => {
            tab.addEventListener('show.bs.tab', function(event) {
                // Reset semua ke gaya default (abu-abu)
                tabs.forEach(t => {
                    t.classList.remove('bg-white', 'shadow-sm', 'text-maroon');
                    t.classList.add('text-secondary');
                });

                // Set tab aktif menjadi putih & teks maroon
                event.target.classList.remove('text-secondary');
                event.target.classList.add('bg-white', 'shadow-sm', 'text-maroon');
            });
        });

        // Trigger klik pada tab aktif pertama kali agar style-nya benar
        document.querySelector('#authTabs .active').dispatchEvent(new Event('show.bs.tab'));
    });
</script>