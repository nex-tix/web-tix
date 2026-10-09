<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="app-content">
  <div class="container-fluid px-0">
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

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="">
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
              <?php if (empty($transactions)): ?>
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    <i class="bi bi-inbox" style="font-size:2rem;opacity:.3"></i>
                    <div class="mt-2">Tidak ada transaksi ditemukan.</div>
                  </td>
                </tr>
              <?php else: ?>
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
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>