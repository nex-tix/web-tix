<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Struk Thermal - <?= esc($transaction['invoice_code']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        /* Styling Umum Tampilan Layar */
        body {
            background-color: #f1f5f9;
        }

        .receipt-80mm {
            width: 80mm;
            max-width: 80mm;
            font-size: 12px;
        }

        .receipt-58mm {
            width: 58mm;
            max-width: 58mm;
            font-size: 10.5px;
        }

        /* Garis Thermal Standar Monokrom */
        .border-thermal-dashed {
            border-top: 1.2px dashed #000000 !important;
            margin: 5px 0 !important;
            width: 100% !important;
            height: 0 !important;
        }

        .border-thermal-solid {
            border-top: 1.5px solid #000000 !important;
            margin: 5px 0 !important;
            width: 100% !important;
            height: 0 !important;
        }

        .thermal-receipt,
        .thermal-receipt * {
            color: #000000 !important;
        }

        .qr-box img,
        .qr-box canvas {
            margin: 0 auto !important;
        }

        /* Aturan Cetak (Thermal Print) */
        @page {
            size: auto;
            margin: 0mm !important;
        }

        @media print {
            * {
                color: #000000 !important;
                border-color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                text-shadow: none !important;
                box-shadow: none !important;
            }

            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
            }

            .no-print {
                display: none !important;
            }

            /* Sembunyikan elemen CodeIgniter Debug Toolbar agar tidak ikut tercetak */
            #debug-icon,
            #debug-bar,
            .ci-debugbar,
            #toolbarContainer {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
            }

            /* Normalisasi Wrapper: Hindari display flex & gap agar tidak memicu Blank Page */
            .receipt-screen-wrapper,
            .receipt-preview-area,
            main,
            #receipt-wrapper {
                display: block !important;
                gap: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .thermal-receipt {
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto !important;
                padding: 1.5mm 2mm 0 2mm !important;
                background: #ffffff !important;
                box-sizing: border-box !important;
            }

            /* Driver thermal 58mm umumnya memiliki lebar cetak efektif 48mm */
            .receipt-58mm {
                width: 48mm !important;
                max-width: 48mm !important;
                font-size: 10px !important;
                line-height: 1.25 !important;
            }

            /* Driver thermal 80mm umumnya memiliki lebar cetak efektif 72mm */
            .receipt-80mm {
                width: 72mm !important;
                max-width: 72mm !important;
                font-size: 11.5px !important;
                line-height: 1.3 !important;
            }

            /* Pemisah halaman untuk transaksi multi-tiket individual / group split */
            .receipt-slip {
                display: block !important;
                page-break-after: always !important;
                break-after: page !important;
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }

            .receipt-slip:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            /* Trailing Feed Spacer: Ruang potong fisik untuk pisau auto-cutter */
            .thermal-cut-spacer {
                display: block !important;
                height: 12mm !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                content: "" !important;
            }

            .border-thermal-dashed {
                border-top: 1.2px dashed #000000 !important;
                margin: 5px 0 !important;
            }

            .border-thermal-solid {
                border-top: 1.5px solid #000000 !important;
                margin: 5px 0 !important;
            }
        }
    </style>
</head>
<?php
$totalVisitors = 0;
$hasGroupTicket = false;
foreach ($tickets as $tk) {
    if (!empty($tk['details'])) {
        foreach ($tk['details'] as $det) {
            $totalVisitors += (int) $det['qty'];
        }
    } else {
        $totalVisitors += (int) ($tk['total_visitors'] ?? 1);
    }
    if (($tk['ticket_category'] ?? '') === 'group') {
        $hasGroupTicket = true;
    }
}
$isGroupOrMulti = (($transaction['ticket_category'] ?? null) === 'group' || $hasGroupTicket || $totalVisitors > 1);
$currentTicketCount = count($tickets);
$isMaster = ($currentTicketCount === 1 && $totalVisitors > 1);
$isPerPerson = ($currentTicketCount === $totalVisitors && $totalVisitors > 1);
?>
<body class="text-slate-900 font-mono text-xs antialiased bg-slate-100 min-h-screen">

    <div class="receipt-screen-wrapper min-h-screen flex flex-col lg:flex-row">
        
        <!-- LEFT SIDEBAR (40% WIDTH, DOCKED, FULL-HEIGHT, WHITE, BORDER-R) -->
        <aside class="w-full lg:w-2/5 shrink-0 bg-white border-b lg:border-b-0 lg:border-r border-slate-200 p-6 lg:p-8 xl:p-10 no-print lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto flex flex-col justify-between items-center space-y-6">
            
            <div class="w-full max-w-md space-y-6">
                <!-- Title & Invoice info -->
                <div class="space-y-1 pb-4 border-b border-slate-100">
                    <div class="flex items-center justify-between">
                        <h1 class="font-bold text-base text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Panel Cetak Struk
                        </h1>
                        <span class="text-[11px] px-2.5 py-0.5 rounded-full font-bold bg-slate-100 text-slate-700">
                            <?= count($tickets) ?> Struk
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-mono"><?= esc($transaction['invoice_code']) ?></p>
                </div>

                <!-- Action Buttons: Cetak & Kembali -->
                <div class="space-y-2">
                    <button type="button" onclick="window.print()" class="w-full px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-sm hover:shadow transition flex items-center justify-center gap-2 cursor-pointer" title="Cetak struk (Enter / P)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Cetak Struk (Enter)</span>
                    </button>
                    <a href="<?= base_url('admin/pos') ?>" class="w-full px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition flex items-center justify-center gap-1.5" title="Kembali ke Loket POS (Esc)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Kembali ke POS (Esc)</span>
                    </a>
                </div>

                <!-- Paper Size Switcher -->
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700">Ukuran Kertas:</span>
                        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                            <button type="button" onclick="setPaperSize('58mm')" id="btn-58mm" class="px-3 py-1 rounded-md font-bold text-xs transition text-slate-600 hover:text-slate-900 cursor-pointer">
                                58mm
                            </button>
                            <button type="button" onclick="setPaperSize('80mm')" id="btn-80mm" class="px-3 py-1 rounded-md font-bold text-xs transition bg-slate-900 text-white shadow-xs cursor-pointer">
                                80mm
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-sans">
                        <button type="button" onclick="toggleAutoCutGuide()" class="text-blue-600 hover:text-blue-800 font-medium underline flex items-center gap-1 cursor-pointer" title="Panduan pengaturan driver printer auto-cut">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tips Auto-Cutter
                        </button>
                        <span>Pintasan: <strong>1</strong> (58) &bull; <strong>2</strong> (80)</span>
                    </div>
                </div>

                <!-- Panduan Pengaturan Driver Printer Auto-Cutter (Collapsible) -->
                <div id="autocut-guide-box" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs space-y-1.5 text-blue-900">
                    <div class="flex justify-between items-center font-bold">
                        <span class="flex items-center gap-1 text-blue-800 text-[11px]">
                            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tips Pengaturan Driver Printer:
                        </span>
                        <button type="button" onclick="toggleAutoCutGuide()" class="text-blue-600 hover:text-blue-900 text-base font-bold px-1 cursor-pointer">&times;</button>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-[11px] leading-relaxed text-blue-950 font-sans">
                        <li><strong>Cut Option:</strong> Pilih <code>Cut at End of Page</code> agar dipotong per lembar tiket.</li>
                        <li><strong>Cut Mode:</strong> Disarankan <code>Partial Cut</code> agar tiket tetap menggantung rapi di printer.</li>
                        <li><strong>Margin Browser:</strong> Set ke <code>None</code> atau <code>Minimum</code> pada dialog cetak.</li>
                        <li><strong>Trailing Feed:</strong> Web otomatis menyertakan spacer 12mm di akhir struk.</li>
                    </ul>
                </div>

                <!-- Group Ticket Split Options (2 Options: Master vs Per Orang) -->
                <?php if ($isGroupOrMulti): ?>
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Pilihan Cetak Group
                        </span>
                        <span class="text-[10px] px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-800">
                            <?= $totalVisitors ?> Orang
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 rounded-xl text-xs">
                        <button type="button" onclick="setSplitMode('master')" id="btn-mode-master"
                            class="py-2 px-2 rounded-lg font-semibold text-center transition cursor-pointer <?= $isMaster ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 bg-white/60' ?>">
                            Master Ticket
                            <span class="block text-[10px] font-normal opacity-80">(1 QR)</span>
                        </button>
                        <button type="button" onclick="setSplitMode('individual')" id="btn-mode-individual"
                            class="py-2 px-2 rounded-lg font-semibold text-center transition cursor-pointer <?= $isPerPerson ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 bg-white/60' ?>">
                            Per Orang
                            <span class="block text-[10px] font-normal opacity-80">(<?= $totalVisitors ?> QR)</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-tight font-sans">
                        Pilih <strong>Master Ticket</strong> untuk mencetak 1 lembar struk rombongan, atau <strong>Per Orang</strong> untuk memecah ke <?= $totalVisitors ?> lembar tiket individu.
                    </p>
                </div>
                <?php endif; ?>

            </div>

            <!-- Footer Sidebar: Pintasan Keyboard -->
            <div class="w-full max-w-md pt-4 border-t border-slate-100 text-[11px] text-slate-400 font-sans hidden lg:block">
                <p class="font-semibold text-slate-500 mb-1.5">Pintasan Keyboard:</p>
                <ul class="space-y-1">
                    <li class="flex items-center justify-between"><span>Cetak Struk:</span> <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded text-[10px] font-mono text-slate-700">Enter</kbd></li>
                    <li class="flex items-center justify-between"><span>Kembali ke POS:</span> <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded text-[10px] font-mono text-slate-700">Esc</kbd></li>
                    <li class="flex items-center justify-between"><span>Ukuran Kertas:</span> <span><kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded text-[10px] font-mono text-slate-700">1</kbd> / <kbd class="px-1.5 py-0.5 bg-slate-100 border border-slate-300 rounded text-[10px] font-mono text-slate-700">2</kbd></span></li>
                </ul>
            </div>

        </aside>

        <!-- RIGHT PREVIEW CANVAS (60% WIDTH, SCROLLABLE, STREAM STRUK TIKET THERMAL) -->
        <main class="receipt-preview-area w-full lg:w-3/5 py-8 px-4 lg:px-8 flex flex-col items-center min-h-screen overflow-y-auto bg-slate-100">
            <div id="receipt-wrapper" class="w-full max-w-4xl flex flex-wrap justify-center items-start content-start gap-6">
        <?php foreach ($tickets as $idx => $t): ?>
        <div class="receipt-slip bg-white p-4 shadow-md border border-slate-300 receipt-58mm thermal-receipt text-black font-mono space-y-2 shrink-0">
            
            <!-- 1. HEADER -->
            <div class="text-center space-y-0.5">
                <h2 class="font-bold text-sm tracking-wider uppercase leading-tight"><?= esc($settings['business_name'] ?? 'NEXTIX') ?></h2>
                <p class="text-[10px] leading-tight text-black"><?= esc($settings['business_address'] ?? 'Uluwatu, Bali') ?></p>
                <p class="text-[9px] text-black">Terminal: Loket POS</p>
            </div>

            <!-- GARIS PUTUS-PUTUS -->
            <div class="border-thermal-dashed"></div>

            <!-- 2. DATA PEMBELIAN -->
            <div class="space-y-0.5 text-[11px] leading-tight text-center">
                <div class="flex justify-center">
                    <span><?= esc($transaction['invoice_code']) ?></span>
                </div>
                <div class="flex justify-center">
                    <span class="break-words max-w-[20ch] truncate"><?= esc($customer['name']) ?></span>
                </div>
                <div class="flex justify-center">
                    <span class="break-words truncate max-w-[20ch]">(<?= esc($customer['origin']) ?>) <?= ucfirst($customer['origin_type']) ?></span>
                </div>
                <?php if (!empty($t['guide_name_snapshot'])): ?>
                <div class="flex justify-center">
                    <span class="break-words max-w-[20ch] truncate">Guide: <?= esc($t['guide_name_snapshot']) ?></span>
                </div>
                <?php elseif (!empty($tickets[0]['guide_name_snapshot'])): ?>
                <div class="flex justify-center">
                    <span class="break-words max-w-[28ch]">Guide: <?= esc($tickets[0]['guide_name_snapshot']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-center text-[10px] text-black">
                    <span><?= date('Y-m-d H:i', strtotime($transaction['created_at'])) ?> WITA</span>
                </div>
                <?php if (!empty($transaction['show_time'])): ?>
                <div class="flex justify-center text-[10px] font-bold text-black">
                    <span>Sesi: <?= esc($transaction['show_time']) ?> WITA</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="border-thermal-dashed"></div>   

            <!-- RINCIAN ITEM PEMBELIAN -->
            <div class="pt-1 space-y-1">
                <?php 
                $detailsToShow = !empty($t['details']) ? $t['details'] : ($tickets[0]['details'] ?? []);
                foreach ($detailsToShow as $det): 
                    $typeName = $ticketTypesById[$det['ticket_type_id']]['name'] ?? 'Tiket';
                    $totalTicket = $det['unit_price'] * $det['qty'];
                ?>
                <div class="flex justify-between items-start text-[11px] leading-tight text-black">
                    <div class="pr-1 text-left break-words">
                        <span><?= esc($typeName) ?></span> x <?= $det['qty'] ?>
                        <div class="text-[9px] text-black">x1 Rp <?= number_format($det['unit_price'], 0, ',', '.') ?></div>
                    </div>
                    <span class="whitespace-nowrap text-right shrink-0">Rp <?= number_format($totalTicket, 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- GARIS PUTUS-PUTUS -->
            <div class="border-thermal-dashed"></div>   

            <!-- 3. SUBTOTAL & DISKON -->
            <div class="space-y-1 text-[11px] text-black">
                <div class="flex justify-between items-center">
                    <span>SUBTOTAL:</span>
                    <span class="whitespace-nowrap">Rp <?= number_format($transaction['subtotal'], 0, ',', '.') ?></span>
                </div>
                <?php if ($transaction['discount_amount'] > 0): ?>
                <div class="flex justify-between items-center">
                    <span>DISKON:</span>
                    <span class="whitespace-nowrap">- Rp <?= number_format($transaction['discount_amount'], 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- GARIS UTUH (SOLID) -->
            <div class="border-thermal-solid"></div>

            <!-- 4. TOTAL, PEMBAYARAN, UANG DITERIMA, KEMBALIAN -->
            <div class="space-y-1 font-bold text-[11px] text-black">
                <div class="flex justify-between items-center text-xs">
                    <span>TOTAL:</span>
                    <span class="whitespace-nowrap">Rp <?= number_format($transaction['total_amount'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center text-[10px] font-normal pt-0.5">
                    <span class="font-bold">PEMBAYARAN:</span>
                    <span class="uppercase whitespace-nowrap font-bold"><?= esc($transaction['payment_method']) ?></span>
                </div>
                <?php if ($transaction['payment_method'] === 'cash'): ?>
                <div class="flex justify-between items-center text-[10px] font-normal">
                    <span class="font-bold">Uang Diterima:</span>
                    <span class="whitespace-nowrap font-bold">Rp <?= number_format($transaction['cash_paid'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center text-[10px] font-normal">
                    <span class="font-bold">Kembalian:</span>
                    <span class="whitespace-nowrap font-bold">Rp <?= number_format($transaction['cash_change'], 0, ',', '.') ?></span>
                </div>
                <?php else: ?>
                <div class="flex justify-between items-center text-[10px] font-normal">
                    <span>No. Ref EDC:</span>
                    <span class="font-mono whitespace-nowrap"><?= esc($transaction['payment_reference']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- GARIS PUTUS-PUTUS -->
            <div class="border-thermal-dashed"></div>

            <!-- 5. QR CODE & KODE TIKET -->
            <div class="flex flex-col items-center justify-center my-1.5 space-y-1">
                <?php if ($t['ticket_category'] === 'group' && (int)$t['total_visitors'] > 1): ?>
                <div class="text-[10px] font-bold uppercase tracking-wider text-center">
                    TIKET ROMBONGAN (<?= (int)$t['total_visitors'] ?> ORANG)
                </div>
                <?php else: ?>
                <div class="text-[10px] font-bold uppercase tracking-wider text-center">
                    TIKET MASUK (1 ORANG)
                </div>
                <?php endif; ?>
                
                <div class="qr-box bg-white p-1 inline-block">
                    <div id="qrcode-<?= $t['id'] ?>"></div>
                </div>

                <div class="font-mono font-bold tracking-wider text-[11px] text-center pt-0.5 break-all">
                    <?= esc($t['ticket_code']) ?>
                </div>
            </div>

            <!-- 6. FOOTER -->
            <div class="text-center text-[8px] space-y-0.5 pt-0.5 text-black">
                <p class="uppercase tracking-wide -mb-1">SELAMAT BERSENANG-SENANG</p>
                <?php if (!empty($settings['receipt_footer'])): ?>
                <p><?= esc($settings['receipt_footer']) ?></p>
                <?php endif; ?>
            </div>

            <!-- GARIS PUTUS-PUTUS PENUTUP -->
            <div class="border-thermal-dashed"></div>

            <!-- Trailing feed space khusus print agar pisau auto-cutter tidak memotong garis penutup/footer -->
            <div class="thermal-cut-spacer hidden print:block"></div>

        </div>
        <?php endforeach; ?>
            </div>
        </main>

    </div>

    <script>
        let currentSize = '58mm';
        const tickets = <?= json_encode($tickets) ?>;
        const totalVisitors = <?= (int)$totalVisitors ?>;
        const transactionId = <?= (int)$transaction['id'] ?>;
        const csrfTokenName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        async function setSplitMode(mode) {
            if (mode !== 'master' && mode !== 'individual') return;
            await executeSplit(mode);
        }

        async function executeSplit(mode) {
            const btnMaster = document.getElementById('btn-mode-master');
            const btnIndiv = document.getElementById('btn-mode-individual');
            if (btnMaster) btnMaster.disabled = true;
            if (btnIndiv) btnIndiv.disabled = true;

            try {
                const formData = new FormData();
                formData.append('split_mode', mode);
                formData.append(csrfTokenName, csrfHash);

                const res = await fetch(`<?= base_url('admin/pos/receipt/' . $transaction['id'] . '/split') ?>`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json();
                if (data && data.csrf_hash) csrfHash = data.csrf_hash;

                if (res.ok && data && data.success) {
                    window.location.reload();
                } else {
                    alert((data && data.message) || 'Gagal mengubah susunan tiket.');
                    if (btnMaster) btnMaster.disabled = false;
                    if (btnIndiv) btnIndiv.disabled = false;
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
                if (btnMaster) btnMaster.disabled = false;
                if (btnIndiv) btnIndiv.disabled = false;
            }
        }

        function renderQRCodes(size) {
            const qrSize = size === '58mm' ? 105 : 128;
            tickets.forEach(ticket => {
                const container = document.getElementById('qrcode-' + ticket.id);
                if (!container) return;
                container.innerHTML = '';
                if (typeof QRCode === 'undefined') {
                    container.textContent = 'QR gagal dimuat';
                    return;
                }

                new QRCode(container, {
                    text: `TICKET|code=${ticket.ticket_code}|transaction=${ticket.transaction_id}|status=active`,
                    width: qrSize,
                    height: qrSize,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.M
                });
            });
        }

        function toggleAutoCutGuide() {
            const box = document.getElementById('autocut-guide-box');
            if (box) box.classList.toggle('hidden');
        }

        function setPaperSize(size) {
            currentSize = size;
            try {
                localStorage.setItem('nextix_receipt_paper_size', size);
            } catch (e) {}

            const slips = document.querySelectorAll('.receipt-slip');
            const btn80 = document.getElementById('btn-80mm');
            const btn58 = document.getElementById('btn-58mm');

            slips.forEach(slip => {
                if (size === '58mm') {
                    slip.classList.remove('receipt-80mm');
                    slip.classList.add('receipt-58mm');
                } else {
                    slip.classList.remove('receipt-58mm');
                    slip.classList.add('receipt-80mm');
                }
            });

            if (size === '58mm') {
                btn58.className = "px-3 py-1.5 rounded-md font-bold text-xs transition bg-slate-900 text-white shadow-xs cursor-pointer";
                btn80.className = "px-3 py-1.5 rounded-md font-bold text-xs transition text-slate-600 hover:text-slate-900 cursor-pointer";
            } else {
                btn80.className = "px-3 py-1.5 rounded-md font-bold text-xs transition bg-slate-900 text-white shadow-xs cursor-pointer";
                btn58.className = "px-3 py-1.5 rounded-md font-bold text-xs transition text-slate-600 hover:text-slate-900 cursor-pointer";
            }

            renderQRCodes(size);
        }

        // Keyboard Shortcuts
        window.addEventListener('keydown', (e) => {
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;

            if (e.key === 'Enter' || (e.ctrlKey && (e.key === 'p' || e.key === 'P')) || e.key === 'p' || e.key === 'P') {
                e.preventDefault();
                window.print();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                window.location.href = "<?= base_url('admin/pos') ?>";
            } else if (e.key === '1') {
                e.preventDefault();
                setPaperSize('58mm');
            } else if (e.key === '2') {
                e.preventDefault();
                setPaperSize('80mm');
            }
        });

        // Inisialisasi
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const sizeParam = urlParams.get('size');

            let preferredSize = '80mm'; // Default 80mm untuk printer kasir auto-cutter
            try {
                const storedSize = localStorage.getItem('nextix_receipt_paper_size');
                if (storedSize === '58mm' || storedSize === '80mm') {
                    preferredSize = storedSize;
                }
            } catch (e) {}

            if (sizeParam === '58mm' || sizeParam === '80mm') {
                preferredSize = sizeParam;
            }

            setPaperSize(preferredSize);
        });
    </script>
</body>
</html>
