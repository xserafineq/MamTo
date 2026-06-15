@extends('layout')

@push('styles')
    @vite(['resources/css/create-auction.css', 'resources/css/auctions.css'])
@endpush

@push('scripts')
    @vite(['resources/js/auctions/category-picker.js', 'resources/js/auctions/create-auction.js'])
@endpush

@section('content')

<section id="create-auction-container">
    <h1 class="edit-auction-title">Edytuj ogłoszenie</h1>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small w-100" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form
        id="create-auction-form"
        data-mode="edit"
        method="POST"
        action="{{ $updateRoute ?? route('auctions.update', $auction) }}"
        enctype="multipart/form-data"
        data-praca-ids="{{ json_encode($pracaCategoryIds) }}"
        data-upload-placeholder="{{ asset('assets/img-upload-icon.svg') }}"
        novalidate
    >
        @csrf
        @method('PUT')

        <section id="upload-img-card-container">
            <div class="upload-img-card-wrap">
                <label class="upload-img-card">
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" class="@error('thumbnail') is-invalid @enderror" hidden>
                    <img
                        class="upload-img-card__preview @if($auction->image) upload-img-card__preview--filled @endif"
                        data-placeholder="{{ asset('assets/img-upload-icon.svg') }}"
                        src="{{ $auction->image?->file_url ?? asset('assets/img-upload-icon.svg') }}"
                        alt="Miniatura"
                    />
                    <div id="thumbnail-label">Miniatura</div>
                </label>
                <button type="button" class="upload-img-card__remove" hidden aria-label="Usuń zdjęcie">&times;</button>
            </div>
            @foreach (['2', '3', '4', '5'] as $index => $label)
                <div class="upload-img-card-wrap">
                    <label class="upload-img-card job-extra-image">
                        <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" class="@error('images.' . $index) is-invalid @enderror" hidden>
                        <img
                            class="upload-img-card__preview"
                            data-placeholder="{{ asset('assets/img-upload-icon.svg') }}"
                            src="{{ asset('assets/img-upload-icon.svg') }}"
                            alt="Zdjęcie {{ $label }}"
                        />
                        <div>{{ $label }}</div>
                    </label>
                    <button type="button" class="upload-img-card__remove" hidden aria-label="Usuń zdjęcie">&times;</button>
                </div>
            @endforeach
        </section>
        <p id="auction-image-note" class="text-muted small">Pozostaw puste, aby zachować obecne zdjęcia. Nowe pliki zastąpią wybrane.</p>

        <input
            type="text"
            name="name"
            id="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Tytuł aukcji"
            data-auction-placeholder="Tytuł aukcji"
            data-job-placeholder="Stanowisko (np. Magazynier)"
            value="{{ old('name', $auction->name) }}"
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
        >{{ old('description', $auction->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        @include('partials.category-picker-form')

        @include('partials.auction-price-fields', [
            'negotiableValue' => old('negotiable', $auction->negotiable ? '1' : '0'),
            'salaryValue' => old('salaryType', $auction->salaryType ?? ($auction->negotiable ? 'do uzgodnienia' : '')),
            'priceValue' => old('price', $auction->price),
        ])

        <div class="location-field">
            <div class="location-input-wrap">
                <input
                    type="text"
                    name="location"
                    id="location"
                    class="form-control @error('location') is-invalid @enderror"
                    placeholder="Lokalizacja (kliknij na mapie)"
                    data-auction-placeholder="Lokalizacja (kliknij na mapie)"
                    data-job-placeholder="Miejsce pracy (kliknij na mapie)"
                    value="{{ old('location', $auction->location) }}"
                    maxlength="200"
                    autocomplete="off"
                    required
                >
                <div id="location-suggestions" class="location-suggestions"></div>
            </div>
            <div id="map" class="location-map"></div>
            <input type="hidden" id="lat" name="latitude" value="{{ old('latitude', $auction->latitude) }}">
            <input type="hidden" id="lng" name="longitude" value="{{ old('longitude', $auction->longitude) }}">
            @error('location')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('latitude')
                <div class="invalid-feedback d-block">Nie wybrano poprawnej lokalizacji na mapie.</div>
            @enderror
        </div>

        @if(!empty($isAdminEdit))
            <select
                name="status"
                id="status"
                class="form-select @error('status') is-invalid @enderror"
                required
            >
                <option value="aktywna" @selected(old('status', $auction->status) === 'aktywna')>Aktywna</option>
                <option value="zakończona" @selected(old('status', $auction->status) === 'zakończona')>Zamknięta</option>
            </select>
            @error('status')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        @endif

        <div id="btns-box">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ $cancelRoute ?? route('auctions.mine') }}" class="btn btn-danger">Anuluj</a>
        </div>
    </form>
</section>

@endsection
