export function initSidebar() {
  const sidebar = document.getElementById('sidebar-wrapper');
  const toggleBtn = document.getElementById('sidebarToggleInternal');
  const toggleIcon = document.getElementById('sidebarToggleIcon');

  if (!sidebar || !toggleBtn || !toggleIcon) return;

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');
    toggleIcon.className = isCollapsed
      ? 'bi bi-arrow-right-circle fs-5'
      : 'bi bi-arrow-left-circle fs-5';
  });
}
