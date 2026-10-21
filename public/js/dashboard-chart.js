//----------------
      // - COLUMN CHART -
      //----------------

      const column_chart_options = {
        series: [
          {
            name: 'Online Store',
            data: [44300, 55600, 57200, 61400],
          },
          {
            name: 'Retail Stores',
            data: [35100, 41200, 36800, 45300],
          },
        ],
        chart: {
          height: 300,
          type: 'bar',
          toolbar: {
            show: false,
          },
        },
        colors: ['#6f42c1', '#20c997'],
        plotOptions: {
          bar: {
            columnWidth: '55%',
            borderRadius: 4,
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {
          categories: ['Q1 2025', 'Q2 2025', 'Q3 2025', 'Q4 2025'],
        },
        yaxis: {
          labels: {
            formatter(value) {
              return `$${Math.round(value / 1000)}k`;
            },
          },
        },
        tooltip: {
          y: {
            formatter(value) {
              return `$${value.toLocaleString()}`;
            },
          },
        },
      };

      const column_chart = new ApexCharts(
        document.querySelector('#column-chart'),
        column_chart_options,
      );
      column_chart.render();

      //--------------------
      // - END COLUMN CHART -
      //--------------------