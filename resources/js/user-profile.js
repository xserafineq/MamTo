document.addEventListener('DOMContentLoaded', () => {
    initSellerRatingButtons();
});

function initSellerRatingButtons() {
    const container = document.getElementById('seller-rating-actions');

    if (!container || container.dataset.canRate !== '1') {
        return;
    }

    const buttons = container.querySelectorAll('.btn-rate');

    buttons.forEach((button) => {
        button.addEventListener('click', async () => {
            const rating = Number(button.dataset.rating);

            buttons.forEach((btn) => {
                btn.disabled = true;
            });

            try {
                const response = await fetch(container.dataset.rateUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': container.dataset.csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ rating }),
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                const ratingEl = document.getElementById('rating');

                buttons.forEach((btn) => {
                    const isSelected = Number(btn.dataset.rating) === data.rating;
                    btn.classList.toggle('is-selected', isSelected);
                    btn.disabled = true;
                });

                container.dataset.canRate = '0';
                container.dataset.userRating = String(data.rating);

                if (ratingEl && data.recommendationPercent !== null) {
                    ratingEl.hidden = false;
                    ratingEl.dataset.recommendationPercent = String(data.recommendationPercent);
                    ratingEl.textContent = `${data.recommendationPercent}% oceniających poleca`;
                }
            } finally {
                if (container.dataset.canRate === '1') {
                    buttons.forEach((btn) => {
                        btn.disabled = false;
                    });
                }
            }
        });
    });
}
