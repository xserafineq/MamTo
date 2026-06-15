document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('deleteRatingModal');
    const form = document.getElementById('deleteRatingForm');
    const modalText = document.getElementById('deleteRatingModalText');

    if (!modal || !form || !modalText) {
        return;
    }

    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;

        if (!button) {
            return;
        }

        form.action = button.dataset.deleteUrl || '';
        modalText.textContent = `Czy na pewno chcesz usunąć polecenie od użytkownika ${button.dataset.reviewerName}? Tej operacji nie można cofnąć.`;
    });
});
