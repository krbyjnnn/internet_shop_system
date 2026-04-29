<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { padding: 8px; width: 300px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 8px 20px; background: #2ecc71; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 12px; }
        a { color: #333; }
    </style>
</head>
<body>
    <h1>Add Product</h1>

    <form method="POST" action="{{ route('admin.products.store') }}">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Price (₱)</label>
            <input type="number" name="price" value="{{ old('price') }}" min="1" step="0.01">
            @error('price') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Stock</label>
            <input type="number" name="stock" value="{{ old('stock') }}" min="0">
            @error('stock') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Add Product</button>
    </form>

    <br>
    <a href="{{ route('admin.products.index') }}">← Back to Products</a>
</body>
</html>