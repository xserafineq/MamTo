export function clearFieldError(input) {
    input.classList.remove('is-invalid');
    const feedback = input.parentElement.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.remove();
    }
}

export function setFieldError(input, message) {
    clearFieldError(input);
    input.classList.add('is-invalid');

    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.textContent = message;
    input.parentElement.appendChild(feedback);
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
