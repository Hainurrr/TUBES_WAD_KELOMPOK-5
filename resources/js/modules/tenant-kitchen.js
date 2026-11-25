function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

export function initTenantKitchen() {
  const root = document.querySelector('[data-kds-root]');
  if (!root) return;

  const statusUrlBase = root.getAttribute('data-status-url-base') || '/tenant/orders';
  const tenantToggleUrl = root.getAttribute('data-tenant-toggle-url');
  const csrfToken = getCsrfToken();

  setInterval(() => {
    window.location.reload();
  }, 30000);

  root.addEventListener('click', (event) => {
    const btn = event.target.closest('[data-order-status]');
    if (!btn) return;

    const orderId = btn.getAttribute('data-order-id');
    const newStatus = btn.getAttribute('data-order-status');
    if (!orderId || !newStatus || !window.confirm('Ubah status pesanan ini?')) return;

    fetch(`${statusUrlBase}/${orderId}/update-status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify({ status: newStatus }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          window.location.reload();
        } else {
          window.alert('Gagal memperbarui status pesanan.');
        }
      })
      .catch(() => {
        window.alert('Terjadi kesalahan sistem saat memperbarui status.');
      });
  });

  const statusSwitch = document.getElementById('tenantStatusSwitch');
  const statusLabel = document.getElementById('tenantStatusLabel');

  if (statusSwitch && statusLabel && tenantToggleUrl) {
    statusSwitch.addEventListener('change', () => {
      const isOpen = statusSwitch.checked ? 1 : 0;
      const applyLabel = (open) => {
        statusLabel.textContent = open ? 'Buka' : 'Tutup';
        statusLabel.style.color = open ? '#34D399' : '#FCA5A5';
      };

      applyLabel(isOpen);

      fetch(tenantToggleUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ is_open: isOpen }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (!data.success) {
            window.alert('Gagal mengubah status gerai.');
            statusSwitch.checked = !statusSwitch.checked;
            applyLabel(statusSwitch.checked ? 1 : 0);
          }
        })
        .catch(() => {
          statusSwitch.checked = !statusSwitch.checked;
          applyLabel(statusSwitch.checked ? 1 : 0);
        });
    });
  }
}
