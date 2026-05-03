<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        .success { background: #2ecc71; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .deliver { background: #2ecc71; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; }
        .tab { padding: 10px 20px; border-radius: 4px; cursor: pointer; border: none; font-size: 15px; }
        .tab.active { background: #333; color: white; }
        .tab.inactive { background: white; color: #333; border: 1px solid #ddd; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .badge { background: #e74c3c; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px; }
        .delivered { color: #2ecc71; font-weight: bold; }
        a { padding: 8px 16px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Orders</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="tabs">
        <button class="tab active" onclick="switchTab('pending')">
            🕐 Pending
            @if($pendingOrders->count() > 0)
                <span class="badge">{{ $pendingOrders->count() }}</span>
            @endif
        </button>
        <button class="tab inactive" onclick="switchTab('history')">
            📋 History
        </button>
    </div>

    {{-- Pending Orders --}}
    <div id="tab-pending" class="tab-content active">
        @if($pendingOrders->isEmpty())
            <p>No pending orders!</p>
        @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Station</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->station->name }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>₱{{ $order->total_price }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.orders.deliver', $order) }}">
                            @csrf
                            <button type="submit" class="deliver">✅ Delivered</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Order History --}}
    <div id="tab-history" class="tab-content">
        @if($deliveredOrders->isEmpty())
            <p>No delivered orders yet!</p>
        @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Station</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deliveredOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->station->name }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>₱{{ $order->total_price }}</td>
                    <td class="delivered">✅ Delivered</td>
                    <td>{{ $order->updated_at->format('h:i A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>

    <script>
        function switchTab(tab) {
            // hide all tabs
            document.getElementById('tab-pending').classList.remove('active');
            document.getElementById('tab-history').classList.remove('active');

            // show selected tab
            document.getElementById('tab-' + tab).classList.add('active');

            // update button styles
            document.querySelectorAll('.tab').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('inactive');
            });
            event.target.classList.add('active');
            event.target.classList.remove('inactive');
        }
    </script>
</body>
</html>