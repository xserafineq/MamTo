document.addEventListener('DOMContentLoaded', () => {
    const picker = document.getElementById('category-picker');

    if (!picker) {
        return;
    }

    const tree = JSON.parse(picker.dataset.categories || '[]');
    const selectedCategoryId = picker.dataset.selectedCategory || '';
    const form = picker.querySelector('form');
    const trigger = picker.querySelector('.category-picker__trigger');
    const columnsContainer = picker.querySelector('.category-picker__columns');
    const panel = picker.querySelector('.category-picker__panel');

    const totalCount = tree.reduce((sum, category) => sum + (category.count ?? 0), 0);

    const rootItems = [
        { id: '', name: 'Wszystkie', children: [], count: totalCount },
        ...tree,
    ];

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

            const count = document.createElement('span');
            count.className = 'category-picker__count';
            count.textContent = String(item.count ?? 0);

            listItem.append(label, count);

            if (String(item.id) === selectedCategoryId) {
                listItem.classList.add('is-selected');
            }

            if (item.children?.length) {
                listItem.classList.add('has-children');
                listItem.addEventListener('click', (event) => {
                    event.stopPropagation();

                    if (listItem.classList.contains('is-active')) {
                        submitCategory(item);
                        return;
                    }

                    expandItem(listItem, item, level, column);
                });
            } else {
                listItem.addEventListener('click', (event) => {
                    event.stopPropagation();
                    submitCategory(item);
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
});
