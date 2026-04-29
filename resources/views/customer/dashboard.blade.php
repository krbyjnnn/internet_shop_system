<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .card { background: white; padding: 30px; border-radius: 8px; max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .timer { font-size: 48px; font-weight: bold; color: #2ecc71; text-align: center; margin: 20px 0; }
        .balance { font-size: 18px; color: #333; text-align: center; margin-bottom: 10px; }
        .username { font-size: 22px; font-weight: bold; text-align: center; margin-bottom: 5px; }
        button { padding: 8px 20px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="username">👤 {{ auth()->user()->name }}</div>
        <div class="balance">Balance: ₱{{ number_format(auth()->user()->balance, 2) }}</div>
        <div class="timer" id="timer">00:00:00</div>
        <p style="text-align:center; color:#999; font-size:12px;">Time remaining at ₱15/hour</p>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <script>
        const balance = {{ auth()->user()->balance }};
        const ratePerHour = 15;
        let totalSeconds = Math.floor((balance / ratePerHour) * 3600);

        function updateTimer() {
            if (totalSeconds <= 0) {
                document.getElementById('timer').innerText = '00:00:00';
                document.getElementById('timer').style.color = '#e74c3c';
                return;
            }

            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            document.getElementById('timer').innerText =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');

            totalSeconds--;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    </script>
</body>
</html>