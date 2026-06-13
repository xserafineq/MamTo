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
    const salaryType = form.querySelector('#salaryType');
    const price = form.querySelector('#price');

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

    categorySelect?.addEventListener('change', () => toggleJobMode(form, categorySelect));
    salaryType?.addEventListener('change', () => toggleSalaryPrice(form, salaryType, price));

    toggleJobMode(form, categorySelect);
    toggleSalaryPrice(form, salaryType, price);

    form.addEventListener('submit', (event) => {
        if (!validateForm(form, categorySelect, thumbnailInput, imageInputs)) {
            event.preventDefault();
        }
    });
});

function getPracaIds(form) {
    try {
        return JSON.parse(form.dataset.pracaIds || '[]').map((id) => Number(id));
    } catch {
        return [];
    }
}

function isJobCategory(form, categorySelect) {
    const categoryId = Number(categorySelect?.value);

    return categoryId > 0 && getPracaIds(form).includes(categoryId);
}

function toggleJobMode(form, categorySelect) {
    const isJob = isJobCategory(form, categorySelect);
    const negotiableBox = form.querySelector('#negotiable-box');
    const negotiable = form.querySelector('#negotiable');
    const salaryType = form.querySelector('#salaryType');
    const thumbnailLabel = form.querySelector('#thumbnail-label');
    const auctionImageNote = form.querySelector('#auction-image-note');
    const extraImages = form.querySelectorAll('.job-extra-image');

    form.classList.toggle('is-job-form', isJob);

    if (auctionImageNote) {
        auctionImageNote.hidden = isJob;
    }

    if (negotiableBox) {
        negotiableBox.hidden = isJob;
    }

    if (negotiable) {
        negotiable.required = !isJob;
        if (isJob) {
            negotiable.value = '0';
        }
    }

    if (salaryType) {
        salaryType.hidden = !isJob;
        salaryType.required = isJob;
        if (!isJob) {
            salaryType.value = '';
        }
    }

    extraImages.forEach((element) => {
        element.hidden = isJob;
    });

    if (thumbnailLabel) {
        thumbnailLabel.textContent = isJob ? 'Logo firmy (opcjonalne)' : 'Miniatura';
    }

    updateFieldPlaceholder(form.querySelector('#name'), isJob);
    updateFieldPlaceholder(form.querySelector('#description'), isJob);
    updateFieldPlaceholder(form.querySelector('#price'), isJob);
    updateFieldPlaceholder(form.querySelector('#location'), isJob);

    toggleSalaryPrice(form, salaryType, form.querySelector('#price'));
}

function toggleSalaryPrice(form, salaryType, price) {
    if (!salaryType || !price) {
        return;
    }

    const isJob = form.classList.contains('is-job-form');
    const isNegotiableSalary = isJob && salaryType.value === 'do uzgodnienia';

    price.required = !isNegotiableSalary;
    price.disabled = isNegotiableSalary;

    if (isNegotiableSalary) {
        price.value = '';
        clearFieldError(price);
    }
}

function updateFieldPlaceholder(field, isJob) {
    if (!field) {
        return;
    }

    field.placeholder = isJob
        ? field.dataset.jobPlaceholder || field.placeholder
        : field.dataset.auctionPlaceholder || field.placeholder;
}

function validateForm(form, categorySelect, thumbnailInput, imageInputs) {
    let isValid = true;
    const isJob = isJobCategory(form, categorySelect);

    const name = form.querySelector('#name');
    if (!name.value.trim()) {
        setFieldError(name, isJob ? 'Stanowisko jest wymagane.' : 'Tytuł aukcji jest wymagany.');
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

    const salaryType = form.querySelector('#salaryType');
    if (isJob && !salaryType.value) {
        setFieldError(salaryType, 'Wybierz rodzaj wynagrodzenia.');
        isValid = false;
    }

    if (!isJob) {
        const negotiable = form.querySelector('#negotiable');
        if (negotiable.value === '') {
            setFieldError(negotiable, 'Wybierz, czy cena jest do negocjacji.');
            isValid = false;
        }
    }

    const price = form.querySelector('#price');
    const salaryNegotiable = isJob && salaryType?.value === 'do uzgodnienia';
    const priceValue = parseFloat(price.value);

    if (!salaryNegotiable) {
        if (!price.value) {
            setFieldError(price, isJob ? 'Wynagrodzenie jest wymagane.' : 'Cena jest wymagana.');
            isValid = false;
        } else if (Number.isNaN(priceValue) || priceValue < 0) {
            setFieldError(price, isJob ? 'Wynagrodzenie musi być liczbą większą lub równą 0.' : 'Cena musi być liczbą większą lub równą 0.');
            isValid = false;
        } else if (priceValue > 99999999.99) {
            setFieldError(price, isJob ? 'Wynagrodzenie jest zbyt wysokie.' : 'Cena jest zbyt wysoka.');
            isValid = false;
        }
    }

    const location = form.querySelector('#location');
    if (!location.value.trim()) {
        setFieldError(location, 'Lokalizacja jest wymagana.');
        isValid = false;
    } else if (location.value.trim().length > 200) {
        setFieldError(location, 'Lokalizacja może mieć maksymalnie 200 znaków.');
        isValid = false;
    }

    const thumbnailRequired = !isJob && form.dataset.mode !== 'edit';
    if (thumbnailRequired && !thumbnailInput.files.length) {
        setFieldError(thumbnailInput, 'Miniatura jest wymagana.');
        isValid = false;
    } else if (thumbnailInput.files.length && !isValidImageFile(thumbnailInput.files[0])) {
        setFieldError(thumbnailInput, 'Miniatura musi być JPG, PNG lub WEBP (max 5 MB).');
        isValid = false;
    }

    if (!isJob) {
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
