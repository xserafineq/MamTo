@extends('layout')

@push('styles')
    @vite(['resources/css/admin-panel.css'])
@endpush

@push('scripts')
    @vite(['resources/js/admin/admin-images.js'])
@endpush

@section('content')
    <section class="admin-panel">
        <h1>Zdjęcia</h1>

        <p class="admin-panel-desc">
            Wszystkie zdjęcia zapisane w systemie. Usunięcie usuwa plik z dysku i rekord z bazy danych.
            Aukcje korzystające ze zdjęcia jako miniatury otrzymają domyślny placeholder.
            Domyślnego zdjęcia systemowego nie można usunąć, ale można je podmienić przyciskiem Edytuj.
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

        <p class="admin-panel-total">Razem: {{ $images->total() }}</p>

        @if ($images->isEmpty())
            <p>Brak zdjęć w systemie.</p>
        @else
            <div class="admin-images-grid">
                @foreach ($images as $image)
                    @php
                        $isPlaceholder = $image->uuid === \App\Services\ImageService::PLACEHOLDER_UUID;
                    @endphp
                    <article class="admin-images-card {{ ! $image->fileExists() ? 'admin-images-card--missing' : '' }}">
                        <button
                            type="button"
                            class="admin-images-card__preview"
                            data-admin-image-preview
                            data-image-src="{{ $image->file_url }}"
                            data-image-alt="{{ basename($image->filename) }}"
                            aria-label="Powiększ zdjęcie {{ basename($image->filename) }}"
                        >
                            <img
                                src="{{ $image->file_url }}"
                                alt="{{ basename($image->filename) }}"
                                loading="lazy"
                            >
                        </button>
                        <div class="admin-images-card__body">
                            <div class="admin-images-card__filename" title="{{ basename($image->filename) }}">
                                {{ basename($image->filename) }}
                            </div>
                            <div class="admin-images-card__meta">
                                {{ $image->uploadedAt->format('d.m.Y H:i') }}
                            </div>
                            <div class="admin-images-card__badges">
                                @if ($isPlaceholder)
                                    <span class="admin-images-card__badge admin-images-card__badge--system">Systemowe</span>
                                @endif
                                @if (! $image->fileExists())
                                    <span class="admin-images-card__badge admin-images-card__badge--missing">Brak pliku</span>
                                @endif
                            </div>
                            <div class="admin-images-card__actions">
                                @if ($isPlaceholder)
                                    <form
                                        method="POST"
                                        action="{{ route('admin.images.update', $image) }}"
                                        enctype="multipart/form-data"
                                        class="admin-images-card__replace-form"
                                        data-admin-image-replace-form
                                    >
                                        @csrf
                                        @method('PUT')
                                        <input
                                            type="file"
                                            name="image"
                                            accept="image/jpeg,image/png,image/webp"
                                            class="visually-hidden"
                                            id="admin-image-input-{{ $image->id }}"
                                        >
                                        <label
                                            for="admin-image-input-{{ $image->id }}"
                                            class="btn btn-outline-primary btn-sm w-100 mb-0"
                                        >
                                            Edytuj
                                        </label>
                                    </form>
                                @else
                                    <form
                                        method="POST"
                                        action="{{ route('admin.images.destroy', $image) }}"
                                        class="admin-images-card__delete-form"
                                        onsubmit="return confirm('Czy na pewno usunąć to zdjęcie? Operacja jest nieodwracalna.')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">Usuń</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @include('admin.partials.pagination', ['paginator' => $images])
        @endif
    </section>

    <div class="modal fade" id="adminImageLightbox" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content admin-images-lightbox__content">
                <button type="button" class="btn-close admin-images-lightbox__close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                <img src="" alt="" class="admin-images-lightbox__image" id="adminImageLightboxImage">
                <div class="admin-images-lightbox__caption" id="adminImageLightboxCaption"></div>
            </div>
        </div>
    </div>
@endsection
