function refreshStock() {
    fetch(window.location.href)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // update stock numbers only
            document.querySelectorAll('.stock-display').forEach(el => {
                const id = el.dataset.productId;
                const newEl = doc.querySelector('.stock-display[data-product-id="' + id + '"]');
                if (newEl) el.innerText = newEl.innerText;
            });
        });
}

setInterval(refreshStock, 30000); // every 30 seconds