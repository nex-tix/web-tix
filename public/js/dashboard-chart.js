//----------------
// - COLUMN CHART -
//----------------
const report = window.dashboardReport;

const column_chart_options = {
  series: [
    {
      name: "Tunai",
      data: report.cash,
    },
    {
      name: "Debit",
      data: report.debit,
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
      borderRadius: 0,
    },
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    categories: report.labels,
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

const column_chart = new ApexCharts(
  document.querySelector("#column-chart"),
  column_chart_options,
);
column_chart.render();

//--------------------
// - END COLUMN CHART -
//--------------------
