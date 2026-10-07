<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login Petugas - nextix LOKET &amp; GATE</title>
    <!-- Tailwind CSS CDN with Forms & Container Queries -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Custom Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#1e232a',
                            darker: '#16191f',
                            sidebar: '#222731',
                            coral: '#e24a3b',
                            coralHover: '#cc3d2f',
                            accentYellow: '#f59e0b',
                            surface: '#f3f4f7'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- BEGIN: Custom Styles -->
    <style data-purpose="custom-utilities">
        /* Subtle background grid pattern mimicking POS terminal backdrops */
        .bg-grid-subtle {
            background-size: 28px 28px;
            background-image:
                radial-gradient(circle, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        }

        .blur-glow {
            filter: blur(80px);
        }
    </style>
    <!-- END: Custom Styles -->
</head>

<body class="min-h-screen bg-[#f8f6f6] font-sans antialiased text-slate-800 flex flex-col justify-between selection:bg-[#e24a3b] selection:text-white">
    <!-- BEGIN: TopStatusBar -->
    <!-- Top bar mirroring the operational terminal dashboard header in design -->
    <header class="w-full bg-[#2c3136] border-b border-white/5 py-2.5 px-6 flex items-center justify-between text-xs">
        <div class="flex items-center space-x-3 text-slate-400">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                Sistem Siap Operasional
            </span>
            <span class="hidden sm:inline text-slate-600">|</span>
            <span class="hidden sm:inline tracking-wide text-slate-300">Zona Gerbang Wisata Uluwatu - Bali (WITA)</span>
        </div>
        <!-- Live Terminal Real-Time Status -->
        <div class="flex items-center space-x-4 text-slate-400">
            <div class="flex items-center space-x-1.5 bg-black/30 px-3 py-1 rounded text-slate-300 border border-white/5">
                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                <span class="font-medium text-[11px]" id="current-date-time">Memuat jam operasional...</span>
            </div>
        </div>
    </header>
    <!-- END: TopStatusBar -->
    <!-- BEGIN: MainContent -->
    <main class="grow flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden bg-grid-subtle">
        <!-- Ambient glowing backdrops representing Uluwatu sunset fiery tones -->
        <div class="absolute -top-32 left-1/4 w-96 h-96 bg-[#e24a3b]/10 rounded-full blur-glow pointer-events-none"></div>
        <div class="absolute -bottom-32 right-1/4 w-96 h-96 bg-amber-500/5 rounded-full blur-glow pointer-events-none"></div>
        <!-- Login Panel Card -->
        <div class="w-full max-w-[460px] bg-white rounded-2xl shadow-2xl shadow-black/40 overflow-hidden border border-slate-100 flex flex-col z-10 transition-all">
            <!-- BEGIN: CardHeaderBranding -->
            <!-- Visual branding identical to top-left of Kecak Uluwatu POS dashboard -->
            <div class="bg-[#2c3136] pt-8 pb-7 px-8 text-center relative border-b border-slate-700/60">
                <!-- Brand identity logo badge -->
                <div class="inline-flex items-center justify-center space-x-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-[#e24a3b] text-white flex items-center justify-center shadow-lg shadow-[#e24a3b]/30">
                        <!-- Icon resembling traditional Uluwatu Temple shrine/foliage silhouette -->
                        <svg class="w-7 h-7" fill="currentColor" viewbox="0 0 24 24">
                            <path d="M12 2L15 8H9L12 2Z"></path>
                            <path d="M7 8H17L19 13H5L7 8Z" opacity="0.9"></path>
                            <path d="M3 13H21L22 19C22 19.55 21.55 20 21 20H3C2.45 20 2 19.55 2 19L3 13Z"></path>
                            <rect fill="#1e232a" height="4" rx="0.5" width="3" x="10.5" y="16"></rect>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h1 class="text-lg font-bold text-white tracking-tight leading-tight">Kecak Uluwatu</h1>
                        <p class="text-[10px] uppercase font-semibold text-slate-400 tracking-widest mt-0.5">LOKET &amp; GATE</p>
                    </div>
                </div>
                <h2 class="text-xl font-bold text-white tracking-tight mt-1">Masuk ke Sistem Loket</h2>
                <p class="text-xs text-slate-300 mt-1.5 max-w-xs mx-auto leading-relaxed">
                    Masukkan username dan kata sandi petugas untuk memulai shift transaksi.
                </p>
                <!-- Subtle accent highlight line -->
                <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-linear-to-r from-transparent via-[#e24a3b] to-transparent"></div>
            </div>
            <!-- END: CardHeaderBranding -->
            <!-- BEGIN: LoginForm -->
            <!-- Strictly contains ONLY username, password, remember checkbox & login button -->
            <div class="p-8 bg-white flex-1">
                <form action="#" class="space-y-5" method="POST" onsubmit="event.preventDefault();">
                    <!-- Input 1: Username / ID Petugas -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5" for="username">
                            Username / ID Petugas
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input autocomplete="username" class="block w-full pl-10 pr-3.5 py-2.5 text-sm rounded-lg border-slate-300 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-[#e24a3b]/20 focus:border-[#e24a3b] transition duration-150" id="username" name="username" placeholder="contoh: kasir.uluwatu atau admin1" required="" type="text" />
                        </div>
                    </div>
                    <!-- Input 2: Password / Kata Sandi -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5" for="password">
                            Kata Sandi
                        </label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <input autocomplete="current-password" class="block w-full pl-10 pr-10 py-2.5 text-sm rounded-lg border-slate-300 text-slate-900 placeholder:text-slate-400 focus:ring-2 focus:ring-[#e24a3b]/20 focus:border-[#e24a3b] transition duration-150" id="password" name="password" placeholder="••••••••" required="" type="password" />
                        </div>
                    </div>
                    <!-- Session persistence checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-[11px] text-slate-400">Shift Siang / Sore</span>
                    </div>
                    <!-- Submit Button: Matching dashboard Coral-Red Action Button -->
                    <div class="pt-2">
                        <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-[#e24a3b] hover:bg-[#cf3e30] active:scale-[0.99] text-white font-semibold text-sm shadow-md shadow-[#e24a3b]/25 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#e24a3b]" type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Masuk Loket
                        </button>
                    </div>
                </form>
                <!-- Informational box for cashiers/operators -->
                <div class="mt-6 pt-5 border-t border-slate-100 flex items-start space-x-2.5 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-sky-500 shrink-0 mt-0.5" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd"></path>
                    </svg>
                    <p class="leading-relaxed">
                        Butuh pergantian shift atau lupa kredensial? Hubungi <span class="font-medium text-slate-700">Supervisor Operasional Lapangan</span>.
                    </p>
                </div>
            </div>
            <!-- END: LoginForm -->
        </div>
    </main>
    <!-- END: MainContent -->
    <!-- BEGIN: PageFooter -->
    <!-- BEGIN: InteractivityScripts -->
    <!-- Script for toggling password visibility and displaying live Bali operational clock -->
    <script data-purpose="event-handlers">
        document.addEventListener('DOMContentLoaded', () => {

            // Live time clock in WITA (UTC+8)
            const dateTimeSpan = document.getElementById('current-date-time');
            const updateClock = () => {
                const now = new Date();
                const options = {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'Asia/Makassar' // WITA
                };
                dateTimeSpan.textContent = now.toLocaleDateString('id-ID', options) + ' WITA';
            };

            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
    <!-- END: InteractivityScripts -->
</body>

</html>