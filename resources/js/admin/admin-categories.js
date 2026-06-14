document.addEventListener('DOMContentLoaded', () => {
    const panel = document.getElementById('admin-categories-panel');

    if (!panel) {
        return;
    }

    const nameInput = document.getElementById('category-name');
    const imageInput = document.getElementById('category-image');
    const previewImage = document.getElementById('category-image-preview');
    const picker = panel.querySelector('.category-picker');
    const actionForm = document.getElementById('category-action-form');
    const deleteForm = document.getElementById('category-delete-form');
    const addButton = document.getElementById('btn-add-category');
    const editButton = document.getElementById('btn-edit-category');
    const deleteButton = document.getElementById('btn-delete-category');
    const storeUrl = panel.dataset.storeUrl;
    const updateUrlTemplate = panel.dataset.updateUrlTemplate;
    const deleteUrlTemplate = panel.dataset.deleteUrlTemplate;
    const defaultImageUrl = panel.dataset.defaultImageUrl;
    const selectedImageUrl = panel.dataset.selectedImageUrl || defaultImageUrl;

    let selectedCategoryId = picker?.dataset.selectedCategory || '';
    let currentCategoryImageUrl = selectedImageUrl;
    let previewObjectUrl = null;

    function updateActionButtons() {
        const hasSelection = Boolean(selectedCategoryId);

        if (editButton) {
            editButton.disabled = !hasSelection;
        }

        if (deleteButton) {
            deleteButton.disabled = !hasSelection;
        }
    }

    function buildActionUrl(template, categoryId) {
        return template.replace('__ID__', String(categoryId));
    }

    function requireName() {
        const name = nameInput?.value.trim() ?? '';

        if (!name) {
            nameInput?.focus();
            return null;
        }

        return name;
    }

    function clearMethodOverride() {
        actionForm?.querySelector('input[name="_method"]')?.remove();
    }

    function setMethodOverride(method) {
        clearMethodOverride();

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = method;
        actionForm?.appendChild(methodInput);
    }

    function setParentId(parentId) {
        let parentInput = actionForm?.querySelector('input[name="parentId"]');

        if (parentId) {
            if (!parentInput) {
                parentInput = document.createElement('input');
                parentInput.type = 'hidden';
                parentInput.name = 'parentId';
                actionForm?.appendChild(parentInput);
            }

            parentInput.value = parentId;
        } else {
            parentInput?.remove();
        }
    }

    function setPreview(url) {
        if (!previewImage) {
            return;
        }

        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
            previewObjectUrl = null;
        }

        previewImage.src = url || defaultImageUrl;
    }

    function resetImageInput() {
        if (imageInput) {
            imageInput.value = '';
        }
    }

    picker?.addEventListener('category-picker:select', (event) => {
        selectedCategoryId = event.detail.id ? String(event.detail.id) : '';

        if (nameInput) {
            nameInput.value = event.detail.id ? event.detail.name : '';
        }

        resetImageInput();
        currentCategoryImageUrl = event.detail.imageUrl || defaultImageUrl;
        setPreview(currentCategoryImageUrl);
        updateActionButtons();
    });

    imageInput?.addEventListener('change', () => {
        const file = imageInput.files?.[0];

        if (!file) {
            setPreview(currentCategoryImageUrl);
            return;
        }

        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
        }

        previewObjectUrl = URL.createObjectURL(file);
        previewImage.src = previewObjectUrl;
    });

    addButton?.addEventListener('click', () => {
        if (!requireName() || !actionForm) {
            return;
        }

        actionForm.action = storeUrl;
        clearMethodOverride();
        setParentId(selectedCategoryId || null);
        actionForm.submit();
    });

    editButton?.addEventListener('click', () => {
        if (!selectedCategoryId || !actionForm || !requireName()) {
            return;
        }

        actionForm.action = buildActionUrl(updateUrlTemplate, selectedCategoryId);
        setMethodOverride('PUT');
        setParentId(null);
        actionForm.submit();
    });

    deleteButton?.addEventListener('click', () => {
        if (!selectedCategoryId || !deleteForm) {
            return;
        }

        const categoryLabel = picker?.querySelector('.category-picker__trigger')?.textContent?.trim()
            || 'wybraną kategorię';

        if (!window.confirm(`Czy na pewno usunąć kategorię „${categoryLabel}”?`)) {
            return;
        }

        deleteForm.action = buildActionUrl(deleteUrlTemplate, selectedCategoryId);
        deleteForm.submit();
    });

    setPreview(selectedImageUrl);
    updateActionButtons();
});
