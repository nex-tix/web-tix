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

            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
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

            .thermal-receipt {
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto !important;
                padding: 1.5mm 2mm !important;
                background: #ffffff !important;
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

            /* Pemisah halaman untuk transaksi multi-tiket individual */
            .receipt-slip {
                page-break-after: always !important;
                break-after: page !important;
            }

            .receipt-slip:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
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
$isCustom = ($isGroupOrMulti && !$isMaster && !$isPerPerson);
$customSplitCount = $isCustom ? ($currentTicketCount - 1) : 1;
?>
<body class="text-slate-900 font-mono text-xs antialiased py-6">

    <!-- Control Toolbar (No Print) -->
    <div class="max-w-md mx-auto mb-5 bg-white p-4 rounded-xl shadow-sm border border-slate-200 no-print space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h1 class="font-bold text-sm text-slate-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Struk & Tiket
                </h1>
                <p class="text-[11px] text-slate-500 font-mono"><?= esc($transaction['invoice_code']) ?> &bull; <?= count($tickets) ?> Struk</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5" title="Cetak struk (Enter / P)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak (Enter)</span>
                </button>
                <a href="<?= base_url('admin/pos') ?>" class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition flex items-center gap-1" title="Kembali ke Loket POS (Esc)">
                    <span>Kembali (Esc)</span>
                </a>
            </div>
        </div>

        <!-- Paper Size Switcher & Shortcut Info -->
        <div class="flex items-center justify-between gap-2 pt-1 text-xs">
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg border border-slate-200">
                <span class="text-[10px] uppercase font-bold text-slate-600 px-2">Kertas:</span>
                <button type="button" onclick="setPaperSize('58mm')" id="btn-58mm" class="px-3 py-1.5 rounded-md font-bold text-xs transition bg-slate-900 text-white shadow-xs">
                    58mm
                </button>
                <button type="button" onclick="setPaperSize('80mm')" id="btn-80mm" class="px-3 py-1.5 rounded-md font-bold text-xs transition text-slate-600 hover:text-slate-900">
                    80mm
                </button>
            </div>
            <div class="text-[10px] text-slate-500 font-sans hidden sm:block">
                Pintasan: <strong>Enter</strong> Cetak &bull; <strong>Esc</strong> Loket
            </div>
        </div>

        <!-- Group Ticket Split Toolbar -->
        <?php if ($isGroupOrMulti): ?>
        <div class="border-t border-slate-200 pt-3 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Pilihan Cetak Tiket Group (<?= $totalVisitors ?> Orang)
                </span>
                <span class="text-[10px] px-2 py-0.5 rounded font-bold bg-amber-100 text-amber-800">
                    <?= $currentTicketCount ?> Struk
                </span>
            </div>

            <div class="grid grid-cols-3 gap-1.5 p-1 bg-slate-100 rounded-lg text-xs">
                <button type="button" onclick="setSplitMode('master')" id="btn-mode-master"
                    class="py-1.5 px-2 rounded-md font-semibold text-center transition <?= $isMaster ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
                    Master Ticket
                    <span class="block text-[10px] font-normal opacity-80">(1 QR)</span>
                </button>
                <button type="button" onclick="setSplitMode('individual')" id="btn-mode-individual"
                    class="py-1.5 px-2 rounded-md font-semibold text-center transition <?= $isPerPerson ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
                    Per Orang
                    <span class="block text-[10px] font-normal opacity-80">(<?= $totalVisitors ?> QR)</span>
                </button>
                <button type="button" onclick="toggleCustomSplit(true)" id="btn-mode-custom"
                    class="py-1.5 px-2 rounded-md font-semibold text-center transition <?= $isCustom ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
                    Custom Split
                    <span class="block text-[10px] font-normal opacity-80">(Sebagian)</span>
                </button>
            </div>

            <!-- Custom Split Sub-panel -->
            <div id="custom-split-panel" class="<?= $isCustom ? '' : 'hidden' ?> p-2.5 bg-amber-50/70 border border-amber-200 rounded-lg space-y-2 text-xs">
                <div class="flex items-center justify-between gap-2">
                    <label for="custom_split_count" class="font-bold text-slate-700">
                        Jumlah Tiket Terpisah (Orang):
                    </label>
                    <div class="flex items-center gap-1.5">
                        <input type="number" id="custom_split_count" min="1" max="<?= max(1, $totalVisitors - 1) ?>" value="<?= $customSplitCount ?>" 
                            oninput="updateCustomSplitPreview(<?= $totalVisitors ?>)"
                            class="w-16 px-2 py-1 text-center font-bold text-sm bg-white border border-slate-300 rounded focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <button type="button" onclick="applyCustomSplit()" id="btn-apply-split"
                            class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded shadow-xs transition">
                            Terapkan
                        </button>
                    </div>
                </div>
                <p id="custom-split-info" class="text-[11px] text-amber-900 leading-tight">
                    <!-- Dinamis terupdate oleh JS -->
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Receipt Container -->
    <div id="receipt-wrapper" class="mx-auto flex flex-col items-center gap-6">
        <?php foreach ($tickets as $idx => $t): ?>
        <div class="receipt-slip bg-white p-4 shadow-md border border-slate-300 receipt-58mm thermal-receipt text-black font-mono space-y-2">
            
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

        </div>
        <?php endforeach; ?>
    </div>

    <script>
        let currentSize = '58mm';
        const tickets = <?= json_encode($tickets) ?>;
        const totalVisitors = <?= (int)$totalVisitors ?>;
        const transactionId = <?= (int)$transaction['id'] ?>;
        const csrfTokenName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        function updateCustomSplitPreview(total) {
            const input = document.getElementById('custom_split_count');
            if (!input) return;
            let val = parseInt(input.value) || 0;
            if (val < 1) val = 1;
            if (val >= total) val = total - 1;
            input.value = val;
            const groupCount = total - val;
            const info = document.getElementById('custom-split-info');
            if (info) {
                info.innerHTML = `Hasil cetak: <strong>1 Tiket Group (${groupCount} Orang)</strong> + <strong>${val} Tiket Terpisah (masing-masing 1 Orang)</strong> &bull; Total ${val + 1} struk tiket.`;
            }
        }

        function toggleCustomSplit(show) {
            const panel = document.getElementById('custom-split-panel');
            if (panel) {
                panel.classList.remove('hidden');
                updateCustomSplitPreview(totalVisitors);
            }
        }

        async function setSplitMode(mode) {
            if (mode === 'custom') {
                toggleCustomSplit(true);
                return;
            }
            await executeSplit(mode, 0);
        }

        async function applyCustomSplit() {
            const input = document.getElementById('custom_split_count');
            const splitCount = parseInt(input.value) || 1;
            await executeSplit('custom', splitCount);
        }

        async function executeSplit(mode, splitCount) {
            const btnApply = document.getElementById('btn-apply-split');
            if (btnApply) {
                btnApply.disabled = true;
                btnApply.textContent = 'Menyimpan...';
            }

            try {
                const formData = new FormData();
                formData.append('split_mode', mode);
                formData.append('split_count', splitCount);
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
                    if (btnApply) {
                        btnApply.disabled = false;
                        btnApply.textContent = 'Terapkan';
                    }
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
                if (btnApply) {
                    btnApply.disabled = false;
                    btnApply.textContent = 'Terapkan';
                }
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

        function setPaperSize(size) {
            currentSize = size;
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
                btn58.className = "px-3 py-1.5 rounded-md font-bold text-xs transition bg-slate-900 text-white shadow-xs";
                btn80.className = "px-3 py-1.5 rounded-md font-bold text-xs transition text-slate-600 hover:text-slate-900";
            } else {
                btn80.className = "px-3 py-1.5 rounded-md font-bold text-xs transition bg-slate-900 text-white shadow-xs";
                btn58.className = "px-3 py-1.5 rounded-md font-bold text-xs transition text-slate-600 hover:text-slate-900";
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

            if (sizeParam === '80mm') {
                setPaperSize('80mm');
            } else {
                setPaperSize('58mm');
            }

            if (typeof totalVisitors !== 'undefined' && totalVisitors > 1) {
                updateCustomSplitPreview(totalVisitors);
            }
        });
    </script>
</body>
</html>
