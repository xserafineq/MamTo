import {
    buildUniqueCityResults,
    normalizeLocationText,
    searchCities,
} from './geocoding';

const DEFAULT_DISTANCE_KM = 10;

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('city-filter-form');
    const input = document.getElementById('city-search');
    const distanceInput = document.getElementById('city-distance');
    const latInput = document.getElementById('city-lat');
    const lngInput = document.getElementById('city-lng');
    const suggestionsEl = document.getElementById('city-suggestions');

    if (!form || !input || !distanceInput || !latInput || !lngInput || !suggestionsEl) {
        return;
    }

    let searchTimeout = null;
    let lastSearchResults = [];

    const hideSuggestions = () => {
        suggestionsEl.innerHTML = '';
        suggestionsEl.style.display = 'none';
    };

    const clearCityCoordinates = () => {
        latInput.value = '';
        lngInput.value = '';
    };

    const submitForm = () => {
        if (latInput.value && lngInput.value && !distanceInput.value) {
            distanceInput.value = String(DEFAULT_DISTANCE_KM);
        }

        form.requestSubmit();
    };

    const selectCity = ({ label, lat, lon }, shouldSubmit = true) => {
        input.value = label;
        latInput.value = lat;
        lngInput.value = lon;
        hideSuggestions();

        if (shouldSubmit) {
            submitForm();
        }
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
            item.className = 'city-suggestions__item';
            item.textContent = label;

            item.addEventListener('click', () => {
                selectCity({
                    label,
                    lat: result.lat,
                    lon: result.lon,
                });
            });

            suggestionsEl.appendChild(item);
        });

        suggestionsEl.style.display = 'block';
    };

    const tryAutoSelectMatch = (query, labeledResults, shouldSubmit = false) => {
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

        selectCity({
            label: match.label,
            lat: match.result.lat,
            lon: match.result.lon,
        }, shouldSubmit);

        return true;
    };

    const runCitySearch = async (query) => {
        try {
            const results = await searchCities(query);
            lastSearchResults = buildUniqueCityResults(results, query);

            if (tryAutoSelectMatch(query, lastSearchResults)) {
                return;
            }

            showSuggestions(lastSearchResults);
        } catch {
            lastSearchResults = [];
            hideSuggestions();
        }
    };

    input.addEventListener('input', () => {
        clearCityCoordinates();
        clearTimeout(searchTimeout);

        const query = input.value.trim();

        if (query.length < 2) {
            lastSearchResults = [];
            hideSuggestions();
            return;
        }

        searchTimeout = setTimeout(() => runCitySearch(query), 400);
    });

    input.addEventListener('blur', () => {
        setTimeout(() => {
            const query = input.value.trim();

            if (!query) {
                if (latInput.value || lngInput.value) {
                    clearCityCoordinates();
                    submitForm();
                }
                return;
            }

            if (latInput.value && lngInput.value) {
                return;
            }

            if (query.length >= 2 && lastSearchResults.length) {
                tryAutoSelectMatch(query, lastSearchResults, true);
            }
        }, 150);
    });

    input.addEventListener('keydown', async (event) => {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        clearTimeout(searchTimeout);
        hideSuggestions();

        const query = input.value.trim();

        if (!query) {
            if (latInput.value || lngInput.value) {
                clearCityCoordinates();
            }
            submitForm();
            return;
        }

        if (query.length < 2) {
            return;
        }

        if (latInput.value && lngInput.value) {
            submitForm();
            return;
        }

        let results = lastSearchResults;

        if (!results.length) {
            try {
                const searchResults = await searchCities(query);
                results = buildUniqueCityResults(searchResults, query);
                lastSearchResults = results;
            } catch {
                return;
            }
        }

        if (tryAutoSelectMatch(query, results, true)) {
            return;
        }

        if (results.length) {
            const first = results[0];
            selectCity({
                label: first.label,
                lat: first.result.lat,
                lon: first.result.lon,
            });
        }
    });

    distanceInput.addEventListener('focus', () => {
        distanceInput.dataset.initialValue = distanceInput.value;
    });

    distanceInput.addEventListener('blur', () => {
        if (distanceInput.dataset.initialValue !== distanceInput.value) {
            submitForm();
        }
    });

    distanceInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitForm();
        }
    });

    document.addEventListener('click', (event) => {
        if (!suggestionsEl.contains(event.target) && event.target !== input) {
            hideSuggestions();
        }
    });
});
