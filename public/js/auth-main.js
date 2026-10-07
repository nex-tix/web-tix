
        document.addEventListener('DOMContentLoaded', () => {

            // Live time clock in WITA (UTC+8)
            const dateTimeSpan = document.getElementById('current-date-time');
            const updateClock = () => {
                const now = new Date();
                const options = {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'Asia/Makassar' // WITA
                };
                dateTimeSpan.textContent = now.toLocaleDateString('id-ID', options) + ' WITA';
            };

            updateClock();
            setInterval(updateClock, 1000);
        });
