export function initCheckout() {
  const confirmBtn = document.querySelector('[data-checkout-confirm]');
  const checkoutForm = document.getElementById('checkoutForm');
  const overlay = document.getElementById('loadingOverlay');
  const modalEl = document.getElementById('confirmationModal');

  if (!confirmBtn || !checkoutForm) return;

  confirmBtn.addEventListener('click', () => {
    const modal = modalEl && window.bootstrap ? window.bootstrap.Modal.getInstance(modalEl) : null;
    if (modal) modal.hide();

    setTimeout(() => {
      if (overlay) overlay.classList.add('is-visible');
      confirmBtn.disabled = true;
      checkoutForm.submit();
    }, 200);
  });
}
