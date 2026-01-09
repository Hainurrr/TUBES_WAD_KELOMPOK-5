<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm" style="height: 80px;">
    <div class="container">
        @php
            $homeRoute = url('/');
            if(Auth::check()) {
                if(Auth::user()->role === 'admin') $homeRoute = route('admin.dashboard');
                elseif(Auth::user()->role === 'tenant') $homeRoute = route('tenant.dashboard');
            }
        @endphp
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ $homeRoute }}" style="color: #800000;">
            <div class="rounded text-white d-flex align-items-center justify-content-center" 
                 style="width: 32px; height: 32px; background-color: #800000;">
                T
            </div>
            <span class="fs-4 tracking-tight">TelyuCan</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
            <div class="mx-auto d-none d-md-block w-50">
                <form action="{{ route('menus.index') }}" method="GET" class="position-relative">
                    <input type="text" name="q" 
                           class="form-control rounded-pill ps-5 border-secondary-subtle" 
                           placeholder="Cari makanan, minuman, atau tenant..." 
                           style="height: 45px;">
                    <i class="bi bi-search position-absolute text-secondary" 
                       style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                </form>
            </div>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                @auth

                    <div class="dropdown">
                        <button class="btn btn-ghost text-secondary position-relative me-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            @if(isset($notifications) && $notifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <li>
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom mb-2">
                                    <h6 class="dropdown-header fw-bold text-dark p-0 m-0">Notifikasi</h6>
                                    @if(isset($notifications) && $notifications->count() > 0)
                                        <a href="{{ route('notifications.readAll') }}" class="text-decoration-none small text-maroon">Tandai Baca Semua</a>
                                    @endif
                                </div>
                            </li>
                            
                            @if(isset($notifications) && $notifications->count() > 0)
                                @foreach($notifications as $notif)
                                    @php 
                                        $data = $notif->data; 
                                        $status = $data['status'] ?? 'info';
                                        $tenantName = $data['tenant_name'] ?? 'System';
                                    @endphp
                                    <li>
                                        <a class="dropdown-item px-3 py-2 d-flex align-items-start gap-3 bg-light" href="{{ route('notifications.read', $notif->id) }}">
                                            <div class="rounded-circle {{ in_array($status, ['completed', 'ready']) ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                <i class="bi {{ $status == 'ready' ? 'bi-bag-check-fill' : ($status == 'completed' ? 'bi-check-circle-fill' : 'bi-hourglass-split') }}"></i>
                                            </div>
                                            <div>
                                                <p class="mb-1 text-wrap small fw-bold text-dark">
                                                    Pesanan di {{ $tenantName }} 
                                                    @if($status == 'pending') menunggu konfirmasi.
                                                    @elseif($status == 'cooking') sedang dimasak.
                                                    @elseif($status == 'ready') siap diambil!
                                                    @elseif($status == 'completed') selesai.
                                                    @else sedang diproses.
                                                    @endif
                                                </p>
                                                <small class="text-secondary d-block" style="font-size: 10px;">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    </li>
                                    @if(!$loop->last) <li><hr class="dropdown-divider my-1"></li> @endif
                                @endforeach
                            @else
                                <li class="text-center py-4">
                                    <i class="bi bi-bell-slash fs-1 text-secondary opacity-25 mb-2 d-block"></i>
                                    <span class="text-secondary small">Belum ada notifikasi baru.</span>
                                </li>
                            @endif
                            
                            <li><hr class="dropdown-divider mt-2"></li>
                            <li><a class="dropdown-item text-center small text-maroon fw-bold py-2" href="{{ route('orders.history') }}">Lihat Semua Pesanan</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn border-0 p-0" type="button" data-bs-toggle="dropdown">
                            <img src="{{ Auth::user()->avatar && Auth::user()->avatar !== 'default_avatar.png' ? '/storage/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=800000&color=fff' }}"
                                 class="rounded-circle border object-fit-cover" width="40" height="40" alt="Avatar">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li class="px-3 py-2">
                                <p class="mb-0 fw-bold text-dark">{{ Auth::user()->name }}</p>
                                <small class="text-secondary" style="font-size: 11px;">{{ Auth::user()->email }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i> Profile & Settings</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i> Kelola Akun</a></li>
                            @endif
                            @if(Auth::user()->role === 'tenant')
                                <li><a class="dropdown-item" href="{{ route('tenant.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('tenant.menus.index') }}"><i class="bi bi-book-half me-2"></i> Kelola Menu</a></li>
                                <li><a class="dropdown-item" href="{{ route('tenant.orders.index') }}"><i class="bi bi-receipt me-2"></i> Pesanan Masuk</a></li>
                            @endif
                            @if(Auth::user()->role !== 'admin')
                                <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i class="bi bi-clock-history me-2"></i> Riwayat Pesanan</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Log out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-maroon rounded-pill px-4 fw-bold">
                        Masuk / Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>