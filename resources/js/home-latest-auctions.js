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
                box.innerHTML = '<p>Brak aukcji.</p>';
                return;
            }

            box.innerHTML = data.map((auction) => {
                const imageUrl = auction.image?.url || placeholderUrl;
                const price = Number(auction.price).toLocaleString('pl-PL', {
                    maximumFractionDigits: 0,
                });

                return `
                    <div class="newest-auction-card">
                        <img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(auction.name)}">
                        <div class="auction-title">${escapeHtml(auction.name)}</div>
                        <a href="/auctions/${auction.id}" class="check-auction-btn">Sprawdź</a>
                        <div class="auction-price">${price} zł</div>
                    </div>
                `;
            }).join('');
        })
        .catch(() => {
            box.innerHTML = '<p class="text-danger">Nie udało się załadować aukcji.</p>';
        });
});

function escapeHtml(text) {
    const element = document.createElement('div');
    element.textContent = text ?? '';
    return element.innerHTML;
}
