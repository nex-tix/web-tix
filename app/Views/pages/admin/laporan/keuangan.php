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
    <div class="filter-export table-card mb-3 p-3">
      <form method="get" action="<?= base_url('admin/laporan/keuangan') ?>" id="form-filter">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-md-4 col-lg-2">
            <label class="form-label filter-label">Tanggal Awal</label>
            <input type="date" name="start" class="form-control form-control-sm" value="<?= esc($startDate) ?>">
          </div>
          <div class="col-6 col-md-4 col-lg-2">
            <label class="form-label filter-label">Tanggal Akhir</label>
            <input type="date" name="end" class="form-control form-control-sm" value="<?= esc($endDate) ?>">
          </div>
          <div class="col-6 col-md-4 col-lg-2">
            <label class="form-label filter-label">Metode Bayar</label>
            <select name="payment_method" class="form-select form-select-sm bg-white">
              <option value="">Semua Metode</option>
              <option value="cash" <?= ($filters['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai (Cash)</option>
              <option value="debit" <?= ($filters['payment_method'] ?? '') === 'debit' ? 'selected' : '' ?>>Debit / EDC</option>
            </select>
          </div>
          <div class="col-6 col-md-4 col-lg-2">
            <label class="form-label filter-label">Pilih Bank</label>
            <select name="bank_id" class="form-select form-select-sm bg-white">
              <option value="">Semua Bank</option>
              <?php foreach (($banksList ?? []) as $b): ?>
                <option value="<?= esc($b['id']) ?>" <?= ((string)($filters['bank_id'] ?? '') === (string)$b['id']) ? 'selected' : '' ?>>
                  <?= esc($b['name']) ?> (<?= esc($b['code']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-md-4 col-lg-2">
            <label class="form-label filter-label">Status Transaksi</label>
            <!-- '' (Semua Status) kini aktif: controller hanya default 'completed' saat param absen -->
            <select name="status" class="form-select form-select-sm bg-white">
              <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Sukses / Completed</option>
              <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Batal / Void</option>
              <option value="" <?= ($filters['status'] ?? '') === '' ? 'selected' : '' ?>>Semua Status</option>
            </select>
          </div>
          <div class="col-12 col-md-4 col-lg-2 d-flex flex-wrap gap-1 filter-export-actions">
            <button type="submit" class="btn btn-sm btn-dark flex-grow-1">
              <i class="bi bi-filter"></i> Filter
            </button>
            <a href="<?= base_url('admin/laporan/keuangan') ?>" class="btn btn-sm btn-outline-secondary" title="Reset filter">
              <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            <?php
              // Tanpa array_filter: status '' (Semua Status) harus ikut terkirim ke export
              $exportQuery = http_build_query([
                'start'          => date('d-m-Y', strtotime($startDate)),
                'end'            => date('d-m-Y', strtotime($endDate)),
                'payment_method' => $filters['payment_method'] ?? '',
                'bank_id'        => $filters['bank_id'] ?? '',
                'status'         => $filters['status'] ?? '',
              ]);
            ?>
            <!-- Tombol Export: dropdown hover — child "PDF" & "Excel" (keduanya aktif) -->
            <div class="dropdown hover-dropdown">
              <button type="button" class="btn btn-sm btn-transaksi dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Ekspor laporan">
                Export
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="<?= base_url('admin/laporan/keuangan/export-pdf?' . $exportQuery) ?>">
                    <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>PDF
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="<?= base_url('admin/laporan/keuangan/export-excel?' . $exportQuery) ?>">
                    <i class="bi bi-file-earmark-excel me-2 text-success"></i>Excel
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Stat Cards KPI — Pendapatan Bersih jadi hero, tiga lainnya pendamping -->
    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card stat-muted">
          <div class="stat-label stat-head">
            <h2>Pendapatan Kotor</h2>
            <p class="stat-desc">Total nilai subtotal penjualan</p>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['gross_sales'], 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card stat-muted">
          <div class="stat-label stat-head">
            <h2>Total Diskon</h2>
            <p class="stat-desc">Potongan harga transaksi</p>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['total_diskon'], 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card stat-hero">
          <div class="stat-label stat-head">
            <h2>Pendapatan Bersih (Net)</h2>
            <p class="stat-desc">Total penerimaan kasir &amp; bank</p>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['net_revenue'], 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card stat-muted">
          <div class="stat-label stat-head">
            <h2>Penerimaan Tunai vs Bank</h2>
            <p class="stat-desc">Per metode bayar</p>
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

    <!-- Rekonsiliasi Settlement per Bank (EDC) -->
    <div class="table-card mb-3">
      <div class="card-header-custom mb-3">
        <div>
          <h6 class="mb-1"><i class="bi bi-bank"></i> Rekonsiliasi Penerimaan per Bank (EDC)</h6>
          <p class="mb-0">Kas Tunai serta 3 bank dengan penerimaan tertinggi ditampilkan sebagai kartu. Rincian semua bank ada di tabel di bawahnya.</p>
        </div>
      </div>

      <div class="row g-2 mb-3" id="bank-cards-grid">
        <!-- Card Tunai / Cash (selalu tampil di awal) -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="bank-card-item d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="badge bg-secondary">TUNAI / CASH</span>
                <span class="bank-meta"><?= count(array_filter($transactions, fn($t) => $t['payment_method'] === 'cash')) ?> tx</span>
              </div>
              <div class="bank-name">Penerimaan Kasir</div>
              <div class="bank-total mt-1">Rp <?= number_format($kpi['total_cash'], 0, ',', '.') ?></div>
            </div>
            <div class="mt-2 pt-2 border-top border-secondary-subtle">
              <span class="bank-meta"><i class="bi bi-cash-stack"></i> Saldo Kasir Fisik</span>
            </div>
          </div>
        </div>

        <!-- Card hanya Top 3 bank (rank + is_top3 sudah dihitung controller) -->
        <?php foreach (($bankBreakdown ?? []) as $bId => $bData):
          if (empty($bData['is_top3'])) continue;
        ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="bank-card-item d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="badge badge-bank-code"><?= esc($bData['code']) ?></span>
                  <span class="tx-sub fw-semibold">Top <?= (int)$bData['rank'] ?></span>
                  <span class="bank-meta"><?= (int)$bData['count'] ?> tx</span>
                </div>
                <div class="bank-name text-truncate" title="<?= esc($bData['name']) ?>"><?= esc($bData['name']) ?></div>
                <div class="bank-total mt-1">Rp <?= number_format((float)$bData['total'], 0, ',', '.') ?></div>
              </div>
              <div class="mt-2 pt-2 border-top border-secondary-subtle">
                <?php
                  // PDF per-bank ikut bawa filter status + payment_method (sinkron dgn tombol PDF utama)
                  $bankPdfQuery = http_build_query([
                    'start'          => date('d-m-Y', strtotime($startDate)),
                    'end'            => date('d-m-Y', strtotime($endDate)),
                    'payment_method' => $filters['payment_method'] ?? '',
                    'bank_id'        => $bId,
                    'status'         => $filters['status'] ?? '',
                  ]);
                ?>
                <a href="<?= base_url('admin/laporan/keuangan/export-pdf?' . $bankPdfQuery) ?>"
                   class="btn btn-outline-secondary btn-sm py-0 px-2 w-100" style="font-size: 0.75rem;" title="Cetak Rekonsiliasi Bank Ini">
                  <i class="bi bi-printer"></i> Cetak PDF Bank
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Tabel rincian SEMUA bank (termasuk Top 3) — class 'datatable' dipakai init DataTables di main.js -->
      <div class="table-responsive">
        <table class="table table-hover datatable bank-table mb-0 align-middle">
          <thead class="bg-transparant">
            <tr>
              <th style="width: 8%">Rank</th>
              <th>Bank</th>
              <th class="num" style="width: 14%">Jumlah Tx</th>
              <th class="num" style="width: 22%">Total Penerimaan</th>
              <th style="width: 18%">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($bankBreakdown)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">Belum ada data bank.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($bankBreakdown as $bId => $bData):
                $isTop3 = !empty($bData['is_top3']);
                $hasTx  = (float)($bData['total'] ?? 0) > 0;
              ?>
                <tr class="<?= $hasTx ? '' : 'is-zero' ?>">
                  <td class="bank-rank <?= $isTop3 ? 'rank-top' : '' ?>"><?= (int)$bData['rank'] ?></td>
                  <td class="bank-name-cell">
                    <?= esc($bData['name']) ?>
                    <span class="tx-sub font-monospace">(<?= esc($bData['code']) ?>)</span>
                  </td>
                  <td class="num bank-tx-cell"><?= (int)$bData['count'] ?> tx</td>
                  <td class="num bank-total-cell <?= $hasTx ? 'fw-semibold' : '' ?>">
                    Rp <?= number_format((float)$bData['total'], 0, ',', '.') ?>
                  </td>
                  <td>
                    <?php
                      // Query per baris: bank_id diganti per baris, filter lain ikut
                      $rowPdfQuery = http_build_query([
                        'start'          => date('d-m-Y', strtotime($startDate)),
                        'end'            => date('d-m-Y', strtotime($endDate)),
                        'payment_method' => $filters['payment_method'] ?? '',
                        'bank_id'        => $bId,
                        'status'         => $filters['status'] ?? '',
                      ]);
                    ?>
                    <!-- Dua tombol aksi per baris: Cetak (PDF) & Excel — query sama, tinggal beda endpoint -->
                    <div class="d-flex gap-1">
                    <a href="<?= base_url('admin/laporan/keuangan/export-pdf?' . $rowPdfQuery) ?>"
                       class="btn btn-sm btn-pdf-outline py-0 px-2" style="font-size: 0.75rem;" title="Cetak Rekonsiliasi Bank Ini">
                      <i class="bi bi-printer"></i> Cetak
                    </a>
                    <a href="<?= base_url('admin/laporan/keuangan/export-excel?' . $rowPdfQuery) ?>"
                       class="btn btn-sm btn-excel-outline py-0 px-2" style="font-size: 0.75rem;" title="Ekspor Excel Rekonsiliasi Bank Ini">
                      <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
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
            <table class="table table-hover datatable mb-0 align-middle" style="min-width: 950px;">
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
                        <span class="tx-sub"><?= date('H:i', strtotime($tx['created_at'])) ?> WITA</span>
                      </td>
                      <td>
                        <span class="font-monospace fw-bold"><?= esc($tx['invoice_code']) ?></span>
                      </td>
                      <td><?= esc($tx['cashier_username'] ?? '-') ?></td>
                      <td><?= esc($tx['customer_name'] ?? 'Umum') ?></td>
                      <td>
                        <?php if ($tx['payment_method'] === 'cash'): ?>
                          <span class="badge badge-method-cash">Tunai</span>
                        <?php elseif ($tx['payment_method'] === 'debit'): ?>
                          <span class="badge badge-method-debit">Debit</span>
                        <?php else: ?>
                          <span class="badge bg-dark"><?= esc($tx['payment_method']) ?></span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (!empty($tx['bank_name'])): ?>
                          <span class="tx-bank-name"><?= esc($tx['bank_name']) ?></span>
                        <?php else: ?>
                          <span class="tx-ref-empty">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (!empty($tx['payment_reference'])): ?>
                          <span class="font-monospace small"><?= esc($tx['payment_reference']) ?></span>
                        <?php else: ?>
                          <span class="tx-ref-empty">N/A</span>
                        <?php endif; ?>
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
