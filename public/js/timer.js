const balance = window.customerBalance;
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