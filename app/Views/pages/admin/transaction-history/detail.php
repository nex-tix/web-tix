<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title">
        <h4 style="color:#2c3136">Detail Transaksi</h4>
        <p style="color:#2c3136">Invoice: <?= esc($transaction['invoice_code'] ?? '-') ?></p>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header bg-dark py-2">
            <h6 class="mb-0 ">Data Pembeli</h6>
          </div>
          <div class="card-body bg-light text-dark">
            <div class="mb-2"><strong>Nama:</strong> <?= esc($customer['name'] ?? '-') ?></div>
            <div class="mb-2"><strong>Asal:</strong> <?= esc($customer['origin'] ?? $customer['origin_type'] ?? '-') ?></div>
            <div class="mb-2"><strong>Telepon:</strong> <?= esc($customer['phone'] ?? '-') ?></div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header bg-dark py-2">
            <h6 class="mb-0">Ringkasan Transaksi</h6>
          </div>
          <div class="card-body bg-light text-dark">
            <div class="row">
              <div class="col-6">
                <small>Tanggal</small>
                <div><?= date('d M Y H:i', strtotime($transaction['created_at'])) ?> WITA</div>
              </div>
              <div class="col-6">
                <small>Kasir</small>
                <div><?= esc($adminUser['name'] ?? '-') ?></div>
              </div>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between">
              <span>Subtotal Tiket</span>
              <strong>Rp <?= number_format($transaction['subtotal_amount'] ?? $transaction['total_amount'] ?? 0, 0, ',', '.') ?></strong>
            </div>
            <?php if (!empty($transaction['discount_amount']) && $transaction['discount_amount'] > 0): ?>
              <div class="d-flex justify-content-between text-danger">
                <span>Diskon</span>
                <strong>- Rp <?= number_format($transaction['discount_amount'], 0, ',', '.') ?></strong>
              </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between">
              <span class="fw-semibold">Total</span>
              <strong class="fs-5">Rp <?= number_format($transaction['total_amount'] ?? $transaction['total'] ?? 0, 0, ',', '.') ?></strong>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-light py-2">
        <h6 class="mb-0 text-dark">Tiket</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr style="background: #f8f9fa;">
              <th style="width: 30%">Kode Tiket</th>
              <th style="width: 20%">Jenis</th>
              <th style="width: 25%">Jumlah</th>
              <th style="width: 25%">Subtotal</th>
            </tr>
          </thead>
          <tbody class="table-light">
            <?php if (empty($tickets)): ?>
              <tr>
                <td colspan="4" class="text-center text-muted py-3">Tidak ada tiket</td>
              </tr>
            <?php else: ?>
              <?php foreach ($tickets as $ticket): ?>
                <tr>
                  <td class="font-monospace"><?= esc($ticket['ticket_code']) ?></td>
                  <td><?= esc(ucfirst($ticket['ticket_category'] ?? '-')) ?></td>
                  <td><?= (int)($ticket['total_visitors'] ?? 0) ?> orang</td>
                  <td>Rp <?= number_format((float)($ticket['computed_subtotal'] ?? 0), 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="<?= base_url('admin/transaction-history') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

  </div>
</div>

<?= $this->endSection() ?>