export function initHeroBento() {
  const form = document.getElementById('tcHeroSearchForm');
  if (!form) return;

  const zoneButtons = form.querySelectorAll('.tc-zone-tab');
  const zoneInput = document.getElementById('tcZoneInput');
  const zoneHint = document.getElementById('tcZoneHint');
  const emeraldTile = document.getElementById('tcEmeraldZoneTile');
  const emeraldTitle = document.getElementById('tcEmeraldZoneTitle');
  const emeraldSub = document.getElementById('tcEmeraldZoneSub');

  const featuredTile = document.getElementById('tcFeaturedTile');
  const featuredImg = document.getElementById('tcFeaturedImg');
  const featuredTitle = document.getElementById('tcFeaturedTitle');
  const featuredTenant = document.getElementById('tcFeaturedTenant');
  const featuredPrice = document.getElementById('tcFeaturedPrice');

  let menuPool = [];
  const catalogEl = document.getElementById('tcHeroMenuCatalog');
  if (catalogEl) {
    try {
      menuPool = JSON.parse(catalogEl.textContent || '[]');
    } catch {
      menuPool = [];
    }
  }

  const defaultFeatured = featuredTile
    ? {
        name: featuredTitle.textContent,
        tenant: featuredTenant.textContent,
        price: featuredPrice.textContent,
        image: featuredImg.src,
        url: featuredTile.getAttribute('href'),
      }
    : null;

  let spotlightTimer = null;

  zoneButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      const selected = btn.getAttribute('data-zone') || '';
      if (zoneInput) zoneInput.value = selected;

      zoneButtons.forEach((item) => {
        const isCurrent = item === btn;
        item.classList.toggle('is-active', isCurrent);
        item.setAttribute('aria-pressed', String(isCurrent));
      });

      if (zoneHint) {
        zoneHint.textContent = selected
          ? `Menyaring area: ${selected}`
          : 'Menampilkan semua kantin buka di kampus';
      }

      if (emeraldTile && emeraldTitle && emeraldSub) {
        const base = emeraldTile.getAttribute('data-base-url') || '/search';
        if (selected) {
          emeraldTitle.textContent = selected;
          emeraldSub.textContent = `Lihat semua gerai buka dan menu di area ${selected}.`;
          const queryZone = selected.replace(/^Kantin\s+/i, '');
          emeraldTile.setAttribute('href', `${base}?location=${encodeURIComponent(queryZone)}`);
        } else {
          emeraldTitle.textContent = 'TULT, GKU & Pujasera';
          emeraldSub.textContent = 'Cek status buka tiap gerai dan stok menu sebelum berjalan ke kantin.';
          emeraldTile.setAttribute('href', base);
        }

        emeraldTile.classList.add('is-spotlight');
        clearTimeout(spotlightTimer);
        spotlightTimer = setTimeout(() => {
          emeraldTile.classList.remove('is-spotlight');
        }, 550);
      }

      if (featuredTile && defaultFeatured && Array.isArray(menuPool) && menuPool.length > 0) {
        const needle = selected.toLowerCase().replace(/^kantin\s+/i, '');
        const matched = selected
          ? menuPool.find(
              (m) =>
                m.tenant.toLowerCase().includes(needle) ||
                (m.category && m.category.toLowerCase().includes(needle))
            ) || defaultFeatured
          : defaultFeatured;

        featuredTile.classList.add('is-syncing');
        setTimeout(() => {
          featuredImg.src = matched.image;
          featuredImg.alt = matched.name;
          featuredTitle.textContent = matched.name;
          featuredTenant.textContent = matched.tenant;
          featuredPrice.textContent = matched.price;
          featuredTile.setAttribute('href', matched.url);
          featuredTile.classList.remove('is-syncing');
        }, 120);
      }
    });
  });
}
