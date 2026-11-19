<?php
$reportDate = $reportDate ?? date('Y-m-d');
$dailyData = $dailyData ?? [];
$filters = $filters ?? [];

$bulanNames = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

$filterBulan = $filters['bulan'] ?? [];
$filterTahun = $filters['tahun'] ?? date('Y');

$minBulan = !empty($filterBulan) ? min($filterBulan) : 1;
$maxBulan = !empty($filterBulan) ? max($filterBulan) : 12;
$rangeLabel = count($filterBulan) === 1
    ? $bulanNames[$minBulan] . ' ' . $filterTahun
    : $bulanNames[$minBulan] . ' s/d ' . $bulanNames[$maxBulan] . ' ' . $filterTahun;

$dailyRows = $dailyData['rows'] ?? [];
$totals = $dailyData['totals'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Tiket <?= esc($rangeLabel) ?></title>
    <style>
        @page {
            margin: 20px;
            @bottom-center {
                content: "Halaman " counter(page) " dari " counter(pages);
                font-size: 9px;
                color: #999;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #333;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #333;
            padding-bottom: 6px;
        }

        .header-title {
            font-size: 15px;
            font-weight: bold;
            color: #1f1f1f;
            margin: 0 0 4px 0;
        }

        .header-subtitle {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin: 0 0 6px 0;
        }

        .header-period {
            font-size: 10px;
            color: #666;
            margin: 0;
        }

        .header-date {
            font-size: 8.5px;
            color: #999;
            margin-top: 2px;
        }

        table.daily-report {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        table.daily-report thead {
            display: table-header-group;
        }

        table.daily-report th {
            color: #000000;
            padding: 4px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8.5px;
            border: 1px solid #1a1f26;
            word-wrap: break-word;
        }

        table.daily-report th:first-child {
            text-align: center;
        }

        table.daily-report th:nth-child(2) {
            text-align: center;
        }

        table.daily-report td {
            padding: 3.5px 4px;
            text-align: center;
            border: 1px solid #000000;
            font-size: 8.5px;
            color: #333;
        }

        table.daily-report td:first-child {
            text-align: center;
            width: 5%;
        }

        table.daily-report td:nth-child(2) {
            text-align: center;
            width: 10%;
        }

        table.daily-report tr.total-row {
            font-weight: bold;
            border-top: 2px solid #333;
        }

        table.daily-report tr.total-row td {
            border: 1px solid #333;
            padding: 4.5px 4px;
            font-size: 8.5px;
        }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">LAPORAN REKAPITULASI TIKET</div>
        <div class="header-subtitle">PERTUNJUKAN KECAK ULUWATU</div>
        <div class="header-period">Periode: <?= esc($rangeLabel) ?></div>
        <div class="header-date">Tanggal Laporan: <?= date('d/m/Y H:i') ?> WITA</div>
    </div>

    <table class="daily-report">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Tiket Terbit</th>
                <th>Pengunjung</th>
                <th>Used</th>
                <th>Active</th>
                <th>Cancelled</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dailyRows as $row): ?>
                <tr>
                    <td><?= (int)$row['no'] ?></td>
                    <td><?= esc($row['tanggal_display']) ?></td>
                    <td><?= number_format((int)$row['tiket_terbit'], 0, ',', '.') ?></td>
                    <td><?= number_format((int)$row['pengunjung'], 0, ',', '.') ?></td>
                    <td><?= number_format((int)$row['used'], 0, ',', '.') ?></td>
                    <td><?= number_format((int)$row['active'], 0, ',', '.') ?></td>
                    <td><?= number_format((int)$row['cancelled'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format((float)$row['pendapatan'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="2">TOTAL PERIODE</td>
                <td><?= number_format((int)$totals['tiket_terbit'], 0, ',', '.') ?></td>
                <td><?= number_format((int)$totals['pengunjung'], 0, ',', '.') ?></td>
                <td><?= number_format((int)$totals['used'], 0, ',', '.') ?></td>
                <td><?= number_format((int)$totals['active'], 0, ',', '.') ?></td>
                <td><?= number_format((int)$totals['cancelled'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format((float)$totals['pendapatan'], 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem pada <?= date('d/m/Y H:i:s') ?> WITA
    </div>
</body>
</html>