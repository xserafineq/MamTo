@extends('layout')

@push('styles')
    @vite(['resources/css/create-auction.css', 'resources/css/auctions.css'])
@endpush

@push('scripts')
    @vite(['resources/js/auctions/category-picker.js', 'resources/js/auctions/create-auction.js'])
@endpush

@section('content')

<section id="create-auction-container">
    @if ($errors->any())
        <div class="create-auction-errors-summary" role="alert">
            <strong>Formularz zawiera błędy.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

        <div class="form-field upload-img-field @error('thumbnail') has-field-error @enderror">
            <section id="upload-img-card-container">
                <label class="upload-img-card @error('thumbnail') is-invalid @enderror">
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" hidden>
                    <img class="upload-img-card__preview" src="{{ asset('assets/img-upload-icon.svg') }}" alt="Miniatura"/>
                    <div id="thumbnail-label">Miniatura</div>
                </label>
                @foreach (['2', '3', '4', '5'] as $index => $label)
                    <label class="upload-img-card job-extra-image @error('images.' . $index) is-invalid @enderror">
                        <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" hidden>
                        <img class="upload-img-card__preview" src="{{ asset('assets/img-upload-icon.svg') }}" alt="Zdjęcie {{ $label }}"/>
                        <div>{{ $label }}</div>
                    </label>
                @endforeach
            </section>
            @error('thumbnail')
                <div class="field-error">{{ $message }}</div>
            @enderror
            @error('images')
                <div class="field-error">{{ $message }}</div>
            @enderror
            @foreach ($errors->getMessages() as $field => $messages)
                @if (str_starts_with($field, 'images.'))
                    @foreach ($messages as $message)
                        <div class="field-error">{{ $message }}</div>
                    @endforeach
                @endif
            @endforeach
        </div>

        <div class="form-field @error('name') has-field-error @enderror">
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
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-field @error('description') has-field-error @enderror">
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
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        @include('partials.category-picker-form')

        @include('partials.auction-price-fields', [
            'negotiableValue' => old('negotiable'),
            'salaryValue' => old('salaryType'),
            'priceValue' => old('price'),
        ])

        <div class="location-field form-field @if($errors->hasAny(['location', 'latitude', 'longitude'])) has-field-error @endif">
            <div class="location-input-wrap">
                <input
                    type="text"
                    name="location"
                    id="location"
                    class="form-control @if($errors->hasAny(['location', 'latitude', 'longitude'])) is-invalid @endif"
                    placeholder="Lokalizacja (kliknij na mapie)"
                    data-auction-placeholder="Lokalizacja (kliknij na mapie)"
                    data-job-placeholder="Miejsce pracy (kliknij na mapie)"
                    value="{{ old('location') }}"
                    maxlength="200"
                    autocomplete="off"
                    required
                >
                <div id="location-suggestions" class="location-suggestions"></div>
            </div>
            <div id="map" class="location-map"></div>
            <input type="hidden" id="lat" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="lng" name="longitude" value="{{ old('longitude') }}">
            @error('location')
                <div class="field-error">{{ $message }}</div>
            @enderror
            @error('latitude')
                <div class="field-error">Nie wybrano poprawnej lokalizacji na mapie.</div>
            @enderror
            @error('longitude')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div id="btns-box">
            <button type="submit" class="btn btn-primary">Zapisz</button>
            <a href="/" class="btn btn-danger">Anuluj</a>
        </div>
    </form>
</section>

@endsection
