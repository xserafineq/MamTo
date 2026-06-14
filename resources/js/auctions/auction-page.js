document.addEventListener('DOMContentLoaded', () => {
    initImageLightbox();
    initFollowButton();
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

