<div class="dropdown">
    <button class="tc-nav-icon-btn position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
        <i class="bi bi-bell"></i>
        @if(isset($notifications) && $notifications->count() > 0)
            <span class="position-absolute top-0 start-100 translate-middle p-1 rounded-circle" style="background-color: var(--tc-amber); border: 2px solid var(--tc-obsidian);">
                <span class="visually-hidden">Notifikasi baru</span>
            </span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end tc-dropdown-menu tc-notif-dropdown mt-2">
        <li class="px-2 py-2 d-flex justify-content-between align-items-center border-bottom mb-1">
            <span class="fw-bold text-dark small">Notifikasi</span>
            @if(isset($notifications) && $notifications->count() > 0)
                <a href="{{ route('notifications.readAll') }}" class="text-decoration-none small fw-semibold text-maroon">Tandai Semua Dibaca</a>
            @endif
        </li>

        @if(isset($notifications) && $notifications->count() > 0)
            @foreach($notifications as $notif)
                @php
                    $data = $notif->data;
                    $status = $data['status'] ?? 'info';
                    $tenantName = $data['tenant_name'] ?? 'Sistem';
                    $statusText = match ($status) {
                        'pending' => 'menunggu konfirmasi.',
                        'cooking' => 'sedang dimasak.',
                        'ready' => 'siap diambil di konter.',
                        'completed' => 'selesai.',
                        default => 'sedang diproses.',
                    };
                    $statusIcon = match ($status) {
                        'ready' => 'bi-bag-check',
                        'completed' => 'bi-check2-circle',
                        default => 'bi-clock',
                    };
                @endphp
                <li>
                    <a class="dropdown-item d-flex align-items-start gap-2 py-2" href="{{ route('notifications.read', $notif->id) }}">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ in_array($status, ['completed', 'ready']) ? 'tc-badge-emerald' : 'tc-badge-amber' }}" style="width: 36px; height: 36px; padding: 0;">
                            <i class="bi {{ $statusIcon }}"></i>
                        </div>
                        <div class="text-wrap">
                            <div class="small fw-semibold text-dark lh-sm">Pesanan di {{ $tenantName }} {{ $statusText }}</div>
                            <small class="text-muted" style="font-size: 0.72rem;">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                </li>
            @endforeach
        @else
            <li class="text-center py-4 px-3">
                <i class="bi bi-bell-slash fs-4 text-muted d-block mb-1"></i>
                <span class="text-muted small">Belum ada notifikasi baru.</span>
            </li>
        @endif

        <li class="border-top mt-1 pt-1">
            <a class="dropdown-item text-center small text-maroon fw-bold" href="{{ route('orders.history') }}">Lihat Semua Pesanan</a>
        </li>
    </ul>
</div>
