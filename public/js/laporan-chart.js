document.addEventListener("DOMContentLoaded", function() {
    const el = document.getElementById('laporanSalesChart');
    if (!el || typeof Chart === 'undefined') return;

    const chartData = JSON.parse(el.getAttribute('data-chart') || '{}');
    const labels = chartData.labels || [];
    const data = chartData.data || [];

    new Chart(el.getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Penjualan",
                data: data,
                borderColor: "#e74c3c",
                backgroundColor: "rgba(231,76,60,0.08)",
                borderWidth: 2,
                pointBackgroundColor: "#fff",
                pointBorderColor: "#e74c3c",
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: "#2c3136",
                    titleFont: { size: 12 },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: c => "Rp " + c.parsed.y.toLocaleString("id-ID")
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: "#f0f0f0", drawBorder: false },
                    ticks: {
                        font: { size: 11 },
                        color: "#6c757d",
                        callback: v => v === 0 ? "Rp 0" : "Rp " + (v / 1000000).toFixed(1).replace(".", ",") + " jt"
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: "#6c757d" }
                }
            }
        }
    });
});
