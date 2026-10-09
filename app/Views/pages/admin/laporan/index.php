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
        <button type="button" class="btn-transaksi"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <?= view('components/stat-card', $stats['sales']) ?>
      </div>
      <div class="col-md-3">
        <?= view('components/stat-card', $stats['discount']) ?>
      </div>
      <div class="col-md-3">
        <?= view('components/stat-card', $stats['attendance']) ?>
      </div>
      <div class="col-md-3">
        <?= view('components/stat-card', $stats['remaining']) ?>
      </div>
    </div>

    <?= view('components/laporan/filter-tabs', [
        'tabs'        => ['Audit Scan Gate & Kehadiran','Demografi Pengunjung'],
        'active_tab'  => 'Audit Scan Gate & Kehadiran',
        'filters'     => $filters ?? [],
        'checkpoints' => $checkpoints ?? [],
        'reportDate'  => $reportDate ?? date('Y-m-d'),
    ]) ?>

    <div class="row g-3 mb-3">
      <div class="col-lg-8">
        <?= view('components/chart-box', [
            'title'        => 'Penjualan per Hari & Metode Pembayaran',
            'subtitle'     => 'Total dibayar per hari dagang (WITA), termasuk void',
            'badge_text'   => '7 Hari Terakhir',
            'chart_id'     => 'laporanSalesChart',
            'height'       => 250,
            'footer_stats' => [
                ['label' => 'Rata-rata 7 hari', 'value' => $chartData['avg']],
                ['label' => 'Tertinggi', 'value' => $chartData['max']],
            ],
            'extra_attrs'  => 'data-chart=\'' . htmlspecialchars(json_encode($chartData), ENT_QUOTES, 'UTF-8') . '\'',
        ]) ?>
      </div>
      <div class="col-lg-4">
        <?= view('components/laporan/demography-card', $demography) ?>
      </div>
    </div>

    <div class="card border-0 shadow-sm bg-white mb-3">
      <div class="card-body p-3">
        <div class="custom-progress">
          <div class="bar-dark" style="width:<?php echo $payment['cash_pct'] ?? 0; ?>%"></div>
          <div class="bar-red" style="width:<?php echo $payment['debit_pct'] ?? 0; ?>%"></div>
        </div>
        <div class="progress-legend">
          <div class="d-flex gap-3 flex-wrap">
            <div class="legend-item"><span class="legend-color"></span> <?php echo $payment['cash_text'] ?? 'Tunai Rp 0'; ?></div>
            <div class="legend-item"><span class="legend-color" style="background-color:#e74c3c"></span> <?php echo $payment['debit_text'] ?? 'Debit / EDC Rp 0'; ?></div>
          </div>
          <div class="fw-semibold">Total: <?php echo $payment['total'] ?? 'Rp 0'; ?></div>
        </div>
      </div>
    </div>

    <?= view('components/laporan/audit-table', [
        'title'     => 'Audit Log Scan Tiket & Kehadiran Per Gate',
        'subtitle'  => 'Catatan real-time verifikasi barcode tiket di pintu masuk teater',
        'headers'   => ['WAKTU SCAN','CHECKPOINT / GATE','KODE TIKET','KATEGORI','ADMIN / KASIR','STATUS SCAN','KETERANGAN'],
        'rows'      => $auditRows,
        'table_id'  => 'laporanAuditTable',
    ]) ?>

  </div>
</div>

<script src="<?= base_url('js/laporan-chart.js') ?>"></script>
<script src="<?= base_url('js/laporan-audit-table.js') ?>"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const picker = document.getElementById('datePicker');
    const btn = document.getElementById('dateBtn');
    const label = document.getElementById('dateLabel');
    if (!picker || !btn || !label) return;

    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const today = new Date();
    const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

    function formatDate(val) {
        const parts = val.split('-');
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        const text = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
        return text + (val === todayStr ? ' (Hari Ini)' : '');
    }

    label.textContent = formatDate(picker.value);

    btn.addEventListener('click', function() {
        if (typeof picker.showPicker === 'function') {
            try {
                picker.showPicker();
                return;
            } catch (e) {}
        }
        picker.style.cssText = 'position:static;opacity:1;width:auto;height:auto;pointer-events:auto';
        picker.focus();
        picker.click();
    });

    picker.addEventListener('blur', function() {
        picker.style.cssText = 'position:absolute;opacity:0;width:1px;height:1px;pointer-events:none';
    });

    picker.addEventListener('change', function() {
        if (!picker.value) return;
        const params = new URLSearchParams(window.location.search);
        params.set('date', picker.value);
        window.location.search = params.toString();
    });
});
</script>
<?= $this->endSection() ?>
