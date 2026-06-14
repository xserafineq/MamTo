function getErrorContainer(input) {
    if (!input) {
        return null;
    }

    return input.closest('.form-field')
        || input.closest('.price-field')
        || input.closest('.create-auction-category-field')
        || input.closest('.location-field')
        || input.closest('.upload-img-field')
        || input.parentElement;
}

export function clearFieldError(input) {
    if (!input) {
        return;
    }

    input.classList.remove('is-invalid');

    const container = getErrorContainer(input);
    container?.querySelectorAll('.field-error, .invalid-feedback').forEach((element) => {
        element.remove();
    });

    container?.classList.remove('has-field-error');
}

export function setFieldError(input, message) {
    if (!input) {
        return;
    }

    clearFieldError(input);
    input.classList.add('is-invalid');

    const container = getErrorContainer(input);

    if (!container) {
        return;
    }

    container.classList.add('has-field-error');

    const feedback = document.createElement('div');
    feedback.className = 'field-error invalid-feedback d-block';
    feedback.textContent = message;
    container.appendChild(feedback);
}

export function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
}

export function isValidName(value) {
    return /^[\p{L}\s-]+$/u.test(value.trim());
}

export function isValidPhone(value) {
    return /^[0-9]{9}$/.test(value.trim());
}
