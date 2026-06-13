import { clearFieldError, setFieldError } from '../auth/validation';

const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
const MAX_FILE_SIZE = 5 * 1024 * 1024;

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#create-auction-form');

    if (!form) {
        return;
    }

    const categorySelect = form.querySelector('#categoryId');
    const thumbnailInput = form.querySelector('#thumbnail');
    const imageInputs = form.querySelectorAll('input[name="images[]"]');

    form.querySelectorAll('input:not([type="file"]), textarea, select').forEach((input) => {
        input.addEventListener('input', () => clearFieldError(input));
        input.addEventListener('change', () => clearFieldError(input));
    });

    thumbnailInput?.addEventListener('change', () => {
        clearFieldError(thumbnailInput);
        previewImage(thumbnailInput);
    });

    imageInputs.forEach((input) => {
        input.addEventListener('change', () => {
            clearFieldError(input);
            previewImage(input);
        });
    });

    form.addEventListener('submit', (event) => {
        if (!validateForm(form, categorySelect, thumbnailInput, imageInputs)) {
            event.preventDefault();
        }
    });
});

function validateForm(form, categorySelect, thumbnailInput, imageInputs) {
    let isValid = true;

    const name = form.querySelector('#name');
    if (!name.value.trim()) {
        setFieldError(name, 'Tytuł aukcji jest wymagany.');
        isValid = false;
    } else if (name.value.trim().length > 255) {
        setFieldError(name, 'Tytuł może mieć maksymalnie 255 znaków.');
        isValid = false;
    }

    const description = form.querySelector('#description');
    if (description.value.length > 5000) {
        setFieldError(description, 'Opis może mieć maksymalnie 5000 znaków.');
        isValid = false;
    }

    if (!categorySelect.value) {
        setFieldError(categorySelect, 'Wybierz kategorię.');
        isValid = false;
    }

    const negotiable = form.querySelector('#negotiable');
    if (negotiable.value === '') {
        setFieldError(negotiable, 'Wybierz, czy cena jest do negocjacji.');
        isValid = false;
    }

    const price = form.querySelector('#price');
    const priceValue = parseFloat(price.value);
    if (!price.value) {
        setFieldError(price, 'Cena jest wymagana.');
        isValid = false;
    } else if (Number.isNaN(priceValue) || priceValue < 0) {
        setFieldError(price, 'Cena musi być liczbą większą lub równą 0.');
        isValid = false;
    } else if (priceValue > 99999999.99) {
        setFieldError(price, 'Cena jest zbyt wysoka.');
        isValid = false;
    }

    const location = form.querySelector('#location');
    if (!location.value.trim()) {
        setFieldError(location, 'Lokalizacja jest wymagana.');
        isValid = false;
    } else if (location.value.trim().length > 200) {
        setFieldError(location, 'Lokalizacja może mieć maksymalnie 200 znaków.');
        isValid = false;
    }

    if (!thumbnailInput.files.length) {
        if (form.dataset.mode !== 'edit') {
            setFieldError(thumbnailInput, 'Miniatura jest wymagana.');
            isValid = false;
        }
    } else if (!isValidImageFile(thumbnailInput.files[0])) {
        setFieldError(thumbnailInput, 'Miniatura musi być JPG, PNG lub WEBP (max 5 MB).');
        isValid = false;
    }

    let extraImagesCount = 0;
    imageInputs.forEach((input) => {
        if (!input.files.length) {
            return;
        }

        extraImagesCount++;

        if (!isValidImageFile(input.files[0])) {
            setFieldError(input, 'Zdjęcie musi być JPG, PNG lub WEBP (max 5 MB).');
            isValid = false;
        }
    });

    if (extraImagesCount > 4) {
        isValid = false;
    }

    return isValid;
}

function isValidImageFile(file) {
    return ALLOWED_IMAGE_TYPES.includes(file.type) && file.size <= MAX_FILE_SIZE;
}

function previewImage(input) {
    const card = input.closest('.upload-img-card');
    const preview = card?.querySelector('.upload-img-card__preview');

    if (!preview || !input.files.length) {
        return;
    }

    preview.src = URL.createObjectURL(input.files[0]);
    preview.classList.add('upload-img-card__preview--filled');
}
