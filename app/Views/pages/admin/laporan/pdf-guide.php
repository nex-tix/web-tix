<?php
$records        = $records ?? [];
$selectedGuide  = $selectedGuide ?? null;
$startDate      = $startDate ?? date('Y-m-01');
$endDate        = $endDate ?? date('Y-m-d');
$totalTrips     = $totalTrips ?? 0;
$totalWisatawan = $totalWisatawan ?? 0;
$totalKomisi    = $totalKomisi ?? 0;

$rangeLabel = date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Komisi Guide <?= esc($rangeLabel) ?></title>
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
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f1f1f;
            margin: 0 0 5px 0;
        }

        .header-subtitle {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin: 0 0 6px 0;
        }

        .header-period {
            font-size: 11px;
            color: #555;
            margin: 0;
        }

        .header-date {
            font-size: 9px;
            color: #888;
            margin-top: 3px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        table.report-table thead {
            display: table-header-group;
        }

        table.report-table th {
            color: #000;
            background: #f0f0f0;
            padding: 7px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #1a1f26;
            word-wrap: break-word;
        }

        table.report-table td {
            padding: 6px 5px;
            border: 1px solid #333;
            font-size: 9px;
            color: #333;
        }

        table.report-table tr.total-row td {
            font-weight: bold;
            background: #f8f9fa;
            border-top: 2px solid #333;
            padding: 8px 5px;
        }

        .summary-box {
            margin-top: 15px;
            border-top: 1px solid #333;
            padding-top: 10px;
            display: table;
            width: 100%;
        }

        .summary-col {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
            background: #fafafa;
        }

        .summary-val {
            font-size: 12px;
            font-weight: bold;
            color: #111;
            margin-top: 2px;
        }

        .summary-lbl {
            font-size: 8.5px;
            color: #666;
            text-transform: uppercase;
        }

        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 9.5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title">NEXTIX LOKET &amp; GATE SYSTEM</div>
        <div class="header-subtitle">
            LAPORAN PEMBAYARAN KOMISI PEMANDU WISATA (GUIDE)
            <?php if (!empty($selectedGuide)): ?>
                <br><span style="font-size: 11px; font-weight: normal;">Pemandu: <?= esc($selectedGuide['code'] . ' - ' . $selectedGuide['name']) ?></span>
            <?php endif; ?>
        </div>
        <div class="header-period">Periode: <?= esc($rangeLabel) ?></div>
        <div class="header-date">Dicetak pada: <?= date('d/m/Y H:i') ?> WITA</div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 11%">Tanggal</th>
                <th style="width: 12%">Kode Guide</th>
                <th style="width: 18%">Nama Guide</th>
                <th style="width: 14%">No. Tiket</th>
                <th style="width: 18%">Rombongan / Tamu</th>
                <th style="width: 9%">Wisatawan</th>
                <th style="width: 14%">Komisi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($records)): ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; color: #777;">Tidak ada catatan trip guide pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($records as $idx => $r): ?>
                    <tr>
                        <td style="text-align: center;"><?= $idx + 1 ?></td>
                        <td style="text-align: center;"><?= date('d/m/Y', strtotime($r['valid_date'])) ?></td>
                        <td style="text-align: center; font-family: monospace; font-weight: bold;"><?= esc($r['guide_code'] ?? '-') ?></td>
                        <td><?= esc($r['guide_name'] ?? $r['guide_name_snapshot'] ?? '-') ?></td>
                        <td style="text-align: center; font-family: monospace; font-size: 8.5px;"><?= esc($r['ticket_code']) ?></td>
                        <td><?= esc($r['customer_name'] ?? 'Rombongan') ?></td>
                        <td style="text-align: center; font-weight: bold;"><?= (int)($r['total_visitors'] ?? 0) ?> org</td>
                        <td style="text-align: right; font-weight: bold;">Rp <?= number_format((float)($r['guide_fee_amount'] ?? 0), 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right; padding-right: 10px;">TOTAL:</td>
                    <td style="text-align: center;"><?= number_format($totalWisatawan, 0, ',', '.') ?> org</td>
                    <td style="text-align: right;">Rp <?= number_format($totalKomisi, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-col">
            <div class="summary-lbl">Total Rombongan / Trip</div>
            <div class="summary-val"><?= number_format($totalTrips, 0, ',', '.') ?> trip</div>
        </div>
        <div class="summary-col">
            <div class="summary-lbl">Total Wisatawan Didampingi</div>
            <div class="summary-val"><?= number_format($totalWisatawan, 0, ',', '.') ?> orang</div>
        </div>
        <div class="summary-col">
            <div class="summary-lbl">Total Komisi Guide</div>
            <div class="summary-val" style="color: #198754;">Rp <?= number_format($totalKomisi, 0, ',', '.') ?></div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Petugas Kasir / Loket,</p>
            <br><br><br>
            <p>( .................................................... )</p>
        </div>
        <div class="signature-box">
            <p>Bagian Keuangan / Pimpinan,</p>
            <br><br><br>
            <p>( .................................................... )</p>
        </div>
    </div>

</body>
</html>
