<!DOCTYPE html>
<html>
<head>
    <title>Top-up Balance</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { padding: 8px; width: 300px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 8px 20px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; }
        a { color: #333; }
    </style>
</head>
<body>
    <h1>Top-up Balance</h1>

    @if(session('success'))
        <div style="background:#2ecc71; color:white; padding:10px; margin-bottom:15px; border-radius:4px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.customers.topup', request('user_id')) }}">
        @csrf

        <div class="form-group">
            <label>Search Customer</label>
            <input type="text" id="search" placeholder="Type username..." onkeyup="filterCustomers()" autocomplete="off">
        </div>

        <div class="form-group">
            <label>Select Customer</label>
            <select name="user_id" id="customerSelect" size="5" style="width:300px; height:auto;">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->username }} — ₱{{ $customer->balance }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Amount (₱)</label>
            <input type="number" name="amount" min="1" placeholder="Enter amount">
            @error('amount') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Top-up</button>
    </form>

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>

    <script>
        function filterCustomers() {
            const search = document.getElementById('search').value.toLowerCase();
            const options = document.getElementById('customerSelect').options;
            for (let i = 0; i < options.length; i++) {
                const text = options[i].text.toLowerCase();
                options[i].style.display = text.includes(search) ? '' : 'none';
            }
        }
    </script>
</body>
</html>