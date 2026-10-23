const chartData = {
  "7days": {
    categories: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
    tunai: [44300, 55600, 57200, 61400, 200000, 290000, 1000000],
    debit: [35100, 41200, 36800, 45300, 900000, 1200000, 1750000],
  },
  // dummy 31 hari, nanti dipotong sesuai jumlah hari di bulan terpilih
  month: {
    tunai: [
      40000, 52000, 48000, 61000, 55000, 70000, 90000, 45000, 50000, 58000,
      62000, 66000, 80000, 95000, 47000, 51000, 49000, 63000, 59000, 72000,
      88000, 99000, 46000, 53000, 57000, 64000, 69000, 77000, 91000, 105000,
      98000,
    ],
    debit: [
      30000, 41000, 39000, 52000, 47000, 61000, 85000, 36000, 42000, 49000,
      55000, 60000, 78000, 92000, 38000, 43000, 40000, 56000, 51000, 68000,
      84000, 97000, 37000, 44000, 50000, 58000, 65000, 74000, 89000, 102000,
      94000,
    ],
  },
  year: {
    categories: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "Mei",
      "Jun",
      "Jul",
      "Agu",
      "Sep",
      "Okt",
      "Nov",
      "Des",
    ],
    tunai: [
      1200000, 1100000, 1350000, 1280000, 1500000, 1420000, 1600000, 1550000,
      1700000, 1650000, 1800000, 2100000,
    ],
    debit: [
      2100000, 1900000, 2300000, 2200000, 2600000, 2450000, 2800000, 2700000,
      3000000, 2900000, 3200000, 3900000,
    ],
  },
};


chartData["7days"] = serverChartData["7days"]; // data asli menimpa dummy
chartData["year"] = serverChartData["perYear"]; // data asli menimpa dummy
chartData["month"] = serverChartData["perMonth"]; // data asli menimpa dummy
const column_chart_report = {
  series: [
    { name: "Tunai", data: chartData["7days"].tunai },
    { name: "debit", data: chartData["7days"].debit },
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
    },
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    categories: chartData["7days"].categories,
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

// ===== state tombol & dropdown =====
const buttons = document.querySelectorAll(".btn-chart-laporan:not(.btn-donut-filter)");
const selectBulan = document.getElementById("select-bulan");

function updateChart(data) {
  report_chart.updateOptions({
    series: [
      { name: "Tunai", data: data.tunai },
      { name: "debit", data: data.debit },
    ],
    xaxis: { categories: data.categories },
  });
}

// tombol 7 hari & 1 tahun
buttons.forEach((btn) => {
  btn.addEventListener("click", () => {
    const range = btn.dataset.range;

    selectBulan.value = "";
    updateChart(chartData[range]);
  });
});

// dropdown bulan
selectBulan.addEventListener("change", () => {
  const month = Number(selectBulan.value);

  // kalau kembali ke "Pilih bulan", balik ke 7 hari
  if (!month) {
    updateChart(chartData["7days"]);
    return;
  }

  updateChart(chartData.month[month]);
});
