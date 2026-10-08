<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Struk Thermal - <?= esc($transaction['invoice_code']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?= base_url('js/qrcode.js') ?>"></script>
    <style>
        @media print {
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .thermal-receipt {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                width: 100% !important;
            }
        }
        .receipt-80mm {
            width: 80mm;
        }
        .receipt-58mm {
            width: 58mm;
            font-size: 11px !important;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-mono text-xs antialiased py-6">

    <!-- Control Toolbar (No Print) -->
    <div class="max-w-md mx-auto mb-6 flex items-center justify-between gap-3 bg-white p-4 rounded-xl shadow-sm border border-slate-200 no-print">
        <div>
            <h1 class="font-bold text-sm text-slate-800">Cetak Struk & Tiket</h1>
            <p class="text-[11px] text-slate-500"><?= esc($transaction['invoice_code']) ?></p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="setMode('80mm')" id="btn-80mm" class="px-3 py-1.5 rounded-lg bg-slate-900 text-white font-bold text-xs">80mm</button>
            <button onclick="setMode('58mm')" id="btn-58mm" class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs">58mm</button>
            <button onclick="window.print()" class="px-4 py-1.5 rounded-lg bg-red-600 text-white font-bold text-xs shadow-sm hover:bg-red-700">Cetak</button>
            <a href="<?= base_url('admin/pos') ?>" class="px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 font-semibold text-xs hover:bg-slate-200">Kembali POS</a>
        </div>
    </div>

    <!-- Receipt Container -->
    <div id="receipt-wrapper" class="mx-auto bg-white p-4 shadow-md border border-slate-300 receipt-80mm space-y-4 thermal-receipt">
        
        <!-- SECTION 1: PAYMENT RECEIPT (Potongan 1) -->
        <div class="space-y-2 pb-4 border-b-2 border-dashed border-slate-400">
            <div class="text-center space-y-0.5">
                <h2 class="font-bold text-sm tracking-wider uppercase"><?= esc($settings['business_name']) ?></h2>
                <p class="text-[10px] text-slate-600"><?= esc($settings['business_address']) ?></p>
                <p class="text-[10px] text-slate-500">Terminal: Loket POS</p>
            </div>

            <div class="space-y-0.5 text-[11px] pt-2 border-t border-dashed border-slate-300">
                <div class="flex justify-between"><span>No. Invoice:</span> <span class="font-bold"><?= esc($transaction['invoice_code']) ?></span></div>
                <div class="flex justify-between"><span>Waktu Trx:</span> <span><?= esc($transaction['created_at']) ?> WITA</span></div>
                <div class="flex justify-between"><span>Pengunjung:</span> <span class="font-bold"><?= esc($customer['name']) ?></span></div>
                <div class="flex justify-between"><span>Asal:</span> <span><?= ucfirst($customer['origin_type']) ?> (<?= esc($customer['origin']) ?>)</span></div>
            </div>

            <div class="border-t border-dashed border-slate-300 pt-2 space-y-1">
                <?php foreach ($tickets as $t): ?>
                    <?php foreach ($t['details'] as $det): 
                        $typeName = $ticketTypesById[$det['ticket_type_id']]['name'] ?? 'Tiket';
                        $catLabel = $t['ticket_category'] === 'group' ? ' (Rombongan)' : '';
                    ?>
                    <div class="flex justify-between text-[11px]">
                        <div>
                            <span class="font-bold"><?= esc($typeName . $catLabel) ?></span> x <?= $det['qty'] ?>
                            <div class="text-[10px] text-slate-500">@ Rp <?= number_format($det['unit_price'], 0, ',', '.') ?></div>
                        </div>
                        <span class="font-bold">Rp <?= number_format($det['subtotal'], 0, ',', '.') ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-dashed border-slate-300 pt-2 space-y-1 font-bold text-[11px]">
                <div class="flex justify-between"><span>SUBTOTAL:</span> <span>Rp <?= number_format($transaction['subtotal'], 0, ',', '.') ?></span></div>
                <?php if ($transaction['discount_amount'] > 0): ?>
                <div class="flex justify-between text-emerald-700"><span>DISKON:</span> <span>- Rp <?= number_format($transaction['discount_amount'], 0, ',', '.') ?></span></div>
                <?php endif; ?>
                <div class="flex justify-between text-sm pt-1 border-t border-slate-300">
                    <span>TOTAL TAGIHAN:</span>
                    <span class="text-red-600">Rp <?= number_format($transaction['total_amount'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-[11px] font-normal text-slate-600 pt-1">
                    <span>PEMBAYARAN:</span>
                    <span class="uppercase font-bold"><?= esc($transaction['payment_method']) ?></span>
                </div>
                <?php if ($transaction['payment_method'] === 'cash'): ?>
                <div class="flex justify-between text-[11px] font-normal text-slate-600">
                    <span>Uang Diterima:</span>
                    <span>Rp <?= number_format($transaction['cash_paid'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-[11px] font-normal text-slate-600">
                    <span>Kembalian:</span>
                    <span>Rp <?= number_format($transaction['cash_change'], 0, ',', '.') ?></span>
                </div>
                <?php else: ?>
                <div class="flex justify-between text-[11px] font-normal text-slate-600">
                    <span>No. Ref EDC:</span>
                    <span><?= esc($transaction['payment_reference']) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="pt-2 text-center text-[10px] text-slate-600 space-y-0.5 border-t border-dashed border-slate-300">
                <p class="font-bold">BERLAKU HANYA HARI INI</p>
                <p><?= esc($tickets[0]['valid_date'] ?? date('Y-m-d')) ?> SAMPAI PUKUL 24.00 WITA</p>
                <p class="italic uppercase font-semibold text-red-600"><?= esc($settings['receipt_footer']) ?></p>
            </div>
        </div>

        <!-- SECTION 2: TICKET CUTS (Potongan 2 - Per Tiket) -->
        <?php foreach ($tickets as $idx => $t): ?>
        <div class="space-y-2 pt-4 pb-4 border-b-2 border-dashed border-slate-400 page-break">
            <div class="text-center space-y-0.5">
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-800">
                    <?= $t['ticket_category'] === 'group' ? 'TIKET ROMBONGAN' : 'TIKET MASUK' ?>
                </h3>
                <p class="text-[10px] text-slate-500">Kecak & Fire Dance Uluwatu</p>
            </div>

            <!-- QR Code container -->
            <div class="flex flex-col items-center justify-center py-2 space-y-1">
                <div id="qrcode-<?= $t['id'] ?>" class="bg-white p-2 border border-slate-200 rounded"></div>
                <span class="font-mono font-bold text-xs tracking-widest"><?= esc($t['ticket_code']) ?></span>
            </div>

            <div class="space-y-0.5 text-[11px] bg-slate-50 p-2 rounded border border-slate-200">
                <div class="flex justify-between"><span>Kategori:</span> <span class="uppercase font-bold"><?= esc($t['ticket_category']) ?></span></div>
                <div class="flex justify-between"><span>Pengunjung:</span> <span class="font-bold"><?= esc($customer['name']) ?></span></div>
                <div class="flex justify-between"><span>Jumlah Tamu:</span> <span class="font-bold"><?= $t['total_visitors'] ?> Orang</span></div>
                <div class="flex justify-between"><span>Berlaku Tgl:</span> <span class="font-bold"><?= esc($t['valid_date']) ?></span></div>
            </div>

            <div class="text-center text-[10px] text-slate-600 space-y-0.5 pt-1">
                <p class="font-bold">BERLAKU HANYA HARI INI SAMPAI 24.00 WITA</p>
                <p class="italic text-red-600">TIDAK DAPAT DI-REFUND</p>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <script>
        function setMode(size) {
            const wrapper = document.getElementById('receipt-wrapper');
            const btn80 = document.getElementById('btn-80mm');
            const btn58 = document.getElementById('btn-58mm');
            if (size === '58mm') {
                wrapper.className = wrapper.className.replace('receipt-80mm', 'receipt-58mm');
                btn58.className = "px-3 py-1.5 rounded-lg bg-slate-900 text-white font-bold text-xs";
                btn80.className = "px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs";
            } else {
                wrapper.className = wrapper.className.replace('receipt-58mm', 'receipt-80mm');
                btn80.className = "px-3 py-1.5 rounded-lg bg-slate-900 text-white font-bold text-xs";
                btn58.className = "px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs";
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            const tickets = <?= json_encode($tickets) ?>;
            tickets.forEach(ticket => {
                const container = document.getElementById('qrcode-' + ticket.id);
                if (!container) return;
                if (typeof qrcode !== 'function') {
                    container.textContent = 'QR gagal dimuat';
                    return;
                }

                const code = qrcode(4, 'M');
                code.addData(ticket.ticket_code);
                code.make();
                container.innerHTML = code.createImgTag(4, 0, 'Ticket QR ' + ticket.ticket_code);

                const image = container.querySelector('img');
                if (image) {
                    image.width = 128;
                    image.height = 128;
                    image.alt = 'QR ' + ticket.ticket_code;
                }
            });
        });
    </script>
</body>
</html>
