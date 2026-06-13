@extends('layout')

@push('styles')
    @vite(['resources/css/create-auction.css'])
@endpush

@push('scripts')
    @vite(['resources/js/auctions/create-auction.js'])
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
        action="{{ route('auctions.update', $auction) }}"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf
        @method('PUT')

        <section id="upload-img-card-container">
            <label class="upload-img-card">
                <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/png,image/webp" class="@error('thumbnail') is-invalid @enderror" hidden>
                <img
                    class="upload-img-card__preview upload-img-card__preview--filled"
                    src="{{ $auction->image?->file_url ?? asset('assets/img-upload-icon.svg') }}"
                    alt="Miniatura"
                />
                <div>Miniatura</div>
            </label>
            @foreach (['2', '3', '4', '5'] as $index => $label)
                <label class="upload-img-card">
                    <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" class="@error('images.' . $index) is-invalid @enderror" hidden>
                    <img class="upload-img-card__preview" src="{{ asset('assets/img-upload-icon.svg') }}" alt="Zdjęcie {{ $label }}"/>
                    <div>{{ $label }}</div>
                </label>
            @endforeach
        </section>
        <p class="text-muted small">Pozostaw puste, aby zachować obecne zdjęcia. Nowe pliki zastąpią wybrane.</p>

        <input
            type="text"
            name="name"
            id="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Tytuł aukcji"
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
            style="height: 100px"
            maxlength="5000"
        >{{ old('description', $auction->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <select
            name="categoryId"
            id="categoryId"
            class="form-select @error('categoryId') is-invalid @enderror"
            required
        >
            <option value="" disabled>Wybierz kategorię</option>
            @foreach ($categories as $category)
                <option value="{{ $category['id'] }}" @selected(old('categoryId', $auction->categoryId) == $category['id'])>
                    {{ $category['label'] }}
                </option>
            @endforeach
        </select>
        @error('categoryId')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div id="price-category-box">
            <select name="negotiable" id="negotiable" class="form-select @error('negotiable') is-invalid @enderror" required>
                <option value="" disabled>Do negocjacji?</option>
                <option value="1" @selected(old('negotiable', $auction->negotiable ? '1' : '0') === '1' || old('negotiable', $auction->negotiable) === true)>Tak</option>
                <option value="0" @selected(old('negotiable', $auction->negotiable ? '1' : '0') === '0' || old('negotiable', $auction->negotiable) === false)>Nie</option>
            </select>
            @error('negotiable')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <input
                type="number"
                name="price"
                id="price"
                class="form-control @error('price') is-invalid @enderror"
                placeholder="Cena"
                value="{{ old('price', $auction->price) }}"
                min="0"
                step="0.01"
                required
            >
            @error('price')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <input
            type="text"
            name="location"
            id="location"
            class="form-control @error('location') is-invalid @enderror"
            placeholder="Lokalizacja (np. Warszawa)"
            value="{{ old('location', $auction->location) }}"
            maxlength="200"
            required
        >
        @error('location')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror

        <div id="btns-box">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <a href="{{ route('auctions.mine') }}" class="btn btn-danger">Anuluj</a>
        </div>
    </form>
</section>

@endsection
