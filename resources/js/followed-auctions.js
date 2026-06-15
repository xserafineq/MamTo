document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.followed-unfollow-btn').forEach((button) => {
        button.addEventListener('click', async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const card = button.closest('.searched-auction-card');

            if (!card || button.disabled) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(button.dataset.unfollowUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': button.dataset.csrf,
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    button.disabled = false;
                    return;
                }

                card.remove();

                const list = document.getElementById('searched-auctions');

                if (!list || list.querySelector('.searched-auction-card')) {
                    return;
                }

                let emptyMessage = document.getElementById('followed-empty');

                if (!emptyMessage) {
                    emptyMessage = document.createElement('p');
                    emptyMessage.id = 'followed-empty';
                    emptyMessage.className = 'followed-empty';
                    emptyMessage.textContent = 'Nie obserwujesz jeszcze żadnych aktywnych aukcji.';
                    list.appendChild(emptyMessage);
                }
            } catch {
                button.disabled = false;
            }
        });
    });
});
