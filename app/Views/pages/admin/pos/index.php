<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="csrf-name" content="<?= csrf_token() ?>">

<main class="flex-1 overflow-y-auto p-6" data-purpose="pos-workspace">
    <div id="pos-alert" class="hidden mb-4 p-3 rounded-lg border text-sm"></div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">POS Loket</h2>
            <p class="text-sm text-slate-500 mt-0.5">Input transaksi tiket pengunjung &amp; cetak tiket fisik pertunjukan.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-gold/20 text-yellow-800 border border-brand-gold/30">
                <svg class="w-3.5 h-3.5 mr-1 text-yellow-700" fill="currentColor" viewbox="0 0 20 20">
                    <path clip-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" fill-rule="evenodd"></path>
                </svg>
                Sesi Sore (18:00 WITA)
            </span>
            <button class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 active:bg-slate-100 transition" onclick="resetTransaction()" type="button">
                <svg class="w-3.5 h-3.5 mr-1 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                Clear Form
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7 space-y-6">
            <section class="bg-white rounded-xl shadow-sm border border-slate-200 p-5" data-purpose="visitor-form-card">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 rounded-md bg-brand-charcoal text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">1. Data Pengunjung</h3>
                    </div>
                    <span class="text-xs text-brand-red font-medium">* Wajib diisi perwakilan</span>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-name">
                            Nama Pengunjung / Perwakilan <span class="text-brand-red">*</span>
                        </label>
                        <input class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-name" placeholder="Contoh: Budi Santoso / John Doe" type="text" />
                        <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="customer_name"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kategori Asal Pengunjung <span class="text-brand-red">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3" data-purpose="origin-category-toggle">
                            <button class="flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm" id="btn-origin-domestik" onclick="setOrigin('domestik')" type="button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Domestik (WNI)
                            </button>
                            <button class="flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all" id="btn-origin-mancanegara" onclick="setOrigin('mancanegara')" type="button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                Internasional (WNA)
                            </button>
                        </div>
                        <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="origin_type"></p>
                    </div>
                    <div class="space-y-1" id="field-domestik">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-city">
                            Kota / Kabupaten Asal
                        </label>
                        <input class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-city" placeholder="Contoh: Jakarta Selatan, Surabaya, Denpasar" type="text" />
                        <p class="field-error hidden text-xs text-rose-600" data-error="origin"></p>
                    </div>
                    <div class="space-y-1 hidden" id="field-mancanegara">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-country">
                            Negara Asal (Country)
                        </label>
                        <select class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-country">
                            <option value="Australia">Australia</option>
                            <option value="France">France</option>
                            <option value="India">India</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Germany">Germany</option>
                            <option value="Japan">Japan</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Lainnya">Lainnya / Other</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-phone">
                                No. WhatsApp / HP
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-semibold text-slate-500">+62</span>
                                <input class="w-full pl-12 text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition py-2" id="visitor-phone" placeholder="81234567890" type="tel" />
                            </div>
                            <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="phone"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-address">
                                Hotel / Alamat di Bali
                            </label>
                            <input class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-address" placeholder="Contoh: Hotel Padang-Padang / Villa Jimbaran" type="text" />
                            <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="address"></p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white rounded-xl shadow-sm border border-slate-200 p-5" data-purpose="ticket-selection-card">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 rounded-md bg-brand-charcoal text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">2. Tiket &amp; Mode Pembelian</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 font-medium">Tgl Tiket:</span>
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-md text-xs font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            <?= date('j M Y') ?> (HARI INI)
                        </span>
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mode Pembelian Tiket</label>
                    <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl gap-1 border border-slate-200">
                        <button class="py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2" id="btn-mode-individual" onclick="setMode('individual')" type="button">
                            <svg class="w-4 h-4 text-brand-red" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Individual (1 - 4 Orang)
                        </button>
                        <button class="py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2" id="btn-mode-rombongan" onclick="setMode('rombongan')" type="button">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Rombongan (5 - 15 Orang)
                        </button>
                    </div>
                    <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="ticket_category"></p>
                    <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="ticket_items"></p>
                </div>
                <div class="p-3 mb-5 rounded-lg border text-xs flex items-center justify-between bg-emerald-50 border-emerald-200 text-emerald-900" id="mode-helper-box">
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewbox="0 0 20 20">
                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                        </svg><span class="text-emerald-900 font-medium" id="mode-desc">Mode Individual: Maksimal 4 tiket per transaksi.</span></div>
                    <div class="font-bold px-2.5 py-1 rounded bg-white text-emerald-800 shadow-xs border border-emerald-200" id="visitor-count-badge">Total: <span class="text-emerald-700" id="total-qty-display">0</span> / 4 Orang</div>
                </div>
                <div class="mb-4 p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2 hidden" id="group-warning"></div>
                <div class="space-y-3" id="tickets-container"></div>
            </section>
        </div>
        <div class="lg:col-span-5 space-y-6">
            <section class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden" data-purpose="checkout-card">
                <div class="bg-brand-charcoal text-white px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <div>
                            <h3 class="font-bold text-sm tracking-wide">Ringkasan Transaksi</h3>
                            <p class="text-[11px] text-slate-300">No. Trx: <span class="font-mono text-brand-gold" id="trx-id">-</span></p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-white/20 text-white tracking-wider" id="summary-mode-badge">INDIVIDUAL</span>
                </div>
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-xs flex justify-between items-center">
                    <div>
                        <span class="text-slate-400">Pengunjung:</span>
                        <span class="font-bold text-slate-800 ml-1" id="summary-visitor-name">- Belum Diisi -</span>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-400">Kategori:</span>
                        <span class="font-bold text-slate-800 ml-1" id="summary-visitor-origin">Domestik (0 Orang)</span>
                    </div>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="space-y-2 min-h-[70px]" id="receipt-items-list">
                        <p class="text-xs text-slate-400 italic text-center py-4">Belum ada tiket yang dipilih.</p>
                    </div>
                    <div class="border-t border-dashed border-slate-300 my-3 pt-3 space-y-1.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-800" id="summary-subtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-emerald-600">
                            <span>Diskon</span>
                            <span class="font-semibold" id="summary-discount">- Rp 0</span>
                        </div>
                        <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                            <span>TOTAL TAGIHAN</span>
                            <span class="text-brand-red text-lg" id="summary-total">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div class="p-5 bg-slate-50/80 border-t border-slate-200" data-purpose="payment-methods">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                    <p class="field-error hidden text-xs text-rose-600 mb-2" data-error="payment_method"></p>
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <button class="py-2 px-3 text-xs font-bold rounded-lg border-2 border-brand-red bg-brand-red text-white flex items-center justify-center gap-1.5 transition" id="pay-method-cash" onclick="setPaymentMethod('cash')" type="button">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Tunai (Cash)
                        </button>
                        <button class="py-2 px-3 text-xs font-bold rounded-lg border-2 border-slate-200 bg-white text-slate-700 hover:border-slate-300 flex items-center justify-center gap-1.5 transition" id="pay-method-edc" onclick="setPaymentMethod('edc')" type="button">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Debit
                        </button>
                    </div>
                    <div class="space-y-3" id="cash-container">
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-500 mb-1.5">Preset Cepat Uang Diterima:</span>
                            <div class="grid grid-cols-3 gap-2">
                                <button class="py-1.5 px-2 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 active:bg-slate-200" onclick="setExactCash()" type="button">Uang Pas</button>
                                <button class="py-1.5 px-2 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 active:bg-slate-200" onclick="setPresetCash(300000)" type="button">300.000</button>
                                <button class="py-1.5 px-2 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 active:bg-slate-200" onclick="setPresetCash(500000)" type="button">500.000</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="cash-received">Nominal Uang Diterima</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-500">Rp</span>
                                <input class="w-full pl-10 text-base font-bold rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition py-2 text-slate-900" id="cash-received" min="0" placeholder="0" step="5000" type="number" />
                            </div>
                            <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="cash_paid"></p>
                        </div>
                        <div class="p-3 rounded-lg border text-xs bg-slate-50 border-slate-200 text-slate-600 flex justify-between items-center" id="change-box">Kembalian: Rp 0</div>
                    </div>
                    <div class="space-y-3 hidden" id="edc-container">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="edc-ref">Nomor Approval / Referensi EDC <span class="text-brand-red">*</span></label>
                            <input class="w-full text-sm font-mono rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="edc-ref" placeholder="Contoh: EDC-BCA-982314" type="text" />
                            <p class="field-error hidden mt-1 text-xs text-rose-600" data-error="payment_reference"></p>
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path></svg>
                            <span>Terminal EDC siap swipe/dip kartu ATM/Debit/QRIS</span>
                        </div>
                    </div>
                    <div class="mt-6 space-y-2">
                        <button class="w-full py-3.5 px-4 rounded-xl bg-brand-red hover:bg-brand-redHover active:scale-[0.99] text-white font-extrabold text-sm tracking-wide shadow-lg shadow-brand-red/30 transition flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed" id="btn-submit-order" onclick="processOrder()" type="button">
                            <svg id="btn-submit-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span id="btn-submit-label">BAYAR &amp; CETAK TIKET</span>
                        </button>
                        <button class="w-full py-2 px-4 rounded-lg bg-transparent hover:bg-slate-200/60 text-slate-600 font-semibold text-xs transition" onclick="resetTransaction()" type="button">Batal / Reset Transaksi</button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<script>
    const ticketTypes = <?= json_encode($ticketTypes) ?>;
    const ticketTypesById = Object.fromEntries(ticketTypes.map(t => [t.id, t]));
    const csrfName = document.querySelector('meta[name="csrf-name"]')?.content || 'csrf_test_name';
    const posState = {
        origin: 'domestik',
        mode: 'individual',
        payment: 'cash',
        quantities: {}
    };
    ticketTypes.forEach(t => posState.quantities[t.id] = 0);

    const limits = { individual: { min: 1, max: 4 }, rombongan: { min: 5, max: 15 } };
    const posStateCategory = () => posState.mode === 'rombongan' ? 'group' : 'individual';
    const rupiah = value => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
    const totalQty = () => Object.values(posState.quantities).reduce((a, b) => a + b, 0);
    const totalPrice = () => {
        let sum = 0;
        for (const t of ticketTypes) {
            if (t.ticket_category !== posStateCategory()) continue;
            const qty = posState.quantities[t.id] || 0;
            if (!qty) continue;
            const gross = qty * parseFloat(t.price);
            const disc = gross * parseFloat(t.discount_percent || 0) / 100;
            sum += gross - disc;
        }
        return Math.round(sum);
    };

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function showAlert(message, type = 'error') {
        const el = document.getElementById('pos-alert');
        el.textContent = message;
        el.className = 'mb-4 p-3 rounded-lg border text-sm ' + (type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-700');
        el.classList.remove('hidden');
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideAlert() {
        document.getElementById('pos-alert').classList.add('hidden');
    }

    function clearFieldErrors() {
        document.querySelectorAll('.field-error').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        document.querySelectorAll('.border-rose-400').forEach(el => el.classList.remove('border-rose-400', 'ring-rose-400'));
        hideAlert();
    }

    function showFieldErrors(errors) {
        const map = {
            customer_name: 'customer_name',
            origin_type: 'origin_type',
            origin: 'origin',
            phone: 'phone',
            address: 'address',
            ticket_category: 'ticket_category',
            payment_method: 'payment_method',
            ticket_items: 'ticket_items'
        };
        let generalMessages = [];
        for (const [field, msg] of Object.entries(errors)) {
            const key = map[field] || field;
            const el = document.querySelector(`[data-error="${key}"]`);
            if (el) {
                el.textContent = msg;
                el.classList.remove('hidden');
                const inputMap = { customer_name: 'visitor-name', origin: posState.origin === 'domestik' ? 'visitor-city' : 'visitor-country', phone: 'visitor-phone', address: 'visitor-address' };
                if (inputMap[key]) {
                    const inp = document.getElementById(inputMap[key]);
                    if (inp) inp.classList.add('border-rose-400', 'ring-rose-400');
                }
            } else {
                generalMessages.push(msg);
            }
        }
        if (generalMessages.length) showAlert(generalMessages.join(' '));
    }

    function setFieldError(key, message) {
        const el = document.querySelector(`[data-error="${key}"]`);
        if (el) {
            el.textContent = message;
            el.classList.remove('hidden');
        } else {
            showAlert(message);
        }
    }

    function setOrigin(origin) {
        posState.origin = origin;
        document.getElementById('field-domestik').classList.toggle('hidden', origin !== 'domestik');
        document.getElementById('field-mancanegara').classList.toggle('hidden', origin !== 'mancanegara');
        document.getElementById('btn-origin-domestik').className = origin === 'domestik' ? 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm' : 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all';
        document.getElementById('btn-origin-mancanegara').className = origin === 'mancanegara' ? 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm' : 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all';
        document.getElementById('summary-visitor-origin').textContent = (origin === 'domestik' ? 'Domestik (WNI)' : 'Mancanegara (WNA)') + ` (${totalQty()} Orang)`;
        clearFieldErrors();
    }

    function setMode(mode) {
        posState.mode = mode;
        Object.keys(posState.quantities).forEach(k => posState.quantities[k] = 0);
        const individual = mode === 'individual';
        document.getElementById('btn-mode-individual').className = individual ? 'py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2' : 'py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2';
        document.getElementById('btn-mode-rombongan').className = !individual ? 'py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2' : 'py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2';
        document.getElementById('mode-desc').textContent = individual ? 'Mode Individual: Maksimal 4 tiket per transaksi.' : 'Khusus Rombongan/Group: Minimal 5 orang, Maksimal 15 orang.';
        document.getElementById('summary-mode-badge').textContent = individual ? 'INDIVIDUAL' : 'ROMBONGAN';
        document.getElementById('summary-mode-badge').className = individual ? 'text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-white/20 text-white tracking-wider' : 'text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-emerald-500 text-white tracking-wider';
        document.getElementById('visitor-count-badge').innerHTML = `Total: <span class="text-emerald-700" id="total-qty-display">0</span> / ${limits[mode].max} Orang`;
        document.getElementById('group-warning').classList.add('hidden');
        clearFieldErrors();
        renderTickets();
        renderCart();
    }

    function renderTickets() {
        const container = document.getElementById('tickets-container');
        const cat = posStateCategory();
        const list = ticketTypes.filter(t => t.ticket_category === cat);
        if (!list.length) {
            container.innerHTML = `<p class="text-xs text-slate-400 italic text-center py-6 border border-dashed border-slate-200 rounded-lg">Tidak ada jenis tiket untuk kategori ${cat}.</p>`;
            return;
        }
        container.innerHTML = list.map(t => {
            const qty = posState.quantities[t.id] || 0;
            const price = parseFloat(t.price);
            const disc = parseFloat(t.discount_percent || 0);
            const hasDisc = disc > 0;
            const discounted = hasDisc ? price - price * disc / 100 : price;
            return `<div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 hover:border-slate-300 transition bg-slate-50/50">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2"><span class="font-bold text-sm text-slate-800">${t.name}</span>${hasDisc ? `<span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">DISKON ${disc}%</span>` : ''}</div>
                            <p class="text-xs text-slate-500">${t.ticket_category === 'group' ? 'Rombongan' : 'Individual'} &middot; ${hasDisc ? `Diskon ${disc}%` : 'Harga normal'}</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-sm font-extrabold text-brand-red">${rupiah(discounted)} <span class="text-xs font-normal text-slate-500">/ orang</span></p>${hasDisc ? `<span class="text-xs text-slate-400 line-through">${rupiah(price)}</span>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-2"><button class="w-8 h-8 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 active:bg-slate-200 font-bold flex items-center justify-center transition shadow-xs" onclick="updateQty(${t.id}, -1)" type="button">-</button><span class="w-10 text-center text-base font-extrabold text-slate-800">${qty}</span><button class="w-8 h-8 rounded-lg bg-brand-charcoal text-white hover:bg-slate-800 active:bg-slate-900 font-bold flex items-center justify-center transition shadow-xs" onclick="updateQty(${t.id}, 1)" type="button">+</button></div>
                    </div>`;
        }).join('');
    }

    function updateQty(ticketTypeId, delta) {
        const next = (posState.quantities[ticketTypeId] || 0) + delta;
        if (next < 0) return;
        if (delta > 0 && totalQty() + 1 > limits[posState.mode].max) {
            showAlert(`Maksimal ${limits[posState.mode].max} tiket untuk mode ${posState.mode}.`, 'error');
            setTimeout(hideAlert, 2500);
            return;
        }
        posState.quantities[ticketTypeId] = next;
        clearFieldErrors();
        renderTickets();
        renderCart();
    }

    function renderCart() {
        const total = totalQty();
        const cat = posStateCategory();
        const badge = document.getElementById('total-qty-display');
        if (badge) badge.textContent = total;
        const countBadge = document.getElementById('visitor-count-badge');
        if (countBadge && !countBadge.querySelector('#total-qty-display')) {
            countBadge.innerHTML = `Total: <span class="text-emerald-700" id="total-qty-display">${total}</span> / ${limits[posState.mode].max} Orang`;
        }
        document.getElementById('summary-visitor-origin').textContent = `${posState.origin === 'domestik' ? 'Domestik (WNI)' : 'Mancanegara (WNA)'} (${total} Orang)`;
        const itemsEl = document.getElementById('receipt-items-list');
        itemsEl.innerHTML = '';
        let hasItems = false;
        for (const t of ticketTypes) {
            if (t.ticket_category !== cat) continue;
            const qty = posState.quantities[t.id] || 0;
            if (!qty) continue;
            hasItems = true;
            const price = parseFloat(t.price);
            const disc = parseFloat(t.discount_percent || 0);
            const unit = disc > 0 ? price - price * disc / 100 : price;
            itemsEl.innerHTML += `<div class="flex justify-between items-center text-xs"><div><span class="font-bold text-slate-800">${t.name}</span><span class="text-slate-400 ml-1">× ${qty}</span><p class="text-[10px] text-slate-400">@ ${rupiah(unit)}${disc > 0 ? ` (reg ${rupiah(price)})` : ''}</p></div><span class="font-bold text-slate-800">${rupiah(qty * unit)}</span></div>`;
        }
        if (!hasItems) itemsEl.innerHTML = '<p class="text-xs text-slate-400 italic text-center py-4">Belum ada tiket yang dipilih.</p>';

        let subtotal = 0, totalAmt = 0;
        for (const t of ticketTypes) {
            if (t.ticket_category !== cat) continue;
            const qty = posState.quantities[t.id] || 0;
            if (!qty) continue;
            const price = parseFloat(t.price);
            const disc = parseFloat(t.discount_percent || 0);
            const gross = qty * price;
            const discounted = disc > 0 ? gross - gross * disc / 100 : gross;
            subtotal += gross;
            totalAmt += discounted;
        }
        subtotal = Math.round(subtotal);
        totalAmt = Math.round(totalAmt);
        document.getElementById('summary-subtotal').textContent = rupiah(subtotal);
        document.getElementById('summary-discount').textContent = `- ${rupiah(subtotal - totalAmt)}`;
        document.getElementById('summary-total').textContent = rupiah(totalAmt);

        const warn = document.getElementById('group-warning');
        if (posState.mode === 'rombongan') {
            if (total > 0 && total < limits.rombongan.min) {
                warn.classList.remove('hidden');
                warn.className = 'mb-4 p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2';
                warn.innerHTML = `<svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span>Perlu <strong>${limits.rombongan.min - total} tiket lagi</strong> untuk minimal rombongan.</span>`;
            } else if (total >= limits.rombongan.min) {
                warn.classList.remove('hidden');
                warn.className = 'mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2';
                warn.innerHTML = `<svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"/></svg><span>Syarat rombongan terpenuhi (${total} orang). Diskon aktif!</span>`;
            } else {
                warn.classList.add('hidden');
            }
        } else {
            warn.classList.add('hidden');
        }

        calculateChange();
    }

    function setPaymentMethod(method) {
        posState.payment = method;
        document.getElementById('cash-container').classList.toggle('hidden', method !== 'cash');
        document.getElementById('edc-container').classList.toggle('hidden', method !== 'edc');
        const btnCash = document.getElementById('pay-method-cash');
        const btnEdc = document.getElementById('pay-method-edc');
        if (method === 'cash') {
            btnCash.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-brand-red bg-brand-red text-white flex items-center justify-center gap-1.5 transition';
            btnEdc.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-slate-200 bg-white text-slate-700 hover:border-slate-300 flex items-center justify-center gap-1.5 transition';
        } else {
            btnEdc.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-brand-red bg-brand-red text-white flex items-center justify-center gap-1.5 transition';
            btnCash.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-slate-200 bg-white text-slate-700 hover:border-slate-300 flex items-center justify-center gap-1.5 transition';
        }
        clearFieldErrors();
    }

    function calculateChange() {
        const box = document.getElementById('change-box');
        const received = Number(document.getElementById('cash-received').value) || 0;
        const diff = received - totalPrice();
        if (!totalPrice()) {
            box.textContent = 'Kembalian: Rp 0';
            box.className = 'p-3 rounded-lg border text-xs flex justify-between items-center bg-slate-50 border-slate-200 text-slate-600';
            return;
        }
        box.textContent = diff >= 0 ? `Kembalian: ${rupiah(diff)}` : `Uang kurang: ${rupiah(Math.abs(diff))}`;
        box.className = `p-3 rounded-lg border text-xs flex justify-between items-center ${diff >= 0 ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-800'}`;
    }

    function setExactCash() {
        document.getElementById('cash-received').value = totalPrice();
        calculateChange();
    }

    function setPresetCash(value) {
        document.getElementById('cash-received').value = value;
        calculateChange();
    }

    function resetTransaction() {
        document.getElementById('visitor-name').value = '';
        document.getElementById('visitor-city').value = '';
        document.getElementById('visitor-phone').value = '';
        document.getElementById('visitor-address').value = '';
        document.getElementById('visitor-country').value = 'Australia';
        document.getElementById('cash-received').value = '';
        document.getElementById('edc-ref').value = '';
        Object.keys(posState.quantities).forEach(k => posState.quantities[k] = 0);
        document.getElementById('summary-visitor-name').textContent = '- Belum Diisi -';
        document.getElementById('trx-id').textContent = '-';
        clearFieldErrors();
        renderTickets();
        renderCart();
    }

    function setSubmitLoading(loading) {
        const btn = document.getElementById('btn-submit-order');
        const spinner = document.getElementById('btn-submit-spinner');
        const label = document.getElementById('btn-submit-label');
        btn.disabled = loading;
        spinner.classList.toggle('hidden', !loading);
        label.textContent = loading ? 'MEMPROSES...' : 'BAYAR & CETAK TIKET';
    }

    async function processOrder() {
        clearFieldErrors();

        const customerName = document.getElementById('visitor-name').value.trim();
        if (!customerName) { setFieldError('customer_name', 'Nama pengunjung wajib diisi.'); document.getElementById('visitor-name').focus(); return; }
        if (customerName.length < 3) { setFieldError('customer_name', 'Minimal 3 karakter.'); return; }

        const qty = totalQty();
        const lim = limits[posState.mode];
        if (qty < lim.min) { setFieldError('ticket_items', `Minimal ${lim.min} tiket untuk mode ${posState.mode}.`); return; }
        if (qty > lim.max) { setFieldError('ticket_items', `Maksimal ${lim.max} tiket untuk mode ${posState.mode}.`); return; }

        const originType = posState.origin === 'domestik' ? 'domestic' : 'international';
        const origin = posState.origin === 'domestik' ? document.getElementById('visitor-city').value.trim() : document.getElementById('visitor-country').value;
        if (!origin) { setFieldError('origin', posState.origin === 'domestik' ? 'Kota asal wajib diisi.' : 'Negara asal wajib dipilih.'); return; }

        const phoneRaw = document.getElementById('visitor-phone').value.trim();
        const address = document.getElementById('visitor-address').value.trim();
        const ticketCategory = posStateCategory();
        const paymentMethod = posState.payment === 'edc' ? 'debit' : 'cash';

        if (paymentMethod === 'cash') {
            const cashVal = document.getElementById('cash-received').value;
            if (cashVal === '' || cashVal === null) { setFieldError('cash_paid', 'Nominal uang diterima wajib diisi.'); return; }
            if (Number(cashVal) < totalPrice()) { setFieldError('cash_paid', `Uang kurang ${rupiah(totalPrice() - Number(cashVal))}.`); return; }
        }
        if (paymentMethod === 'debit' && !document.getElementById('edc-ref').value.trim()) {
            setFieldError('payment_reference', 'Nomor referensi EDC wajib diisi.');
            return;
        }

        const ticketItems = [];
        for (const t of ticketTypes) {
            if (t.ticket_category !== ticketCategory) continue;
            const q = posState.quantities[t.id] || 0;
            if (q > 0) ticketItems.push({ ticket_type_id: t.id, qty: q });
        }
        if (!ticketItems.length) { setFieldError('ticket_items', 'Pilih minimal satu jenis tiket.'); return; }

        const payload = new URLSearchParams();
        payload.append(csrfName, getCsrfToken());
        payload.append('customer_name', customerName);
        payload.append('origin_type', originType);
        payload.append('origin', origin);
        payload.append('phone', phoneRaw);
        payload.append('address', address);
        payload.append('ticket_category', ticketCategory);
        payload.append('payment_method', paymentMethod);
        if (paymentMethod === 'cash') payload.append('cash_paid', document.getElementById('cash-received').value);
        if (paymentMethod === 'debit') payload.append('payment_reference', document.getElementById('edc-ref').value.trim());
        ticketItems.forEach((item, idx) => {
            payload.append(`ticket_items[${idx}][ticket_type_id]`, item.ticket_type_id);
            payload.append(`ticket_items[${idx}][qty]`, item.qty);
        });

        setSubmitLoading(true);
        try {
            const res = await fetch("<?= base_url('admin/pos/store') ?>", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: payload
            });

            const csrfHeader = res.headers.get('X-CSRF-TOKEN') || res.headers.get('X-CSRF-HASH');
            if (csrfHeader) document.querySelector('meta[name="csrf-token"]').content = csrfHeader;

            let data = null;
            try { data = await res.json(); } catch (_) {}

            const newToken = data && (data.csrf_token || data.csrf_hash || data.token);
            if (newToken) document.querySelector('meta[name="csrf-token"]').content = newToken;

            if (res.ok && data && data.success) {
                const inv = data.data?.transaction?.invoice_code || data.data?.ticket?.ticket_code || '-';
                document.getElementById('trx-id').textContent = inv;
                showAlert(`Transaksi berhasil! Invoice: ${inv}. Mengalihkan...`, 'success');
                setTimeout(() => { window.location.href = "<?= base_url('admin/pos') ?>?success=" + encodeURIComponent(inv); }, 900);
                return;
            }

            if (res.status === 422 && data && data.errors) {
                showFieldErrors(data.errors);
                const first = document.querySelector('.field-error:not(.hidden)');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            if (data && data.errors) {
                showFieldErrors(data.errors);
                return;
            }

            showAlert(data?.message || `Gagal memproses transaksi (HTTP ${res.status}).`, 'error');
        } catch (err) {
            showAlert('Gagal terhubung ke server. Cek koneksi dan coba lagi.', 'error');
        } finally {
            setSubmitLoading(false);
        }
    }

    document.getElementById('visitor-name').addEventListener('input', e => {
        document.getElementById('summary-visitor-name').textContent = e.target.value || '- Belum Diisi -';
        const el = document.querySelector('[data-error="customer_name"]');
        if (el) { el.textContent = ''; el.classList.add('hidden'); }
        e.target.classList.remove('border-rose-400', 'ring-rose-400');
    });
    ['visitor-city', 'visitor-phone', 'visitor-address', 'cash-received', 'edc-ref'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', clearFieldErrors);
    });
    document.getElementById('visitor-country').addEventListener('change', clearFieldErrors);

    (function handleSuccessParam() {
        const inv = new URLSearchParams(window.location.search).get('success');
        if (inv) {
            document.getElementById('trx-id').textContent = inv;
            showAlert(`Transaksi terakhir berhasil! Invoice: ${inv}`, 'success');
        }
    })();

    setMode('individual');
    setOrigin('domestik');
    setPaymentMethod('cash');
    renderTickets();
    renderCart();
</script>
<?= $this->endSection() ?>

