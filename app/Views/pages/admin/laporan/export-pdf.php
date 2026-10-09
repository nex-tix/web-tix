<?php
$reportDate = $reportDate ?? date('Y-m-d');
$reportDateDisplay = date('d F Y', strtotime($reportDate));
$stats = $stats ?? [];
$payment = $payment ?? [];
$demography = $demography ?? [];
$chartData = $chartData ?? [];
$auditRows = $auditRows ?? [];
$filters = $filters ?? [];
$filterLabels = [];
if (!empty($filters['checkpoint_id'])) $filterLabels[] = 'Checkpoint #' . $filters['checkpoint_id'];
if (!empty($filters['payment_method'])) $filterLabels[] = ucfirst($filters['payment_method']);
if (!empty($filters['ticket_category'])) $filterLabels[] = ucfirst($filters['ticket_category']);
if (!empty($filters['search'])) $filterLabels[] = 'Tiket: ' . $filters['search'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan <?= esc($reportDateDisplay) ?></title>
<style>
@page { margin: 34px 32px 42px; }
* { box-sizing: border-box; }
body { margin: 0; color: #263238; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
.header { border-bottom: 3px solid #cf4647; padding-bottom: 14px; margin-bottom: 18px; }
.brand { color: #cf4647; font-size: 10px; font-weight: bold; letter-spacing: 1.4px; text-transform: uppercase; }
h1 { margin: 5px 0 3px; color: #1f2529; font-size: 22px; }
.subtitle { color: #68737b; font-size: 9px; }
.meta { margin-top: 12px; color: #4f5b62; font-size: 9px; }
.pill { background: #f1f3f4; border-radius: 10px; color: #4f5b62; display: inline-block; margin-left: 4px; padding: 4px 8px; }
.section-title { border-left: 4px solid #cf4647; color: #263238; font-size: 12px; font-weight: bold; margin: 18px 0 8px; padding-left: 7px; }
.grid { width: 100%; }
.grid td { padding: 0 5px 8px 0; vertical-align: top; width: 25%; }
.grid td:last-child { padding-right: 0; }
.stat-card { background: #f6f7f8; border: 1px solid #e1e5e7; border-radius: 5px; min-height: 62px; padding: 9px; }
.stat-label { color: #68737b; font-size: 8px; text-transform: uppercase; }
.stat-value { color: #1f2529; font-size: 13px; font-weight: bold; margin-top: 7px; }
.stat-sub { color: #68737b; font-size: 8px; margin-top: 3px; }
.two-col { width: 100%; }
.two-col td { padding-right: 12px; vertical-align: top; width: 50%; }
.two-col td:last-child { padding: 0; }
.panel { border: 1px solid #e1e5e7; border-radius: 5px; padding: 11px; }
.panel-title { color: #263238; font-size: 10px; font-weight: bold; margin-bottom: 9px; }
.bar-bg { background: #edf0f1; height: 12px; width: 100%; }
.bar-cash { background: #263238; height: 12px; }
.bar-debit { background: #cf4647; height: 12px; }
.legend { color: #4f5b62; font-size: 8px; line-height: 18px; }
.dot { display: inline-block; height: 7px; margin-right: 4px; width: 7px; }
.demography-row { border-bottom: 1px solid #edf0f1; padding: 5px 0; }
.demography-row:last-child { border-bottom: 0; }
.origin { color: #4f5b62; }
.origin-value { float: right; font-weight: bold; }
table.audit { border-collapse: collapse; margin-top: 6px; width: 100%; }
table.audit th { background: #263238; color: #fff; font-size: 7px; padding: 7px 5px; text-align: left; }
table.audit td { border-bottom: 1px solid #e1e5e7; color: #4f5b62; font-size: 7.5px; padding: 6px 5px; vertical-align: top; }
table.audit tr:nth-child(even) td { background: #fafafa; }
.note { color: #68737b; font-size: 8px; margin-top: 8px; }
.footer { border-top: 1px solid #e1e5e7; color: #8a949b; font-size: 7px; margin-top: 18px; padding-top: 7px; text-align: center; }
</style>
</head>
<body>
<div class="header">
  <div class="brand">Laporan Operasional</div>
  <h1>Laporan &amp; Ekspor</h1>
  <div class="subtitle">Ringkasan penjualan, pembayaran, demografi pengunjung, dan audit gate.</div>
  <div class="meta">Tanggal laporan: <strong><?= esc($reportDateDisplay) ?></strong><?php if ($filterLabels): ?><?php foreach ($filterLabels as $filterLabel): ?><span class="pill"><?= esc($filterLabel) ?></span><?php endforeach; ?><?php endif; ?></div>
</div>

<div class="section-title">Ringkasan Statistik</div>
<table class="grid"><tr>
<?php foreach (['sales' => 'Penjualan', 'discount' => 'Diskon', 'attendance' => 'Pengunjung Masuk', 'remaining' => 'Belum Dipakai'] as $key => $label): ?>
<td><div class="stat-card"><div class="stat-label"><?= esc($label) ?></div><div class="stat-value"><?= $stats[$key]['value'] ?? ($key === 'attendance' || $key === 'remaining' ? '0' : 'Rp 0') ?></div><div class="stat-sub"><?= $stats[$key]['sub'] ?? '' ?></div></div></td>
<?php endforeach; ?>
</tr></table>

<div class="section-title">Pembayaran &amp; Demografi</div>
<table class="two-col"><tr>
<td><div class="panel"><div class="panel-title">Komposisi Metode Pembayaran</div><div class="bar-bg"><div class="bar-cash" style="width:<?= esc($payment['cash_pct'] ?? 0) ?>%;"></div></div><div class="legend"><span class="dot" style="background:#263238;"></span><?= esc($payment['cash_text'] ?? 'Tunai Rp 0') ?><br><span class="dot" style="background:#cf4647;"></span><?= esc($payment['debit_text'] ?? 'Debit / EDC Rp 0') ?><br><strong>Total: <?= esc($payment['total'] ?? 'Rp 0') ?></strong></div></div></td>
<td><div class="panel"><div class="panel-title">Demografi Pengunjung</div><div class="demography-row"><span class="origin">Domestik</span><span class="origin-value"><?= esc($demography['domestic_percent'] ?? '0%') ?> (<?= esc($demography['domestic_count'] ?? '0 orang') ?>)</span></div><div class="demography-row"><span class="origin">Mancanegara</span><span class="origin-value"><?= esc($demography['mancanegara_percent'] ?? '0%') ?> (<?= esc($demography['mancanegara_count'] ?? '0 orang') ?>)</span></div><div class="demography-row"><span class="origin">Individual</span><span class="origin-value"><?= esc($demography['individual_label'] ?? '0 orang') ?></span></div><div class="demography-row"><span class="origin">Rombongan</span><span class="origin-value"><?= esc($demography['group_label'] ?? '0 nota') ?></span></div></div></td>
</tr></table>

<div class="section-title">Penjualan 7 Hari Terakhir</div>
<div class="panel"><table class="audit"><tr><th>HARI</th><th>TOTAL PENJUALAN</th></tr><?php foreach (($chartData['labels'] ?? []) as $index => $label): ?><tr><td><?= esc($label) ?></td><td>Rp <?= number_format((float)($chartData['data'][$index] ?? 0), 0, ',', '.') ?></td></tr><?php endforeach; ?><?php if (empty($chartData['labels'])): ?><tr><td colspan="2">Tidak ada data penjualan.</td></tr><?php endif; ?></table></div>

<div class="section-title">Audit Log Scan Tiket &amp; Kehadiran Per Gate</div>
<table class="audit"><tr><th>WAKTU SCAN</th><th>CHECKPOINT / GATE</th><th>KODE TIKET</th><th>KATEGORI</th><th>ADMIN / KASIR</th><th>STATUS</th><th>KETERANGAN</th></tr><?php if ($auditRows): ?><?php foreach ($auditRows as $row): ?><tr><?php foreach ($row as $cell): ?><td><?= $cell ?></td><?php endforeach; ?></tr><?php endforeach; ?><?php else: ?><tr><td colspan="7">Tidak ada data audit scan untuk filter yang dipilih.</td></tr><?php endif; ?></table>
<div class="footer">Dokumen dibuat otomatis oleh sistem pada <?= date('d/m/Y H:i') ?> WITA</div>
</body>
</html>