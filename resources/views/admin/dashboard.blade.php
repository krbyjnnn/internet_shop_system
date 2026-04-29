<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
            color: #333;
        }
        h1 {
            margin-bottom: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            max-width: 600px;
        }
        .station {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
        }
        .available {
            background-color: #2ecc71;
            color: white;
        }
        .occupied {
            background-color: #e74c3c;
            color: white;
        }
        .logout {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Total Stations: {{ $stations->count() }}</p>

    <div class="grid">
        @foreach ($stations as $station)
            <div class="station {{ $station->is_occupied ? 'occupied' : 'available' }}">
                {{ $station->name }}
                <br>
                <small>{{ $station->is_occupied ? 'Occupied' : 'Available' }}</small>
            </div>
        @endforeach
    </div>

    <div class="logout">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>