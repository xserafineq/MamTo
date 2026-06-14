document.addEventListener('DOMContentLoaded', () => {
    initImageLightbox();
    initFollowButton();
    initSellerRatingButtons();
});

function initImageLightbox() {
    const mainCarousel = document.getElementById('auctionCarousel');
    const lightboxEl = document.getElementById('imageLightbox');
    const lightboxCarouselEl = document.getElementById('lightboxCarousel');

    if (!mainCarousel || !lightboxEl || !lightboxCarouselEl) {
        return;
    }

    const lightboxModal = new bootstrap.Modal(lightboxEl);
    const lightboxCarousel = new bootstrap.Carousel(lightboxCarouselEl, { ride: false });

    mainCarousel.querySelectorAll('.auction-carousel-image').forEach((img) => {
        img.addEventListener('click', () => {
            const index = Number(img.dataset.slideIndex ?? 0);
            lightboxModal.show();
            lightboxCarousel.to(index);
        });
    });
}

function initFollowButton() {
    const button = document.getElementById('follow-btn');

    if (!button) {
        return;
    }

    button.addEventListener('click', async () => {
        const isFollowed = button.dataset.followed === '1';
        const url = isFollowed ? button.dataset.unfollowUrl : button.dataset.followUrl;
        const method = isFollowed ? 'DELETE' : 'POST';

        button.disabled = true;

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': button.dataset.csrf,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            const followed = Boolean(data.followed);

            button.dataset.followed = followed ? '1' : '0';
            button.classList.toggle('is-followed', followed);
            button.setAttribute('aria-pressed', followed ? 'true' : 'false');
            button.setAttribute(
                'aria-label',
                followed ? 'Usuń z obserwowanych' : 'Dodaj do obserwowanych',
            );
        } finally {
            button.disabled = false;
        }
    });
}

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
