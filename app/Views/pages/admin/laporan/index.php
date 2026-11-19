<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Laporan &amp; Ekspor</h4>
        <p class="text-slate-500">Ringkasan rekapitulasi penjualan, diskon, demografi, dan audit gate per hari dagang berjalan.</p>
      </div>
    </div>

    <div class="filter-export table-card mb-3 p-3">
      <div class="row g-2 align-items-end">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <label class="form-label filter-label">Tanggal Awal</label>
          <input type="date" id="filter-tanggal-awal" class="form-control form-control-sm" value="<?= date('Y-m-01') ?>">
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <label class="form-label filter-label">Tanggal Akhir</label>
          <input type="date" id="filter-tanggal-akhir" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-auto">
          <a id="btn-export-pdf" class="btn btn-sm btn-transaksi w-100 justify-content-center" href="<?= base_url('admin/laporan/export-pdf?start=' . date('d-m-Y', strtotime('first day of this month')) . '&end=' . date('d-m-Y')) ?>">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
          </a>
        </div>
      </div>
    </div>



    <div class="row g-3">
      <div class="col-12 col-sm-6 col-lg-3">
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
      <div class="col-12 col-sm-6 col-lg-3">
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
      <div class="col-12 col-sm-6 col-lg-3">
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
      <div class="col-12 col-sm-6 col-lg-3">
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
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 bg-white">
          <h3 class="sub-text mb-0">Rekap penjualan</h3>

          <div class="btn-chart d-flex flex-wrap gap-2">
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
        <div class="card-header d-flex flex-wrap align-items-center gap-3 bg-white">
          <h3 class="sub-text mb-0">Analisa Penjualan Bulanan</h3>

          <div class="btn-chart d-flex flex-wrap gap-2">
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
          <div class="col-12 col-md-6 col-lg-4">
            <div class="chart-card shadow-sm">
              <div class="card-header d-flex flex-wrap align-items-center bg-white">
                <h3 class="sub-text">Metode Bayar</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="donut-chart-1"></div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-12 col-md-6 col-lg-4">
            <div class="chart-card shadow-sm">
              <div class="card-header d-flex flex-wrap align-items-center bg-white">
                <h3 class="sub-text">Asal pengunjung</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div id="donut-chart-2"></div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>

          <div class="col-12 col-md-12 col-lg-4">
            <div class="chart-card shadow-sm">
              <div class="card-header d-flex flex-wrap align-items-center bg-white">
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
              <h4 class="sub-text">Audit Scan Tiket</h4>
            </div>
          </div>
          <div class="table-responsive">
            <table id="datatable" class="table table-hover datatable mb-0" style="min-width: 750px;">
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
              <tbody>
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
                      <?php elseif ($ch['result'] == 'ALREADY_USED') : ?>
                        Tiket telah terpakai
                      <?php elseif ($ch['result'] == 'EXPIRED') : ?>
                        Tiket telah kadaluarsa
                      <?php elseif ($ch['result'] == 'NOT_YET_VALID') : ?>
                        Tiket belum bisa digunakan
                      <?php elseif ($ch['result'] == 'CANCELLED') : ?>
                        Tiket telah dibatalkan
                      <?php else : ?>
                        <?= esc($ch['result']) ?>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
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

          const startVal = startInput.value ? formatDateRegional(startInput.value) : '<?= date('d-m-Y', strtotime('first day of this month')) ?>';
          const endVal = endInput.value ? formatDateRegional(endInput.value) : '<?= date('d-m-Y') ?>';

          exportBtn.href = '<?= base_url('admin/laporan/export-pdf') ?>?start=' + startVal + '&end=' + endVal;
        }

        startInput.addEventListener('change', updateExportUrl);
        endInput.addEventListener('change', updateExportUrl);

        exportBtn.addEventListener('click', function(e) {
          if (startInput.value && endInput.value && endInput.value < startInput.value) {
            e.preventDefault();
            showAlert('Tanggal akhir tidak boleh lebih kecil dari tanggal awal');
          }
        });

        updateExportUrl();
      });
    </script>
  </div>
</div>
<?= $this->endSection() ?>