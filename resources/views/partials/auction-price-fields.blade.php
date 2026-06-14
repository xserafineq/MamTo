<div id="price-category-box">
    <div class="price-field form-field @error('negotiable') has-field-error @enderror" id="negotiable-box">
        <label class="price-field__label" for="negotiable">Do negocjacji?</label>
        <select
            name="negotiable"
            id="negotiable"
            class="form-select price-field__control @error('negotiable') is-invalid @enderror"
            required
        >
            <option value="" disabled {{ $negotiableValue === null ? 'selected' : '' }}>Wybierz</option>
            <option value="1" @selected($negotiableValue === '1' || $negotiableValue === 1 || $negotiableValue === true)>Tak</option>
            <option value="0" @selected($negotiableValue === '0' || $negotiableValue === 0 || $negotiableValue === false)>Nie</option>
        </select>
        @error('negotiable')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="price-field form-field @error('salaryType') has-field-error @enderror" id="salaryType-box" hidden>
        <label class="price-field__label" for="salaryType">Rodzaj wynagrodzenia</label>
        <select
            name="salaryType"
            id="salaryType"
            class="form-select price-field__control @error('salaryType') is-invalid @enderror"
        >
            <option value="" disabled {{ $salaryValue ? '' : 'selected' }}>Wybierz</option>
            <option value="brutto/h" @selected($salaryValue === 'brutto/h')>brutto / h</option>
            <option value="brutto/mies." @selected($salaryValue === 'brutto/mies.')>brutto / mies.</option>
            <option value="netto/h" @selected($salaryValue === 'netto/h')>netto / h</option>
            <option value="do uzgodnienia" @selected($salaryValue === 'do uzgodnienia')>do uzgodnienia</option>
        </select>
        @error('salaryType')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="price-field price-field--amount form-field @error('price') has-field-error @enderror">
        <label class="price-field__label" for="price" id="price-label" data-auction-label="Cena" data-job-label="Wynagrodzenie">Cena</label>
        <input
            type="number"
            name="price"
            id="price"
            class="form-control price-field__control @error('price') is-invalid @enderror"
            placeholder="Cena"
            data-auction-placeholder="Cena"
            data-job-placeholder="Wynagrodzenie"
            value="{{ $priceValue }}"
            min="0"
            step="0.01"
            required
        >
        @error('price')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>
</div>
