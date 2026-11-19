<?php
$expenses         = $expenses ?? [];
$selectedCategory = $selectedCategory ?? null;
$startDate        = $startDate ?? null;
$endDate          = $endDate ?? null;
$paymentMethod    = $paymentMethod ?? '';
$search           = $search ?? '';
$totalNominal     = $totalNominal ?? 0;

$kategoriLabel = !empty($selectedCategory) ? esc($selectedCategory['name']) : 'Semua Kategori';
$metodeLabel   = !empty($paymentMethod) ? ($paymentMethod === 'cash' ? 'Tunai (Cash)' : 'Transfer Bank') : 'Semua Metode';

if (!empty($startDate) && !empty($endDate)) {
    $rangeLabel = date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
} elseif (!empty($startDate)) {
    $rangeLabel = 'Mulai ' . date('d/m/Y', strtotime($startDate));
} elseif (!empty($endDate)) {
    $rangeLabel = 'Sampai ' . date('d/m/Y', strtotime($endDate));
} else {
    $rangeLabel = 'Semua Periode';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran <?= esc($rangeLabel) ?></title>
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
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin: 0 0 5px 0;
        }

        .header-period {
            font-size: 10px;
            color: #555;
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
            margin-bottom: 10px;
            table-layout: fixed;
        }

        table.report-table thead {
            display: table-header-group;
        }

        table.report-table th {
            color: #000;
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
            border-top: 2px solid #333;
            padding: 4.5px 4px;
            font-size: 8.5px;
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
        <div class="header-subtitle">
            LAPORAN PENGELUARAN KAS &amp; BIAYA OPERASIONAL
        </div>
        <div class="header-period">
            Kategori: <strong><?= esc($kategoriLabel) ?></strong> &nbsp;|&nbsp; 
            Metode: <strong><?= esc($metodeLabel) ?></strong> &nbsp;|&nbsp; 
            Periode: <?= esc($rangeLabel) ?>
            <?php if (!empty($search)): ?>
                &nbsp;|&nbsp; Pencarian: <strong>"<?= esc($search) ?>"</strong>
            <?php endif; ?>
        </div>
        <div class="header-date">Dicetak pada: <?= date('d/m/Y H:i') ?> WITA</div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 4%">No</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 13%">Kode</th>
                <th style="width: 15%">Kategori</th>
                <th style="width: 26%">Keterangan</th>
                <th style="width: 14%">Metode Bayar</th>
                <th style="width: 18%">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($expenses)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px; color: #777;">Tidak ada catatan pengeluaran pada periode ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($expenses as $idx => $exp): ?>
                    <tr>
                        <td style="text-align: center;"><?= $idx + 1 ?></td>
                        <td style="text-align: center;"><?= date('d/m/Y', strtotime($exp['expense_date'])) ?></td>
                        <td style="text-align: center; font-family: monospace; font-weight: bold;"><?= esc($exp['expense_code']) ?></td>
                        <td><?= esc($exp['category_name'] ?? 'Umum') ?></td>
                        <td>
                            <?= esc($exp['title']) ?>
                            <?php if (!empty($exp['notes'])): ?>
                                <br><span style="font-size: 7.5px; color: #666;">(<?= esc($exp['notes']) ?>)</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <?= ($exp['payment_method'] === 'transfer') ? 'Transfer ' . esc($exp['bank_code'] ?: $exp['bank_name'] ?: 'Bank') : 'Tunai (Cash)' ?>
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            Rp <?= number_format((float)$exp['amount'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="6" style="text-align: right; padding-right: 10px;">TOTAL PENGELUARAN:</td>
                    <td style="text-align: right;">Rp <?= number_format($totalNominal, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

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
