import {
    clearFieldError,
    isValidEmail,
    isValidName,
    isValidPhone,
    setFieldError,
} from './auth/validation';

document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.querySelector('#profile-form');
    const passwordForm = document.querySelector('#password-form');

    if (profileForm) {
        bindClearErrors(profileForm);
        profileForm.addEventListener('submit', (event) => {
            if (!validateProfileForm(profileForm)) {
                event.preventDefault();
            }
        });
    }

    if (passwordForm) {
        bindClearErrors(passwordForm);
        passwordForm.addEventListener('submit', (event) => {
            if (!validatePasswordForm(passwordForm)) {
                event.preventDefault();
            }
        });
    }
});

function bindClearErrors(form) {
    form.querySelectorAll('input').forEach((input) => {
        input.addEventListener('input', () => clearFieldError(input));
    });
}

function validateProfileForm(form) {
    let isValid = true;

    const firstName = form.querySelector('#firstName');
    const lastName = form.querySelector('#lastName');
    const email = form.querySelector('#email');
    const phoneNumber = form.querySelector('#phoneNumber');

    const firstNameValue = firstName.value.trim();
    if (!firstNameValue) {
        setFieldError(firstName, 'Imię jest wymagane.');
        isValid = false;
    } else if (!isValidName(firstNameValue)) {
        setFieldError(firstName, 'Imię może zawierać tylko litery.');
        isValid = false;
    }

    const lastNameValue = lastName.value.trim();
    if (!lastNameValue) {
        setFieldError(lastName, 'Nazwisko jest wymagane.');
        isValid = false;
    } else if (!isValidName(lastNameValue)) {
        setFieldError(lastName, 'Nazwisko może zawierać tylko litery.');
        isValid = false;
    }

    const emailValue = email.value.trim();
    if (!emailValue) {
        setFieldError(email, 'Adres e-mail jest wymagany.');
        isValid = false;
    } else if (!isValidEmail(emailValue)) {
        setFieldError(email, 'Podaj prawidłowy adres e-mail.');
        isValid = false;
    }

    const phoneValue = phoneNumber.value.trim();
    if (!phoneValue) {
        setFieldError(phoneNumber, 'Numer telefonu jest wymagany.');
        isValid = false;
    } else if (!isValidPhone(phoneValue)) {
        setFieldError(phoneNumber, 'Numer telefonu musi składać się z 9 cyfr.');
        isValid = false;
    }

    return isValid;
}

function validatePasswordForm(form) {
    let isValid = true;

    const currentPassword = form.querySelector('#current_password');
    const password = form.querySelector('#password');
    const passwordConfirmation = form.querySelector('#password_confirmation');

    if (!currentPassword.value) {
        setFieldError(currentPassword, 'Podaj obecne hasło.');
        isValid = false;
    }

    if (!password.value) {
        setFieldError(password, 'Nowe hasło jest wymagane.');
        isValid = false;
    } else if (password.value.length < 8) {
        setFieldError(password, 'Nowe hasło musi mieć co najmniej 8 znaków.');
        isValid = false;
    }

    if (!passwordConfirmation.value) {
        setFieldError(passwordConfirmation, 'Potwierdzenie hasła jest wymagane.');
        isValid = false;
    } else if (password.value !== passwordConfirmation.value) {
        setFieldError(passwordConfirmation, 'Hasła nie są identyczne.');
        isValid = false;
    }

    return isValid;
}
