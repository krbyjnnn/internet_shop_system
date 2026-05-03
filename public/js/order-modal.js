let selectedProductId = null;
let selectedPrice = 0;

function openModal(productId, productName, price) {
    const qty = parseInt(document.getElementById('qty-' + productId).value);
    const total = price * qty;

    selectedProductId = productId;
    selectedPrice = price;

    document.getElementById('modal-product-name').innerText = productName;
    document.getElementById('modal-quantity').innerText = qty;
    document.getElementById('modal-total').innerText = '₱' + total.toFixed(2);
    document.getElementById('modal-overlay').style.display = 'block';
}

function closeModal() {
    document.getElementById('modal-overlay').style.display = 'none';
}

function submitOrder() {
    const qty = parseInt(document.getElementById('qty-' + selectedProductId).value);
    document.getElementById('form-product-id').value = selectedProductId;
    document.getElementById('form-quantity').value = qty;
    document.getElementById('order-form').submit();
}