// Frontend behavior: validation, image preview, submit to backend
(() => {
  const form = document.getElementById('sellForm');
  const photosInput = document.getElementById('photos');
  const previewEl = document.getElementById('preview');
  const status = document.getElementById('status');
  const submitBtn = document.getElementById('submitBtn');

  // Image preview (limit 6)
  photosInput.addEventListener('change', () => {
    previewEl.innerHTML = '';
    const files = Array.from(photosInput.files).slice(0, 6);
    files.forEach(file => {
      if (!file.type.startsWith('image/')) return;
      const img = document.createElement('img');
      img.alt = file.name;
      img.className = 'shadow-sm';
      const reader = new FileReader();
      reader.onload = e => img.src = e.target.result;
      reader.readAsDataURL(file);
      previewEl.appendChild(img);
    });
  });

  // Bootstrap validation + custom submit
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    e.stopPropagation();

    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    submitBtn.disabled = true;
    status.textContent = 'Enviando...';

    const formData = new FormData(form);

    // If you want to preview or transform, do it here
    try {
      const resp = await fetch('/api/submit', {
        method: 'POST',
        body: formData
      });
      if (!resp.ok) throw new Error(`Server error (${resp.status})`);
      const data = await resp.json();
      status.textContent = '¡Anuncio enviado! ID: ' + data.id;
      form.reset();
      previewEl.innerHTML = '';
      form.classList.remove('was-validated');
    } catch (err) {
      console.error(err);
      status.textContent = 'Error al enviar: ' + (err.message || err);
    } finally {
      submitBtn.disabled = false;
    }
  });
})();