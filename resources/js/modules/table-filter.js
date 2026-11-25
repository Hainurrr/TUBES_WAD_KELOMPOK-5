export function initTableFilter() {
  const inputs = document.querySelectorAll('[data-table-filter]');
  if (inputs.length === 0) return;

  inputs.forEach((input) => {
    const tableSelector = input.getAttribute('data-table-filter');
    const table = tableSelector ? document.querySelector(tableSelector) : null;
    if (!table) return;

    input.addEventListener('input', () => {
      const query = input.value.toLowerCase().trim();
      table.querySelectorAll('tbody [data-filter-row]').forEach((row) => {
        row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
      });
    });
  });
}
