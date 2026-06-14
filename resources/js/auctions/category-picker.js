document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.category-picker').forEach(initCategoryPicker);
});

function initCategoryPicker(picker) {
    const mode = picker.dataset.mode || 'filter';
    const isParentMode = mode === 'parent';
    const isSelectMode = mode === 'select';
    const isFormMode = mode === 'form';
    const showCount = picker.dataset.showCount !== 'false';
    const tree = JSON.parse(picker.dataset.categories || '[]');
    const excludedIds = new Set(JSON.parse(picker.dataset.excludedIds || '[]').map(String));
    const selectedCategoryId = picker.dataset.selectedCategory || '';
    const form = isParentMode || isSelectMode || isFormMode
        ? picker.closest('form')
        : picker.querySelector('form');
    const trigger = picker.querySelector('.category-picker__trigger');
    const columnsContainer = picker.querySelector('.category-picker__columns');
    const panel = picker.querySelector('.category-picker__panel');

    if (!trigger || !columnsContainer || !panel) {
        return;
    }

    if (mode === 'filter' && !form) {
        return;
    }

    if ((isParentMode || isFormMode) && !form) {
        return;
    }

    const filteredTree = filterTree(tree, excludedIds);
    const rootItems = isParentMode
        ? [{ id: '', name: 'Brak (kategoria główna)', children: filteredTree, count: 0 }]
        : isSelectMode
            ? [{ id: '', name: 'Brak zaznaczenia', children: filteredTree, count: 0 }]
            : isFormMode
                ? filteredTree
                : buildFilterRootItems(filteredTree);

    function buildFilterRootItems(nodes) {
        const totalCount = nodes.reduce((sum, category) => sum + (category.count ?? 0), 0);

        return [
            { id: '', name: 'Wszystkie', children: [], count: totalCount },
            ...nodes,
        ];
    }

    function filterTree(nodes, excludedSet) {
        return nodes
            .filter((node) => !excludedSet.has(String(node.id)))
            .map((node) => ({
                ...node,
                children: filterTree(node.children || [], excludedSet),
            }));
    }

    function findPath(nodes, targetId, trail = []) {
        for (const node of nodes) {
            const nextTrail = [...trail, node];

            if (String(node.id) === String(targetId)) {
                return nextTrail;
            }

            if (node.children?.length) {
                const found = findPath(node.children, targetId, nextTrail);

                if (found) {
                    return found;
                }
            }
        }

        return null;
    }

    function getSelectionLabel(itemId) {
        if (!itemId) {
            if (isParentMode) {
                return 'Brak (kategoria główna)';
            }

            if (isSelectMode) {
                return 'Brak zaznaczenia';
            }

            if (isFormMode) {
                return 'Wybierz kategorię';
            }

            return 'Wszystkie kategorie';
        }

        const path = findPath(rootItems, itemId);

        if (!path?.length) {
            return 'Wybrana kategoria';
        }

        if (mode === 'filter' && path.length <= 1) {
            return path[path.length - 1].name;
        }

        if (mode === 'filter') {
            return path.slice(1).map((node) => node.name).join(' > ');
        }

        if (path.length === 1) {
            return path[0].name;
        }

        return path.map((node) => node.name).join(' > ');
    }

    function submitCategory(item) {
        let categoryInput = form.querySelector('input[name="category"]');

        if (item.id) {
            if (!categoryInput) {
                categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = 'category';
                form.appendChild(categoryInput);
            }

            categoryInput.value = item.id;
        } else if (categoryInput) {
            categoryInput.remove();
        }

        form.submit();
    }

    function selectCategory(item) {
        const inputName = picker.dataset.inputName || (isFormMode ? 'categoryId' : 'selectedCategoryId');
        const container = isFormMode || isParentMode ? form : picker;
        let categoryInput = container.querySelector(`input[name="${inputName}"]`);

        if (item.id) {
            if (!categoryInput) {
                categoryInput = document.createElement('input');
                categoryInput.type = 'hidden';
                categoryInput.name = inputName;
                if (isFormMode && inputName === 'categoryId') {
                    categoryInput.id = 'categoryId';
                }
                container.insertBefore(categoryInput, isFormMode ? picker : container.firstChild);
            }

            categoryInput.value = item.id;
        } else if (categoryInput) {
            categoryInput.remove();
        }

        trigger.textContent = getSelectionLabel(item.id);

        if (isFormMode && categoryInput) {
            categoryInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        picker.dispatchEvent(new CustomEvent('category-picker:select', {
            bubbles: true,
            detail: {
                id: item.id || null,
                name: item.id ? item.name : '',
                imageUrl: item.imageUrl || null,
            },
        }));
        closePanel();
    }

    function selectParent(item) {
        let parentInput = form.querySelector('input[name="parentId"]');

        if (item.id) {
            if (!parentInput) {
                parentInput = document.createElement('input');
                parentInput.type = 'hidden';
                parentInput.name = 'parentId';
                form.insertBefore(parentInput, picker);
            }

            parentInput.value = item.id;
        } else if (parentInput) {
            parentInput.remove();
        }

        trigger.textContent = getSelectionLabel(item.id);
        closePanel();
    }

    function handleSelection(item) {
        if (isParentMode) {
            selectParent(item);
            return;
        }

        if (isSelectMode || isFormMode) {
            selectCategory(item);
            return;
        }

        submitCategory(item);
    }

    function clearColumnsFrom(level) {
        columnsContainer.querySelectorAll('.category-picker__column').forEach((column) => {
            if (Number(column.dataset.level) >= level) {
                column.remove();
            }
        });
    }

    function expandItem(listItem, item, level, column) {
        clearColumnsFrom(level + 1);
        column.querySelectorAll('.category-picker__item').forEach((element) => {
            element.classList.remove('is-active');
        });
        listItem.classList.add('is-active');
        columnsContainer.appendChild(renderColumn(item.children, level + 1));
    }

    function renderColumn(items, level) {
        const column = document.createElement('ul');
        column.className = 'category-picker__column';
        column.dataset.level = String(level);

        items.forEach((item) => {
            const listItem = document.createElement('li');
            listItem.className = 'category-picker__item';
            listItem.dataset.id = item.id;

            const label = document.createElement('span');
            label.className = 'category-picker__label';
            label.textContent = item.name;

            listItem.appendChild(label);

            if (showCount) {
                const count = document.createElement('span');
                count.className = 'category-picker__count';
                count.textContent = String(item.count ?? 0);
                listItem.appendChild(count);
            }

            if (String(item.id) === selectedCategoryId) {
                listItem.classList.add('is-selected');
            }

            if (item.children?.length) {
                listItem.classList.add('has-children');
                listItem.addEventListener('click', (event) => {
                    event.stopPropagation();

                    if (listItem.classList.contains('is-active')) {
                        handleSelection(item);
                        return;
                    }

                    expandItem(listItem, item, level, column);
                });
            } else {
                listItem.addEventListener('click', (event) => {
                    event.stopPropagation();
                    handleSelection(item);
                });
            }

            column.appendChild(listItem);
        });

        return column;
    }

    function expandSelectedPath() {
        if (!selectedCategoryId) {
            return;
        }

        const path = findPath(rootItems, selectedCategoryId);

        if (!path || path.length < 2) {
            return;
        }

        for (let index = 0; index < path.length - 1; index++) {
            const parent = path[index];
            const child = path[index + 1];
            const parentColumn = columnsContainer.querySelector(`[data-level="${index}"]`);

            if (!parentColumn) {
                break;
            }

            const parentItem = parentColumn.querySelector(
                `.category-picker__item[data-id="${parent.id}"]`,
            );

            if (parentItem && parent.children?.length) {
                expandItem(parentItem, parent, index, parentColumn);
            }

            const childColumn = columnsContainer.querySelector(`[data-level="${index + 1}"]`);

            childColumn?.querySelectorAll('.category-picker__item').forEach((element) => {
                element.classList.toggle('is-active', String(element.dataset.id) === String(child.id));
            });
        }
    }

    function openPanel() {
        panel.classList.add('is-open');
        columnsContainer.innerHTML = '';
        columnsContainer.appendChild(renderColumn(rootItems, 0));
        expandSelectedPath();
    }

    function closePanel() {
        panel.classList.remove('is-open');
        columnsContainer.innerHTML = '';
    }

    trigger.textContent = picker.dataset.selectedLabel || getSelectionLabel(selectedCategoryId);

    trigger.addEventListener('click', (event) => {
        event.stopPropagation();

        if (panel.classList.contains('is-open')) {
            closePanel();
        } else {
            openPanel();
        }
    });

    document.addEventListener('click', (event) => {
        if (!picker.contains(event.target)) {
            closePanel();
        }
    });
}
