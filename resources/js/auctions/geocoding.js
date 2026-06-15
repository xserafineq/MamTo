export const NOMINATIM_URL = 'https://nominatim.openstreetmap.org';

const PLACE_TYPES = new Set(['city', 'town', 'village', 'hamlet']);
const PLACE_TYPE_PRIORITY = { city: 0, town: 1, village: 2, hamlet: 3 };
const CITY_ADDRESSTYPES = new Set(['city', 'town', 'village', 'hamlet', 'administrative']);
const EXCLUDED_CITY_CLASSES = new Set([
    'water',
    'tourism',
    'historic',
    'highway',
    'amenity',
    'building',
    'natural',
    'leisure',
    'shop',
]);

// Sprawdza, czy wynik wyszukiwania z API reprezentuje miejscowość
export function isCitySearchResult(result) {
    if (EXCLUDED_CITY_CLASSES.has(result.class)) {
        return false;
    }

    if (result.class === 'place' && PLACE_TYPES.has(result.type)) {
        return true;
    }

    if (result.class === 'boundary' && result.type === 'administrative') {
        return result.addresstype
            && CITY_ADDRESSTYPES.has(result.addresstype)
            && (result.place_rank ?? 99) <= 18;
    }

    return false;
}

// Formatuje adres do krótkiej, czytelnej postaci (np. ulica i miasto)
export function formatShortLocation(address) {
    if (!address || typeof address !== 'object') {
        return null;
    }

    const locality = address.city
        || address.town
        || address.village
        || address.hamlet
        || address.municipality;

    const street = address.road || address.pedestrian || address.footway;
    const houseNumber = address.house_number;

    let streetPart = null;

    if (street && houseNumber) {
        streetPart = `${street} ${houseNumber}`;
    } else if (street) {
        streetPart = street;
    }

    if (streetPart && locality) {
        return `${streetPart}, ${locality}`;
    }

    if (locality) {
        return locality;
    }

    if (streetPart) {
        return streetPart;
    }

    return null;
}

// Wyciąga z obiektu adresu tylko nazwę miasta/miejscowości
export function formatCityLocation(address) {
    if (!address || typeof address !== 'object') {
        return null;
    }

    return address.city
        || address.town
        || address.village
        || address.hamlet
        || null;
}

// Generuje czytelną etykietę tekstową dla wyniku wyszukiwania
export function locationLabelFromResult(result) {
    return formatShortLocation(result.address)
        || result.display_name?.split(',').slice(0, 2).join(',').trim()
        || null;
}

// Zwraca nazwę miasta, jeśli wynik reprezentuje miejscowość
export function cityLabelFromResult(result) {
    if (!isCitySearchResult(result)) {
        return null;
    }

    return result.name || formatCityLocation(result.address);
}

// Normalizuje tekst lokalizacji (usuwa spacje i zamienia na małe litery)
export function normalizeLocationText(value) {
    return value.trim().toLowerCase().replace(/\s+/g, ' ');
}

// Tworzy listę wyników z wygenerowanymi etykietami tekstowymi
export function buildLabeledResults(results, labelFn = locationLabelFromResult) {
    return results
        .map((result) => ({
            result,
            label: labelFn(result),
        }))
        .filter((item) => item.label);
}

// Zwraca priorytet sortowania dla danego typu miejscowości
function placeTypePriority(result) {
    if (result.class === 'place' && result.type in PLACE_TYPE_PRIORITY) {
        return PLACE_TYPE_PRIORITY[result.type];
    }

    return 99;
}

// Sortuje wyniki wyszukiwania miast według dopasowania i ważności
function sortCityResults(results, query = '') {
    const normalizedQuery = normalizeLocationText(query);

    return [...results].sort((a, b) => {
        const aName = normalizeLocationText(a.name || cityLabelFromResult(a) || '');
        const bName = normalizeLocationText(b.name || cityLabelFromResult(b) || '');

        if (normalizedQuery) {
            const aStarts = aName.startsWith(normalizedQuery) ? 0 : 1;
            const bStarts = bName.startsWith(normalizedQuery) ? 0 : 1;

            if (aStarts !== bStarts) {
                return aStarts - bStarts;
            }
        }

        const typeDiff = placeTypePriority(a) - placeTypePriority(b);

        if (typeDiff !== 0) {
            return typeDiff;
        }

        return (b.importance ?? 0) - (a.importance ?? 0);
    });
}

// Zwraca unikalną listę miast na podstawie nazwy
export function buildUniqueCityResults(results, query = '') {
    const seen = new Set();
    const items = [];

    for (const result of sortCityResults(results, query)) {
        const label = cityLabelFromResult(result);

        if (!label) {
            continue;
        }

        const key = normalizeLocationText(label);

        if (seen.has(key)) {
            continue;
        }

        const keySeen = key;
        seen.add(keySeen);
        items.push({ result, label });
    }

    return items;
}

// Wyszukuje miejscowości w Polsce na podstawie zapytania tekstowego
export async function searchCities(query, { limit = 15 } = {}) {
    const params = new URLSearchParams({
        format: 'json',
        q: query,
        countrycodes: 'pl',
        limit: String(limit),
        addressdetails: '1',
        dedupe: '1',
        'accept-language': 'pl',
    });

    const response = await fetch(`${NOMINATIM_URL}/search?${params.toString()}`);

    if (!response.ok) {
        throw new Error('City search failed');
    }

    const results = await response.json();

    return results.filter(isCitySearchResult);
}

// Wyszukuje dowolne lokalizacje w Polsce na podstawie zapytania
export async function searchLocations(query, { limit = 5 } = {}) {
    const params = new URLSearchParams({
        format: 'json',
        q: query,
        countrycodes: 'pl',
        limit: String(limit),
        addressdetails: '1',
        'accept-language': 'pl',
    });

    const response = await fetch(`${NOMINATIM_URL}/search?${params.toString()}`);

    if (!response.ok) {
        throw new Error('Geocoding failed');
    }

    return response.json();
}

// Wykonuje geokodowanie odwrotne (współrzędne na adres)
export async function reverseGeocode(lat, lng) {
    const params = new URLSearchParams({
        format: 'json',
        lat: String(lat),
        lon: String(lng),
        addressdetails: '1',
        'accept-language': 'pl',
    });

    const response = await fetch(`${NOMINATIM_URL}/reverse?${params.toString()}`);

    if (!response.ok) {
        throw new Error('Reverse geocoding failed');
    }

    const data = await response.json();

    return formatShortLocation(data.address) || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
}
