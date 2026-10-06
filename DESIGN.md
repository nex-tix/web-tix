# DESIGN DOCUMENT
**Sistem Ticketing POS Loket Web & Mobile Validator Gate — Pertunjukan Kecak Uluwatu**  
Versi: 1.0 (berdasarkan PRD v2.5)  
Tujuan: Panduan visual & interaction untuk tim Front‑end (CI4 + Blade/Views) dan Mobile (Flutter).

---

## 1. Brand & Colour Palette
| Role | Hex | Usage |
|------|-----|-------|
| **Primary** | `#CF4647` | Tombol aksi utama (Simpan, Cetak, Login), link aktif, border focus, badge *success* (valid scan) |
| **Secondary‑Gold** | `#F5D061` | Highlight card *dashboard* (total penjualan, visitor), label *warning* (expired), ikon peringatan |
| **Secondary‑OffWhite** | `#F8F6F6` | Latar halaman web, background card, area input disabled |
| **Accent/Neutral** | `#2A363B` | Teks utama, heading, navbar, app‑bar mobile, border tabel, QR‑code dark modules |
| **Success** | `#2E7D32` | Status *VALID*, check‑in berhasil |
| **Error** | `#C62828` | Status *ALREADY_USED*, *CANCELLED*, *NOT_FOUND* |
| **Warning** | `#F57F17` | Status *EXPIRED* |
| **Info** | `#1565C0` | Mode *Cek* (read‑only), link sekunder |

> Semua warna harus memenuhi kontras WCAG AA (≥4.5:1) terhadap background putih/off‑white.

---

## 2. Tipografi
| Elemen | Font | Ukuran | Weight | Catatan |
|--------|------|--------|--------|---------|
| Heading 1 (page title) | **Inter** / system‑ui | 28 px | 700 | Warna `#2A363B` |
| Heading 2 (section) | Inter | 22 px | 600 | |
| Heading 3 (card title) | Inter | 18 px | 600 | |
| Body / label | Inter | 14 px | 400 | `#2A363B` |
| Small / caption | Inter | 12 px | 400 | `#6B7A80` (muted) |
| Button text | Inter | 14 px | 600 | Uppercase optional |
| Monospace (kode tiket, invoice) | **JetBrains Mono** | 13 px | 400 | QR‑code preview, ticket code |

Font di‑load via CDN (Google Fonts) atau lokal di `public/fonts/`.

---

## 3. Spacing & Layout Grid
- **Base unit** = 4 px. Semua margin/padding kelipatan 4.
- **Container max‑width** web: 1280 px, centered, padding 24 px (6 units).
- **Card radius** = 8 px, shadow `0 2px 6px rgba(42,54,59,0.08)`.
- **Mobile**: safe‑area inset, bottom navigation 56 px height.

---

## 4. Component Library (Web – CI4 Views)

### 4.1 Buttons
| Variant | Background | Text | Border | Hover / Focus |
|---------|------------|------|--------|---------------|
| Primary | `#CF4647` | `#FFF` | none | `#B33A3B` |
| Secondary | `#F5D061` | `#2A363B` | none | `#E0B94E` |
| Outline | transparent | `#CF4647` | 1px solid `#CF4647` | bg `#FFF5F5` |
| Ghost | transparent | `#2A363B` | none | bg `#F0F0F0` |
| Danger | `#C62828` | `#FFF` | none | `#A51C1C` |

Disabled: opacity 0.5, cursor not‑allowed.

### 4.2 Form Controls
- **Input, Select, Textarea**: border 1px `#CFD8DC`, radius 6 px, padding 10 px 12 px.
- **Focus**: border `#CF4647`, box‑shadow `0 0 0 3px rgba(207,70,71,0.15)`.
- **Error**: border `#C62828`, helper text merah.
- **Label** di atas input, 12 px, weight 500.

### 4.3 Tables
- Header bg `#2A363B`, text `#FFF`.
- Row hover bg `#F8F6F6`.
- Zebra stripe optional.
- Action icons (print, void) size 20 px, color `#2A363B`; hover → `#CF4647`.

### 4.4 Cards / Panels
- Background `#FFF`, border 1px `#E0E0E0`.
- Header optional: bg `#F8F6F6`, border‑bottom 1px `#E0E0E0`.

### 4.5 Modal / Dialog
- Overlay `rgba(42,54,59,0.5)`.
- Container max‑width 560 px, radius 8 px, padding 24 px.
- Footer buttons right‑aligned (Cancel ghost, Confirm primary).

### 4.6 Toast / Snackbar
- Position top‑right (web) / bottom (mobile).
- Success bg `#2E7D32`, Error bg `#C62828`, Warning bg `#F57F17`, Info bg `#1565C0`.
- Auto‑dismiss 3 s, action button optional.

### 4.7 QR Code Display
- Canvas/SVG, dark modules `#2A363B`, light `#FFF`.
- Size 180 px (web), 200 px (mobile).
- Caption below: ticket code monospace 12 px.

---

## 5. Web Screens (Admin)

| Screen | Key Components | Notes |
|--------|----------------|-------|
| **Login** | Centered card 360 px, logo, fields username/password, primary button *Masuk*, link *Lupa password* (tidak dipakai) | Background `#F8F6F6`. |
| **Dashboard** | 4 metric cards (Penjualan Hari Ini, Tiket Terbit, Sudah Masuk, Aktif Belum Dipakai), chart penjualan 7 hari (line, primary color), tabel transaksi terbaru 5 baris | Responsive: cards stack < 768 px. |
| **POS Loket** | Stepper 1‑Data Pengunjung → 2‑Pilih Jenis & Jumlah → 3‑Pembayaran → 4‑Cetak. <br>• Jenis penjualan radio (Individual / Rombongan). <br>• Daftar ticket_type sesuai kategori (filter client‑side). <br>• Input qty per jenis, live total. <br>• Cash: uang diterima + kembalian auto. <br>• Debit: referensi EDC required. <br>• Tombol *Simpan & Cetak* primary. | Validasi batas `max_individual_per_tx`, `min_group_size`, `max_group_members`. Tanggal berlaku read‑only. |
| **Riwayat Penjualan** | Filter tanggal, petugas, metode bayar. Table dengan aksi *Cetak Ulang* (outline), *Void* (danger). Pagination 15/halaman. | Cetak ulang hanya tiket `active` hari ini. Void hanya jika tidak ada tiket `used`. |
| **Master Jenis Tiket** | CRUD modal. Field: nama, kategori (individual/group), harga, diskon %, aktif. **Peringatan** kuning jika harga pasangan individual/rombongan berbeda. | Diskon % = satu sumber. |
| **Master User / Gate User** | Table, toggle aktif, reset password modal. |
| **Checkpoint** | CRUD sederhana (nama, aktif). |
| **Pengaturan** | Form key‑value (settings table). Validasi numerik & min. Setiap simpan catat `updated_by`. |
| **Laporan** | Tab per laporan (Penjualan, Diskon Individual vs Rombongan, Demografi, Audit Scan, Tiket Terjual vs Terpakai). Tombol *Ekspor PDF* (secondary), *Ekspor Excel* (outline). | Gunakan library PDF (dompdf) & PhpSpreadsheet. |

---

## 6. Mobile Validator Screens (Flutter)

| Screen | Widgets | Interaction |
|--------|---------|-------------|
| **Login** | Scaffold bg `#F8F6F6`, centered Card, TextField username/password, PrimaryButton *Login*, error toast. | Token disimpan secure storage; auto‑login jika token valid. |
| **Pilih Checkpoint** | ListView radio tiles (nama checkpoint), PrimaryButton *Lanjut*. | Disimpan lokal untuk sesi. |
| **Scanner (Check‑in Mode)** | `mobile_scanner` full‑screen, overlay frame corners primary `#CF4647`. Top toggle **Mode Cek** (Switch). Bottom sheet hasil scan (auto‑dismiss 2 s). | Debounce 1 s. Beep & vibrate: success high‑pitch, reject double‑tone. |
| **Hasil Scan (Modal BottomSheet)** | • Status badge (color per tabel 7.3 PRD). <br>• Info: jenis tiket, jumlah pengunjung, nama pembawa. <br>• Jika *Cek* & valid → button *Check‑in Sekarang* (primary). | Tap outside / auto close → back to scanner. |
| **Dashboard Tamu** | Segmented control **Hari Ini / Bulan Ini** (primary selected). Three metric cards (Total Masuk, Tiket Valid, Ditolak). ListView ringkas scan terakhir 10. | Pull‑to‑refresh. |
| **Riwayat Validasi** | ListView grouped by date, each tile: kode tiket, waktu HH:MM:SS, badge result, checkpoint, visitor_count. | Infinite scroll, filter by result optional. |
| **Input Manual Kode** | Dialog dengan TextField monospace, PrimaryButton *Cek*. | Dipakai saat QR rusak. |
| **Koneksi Terputus** | Snackbar persistent *Tidak ada koneksi*; scanner disabled. | Auto‑retry saat online. |

**ThemeData (Flutter)**
```dart
final ThemeData appTheme = ThemeData(
  useMaterial3: true,
  colorScheme: ColorScheme.fromSeed(
    seedColor: Color(0xFFCF4647),
    primary: Color(0xFFCF4647),
    secondary: Color(0xFFF5D061),
    surface: Color(0xFFF8F6F6),
    onPrimary: Colors.white,
    onSurface: Color(0xFF2A363B),
    error: Color(0xFFC62828),
    errorContainer: Color(0xFFFFEBEE),
    tertiary: Color(0xFF2A363B),
  ),
  textTheme: GoogleFonts.interTextTheme().apply(
    bodyColor: Color(0xFF2A363B),
    displayColor: Color(0xFF2A363B),
  ),
  cardTheme: CardThemeData(
    color: Colors.white,
    elevation: 2,
    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
    margin: EdgeInsets.all(12),
  ),
  elevatedButtonTheme: ElevatedButtonThemeData(
    style: ElevatedButton.styleFrom(
      backgroundColor: Color(0xFFCF4647),
      foregroundColor: Colors.white,
      minimumSize: Size(double.infinity, 48),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      textStyle: TextStyle(fontSize: 14, fontWeight: FontWeight.w600),
    ),
  ),
  inputDecorationTheme: InputDecorationTheme(
    filled: true,
    fillColor: Colors.white,
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 14),
    border: OutlineInputBorder(
      borderRadius: BorderRadius.circular(8),
      borderSide: BorderSide(color: Color(0xFFCFD8DC)),
    ),
    focusedBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(8),
      borderSide: BorderSide(color: Color(0xFFCF4647), width: 2),
    ),
    errorBorder: OutlineInputBorder(
      borderRadius: BorderRadius.circular(8),
      borderSide: BorderSide(color: Color(0xFFC62828), width: 2),
    ),
    labelStyle: TextStyle(color: Color(0xFF2A363B)),
  ),
);
```

---

## 7. Print Layout (Thermal 58/80 mm)
- **CSS**: `@media print { @page { size: 80mm auto; margin: 0; } body { font-family: 'JetBrains Mono', monospace; font-size: 11px; color:#2A363B; } .no-print { display:none; } }`
- **Structure** (per PRD §10):
  1. Header: `business_name`, `business_address` (center, bold).
  2. Meta: tanggal/time, invoice, petugas, nama, asal, bayar.
  3. Line items: qty × name @price, subtotal.
  4. Diskon line (label *Diskon Rombongan* / *Diskon Promo* + %).
  5. Total, Cash/Debit, kembalian.
  6. **TOTAL PENGUNJUNG**.
  7. QR code (center) + ticket code below.
  8. Footer: "BERLAKU HANYA HARI INI: DD BULAN YYYY SAMPAI PUKUL 24.00 WITA\nTIDAK DAPAT DI-REFUND" + `receipt_footer`.
- QR generated with **error correction M**, data = ticket_code only.

---

## 8. Accessibility & Localization
- Semua teks Bahasa Indonesia.
- ARIA labels pada button/icon.
- Focus order logical (tabindex).
- Contras sesuai WCAG AA.
- Mobile: `Semantics` labels untuk scanner overlay.

---

## 9. Responsive Breakpoints (Web)
| Breakpoint | Layout |
|------------|--------|
| ≤ 576 px | Stacked cards, full‑width buttons, modal full‑screen. |
| 577‑991 px | Two‑column dashboard cards, side‑by‑side POS steps. |
| ≥ 992 px | Full 4‑card row, POS stepper horizontal. |

---

## 10. Asset Checklist
- Logo SVG (primary red on transparent).
- Favicon 32×32 (primary).
- Icon set: Material Icons (outlined) – color inherits `currentColor`.
- Sound files: `success.wav`, `error.wav` (short, <200 ms).
- Fonts: Inter (woff2), JetBrains Mono (woff2).

---

## 11. Handoff Notes for Developers
1. **CSS Variables** (root) – define palette once:
   ```css
   :root{
     --c-primary:#CF4647; --c-primary-hover:#B33A3B;
     --c-gold:#F5D061; --c-gold-hover:#E0B94E;
     --c-bg:#F8F6F6; --c-surface:#FFFFFF;
     --c-text:#2A363B; --c-muted:#6B7A80;
     --c-success:#2E7D32; --c-error:#C62828; --c-warning:#F57F17; --c-info:#1565C0;
     --radius:8px; --shadow:0 2px 6px rgba(42,54,59,.08);
   }
   ```
2. **Flutter** – use `ThemeData` above; all colours via `Theme.of(context).colorScheme`.
3. **Print CSS** – place in `public/css/print.css` and load on POS page only.
4. **QR Generation** – backend returns `ticket_code`; front‑end renders via `qr_flutter` (mobile) or `qrcode.js` (web). No extra payload.
5. **Error handling** – map API error codes to toast colors (table 7.3). Keep messages identical to PRD.

---

## 12. Version Control
| Versi | Tanggal | Penulis | Catatan |
|-------|---------|---------|---------|
| 1.0 | 2026‑10‑06 | Hermes Agent | Initial design from PRD v2.5 |

## 13. Catatan Implementasi Mockup (`index.html`)

Ditambahkan setelah mockup statis dibangun, agar dokumen ini menggambarkan sistem yang benar-benar ada.
Mockup memakai token di bawah; angkanya sudah diverifikasi di browser (1440 / 1024 / 390 px).

### 13.1 Token yang dipakai (`:root`)

```css
--c-primary:#CF4647; --c-primary-hover:#B33A3B; --c-primary-press:#9E3233; --c-primary-soft:#FBEBEB;
--c-gold:#F5D061;    --c-gold-hover:#E0B94E;    --c-gold-soft:#FCF3DB;   --c-gold-ink:#6B5210;
--c-bg:#F8F6F6;      --c-surface:#FFFFFF;       --c-surface-2:#F1EEEE;
--c-ink:#2A363B;     --c-ink-3:#55666D;         --c-muted:#5F6F76;
--c-line:#E0E0E0;    --c-line-strong:#CFD8DC;
--c-success:#2E7D32; --c-error:#C62828;         --c-warning:#B35C00;     --c-info:#1565C0;
--radius:8px; --radius-sm:6px; --shadow:0 2px 6px rgba(42,54,59,.08);
--shadow-lg:0 18px 40px -14px rgba(42,54,59,.34), 0 2px 6px rgba(42,54,59,.08);
--ease:cubic-bezier(.16,1,.3,1); --rail-w:252px;
```

Penyesuaian dari §1: `--c-muted` digelapkan dari `#6B7A80` (kontras 4,4:1 pada putih) menjadi
`#5F6F76` (5,2:1) agar lolos WCAG AA; `--c-warning` menjadi `#B35C00` agar teks peringatan di atas
latar `#FDF0E0` tetap terbaca. Warna status di badge selalu dipasangkan dengan teks, tidak pernah
warna saja.

### 13.2 Komponen milik surface ini

| Komponen | Perilaku |
|---|---|
| **Jam hari dagang** (`.dayclock`) | Chip ink di baris atas: tanggal hari dagang WITA, sisa waktu ke 24.00 WITA dalam mono, dan garis progres hari (`transform:scaleX`, tick 15 detik). Ini pengingat tetap bahwa tiket hari ini hangus tengah malam (BR-01/BR-27). Hadir juga di layar login. |
| **Pratinjau struk hidup** (`.receipt`) | Panel kanan POS merender struk thermal 80 mm mengikuti form, bukan setelah simpan. Rombongan = 1 potongan; individual = 1 potongan per tiket (potongan 2+ hanya memuat QR). Baris yang berubah berkedip gold (`flash-ink`). |
| **Papan hari** (`.board`) | Satu panel berisi empat sel berbatas hairline (uang di sel gold) + bar komposisi tunai/debit. Bukan empat kartu terpisah, agar terbaca sebagai satu instrumen. |
| **Rail ink berstatus** | Item navigasi aktif = pil gold dengan teks ink; pada ≤1024 px rail menyusut jadi rel ikon, pada ≤760 px menjadi strip horizontal di atas konten. |
| **Tabel padat** | Header ink, baris 48 px, angka rata kanan dengan `tabular-nums`, kode nota dan tiket dalam mono. Tabel lebar (`tbl--wide`) menggeser horizontal, tidak memotong kolom. |
| **QR** | Dirender klien dari `public/js/qrcode.js` (qrcode-generator, MIT), error correction M, isi hanya kode tiket. |

### 13.3 Cetak

`@media print` pada `index.html`: `@page{size:80mm auto}`, seluruh `body > *` disembunyikan dan hanya
`#print-area` yang tampil. Sebelum `window.print()` dipanggil, potongan struk disalin ke `#print-area`,
satu potongan per halaman (`page-break-after:always`).

### 13.4 Yang belum final

- Data di mockup **sintetis**; hanya format kode, aturan batas, dan tata letak struk yang berasal dari PRD.
- Logo masih mark SVG buatan sendiri (bukan aset brand), ikon memakai satu set SVG inline 24 px.
- Inter & JetBrains Mono dimuat dari Google Fonts pada mockup; untuk produksi disarankan self-host di
  `public/fonts/` (bagian 10).
- Mockup belum dipecah ke `app/Views/`; pemecahannya menunggu persetujuan peninjauan.

---  
*End of DESIGN.md*