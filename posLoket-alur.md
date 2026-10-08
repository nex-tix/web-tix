# ALUR PENGERJAAN FASE 2.3 

**important**
 PosController menampung data (function store), TransactionService mengeksekusi logika bisnis (function create ke 5 table: transactions->tickets->ticket_detail->print_logs)

 **Kondisi**
 TransactionService menggunakan model dari masing-masing ke 5 table

 admin
                    │
                    ▼
             PosController
                    │
             POST /store
                    │
                    ▼
          TransactionService
                    │
          ┌─────────┴─────────┐
          │   DB TRANSACTION  │
          │                   │
          ▼                   ▼
      customers         transactions
                              │
                              ▼
                           tickets
                              │
                              ▼
                       ticket_details
                              │
                              ▼
                         print_log
                              │
                         COMMIT
                              │
                              ▼
                    Transaksi berhasil
                              │
                              ▼
                     PrintController
                              │
                              ▼
                       Thermal Receipt

Jadi untuk kondisi project kamu sekarang, langkah berikutnya bukan membuat banyak controller. Yang paling tepat adalah membuat TransactionService, lalu menghubungkannya ke PosController yang sudah kamu punya. Setelah itu kita bisa membedah data POST dari form → validasi → isi setiap kolom customers, transactions, tickets, ticket_details, dan print_log satu per satu.

**URUTAN**
customers
   ↓
transactions
   ↓
tickets
   ↓
ticket_details
   ↓
print_log

Kenapa harus berurutan?

Karena tabel berikutnya membutuhkan ID dari tabel sebelumnya.