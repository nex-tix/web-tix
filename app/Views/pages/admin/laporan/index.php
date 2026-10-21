<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title">
        <h4 style="color:#2c3136">Laporan &amp; Ekspor</h4>
        <p style="color:#2c3136">Ringkasan rekapitulasi penjualan, diskon, demografi, dan audit gate per hari dagang berjalan.</p>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <input type="date" id="datePicker" class="form-control form-control-sm" style="position:absolute;opacity:0;width:1px;height:1px;pointer-events:none" value="<?= esc($reportDate) ?>">
        <button type="button" id="dateBtn" class="btn-laporan"><i class="bi bi-calendar3"></i> <span id="dateLabel">Pilih Tanggal</span></button>
        <?php $exportQuery = http_build_query(array_filter([
          'date'       => $reportDate,
          'checkpoint' => $filters['checkpoint_id'] ?? null,
          'payment'    => $filters['payment_method'] ?? null,
          'category'   => $filters['ticket_category'] ?? null,
          'q'          => $filters['search'] ?? null,
        ])); ?>
        <a href="<?= base_url('admin/laporan/export-pdf?' . esc($exportQuery)) ?>" class="btn-transaksi"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Penjualan hari ini</h2>
          </div>
          <div class="stat-value">
            <h4>Rp 9.897.000,00</h4>
          </div>
          <div class="stat-sub">
            <h4>+23% dari penjualan kemarin (Rp 12.234.000,00)</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Rekap diskon hari ini</h2>
          </div>
          <div class="stat-value">
            <h4>Rp 290.000,00</h4>
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
            <h4>22</h4>
          </div>
          <div class="stat-sub">
            <h4>22 dari 44 tiket terjual</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Tiket belum terpakai</h2>
          </div>
          <div class="stat-value">
            <h4>22</h4>
          </div>
          <div class="stat-sub">
            <h4>Valid hingga tengah malam <br> (24:00 WITA)</h4>
          </div>
        </div>
      </div>
    </div>

    <section id="chart" class="pt-3">
      <!-- Chart -->
      <!--begin::Card-->
      <div class="chart-card container-fluid">
        <div class="card-header d-flex">
          <h3 class="sub-text">Penjualan 1 minggu, 1 bulan, 1 tahun</h3>

          <div style="margin-left: auto;" class="btn-chart d-flex gap-2">
            <button class="btn-chart-laporan">7 hari</button>
            <button class="btn-chart-laporan">1 bulan</button>
            <button class="btn-chart-laporan">1 tahun</button>
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
      <div class="row g-3">
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="chart-card">
            <div class="card-header">
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
          <div class="chart-card">
            <div class="card-header">
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
          <div class="chart-card">
            <div class="card-header">
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
    </section>

    <section id="table-laporan" class="pt-3">
      <div class="container-fluid p-0">
        <div class="table-card">
          <div class="app-content-header">
            <div class="page-title">
              <h4 style="color: #2c3136;" class="sub-text">Audit Scan Tiket</h4>
            </div>
          </div>
          <table id="dataTiketTable" class="table table-hover mb-0" style="width:100%">
            <thead>
              <tr>
                <th>Waktu Scan</th>
                <th>Checkpoint</th>
                <th>Kode Tiket</th>
                <th>Kategori</th>
                <th>Admin/kasir</th>
                <th>Status Scan</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody class="table-light">
              <tr>
                <td>10:38:02 WITA</td>
                <td>Gerbang Selatan</td>
                <td>GRP-HXH2-V262-HPXD-5U42</td>
                <td>Group</td>
                <td>Raya</td>
                <td>Diterima</td>
                <td>Valid, akses diberikan</td>
              </tr>
            </tbody>
          </table>
        </div>
    </section>
  </div>
</div>
</div>
<?= $this->endSection() ?>