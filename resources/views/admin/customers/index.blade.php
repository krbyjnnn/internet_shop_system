<!DOCTYPE html>
<html>
<head>
    <title>Customers</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        a { padding: 8px 16px; background: #2ecc71; color: white; text-decoration: none; border-radius: 4px; }
        .success { background: #2ecc71; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Customers</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.customers.create') }}">+ Create Customer</a>
    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>₱{{ $customer->balance }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
</body>
</html>