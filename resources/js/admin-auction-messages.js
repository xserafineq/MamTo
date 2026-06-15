document.addEventListener('DOMContentLoaded', () => {
    const previewModal = document.getElementById('chatPreviewModal');
    const previewBody = document.getElementById('chatPreviewModalBody');
    const previewTitle = document.getElementById('chatPreviewModalLabel');

    if (previewModal && previewBody) {
        previewModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;

            if (!button) {
                return;
            }

            const chatId = button.dataset.chatId;
            const source = document.getElementById(`chat-preview-${chatId}`);

            if (!source) {
                previewBody.innerHTML = '<p class="mb-0 text-muted">Nie udało się załadować konwersacji.</p>';
                return;
            }

            previewBody.innerHTML = source.innerHTML;

            if (previewTitle && button.dataset.chatTitle) {
                previewTitle.textContent = `Podgląd: ${button.dataset.chatTitle}`;
            }
        });

        previewModal.addEventListener('hidden.bs.modal', () => {
            previewBody.innerHTML = '';
        });
    }

    const deleteModal = document.getElementById('deleteChatModal');
    const deleteForm = document.getElementById('deleteChatForm');
    const deleteModalText = document.getElementById('deleteChatModalText');

    if (!deleteModal || !deleteForm || !deleteModalText) {
        return;
    }

    deleteModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;

        if (!button) {
            return;
        }

        deleteForm.action = button.dataset.deleteUrl || '';
        deleteModalText.textContent = `Czy na pewno chcesz usunąć rozmowę ${button.dataset.chatTitle}? Wszystkie wiadomości zostaną trwale usunięte.`;
    });
});
