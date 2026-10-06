---
version: 1
slug: "index-html"
primary_target: "index.html"
related_targets: []
---

# Surface brief — index.html (Web Admin Loket)

Target: `index.html` (mockup statis satu file, ditinjau dulu sebelum dipecah ke view CI4).
Related: `DESIGN.md`, `PRD.md`.

## Scope & mode

Satu file `index.html` berisi seluruh layar web yang terdaftar di `DESIGN.md` §5 — Login, Dashboard,
POS Loket, Riwayat Penjualan, Master Jenis Tiket, Master Pengguna, Checkpoint, Pengaturan, Laporan —
dengan navigasi samping. Mode surface: **Operate**. Dunia visual sudah dipin oleh `DESIGN.md`; tidak
ada pemilihan dunia baru.

## Audience, job, action, proof

Petugas loket yang melayani antrean tamu berurutan. Pekerjaannya: satu transaksi selesai — data
pengunjung, jenis & jumlah, bayar, cetak — tanpa berpindah layar. Bukti/konten: data sintetis yang
konsisten satu sama lain (satu dataset menggerakkan metrik dashboard, tabel riwayat, dan laporan) plus
data nyata dari seed & PRD (jenis tiket, harga, diskon, `settings`, contoh struk INV-20261005-K7M3QX).
Kendala: tanpa framework front-end, tanpa API nyata, harus bisa dibuka langsung dan dibuka di browser
untuk ditinjau; seluruh teks Bahasa Indonesia.

## Chosen direction & memorable moment

**POS sebagai instrumen kerja**: form transaksi dan struk thermal yang akan tercetak berdiri
berdampingan dalam satu frame, dan struk itu hidup mengikuti form. Momen yang diingat: **jam hari
dagang** — chip di topbar yang menghitung sisa waktu sampai 00.00 WITA, karena setiap tiket yang
dijual hari itu mati pada tengah malam.

## Unresolved decisions

- Apakah mockup dipecah ke `app/Views/layouts/main.php` + `app/Views/pages/*` setelah disetujui.
- Ikon dan logo final (saat ini SVG inline buatan sendiri, bukan aset brand).
- Apakah mode kiosk-printing dipakai di loket (memengaruhi perilaku tombol Cetak).

## Direction contract

THESIS: Loket adalah instrumen kerja, bukan laporan — form penjualan dan struk yang akan tercetak
berdiri dalam satu frame, sehingga petugas tidak pernah meninggalkan transaksi untuk memeriksa apa
yang akan tertulis di kertas. Yang ditolak: wizard kartu-per-field dengan dialog cetak sebagai
pikiran-kemudian, dan shell admin generik (rail gelap + kartu abu-abu).

OWN-WORLD: Ink `#2A363B` memiliki shell — navigasi, kertas struk, header tabel, dan modul QR. Lantai
halaman `#F8F6F6`, panel putih dengan satu border hairline `#E0E0E0` dan satu bayangan lembut. Primary
`#CF4647` memegang setiap aksi dan tinta data grafik; gold `#F5D061` hanya untuk uang dan tenggat hari.
Inter untuk antarmuka, JetBrains Mono dipakai hanya untuk kode tiket, nomor nota, dan struk. Radius 8px,
grid 4px, baris tabel padat 48px.

STORY: Petugas masuk, melihat hari dagangnya (jam tenggat, penjualan hari ini, tiket yang belum masuk),
mengisi satu transaksi, membayar, mencetak, lalu bisa melacak kembali setiap nota hari itu untuk cetak
ulang atau void.

FIRST VIEWPORT: Rail ink 248px di kiri; di atas konten, baris tunggal berisi judul layar dan chip jam
hari dagang (sisa waktu ke 24.00 WITA dengan garis progres hari). Kemudian papan hari ini: empat angka
— penjualan hari ini, tiket terbit, pengunjung sudah masuk, tiket aktif belum dipakai — dengan
komposisi Cash/Debit satu baris bertumpuk, grafik penjualan tujuh hari, dan tabel transaksi terbaru
tempat aksi Cetak Ulang dan Void sudah terlihat statusnya.

FORM: papan kerja loket bergaya instrumen (rail + papan hari + tabel padat). Tidak ada roll: dunia
visual sudah dipin oleh `DESIGN.md` §1–§4 dan struktur dashboard oleh `DESIGN.md` §5, sehingga aturan
`mode-operate.md` (dunia hanya meminjamkan type, palette, density, dan satu signature move) langsung
mengikat. Ambisi yang dibawa: struk thermal hidup berdampingan dengan form POS, dan jam tenggat
sebagai elemen global.

FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
