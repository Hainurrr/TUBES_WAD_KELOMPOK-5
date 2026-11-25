function normalizeDate(dateStr) {
  const parts = String(dateStr).split('-');
  if (parts.length !== 3) return dateStr;
  return `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
}

export function initTenantOps() {
  const addRowBtn = document.querySelector('[data-add-manual-item]');
  const itemsContainer = document.getElementById('manual-items-container');

  if (addRowBtn && itemsContainer) {
    let itemIndex = itemsContainer.querySelectorAll('.item-row').length || 1;

    addRowBtn.addEventListener('click', () => {
      const firstRow = itemsContainer.querySelector('.item-row');
      if (!firstRow) return;

      const newRow = firstRow.cloneNode(true);
      newRow.querySelectorAll('[name]').forEach((field) => {
        field.name = field.name.replace(/items\[\d+\]/, `items[${itemIndex}]`);
      });

      const select = newRow.querySelector('select');
      const qtyInput = newRow.querySelector('input[type="number"]');
      if (select) select.value = '';
      if (qtyInput) qtyInput.value = '1';

      itemsContainer.appendChild(newRow);
      itemIndex += 1;
    });
  }

  const orderDates = document.querySelectorAll('.order-date[data-date]');
  if (orderDates.length > 0) {
    const year = new Date().getFullYear();
    fetch(`https://api-harilibur.vercel.app/api?year=${year}`)
      .then((res) => res.json())
      .then((data) => {
        const holidays = {};
        if (Array.isArray(data)) {
          data.forEach((h) => {
            holidays[normalizeDate(h.holiday_date)] = h.holiday_name;
          });
        }

        orderDates.forEach((el) => {
          const dateStr = el.getAttribute('data-date');
          if (!dateStr || !holidays[dateStr]) return;

          const existing = el.querySelector('.badge-holiday');
          if (existing) existing.remove();

          const badge = document.createElement('span');
          badge.className = 'tc-badge tc-badge-amber ms-2 badge-holiday';
          badge.title = 'Hari Libur Nasional';

          const icon = document.createElement('i');
          icon.className = 'bi bi-calendar-event';
          badge.append(icon, document.createTextNode(holidays[dateStr]));
          el.appendChild(badge);
        });
      })
      .catch(() => {});
  }

  const holidayAlert = document.querySelector('#holidayAlert[data-holiday-alert]');
  if (holidayAlert) {
    fetch('https://dayoffapi.vercel.app/api')
      .then((res) => res.json())
      .then((data) => {
        const today = new Date().toISOString().split('T')[0];
        if (Array.isArray(data) && data.some((holiday) => holiday.tanggal === today)) {
          holidayAlert.classList.remove('d-none');
        }
      })
      .catch(() => {});
  }
}
