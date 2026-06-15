document.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('auctions-box');
    if (!box) {
        return;
    }

    const placeholderUrl = box.dataset.placeholderUrl || '/assets/placeholder.png';

    fetch('/api/auctions/latest?limit=6', {
        headers: { Accept: 'application/json' },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            return response.json();
        })
        .then(({ data }) => {
            if (!data?.length) {
                box.innerHTML = '<p class="home-auctions-empty">Brak aukcji.</p>';
                return;
            }

            box.innerHTML = data.map((auction) => {
                const imageUrl = auction.image?.url || placeholderUrl;

                return `
                    <a href="/auctions/${auction.id}" class="newest-auction-card">
                        <div class="newest-auction-card__image">
                            <img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(auction.name)}">
                        </div>
                        <div class="newest-auction-card__body">
                            <div class="newest-auction-card__title">${escapeHtml(auction.name)}</div>
                            <div class="newest-auction-card__footer">
                                <div class="newest-auction-card__price">${escapeHtml(formatPrice(auction))}</div>
                                <span class="newest-auction-card__action">Sprawdź</span>
                            </div>
                        </div>
                    </a>
                `;
            }).join('');
        })
        .catch(() => {
            box.innerHTML = '<p class="home-auctions-empty home-auctions-empty--error">Nie udało się załadować aukcji.</p>';
        });
});

function formatPrice(auction) {
    if (auction.isJobOffer) {
        if (auction.salaryType === 'do uzgodnienia' || (auction.negotiable && !auction.salaryType)) {
            return 'Do uzgodnienia';
        }

        const price = Number(auction.price).toLocaleString('pl-PL', {
            maximumFractionDigits: 0,
        });

        return auction.salaryType ? `${price} zł ${auction.salaryType}` : `${price} zł`;
    }

    return `${Number(auction.price).toLocaleString('pl-PL', {
        maximumFractionDigits: 0,
    })} zł`;
}

function escapeHtml(text) {
    const element = document.createElement('div');
    element.textContent = text ?? '';
    return element.innerHTML;
}
