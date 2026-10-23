if (typeof serverDonutChartData !== "undefined") {
  const donutCharts = {
    1: { id: "#donut-chart-1", data: serverDonutChartData.methodPay },
    2: { id: "#donut-chart-2", data: serverDonutChartData.originType },
    3: { id: "#donut-chart-3", data: serverDonutChartData.ticketType },
  };

  const instances = {};
  const currentMonth = new Date().getMonth() + 1;

  Object.entries(donutCharts).forEach(([key, chart]) => {
    const data = chart.data[currentMonth];
    instances[key] = new ApexCharts(document.querySelector(chart.id), {
      chart: { type: "donut", height: 300 },
      legend: { position: "bottom" },
      plotOptions: { pie: { donut: { size: "35%" } } },
      colors: ["#CF4647", "#2A363B", "#f1c40f"],
      series: data.series,
      labels: data.labels,
    });
    instances[key].render();
  });

  const donutMonthSelect = document.getElementById("select-donut-bulan");
  if (donutMonthSelect) {
    donutMonthSelect.value = currentMonth;

    donutMonthSelect.addEventListener("change", () => {
      const month = parseInt(donutMonthSelect.value, 10);
      if (!month) return;

      Object.keys(donutCharts).forEach((key) => {
        const data = donutCharts[key].data[month];
        if (!data) return;

        instances[key].updateOptions({
          series: data.series,
          labels: data.labels,
        });
      });
    });
  }
}