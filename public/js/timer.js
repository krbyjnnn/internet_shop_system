const ratePerHour = 15;
let balance = window.customerBalance;
let totalSeconds = Math.floor((balance / ratePerHour) * 3600);
let redirected = false;
let timerInterval;

function updateTimer() {
    if (totalSeconds <= 0 && !redirected) {
        redirected = true;
        clearInterval(timerInterval);
        document.getElementById('timer').innerText = '00:00:00';
        document.getElementById('timer').style.color = '#e74c3c';

        // deduct all remaining balance from DB then redirect
        fetch('/customer/deduct-balance', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ amount: balance })
        }).then(() => {
            setTimeout(function() {
                window.location.href = '/customer/locked';
            }, 1000);
        });
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
timerInterval = setInterval(updateTimer, 1000);