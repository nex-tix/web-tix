<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title">
        <h4 style="color:#2c3136">Detail Tiket</h4>
        <p style="color:#2c3136">Kode: <?= esc($ticket['ticket_code'] ?? '-') ?></p>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header bg-dark py-2">
            <h6 class="mb-0 ">Data Tiket</h6>
          </div>
          <div class="card-body bg-light text-dark">
            <div class="mb-2"><strong>Kode Tiket:</strong> <span class="font-monospace"><?= esc($ticket['ticket_code']) ?></span></div>
            <div class="mb-2"><strong>Tipe:</strong> <?= esc(!empty($typeNames) ? implode(', ', $typeNames) : ucfirst($ticket['ticket_category'] ?? '-')) ?></div>
            <div class="mb-2"><strong>Kategori:</strong>
              <span class="badge bg-<?= ($ticket['ticket_category'] ?? '') === 'group' ? 'warning text-dark' : 'danger' ?>">
                <?= esc(ucfirst($ticket['ticket_category'] ?? '-')) ?>
              </span>
            </div>
            <div class="mb-2"><strong>Valid Date:</strong> <?= date('d M Y', strtotime($ticket['valid_date'])) ?></div>
            <div class="mb-2"><strong>Pengunjung:</strong> <?= (int)($ticket['total_visitors'] ?? 0) ?> orang</div>
            <div class="mb-2"><strong>Hadir:</strong>
              <?php if (isset($ticket['actual_visitors']) && $ticket['actual_visitors'] !== null && $ticket['actual_visitors'] !== '' && (int)$ticket['actual_visitors'] > 0): ?>
                <?= (int)$ticket['actual_visitors'] ?> orang
              <?php else: ?>
                <span class="fw-bold"> - </span>
              <?php endif; ?>
            </div>
            <div class="mb-0"><strong>Status:</strong>
              <?php if (($ticket['status'] ?? '') === 'active'): ?>
                <span class="badge bg-success">Aktif</span>
              <?php elseif (($ticket['status'] ?? '') === 'used'): ?>
                <span class="badge bg-primary">Terpakai</span>
              <?php else: ?>
                <span class="badge bg-danger">Dibatalkan</span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header bg-dark py-2">
            <h6 class="mb-0">Data Transaksi</h6>
          </div>
          <div class="card-body bg-light text-dark">
            <div class="row">
              <div class="col-6">
                <small>Invoice</small>
                <div class="font-monospace fw-bold"><?= esc($ticket['invoice_code'] ?? '-') ?></div>
              </div>
              <div class="col-6">
                <small>Tanggal Transaksi</small>
                <div><?= !empty($ticket['transaction_created_at']) ? date('d M Y H:i', strtotime($ticket['transaction_created_at'])) . ' WITA' : '-' ?></div>
              </div>
            </div>
            <hr class="my-2">
            <div class="row">
              <div class="col-6">
                <small>Kasir</small>
                <div><?= esc($ticket['admin_name'] ?? '-') ?></div>
              </div>
              <div class="col-6">
                <small>Metode Bayar</small>
                <div>
                  <?php if (($ticket['payment_method'] ?? '') === 'cash'): ?>
                    <span class="badge bg-secondary">Tunai</span>
                  <?php elseif (($ticket['payment_method'] ?? '') === 'debit'): ?>
                    <span class="badge bg-primary">Debit</span>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <hr class="my-2">
            <div class="row mb-2">
              <div class="col-6">
                <small>Pembeli</small>
                <div><?= esc($ticket['customer_name'] ?? '-') ?></div>
              </div>
              <div class="col-6">
                <small>Asal</small>
                <div><?= esc($ticket['origin'] ?? $ticket['origin_type'] ?? '-') ?></div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <span>Subtotal</span>
              <strong>Rp <?= number_format((float)($ticket['tx_subtotal'] ?? 0), 0, ',', '.') ?></strong>
            </div>
            <?php if ((float)($ticket['tx_discount_amount'] ?? 0) > 0): ?>
              <div class="d-flex justify-content-between text-danger">
                <span>Diskon</span>
                <strong>- Rp <?= number_format((float)$ticket['tx_discount_amount'], 0, ',', '.') ?></strong>
              </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between">
              <span class="fw-semibold">Total Transaksi</span>
              <strong class="fs-5">Rp <?= number_format((float)($ticket['tx_total_amount'] ?? 0), 0, ',', '.') ?></strong>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-light py-2">
        <h6 class="mb-0 text-dark">Rincian Tipe Tiket</h6>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover mb-0">
          <thead>
            <tr style="background: #f8f9fa;">
              <th style="width: 30%">Tipe</th>
              <th style="width: 20%">Kategori</th>
              <th style="width: 15%">Qty</th>
              <th style="width: 20%">Harga Satuan</th>
              <th style="width: 15%">Subtotal</th>
            </tr>
          </thead>
          <tbody class="table-light">
            <?php if (empty($details)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-3">Tidak ada rincian tiket</td>
              </tr>
            <?php else: ?>
              <?php foreach ($details as $det): ?>
                <tr>
                  <td class="fw-semibold"><?= esc($det['type_name'] ?? '-') ?></td>
                  <td>
                    <span class="badge bg-<?= ($det['ticket_category'] ?? '') === 'group' ? 'warning text-dark' : 'danger' ?>">
                      <?= esc(ucfirst($det['ticket_category'] ?? '-')) ?>
                    </span>
                  </td>
                  <td><?= (int)($det['qty'] ?? 0) ?> orang</td>
                  <td>Rp <?= number_format((float)($det['unit_price'] ?? 0), 0, ',', '.') ?></td>
                  <td>Rp <?= number_format((float)($det['subtotal'] ?? 0), 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="<?= base_url('admin/data-tiket') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
