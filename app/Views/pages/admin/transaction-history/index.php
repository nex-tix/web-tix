<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Riwayat Penjualan</h4>
        <p class="text-slate-500">Daftar semua transaksi berdasarkan tanggal, metode bayar, dan kasir.</p>
      </div>
    </div>

    <?= view('components/transaction-history-filter', [
      'filters' => $filters ?? [],
      'users'   => $users ?? [],
    ]) ?>

    <div class="border-0 shadow-sm mb-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table
            id="<?= esc($table_id ?? 'transactionTable') ?>"
            class="table table-hover mb-0"
            style="width: 100%">
            <thead>
              <tr style="background: #1a1d20; color: #fff;">
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Kasir</th>
                <th>Metode Bayar</th>
                <th>Total</th>
                <th>Tiket</th>
                <th>Status</th>
                <th style="text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-light">
                <?php foreach ($transactions as $row): ?>
                  <tr>
                    <td>
                      <div class="fw-semibold"><?= date('d M Y', strtotime($row['created_at'])) ?></div>
                      <small><?= date('H:i', strtotime($row['created_at'])) ?> WITA</small>
                    </td>
                    <td class="font-monospace fw-bold"><?= esc($row['invoice_code']) ?></td>
                    <td><?= esc($row['admin_name'] ?? '-') ?></td>
                    <td>
                      <?php if (($row['payment_method'] ?? '') === 'cash'): ?>
                        <span class="badge bg-secondary">Tunai</span>
                      <?php else: ?>
                        <span class="badge bg-primary">Debit</span>
                      <?php endif; ?>
                    </td>
                    <td class="fw-semibold">Rp <?= number_format((float)($row['total_amount'] ?? $row['total'] ?? 0), 0, ',', '.') ?></td>
                    <td><?= (int)($row['ticket_count'] ?? 0) ?> tiket / <?= (int)($row['total_visitors'] ?? 0) ?> orang</td>
                    <td>
                      <?php if (($row['status'] ?? '') === 'cancelled' || ($row['status'] ?? '') === 'voided'): ?>
                        <span class="badge bg-danger">Dibatalkan</span>
                      <?php else: ?>
                        <span class="badge bg-success">Selesai</span>
                      <?php endif; ?>
                    </td>
                    <td style="text-align:center">
                      <a href="<?= base_url('admin/transaction-history/detail/' . $row['id']) ?>" class=" text-center btn btn-sm btn-outline-dark" title="Detail">
                        <i class="bi bi-eye action-icon"></i>
                      </a>
                        <!-- <button
                          type="button"
                          class="btn btn-sm btn-outline-danger"
                          title="Void Transaksi"
                          data-bs-toggle="modal"
                          data-bs-target="#voidTransactionModal"
                          data-void-url="<?= esc(base_url('admin/transaction-history/void/' . $row['id']), 'attr') ?>"
                          data-invoice="<?= esc($row['invoice_code'] ?? '-', 'attr') ?>">
                          <i class="bi bi-x-circle"></i>
                        </button> -->
                      <?php if (($row['status'] ?? '') === 'completed'): ?>
                        <!-- tombol edit pay-method: buka modal ubah metode bayar -->
                         <button type="button" class="btn btn-sm btn-outline-warning btn-edit"
                          title="Ubah Metode Bayar"
                          data-bs-toggle="modal"
                          data-bs-target="#editPaymentModal"
                          data-payment-url="<?= esc(base_url('admin/transaction-history/update-payment/' . $row['id']), 'attr') ?>"
                          data-invoice="<?= esc($row['invoice_code'] ?? '-', 'attr') ?>"
                          data-payment-method="<?= esc($row['payment_method'] ?? 'cash', 'attr') ?>">
                          <i class="bi bi-pencil"></i>
                         </button>

                        <!-- Tombol cetak ulang: buka modal, daftar tiket diambil via AJAX -->
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-primary"
                          title="Cetak Ulang"
                          data-bs-toggle="modal"
                          data-bs-target="#reprintTransactionModal"
                          data-reprint-url="<?= esc(base_url('admin/transaction-history/reprint-data/' . $row['id']), 'attr') ?>">
                          <i class="bi bi-printer"></i>
                        </button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="modal fade" id="voidTransactionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="voidTransactionForm" method="POST" action="" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h6 class="modal-title">Void Transaksi</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-2">
            Invoice: <strong id="voidInvoiceCode">-</strong>
          </p>
          <div class="alert alert-warning py-2 mb-3">
            <i class="bi bi-exclamation-triangle"></i>
            Void membatalkan transaksi beserta seluruh tiketnya. Tindakan ini tidak dapat dibatalkan dan bukan refund.
          </div>
          <label for="voidReason" class="form-label fw-semibold">Alasan Void <span class="text-danger">*</span></label>
          <textarea id="voidReason" name="void_reason" class="form-control" rows="3" maxlength="255" placeholder="Tuliskan alasan pembatalan..." required></textarea>
          <div class="invalid-feedback">Alasan void wajib diisi.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-sm">Void</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal ubah metode bayar: hanya field payment_method (cash ↔ debit) -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="editPaymentForm" method="POST" action="" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h6 class="modal-title">Ubah Metode Bayar</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="mb-2">
            Invoice: <strong id="editInvoiceCode">-</strong>
          </p>
          <div class="alert alert-warning py-2 mb-3">
            <i class="bi bi-exclamation-triangle"></i>
            Hanya metode bayar yang bisa diubah. Nominal dan status transaksi tidak berubah.
          </div>
          <label class="form-label fw-semibold">Metode Bayar <span class="text-danger">*</span></label>
          <div class="d-flex gap-4">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" id="payCash" value="cash" required>
              <label class="form-check-label" for="payCash">Tunai</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" id="payDebit" value="debit" required>
              <label class="form-check-label" for="payDebit">Debit</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning btn-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal cetak ulang: daftar tiket aktif & berlaku hari ini diambil via AJAX saat modal dibuka -->
<div
  class="modal fade"
  id="reprintTransactionModal"
  tabindex="-1"
  aria-hidden="true"
  data-csrf-name="<?= esc(csrf_token(), 'attr') ?>"
  data-csrf-hash="<?= esc(csrf_hash(), 'attr') ?>">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Cetak Ulang Tiket</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="reprintModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Flash session → toastr; dipanggil di sini karena toastr.min.js baru dimuat di layouts/js-query (setelah content)
    <?php if (session()->get('success')): ?>
      toastr.success(<?= json_encode(session()->get('success')) ?>, 'Berhasil', {
        timeOut: 2500,
        extendedTimeOut: 0
      });
    <?php endif; ?>
    <?php if (session()->get('error')): ?>
      toastr.error(<?= json_encode(session()->get('error')) ?>, 'Gagal', {
        timeOut: 3000,
        extendedTimeOut: 0
      });
    <?php endif; ?>

    const modal = document.getElementById('voidTransactionModal');
    const form = document.getElementById('voidTransactionForm');
    const reason = document.getElementById('voidReason');
    const invoice = document.getElementById('voidInvoiceCode');

    modal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      form.action = button.getAttribute('data-void-url');
      invoice.textContent = button.getAttribute('data-invoice');
      reason.value = '';
      reason.classList.remove('is-invalid');
    });

    form.addEventListener('submit', function(event) {
      event.preventDefault();

      if (reason.value.trim() === '') {
        reason.classList.add('is-invalid');
        return;
      }
      reason.classList.remove('is-invalid');

      Swal.fire({
        title: 'Void transaksi ini?',
        text: 'Transaksi ' + invoice.textContent + ' dan seluruh tiketnya akan dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Void',
        cancelButtonText: 'Batal',
      }).then(function(result) {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // --- Modal ubah metode bayar: isi form dari data-* button, konfirmasi SweetAlert sebelum POST ---
    const editPaymentModal = document.getElementById('editPaymentModal');
    const editPaymentForm = document.getElementById('editPaymentForm');
    const editInvoiceCode = document.getElementById('editInvoiceCode');

    editPaymentModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      editPaymentForm.action = button.getAttribute('data-payment-url');
      editInvoiceCode.textContent = button.getAttribute('data-invoice');
      // pre-select radio sesuai metode saat ini
      const current = button.getAttribute('data-payment-method');
      document.getElementById('payCash').checked = (current === 'cash');
      document.getElementById('payDebit').checked = (current !== 'cash');
    });

    editPaymentForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const selected = editPaymentForm.querySelector('input[name="payment_method"]:checked');
      if (!selected) {
        toastr.error('Pilih metode bayar terlebih dahulu.', 'Validasi Gagal', {
          timeOut: 2500,
          extendedTimeOut: 0
        });
        return;
      }
      const label = selected.value === 'cash' ? 'Tunai' : 'Debit';
      Swal.fire({
        title: 'Ubah metode bayar?',
        text: 'Invoice ' + editInvoiceCode.textContent + ' akan diubah menjadi ' + label + '.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d97706',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Batal',
      }).then(function(result) {
        if (result.isConfirmed) {
          editPaymentForm.submit();
        }
      });
    });

    // --- Modal cetak ulang: kartu tiket dibangun via DOM API (data dari JSON, hindari innerHTML mentah) ---
    const reprintModal = document.getElementById('reprintTransactionModal');
    const reprintBody = document.getElementById('reprintModalBody');
    const reprintCsrfName = reprintModal.getAttribute('data-csrf-name');
    const reprintCsrfHash = reprintModal.getAttribute('data-csrf-hash');

    reprintModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const dataUrl = button ? button.getAttribute('data-reprint-url') : '';
      // Simpan hanya prefix URL POST ".../reprint/" — id tiket ditambahkan per kartu.
      // Ganti /reprint-data/<id_transaksi> menjadi /reprint/; id transaksi TIDAK
      // boleh dipakai sebagai target POST (endpoint menunggu id tiket, bukan id transaksi).
      reprintModal.setAttribute('data-post-url', String(dataUrl).replace(/reprint-data\/\d+$/, 'reprint/'));
      reprintBody.innerHTML = '';
      reprintBody.appendChild(buildLoading());
      fetchReprintData(dataUrl);
    });

    function buildLoading() {
      const wrap = document.createElement('div');
      wrap.className = 'text-center py-4 text-gray';
      const spin = document.createElement('div');
      spin.className = 'spinner-border text-gray mb-2';
      spin.setAttribute('role', 'status');
      const p = document.createElement('p');
      p.className = 'mb-0 small';
      p.textContent = 'Memuat data tiket...';
      wrap.append(spin, p);
      return wrap;
    }

    function buildMessage(text, variant) {
      const div = document.createElement('div');
      div.className = 'alert alert-' + variant + ' py-2 mb-0';
      div.textContent = text;
      return div;
    }

    function fetchReprintData(url) {
      if (!url) {
        reprintBody.innerHTML = '';
        reprintBody.appendChild(buildMessage('Transaksi tidak ditemukan.', 'danger'));
        return;
      }
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(res) {
          if (!res.ok) throw new Error('HTTP ' + res.status);
          return res.json();
        })
        .then(function(data) { renderReprintTickets(data); })
        .catch(function() {
          reprintBody.innerHTML = '';
          reprintBody.appendChild(buildMessage('Gagal memuat data tiket. Silakan tutup dan coba lagi.', 'danger'));
        });
    }

    function renderReprintTickets(data) {
      reprintBody.innerHTML = '';
      const tickets = (data && Array.isArray(data.tickets)) ? data.tickets : [];

      const info = document.createElement('p');
      info.className = 'text-gray small mb-3';
      info.textContent = 'Invoice ' + ((data && data.invoice_code) || '-') + ' · ' + tickets.length + ' tiket tersedia untuk cetak ulang';
      reprintBody.appendChild(info);

      if (tickets.length === 0) {
        reprintBody.appendChild(buildMessage('Tidak ada tiket aktif yang berlaku hari ini untuk invoice ini.', 'warning'));
        return;
      }
      tickets.forEach(function(t) { reprintBody.appendChild(buildReprintCard(t)); });
    }

    function buildInfoBox(label, value) {
      const box = document.createElement('div');
      box.className = 'col-6';
      const inner = document.createElement('div');
      inner.className = 'rounded border p-2 bg-white';
      const l = document.createElement('p');
      l.className = 'mb-0 text-uppercase text-gray';
      l.style.cssText = 'font-size:10px;letter-spacing:.06em';
      l.textContent = label;
      const v = document.createElement('p');
      v.className = 'fw-bold mb-0';
      v.style.fontSize = '.9rem';
      v.textContent = value;
      inner.append(l, v);
      box.appendChild(inner);
      return box;
    }

    function formatTanggalID(s) {
      const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
      const p = String(s || '').split('-');
      if (p.length !== 3) return s || '-';
      const m = parseInt(p[1], 10);
      return p[2] + ' ' + (bulan[m - 1] || p[1]) + ' ' + p[0];
    }

    function buildReprintCard(t) {
      const isGroup = t.ticket_category === 'group';

      const card = document.createElement('div');
      card.className = 'border rounded overflow-hidden mb-3';

      // Header: kode tiket + badge kategori (tiruan kartu di halaman reprint lama)
      const head = document.createElement('div');
      head.className = 'd-flex align-items-center justify-content-between gap-2 px-3 py-2';
      head.style.background = '#1a1d20';
      head.style.color = '#D3D3D3 ';
      const headL = document.createElement('div');
      const cap = document.createElement('p');
      cap.className = 'mb-0 text-uppercase';
      cap.style.cssText = 'font-size:10px;letter-spacing:.08em;color:#94a3b8';
      cap.textContent = 'Kode Tiket';
      const code = document.createElement('p');
      code.className = 'mb-0 font-monospace fw-bold';
      code.style.fontSize = '.85rem';
      code.textContent = t.ticket_code;
      headL.append(cap, code);
      const badge = document.createElement('span');
      badge.className = 'badge ' + (isGroup ? 'bg-success' : 'bg-warning text-dark');
      badge.textContent = isGroup ? 'Rombongan' : 'Individual';
      head.append(headL, badge);

      const body = document.createElement('div');
      body.className = 'p-3';

      const grid = document.createElement('div');
      grid.className = 'row g-2 mb-3 text-gray';
      grid.append(
        buildInfoBox('Jumlah Tamu', t.total_visitors + ' orang'),
        buildInfoBox('Tanggal Berlaku', formatTanggalID(t.valid_date))
      );

      const status = document.createElement('div');
      status.className = 'alert alert-success py-2 mb-3 d-flex align-items-center gap-2';
      status.style.fontSize = '.8rem';
      const sIcon = document.createElement('i');
      sIcon.className = 'bi bi-check-circle-fill';
      const sText = document.createElement('span');
      sText.textContent = 'Tiket aktif · ' + (isGroup ? '1 QR untuk seluruh rombongan' : '1 QR untuk 1 orang');
      status.append(sIcon, sText);

      // Form POST per tiket: prefix reprint/ + id tiket dari JSON (bukan id transaksi)
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = (reprintModal.getAttribute('data-post-url') || '') + t.id;
      const csrf = document.createElement('input');
      csrf.type = 'hidden';
      csrf.name = reprintCsrfName;
      csrf.value = reprintCsrfHash;

      const label = document.createElement('label');
      label.className = 'form-label fw-semibold';
      label.style.cssText = 'font-size:.75rem;text-transform:uppercase;letter-spacing:.06em';
      const star = document.createElement('span');
      star.className = 'text-danger';
      star.textContent = '*';
      label.append('Alasan Cetak Ulang ', star);

      const ta = document.createElement('textarea');
      ta.className = 'form-control';
      ta.rows = 2;
      ta.maxLength = 255;
      ta.required = true;
      ta.name = 'reprint_reason';
      ta.placeholder = 'Contoh: tiket rusak atau hasil cetak tidak terbaca';

      const fb = document.createElement('div');
      fb.className = 'invalid-feedback';
      fb.textContent = 'Alasan cetak ulang wajib diisi.';

      const submit = document.createElement('button');
      submit.type = 'submit';
      submit.className = 'btn btn-danger w-100 fw-bold mt-3';
      const bIcon = document.createElement('i');
      bIcon.className = 'bi bi-printer me-1';
      submit.append(bIcon, ' CETAK ULANG TIKET');

      form.append(csrf, label, ta, fb, submit);

      form.addEventListener('submit', function(ev) {
        ev.preventDefault();
        if (ta.value.trim() === '') {
          ta.classList.add('is-invalid');
          return;
        }
        ta.classList.remove('is-invalid');
        Swal.fire({
          title: 'Cetak ulang tiket ini?',
          text: 'Tiket ' + t.ticket_code + ' akan dicetak ulang.',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Cetak Ulang',
          cancelButtonText: 'Batal',
        }).then(function(result) {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });

      body.append(grid, status, form);
      card.append(head, body);
      return card;
    }
  });
</script>
<?= $this->endSection() ?>