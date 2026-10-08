<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<main class="flex-1 overflow-y-auto p-6" data-purpose="pos-workspace">
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
            <button class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 active:bg-slate-100 transition" onclick="resetTransaction()">
                <svg class="w-3.5 h-3.5 mr-1 text-slate-500" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                Clear Form
            </button>
        </div>
    </div>
    <!-- POS Two-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- LEFT COLUMN: Visitor Data & Ticket Controls (~62% = 7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Card 1: Data Pengunjung -->
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
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-name">
                            Nama Pengunjung / Perwakilan <span class="text-brand-red">*</span>
                        </label>
                        <input class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-name" placeholder="Contoh: Budi Santoso / John Doe" required="" type="text" value="I Made Suartana (Tour Guide Bali Mandiri)" />
                    </div>
                    <!-- Asal Pengunjung Toggle Buttons -->
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
                    </div>
                    <!-- Dynamic Reactive Fields: Domestik vs Mancanegara -->
                    <div class="space-y-1" id="field-domestik">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-city">
                            Kota / Kabupaten Asal
                        </label>
                        <input class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-city" placeholder="Contoh: Jakarta Selatan, Surabaya, Denpasar" type="text" value="Denpasar, Bali" />
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
                    <!-- Kontak & Alamat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-phone">
                                No. WhatsApp / HP
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-semibold text-slate-500">+62</span>
                                <input class="w-full pl-12 text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition py-2" id="visitor-phone" placeholder="81234567890" type="tel" value="81234567899" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="visitor-address">
                                Hotel / Alamat di Bali
                            </label>
                            <input class="w-full text-sm rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="visitor-address" placeholder="Contoh: Hotel Padang-Padang / Villa Jimbaran" type="text" value="Bali Mandiri Tours &amp; Travel" />
                        </div>
                    </div>
                </div>
            </section>
            <!-- Card 2: Informasi Tiket & Mode Pembelian -->
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
                    <!-- Read Only Date Badge (Required Constraint) -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 font-medium">Tgl Tiket:</span>
                        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-md text-xs font-bold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            6 Okt 2026 (HARI INI)
                        </span>
                    </div>
                </div>
                <!-- Segmented Control Mode Pembelian: Individual vs Rombongan -->
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mode Pembelian Tiket</label>
                    <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl gap-1 border border-slate-200">
                        <button class="py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2" id="btn-mode-individual" onclick="setMode('individual')" type="button">
                            <svg class="w-4 h-4 text-brand-red" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Individual (1 - 4 Orang)
                        </button>
                        <button class="py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2" id="btn-mode-rombongan" onclick="setMode('rombongan')" type="button">
                            <svg class="w-4 h-4 text-brand-gold" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Rombongan (5 - 15 Orang)
                        </button>
                    </div>
                </div>
                <!-- Status Banner & Limits -->
                <div class="p-3 mb-5 rounded-lg border text-xs flex items-center justify-between bg-emerald-50 border-emerald-200 text-emerald-900" id="mode-helper-box">
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewbox="0 0 20 20">
                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                        </svg><span class="text-emerald-900 font-medium" id="mode-desc">Mode Rombongan: Minimal 5 tiket dan maksimal 15 tiket per transaksi (Diskon Rombongan 5% berlaku).</span></div>
                    <div class="font-bold px-2.5 py-1 rounded bg-white text-emerald-800 shadow-xs border border-emerald-200" id="visitor-count-badge">Total: <span class="text-emerald-700" id="total-qty-display">8</span> / 15 Orang</div>
                </div>
                <!-- Warning Alert for Group Minimum Requirement (Hidden by default) -->
                <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between" id="group-warning">
                    <div class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewbox="0 0 20 20">
                            <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                        </svg><span>Syarat rombongan terpenuhi (8 orang). <strong>Diskon rombongan 5% aktif</strong>.</span></div><span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wide">Hemat Rp 35.500</span>
                </div>
                <!-- Ticket Types Selection List -->
                <div class="space-y-3" id="tickets-container">
                    <div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 hover:border-slate-300 transition bg-slate-50/50">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2"><span class="font-bold text-sm text-slate-800" id="label-adult">Dewasa / Adult (Rombongan)</span><span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800" id="badge-discount-adult">DISKON 5%</span></div>
                            <p class="text-xs text-slate-500">Usia &gt; 10 tahun / Wisatawan Reguler Rombongan</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-sm font-extrabold text-brand-red" id="price-adult">Rp 95.000 <span class="text-xs font-normal text-slate-500">/ orang</span></p><span class="text-xs text-slate-400 line-through">Rp 100.000</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2"><button class="w-8 h-8 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 active:bg-slate-200 font-bold flex items-center justify-center transition shadow-xs" onclick="updateQty('adult', -1)" type="button">-</button><span class="w-10 text-center text-base font-extrabold text-slate-800" id="qty-adult">6</span><button class="w-8 h-8 rounded-lg bg-brand-charcoal text-white hover:bg-slate-800 active:bg-slate-900 font-bold flex items-center justify-center transition shadow-xs" onclick="updateQty('adult', 1)" type="button">+</button></div>
                    </div>
                    <div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 hover:border-slate-300 transition bg-slate-50/50">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2"><span class="font-bold text-sm text-slate-800" id="label-child">Anak-anak / Child (Rombongan)</span><span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800" id="badge-discount-child">DISKON 5%</span></div>
                            <p class="text-xs text-slate-500">Usia 3 - 10 tahun (Balita &lt; 3 thn gratis tanpa kursi)</p>
                            <div class="flex items-baseline gap-2">
                                <p class="text-sm font-extrabold text-brand-red" id="price-child">Rp 70.000 <span class="text-xs font-normal text-slate-500">/ orang</span></p><span class="text-xs text-slate-400 line-through">Rp 75.000</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2"><button class="w-8 h-8 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 active:bg-slate-200 font-bold flex items-center justify-center transition shadow-xs" onclick="updateQty('child', -1)" type="button">-</button><span class="w-10 text-center text-base font-extrabold text-slate-800" id="qty-child">2</span><button class="w-8 h-8 rounded-lg bg-brand-charcoal text-white hover:bg-slate-800 active:bg-slate-900 font-bold flex items-center justify-center transition shadow-xs" onclick="updateQty('child', 1)" type="button">+</button></div>
                    </div>
                </div>
            </section>
        </div>
        <!-- RIGHT COLUMN: Order Summary & Payment Gateway (~38% = 5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Card: Ringkasan Pesanan & Pembayaran -->
            <section class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden" data-purpose="checkout-card">
                <!-- Card Header -->
                <div class="bg-brand-charcoal text-white px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-brand-gold" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <div>
                            <h3 class="font-bold text-sm tracking-wide">Ringkasan Transaksi</h3>
                            <p class="text-[11px] text-slate-300">No. Trx: <span class="font-mono text-brand-gold" id="trx-id">KW-20261006-084</span></p>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-emerald-500 text-white tracking-wider" id="summary-mode-badge">ROMBONGAN</span>
                </div>
                <!-- Visitor Meta Snapshot -->
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 text-xs flex justify-between items-center">
                    <div>
                        <span class="text-slate-400">Pengunjung:</span>
                        <span class="font-bold text-slate-800 ml-1" id="summary-visitor-name">I Made Suartana (Tour Guide Bali Mandiri)</span>
                    </div>
                    <div class="text-right">
                        <span class="text-slate-400">Kategori:</span>
                        <span class="font-bold text-slate-800 ml-1" id="summary-visitor-origin">Domestik (8 Orang)</span>
                    </div>
                </div>
                <!-- Item Breakdown Table -->
                <div class="p-5 space-y-3 text-sm">
                    <div class="space-y-2 min-h-[70px]" id="receipt-items-list">
                        <div class="flex justify-between items-center text-xs pb-1 border-b border-slate-100">
                            <div><span class="font-bold text-slate-800">Dewasa (Rombongan)</span><span class="text-slate-400 ml-1">× 6</span>
                                <p class="text-[10px] text-slate-400">@ Rp 95.000 (Reguler Rp 100.000)</p>
                            </div><span class="font-bold text-slate-800">Rp 570.000</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <div><span class="font-bold text-slate-800">Anak-anak (Rombongan)</span><span class="text-slate-400 ml-1">× 2</span>
                                <p class="text-[10px] text-slate-400">@ Rp 70.000 (Reguler Rp 75.000)</p>
                            </div><span class="font-bold text-slate-800">Rp 140.000</span>
                        </div>
                    </div>
                    <!-- Perforation border aesthetic -->
                    <div class="border-t border-dashed border-slate-300 my-3 pt-3 space-y-1.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-800" id="summary-subtotal">Rp 710.000</span>
                        </div>
                        <div class="flex justify-between text-emerald-600">
                            <span>Diskon Rombongan</span>
                            <span class="font-semibold" id="summary-discount">- Rp 35.500</span>
                        </div>
                        <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                            <span>TOTAL TAGIHAN</span>
                            <span class="text-brand-red text-lg" id="summary-total">Rp 674.500</span>
                        </div>
                    </div>
                </div>
                <!-- Payment Section -->
                <div class="p-5 bg-slate-50/80 border-t border-slate-200" data-purpose="payment-methods">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                    <!-- Payment Mode Buttons -->
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
                    <!-- CASH CONTAINER -->
                    <div class="space-y-3" id="cash-container">
                        <!-- Quick Preset Cash Buttons -->
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-500 mb-1.5">Preset Cepat Uang Diterima:</span>
                            <div class="grid grid-cols-3 gap-2">
                                <button class="py-1.5 px-2 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 active:bg-slate-200" onclick="setExactCash()" type="button">
                                    Uang Pas
                                </button>
                                <button class="py-1.5 px-2 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 active:bg-slate-200" onclick="setPresetCash(300000)" type="button">
                                    300.000
                                </button>
                                <button class="py-1.5 px-2 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700 hover:bg-slate-100 active:bg-slate-200" onclick="setPresetCash(500000)" type="button">
                                    500.000
                                </button>
                            </div>
                        </div>
                        <!-- Cash Received Input -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="cash-received">
                                Nominal Uang Diterima
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-500">Rp</span>
                                <input class="w-full pl-10 text-base font-bold rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition py-2 text-slate-900" id="cash-received" min="0" oninput="calculateChange()" placeholder="0" step="5000" type="number" value="700000" />
                            </div>
                        </div>
                        <!-- Change Display Status -->
                        <div class="p-3 rounded-lg border text-xs bg-emerald-50 border-emerald-300 text-emerald-900 flex justify-between items-center" id="change-box">
                            <div class="flex items-center gap-1.5"><svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewbox="0 0 20 20">
                                    <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                                </svg><span class="font-bold">Kembalian:</span></div><span class="text-sm font-extrabold text-emerald-700">Rp 25.500</span>
                        </div>
                    </div>
                    <!-- EDC / DEBIT CONTAINER -->
                    <div class="space-y-3 hidden" id="edc-container">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="edc-ref">
                                Nomor Approval / Referensi EDC <span class="text-brand-red">*</span>
                            </label>
                            <input class="w-full text-sm font-mono rounded-lg border-slate-300 focus:border-brand-red focus:ring-brand-red transition px-3 py-2" id="edc-ref" placeholder="Contoh: EDC-BCA-982314" type="text" />
                        </div>
                        <div class="flex items-center gap-2 p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewbox="0 0 20 20">
                                <path clip-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" fill-rule="evenodd"></path>
                            </svg>
                            <span>Terminal EDC siap swipe/dip kartu ATM/Debit/QRIS</span>
                        </div>
                    </div>
                    <!-- Main CTA Action Buttons -->
                    <div class="mt-6 space-y-2">
                        <button class="w-full py-3.5 px-4 rounded-xl bg-brand-red hover:bg-brand-redHover active:scale-[0.99] text-white font-extrabold text-sm tracking-wide shadow-lg shadow-brand-red/30 transition flex items-center justify-center gap-2" id="btn-submit-order" onclick="processOrder()" type="button">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            BAYAR &amp; CETAK TIKET
                        </button>
                        <button class="w-full py-2 px-4 rounded-lg bg-transparent hover:bg-slate-200/60 text-slate-600 font-semibold text-xs transition" onclick="resetTransaction()" type="button">
                            Batal / Reset Transaksi
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<script>
    const posState = {
        origin: 'domestik',
        mode: 'individual',
        payment: 'cash',
        quantities: {
            adult: 0,
            child: 0
        },
        prices: {
            individual: {
                adult: 100000,
                child: 75000
            },
            rombongan: {
                adult: 95000,
                child: 70000
            }
        },
        limits: {
            individual: {
                min: 1,
                max: 4
            },
            rombongan: {
                min: 5,
                max: 15
            }
        }
    };

    const rupiah = value => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(value);
    const totalQty = () => posState.quantities.adult + posState.quantities.child;
    const totalPrice = () => {
        const prices = posState.prices[posState.mode];
        return posState.quantities.adult * prices.adult + posState.quantities.child * prices.child;
    };

    function setOrigin(origin) {
        posState.origin = origin;
        document.getElementById('field-domestik').classList.toggle('hidden', origin !== 'domestik');
        document.getElementById('field-mancanegara').classList.toggle('hidden', origin !== 'mancanegara');
        document.getElementById('btn-origin-domestik').className = origin === 'domestik' ? 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm' : 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all';
        document.getElementById('btn-origin-mancanegara').className = origin === 'mancanegara' ? 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm' : 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all';
        document.getElementById('summary-visitor-origin').textContent = origin === 'domestik' ? 'Domestik (WNI)' : 'Mancanegara (WNA)';
    }

    function setMode(mode) {
        posState.mode = mode;
        posState.quantities = {
            adult: 0,
            child: 0
        };
        const individual = mode === 'individual';
        document.getElementById('btn-mode-individual').className = individual ? 'py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2' : 'py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2';
        document.getElementById('btn-mode-rombongan').className = !individual ? 'py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2' : 'py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2';
        document.getElementById('mode-desc').textContent = individual ? 'Mode Individual: Maksimal 4 tiket per transaksi.' : 'Khusus Rombongan/Group: Minimal 5 orang, Maksimal 15 orang.';
        document.getElementById('summary-mode-badge').textContent = individual ? 'INDIVIDUAL' : 'ROMBONGAN (DISCOUNT 5%)';
        document.getElementById('badge-discount-adult').classList.toggle('hidden', individual);
        document.getElementById('badge-discount-child').classList.toggle('hidden', individual);
        document.getElementById('price-adult').firstChild.textContent = rupiah(posState.prices[mode].adult) + ' ';
        document.getElementById('price-child').firstChild.textContent = rupiah(posState.prices[mode].child) + ' ';
        renderCart();
    }

    function updateQty(type, delta) {
        const next = posState.quantities[type] + delta;
        if (next < 0 || totalQty() + delta > posState.limits[posState.mode].max) return;
        posState.quantities[type] = next;
        renderCart();
    }

    function renderCart() {
        const total = totalQty();
        const prices = posState.prices[posState.mode];
        document.getElementById('qty-adult').textContent = posState.quantities.adult;
        document.getElementById('qty-child').textContent = posState.quantities.child;
        document.getElementById('total-qty-display').textContent = total;
        document.getElementById('visitor-count-badge').lastChild.textContent = ` / ${posState.limits[posState.mode].max} Orang`;
        document.getElementById('summary-visitor-origin').textContent = `${posState.origin === 'domestik' ? 'Domestik (WNI)' : 'Mancanegara (WNA)'} (${total} Orang)`;
        const items = document.getElementById('receipt-items-list');
        items.innerHTML = '';
        [
            ['adult', 'Dewasa / Adult'],
            ['child', 'Anak-anak / Child']
        ].forEach(([type, label]) => {
            const qty = posState.quantities[type];
            if (!qty) return;
            items.innerHTML += `<div class="flex justify-between items-center text-xs"><div><span class="font-bold text-slate-800">${label}</span><span class="text-slate-400 ml-1">× ${qty}</span><p class="text-[10px] text-slate-400">@ ${rupiah(prices[type])}</p></div><span class="font-bold text-slate-800">${rupiah(qty * prices[type])}</span></div>`;
        });
        if (!total) items.innerHTML = '<p class="text-xs text-slate-400 italic text-center py-4">Belum ada tiket yang dipilih.</p>';
        const subtotal = posState.quantities.adult * posState.prices.individual.adult + posState.quantities.child * posState.prices.individual.child;
        document.getElementById('summary-subtotal').textContent = rupiah(subtotal);
        document.getElementById('summary-discount').textContent = `- ${rupiah(subtotal - totalPrice())}`;
        document.getElementById('summary-total').textContent = rupiah(totalPrice());
        calculateChange();
    }

    function setPaymentMethod(method) {
        posState.payment = method;
        document.getElementById('cash-container').classList.toggle('hidden', method !== 'cash');
        document.getElementById('edc-container').classList.toggle('hidden', method !== 'edc');
    }

    function calculateChange() {
        const box = document.getElementById('change-box');
        const received = Number(document.getElementById('cash-received').value) || 0;
        const difference = received - totalPrice();
        box.classList.remove('hidden');
        box.textContent = difference >= 0 ? `Kembalian: ${rupiah(difference)}` : `Uang kurang: ${rupiah(Math.abs(difference))}`;
        box.className = `p-3 rounded-lg border text-xs flex justify-between items-center ${difference >= 0 ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-800'}`;
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
        document.querySelectorAll('input').forEach(input => {
            if (input.type !== 'button') input.value = '';
        });
        posState.quantities = {
            adult: 0,
            child: 0
        };
        document.getElementById('summary-visitor-name').textContent = '- Belum Diisi -';
        renderCart();
    }

    function processOrder() {
        if (!document.getElementById('visitor-name').value.trim()) return alert('Nama pengunjung wajib diisi.');
        if (totalQty() < posState.limits[posState.mode].min) return alert(`Minimal ${posState.limits[posState.mode].min} tiket.`);
        if (posState.payment === 'cash' && Number(document.getElementById('cash-received').value) < totalPrice()) return alert('Uang tunai masih kurang.');
        if (posState.payment === 'edc' && !document.getElementById('edc-ref').value.trim()) return alert('Nomor referensi EDC wajib diisi.');
        alert(`Transaksi berhasil diproses sebesar ${rupiah(totalPrice())}.`);
    }
    document.getElementById('visitor-name').addEventListener('input', event => document.getElementById('summary-visitor-name').textContent = event.target.value || '- Belum Diisi -');
    document.getElementById('cash-received').addEventListener('input', calculateChange);
    setMode('individual');
    setOrigin('domestik');
    setPaymentMethod('cash');
    renderCart();
</script>
<?= $this->endSection() ?>