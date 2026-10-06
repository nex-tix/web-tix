      /* ============================================================ CHART ============================================================ */ const ctx =
        document.getElementById("salesChart").getContext("2d");
      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["Rab", "Kam", "Jum", "Sab", "Min", "Sen", "Hari ini"],
          datasets: [
            {
              label: "Penjualan",
              data: [
                5800000, 6900000, 5200000, 7400000, 6300000, 6800000, 8209000,
              ],
              borderColor: "#e74c3c",
              backgroundColor: "rgba(231, 76, 60, 0.08)",
              borderWidth: 2,
              pointBackgroundColor: "#fff",
              pointBorderColor: "#e74c3c",
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6,
              fill: true,
              tension: 0.3,
            },
          ],
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
                label: function (context) {
                  return "Rp " + context.parsed.y.toLocaleString("id-ID");
                },
              },
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: "#f0f0f0", drawBorder: false },
              ticks: {
                font: { size: 11 },
                color: "#6c757d",
                callback: function (value) {
                  if (value === 0) {
                    return "Rp 0";
                  }
                  return (
                    "Rp " +
                    (value / 1000000).toFixed(1).replace(".", ",") +
                    " jt"
                  );
                },
              },
            },
            x: {
              grid: { display: false },
              ticks: { font: { size: 11 }, color: "#6c757d" },
            },
          },
        },
      });
      /* ============================================================ DATATABLE ============================================================ */ $(
        document,
      ).ready(function () {
        $("#transactionTable").DataTable({
          pageLength: 5,
          lengthChange: false,
          searching: false,
          ordering: false,
          info: true,
          paging: true,
          language: {
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            paginate: {
              previous: "<i class='bi bi-chevron-left'></i>",
              next: "<i class='bi bi-chevron-right'></i>",
            },
          },
          layout: {
            topStart: {
              buttons: [
                "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"
            ],
            },
          },
          columnDefs: [{ orderable: false, targets: [7] }],
        });
      });