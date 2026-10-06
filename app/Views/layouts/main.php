<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kecak Uluwatu - Dashboard (AdminLTE 4)</title>

  <!-- Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <!-- DataTables Bootstrap 5 CSS -->
  <link
    href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.datatables.net/v/dt/dt-3.0.2/datatables.min.css"
    rel="stylesheet" />
  <link
    href="https://cdn.datatables.net/buttons/4.0.2/css/buttons.dataTables.min.css"
    rel="stylesheet" />
  <!-- AdminLTE 4 CSS -->
  <link rel="stylesheet" href="public/css/adminlte.min.css" />

  <link rel="stylesheet" href="public/css/main.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <!-- ===================================================== HEADER ====================================================== -->

    <!-- ===================================================== SIDEBAR ====================================================== -->
    <!-- Sidebar AdminLTE 4 -->
    <aside class="app-sidebar" data-bs-theme="dark">
      <!-- Brand -->
      <div class="brand-link">
        <div class="brand-icon">
          <i class="bi bi-tree-fill"></i>
        </div>

        <div class="brand-text">
          <h6>Kecak Uluwatu</h6>
          <span>Loket & Gate</span>
        </div>
      </div>

      <!-- Sidebar Wrapper -->
      <div class="sidebar-wrapper">
        <!-- Menu -->
        <nav>
          <div class="nav-section">Operasional</div>

          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-pc-display"></i>
                <p>POS Loket</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-receipt"></i>
                <p>Riwayat Penjualan</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-bar-chart-line"></i>
                <p>Laporan</p>
              </a>
            </li>
          </ul>

          <div class="nav-section">Master Data</div>

          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-ticket-perforated"></i>
                <p>Jenis Tiket</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-people"></i>
                <p>Pengguna</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-check2-square"></i>
                <p>Checkpoint</p>
              </a>
            </li>
          </ul>

          <div class="nav-section">Sistem</div>

          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="bi bi-gear"></i>
                <p>Pengaturan</p>
              </a>
            </li>
          </ul>
        </nav>

        <!-- User Footer -->
        <div class="sidebar-footer">
          <div class="avatar">AL</div>

          <div class="user-info">
            <h6>Admin Loket 1</h6>
            <span>Terminal loket</span>
          </div>

          <i class="bi bi-box-arrow-right logout-icon"></i>
        </div>
      </div>
    </aside>
    <!-- ===================================================== MAIN ====================================================== -->
    <main class="app-main">
      <!-- ================================================= TOP STATUS ================================================== -->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid px-0">
          <!-- Sidebar Toggle -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a
                class="nav-link"
                data-lte-toggle="sidebar"
                href="#"
                role="button"
                aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
              </a>
            </li>
          </ul>
          <!-- Header kanan -->
          <div class="top-bar-right">
            <div class="header-clock">
              <span class="date-label"> Hari dagang - Sel, 6 Okt 2026 </span>
              <span class="time-label">
                Sisa 10j 54m <span>batas 24.00 WITA</span>
              </span>
            </div>
            <a href="#" class="btn-keluar">
              <i class="bi bi-box-arrow-right"></i> Keluar
            </a>
          </div>
        </div>
      </nav>

      <!-- ================================================= CONTENT ================================================== -->
      <div class="app-content">
        <div class="container-fluid px-0">
          <div class="app-header-custom">
            <div class="top-bar-left">
              <i class="bi bi-circle-fill dot-green text-success"></i>
              <span style="color: #2c3136">
                Terminal Loket 1 &middot; Shift siang &middot; online
              </span>
            </div>
          </div>
          <!-- ================================================= PAGE HEADER ================================================== -->
          <div class="app-content-header">
            <div class="page-title">
              <h4 style="color: #2c3136">Papan hari ini</h4>
              <p style="color: #2c3136">
                Ringkasan penjualan dan kunjungan seluruh gate pada hari
                dagang berjalan.
              </p>
            </div>
            <div class="d-flex gap-2">
              <button class="btn-laporan">
                <i class="bi bi-bar-chart-line"></i> Laporan
              </button>
              <button class="btn-transaksi">
                <i class="bi bi-plus-lg"></i> <span>Transaksi baru</span>
              </button>
            </div>
          </div>
          <!-- ========================================= STAT CARDS ========================================== -->
          <div class="row g-3 mb-3">
            <div class="col-md-3">
              <div class="stat-card" style="background-color: #fdf8e8">
                <div class="stat-label">Penjualan Hari Ini</div>
                <div class="stat-value">Rp 8.209.000</div>
                <div class="stat-sub green">
                  +24% dari kemarin (Rp 6.600.000)
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-card">
                <div class="stat-label">Tiket Terbit</div>
                <div class="stat-value">43 <small>tiket</small></div>
                <div class="stat-sub">35 individual &middot; 8 rombongan</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-card">
                <div class="stat-label">Pengunjung Sudah Masuk</div>
                <div class="stat-value">41 <small>orang</small></div>
                <div class="stat-sub">44% dari 94 pengunjung terjual</div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat-card">
                <div class="stat-label">
                  <span class="dot-yellow"></span> Belum Dipakai
                </div>
                <div class="stat-value">18 <small>tiket</small></div>
                <div class="stat-sub">
                  53 pengunjung &middot; hangus 24.00 WITA
                </div>
              </div>
            </div>
          </div>
          <!-- ========================================= PAYMENT PROGRESS ========================================== -->
          <div class="card border-0 shadow-sm mb-3 bg-white">
            <div class="card-body p-3">
              <div class="custom-progress">
                <div class="bar-dark" style="width: 66.6%"></div>
                <div class="bar-red" style="width: 33.4%"></div>
              </div>
              <div class="progress-legend">
                <div class="d-flex gap-3 flex-wrap">
                  <div class="legend-item">
                    <span
                      class="legend-color">
                    </span>
                    Tunai Rp 5.465.000 (14 nota)
                  </div>
                  <div class="legend-item">
                    <span
                      class="legend-color"
                      style="background-color: #e74c3c">
                    </span>
                    Debit Rp 2.744.000 (6 nota)
                  </div>
                </div>
                <div class="fw-semibold">Total diterima Rp 8.209.000</div>
              </div>
            </div>
          </div>
          <!-- ========================================= CHART + CASH ========================================== -->
          <div class="row g-3 mb-3">
            <!-- Chart -->
            <div class="col-lg-8">
              <div class="chart-card">
                <div class="card-header-custom">
                  <div>
                    <h6 style="color: #2c3136">Penjualan tujuh hari</h6>
                    <p>Total dibayar per hari dagang (WITA), termasuk void</p>
                  </div>
                  <span class="badge-hari-ini"> hari ini </span>
                </div>
                <div style="height: 250px">
                  <canvas id="salesChart"></canvas>
                </div>
                <div
                  class="d-flex justify-content-between mt-3 flex-wrap gap-2"
                  style="font-size: 0.8rem; color: var(--text-muted)">
                  <span>
                    Rata-rata tujuh hari
                    <strong style="color: var(--text-dark)">
                      Rp 6.067.000
                    </strong>
                  </span>
                  <span>
                    Tertinggi
                    <strong style="color: var(--text-dark)">
                      Rp 8.209.000
                    </strong>
                  </span>
                  <span>
                    Hari ini
                    <strong style="color: var(--text-dark)">
                      Rp 8.209.000
                    </strong>
                  </span>
                </div>
              </div>
            </div>
            <!-- Cash -->
            <div class="col-lg-4">
              <div class="cash-card">
                <h6 style="color: #2c3136">Cocokkan kas & EDC</h6>
                <p class="sub">Yang harus sama saat tutup loket</p>
                <div class="cash-row">
                  <span class="label"> Setoran tunai </span>
                  <div class="bar-container">
                    <div class="bar-fill dark" style="width: 66.6%"></div>
                  </div>
                  <span class="value"> Rp 5.465.000 </span>
                </div>
                <div class="cash-row">
                  <span class="label"> Mesin EDC </span>
                  <div class="bar-container">
                    <div class="bar-fill red" style="width: 33.4%"></div>
                  </div>
                  <span class="value"> Rp 2.744.000 </span>
                </div>
                <div class="cash-row sub-row">
                  <span class="label"> Jumlah nota </span>
                  <span class="value"> 20 nota </span>
                </div>
                <div class="cash-row">
                  <span class="label"> Diskon diberikan </span>
                  <span class="value" style="color: #e74c3c">
                    -Rp 271.000
                  </span>
                </div>
                <div class="cash-row">
                  <span class="label"> Nota di-void </span>
                  <span class="value"> 1 nota &middot; Rp 200.000 </span>
                </div>
                <div class="info-box">
                  <i class="bi bi-info-circle-fill"></i>
                  <span>
                    Angka tunai dicocokkan dengan isi laci, angka EDC dengan
                    slip mesin. Void bukan refund.
                  </span>
                </div>
              </div>
            </div>
          </div>
          <!-- ========================================= TABLE ========================================== -->
          <div class="table-card">
            <div class="card-header-custom">
              <div>
                <h6 style="color: #2c3136">Transaksi terbaru</h6>
                <p>Lima nota terakhir di loket ini</p>
              </div>
              <a href="#" class="link-semua">
                Semua riwayat <i class="bi bi-chevron-right"></i>
              </a>
            </div>
            <div class="table-responsive">
              <table
                id="transactionTable"
                class="table table-hover"
                style="width: 100%">
                <thead>
                  <tr style="background: #1a1d20">
                    <th>WAKTU</th>
                    <th>NOTA</th>
                    <th>PENGUNJUNG</th>
                    <th>JENIS</th>
                    <th>BAYAR</th>
                    <th>TOTAL</th>
                    <th>STATUS TIKET</th>
                    <th>AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>08:41</td>
                    <td>INV-20261006-DBBP3U</td>
                    <td>
                      <div class="fw-semibold">Lukman Hakim</div>
                      <div class="visitor-meta">
                        6 pengunjung &middot; Denpasar
                      </div>
                    </td>
                    <td>Rombongan</td>
                    <td>Tunai</td>
                    <td>Rp 494.000</td>
                    <td><span class="badge-status-used"> Used </span></td>
                    <td>
                      <i class="bi bi-printer action-icon"></i>
                      <i class="bi bi-three-dots-vertical action-icon"></i>
                    </td>
                  </tr>
                  <tr>
                    <td>08:35</td>
                    <td>INV-20261006-A1B2C3</td>
                    <td>
                      <div class="fw-semibold">Sarah Whitfield</div>
                      <div class="visitor-meta">
                        2 pengunjung &middot; Australia
                      </div>
                    </td>
                    <td>Individual</td>
                    <td>Debit</td>
                    <td>Rp 100.000</td>
                    <td><span class="badge-status-used"> Used </span></td>
                    <td>
                      <i class="bi bi-printer action-icon"></i>
                      <i class="bi bi-three-dots-vertical action-icon"></i>
                    </td>
                  </tr>
                  <tr>
                    <td>08:28</td>
                    <td>INV-20261006-X9Y8Z7</td>
                    <td>
                      <div class="fw-semibold">Budi Santoso</div>
                      <div class="visitor-meta">
                        4 pengunjung &middot; Surabaya
                      </div>
                    </td>
                    <td>Individual</td>
                    <td>Tunai</td>
                    <td>Rp 200.000</td>
                    <td><span class="badge-status-unused"> Active </span></td>
                    <td>
                      <i class="bi bi-printer action-icon"></i>
                      <i class="bi bi-three-dots-vertical action-icon"></i>
                    </td>
                  </tr>
                  <tr>
                    <td>08:15</td>
                    <td>INV-20261006-QWERTY</td>
                    <td>
                      <div class="fw-semibold">Chen Wei</div>
                      <div class="visitor-meta">
                        1 pengunjung &middot; China
                      </div>
                    </td>
                    <td>Individual</td>
                    <td>Debit</td>
                    <td>Rp 50.000</td>
                    <td><span class="badge-status-used"> Used </span></td>
                    <td>
                      <i class="bi bi-printer action-icon"></i>
                      <i class="bi bi-three-dots-vertical action-icon"></i>
                    </td>
                  </tr>
                  <tr>
                    <td>08:02</td>
                    <td>INV-20261006-POIUYT</td>
                    <td>
                      <div class="fw-semibold">Dewi Lestari</div>
                      <div class="visitor-meta">
                        3 pengunjung &middot; Bandung
                      </div>
                    </td>
                    <td>Rombongan</td>
                    <td>Tunai</td>
                    <td>Rp 150.000</td>
                    <td><span class="badge-status-used"> Used </span></td>
                    <td>
                      <i class="bi bi-printer action-icon"></i>
                      <i class="bi bi-three-dots-vertical action-icon"></i>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <!-- ========================================================= JAVASCRIPT ========================================================== -->
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/v/dt/dt-3.0.2/datatables.min.js"></script>
  <script src="https://cdn.datatables.net/3.0.2/js/dataTables.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/4.0.2/js/dataTables.buttons.min.js"></script>

  <script src="https://cdn.datatables.net/buttons/4.0.2/js/buttons.dataTables.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.3.11/build/pdfmake.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/pdfmake@0.3.11/build/vfs_fonts.js"></script>
  <!-- AdminLTE -->
  <script src="public/js/adminlte.min.js"></script>

</body>
<?= $this->include("public/js/main.js") ?>

</html>