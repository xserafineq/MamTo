import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';
import { clearFieldError, setFieldError } from '../auth/validation';
import {
    buildLabeledResults,
    normalizeLocationText,
    reverseGeocode,
    searchLocations,
} from './geocoding';

const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
const MAX_FILE_SIZE = 5 * 1024 * 1024;
const DEFAULT_CENTER = [52.0693, 19.4803];
const DEFAULT_ZOOM = 6;
const SELECTED_ZOOM = 14;

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#create-auction-form');

    if (!form) {
        return;
    }

    const categoryInput = form.querySelector('#categoryId');
    const categoryPicker = form.querySelector('.create-auction-category-picker');
    const thumbnailInput = form.querySelector('#thumbnail');
    const imageInputs = form.querySelectorAll('input[name="images[]"]');
    const salaryType = form.querySelector('#salaryType');
    const price = form.querySelector('#price');

    initLocationMap(form);

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

    categoryInput?.addEventListener('change', () => {
        clearCategoryFieldError(form);
        toggleJobMode(form);
    });

    categoryPicker?.addEventListener('category-picker:select', () => {
        clearCategoryFieldError(form);
        toggleJobMode(form);
    });
    salaryType?.addEventListener('change', () => toggleSalaryPrice(form, salaryType, price));

    toggleJobMode(form);
    toggleSalaryPrice(form, salaryType, price);

    form.addEventListener('submit', (event) => {
        if (!validateForm(form, categoryInput, thumbnailInput, imageInputs)) {
            event.preventDefault();
            scrollToFirstError(form);
        }
    });

    initServerSideErrors(form);
});

function initLocationMap(form) {
    const locationInput = form.querySelector('#location');
    const latInput = form.querySelector('#lat');
    const lngInput = form.querySelector('#lng');
    const suggestionsEl = form.querySelector('#location-suggestions');
    const mapEl = form.querySelector('#map');

    if (!locationInput || !latInput || !lngInput || !suggestionsEl || !mapEl) {
        return;
    }

    const map = L.map(mapEl).setView(DEFAULT_CENTER, DEFAULT_ZOOM);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    let marker = null;
    let searchTimeout = null;
    let lastSearchResults = [];

    const setCoordinates = (lat, lng, label = null) => {
        latInput.value = Number(lat).toFixed(8);
        lngInput.value = Number(lng).toFixed(8);

        if (label) {
            locationInput.value = label.length > 200 ? label.slice(0, 200) : label;
        }

        clearFieldError(locationInput);

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }

        map.setView([lat, lng], SELECTED_ZOOM);
    };

    const hideSuggestions = () => {
        suggestionsEl.innerHTML = '';
        suggestionsEl.style.display = 'none';
    };

    const tryAutoSelectMatch = (query, labeledResults) => {
        const normalizedQuery = normalizeLocationText(query);

        if (!normalizedQuery) {
            return false;
        }

        const match = labeledResults.find(
            (item) => normalizeLocationText(item.label) === normalizedQuery,
        );

        if (!match) {
            return false;
        }

        setCoordinates(match.result.lat, match.result.lon, match.label);
        hideSuggestions();
        return true;
    };

    const showSuggestions = (labeledResults) => {
        suggestionsEl.innerHTML = '';

        if (!labeledResults.length) {
            suggestionsEl.style.display = 'none';
            return;
        }

        labeledResults.forEach(({ result, label }) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'location-suggestions__item';
            item.textContent = label;

            item.addEventListener('click', () => {
                setCoordinates(result.lat, result.lon, label);
                hideSuggestions();
            });

            suggestionsEl.appendChild(item);
        });

        suggestionsEl.style.display = 'block';
    };

    const runLocationSearch = async (query) => {
        try {
            const results = await searchLocations(query);
            lastSearchResults = buildLabeledResults(results);

            if (tryAutoSelectMatch(query, lastSearchResults)) {
                return;
            }

            showSuggestions(lastSearchResults);
        } catch {
            lastSearchResults = [];
            hideSuggestions();
        }
    };

    map.on('click', async (event) => {
        const { lat, lng } = event.latlng;

        try {
            const label = await reverseGeocode(lat, lng);
            setCoordinates(lat, lng, label);
            hideSuggestions();
        } catch {
            setCoordinates(lat, lng);
            setFieldError(locationInput, 'Nie udało się pobrać adresu. Spróbuj ponownie.');
        }
    });

    locationInput.addEventListener('input', () => {
        latInput.value = '';
        lngInput.value = '';

        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }

        clearTimeout(searchTimeout);
        const query = locationInput.value.trim();

        if (query.length < 3) {
            lastSearchResults = [];
            hideSuggestions();
            return;
        }

        searchTimeout = setTimeout(() => runLocationSearch(query), 400);
    });

    locationInput.addEventListener('blur', () => {
        setTimeout(() => {
            if (latInput.value && lngInput.value) {
                return;
            }

            const query = locationInput.value.trim();

            if (query.length >= 3 && lastSearchResults.length) {
                tryAutoSelectMatch(query, lastSearchResults);
            }
        }, 150);
    });

    locationInput.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();

        const query = locationInput.value.trim();

        if (query.length < 3 || !lastSearchResults.length) {
            return;
        }

        if (tryAutoSelectMatch(query, lastSearchResults)) {
            return;
        }

        const first = lastSearchResults[0];
        setCoordinates(first.result.lat, first.result.lon, first.label);
        hideSuggestions();
    });

    document.addEventListener('click', (event) => {
        if (!suggestionsEl.contains(event.target) && event.target !== locationInput) {
            hideSuggestions();
        }
    });

    const initialLat = parseFloat(latInput.value);
    const initialLng = parseFloat(lngInput.value);

    if (!Number.isNaN(initialLat) && !Number.isNaN(initialLng)) {
        setCoordinates(initialLat, initialLng);
    }

    setTimeout(() => map.invalidateSize(), 100);
}

function getCategoryPicker(form) {
    return form.querySelector('.create-auction-category-picker');
}

function clearCategoryFieldError(form) {
    const categoryInput = form.querySelector('#categoryId');
    clearFieldError(categoryInput);
    getCategoryPicker(form)?.classList.remove('is-invalid');
}

function initServerSideErrors(form) {
    const categoryInput = form.querySelector('#categoryId');

    if (categoryInput?.classList.contains('is-invalid')) {
        getCategoryPicker(form)?.classList.add('is-invalid');
    }

    if (form.querySelector('.field-error')) {
        scrollToFirstError(form);
    }
}

function scrollToFirstError(form) {
    const firstError = form.querySelector('.field-error')
        || form.querySelector('.is-invalid');

    if (!firstError) {
        return;
    }

    const scrollTarget = firstError.closest('.form-field')
        || firstError.closest('.upload-img-field')
        || firstError;

    scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function getPracaIds(form) {
    try {
        return JSON.parse(form.dataset.pracaIds || '[]').map((id) => Number(id));
    } catch {
        return [];
    }
}

function isJobCategory(form) {
    const categoryId = Number(form.querySelector('#categoryId')?.value);

    return categoryId > 0 && getPracaIds(form).includes(categoryId);
}

function toggleJobMode(form) {
    const isJob = isJobCategory(form);
    const negotiableBox = form.querySelector('#negotiable-box');
    const negotiable = form.querySelector('#negotiable');
    const salaryTypeBox = form.querySelector('#salaryType-box');
    const salaryType = form.querySelector('#salaryType');
    const priceLabel = form.querySelector('#price-label');
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

    if (salaryTypeBox) {
        salaryTypeBox.hidden = !isJob;
    }

    if (salaryType) {
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

    if (priceLabel) {
        priceLabel.textContent = isJob
            ? priceLabel.dataset.jobLabel || 'Wynagrodzenie'
            : priceLabel.dataset.auctionLabel || 'Cena';
    }

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

function validateForm(form, categoryInput, thumbnailInput, imageInputs) {
    let isValid = true;
    const isJob = isJobCategory(form);

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

    if (!categoryInput?.value) {
        setFieldError(categoryInput, 'Wybierz kategorię.');
        getCategoryPicker(form)?.classList.add('is-invalid');
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
    const lat = form.querySelector('#lat');
    const lng = form.querySelector('#lng');

    if (!location.value.trim()) {
        setFieldError(location, 'Lokalizacja jest wymagana.');
        isValid = false;
    } else if (location.value.trim().length > 200) {
        setFieldError(location, 'Lokalizacja może mieć maksymalnie 200 znaków.');
        isValid = false;
    } else if (!lat.value || !lng.value) {
        setFieldError(location, 'Wybierz lokalizację klikając na mapie.');
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
