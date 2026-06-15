document.addEventListener('DOMContentLoaded', () => {
    initAdminImageReplaceForms();
    initAdminImageLightbox();
});

function initAdminImageReplaceForms() {
    document.querySelectorAll('[data-admin-image-replace-form]').forEach((form) => {
        const input = form.querySelector('input[type="file"]');

        if (!input) {
            return;
        }

        input.addEventListener('change', () => {
            if (!input.files?.length) {
                return;
            }

            const confirmed = window.confirm('Czy na pewno podmienić to zdjęcie systemowe?');

            if (confirmed) {
                form.submit();
            } else {
                input.value = '';
            }
        });
    });
}

function initAdminImageLightbox() {
    const modalEl = document.getElementById('adminImageLightbox');
    const imageEl = document.getElementById('adminImageLightboxImage');
    const captionEl = document.getElementById('adminImageLightboxCaption');

    if (!modalEl || !imageEl || !captionEl) {
        return;
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

    document.querySelectorAll('[data-admin-image-preview]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const previewImage = trigger.querySelector('img');
            const alt = trigger.dataset.imageAlt || previewImage?.alt || '';
            const src = trigger.dataset.imageSrc || previewImage?.src || '';

            imageEl.src = src;
            imageEl.alt = alt;
            captionEl.textContent = alt;
            modal.show();
        });
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        imageEl.removeAttribute('src');
        imageEl.alt = '';
        captionEl.textContent = '';
    });
}
