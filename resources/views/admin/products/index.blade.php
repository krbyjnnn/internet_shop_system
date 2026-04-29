<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        .success { background: #2ecc71; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        a { padding: 8px 16px; background: #2ecc71; color: white; text-decoration: none; border-radius: 4px; }
        .delete { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Products</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.products.create') }}">+ Add Product</a>
    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>₱{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <a href="{{ route('admin.dashboard') }}" style="background:#333;">← Back to Dashboard</a>
</body>
</html>