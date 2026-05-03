<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .timer { font-size: 48px; font-weight: bold; color: #2ecc71; text-align: center; margin: 20px 0; }
        .balance { font-size: 18px; color: #333; text-align: center; margin-bottom: 10px; }
        .username { font-size: 22px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px; }
        .product-item { background: white; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .add-btn { background: #3498db; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 8px; }
        .add-btn:disabled { background: #bdc3c7; cursor: not-allowed; }
        .logout-btn { background: #e74c3c; color: white; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 10px; }
        .success { background: #2ecc71; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .error { background: #e74c3c; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; }

        /* Cart styles */
        .cart { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .cart h2 { margin-bottom: 15px; }
        .cart-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee; }
        .cart-item:last-child { border-bottom: none; }
        .remove-btn { background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; }
        .cart-total { font-size: 18px; font-weight: bold; margin-top: 10px; text-align: right; }
        .place-order-btn { background: #2ecc71; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 10px; }
        .empty-cart { color: #999; text-align: center; padding: 10px; }

        /* Modal styles */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
        .modal { background: white; padding: 30px; border-radius: 8px; max-width: 400px; margin: 100px auto; text-align: center; }
        .modal h2 { margin-bottom: 15px; }
        .modal-details { background: #f5f5f5; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; }
        .modal-item { display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid #ddd; }
        .modal-total { font-weight: bold; margin-top: 10px; text-align: right; }
        .modal-buttons { display: flex; gap: 10px; justify-content: center; }
        .confirm-btn { background: #2ecc71; color: white; border: none; padding: 10px 25px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .cancel-btn { background: #e74c3c; color: white; border: none; padding: 10px 25px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .added { border: 2px solid #2ecc71; }
    </style>
</head>
<body>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="username">👤 {{ auth()->user()->name }}</div>
        <div class="balance">Balance: ₱{{ number_format(auth()->user()->balance, 2) }}</div>
        <div class="timer" id="timer">00:00:00</div>
        <p style="text-align:center; color:#999; font-size:12px;">Time remaining at ₱15/hour</p>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <h2>🛒 Order Snacks</h2>
    <div class="product-grid">
        @foreach($products as $product)
        <div class="product-item" id="product-card-{{ $product->id }}">
            <strong>{{ $product->name }}</strong>
            <p>₱{{ $product->price }}</p>
            <p class="stock-display" data-product-id="{{ $product->id }}">
                Stock: {{ $product->stock }}
            </p>
            <input type="number"
                   id="qty-{{ $product->id }}"
                   value="1" min="1" max="{{ $product->stock }}"
                   style="width:60px; padding:4px; text-align:center; margin-bottom:5px;">
            <button type="button"
                    class="add-btn"
                    id="add-btn-{{ $product->id }}"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}
                    onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})">
                {{ $product->stock <= 0 ? 'Out of Stock' : '+ Add to Cart' }}
            </button>
        </div>
        @endforeach
    </div>

    <!-- Cart -->
    <div class="cart">
        <h2>🧺 Cart</h2>
        <div id="cart-items">
            <p class="empty-cart">No items yet!</p>
        </div>
        <div class="cart-total" id="cart-total" style="display:none;">
            Total: ₱<span id="total-amount">0.00</span>
        </div>
        <button class="place-order-btn" id="place-order-btn" style="display:none;" onclick="openModal()">
            🛒 Place Order
        </button>
    </div>

    <!-- Hidden form -->
    <form id="order-form" method="POST" action="{{ route('customer.orders.multiple') }}">
        @csrf
        <div id="hidden-inputs"></div>
    </form>

    <!-- Modal -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal">
            <h2>Confirm Order</h2>
            <div class="modal-details" id="modal-details"></div>
            <div class="modal-buttons">
                <button class="cancel-btn" onclick="closeModal()">❌ Cancel</button>
                <button class="confirm-btn" onclick="submitOrder()">✅ Confirm</button>
            </div>
        </div>
    </div>

    <script>
        window.customerBalance = {{ auth()->user()->balance }};
    </script>
    <script src="{{ asset('js/timer.js') }}"></script>
    <script src="{{ asset('js/stock-refresh.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>