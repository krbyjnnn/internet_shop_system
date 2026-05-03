let cart = {};

function addToCart(productId, productName, price, stock) {
    const qty = parseInt(document.getElementById('qty-' + productId).value);

    if (qty > stock) {
        alert('Not enough stock!');
        return;
    }

    cart[productId] = {
        id: productId,
        name: productName,
        price: price,
        quantity: qty,
        total: price * qty
    };

    // highlight product card
    document.getElementById('product-card-' + productId).classList.add('added');
    document.getElementById('add-btn-' + productId).innerText = '✅ Added';

    renderCart();
}

function removeFromCart(productId) {
    delete cart[productId];

    // remove highlight
    document.getElementById('product-card-' + productId).classList.remove('added');
    document.getElementById('add-btn-' + productId).innerText = '+ Add to Cart';

    renderCart();
}

function renderCart() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const totalAmount = document.getElementById('total-amount');

    const keys = Object.keys(cart);

    if (keys.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">No items yet!</p>';
        cartTotal.style.display = 'none';
        placeOrderBtn.style.display = 'none';
        return;
    }

    let html = '';
    let total = 0;

    keys.forEach(id => {
        const item = cart[id];
        total += item.total;
        html += `
            <div class="cart-item">
                <span>${item.name} x${item.quantity}</span>
                <span>₱${item.total.toFixed(2)}</span>
                <button class="remove-btn" onclick="removeFromCart(${item.id})">✕</button>
            </div>
        `;
    });

    cartItems.innerHTML = html;
    totalAmount.innerText = total.toFixed(2);
    cartTotal.style.display = 'block';
    placeOrderBtn.style.display = 'block';
}

function openModal() {
    const keys = Object.keys(cart);
    if (keys.length === 0) return;

    let html = '';
    let total = 0;

    keys.forEach(id => {
        const item = cart[id];
        total += item.total;
        html += `
            <div class="modal-item">
                <span>${item.name} x${item.quantity}</span>
                <span>₱${item.total.toFixed(2)}</span>
            </div>
        `;
    });

    html += `<div class="modal-total">Total: ₱${total.toFixed(2)}</div>`;

    document.getElementById('modal-details').innerHTML = html;
    document.getElementById('modal-overlay').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal-overlay').style.display = 'none';
}

function submitOrder() {
    const keys = Object.keys(cart);
    let html = '';

    keys.forEach((id, index) => {
        const item = cart[id];
        html += `
            <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
        `;
    });

    document.getElementById('hidden-inputs').innerHTML = html;
    document.getElementById('order-form').submit();
}