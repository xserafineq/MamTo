import {
    clearFieldError,
    isValidEmail,
    isValidName,
    isValidPhone,
    setFieldError,
} from './validation';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#register-form');
    if (!form) {
        return;
    }

    const fields = {
        firstName: form.querySelector('#firstName'),
        lastName: form.querySelector('#lastName'),
        email: form.querySelector('#email'),
        phoneNumber: form.querySelector('#phoneNumber'),
        password: form.querySelector('#password'),
        passwordConfirmation: form.querySelector('#password_confirmation'),
    };

    Object.values(fields).forEach((input) => {
        input.addEventListener('input', () => clearFieldError(input));
    });

    form.addEventListener('submit', (event) => {
        let isValid = true;

        const firstName = fields.firstName.value.trim();
        if (!firstName) {
            setFieldError(fields.firstName, 'Imię jest wymagane.');
            isValid = false;
        } else if (firstName.length > 100) {
            setFieldError(fields.firstName, 'Imię może mieć maksymalnie 100 znaków.');
            isValid = false;
        } else if (!isValidName(firstName)) {
            setFieldError(fields.firstName, 'Imię może zawierać tylko litery.');
            isValid = false;
        }

        const lastName = fields.lastName.value.trim();
        if (!lastName) {
            setFieldError(fields.lastName, 'Nazwisko jest wymagane.');
            isValid = false;
        } else if (lastName.length > 100) {
            setFieldError(fields.lastName, 'Nazwisko może mieć maksymalnie 100 znaków.');
            isValid = false;
        } else if (!isValidName(lastName)) {
            setFieldError(fields.lastName, 'Nazwisko może zawierać tylko litery.');
            isValid = false;
        }

        const email = fields.email.value.trim();
        if (!email) {
            setFieldError(fields.email, 'Adres e-mail jest wymagany.');
            isValid = false;
        } else if (!isValidEmail(email)) {
            setFieldError(fields.email, 'Podaj prawidłowy adres e-mail.');
            isValid = false;
        } else if (email.length > 200) {
            setFieldError(fields.email, 'Adres e-mail może mieć maksymalnie 200 znaków.');
            isValid = false;
        }

        const phoneNumber = fields.phoneNumber.value.trim();
        if (!phoneNumber) {
            setFieldError(fields.phoneNumber, 'Numer telefonu jest wymagany.');
            isValid = false;
        } else if (!isValidPhone(phoneNumber)) {
            setFieldError(fields.phoneNumber, 'Numer telefonu musi składać się z 9 cyfr.');
            isValid = false;
        }

        const password = fields.password.value;
        if (!password) {
            setFieldError(fields.password, 'Hasło jest wymagane.');
            isValid = false;
        } else if (password.length < 8) {
            setFieldError(fields.password, 'Hasło musi mieć co najmniej 8 znaków.');
            isValid = false;
        }

        const passwordConfirmation = fields.passwordConfirmation.value;
        if (!passwordConfirmation) {
            setFieldError(fields.passwordConfirmation, 'Potwierdzenie hasła jest wymagane.');
            isValid = false;
        } else if (password !== passwordConfirmation) {
            setFieldError(fields.passwordConfirmation, 'Hasła nie są identyczne.');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
