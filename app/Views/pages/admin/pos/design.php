<!DOCTYPE html>

<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>POS Loket - Kecak Uluwatu</title>
  <!-- Tailwind CSS CDN with forms plugin -->
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <!-- Tailwind Configuration for exact Brand Colors -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              charcoal: '#2A363B',
              dark: '#1e262a',
              red: '#CF4647',
              redHover: '#b53b3c',
              gold: '#F5D061',
              goldHover: '#e0bd50',
              bgLight: '#F8F6F6',
              borderLight: '#E5E7EB',
            }
          },
          fontFamily: {
            sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
          }
        }
      }
    }
  </script>
  <!-- BEGIN: Custom Styles for Layout & Utilities -->
  <style data-purpose="custom-utilities">
    /* Custom scrollbar for receipt and sidebar */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #c7cbd1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #a0a6b1;
    }

    /* Subtle dot grid for receipt aesthetic */
    .receipt-perforation {
      background-image: radial-gradient(circle, #E5E7EB 1.5px, transparent 1.5px);
      background-size: 8px 8px;
    }

    .badge-subtle {
      letter-spacing: 0.025em;
    }
  </style>
  <!-- END: Custom Styles -->
</head>

<body class="bg-brand-bgLight text-slate-800 antialiased min-h-screen flex flex-col font-sans select-none">
  <!-- BEGIN: AppContainer -->
  <div class="flex h-screen overflow-hidden">
    <!-- BEGIN: MainSidebar -->
    <aside class="w-64 bg-brand-charcoal text-slate-200 flex flex-col justify-between shrink-0 z-20 shadow-xl" data-purpose="application-sidebar">
      <!-- Top Branding and Nav Section -->
      <div class="flex flex-col h-full overflow-y-auto">
        <!-- Brand Header Matching Image -->
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10" data-purpose="sidebar-brand">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-red to-orange-500 flex items-center justify-center shadow-lg shadow-brand-red/30 shrink-0">
            <!-- Balinese Kecak flame motif SVG -->
            <svg class="w-6 h-6 text-brand-gold fill-current" viewbox="0 0 24 24">
              <path d="M12 2C8.5 7 6 9.5 6 13a6 6 0 0 0 12 0c0-3.5-2.5-6-6-11zm0 18a4 4 0 0 1-4-4c0-2.3 1.8-4.2 3.5-6.6.3-.4.7-.8 1-1.3.4.7.8 1.2 1.1 1.7 1.4 2.2 2.4 4.1 2.4 6.2a4 4 0 0 1-4 4z"></path>
            </svg>
          </div>
          <div>
            <h1 class="text-base font-bold tracking-tight text-white leading-tight">Kecak Uluwatu</h1>
            <p class="text-[11px] font-semibold text-slate-400 tracking-wider">LOKET &amp; GATE</p>
          </div>
        </div>
        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-6" data-purpose="sidebar-navigation">
          <!-- Operasional Group -->
          <div>
            <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">OPERASIONAL</div>
            <ul class="space-y-1">
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <rect height="9" rx="1" stroke-width="2" width="7" x="3" y="3"></rect>
                    <rect height="5" rx="1" stroke-width="2" width="7" x="14" y="3"></rect>
                    <rect height="9" rx="1" stroke-width="2" width="7" x="14" y="12"></rect>
                    <rect height="5" rx="1" stroke-width="2" width="7" x="3" y="16"></rect>
                  </svg>
                  <span>Dashboard</span>
                </a>
              </li>
              <li>
                <!-- Active Nav: POS Loket (Gold Highlight from Image) -->
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold rounded-lg bg-brand-gold text-slate-900 shadow-sm transition-all" href="#">
                  <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                    <rect height="16" rx="2" width="20" x="2" y="4"></rect>
                    <path d="M7 15h.01M17 15h2M7 11h2M12 11h2M17 11h2M7 7h10"></path>
                  </svg>
                  <span>POS Loket</span>
                </a>
              </li>
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                  </svg>
                  <span>Riwayat Penjualan</span>
                </a>
              </li>
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                  </svg>
                  <span>Laporan</span>
                </a>
              </li>
            </ul>
          </div>
          <!-- Master Data Group -->
          <div>
            <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">MASTER DATA</div>
            <ul class="space-y-1">
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                  </svg>
                  <span>Jenis Tiket</span>
                </a>
              </li>
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                  </svg>
                  <span>Pengguna</span>
                </a>
              </li>
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                  </svg>
                  <span>Checkpoint</span>
                </a>
              </li>
            </ul>
          </div>
          <!-- Sistem Group -->
          <div>
            <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">SISTEM</div>
            <ul class="space-y-1">
              <li>
                <a class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-colors" href="#">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                  </svg>
                  <span>Pengaturan</span>
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <!-- Cashier Operator Profile Bar at Bottom of Sidebar -->
        <div class="p-3 border-t border-white/10 bg-brand-dark/40" data-purpose="sidebar-user-footer">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-slate-700 text-white font-bold flex items-center justify-center text-xs border border-white/20">
                AL
              </div>
              <div class="leading-tight">
                <p class="text-sm font-semibold text-white">Admin Loket 1</p>
                <p class="text-[11px] text-slate-400">Terminal loket</p>
              </div>
            </div>
            <button class="p-1.5 rounded-md text-slate-400 hover:text-white hover:bg-white/10 transition" title="Keluar Akun">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </aside>
    <!-- END: MainSidebar -->
    <!-- BEGIN: MainContentWrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-brand-bgLight">
      <!-- BEGIN: TopBar -->
      <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shrink-0 z-10" data-purpose="application-header">
        <div class="flex items-center gap-4">
          <button class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" id="btn-toggle-sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </svg>
          </button>
          <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">
              <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
              Loket 01 Online
            </span>
            <span class="text-xs text-slate-400">|</span>
            <span class="text-xs text-slate-500 font-medium">Tiket Pertunjukan Sunset Kecak &amp; Tari Api</span>
          </div>
        </div>
        <!-- Shift & Logout Badge matching screenshot exactly -->
        <div class="flex items-center gap-4">
          <div class="bg-brand-charcoal text-white rounded-lg px-4 py-1.5 text-right shadow-sm flex items-center gap-3">
            <div>
              <p class="text-[10px] font-semibold text-slate-300 uppercase tracking-wider">HARI DAGANG - SEL, 6 OKT 2026</p>
              <p class="text-xs font-bold text-white tracking-wide">
                Sisa <span class="text-brand-gold font-extrabold" id="countdown-timer">10j 54m</span> <span class="font-normal text-slate-300 text-[10px]">batas 24.00 WITA</span>
              </p>
            </div>
            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
          </div>
          <button class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-brand-red px-3 py-2 rounded-md hover:bg-slate-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </svg>
            <span>Keluar</span>
          </button>
        </div>
      </header>
      <!-- END: TopBar -->
      <!-- BEGIN: POSMainContent -->
      <main class="flex-1 overflow-y-auto p-6" data-purpose="pos-workspace">
        <!-- POS Title Header -->
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
                      Mancanegara (WNA)
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
                    Non-Tunai / EDC
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
      <!-- END: POSMainContent -->
    </div>
    <!-- END: MainContentWrapper -->
  </div>
  <!-- END: AppContainer -->
  <!-- BEGIN: SuccessModal (Print Preview) -->
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs hidden p-4" data-purpose="ticket-receipt-modal" id="print-modal">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-150">
      <!-- Modal Header -->
      <div class="bg-brand-charcoal text-white p-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
              <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </svg>
          </div>
          <div>
            <h4 class="font-bold text-sm">Transaksi Berhasil!</h4>
            <p class="text-[11px] text-slate-300">Tiket berhasil diterbitkan</p>
          </div>
        </div>
        <button class="text-slate-400 hover:text-white p-1 rounded-md" onclick="closeModal()">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
          </svg>
        </button>
      </div>
      <!-- Thermal Ticket Print Preview Paper -->
      <div class="p-6 bg-slate-50">
        <div class="bg-white border border-slate-300 rounded-lg p-4 shadow-sm font-mono text-xs space-y-3 receipt-perforation text-slate-800">
          <div class="text-center pb-2 border-b border-dashed border-slate-300 space-y-0.5">
            <h5 class="font-bold text-sm tracking-wide text-brand-charcoal">KECAK &amp; FIRE DANCE</h5>
            <p class="text-[10px] text-slate-500">Pura Luhur Uluwatu, Bali</p>
            <p class="text-[10px] text-slate-400">Terminal: Loket 01 | Shift 1</p>
          </div>
          <div class="space-y-1 text-[11px]">
            <div class="flex justify-between"><span class="text-slate-500">No. Tiket:</span> <span class="font-bold" id="m-ticket-id">KW-20261006-084</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Waktu Trx:</span> <span id="m-ticket-time">06/10/2026 17:05:22 WITA</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Pengunjung:</span> <span class="font-bold" id="m-visitor-name">Budi Santoso</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kategori:</span> <span id="m-visitor-origin">Domestik (Jakarta)</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Mode:</span> <span class="font-bold text-brand-red" id="m-mode">INDIVIDUAL</span></div>
          </div>
          <div class="border-t border-dashed border-slate-300 pt-2 space-y-1" id="m-items-list">
            <!-- Dynamic Ticket Rows -->
          </div>
          <div class="border-t border-dashed border-slate-300 pt-2 space-y-1 font-bold">
            <div class="flex justify-between"><span>TOTAL BAYAR:</span> <span class="text-brand-red text-sm" id="m-total-pay">Rp 200.000</span></div>
            <div class="flex justify-between text-[11px] font-normal text-slate-600"><span id="m-pay-type">Tunai:</span> <span id="m-cash-received">Rp 200.000</span></div>
            <div class="flex justify-between text-[11px] text-slate-600"><span>Kembalian:</span> <span id="m-change">Rp 0</span></div>
          </div>
          <!-- Barcode simulation -->
          <div class="pt-3 text-center space-y-1 border-t border-dashed border-slate-300">
            <div class="inline-block px-4 py-2 bg-slate-900 text-white font-mono tracking-widest text-xs rounded">
              ||| | ||||| || |||||| | |||||
            </div>
            <p class="text-[9px] text-slate-400">Pintu Gate Masuk Buka: 17:15 WITA</p>
          </div>
        </div>
      </div>
      <!-- Modal Footer -->
      <div class="bg-white px-6 py-3 border-t border-slate-200 flex items-center justify-end gap-3">
        <button class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition" onclick="closeModal()">
          Tutup
        </button>
        <button class="px-4 py-2 text-xs font-bold text-white bg-brand-charcoal hover:bg-slate-800 rounded-lg flex items-center gap-1.5 transition" onclick="printThermalTicket()">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
            <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
          </svg>
          Cetak Tiket Fisik
        </button>
      </div>
    </div>
  </div>
  <!-- END: SuccessModal -->
  <!-- BEGIN: ToastNotification -->
  <div class="fixed bottom-5 right-5 z-50 bg-brand-charcoal text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 transform translate-y-24 opacity-0 transition-all duration-300" data-purpose="action-toast" id="toast">
    <div class="text-brand-gold" id="toast-icon">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
        <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
      </svg>
    </div>
    <div class="text-xs font-medium" id="toast-message">Pesan notifikasi</div>
  </div>
  <!-- END: ToastNotification -->
  <!-- BEGIN: POSInteractiveLogic -->
  <script data-purpose="pos-engine">
    // State Machine
    const posState = {
      originCategory: 'domestik', // 'domestik' | 'mancanegara'
      mode: 'individual', // 'individual' | 'rombongan'
      paymentMethod: 'cash', // 'cash' | 'edc'
      quantities: {
        adult: 0,
        child: 0
      },
      prices: {
        individual: {
          adult: 100000,
          child: 75000,
          discountPct: 0
        },
        rombongan: {
          adult: 95000,
          child: 70000,
          discountPct: 0.05
        }
      },
      cashReceived: 0,
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

    // Format IDR Currency
    function formatRupiah(number) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(number);
    }

    // Toggle Origin Category (Domestik vs Mancanegara)
    function setOrigin(category) {
      posState.originCategory = category;
      const btnDom = document.getElementById('btn-origin-domestik');
      const btnMan = document.getElementById('btn-origin-mancanegara');
      const fldDom = document.getElementById('field-domestik');
      const fldMan = document.getElementById('field-mancanegara');
      const summaryOrigin = document.getElementById('summary-visitor-origin');

      if (category === 'domestik') {
        btnDom.className = 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm';
        btnMan.className = 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all';
        fldDom.classList.remove('hidden');
        fldMan.classList.add('hidden');
        summaryOrigin.textContent = 'Domestik (WNI)';
      } else {
        btnMan.className = 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-brand-charcoal bg-brand-charcoal text-white transition-all shadow-sm';
        btnDom.className = 'flex items-center justify-center gap-2 py-2 px-3 text-sm font-semibold rounded-lg border-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300 transition-all';
        fldMan.classList.remove('hidden');
        fldDom.classList.add('hidden');
        summaryOrigin.textContent = 'Mancanegara (WNA)';
      }
    }

    // Toggle Mode (Individual vs Rombongan)
    function setMode(mode) {
      if (posState.mode === mode) return;

      // Reset quantities on mode switch to enforce rule integrity
      posState.mode = mode;
      posState.quantities.adult = 0;
      posState.quantities.child = 0;

      const btnInd = document.getElementById('btn-mode-individual');
      const btnRom = document.getElementById('btn-mode-rombongan');
      const modeDesc = document.getElementById('mode-desc');
      const countBadge = document.getElementById('visitor-count-badge');
      const summaryModeBadge = document.getElementById('summary-mode-badge');
      const groupWarning = document.getElementById('group-warning');

      const badgeDiscAdult = document.getElementById('badge-discount-adult');
      const badgeDiscChild = document.getElementById('badge-discount-child');

      const priceAdultEl = document.getElementById('price-adult');
      const priceChildEl = document.getElementById('price-child');

      if (mode === 'individual') {
        btnInd.className = 'py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2';
        btnRom.className = 'py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2';
        modeDesc.textContent = 'Mode Individual: Maksimal 4 tiket per transaksi.';
        countBadge.innerHTML = 'Total: <span id="total-qty-display">0</span> / 4';
        summaryModeBadge.textContent = 'INDIVIDUAL';
        summaryModeBadge.className = 'text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-white/20 text-white tracking-wider';
        groupWarning.classList.add('hidden');

        badgeDiscAdult.classList.add('hidden');
        badgeDiscChild.classList.add('hidden');

        priceAdultEl.textContent = formatRupiah(posState.prices.individual.adult);
        priceChildEl.textContent = formatRupiah(posState.prices.individual.child);
      } else {
        btnRom.className = 'py-2.5 rounded-lg text-sm font-bold bg-white text-slate-900 shadow-sm transition-all flex items-center justify-center gap-2';
        btnInd.className = 'py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:text-slate-900 transition-all flex items-center justify-center gap-2';
        modeDesc.textContent = 'Khusus Rombongan/Group: Minimal 5 orang, Maksimal 15 orang.';
        countBadge.innerHTML = 'Jumlah: <span id="total-qty-display">0</span> / 15';
        summaryModeBadge.textContent = 'ROMBONGAN (DISCOUNT 5%)';
        summaryModeBadge.className = 'text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-emerald-500 text-white tracking-wider';
        groupWarning.classList.remove('hidden');

        badgeDiscAdult.classList.remove('hidden');
        badgeDiscChild.classList.remove('hidden');

        priceAdultEl.textContent = formatRupiah(posState.prices.rombongan.adult);
        priceChildEl.textContent = formatRupiah(posState.prices.rombongan.child);
      }

      updateStepperUI();
      renderCart();
    }

    // Step Quantity with Constraint Handling
    function updateQty(type, delta) {
      const currentTotal = posState.quantities.adult + posState.quantities.child;
      const targetTotal = currentTotal + delta;
      const targetQty = posState.quantities[type] + delta;

      if (targetQty < 0) return;

      const limits = posState.limits[posState.mode];

      if (delta > 0 && targetTotal > limits.max) {
        showToast(`Batas maksimal mode ${posState.mode} adalah ${limits.max} tiket!`, 'warning');
        return;
      }

      posState.quantities[type] = targetQty;
      updateStepperUI();
      renderCart();
    }

    // Update Counter UI Badges
    function updateStepperUI() {
      document.getElementById('qty-adult').textContent = posState.quantities.adult;
      document.getElementById('qty-child').textContent = posState.quantities.child;

      const total = posState.quantities.adult + posState.quantities.child;
      document.getElementById('total-qty-display').textContent = total;

      const groupWarning = document.getElementById('group-warning');
      if (posState.mode === 'rombongan') {
        if (total > 0 && total < posState.limits.rombongan.min) {
          groupWarning.classList.remove('hidden');
          groupWarning.innerHTML = `
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>Perlu <strong>${posState.limits.rombongan.min - total} tiket lagi</strong> untuk memenuhi batas minimal 5 rombongan.</span>
          `;
        } else if (total >= posState.limits.rombongan.min) {
          groupWarning.classList.remove('hidden');
          groupWarning.className = 'mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs flex items-center gap-2';
          groupWarning.innerHTML = `
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>Syarat rombongan terpenuhi (${total} orang). Diskon rombongan aktif!</span>
          `;
        } else {
          groupWarning.classList.remove('hidden');
          groupWarning.className = 'mb-4 p-3 rounded-lg bg-amber-50 border border-amber-300 text-amber-800 text-xs flex items-center gap-2';
          groupWarning.innerHTML = `<span>Tiket rombongan membutuhkan <strong>minimal 5 orang</strong> untuk dapat diproses.</span>`;
        }
      }
    }

    // Render Order Summary & Financials
    function renderCart() {
      const container = document.getElementById('receipt-items-list');
      const isRombongan = posState.mode === 'rombongan';
      const prices = posState.prices[posState.mode];

      const adultCount = posState.quantities.adult;
      const childCount = posState.quantities.child;
      const totalItems = adultCount + childCount;

      container.innerHTML = '';

      if (totalItems === 0) {
        container.innerHTML = `<p id="empty-cart-msg" class="text-xs text-slate-400 italic text-center py-4">Belum ada tiket yang dipilih.</p>`;
      } else {
        if (adultCount > 0) {
          const lineTotal = adultCount * prices.adult;
          container.innerHTML += `
            <div class="flex justify-between items-center text-xs">
              <div>
                <span class="font-bold text-slate-800">Dewasa / Adult</span>
                <span class="text-slate-400 ml-1">x ${adultCount}</span>
                <p class="text-[10px] text-slate-400">@ ${formatRupiah(prices.adult)}</p>
              </div>
              <span class="font-bold text-slate-800">${formatRupiah(lineTotal)}</span>
            </div>
          `;
        }
        if (childCount > 0) {
          const lineTotal = childCount * prices.child;
          container.innerHTML += `
            <div class="flex justify-between items-center text-xs">
              <div>
                <span class="font-bold text-slate-800">Anak-anak / Child</span>
                <span class="text-slate-400 ml-1">x ${childCount}</span>
                <p class="text-[10px] text-slate-400">@ ${formatRupiah(prices.child)}</p>
              </div>
              <span class="font-bold text-slate-800">${formatRupiah(lineTotal)}</span>
            </div>
          `;
        }
      }

      // Calculations
      let standardSubtotal = (adultCount * posState.prices.individual.adult) + (childCount * posState.prices.individual.child);
      let actualTotal = (adultCount * prices.adult) + (childCount * prices.child);
      let discountAmount = standardSubtotal - actualTotal;

      document.getElementById('summary-subtotal').textContent = formatRupiah(standardSubtotal);
      document.getElementById('summary-discount').textContent = `- ${formatRupiah(discountAmount)}`;
      document.getElementById('summary-total').textContent = formatRupiah(actualTotal);

      // Trigger change calculation
      calculateChange();
    }

    // Payment Method Switching (Cash vs EDC)
    function setPaymentMethod(method) {
      posState.paymentMethod = method;
      const btnCash = document.getElementById('pay-method-cash');
      const btnEdc = document.getElementById('pay-method-edc');
      const cashBox = document.getElementById('cash-container');
      const edcBox = document.getElementById('edc-container');

      if (method === 'cash') {
        btnCash.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-brand-red bg-brand-red text-white flex items-center justify-center gap-1.5 transition';
        btnEdc.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-slate-200 bg-white text-slate-700 hover:border-slate-300 flex items-center justify-center gap-1.5 transition';
        cashBox.classList.remove('hidden');
        edcBox.classList.add('hidden');
      } else {
        btnEdc.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-brand-red bg-brand-red text-white flex items-center justify-center gap-1.5 transition';
        btnCash.className = 'py-2 px-3 text-xs font-bold rounded-lg border-2 border-slate-200 bg-white text-slate-700 hover:border-slate-300 flex items-center justify-center gap-1.5 transition';
        edcBox.classList.remove('hidden');
        cashBox.classList.add('hidden');
      }
    }

    // Cash Handling & Kembalian Calculation
    function calculateChange() {
      if (posState.paymentMethod !== 'cash') return;

      const prices = posState.prices[posState.mode];
      const actualTotal = (posState.quantities.adult * prices.adult) + (posState.quantities.child * prices.child);
      const inputEl = document.getElementById('cash-received');
      const cashReceived = parseFloat(inputEl.value) || 0;
      const changeBox = document.getElementById('change-box');

      if (cashReceived === 0 && actualTotal === 0) {
        changeBox.classList.add('hidden');
        return;
      }

      changeBox.classList.remove('hidden');

      if (actualTotal === 0) {
        changeBox.className = 'p-3 rounded-lg border text-xs bg-slate-100 border-slate-200 text-slate-600';
        changeBox.innerHTML = `Pilih tiket terlebih dahulu.`;
        return;
      }

      if (cashReceived >= actualTotal) {
        const change = cashReceived - actualTotal;
        changeBox.className = 'p-3 rounded-lg border text-xs bg-emerald-50 border-emerald-300 text-emerald-900 flex justify-between items-center';
        changeBox.innerHTML = `
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-bold">Kembalian:</span>
          </div>
          <span class="text-sm font-extrabold text-emerald-700">${formatRupiah(change)}</span>
        `;
      } else {
        const shortage = actualTotal - cashReceived;
        changeBox.className = 'p-3 rounded-lg border text-xs bg-rose-50 border-rose-300 text-rose-800 flex justify-between items-center';
        changeBox.innerHTML = `
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0118 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>Uang Kurang:</span>
          </div>
          <span class="font-bold text-rose-700">- ${formatRupiah(shortage)}</span>
        `;
      }
    }

    // Set Exact Cash
    function setExactCash() {
      const prices = posState.prices[posState.mode];
      const actualTotal = (posState.quantities.adult * prices.adult) + (posState.quantities.child * prices.child);
      const inputEl = document.getElementById('cash-received');
      inputEl.value = actualTotal;
      calculateChange();
    }

    // Set Preset Cash Amount
    function setPresetCash(amount) {
      const inputEl = document.getElementById('cash-received');
      inputEl.value = amount;
      calculateChange();
    }

    // Live update visitor name to summary
    document.getElementById('visitor-name').addEventListener('input', function(e) {
      const val = e.target.value.trim();
      document.getElementById('summary-visitor-name').textContent = val || '- Belum Diisi -';
    });

    // Process Transaction & Validations
    function processOrder() {
      const visitorName = document.getElementById('visitor-name').value.trim();
      const totalTickets = posState.quantities.adult + posState.quantities.child;
      const prices = posState.prices[posState.mode];
      const totalAmount = (posState.quantities.adult * prices.adult) + (posState.quantities.child * prices.child);

      // Validations
      if (!visitorName) {
        showToast('Nama pengunjung perwakilan wajib diisi!', 'error');
        document.getElementById('visitor-name').focus();
        return;
      }

      if (totalTickets === 0) {
        showToast('Pilih minimal 1 tiket pertunjukan!', 'error');
        return;
      }

      if (posState.mode === 'rombongan' && totalTickets < posState.limits.rombongan.min) {
        showToast(`Tiket rombongan minimal ${posState.limits.rombongan.min} orang! Saat ini baru ${totalTickets}.`, 'error');
        return;
      }

      if (posState.mode === 'individual' && totalTickets > posState.limits.individual.max) {
        showToast(`Mode individual maksimal ${posState.limits.individual.max} orang!`, 'error');
        return;
      }

      if (posState.paymentMethod === 'cash') {
        const cashReceived = parseFloat(document.getElementById('cash-received').value) || 0;
        if (cashReceived < totalAmount) {
          showToast('Uang tunai yang diterima masih kurang!', 'error');
          document.getElementById('cash-received').focus();
          return;
        }
      } else {
        const refNo = document.getElementById('edc-ref').value.trim();
        if (!refNo) {
          showToast('Nomor referensi / approval EDC wajib diisi!', 'error');
          document.getElementById('edc-ref').focus();
          return;
        }
      }

      // Populate Thermal Modal Preview
      const now = new Date();
      const timeStr = `06/10/2026 ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}:${String(now.getSeconds()).padStart(2,'0')} WITA`;
      const trxCode = `KW-20261006-${Math.floor(100 + Math.random() * 900)}`;

      document.getElementById('m-ticket-id').textContent = trxCode;
      document.getElementById('m-ticket-time').textContent = timeStr;
      document.getElementById('m-visitor-name').textContent = visitorName;
      document.getElementById('m-mode').textContent = posState.mode.toUpperCase();

      let locDetail = posState.originCategory === 'domestik' ?
        (document.getElementById('visitor-city').value.trim() || 'Indonesia') :
        document.getElementById('visitor-country').value;
      document.getElementById('m-visitor-origin').textContent = `${posState.originCategory === 'domestik' ? 'Domestik' : 'WNA'} (${locDetail})`;

      // Render Modal Items
      const modalItems = document.getElementById('m-items-list');
      modalItems.innerHTML = '';
      if (posState.quantities.adult > 0) {
        modalItems.innerHTML += `
          <div class="flex justify-between">
            <span>Dewasa x ${posState.quantities.adult}</span>
            <span>${formatRupiah(posState.quantities.adult * prices.adult)}</span>
          </div>
        `;
      }
      if (posState.quantities.child > 0) {
        modalItems.innerHTML += `
          <div class="flex justify-between">
            <span>Anak x ${posState.quantities.child}</span>
            <span>${formatRupiah(posState.quantities.child * prices.child)}</span>
          </div>
        `;
      }

      document.getElementById('m-total-pay').textContent = formatRupiah(totalAmount);

      if (posState.paymentMethod === 'cash') {
        const cashVal = parseFloat(document.getElementById('cash-received').value) || totalAmount;
        document.getElementById('m-pay-type').textContent = 'Tunai Diterima:';
        document.getElementById('m-cash-received').textContent = formatRupiah(cashVal);
        document.getElementById('m-change').textContent = formatRupiah(cashVal - totalAmount);
      } else {
        const edcRef = document.getElementById('edc-ref').value;
        document.getElementById('m-pay-type').textContent = `EDC Ref: ${edcRef}`;
        document.getElementById('m-cash-received').textContent = formatRupiah(totalAmount);
        document.getElementById('m-change').textContent = 'Rp 0 (Non-Tunai)';
      }

      // Show Print Modal
      document.getElementById('print-modal').classList.remove('hidden');
    }

    // Reset Transaction to default clean state
    function resetTransaction() {
      document.getElementById('visitor-name').value = '';
      document.getElementById('visitor-city').value = '';
      document.getElementById('visitor-phone').value = '';
      document.getElementById('visitor-address').value = '';
      document.getElementById('cash-received').value = '';
      document.getElementById('edc-ref').value = '';
      document.getElementById('summary-visitor-name').textContent = '- Belum Diisi -';

      posState.quantities.adult = 0;
      posState.quantities.child = 0;
      updateStepperUI();
      renderCart();
      showToast('Form transaksi telah direset.', 'info');
    }

    // Modal Control
    function closeModal() {
      document.getElementById('print-modal').classList.add('hidden');
      // Generate new transaction ID
      document.getElementById('trx-id').textContent = `KW-20261006-${Math.floor(100 + Math.random() * 900)}`;
      resetTransaction();
    }

    function printThermalTicket() {
      showToast('Mengirim tiket ke Thermal Printer Loket...', 'info');
      setTimeout(() => {
        closeModal();
        showToast('Tiket fisik berhasil dicetak!', 'success');
      }, 900);
    }

    // Toast Messenger
    function showToast(msg, type = 'info') {
      const toast = document.getElementById('toast');
      const toastMsg = document.getElementById('toast-message');
      const toastIcon = document.getElementById('toast-icon');

      toastMsg.textContent = msg;

      if (type === 'error') {
        toastIcon.innerHTML = `<svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`;
      } else if (type === 'warning') {
        toastIcon.innerHTML = `<svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`;
      } else {
        toastIcon.innerHTML = `<svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`;
      }

      toast.classList.remove('translate-y-24', 'opacity-0');
      setTimeout(() => {
        toast.classList.add('translate-y-24', 'opacity-0');
      }, 3500);
    }

    // Top Countdown Timer logic matching screenshot "Sisa 10j 54m"
    (function initTimer() {
      let minutes = 54;
      let hours = 10;
      setInterval(() => {
        if (minutes > 0) {
          minutes--;
        } else {
          if (hours > 0) {
            hours--;
            minutes = 59;
          }
        }
        document.getElementById('countdown-timer').textContent = `${hours}j ${minutes}m`;
      }, 60000);
    })();

    // Sidebar Toggle for Mobile / Responsive View
    document.getElementById('btn-toggle-sidebar').addEventListener('click', () => {
      const sidebar = document.querySelector('aside');
      sidebar.classList.toggle('hidden');
    });

    // Initialize Default State
    renderCart();
  </script>
  <!-- END: POSInteractiveLogic -->
</body>

</html>