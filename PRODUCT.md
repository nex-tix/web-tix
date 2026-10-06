# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Petugas loket (login sebagai akun role **Admin**) di gerbang Pertunjukan Kecak Uluwatu. Satu orang
melayani antrean tamu berurutan pada satu terminal loket, siang sampai malam, di ruang loket yang
terang. Pekerjaannya: memasukkan data pengunjung, memilih jenis penjualan (individual atau
rombongan), menerima pembayaran tunai atau debit, mencetak tiket ber-QR, dan sesekali mencetak ulang
atau mem-void transaksi yang salah.

Petugas gate memakai aplikasi Flutter terpisah (di luar cakupan surface web ini).

## Product Purpose

Menjual tiket masuk pertunjukan Kecak, menerbitkan satu QR per tiket, dan memvalidasi QR itu satu kali
di gate. Keberhasilan = antrean loket selesai cepat tanpa salah hitung, dan tidak ada tiket yang bisa
dipakai dua kali.

## Positioning

Tiket hanya berlaku pada hari pembelian sampai pukul 24.00 WITA, tanpa pemilihan tanggal, tanpa
refund, tanpa penjadwalan ulang. Harga, diskon, dan tanggal berlaku ditentukan server; petugas loket
tidak mengetik diskon. Check-in dilakukan satu request atomik dengan row lock, sehingga satu QR tidak
bisa lolos dua gate.

## Operating Context

- Zona waktu tunggal **WITA** (UTC+8); hari dagang berganti tepat 00.00 WITA dan tiket yang belum
  dipakai kedaluwarsa saat itu juga.
- Loket dan gate **full online**; tanpa koneksi penjualan dan validasi tidak diproses, tanpa antrean
  offline.
- Cetak memakai printer thermal 58/80 mm lewat dialog cetak browser (`@media print`), satu QR per
  potongan kertas.
- Pembayaran hanya **Cash** (uang diterima ≥ total, kembalian dihitung sistem) atau **Debit** (wajib
  nomor referensi EDC). Tanpa payment gateway.
- Data pengunjung yang wajib hanya nama dan asal (Domestik/Mancanegara + kota atau negara); alamat dan
  nomor HP boleh kosong.
- Data pribadi dianonimkan otomatis setelah 12 bulan; rekap demografi tetap memakai kolom asal.

## Capabilities and Constraints

Surface web ini mencakup: Login, Dashboard, POS Loket, Riwayat Penjualan (cetak ulang + void), Master
Jenis Tiket, Master Pengguna (Admin & Gate), Checkpoint, Pengaturan, dan Laporan (ekspor PDF/Excel).

- Batas individual maks 4 tiket per transaksi; rombongan 1 tiket group per transaksi, minimum 5 dan
  maksimum 15 orang. Satu transaksi tidak mencampur individual dan rombongan.
- Diskon hanya dari `ticket_types.discount_percent` (satu sumber) — rombongan default 5%, promo
  individual dipasang manual oleh Admin. Tabel `settings` tidak memuat diskon.
- Harga, diskon, dan tanggal berlaku disalin (snapshot) ke `ticket_details` saat transaksi dibuat;
  perubahan harga berlaku untuk transaksi berikutnya saja.
- `transactions.subtotal` = kotor sebelum diskon; `total_amount` = yang dibayar.
- Cetak ulang hanya untuk tiket `active` yang masih berlaku hari ini; void hanya bila belum ada tiket
  `used` pada transaksi dan wajib mengisi alasan.
- QR error correction level M, isinya hanya kode tiket, tanpa data pribadi.
- Stack: PHP CodeIgniter 4 + MySQL 8 (skema & migrasi sudah ada di `app/Database/Migrations`), CSS
  `@media print` untuk thermal, tanpa framework front-end.

## Brand Commitments

Nama tempat dan alamat pada struk diatur lewat Pengaturan: "Pertunjukan Kecak Uluwatu",
"Pecatu, Kuta Selatan, Badung", footer "Tidak dapat di-refund". Seluruh antarmuka berbahasa Indonesia.
Palet dan tipografi terikat pada `DESIGN.md` (Primary `#CF4647`, Gold `#F5D061`, off-white `#F8F6F6`,
ink `#2A363B`, Inter + JetBrains Mono).

## Evidence on Hand

- `PRD.md` v2.5 — sumber kebenaran produk, alur bisnis, aturan (BR-01…BR-34), skema DB, tata letak
  struk thermal, parameter `settings`. Semua fakta di atas berasal dari dokumen ini.
- `DESIGN.md` v1.0 — palet, tipografi, komponen, dan daftar layar web.
- `app/Database/Migrations/*` dan `app/Database/Seeds/*` — skema nyata, jenis tiket awal
  (Adult Rp100.000 / Child Rp60.000 / Group Rp100.000 diskon 10%), checkpoint "Gerbang Utama",
  dan nilai awal `settings`.
- Belum ada: data transaksi nyata, logo, foto, atau aset cetak. Mockup memakai data sintetis yang
  diberi label; klaim komersial (harga, jumlah penjualan, nama tamu) tidak boleh dianggap nyata.

## Product Principles

1. Satu layar, satu pekerjaan: petugas tidak berpindah halaman untuk menyelesaikan satu transaksi.
2. Angka yang tampil harus bisa dicocokkan dengan struk dan uang di laci.
3. Batas dan diskon ditegakkan sistem, bukan ingatan petugas.
4. Hari dagang terlihat: setiap layar tahu tiket yang dijual berakhir pukul 24.00 WITA.
5. Riwayat tidak boleh berubah diam-diam: harga, diskon, dan validasi tersimpan pada titik transaksi.

## Accessibility & Inclusion

Tidak ada kebutuhan khusus yang dikonfirmasi. Target default: kontras WCAG AA pada teks, fokus
keyboard terlihat pada semua kontrol, tombol ikon punya label aksesibel, dan status tidak pernah
disampaikan lewat warna saja (selalu ada teks pendamping).
