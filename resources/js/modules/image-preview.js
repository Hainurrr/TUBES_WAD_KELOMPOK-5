export function initImagePreview() {
  const inputs = document.querySelectorAll('input[type="file"][data-image-preview]');
  if (inputs.length === 0) return;

  inputs.forEach((input) => {
    input.addEventListener('change', () => {
      const targetSelector = input.getAttribute('data-image-preview');
      const target = targetSelector ? document.querySelector(targetSelector) : null;

      if (!target || !input.files || !input.files[0]) return;

      const reader = new FileReader();
      reader.onload = (event) => {
        if (event.target?.result) {
          target.src = event.target.result;
        }
      };
      reader.readAsDataURL(input.files[0]);
    });
  });
}
