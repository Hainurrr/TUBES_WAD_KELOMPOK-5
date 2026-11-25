function showPaymentToast(type, title, message) {
  const iconMap = {
    success: 'bi-check-circle-fill',
    warning: 'bi-exclamation-triangle-fill',
    danger: 'bi-x-circle-fill',
  };

  const alert = document.createElement('div');
  alert.className = `alert alert-${type} alert-dismissible fade show border-0 shadow-lg tc-toast-alert`;
  alert.setAttribute('role', 'alert');

  const row = document.createElement('div');
  row.className = 'd-flex align-items-center gap-3';

  const icon = document.createElement('i');
  icon.className = `bi ${iconMap[type] || 'bi-info-circle-fill'} fs-4`;

  const copy = document.createElement('div');
  const strong = document.createElement('strong');
  strong.className = 'd-block';
  strong.textContent = title;
  const span = document.createElement('span');
  span.className = 'small';
  span.textContent = message;

  copy.append(strong, span);
  row.append(icon, copy);

  const closeBtn = document.createElement('button');
  closeBtn.type = 'button';
  closeBtn.className = 'btn-close';
  closeBtn.setAttribute('data-bs-dismiss', 'alert');
  closeBtn.setAttribute('aria-label', 'Tutup');

  alert.append(row, closeBtn);
  document.body.appendChild(alert);
}

export function initPaymentSnap() {
  const root = document.querySelector('[data-snap-root]');
  if (!root) return;

  const snapToken = root.getAttribute('data-snap-token');
  const redirectUrl = root.getAttribute('data-redirect-url') || '/orders/history';
  const payBtn = root.querySelector('[data-snap-pay]');

  const openSnap = () => {
    if (!snapToken || !window.snap) return;

    window.snap.pay(snapToken, {
      onSuccess: () => {
        showPaymentToast('success', 'Pembayaran Berhasil', 'Kantin segera menyiapkan pesananmu.');
        setTimeout(() => {
          window.location.href = redirectUrl;
        }, 1800);
      },
      onPending: () => {
        showPaymentToast('warning', 'Menunggu Pembayaran', 'Selesaikan pembayaran di aplikasi pilihanmu.');
      },
      onError: () => {
        showPaymentToast('danger', 'Pembayaran Gagal', 'Silakan coba kembali dengan metode pembayaran lain.');
      },
      onClose: () => {},
    });
  };

  if (payBtn) {
    payBtn.addEventListener('click', openSnap);
  }

  setTimeout(openSnap, 600);
}
