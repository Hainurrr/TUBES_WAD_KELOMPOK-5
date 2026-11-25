const WILAYAH_API = 'https://www.emsifa.com/api-wilayah-indonesia/api';

function populateSelect(selectEl, placeholder, items) {
  selectEl.innerHTML = '';
  const defaultOption = document.createElement('option');
  defaultOption.value = '';
  defaultOption.textContent = placeholder;
  selectEl.appendChild(defaultOption);

  items.forEach((item) => {
    const option = document.createElement('option');
    option.value = item.id;
    option.textContent = item.name;
    option.dataset.name = item.name;
    selectEl.appendChild(option);
  });
}

export function initWilayahForm() {
  const provinceSelect = document.getElementById('provinceSelect');
  const regencySelect = document.getElementById('regencySelect');
  const districtSelect = document.getElementById('districtSelect');
  const finalCityInput = document.getElementById('finalCity');
  const addressInput = document.getElementById('address');

  if (provinceSelect && regencySelect && districtSelect && finalCityInput) {
    let selectedProvinceName = '';
    let selectedRegencyName = '';

    fetch(`${WILAYAH_API}/provinces.json`)
      .then((res) => res.json())
      .then((data) => populateSelect(provinceSelect, 'Pilih Provinsi', data))
      .catch(() => {
        populateSelect(provinceSelect, 'Gagal memuat wilayah', []);
      });

    provinceSelect.addEventListener('change', () => {
      const provId = provinceSelect.value;
      selectedProvinceName = provinceSelect.options[provinceSelect.selectedIndex]?.dataset?.name || '';

      populateSelect(regencySelect, 'Memuat...', []);
      regencySelect.disabled = true;
      populateSelect(districtSelect, 'Pilih kota terlebih dahulu', []);
      districtSelect.disabled = true;

      if (!provId) return;

      fetch(`${WILAYAH_API}/regencies/${provId}.json`)
        .then((res) => res.json())
        .then((data) => {
          populateSelect(regencySelect, 'Pilih Kota / Kabupaten', data);
          regencySelect.disabled = false;
        })
        .catch(() => {
          populateSelect(regencySelect, 'Gagal memuat kota', []);
        });
    });

    regencySelect.addEventListener('change', () => {
      const regId = regencySelect.value;
      selectedRegencyName = regencySelect.options[regencySelect.selectedIndex]?.dataset?.name || '';

      if (selectedRegencyName && selectedProvinceName) {
        finalCityInput.value = `${selectedRegencyName}, ${selectedProvinceName}`;
      }

      populateSelect(districtSelect, 'Memuat...', []);
      districtSelect.disabled = true;

      if (!regId) return;

      fetch(`${WILAYAH_API}/districts/${regId}.json`)
        .then((res) => res.json())
        .then((data) => {
          populateSelect(districtSelect, 'Pilih Kecamatan', data);
          districtSelect.disabled = false;
        })
        .catch(() => {
          populateSelect(districtSelect, 'Gagal memuat kecamatan', []);
        });
    });

    districtSelect.addEventListener('change', () => {
      const districtName = districtSelect.options[districtSelect.selectedIndex]?.dataset?.name || '';
      if (addressInput && addressInput.value.trim() === '' && districtName) {
        addressInput.value = `Kec. ${districtName}`;
      }
    });
  }

  const categorySelect = document.getElementById('category_select');
  const customCategoryWrapper = document.getElementById('custom_category_wrapper');
  const customCategoryInput = document.getElementById('category_custom');

  if (categorySelect && customCategoryWrapper && customCategoryInput) {
    const syncCustomCategory = () => {
      const isCustom = categorySelect.value === 'Lainnya';
      customCategoryWrapper.classList.toggle('d-none', !isCustom);
      customCategoryInput.required = isCustom;
    };

    categorySelect.addEventListener('change', syncCustomCategory);
    syncCustomCategory();
  }
}
