<!DOCTYPE html>
<html>
<head>
    <title>Select Your PC</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        h1 { margin-bottom: 20px; }
        .grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; max-width: 600px; }
        .station { padding: 20px; border-radius: 8px; text-align: center; font-weight: bold; cursor: pointer; background: #2ecc71; color: white; border: none; font-size: 16px; }
        .station:hover { background: #27ae60; }
    </style>
</head>
<body>
    <h1>Select Your PC</h1>
    <p>Please click the PC you are sitting at:</p>

    <form method="POST" action="{{ route('customer.select_station.store') }}">
        @csrf
        <div class="grid">
            @foreach ($stations as $station)
                <button type="submit" name="station_id" value="{{ $station->id }}" class="station">
                    {{ $station->name }}
                </button>
            @endforeach
        </div>
    </form>
</body>
</html>