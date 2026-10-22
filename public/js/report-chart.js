const column_chart_report = {
  series: [
    {
      name: "Tunai",
      data: [44300, 55600, 57200, 61400, 200000, 290000, 1000000],
    },
    {
      name: "debit",
      data: [35100, 41200, 36800, 45300, 900000, 1200000, 1750000],
    },
  ],
  chart: {
    height: 300,
    type: "bar",
    toolbar: {
      show: false,
    },
  },
  colors: ["#CF4647", "#2A363B"],
  plotOptions: {
    bar: {
      columnWidth: "55%",
      borderRadius: 4,
    },
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    categories: [
      "Q1 2025",
      "Q2 2025",
      "Q3 2025",
      "Q4 2025",
      "Q5 2025",
      "Q6 2025",
      "Q7 2025",
    ],
  },
  yaxis: {
    labels: {
      formatter(value) {
        return `Rp ${(value / 1000000).toFixed(1)} Jt`;
      },
    },
  },
  tooltip: {
    y: {
      formatter(value) {
        return `Rp ${value.toLocaleString("id-ID")}`;
      },
    },
  },
};

const report_chart = new ApexCharts(
  document.querySelector("#column-chart-report"),
  column_chart_report,
);
report_chart.render();
