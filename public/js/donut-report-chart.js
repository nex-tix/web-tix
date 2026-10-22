
const methodPay = serverDonutChartData.methodPay7Days;

// Menjumlahkan transaksi selama 7 hari
const totalTunai = methodPay.tunai.reduce(
  (total, value) => total + value, 0
);

const totalDebit = methodPay.debit.reduce(
  (total, value) => total + value, 0
);

const charts = [
  {
    id: "#donut-chart-1",
    series: [totalTunai, totalDebit],
    labels: ["Tunai", "Debit"],
  },
  {
    id: "#donut-chart-2",
    series: [120, 80],
    labels: ["Domestik", "Mancanegara"],
  },
  {
    id: "#donut-chart-3",
    series: [40, 40, 20],
    labels: ["Child", "Adult", "Group"],
  },
];

charts.forEach(function (item) {
  new ApexCharts(document.querySelector(item.id), {
    chart: {
      type: "donut",
      height: 300,
    },
    legend: {
      position: "bottom",
    },
    plotOptions: {
      pie: {
        donut: {
          size: "35%",
        },
      },
    },
    colors: ["#CF4647", "#2A363B", "#f1c40f"],
    series: item.series,
    labels: item.labels,
  }).render();
});
