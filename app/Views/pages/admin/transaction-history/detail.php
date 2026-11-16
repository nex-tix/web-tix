<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Detail Transaksi</h4>
        <p class="text-slate-500">Invoice: <?= esc($transaction['invoice_code'] ?? '-') ?></p>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header py-2">
            <h6 class="mb-0 ">Data Pembeli</h6>
          </div>
          <div class="card-body card-history">
            <div class="mb-2"><strong>Nama:</strong> <?= esc($customer['name'] ?? '-') ?></div>
            <div class="mb-2"><strong>Asal:</strong> <?= esc($customer['origin'] ?? $customer['origin_type'] ?? '-') ?></div>
            <div class="mb-2"><strong>Telepon:</strong> <?= esc($customer['phone'] ?? '-') ?></div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-header py-2">
            <h6 class="mb-0">Ringkasan Transaksi</h6>
          </div>
          <div class="card-body card-history">
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
      <div class="card-header py-2 d-flex justify-content-start align-items-center gap-3">
        <h6 class="mb-0">Rincian Tiket</h6>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover table-detail mb-0 align-middle">
            <thead>
              <tr>
                <th style="width: 25%">Kode Tiket</th>
                <th style="width: 20%">Tipe Pengunjung</th>
                <th style="width: 15%">Kategori</th>
                <th style="width: 12%">Jumlah</th>
                <th style="width: 14%">Harga Satuan</th>
                <th style="width: 14%" class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($tickets)): ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-3">Tidak ada tiket</td>
                </tr>
              <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                  <?php if (!empty($ticket['details'])): ?>
                    <?php foreach ($ticket['details'] as $det): 
                      $typeName = $ticketTypesById[$det['ticket_type_id']]['name'] ?? 'Tiket';
                    ?>
                      <tr>
                        <td>
                          <span class="font-monospace fw-bold"><?= esc($ticket['ticket_code']) ?></span>
                          <?php if (!empty($ticket['guide_name_snapshot'])): ?>
                            <div class="guide-name"><i class="bi bi-person-badge"></i> Guide: <?= esc($ticket['guide_name_snapshot']) ?></div>
                          <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= esc($typeName) ?></td>
                        <td>
                          <span class="badge bg-<?= ($ticket['ticket_category'] ?? '') === 'group' ? 'warning text-dark' : 'primary' ?>">
                            <?= esc(ucfirst($ticket['ticket_category'] ?? '-')) ?>
                          </span>
                        </td>
                        <td><?= (int)($det['qty'] ?? 0) ?> orang</td>
                        <td>Rp <?= number_format((float)($det['unit_price'] ?? 0), 0, ',', '.') ?></td>
                        <td class="text-end fw-semibold">Rp <?= number_format((float)($det['subtotal'] ?? 0), 0, ',', '.') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td class="font-monospace fw-bold"><?= esc($ticket['ticket_code']) ?></td>
                      <td>-</td>
                      <td>
                        <span class="badge bg-<?= ($ticket['ticket_category'] ?? '') === 'group' ? 'warning text-dark' : 'primary' ?>">
                          <?= esc(ucfirst($ticket['ticket_category'] ?? '-')) ?>
                        </span>
                      </td>
                      <td><?= (int)($ticket['total_visitors'] ?? 0) ?> orang</td>
                      <td>-</td>
                      <td class="text-end fw-semibold">Rp <?= number_format((float)($ticket['computed_subtotal'] ?? 0), 0, ',', '.') ?></td>
                    </tr>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
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