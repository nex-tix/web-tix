<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="app-content">
  <div class="container-fluid px-0">
    <?php if (session()->get('success')): ?>
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <?= session()->get('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->get('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <?= session()->get('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <div class="app-content-header">
      <div class="page-title">
        <h4 style="color:#2c3136">Riwayat Penjualan</h4>
        <p style="color:#2c3136">Daftar semua transaksi berdasarkan tanggal, metode bayar, dan kasir.</p>
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
                      <a href="<?= base_url('admin/transaction-history/detail/' . $row['id']) ?>" class="btn btn-sm btn-outline-dark" title="Detail">
                        <i class="bi bi-eye"></i>
                      </a>
                      <?php if (($row['status'] ?? '') === 'completed'): ?>
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-danger"
                          title="Void Transaksi"
                          data-bs-toggle="modal"
                          data-bs-target="#voidTransactionModal"
                          data-void-url="<?= esc(base_url('admin/transaction-history/void/' . $row['id']), 'attr') ?>"
                          data-invoice="<?= esc($row['invoice_code'] ?? '-', 'attr') ?>">
                          <i class="bi bi-x-circle"></i>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
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
  });
</script>
<?= $this->endSection() ?>