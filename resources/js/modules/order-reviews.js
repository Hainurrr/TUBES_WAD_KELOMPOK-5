function bindStarGroup(containerSelector, inputEl) {
  const stars = document.querySelectorAll(`${containerSelector} .star-input`);
  if (!inputEl || stars.length === 0) return () => {};

  const render = (value) => {
    const numeric = Number(value) || 0;
    stars.forEach((star) => {
      const starValue = Number(star.getAttribute('data-value'));
      star.classList.toggle('bi-star-fill', starValue <= numeric);
      star.classList.toggle('bi-star', starValue > numeric);
    });
  };

  stars.forEach((star) => {
    star.addEventListener('click', () => {
      const value = star.getAttribute('data-value') || '';
      inputEl.value = value;
      render(value);
    });
  });

  return render;
}

export function initOrderReviews() {
  const reviewModal = document.getElementById('reviewModal');
  const editReviewModal = document.getElementById('editReviewModal');
  if (!reviewModal && !editReviewModal) return;

  const ratingInput = document.getElementById('ratingInput');
  const editRatingInput = document.getElementById('editRatingInput');
  const ratingError = document.getElementById('ratingError');
  const reviewForm = document.getElementById('reviewForm');

  const renderCreateStars = bindStarGroup('#reviewModal', ratingInput);
  const renderEditStars = bindStarGroup('#editReviewModal', editRatingInput);

  let orderMenus = {};
  const dataEl = document.getElementById('tcPastOrderMenus');
  if (dataEl) {
    try {
      orderMenus = JSON.parse(dataEl.textContent || '{}');
    } catch {
      orderMenus = {};
    }
  }

  document.querySelectorAll('[data-review-create]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const orderId = btn.getAttribute('data-order-id');
      const orderInput = document.getElementById('modalOrderId');
      const menuSelect = document.getElementById('modalMenuSelect');
      const commentInput = document.getElementById('comment');

      if (orderInput) orderInput.value = orderId || '';
      if (menuSelect) {
        menuSelect.innerHTML = '';
        (orderMenus[orderId] || []).forEach((menu) => {
          const option = document.createElement('option');
          option.value = menu.id;
          option.textContent = menu.name;
          menuSelect.appendChild(option);
        });
      }

      if (ratingInput) ratingInput.value = '';
      if (commentInput) commentInput.value = '';
      renderCreateStars(0);
      if (ratingError) ratingError.classList.add('d-none');
    });
  });

  document.querySelectorAll('[data-review-edit]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const form = document.getElementById('editReviewForm');
      const menuInput = document.getElementById('editMenuName');
      const commentInput = document.getElementById('editComment');

      if (form) form.action = btn.getAttribute('data-review-url') || '';
      if (menuInput) menuInput.value = btn.getAttribute('data-review-menu') || '';
      if (editRatingInput) editRatingInput.value = btn.getAttribute('data-review-rating') || '';
      if (commentInput) commentInput.value = btn.getAttribute('data-review-comment') || '';
      renderEditStars(btn.getAttribute('data-review-rating') || 0);

      if (window.bootstrap) {
        document.querySelectorAll('.modal').forEach((modal) => {
          const instance = window.bootstrap.Modal.getInstance(modal);
          if (instance) instance.hide();
        });
        if (editReviewModal) {
          new window.bootstrap.Modal(editReviewModal).show();
        }
      }
    });
  });

  if (reviewForm && ratingInput) {
    reviewForm.addEventListener('submit', (event) => {
      if (!ratingInput.value) {
        event.preventDefault();
        if (ratingError) ratingError.classList.remove('d-none');
      } else if (ratingError) {
        ratingError.classList.add('d-none');
      }
    });
  }
}
