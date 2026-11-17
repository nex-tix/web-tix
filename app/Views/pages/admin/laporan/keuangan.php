<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Laporan Keuangan</h4>
        <p class="text-slate-500">Rekapitulasi arus kas penjualan tiket, rekonsiliasi penerimaan per bank (EDC), dan diskon.</p>
      </div>
    </div>

    <!-- Filter & Export Card -->
    <div class="filter-export card border-0 shadow-sm bg-white mb-3">
      <div class="card-body p-3">
        <form method="get" action="<?= base_url('admin/laporan/keuangan') ?>" id="form-filter">
          <div class="row g-2 align-items-end">
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Tanggal Awal</label>
              <input type="date" name="start" class="form-control form-control-sm" value="<?= esc($startDate) ?>">
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Tanggal Akhir</label>
              <input type="date" name="end" class="form-control form-control-sm" value="<?= esc($endDate) ?>">
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Metode Bayar</label>
              <select name="payment_method" class="form-select form-select-sm">
                <option value="">Semua Metode</option>
                <option value="cash" <?= ($filters['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai (Cash)</option>
                <option value="debit" <?= ($filters['payment_method'] ?? '') === 'debit' ? 'selected' : '' ?>>Debit / EDC</option>
              </select>
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Pilih Bank</label>
              <select name="bank_id" class="form-select form-select-sm">
                <option value="">Semua Bank</option>
                <?php foreach (($banksList ?? []) as $b): ?>
                  <option value="<?= esc($b['id']) ?>" <?= ((string)($filters['bank_id'] ?? '') === (string)$b['id']) ? 'selected' : '' ?>>
                    <?= esc($b['name']) ?> (<?= esc($b['code']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Status Transaksi</label>
              <select name="status" class="form-select form-select-sm">
                <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Sukses / Completed</option>
                <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Batal / Void</option>
                <option value="">Semua Status</option>
              </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-1">
              <button type="submit" class="btn btn-sm btn-dark flex-grow-1">
                <i class="bi bi-filter"></i> Filter
              </button>
              <a href="<?= base_url('admin/laporan/keuangan') ?>" class="btn btn-sm btn-outline-secondary" title="Reset filter">
                <i class="bi bi-arrow-counterclockwise"></i>
              </a>
              <?php
                $exportQuery = http_build_query(array_filter([
                  'start'          => date('d-m-Y', strtotime($startDate)),
                  'end'            => date('d-m-Y', strtotime($endDate)),
                  'payment_method' => $filters['payment_method'] ?? '',
                  'bank_id'        => $filters['bank_id'] ?? '',
                  'status'         => $filters['status'] ?? '',
                ]));
              ?>
              <a href="<?= base_url('admin/laporan/keuangan/export-pdf?' . $exportQuery) ?>" class="btn btn-sm btn-transaksi" title="Cetak PDF">
                <i class="bi bi-file-earmark-pdf"></i> PDF
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Stat Cards KPI -->
    <div class="row g-3 mb-3">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Pendapatan Kotor</h2>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['gross_sales'], 0, ',', '.') ?></h4>
          </div>
          <div class="stat-sub">
            <h4>Total nilai subtotal penjualan</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Total Diskon</h2>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['total_diskon'], 0, ',', '.') ?></h4>
          </div>
          <div class="stat-sub">
            <h4>Potongan harga transaksi</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Pendapatan Bersih (Net)</h2>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['net_revenue'], 0, ',', '.') ?></h4>
          </div>
          <div class="stat-sub green">
            <h4>Total penerimaan kasir &amp; bank</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Penerimaan Tunai vs Bank</h2>
          </div>
          <div class="stat-value">
            <h4 style="font-size: 1.15rem;">Tunai: Rp <?= number_format($kpi['total_cash'], 0, ',', '.') ?></h4>
          </div>
          <div class="stat-sub">
            <h4>Debit / EDC: Rp <?= number_format($kpi['total_debit'], 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Rekonsiliasi Settlement per Bank -->
    <div class="card border-0 shadow-sm bg-white mb-3">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-bank"></i> Rekonsiliasi Penerimaan per Bank (EDC)</h6>
        <span class="text-muted small">Periode: <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></span>
      </div>
      <div class="card-body p-3">
        <div class="row g-2">
          <!-- Card Tunai -->
          <div class="col-md-3 col-sm-6">
            <div class="p-2 border rounded-2 bg-white">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge bg-secondary">TUNAI / CASH</span>
                <span class="text-muted small"><?= count(array_filter($transactions, fn($t) => $t['payment_method'] === 'cash')) ?> tx</span>
              </div>
              <div class="fw-bold fs-6">Rp <?= number_format($kpi['total_cash'], 0, ',', '.') ?></div>
            </div>
          </div>
          <!-- Card per Bank -->
          <?php foreach (($bankBreakdown ?? []) as $bId => $bData): ?>
            <div class="col-md-3 col-sm-6">
              <div class="p-2 border rounded-2 bg-light d-flex flex-column justify-content-between" style="height: 100%;">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge bg-primary"><?= esc($bData['code']) ?></span>
                    <span class="text-muted small"><?= (int)$bData['count'] ?> tx</span>
                  </div>
                  <div class="small fw-semibold text-truncate"><?= esc($bData['name']) ?></div>
                  <div class="fw-bold fs-6 text-dark mt-1">Rp <?= number_format((float)$bData['total'], 0, ',', '.') ?></div>
                </div>
                <div class="mt-2 pt-1 border-top">
                  <a href="<?= base_url('admin/laporan/keuangan/export-pdf?start=' . date('d-m-Y', strtotime($startDate)) . '&end=' . date('d-m-Y', strtotime($endDate)) . '&bank_id=' . $bId) ?>" 
                     class="btn btn-outline-primary btn-sm py-0 px-2 w-100" style="font-size: 0.75rem;" title="Cetak Rekonsiliasi Bank Ini">
                    <i class="bi bi-printer"></i> Cetak PDF Bank
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Table Rincian Transaksi Keuangan -->
    <section id="table-keuangan" class="pt-2">
      <div class="container-fluid p-0">
        <div class="table-card">
          <div class="app-content-header">
            <div class="page-title">
              <h4 class="sub-text">Rincian Transaksi Keuangan</h4>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover datatable mb-0 align-middle">
              <thead>
                <tr>
                  <th style="width: 4%">No</th>
                  <th style="width: 12%">Waktu Transaksi</th>
                  <th style="width: 14%">No. Invoice</th>
                  <th style="width: 11%">Kasir</th>
                  <th style="width: 13%">Customer</th>
                  <th style="width: 9%">Metode</th>
                  <th style="width: 12%">Bank (EDC)</th>
                  <th style="width: 11%">No. Ref EDC</th>
                  <th style="width: 14%" class="text-end">Total Bayar</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($transactions)): ?>
                  <tr>
                    <td colspan="9" class="text-center text-muted py-4">Tidak ada data transaksi keuangan pada rentang filter ini.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($transactions as $idx => $tx): ?>
                    <tr>
                      <td><?= $idx + 1 ?></td>
                      <td>
                        <?= date('d/m/Y', strtotime($tx['created_at'])) ?><br>
                        <span class="text-muted small"><?= date('H:i', strtotime($tx['created_at'])) ?> WITA</span>
                      </td>
                      <td>
                        <span class="font-monospace fw-bold"><?= esc($tx['invoice_code']) ?></span>
                      </td>
                      <td><?= esc($tx['cashier_username'] ?? '-') ?></td>
                      <td><?= esc($tx['customer_name'] ?? 'Umum') ?></td>
                      <td>
                        <?php if ($tx['payment_method'] === 'cash'): ?>
                          <span class="badge bg-secondary">Tunai</span>
                        <?php elseif ($tx['payment_method'] === 'debit'): ?>
                          <span class="badge bg-primary">Debit</span>
                        <?php else: ?>
                          <span class="badge bg-dark"><?= esc($tx['payment_method']) ?></span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (!empty($tx['bank_name'])): ?>
                          <span class="badge bg-info text-dark"><?= esc($tx['bank_name']) ?></span>
                        <?php else: ?>
                          <span class="text-muted">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="font-monospace small"><?= esc($tx['payment_reference'] ?? '-') ?></span>
                      </td>
                      <td class="text-end fw-bold">
                        Rp <?= number_format((float)($tx['total_amount'] ?? 0), 0, ',', '.') ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>
<?= $this->endSection() ?>
