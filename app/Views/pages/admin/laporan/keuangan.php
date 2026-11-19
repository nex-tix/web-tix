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
            <select name="status" class="form-select form-select-sm bg-white">
              <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Sukses / Completed</option>
              <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Batal / Void</option>
              <option value="">Semua Status</option>
            </select>
          </div>
          <div class="col-12 col-md-4 col-lg-2 d-flex gap-1">
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

    <!-- Stat Cards KPI -->
    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-6 col-lg-3">
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
      <div class="col-12 col-sm-6 col-lg-3">
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
      <div class="col-12 col-sm-6 col-lg-3">
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
      <div class="col-12 col-sm-6 col-lg-3">
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

    <!-- Rekonsiliasi Settlement per Bank (EDC) -->
    <div class="table-card mb-3">
      <div class="card-header-custom d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
          <h6 class="mb-1"><i class="bi bi-bank"></i> Rekonsiliasi Penerimaan per Bank (EDC)</h6>
          <p class="mb-0">Menampilkan Kas Tunai serta Bank. Gunakan pencarian untuk bank lainnya.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
          <!-- Live Search Input -->
          <div class="input-group input-group-sm flex-grow-1 flex-md-grow-0" style="min-width: 180px; max-width: 100%;">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="bank-search-input" class="form-control" placeholder="Cari nama / kode bank...">
          </div>
          <!-- Quick Filter Pills -->
          <div class="btn-group btn-group-sm flex-wrap flex-sm-nowrap w-100 w-sm-auto" role="group">
            <button type="button" class="btn btn-dark active" id="btn-filter-top3">Top 3 Bank</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-filter-all">Semua Bank</button>
            <button type="button" class="btn btn-outline-secondary" id="btn-filter-active">Ada Transaksi</button>
          </div>
        </div>
      </div>

      <div class="row g-2" id="bank-cards-grid">
        <!-- Card Tunai / Cash (Selalu tampil di awal) -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 bank-item" data-name="tunai cash" data-code="cash" data-top3="1" data-has-tx="1">
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

        <!-- Loop Cards per Bank -->
        <?php foreach (($bankBreakdown ?? []) as $bId => $bData): 
          $isTop3 = !empty($bData['is_top3']);
          $hasTx  = (float)($bData['total'] ?? 0) > 0;
        ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3 bank-item <?= $isTop3 ? '' : 'd-none' ?>" 
               data-name="<?= esc(strtolower($bData['name'])) ?>" 
               data-code="<?= esc(strtolower($bData['code'])) ?>" 
               data-top3="<?= $isTop3 ? '1' : '0' ?>" 
               data-has-tx="<?= $hasTx ? '1' : '0' ?>">
            <div class="bank-card-item d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="badge bg-primary"><?= esc($bData['code']) ?></span>
                  <?php if ($isTop3): ?>
                    <span class="badge bg-warning text-dark py-0" style="font-size: 0.65rem;">Top <?= $bData['rank'] ?></span>
                  <?php endif; ?>
                  <span class="bank-meta"><?= (int)$bData['count'] ?> tx</span>
                </div>
                <div class="bank-name text-truncate" title="<?= esc($bData['name']) ?>"><?= esc($bData['name']) ?></div>
                <div class="bank-total mt-1">Rp <?= number_format((float)$bData['total'], 0, ',', '.') ?></div>
              </div>
              <div class="mt-2 pt-2 border-top border-secondary-subtle">
                <a href="<?= base_url('admin/laporan/keuangan/export-pdf?start=' . date('d-m-Y', strtotime($startDate)) . '&end=' . date('d-m-Y', strtotime($endDate)) . '&bank_id=' . $bId) ?>" 
                   class="btn btn-outline-secondary btn-sm py-0 px-2 w-100" style="font-size: 0.75rem;" title="Cetak Rekonsiliasi Bank Ini">
                  <i class="bi bi-printer"></i> Cetak PDF Bank
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

        <!-- Message if search yields no results -->
        <div class="col-12 d-none text-center py-3 text-muted" id="bank-no-results">
          <i class="bi bi-search fs-4 d-block mb-1 opacity-50"></i>
          Tidak ada bank yang sesuai dengan pencarian.
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
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= esc($tx['bank_name']) ?></span>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('bank-search-input');
    const btnTop3     = document.getElementById('btn-filter-top3');
    const btnAll      = document.getElementById('btn-filter-all');
    const btnActive   = document.getElementById('btn-filter-active');
    const bankItems   = document.querySelectorAll('.bank-item');
    const noResults   = document.getElementById('bank-no-results');

    let currentFilter = 'top3'; // 'top3' | 'all' | 'active'

    function applyBankFilter() {
      const q = searchInput.value.trim().toLowerCase();
      let visibleCount = 0;

      bankItems.forEach(item => {
        const name  = item.getAttribute('data-name') || '';
        const code  = item.getAttribute('data-code') || '';
        const top3  = item.getAttribute('data-top3') === '1';
        const hasTx = item.getAttribute('data-has-tx') === '1';

        const matchSearch = (q === '') || name.includes(q) || code.includes(q);

        let matchPill = true;
        if (q === '') {
          if (currentFilter === 'top3') {
            matchPill = top3;
          } else if (currentFilter === 'active') {
            matchPill = hasTx;
          } else if (currentFilter === 'all') {
            matchPill = true;
          }
        }

        if (matchSearch && matchPill) {
          item.classList.remove('d-none');
          visibleCount++;
        } else {
          item.classList.add('d-none');
        }
      });

      if (noResults) {
        if (visibleCount === 0) {
          noResults.classList.remove('d-none');
        } else {
          noResults.classList.add('d-none');
        }
      }
    }

    if (searchInput) {
      searchInput.addEventListener('input', applyBankFilter);
    }

    function setActiveBtn(activeBtn) {
      [btnTop3, btnAll, btnActive].forEach(btn => {
        if (!btn) return;
        if (btn === activeBtn) {
          btn.classList.add('btn-dark', 'active');
          btn.classList.remove('btn-outline-secondary');
        } else {
          btn.classList.remove('btn-dark', 'active');
          btn.classList.add('btn-outline-secondary');
        }
      });
    }

    if (btnTop3) {
      btnTop3.addEventListener('click', function() {
        currentFilter = 'top3';
        setActiveBtn(btnTop3);
        searchInput.value = '';
        applyBankFilter();
      });
    }

    if (btnAll) {
      btnAll.addEventListener('click', function() {
        currentFilter = 'all';
        setActiveBtn(btnAll);
        searchInput.value = '';
        applyBankFilter();
      });
    }

    if (btnActive) {
      btnActive.addEventListener('click', function() {
        currentFilter = 'active';
        setActiveBtn(btnActive);
        searchInput.value = '';
        applyBankFilter();
      });
    }
  });
</script>
<?= $this->endSection() ?>
