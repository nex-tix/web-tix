<?php
$tickets          = $tickets ?? [];
$originType       = $originType ?? '';
$startDate        = $startDate ?? date('Y-m-01');
$endDate          = $endDate ?? date('Y-m-d');
$totalPengunjung  = $totalPengunjung ?? 0;
$totalDomestik    = $totalDomestik ?? 0;
$domestikPct      = $domestikPct ?? 0;
$totalMancanegara = $totalMancanegara ?? 0;
$mancanegaraPct   = $mancanegaraPct ?? 0;
$totalHadir       = $totalHadir ?? 0;
$hadirPct         = $hadirPct ?? 0;

$kategoriLabel = match ($originType) {
    'domestic'      => 'Domestik',
    'international' => 'Mancanegara',
    default         => 'Domestik & Mancanegara',
};

$rangeLabel = date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pengunjung <?= esc($rangeLabel) ?></title>
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
            font-size: 9px;
            line-height: 1.3;
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
            margin: 0 0 3px 0;
        }

        .header-subtitle {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin: 0 0 4px 0;
        }

        .header-period {
            font-size: 10px;
            color: #444;
            margin: 0;
        }

        .header-date {
            font-size: 8.5px;
            color: #888;
            margin-top: 2px;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            table-layout: fixed;
        }

        table.report-table thead {
            display: table-header-group;
        }

        table.report-table th {
            color: #000;
            background: #f0f0f0;
            padding: 4px 4px;
            text-align: center;
            font-weight: bold;
            font-size: 8.5px;
            border: 1px solid #1a1f26;
            word-wrap: break-word;
        }

        table.report-table td {
            padding: 3.5px 4px;
            border: 1px solid #333;
            font-size: 8.5px;
            color: #333;
        }

        table.report-table tr.total-row td {
            font-weight: bold;
            background: #f8f9fa;
            border-top: 2px solid #333;
            padding: 4.5px 4px;
        }

        .signature-section {
            margin-top: 15px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-title">NEXTIX LOKET &amp; GATE SYSTEM</div>
        <div class="header-subtitle">LAPORAN DATA &amp; MANIFEST PENGUNJUNG</div>
        <div class="header-period">Kategori: <strong><?= esc($kategoriLabel) ?></strong> &nbsp;|&nbsp; Periode: <?= esc($rangeLabel) ?></div>
        <div class="header-date">Dicetak pada: <?= date('d/m/Y H:i') ?> WITA</div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 14%">No. Tiket</th>
                <th style="width: 14%">Invoice</th>
                <th style="width: 21%">Nama Pengunjung</th>
                <th style="width: 18%">Kota / Negara</th>
                <th style="width: 7%">Tipe</th>
                <th style="width: 6%">Qty</th>
                <th style="width: 6%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 12px; color: #777;">Tidak ada data pengunjung pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tickets as $idx => $t): ?>
                    <tr>
                        <td style="text-align: center;"><?= $idx + 1 ?></td>
                        <td style="text-align: center;"><?= date('d/m/Y', strtotime($t['valid_date'])) ?></td>
                        <td style="text-align: center; font-family: monospace; font-weight: bold;"><?= esc($t['ticket_code']) ?></td>
                        <td style="text-align: center; font-family: monospace; font-size: 8px;"><?= esc($t['invoice_code'] ?? '-') ?></td>
                        <td><?= esc($t['customer_name'] ?? 'Umum') ?></td>
                        <td><?= esc($t['origin'] ?? '-') ?></td>
                        <td style="text-align: center;"><?= esc(ucfirst($t['ticket_category'] ?? 'Individual')) ?></td>
                        <td style="text-align: center; font-weight: bold;"><?= (int)($t['total_visitors'] ?? 0) ?></td>
                        <td style="text-align: center; font-size: 8px;"><?= ($t['status'] ?? '') === 'used' ? 'Hadir' : (($t['status'] ?? '') === 'active' ? 'Aktif' : 'Batal') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="7" style="text-align: right; padding-right: 8px;">TOTAL PENGUNJUNG:</td>
                    <td style="text-align: center;"><?= number_format($totalPengunjung, 0, ',', '.') ?> org</td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Petugas Ticketing / Gate,</p>
            <br><br><br>
            <p>( .................................................... )</p>
        </div>
        <div class="signature-box">
            <p>Mengetahui / Supervisor,</p>
            <br><br><br>
            <p>( .................................................... )</p>
        </div>
    </div>

</body>
</html>
