//----------------
// - COLUMN CHART -
//----------------
(function () {
  const chartEl = document.querySelector("#column-chart");
  const report = window.dashboardReport;

  if (!chartEl || !report) return;
   // Warna per tema
  const chartColors = {
    light: ["#CF4647", "#2A363B"],
    dark: ["#CF4647", "#f1c40f"], // Debit: kuning agar kontras di dark mode
  };
  const getTheme = () =>
    document.documentElement.getAttribute("data-theme") === "dark"
      ? "dark"
      : "light";

  const chartRangeData = report.chartRangeData || {
    "7days": {
      title: "Penjualan tujuh hari",
      categories: report.labels || [],
      tunai: report.cash || [],
      debit: report.debit || [],
    },
    "30days": {
      title: "Penjualan 1 bulan terakhir",
      categories: report.labels || [],
      tunai: report.cash || [],
      debit: report.debit || [],
    },
  };

  const initialRange = "7days";
  const initialData = chartRangeData[initialRange] || {
    categories: report.labels || [],
    tunai: report.cash || [],
    debit: report.debit || [],
  };

  const column_chart_options = {
    series: [
      {
        name: "Tunai",
        data: initialData.tunai,
      },
      {
        name: "Debit",
        data: initialData.debit,
      },
    ],
    chart: {
      height: 300,
      type: "bar",
      toolbar: {
        show: false,
      },
    },
    colors: chartColors[getTheme()], // <-- warna awal sesuai tema
    plotOptions: {
      bar: {
        columnWidth: "55%",
        borderRadius: 0,
      },
    },
    dataLabels: {
      enabled: false,
    },
    xaxis: {
      categories: initialData.categories,
      labels: {
        rotate: -45,
        rotateAlways: false,
        hideOverlappingLabels: true,
      },
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

  const column_chart = new ApexCharts(chartEl, column_chart_options);
  column_chart.render();

  // Filter tombol 7 hari & 1 bulan terakhir
  const buttons = document.querySelectorAll(".chart-card .btn-chart-laporan[data-range]");
  const chartTitle = document.getElementById("dashboard-chart-title");

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const range = btn.dataset.range;
      const data = chartRangeData[range];
      if (!data) return;

      buttons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      if (chartTitle && data.title) {
        chartTitle.textContent = data.title;
      }

      column_chart.updateOptions({
        series: [
          {
            name: "Tunai",
            data: data.tunai,
          },
          {
            name: "Debit",
            data: data.debit,
          },
        ],
        xaxis: {
          categories: data.categories,
        },
      });
    });
  });

  
  // Update warna chart saat tema berubah
  new MutationObserver(() => {
    column_chart.updateOptions({
      colors: chartColors[getTheme()],
    });
  }).observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["data-theme"],
  });

})();

//--------------------
// - END COLUMN CHART -
//--------------------
