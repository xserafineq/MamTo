@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css', 'resources/css/auctions.css'])
@endpush

@push('scripts')
    @vite(['resources/js/auctions/category-picker.js', 'resources/js/admin/admin-categories.js'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Kategorie</h1>

        <p class="admin-panel-desc">
            Wybierz kategorię z listy. <strong>Dodaj</strong> tworzy podkategorię wybranej pozycji,
            <strong>Edytuj</strong> zmienia jej nazwę i zdjęcie, a <strong>Usuń</strong> usuwa ją z systemu.
            Kategorii z podkategoriami lub przypisanymi ogłoszeniami nie można usunąć.
        </p>

        @include('admin.partials.nav')

        @if (session('success'))
            <div class="alert alert-success py-2 small" role="alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <article
            id="admin-categories-panel"
            class="admin-panel-category-form-card"
            data-store-url="{{ route('admin.categories.store') }}"
            data-update-url-template="{{ url('admin/categories/__ID__') }}"
            data-delete-url-template="{{ url('admin/categories/__ID__') }}"
            data-default-image-url="{{ asset('assets/default-category.png') }}"
            data-selected-image-url="{{ $selectedCategoryImageUrl }}"
        >
            <h2 class="admin-panel-category-form-title">Zarządzaj kategoriami</h2>

            <div class="admin-panel-category-picker-field admin-panel-category-picker-field--select">
                <span class="admin-panel-category-picker-label">Wybrana kategoria</span>
                <div
                    class="category-picker admin-panel-parent-picker"
                    data-mode="select"
                    data-show-count="false"
                    data-categories='@json($categoryTree)'
                    data-selected-category="{{ old('selectedCategoryId', '') }}"
                    data-selected-label="{{ $selectedCategoryLabel }}"
                >
                    @if (old('selectedCategoryId'))
                        <input type="hidden" name="selectedCategoryId" value="{{ old('selectedCategoryId') }}">
                    @endif

                    <div class="category-picker__trigger">{{ $selectedCategoryLabel }}</div>
                    <div class="category-picker__panel">
                        <div class="category-picker__columns"></div>
                    </div>
                </div>
            </div>

            <form
                id="category-action-form"
                class="admin-panel-category-main-form"
                method="POST"
                action="{{ route('admin.categories.store') }}"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="admin-panel-category-image-field">
                    <span class="admin-panel-category-picker-label">Zdjęcie kategorii</span>
                    <div class="admin-panel-category-image-row">
                        <img
                            id="category-image-preview"
                            class="admin-panel-category-image-preview"
                            src="{{ $selectedCategoryImageUrl }}"
                            alt="Podgląd zdjęcia kategorii"
                        >
                        <label class="admin-panel-category-image-upload btn btn-outline-primary">
                            Wybierz plik
                            <input
                                type="file"
                                id="category-image"
                                name="image"
                                accept="image/jpeg,image/png,image/webp"
                                hidden
                            >
                        </label>
                    </div>
                    <span class="admin-panel-category-image-hint">JPG, PNG lub WEBP, maks. 5 MB. Opcjonalne przy dodawaniu.</span>
                </div>

                <div class="admin-panel-category-actions">
                    <input
                        type="text"
                        id="category-name"
                        name="name"
                        class="form-control admin-panel-category-input"
                        placeholder="Nazwa kategorii"
                        value="{{ old('name') }}"
                        maxlength="150"
                    >

                    <div class="admin-panel-category-buttons">
                        <button type="button" id="btn-add-category" class="btn btn-primary admin-panel-category-btn">
                            Dodaj
                        </button>
                        <button
                            type="button"
                            id="btn-edit-category"
                            class="btn btn-primary admin-panel-category-btn"
                            @disabled(! old('selectedCategoryId'))
                        >
                            Edytuj
                        </button>
                        <button
                            type="button"
                            id="btn-delete-category"
                            class="btn btn-danger admin-panel-category-btn admin-panel-category-btn--danger"
                            @disabled(! old('selectedCategoryId'))
                        >
                            Usuń
                        </button>
                    </div>
                </div>
            </form>

            <form id="category-delete-form" method="POST" action="" hidden>
                @csrf
                @method('DELETE')
            </form>
        </article>
    </section>
@endsection
