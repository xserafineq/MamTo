document.addEventListener('DOMContentLoaded', () => {
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
});
