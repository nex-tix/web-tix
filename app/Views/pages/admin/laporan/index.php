<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title">
        <h4 style="color:#2c3136">Laporan &amp; Ekspor</h4>
        <p style="color:#2c3136">Ringkasan rekapitulasi penjualan, diskon, demografi, dan audit gate per hari dagang berjalan.</p>
      </div>
    </div>

    <div class="filter-export card border-0 shadow-sm bg-white mb-3">
      <div class="card-body p-3">
        <div class="d-flex align-items-end gap-3" style="flex-wrap:nowrap;">
          <div>
            <label class="form-label filter-label">Tanggal Awal</label>
            <input type="date" id="filter-tanggal-awal" class="form-control" style="min-width: 170px;">
          </div>
          <div>
            <label class="form-label filter-label">Tanggal Akhir</label>
            <input type="date" id="filter-tanggal-akhir" class="form-control" style="min-width: 170px;">
          </div>
          <div>
            <a id="btn-export-pdf" class="btn btn-transaksi" href="#">
              <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
          </div>
        </div>
      </div>
    </div>



    <div class="row g-3">
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Penjualan hari ini</h2>
          </div>
          <div class="stat-value">
            <h4>Rp <?= esc(number_format($stat_penjualan['total_amount'], 0, ',', '.')) ?></h4>
          </div>
          <div class="stat-sub">
            <?php $cmpStatus = $stat_penjualan_comparison['status'] ?? 'neutral'; ?>
            <h4 style="<?= $cmpStatus === 'up' ? 'color:#198754' : ($cmpStatus === 'down' ? 'color:#dc3545' : '') ?>">
              <?= esc($stat_penjualan_comparison['label'] ?? 'Belum ada transaksi hari ini') ?>
            </h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Rekap diskon hari ini</h2>
          </div>
          <div class="stat-value">
            <h4>Rp <?= esc(number_format($stat_diskon['discount_amount'], 0, ',', '.')) ?></h4>
          </div>
          <div class="stat-sub">
            <h4>Total diskon dari transaksi hari ini</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Pengunjung masuk</h2>
          </div>
          <div class="stat-value">
            <h4><?= esc($stat_pengunjung) ?></h4>
          </div>
          <div class="stat-sub">
            <h4><?= esc($stat_pengunjung_sub ?? 'Belum ada tiket terjual hari ini') ?></h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Tiket belum terpakai</h2>
          </div>
          <div class="stat-value">
            <h4><?= esc($stat_tiket) ?></h4>
          </div>
          <div class="stat-sub">
            <h4>Berlaku sampai tengah malam <br> (24:00 WITA)</h4>
          </div>
        </div>
      </div>
    </div>

    <section id="chart" class="pt-3">
      <!-- Chart -->
      <!--begin::Card-->
      <script>
        const serverChartData = <?= json_encode($chart_data) ?>;
      </script>

      <div class="chart-card container-fluid">
        <div class="card-header d-flex">
          <h3 class="sub-text">Rekap penjualan</h3>

          <div style="margin-left: auto;" class="btn-chart d-flex gap-2">
            <button class="btn-chart-laporan" data-range="7days">7 hari</button>
            <select id="select-bulan" class="input-chart-laporan">
              <option value="">Pilih bulan</option>
              <option value="1">Januari</option>
              <option value="2">Februari</option>
              <option value="3">Maret</option>
              <option value="4">April</option>
              <option value="5">Mei</option>
              <option value="6">Juni</option>
              <option value="7">Juli</option>
              <option value="8">Agustus</option>
              <option value="9">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
            <button class="btn-chart-laporan" data-range="year">1 tahun</button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div id="column-chart-report"></div>
        </div>
        <!-- /.card-body -->
      </div>
      <!--end::Card-->
    </section>

    <section id="dough-chart" class="pt-3">
      <script>
        const serverDonutChartData = <?= json_encode($donut_chart_data) ?>;
      </script>
      <div class="chart-card-header container-fluid">
        <div class="card-header d-flex">
          <h3 class="sub-text">Analisa Penjualan Bulanan</h3>

          <div style="margin-left: auto;" class="btn-chart d-flex gap-2">
            <select id="select-donut-bulan" class="input-chart-laporan select-donut-bulan">
              <option value="1">Januari</option>
              <option value="2">Februari</option>
              <option value="3">Maret</option>
              <option value="4">April</option>
              <option value="5">Mei</option>
              <option value="6">Juni</option>
              <option value="7">Juli</option>
              <option value="8">Agustus</option>
              <option value="9">September</option>
              <option value="10">Oktober</option>
              <option value="11">November</option>
              <option value="12">Desember</option>
            </select>
          </div>
        </div>
        <!-- /.card-header -->

        <div class="row g-3 mt-1">
          <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="chart-card shadow-sm">
              <div class="card-header d-flex flex-wrap align-items-center">
                <h3 class="sub-text">Metode Bayar</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="donut-chart-1"></div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="chart-card shadow-sm">
              <div class="card-header d-flex flex-wrap align-items-center">
                <h3 class="sub-text">Asal pengunjung</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="donut-chart-2"></div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="chart-card shadow-sm">
              <div class="card-header d-flex flex-wrap align-items-center">
                <h3 class="sub-text">Jenis Tiket</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="donut-chart-3"></div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
      </div>

    </section>

    <section id="table-laporan" class="pt-3">
      <div class="container-fluid p-0">
        <div class="table-card">
          <div class="app-content-header">
            <div class="page-title">
              <h4 style="color: #2c3136;" class="sub-text">Audit Scan Tiket</h4>
            </div>
          </div>
          <table id="datatable" class="table table-hover datatable mb-0">
            <thead>
              <tr>
                <th>Waktu Scan</th>
                <th>Checkpoint</th>
                <th>Kode Tiket</th>
                <th>Kategori</th>
                <th>Admin/kasir</th>
                <th>Status Scan</th>
              </tr>
            </thead>
            <tbody class="table-light">
              <?php foreach ($checkin_histories as $ch) : ?>
                <tr>
                  <td><?= esc(date('d-m-Y', strtotime($ch['scanned_at']))) ?> <br> <?= esc(date('H:i', strtotime($ch['scanned_at']))) ?> WITA</td>
                  <td><?= esc($ch['checkpoint_name']) ?></td>
                  <td><?= esc($ch['ticket_code']) ?></td>
                  <td><?= esc($ch['ticket_category']) ?></td>
                  <td><?= esc($ch['gate_username']) ?></td>
                  <td>
                    <?php if ($ch['result'] == 'VALID') : ?>
                      Diterima
                    <?Php elseif ($ch['result'] == 'ALREADY_USED') : ?>
                      Tiket telah terpakai
                    <?Php elseif ($ch['result'] == 'EXPIRED') : ?>
                      Tiket telah kadaluarwa
                    <?Php elseif ($ch['result'] == 'NOT_YET_VALID') : ?>
                      Tiket belum bisa digunakan
                    <?Php elseif ($ch['result'] == 'CANCELLED') : ?>
                      Tiket telah dibatalkan
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach ?>

            </tbody>
          </table>
        </div>
    </section>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const startInput = document.getElementById('filter-tanggal-awal');
        const endInput = document.getElementById('filter-tanggal-akhir');
        const exportBtn = document.getElementById('btn-export-pdf');
        let alertEl = null;

        function formatDateRegional(dateStr) {
          if (!dateStr) return '';
          const parts = dateStr.split('-');
          return parts[2] + '-' + parts[1] + '-' + parts[0];
        }

        function hideAlert() {
          if (alertEl) {
            alertEl.remove();
            alertEl = null;
          }
        }

        function showAlert(message) {
          hideAlert();
          alertEl = document.createElement('div');
          alertEl.className = 'alert alert-warning alert-dismissible fade show mt-3';
          alertEl.setAttribute('role', 'alert');
          alertEl.innerHTML = message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>';
          document.querySelector('.filter-export').insertAdjacentElement('afterend', alertEl);
          setTimeout(hideAlert, 3000);
        }

        function updateExportUrl() {
          hideAlert();

          if (!startInput.value || !endInput.value) {
            exportBtn.href = '#';
            return;
          }

          exportBtn.href = '<?= base_url('admin/laporan/export-pdf') ?>?start=' +
            formatDateRegional(startInput.value) + '&end=' + formatDateRegional(endInput.value);
        }

        startInput.addEventListener('change', updateExportUrl);
        endInput.addEventListener('change', updateExportUrl);

        exportBtn.addEventListener('click', function (e) {
          if (!startInput.value || !endInput.value) {
            e.preventDefault();
            showAlert('Silahkan tentukan tanggal dahulu');
            return;
          }
          if (endInput.value < startInput.value) {
            e.preventDefault();
            showAlert('Tanggal akhir tidak boleh lebih kecil dari tanggal awal');
          }
        });

        updateExportUrl();
      });
    </script>
  </div>
</div>
</div>
<?= $this->endSection() ?>