const STORAGE_KEY = 'telyucan_academic_notice_ack_v1';

function isAcknowledged() {
  try {
    return window.sessionStorage.getItem(STORAGE_KEY) === '1';
  } catch {
    return false;
  }
}

function markAcknowledged() {
  try {
    window.sessionStorage.setItem(STORAGE_KEY, '1');
  } catch {
    // Ignore restricted storage environments.
  }
}

function showFallbackModal(modalEl, ackBtn) {
  const backdrop = document.createElement('div');
  backdrop.className = 'modal-backdrop fade show';
  document.body.appendChild(backdrop);
  document.body.classList.add('modal-open');

  modalEl.style.display = 'block';
  modalEl.removeAttribute('aria-hidden');
  modalEl.setAttribute('aria-modal', 'true');
  modalEl.classList.add('show');
  ackBtn?.focus();

  ackBtn?.addEventListener('click', () => {
    markAcknowledged();
    modalEl.classList.remove('show');
    modalEl.style.display = 'none';
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.removeAttribute('aria-modal');
    document.body.classList.remove('modal-open');
    backdrop.remove();
  }, { once: true });
}

export function initAcademicNotice() {
  const modalEl = document.querySelector('[data-academic-notice]');
  if (!modalEl || isAcknowledged()) return;

  const ackBtn = modalEl.querySelector('[data-academic-notice-ack]');
  const bootstrapApi = window.bootstrap;

  if (!bootstrapApi || !bootstrapApi.Modal) {
    showFallbackModal(modalEl, ackBtn);
    return;
  }

  const modal = bootstrapApi.Modal.getOrCreateInstance(modalEl, {
    backdrop: 'static',
    keyboard: false,
  });

  if (ackBtn) {
    ackBtn.addEventListener('click', () => {
      markAcknowledged();
      modal.hide();
    }, { once: true });
  }

  modalEl.addEventListener('shown.bs.modal', () => {
    ackBtn?.focus();
  }, { once: true });

  modal.show();
}
