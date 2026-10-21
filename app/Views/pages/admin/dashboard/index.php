  <?= $this->extend('layouts/main') ?>
  <?= $this->section('content') ?>
  <div class="app-content">
    <div class="container-fluid px-0">
      <!-- ========================================= STAT CARDS ========================================== -->
      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <a href="<?= base_url('admin/laporan') ?>">
            <?= view('components/stat-card', [
              'label'     => $report['stats']['sales']['label'],
              'value'     => $report['stats']['sales']['value'],
              'sub'       => $report['stats']['sales']['sub'],
              'sub_class' => $report['stats']['sales']['sub_class'],
            ]) ?>
          </a>
        </div>
        <div class="col-md-3">
          <a href="<?= base_url('admin/data-tiket') ?>">
            <?= view('components/stat-card', [
              'label' => 'Tiket Terbit',
              'value' => ((int)str_replace(' <small>orang</small>', '', $report['stats']['attendance']['value']) + (int)str_replace(' <small>tiket</small>', '', $report['stats']['remaining']['value'])) . ' <small>tiket</small>',
              'sub'   => 'individual & rombongan'
            ]) ?>
          </a>
        </div>
        <div class="col-md-3">
          <a href="<?= base_url('admin/laporan') ?>">
            <?= view('components/stat-card', [
              'label' => $report['stats']['attendance']['label'],
              'value' => $report['stats']['attendance']['value'],
              'sub'   => $report['stats']['attendance']['sub']
            ]) ?>
          </a>
        </div>
        <div class="col-md-3">
          <a href="<?= base_url('admin/data-tiket?status=aktif') ?>">
            <?= view('components/stat-card', [
              'label'     => $report['stats']['remaining']['label'],
              'value'     => $report['stats']['remaining']['value'],
              'sub'       => $report['stats']['remaining']['sub'],
              'dot_class' => $report['stats']['remaining']['dot_class']
            ]) ?>
          </a>
        </div>
      </div>
      <!-- ========================================= PAYMENT PROGRESS ========================================== -->
      <div class="card border-0 shadow-sm mb-3 bg-white">
        <div class="card-body p-3">
          <div class="custom-progress">
            <div class="bar-dark" style="width: <?= $report['paymentBreakdown']['cash_pct'] ?>%"></div>
            <div class="bar-red" style="width: <?= $report['paymentBreakdown']['debit_pct'] ?>%"></div>
          </div>
          <div class="progress-legend">
            <div class="d-flex gap-3 flex-wrap">
              <div class="legend-item">
                <span class="legend-color"></span>
                <?= $report['paymentBreakdown']['cash_text'] ?>
              </div>
              <div class="legend-item">
                <span class="legend-color" style="background-color: #e74c3c"></span>
                <?= $report['paymentBreakdown']['debit_text'] ?>
              </div>
            </div>
            <div class="fw-semibold"><?= $report['paymentBreakdown']['total'] ?></div>
          </div>
        </div>
      </div>
      <!-- ========================================= CHART + CASH ========================================== -->
      
      <div class="row g-3 mb-3">
        <!-- Chart -->
                <div class="col-lg-8 chart-card">
                  <div class="card-header">
                    <h3 class="card-title sub-text">Penjualan tujuh hari</h3> <br>
                    <p class="font-sm text-secondary"> Total dibayar per hari dagang (WITA), termasuk void</p>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div id="column-chart"></div>
                  </div>
                  <!-- /.card-body -->
                </div>

        <!-- Cash -->
        <div class="col-lg-4">
          <?= view('components/cash-recon', [
            'title'        => 'Cocokkan kas & EDC',
            'subtitle'     => 'Yang harus sama saat tutup loket',
            'cash_rate'    => $report['paymentBreakdown']['cash_pct'],
            'cash_amount'  => 'Rp' . number_format($report['paymentBreakdown']['cash']['total'], 0, ',', '.'),
            'edc_rate'     => $report['paymentBreakdown']['debit_pct'],
            'edc_amount'   => 'Rp' . number_format($report['paymentBreakdown']['debit']['total'], 0, ',', '.'),
            'total_notes'  => ($report['paymentBreakdown']['cash']['count'] + $report['paymentBreakdown']['debit']['count']) . ' nota',
            'discount'     => $report['stats']['discount']['value'],
            'info_text'    => 'Angka tunai dicocokkan dengan isi laci, angka EDC dengan slip mesin. Void bukan refund.'
          ]) ?>
        </div>
      </div>

      <script>
        window.dashboardChartData = {
          labels: <?= json_encode($report['chartData']['labels']) ?>,
          data: <?= json_encode($report['chartData']['data']) ?>
        };
      </script>
      <!-- ========================================= TABLE ========================================== -->
      <?= view('components/transaction-table', [
        'title'    => 'Transaksi terbaru',
        'subtitle' => 'Tujuh nota terakhir di loket ini',
        'link_url' => base_url('admin/transaction-history'),
        'link_text' => 'Semua riwayat',
        'table_id' => 'transactionTable',
        'headers'  => ['WAKTU', 'NOTA', 'PENGUNJUNG', 'JENIS', 'BAYAR', 'TOTAL', 'STATUS TIKET', ''],
        'rows'     => $report['transactions']['rows'] ?? []
      ]) ?>

      <div class="text-black small mt-2">
        Total <?= esc($report['transactions']['total'] ?? 0) ?> transaksi
      </div>
    </div>
  </div>
<script>
    window.dashboardReport = <?= json_encode(
        $report,
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_QUOT |
        JSON_HEX_AMP
    ) ?>;
</script>
  <?= $this->endSection() ?>