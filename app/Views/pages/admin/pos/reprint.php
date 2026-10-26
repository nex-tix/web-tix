<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="app-content">
    <div class="container-fluid px-0">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-3 border-b border-slate-200 gap-3">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Cetak Ulang Tiket</h2>
                <p class="text-sm text-slate-500 mt-0.5">Cari invoice dan cetak ulang tiket yang masih aktif serta berlaku hari ini.</p>
            </div>
            <a href="<?= base_url('admin/pos') ?>" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 transition">
                <i class="bi bi-arrow-left mr-2"></i> Kembali ke POS
            </a>
        </div>

        <?php if (session()->get('error')): ?>
            <div class="mb-4 p-3 rounded-lg border border-rose-200 bg-rose-50 text-sm text-rose-800">
                <?= esc(session()->get('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->get('success')): ?>
            <div class="mb-4 p-3 rounded-lg border border-emerald-200 bg-emerald-50 text-sm text-emerald-800">
                <?= esc(session()->get('success')) ?>
            </div>
        <?php endif; ?>

        <section class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-6">
            <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                <div class="p-1.5 rounded-md bg-brand-charcoal text-white">
                    <i class="bi bi-search w-4 h-4"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Cari Transaksi</h3>
            </div>
            <form method="GET" action="<?= base_url('admin/pos/reprint') ?>" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="invoice_code">Kode Invoice</label>
                    <input class="input-design w-full mb-2 text-black text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2.5" id="invoice_code" name="invoice_code" value="<?= esc($search) ?>" placeholder="Contoh: INV-20261008-ABC123" type="text" autocomplete="off" />
                    <div class="d-flex justify-end flex-column">
                        <div class="flex items-center gap-2">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-brand-red hover:bg-brand-redHover text-white font-bold text-sm shadow-sm transition">
                                <i class="bi bi-search"></i> Cari Invoice
                            </button>
                            <a href="<?= base_url('admin/pos/reprint') ?>" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 font-bold text-sm shadow-sm transition">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                        <p class="text-xs text-slate-500 mt-1.5">Hanya tiket aktif yang berlaku hari ini dapat dicetak ulang.</p>
                    </div>
                    
                </div>
            </form>
        </section>

        <?php if ($error): ?>
            <div class="mb-4 p-3 rounded-lg border border-amber-200 bg-amber-50 text-sm text-amber-800">
                <i class="bi bi-exclamation-triangle mr-1"></i> <?= esc($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($tickets)): ?>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Hasil Pencarian</h3>
                    <p class="text-xs text-slate-500 mt-1">Invoice <span class="font-mono font-semibold text-slate-700"><?= esc($transaction['invoice_code']) ?></span> · <?= count($tickets) ?> tiket tersedia untuk cetak ulang</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                <?php foreach ($tickets as $t): ?>
                    <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-brand-charcoal text-white px-5 py-3.5 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-slate-300">Kode Tiket</p>
                                <p class="font-mono font-bold text-sm tracking-wide mt-0.5 break-all"><?= esc($t['ticket_code']) ?></p>
                            </div>
                            <span class="shrink-0 text-[10px] font-bold uppercase px-2.5 py-1 rounded-full <?= $t['ticket_category'] === 'group' ? 'bg-emerald-500 text-white' : 'bg-brand-gold text-slate-900' ?>">
                                <?= esc($t['ticket_category'] === 'group' ? 'Rombongan' : 'Individual') ?>
                            </span>
                        </div>

                        <div class="p-5">
                            <div class="grid grid-cols-2 gap-3 mb-5">
                                <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500">Jumlah Tamu</p>
                                    <p class="font-bold text-slate-800 mt-1"><?= $t['total_visitors'] ?> orang</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 border border-slate-100 p-3">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500">Tanggal Berlaku</p>
                                    <p class="font-bold text-slate-800 mt-1"><?= esc($t['valid_date']) ?></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-4 text-xs text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Tiket aktif · <?= $t['ticket_category'] === 'group' ? '1 QR untuk seluruh rombongan' : '1 QR untuk 1 orang' ?></span>
                            </div>

                            <form method="POST" action="<?= base_url("admin/pos/reprint/{$t['id']}") ?>" class="border-t border-slate-100 pt-4">
                                <?= csrf_field() ?>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="reprint_reason_<?= $t['id'] ?>">Alasan Cetak Ulang <span class="text-brand-red">*</span></label>
                                <textarea class="w-full text-sm text-black rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2 mb-3" id="reprint_reason_<?= $t['id'] ?>" name="reprint_reason" rows="2" maxlength="255" required placeholder="Contoh: tiket rusak atau hasil cetak tidak terbaca"></textarea>
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-brand-red hover:bg-brand-redHover text-white font-extrabold text-sm tracking-wide shadow-sm transition">
                                    <i class="bi bi-printer"></i> CETAK ULANG TIKET
                                </button>
                            </form>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php elseif ($search === ''): ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-brand-charcoal text-brand-gold flex items-center justify-center text-xl">
                    <i class="bi bi-printer"></i>
                </div>
                <h3 class="font-bold text-slate-800 mt-4">Cari invoice untuk memulai</h3>
                <p class="text-sm text-slate-500 mt-1">Tiket individual tampil satu kartu per QR. Tiket rombongan memakai satu QR untuk seluruh grup.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>