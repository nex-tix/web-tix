//----------------
// - COLUMN CHART -
//----------------

const column_chart_options = {
  series: [
    {
      name: "Tunai",
      data: [443000, 556000, 572000, 614000, 2000000],
    },
    {
      name: "Debit",
      data: [500000, 412000, 368000, 453000, 2300000],
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
    categories: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025", "Q5 2025"],
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
        return `Rp ${value.toLocaleString()}`;
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
