<div class="create-auction-category-field form-field @error('categoryId') has-field-error @enderror">
    <span class="form-field__label">Kategoria</span>

    <input
        type="hidden"
        name="categoryId"
        id="categoryId"
        value="{{ $selectedCategoryId ?? '' }}"
        class="@error('categoryId') is-invalid @enderror"
    >

    <div
        class="category-picker create-auction-category-picker @error('categoryId') is-invalid @enderror"
        data-mode="form"
        data-show-count="false"
        data-categories='@json($categoryTree)'
        data-selected-category="{{ $selectedCategoryId ?? '' }}"
        data-selected-label="{{ $selectedCategoryLabel ?? 'Wybierz kategorię' }}"
        data-input-name="categoryId"
    >
        <div class="category-picker__trigger">{{ $selectedCategoryLabel ?? 'Wybierz kategorię' }}</div>
        <div class="category-picker__panel">
            <div class="category-picker__columns"></div>
        </div>
    </div>

    @error('categoryId')
        <div class="field-error">{{ $message }}</div>
    @enderror
</div>
