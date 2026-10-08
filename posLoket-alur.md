Berdasarkan checklist **2.2–2.4** itu, sebaiknya alur POS jangan dibuat sebagai satu controller besar. Pisahkan antara **menampilkan form, menghitung/validasi, menyimpan transaksi, dan mencetak**.

### 1. Gambaran alur keseluruhan

```text
Petugas buka POS
      │
      ▼
POSController::index()
      │
      ├── Ambil settings
      ├── Ambil jenis tiket aktif
      ├── Ambil konfigurasi aturan
      └── Tampilkan form POS
              │
              ▼
       Petugas mengisi form
              │
              ├── Data pengunjung
              ├── Individual / Rombongan
              ├── Jenis tiket
              ├── Jumlah
              └── Pembayaran
              │
              ▼
     JavaScript hitung total live
              │
              ▼
      Submit transaksi
              │
              ▼
POSController::store()
              │
              ▼
       Validasi server
              │
              ▼
   DB Transaction / Atomic
              │
       ┌──────┴──────┐
       ▼             ▼
   customers     transactions
                       │
                       ▼
                     tickets
                       │
                       ▼
                 ticket_details
                       │
                       ▼
                print_log(initial)
                       │
                       ▼
               Commit database
                       │
                       ▼
            Halaman hasil transaksi
                       │
                       ▼
               Cetak thermal
```

---

# 2. Controller yang diperlukan

Untuk modul POS, saya menyarankan minimal:

```text
app/Controllers/
├── POSController.php
├── PrintController.php
└── TransactionController.php
```

Tetapi untuk implementasi yang lebih rapi, controller sebenarnya **tidak perlu menangani seluruh business logic sendiri**.

Strukturnya bisa:

```text
Controller
    ↓
Service
    ↓
Model
    ↓
Database
```

Misalnya:

```text
POSController
      ↓
TransactionService
      ↓
CustomerModel
TransactionModel
TicketModel
TicketDetailModel
PrintLogModel
```

---

# 3. `POSController`

Controller utama untuk halaman kasir.

## Method

```php
class POSController extends BaseController
{
    public function index()
    {
        //
    }

    public function store()
    {
        //
    }
}
```

### `index()`

Tugasnya **menampilkan halaman POS**, bukan menyimpan transaksi.

Yang diambil:

* jenis tiket aktif
* kategori tiket
* harga tiket
* konfigurasi `max_individual_per_tx`
* `min_group_size`
* `max_group_members`
* tanggal hari ini
* settings struk
* metode pembayaran

Alurnya:

```text
GET /pos
   ↓
POSController::index()
   ↓
TicketTypeModel
SettingsModel
Configuration
   ↓
View POS
```

Contohnya:

```php
public function index()
{
    $ticketTypes = $this->ticketTypeModel
        ->where('is_active', 1)
        ->findAll();

    return view('pos/index', [
        'ticketTypes' => $ticketTypes,
        'today'       => date('Y-m-d'),
    ]);
}
```

---

# 4. Form POS

Di halaman tersebut petugas mengisi:

### Data pengunjung

```text
Nama
Asal
 ├── Domestik
 │     └── Kota/Kabupaten
 │
 └── Mancanegara
       └── Negara

Alamat
Nomor HP
```

### Mode pembelian

```text
○ Individual
○ Rombongan
```

Kemudian jenis tiket ditampilkan berdasarkan kategori.

Contohnya:

```text
Individual
----------------
Adult
Child


Rombongan
----------------
Adult Group
Child Group
```

Aturan **BR-33** berarti frontend tidak boleh memberikan pilihan jenis tiket yang tidak sesuai mode.

---

# 5. Perhitungan live dilakukan JavaScript

Bagian ini **tidak perlu controller setiap kali angka berubah**.

Misalnya:

```text
Adult        4 × Rp100.000
Child        2 × Rp50.000
-------------------------
Subtotal       Rp500.000

Diskon 1%
-------------------------
Diskon          Rp6.000

TOTAL          Rp494.000
```

JavaScript menghitung:

```text
jumlah
×
harga
=
subtotal

subtotal
-
diskon
=
total
```

Ini hanya untuk **UX**.

Server tetap menghitung ulang saat `store()`.

> Jangan percaya `total` yang dikirim browser.

---

# 6. `POSController::store()`

Ini bagian paling penting.

Ketika kasir menekan:

```text
[ BAYAR / SIMPAN TRANSAKSI ]
```

request masuk:

```text
POST /pos/store
```

kemudian:

```php
public function store()
{
    // 1. Validasi request

    // 2. Validasi business rules

    // 3. TransactionService menjalankan transaksi DB

    // 4. Redirect ke halaman sukses
}
```

Tetapi saya **tidak menyarankan** memasukkan seluruh proses database langsung ke controller.

Lebih baik:

```text
POSController
      ↓
TransactionService
      ↓
Database Transaction
```

---

# 7. Validasi tahap pertama

`POSController::store()` menerima data:

```text
customer_name
origin_type
city_country
address
phone

purchase_mode

ticket_items[]

payment_method
cash_received
edc_reference
```

Kemudian validasi dasar.

Contohnya:

```php
$rules = [
    'customer_name' => 'required',
    'origin_type'   => 'required|in_list[domestic,foreign]',
    'address'       => 'required',
    'phone'         => 'required',
    'purchase_mode' => 'required|in_list[individual,group]',
    'payment_method'=> 'required|in_list[cash,debit]',
];
```

---

# 8. Validasi Individual

Kalau:

```text
purchase_mode = individual
```

maka:

```text
jumlah tiket <= max_individual_per_tx
```

Dalam konfigurasi kamu:

```text
max_individual_per_tx = 4
```

Jadi:

```text
1 tiket  ✅
2 tiket  ✅
3 tiket  ✅
4 tiket  ✅
5 tiket  ❌
```

Controller/service harus menolak jika:

```php
$totalMembers > $maxIndividualPerTx
```

---

# 9. Validasi Rombongan

Kalau:

```text
purchase_mode = group
```

maka:

```text
min_group_size <= total member <= max_group_members
```

Dengan konfigurasi:

```text
min_group_size    = 5
max_group_members = 15
```

maka:

```text
1–4   ❌
5     ✅
6     ✅
...
15    ✅
16    ❌
```

Jika 4 orang memilih mode rombongan:

```text
Tidak dapat menggunakan mode rombongan.
Minimal jumlah anggota rombongan adalah 5 orang.
```

---

# 10. Validasi kategori tiket

Ini penting karena checklist kamu mengatakan:

> Tolak campuran individual + rombongan dan jenis lintas kategori.

Misalnya:

```text
Individual Adult
+
Group Adult
```

harus:

```text
❌
```

Begitu juga kalau satu transaksi mencoba memasukkan jenis tiket dari kategori yang berbeda.

Jadi service melakukan:

```text
purchase_mode
       ↓
validasi semua ticket_type
       ↓
kategori cocok?
       ↓
YES → lanjut
NO  → reject
```

---

# 11. Validasi pembayaran

### Cash

Misalnya:

```text
Total       Rp494.000
Dibayar     Rp500.000
```

maka:

```text
Kembalian = Rp6.000
```

Server memastikan:

```php
$cashReceived >= $total
```

Kalau:

```text
Total       Rp494.000
Dibayar     Rp400.000
```

maka:

```text
❌ Uang pembayaran kurang
```

---

# 12. Pembayaran Debit

Kalau:

```text
payment_method = debit
```

maka:

```text
edc_reference
```

wajib diisi.

Contoh:

```text
Metode pembayaran : Debit
No. Referensi EDC : EDC-20261007-001
```

Kalau kosong:

```text
❌ Nomor referensi EDC wajib diisi.
```

---

# 13. Setelah validasi → `TransactionService`

Setelah semua validasi lolos:

```text
POSController
      ↓
TransactionService::create()
```

Service inilah yang menjalankan **BR-32**.

```php
$db->transStart();

customers
   ↓
transactions
   ↓
tickets
   ↓
ticket_details
   ↓
print_log

$db->transComplete();
```

---

# 14. Insert `customers`

Pertama simpan data pengunjung.

Contoh:

```text
customers

id
name
origin_type
city_country
address
phone
```

Misalnya:

```text
Nama       : Made Arya
Asal       : Domestik
Kota       : Denpasar
Alamat     : ...
HP         : ...
```

Hasil:

```text
customer_id = 125
```

---

# 15. Insert `transactions`

Kemudian membuat transaksi.

Misalnya:

```text
transaction_code = TX-20261007-0001
customer_id      = 125
purchase_mode    = group
payment_method   = cash
subtotal         = 500000
discount_amount  = 6000
total_amount     = 494000
cash_received    = 500000
change_amount    = 6000
status           = completed
```

Status awal:

```text
completed
```

sesuai BR-16.

---

# 16. Generate ticket

Setelah transaksi berhasil dibuat, service membuat ticket.

Misalnya 6 orang:

```text
Transaction
TX-20261007-0001
        │
        ├── Ticket 1
        ├── Ticket 2
        ├── Ticket 3
        ├── Ticket 4
        ├── Ticket 5
        └── Ticket 6
```

Setiap ticket mempunyai kode unik.

Contoh:

```text
NXT-7K4P9A
NXT-2M8Q3B
NXT-9X5D7C
...
```

Dan:

```text
status = active
```

---

# 17. `ticket_details`

Setiap ticket kemudian memiliki snapshot harga.

Ini **sangat penting**.

Misalnya harga saat transaksi:

```text
unit_price       = 100000
discount_percent = 10
discount_amount  = 10000
subtotal         = 90000
```

Data ini disimpan di `ticket_details`.

Kenapa?

Karena harga `ticket_types` bisa berubah di masa depan.

Misalnya sekarang:

```text
Adult = Rp100.000
```

bulan depan:

```text
Adult = Rp120.000
```

Transaksi lama tetap harus menunjukkan:

```text
Rp100.000
```

bukan mengikuti harga baru.

Jadi `ticket_details` adalah **snapshot transaksi**.

---

# 18. `print_log`

Setelah ticket berhasil dibuat:

```text
print_log
```

dibuat dengan:

```text
print_type = initial
```

Misalnya:

```text
transaction_id
ticket_id
print_type = initial
printed_by
printed_at
```

Ini mencatat bahwa tiket tersebut sudah mengalami **cetak pertama**.

---

# 19. Atomic transaction

Semua proses tadi harus berada dalam:

```php
$db->transStart();

...

$db->transComplete();
```

atau mekanisme transaction yang setara.

Sehingga:

```text
customer        ✅
transaction     ✅
ticket 1        ✅
ticket 2        ❌
```

tidak boleh menghasilkan database:

```text
customer        ada
transaction     ada
ticket 1        ada
ticket 2        tidak ada
```

Jika ticket ke-2 gagal:

```text
ROLLBACK
```

semuanya dibatalkan.

Hasil akhirnya:

```text
customers       ❌
transactions    ❌
tickets         ❌
ticket_details  ❌
print_log       ❌
```

Ini inti dari **BR-32**.

---

# 20. Setelah commit

Kalau semua berhasil:

```text
COMMIT
   ↓
Transaction berhasil
   ↓
redirect
   ↓
POSController::success()
```

Misalnya:

```text
/pos/success/TX-20261007-0001
```

Halaman ini menampilkan:

```text
TRANSAKSI BERHASIL

No. Transaksi:
TX-20261007-0001

Total:
Rp494.000

[ CETAK STRUK ]
[ TRANSAKSI BARU ]
```

---

# 21. `PrintController`

Saya sarankan pencetakan dipisahkan.

Misalnya:

```php
class PrintController extends BaseController
{
    public function transaction($transactionId)
    {
        //
    }

    public function reprint($ticketId)
    {
        //
    }
}
```

---

# 22. Cetak pertama

Ketika:

```text
[CETAK STRUK]
```

ditekan:

```text
PrintController::transaction()
```

mengambil:

```text
transaction
tickets
ticket_details
settings
```

kemudian menghasilkan halaman thermal:

```text
58mm / 80mm
```

CSS:

```css
@media print {
    ...
}
```

---

# 23. Struktur potongan struk

Untuk transaksi 6 orang:

```text
┌─────────────────────┐
│    KECAK ULUWATU    │
│                     │
│ TRANSAKSI            │
│ TX-20261007-0001    │
│                     │
│ Adult Group   6     │
│ Rp500.000           │
│ Diskon Rombongan    │
│ -Rp6.000            │
│                     │
│ TOTAL Rp494.000     │
│ CASH Rp500.000      │
│ KEMBALI Rp6.000     │
└─────────────────────┘
```

Kemudian potongan tiket:

```text
┌─────────────────────┐
│    KECAK ULUWATU    │
│                     │
│ TICKET              │
│ NXT-7K4P9A          │
│                     │
│ Adult               │
│                     │
│      [ QR CODE ]    │
│                     │
│ BERLAKU HARI INI    │
│ SAMPAI 24.00 WITA   │
│                     │
│ TIDAK DAPAT REFUND  │
└─────────────────────┘
```

Potongan pertama berbeda dari tiket berikutnya sesuai requirement kamu.

---

# 24. QR Code

QR **tidak boleh berisi**:

```text
nama
nomor HP
alamat
harga
```

Isi QR cukup:

```text
NXT-7K4P9A
```

Saat dicetak:

```text
Ticket Code
      ↓
QR Generator
      ↓
QR Level M
      ↓
thermal receipt
```

Nantinya mobile validator cukup membaca:

```text
NXT-7K4P9A
```

kemudian API mencari ticket tersebut.

---

# 25. Cetak ulang Admin

Ini berbeda dengan cetak pertama.

Alurnya:

```text
Admin
 ↓
Riwayat / Detail transaksi
 ↓
Pilih tiket
 ↓
[CETAK ULANG]
 ↓
PrintController::reprint()
```

Sebelum mencetak, server harus memastikan:

```text
ticket.status == active
```

dan:

```text
ticket.valid_date == hari ini
```

Jika:

```text
used
expired
cancelled
```

maka:

```text
❌ Tidak dapat dicetak ulang
```

Kalau valid:

```text
generate thermal
      ↓
print_log
      ↓
print_type = reprint
```

---

# 26. Controller akhirnya

Kalau dirangkum, struktur controller yang saya rekomendasikan:

```text
app/Controllers/
│
├── POSController.php
│   ├── index()
│   └── store()
│
├── PrintController.php
│   ├── transaction()
│   └── reprint()
│
└── TransactionController.php
    ├── index()
    └── show()
```

### Fungsinya

| Controller              | Fungsi                         |
| ----------------------- | ------------------------------ |
| `POSController`         | Form POS dan membuat transaksi |
| `TransactionController` | Riwayat/detail transaksi       |
| `PrintController`       | Cetak awal dan cetak ulang     |

---

# 27. Service yang sebaiknya digunakan

Dari requirement ini, justru **service layer menjadi bagian penting**.

Saya akan membuat kira-kira:

```text
app/Services/
│
├── TransactionService.php
├── TicketService.php
├── PricingService.php
├── CodeGeneratorService.php
├── PrintService.php
└── QrCodeService.php
```

Perannya:

### `TransactionService`

Menangani:

```text
customers
transactions
tickets
ticket_details
print_log
```

dalam satu DB transaction.

### `PricingService`

Menangani:

```text
harga
subtotal
diskon
total
kembalian
```

### `TicketService`

Menangani:

```text
individual
group
jumlah tiket
ticket status
valid date
```

### `CodeGeneratorService`

Membuat:

```text
transaction_code
ticket_code
```

### `PrintService`

Menyiapkan data struk dan proses print.

### `QrCodeService`

Menghasilkan:

```text
ticket_code → QR
```

---

# 28. Jadi alur finalnya

Secara arsitektur:

```text
                    ┌─────────────────┐
                    │   POSController │
                    └────────┬────────┘
                             │
                    Form / Submit
                             │
                             ▼
                  ┌─────────────────────┐
                  │ TransactionService  │
                  └──────────┬──────────┘
                             │
             ┌───────────────┼────────────────┐
             ▼               ▼                ▼
       PricingService   TicketService   CodeGenerator
             │               │                │
             └───────────────┼────────────────┘
                             ▼
                     DB TRANSACTION
                             │
          ┌──────────────────┼──────────────────┐
          ▼                  ▼                  ▼
     customers         transactions          tickets
                                                  │
                                                  ▼
                                          ticket_details
                                                  │
                                                  ▼
                                            print_log
                             │
                             ▼
                           COMMIT
                             │
                             ▼
                  ┌──────────────────┐
                  │ PrintController  │
                  └────────┬─────────┘
                           │
                    PrintService
                           │
                    ┌──────┴──────┐
                    ▼             ▼
                  QR Code       Thermal
```

**Intinya:** `POSController` sebaiknya tipis. Ia menerima request dan menyerahkan pekerjaan bisnis ke `TransactionService`. Dengan begitu requirement **atomic transaction, validasi 1–4 / 5–15, snapshot harga, status, QR, dan print log** tidak bercampur menjadi controller yang sulit dirawat.

Kalau kamu sekarang sudah punya `CodeGeneratorService` seperti yang kamu kirim sebelumnya, struktur ini juga bisa langsung disesuaikan dengan **Model CI4 yang sudah kamu buat**, sehingga kita bisa menentukan **file Controller + Service + Model mana yang benar-benar perlu dibuat dan mana yang sudah ada**.
