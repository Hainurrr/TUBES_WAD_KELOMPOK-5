export function initFloatingCart() {
  const popup = document.getElementById('cartPopup');
  const triggers = document.querySelectorAll('[data-cart-toggle]');
  const mainTrigger = document.querySelector('.tc-cart-trigger');

  if (!popup || triggers.length === 0) return;

  const setExpanded = (open) => {
    popup.classList.toggle('d-none', !open);
    if (mainTrigger) {
      mainTrigger.setAttribute('aria-expanded', String(open));
    }
  };

  triggers.forEach((btn) => {
    btn.addEventListener('click', () => {
      const isHidden = popup.classList.contains('d-none');
      setExpanded(isHidden);
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !popup.classList.contains('d-none')) {
      setExpanded(false);
    }
  });
}
