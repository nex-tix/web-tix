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
            <label class="form-label filter-label">Periode Bulan</label>
            <div class="dropdown">
              <button class="btn btn-filter dropdown-toggle" id="btn-bulan-label" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <span id="bulan-label-text">Pilih bulan</span>
              </button>
              <div class="dropdown-menu filter-dropdown-menu p-4" id="filter-bulan-menu">
                <?php
                $bulanList = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                foreach ($bulanList as $no => $nama): ?>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="bulan-<?= $no ?>" value="<?= $no ?>">
                    <label class="form-check-label" for="bulan-<?= $no ?>"><?= $nama ?></label>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <div>
            <label class="form-label filter-label">Tahun</label>
            <select id="filter-tahun" class="form-select">
              <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                <option value="<?= $y ?>" <?= $y === (int)date('Y') ? 'selected' : '' ?>><?= $y ?></option>
              <?php endfor; ?>
            </select>
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
        const monthBoxes = Array.from(document.querySelectorAll('#filter-bulan-menu .form-check-input'));
        const tahunSelect = document.getElementById('filter-tahun');
        const exportBtn = document.getElementById('btn-export-pdf');
        const labelEl = document.getElementById('bulan-label-text');
        const shortNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const fullNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        monthBoxes.forEach(function (cb) {
          if (parseInt(cb.value) === <?= (int)date('n') ?>) cb.checked = true;
        });

        function selectedMonths() {
          return monthBoxes
            .filter(function (cb) { return cb.checked; })
            .map(function (cb) { return parseInt(cb.value); })
            .sort(function (a, b) { return a - b; });
        }

        function updateExportUrl() {
          const selected = selectedMonths();

          if (selected.length === 0) {
            labelEl.textContent = 'Pilih bulan...';
            exportBtn.href = '#';
            exportBtn.classList.add('disabled');
            return;
          }

          exportBtn.classList.remove('disabled');

          const minS = shortNames[selected[0] - 1];
          const maxS = shortNames[selected[selected.length - 1] - 1];
          labelEl.textContent = selected.length === 1
            ? fullNames[selected[0] - 1]
            : minS + ' s/d ' + maxS;

          exportBtn.href = '<?= base_url('admin/laporan/export-pdf') ?>?bulan=' + selected.join(',') + '&tahun=' + tahunSelect.value;
        }

        monthBoxes.forEach(function (cb) { cb.addEventListener('change', updateExportUrl); });
        tahunSelect.addEventListener('change', updateExportUrl);
        updateExportUrl();
      });
    </script>
  </div>
</div>
</div>
<?= $this->endSection() ?>