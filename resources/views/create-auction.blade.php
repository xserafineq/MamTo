@extends('layout')

@push('styles')
    @vite(['resources/css/create-auction.css'])
@endpush

@push('scripts')
    @vite(['resources/js/auctions/create-auction.js'])
@endpush

@section('content')

<section id="create-auction-container">
    @if ($errors->any())
        <div class="alert alert-danger py-2 small w-100" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form
        id="create-auction-form"
        method="POST"
        action="{{ route('auctions.store') }}"
        enctype="multipart/form-data"
        data-praca-ids="{{ json_encode($pracaCategoryIds) }}"
        novalidate
    >
        @csrf

        <section id="upload-img-card-container">
            <label class="upload-img-card">
                <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" class="@error('thumbnail') is-invalid @enderror" hidden>
                <img class="upload-img-card__preview" src="{{ asset('assets/img-upload-icon.svg') }}" alt="Miniatura"/>
                <div id="thumbnail-label">Miniatura</div>
            </label>
            @foreach (['2', '3', '4', '5'] as $index => $label)
                <label class="upload-img-card job-extra-image">
                    <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" class="@error('images.' . $index) is-invalid @enderror" hidden>
                    <img class="upload-img-card__preview" src="{{ asset('assets/img-upload-icon.svg') }}" alt="Zdjęcie {{ $label }}"/>
                    <div>{{ $label }}</div>
                </label>
            @endforeach
        </section>
        @error('thumbnail')
            <div class="text-danger small">{{ $message }}</div>
        @enderror

        <input
            type="text"
            name="name"
            id="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Tytuł aukcji"
            data-auction-placeholder="Tytuł aukcji"
            data-job-placeholder="Stanowisko (np. Magazynier)"
            value="{{ old('name') }}"
            maxlength="255"
            required
        >
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <textarea
            name="description"
            id="description"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Opis"
            data-auction-placeholder="Opis"
            data-job-placeholder="Opis stanowiska i obowiązków"
            style="height: 100px"
            maxlength="5000"
        >{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <select
            name="categoryId"
            id="categoryId"
            class="form-select @error('categoryId') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ old('categoryId') ? '' : 'selected' }}>Wybierz kategorię</option>
            @foreach ($categories as $category)
                <option value="{{ $category['id'] }}" @selected(old('categoryId') == $category['id'])>
                    {{ $category['label'] }}
                </option>
            @endforeach
        </select>
        @error('categoryId')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div id="price-category-box">
            <div id="negotiable-box">
                <select name="negotiable" id="negotiable" class="form-select @error('negotiable') is-invalid @enderror" required>
                    <option value="" disabled {{ old('negotiable') !== null ? '' : 'selected' }}>Do negocjacji?</option>
                    <option value="1" @selected(old('negotiable') === '1' || old('negotiable') === 1 || old('negotiable') === true)>Tak</option>
                    <option value="0" @selected(old('negotiable') === '0' || old('negotiable') === 0 || old('negotiable') === false)>Nie</option>
                </select>
                @error('negotiable')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <select
                name="salaryType"
                id="salaryType"
                class="form-select @error('salaryType') is-invalid @enderror"
                hidden
            >
                <option value="" disabled {{ old('salaryType') ? '' : 'selected' }}>Rodzaj wynagrodzenia</option>
                <option value="brutto/h" @selected(old('salaryType') === 'brutto/h')>brutto / h</option>
                <option value="brutto/mies." @selected(old('salaryType') === 'brutto/mies.')>brutto / mies.</option>
                <option value="netto/h" @selected(old('salaryType') === 'netto/h')>netto / h</option>
                <option value="do uzgodnienia" @selected(old('salaryType') === 'do uzgodnienia')>do uzgodnienia</option>
            </select>
            @error('salaryType')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <input
                type="number"
                name="price"
                id="price"
                class="form-control @error('price') is-invalid @enderror"
                placeholder="Cena"
                data-auction-placeholder="Cena"
                data-job-placeholder="Wynagrodzenie"
                value="{{ old('price') }}"
                min="0"
                step="0.01"
                required
            >
            @error('price')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="location-field">
            <input
                type="text"
                name="location"
                id="location-search"
                class="form-control @error('location') is-invalid @enderror"
                placeholder="Lokalizacja (np. Warszawa, lub kliknij na mapie)"
                value="{{ old('location') }}"
                maxlength="200"
                autocomplete="off"
                required
            >
            <div id="location-suggestions" class="location-suggestions"></div>
            <div id="map" class="location-map"></div>
            <input type="hidden" id="lat" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="lng" name="longitude" value="{{ old('longitude') }}">
            @error('location')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('latitude')
                <div class="invalid-feedback d-block">Nie wybrano poprawnej lokalizacji z mapy lub podpowiedzi.</div>
            @enderror
        </div>
        <input
            type="text"
            name="location"
            id="location"
            class="form-control @error('location') is-invalid @enderror"
            placeholder="Lokalizacja (np. Warszawa)"
            data-auction-placeholder="Lokalizacja (np. Warszawa)"
            data-job-placeholder="Miejsce pracy (np. Warszawa)"
            value="{{ old('location') }}"
            maxlength="200"
            required
        >
        @error('location')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div id="btns-box">
            <button type="submit" class="btn btn-primary">Zapisz</button>
            <a href="/" class="btn btn-danger">Anuluj</a>
        </div>
    </form>
</section>

@endsection
