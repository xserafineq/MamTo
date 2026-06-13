import { clearFieldError, isValidEmail, setFieldError } from './validation';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#login-form');
    if (!form) {
        return;
    }

    const emailInput = form.querySelector('#email');
    const passwordInput = form.querySelector('#password');

    form.querySelectorAll('input').forEach((input) => {
        input.addEventListener('input', () => clearFieldError(input));
    });

    form.addEventListener('submit', (event) => {
        let isValid = true;

        const email = emailInput.value.trim();
        if (!email) {
            setFieldError(emailInput, 'Adres e-mail jest wymagany.');
            isValid = false;
        } else if (!isValidEmail(email)) {
            setFieldError(emailInput, 'Podaj prawidłowy adres e-mail.');
            isValid = false;
        }

        const password = passwordInput.value;
        if (!password) {
            setFieldError(passwordInput, 'Hasło jest wymagane.');
            isValid = false;
        } else if (password.length < 8) {
            setFieldError(passwordInput, 'Hasło musi mieć co najmniej 8 znaków.');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
