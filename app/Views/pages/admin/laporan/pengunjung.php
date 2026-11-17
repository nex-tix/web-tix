<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Laporan Data Pengunjung</h4>
        <p class="text-slate-500">Rekapitulasi manifest kunjungan wisatawan, demografi asal, dan status kehadiran di gate.</p>
      </div>
    </div>

    <!-- Filter & Export Card -->
    <div class="filter-export card border-0 shadow-sm bg-white mb-3">
      <div class="card-body p-3">
        <form method="get" action="<?= base_url('admin/laporan/pengunjung') ?>" id="form-filter">
          <div class="row g-2 align-items-end">
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Tanggal Awal</label>
              <input type="date" name="start" id="filter-start" class="form-control form-control-sm" value="<?= esc($startDate) ?>">
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Tanggal Akhir</label>
              <input type="date" name="end" id="filter-end" class="form-control form-control-sm" value="<?= esc($endDate) ?>">
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Kategori Asal</label>
              <select name="origin_type" class="form-select form-select-sm">
                <option value="">Semua Asal</option>
                <option value="domestic" <?= ($filters['origin_type'] ?? '') === 'domestic' ? 'selected' : '' ?>>Domestik</option>
                <option value="international" <?= ($filters['origin_type'] ?? '') === 'international' ? 'selected' : '' ?>>Mancanegara</option>
              </select>
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Tipe Tiket</label>
              <select name="category" class="form-select form-select-sm">
                <option value="">Semua Tipe</option>
                <option value="individual" <?= ($filters['category'] ?? '') === 'individual' ? 'selected' : '' ?>>Individual</option>
                <option value="group" <?= ($filters['category'] ?? '') === 'group' ? 'selected' : '' ?>>Group / Rombongan</option>
              </select>
            </div>
            <div class="col-md-2 col-sm-6">
              <label class="form-label filter-label">Status Tiket</label>
              <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif (Belum Hadir)</option>
                <option value="used" <?= ($filters['status'] ?? '') === 'used' ? 'selected' : '' ?>>Terpakai (Hadir)</option>
                <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
              </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-1">
              <button type="submit" class="btn btn-sm btn-dark flex-grow-1">
                <i class="bi bi-filter"></i> Filter
              </button>
              <a href="<?= base_url('admin/laporan/pengunjung') ?>" class="btn btn-sm btn-outline-secondary" title="Reset filter">
                <i class="bi bi-arrow-counterclockwise"></i>
              </a>
              <?php
                $exportQuery = http_build_query(array_filter([
                  'start'       => date('d-m-Y', strtotime($startDate)),
                  'end'         => date('d-m-Y', strtotime($endDate)),
                  'origin_type' => $filters['origin_type'] ?? '',
                  'category'    => $filters['category'] ?? '',
                  'status'      => $filters['status'] ?? '',
                ]));
              ?>
              <a href="<?= base_url('admin/laporan/pengunjung/export-pdf?' . $exportQuery) ?>" class="btn btn-sm btn-transaksi" title="Cetak PDF">
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
            <h2>Total Wisatawan</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_pengunjung'], 0, ',', '.') ?> <small>orang</small></h4>
          </div>
          <div class="stat-sub">
            <h4>Periode <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Wisatawan Domestik</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_domestik'], 0, ',', '.') ?> <small>orang</small></h4>
          </div>
          <div class="stat-sub green">
            <h4><?= $kpi['domestik_pct'] ?>% dari total pengunjung</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Wisatawan Mancanegara</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_mancanegara'], 0, ',', '.') ?> <small>orang</small></h4>
          </div>
          <div class="stat-sub">
            <h4><?= $kpi['mancanegara_pct'] ?>% dari total pengunjung</h4>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Hadir di Gate</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_hadir'], 0, ',', '.') ?> <small>orang</small></h4>
          </div>
          <div class="stat-sub">
            <h4><?= $kpi['hadir_pct'] ?>% kehadiran aktual</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Manifest Pengunjung -->
    <section id="table-pengunjung" class="pt-2">
      <div class="container-fluid p-0">
        <div class="table-card">
          <div class="app-content-header">
            <div class="page-title">
              <h4 class="sub-text">Manifest Kunjungan Wisatawan</h4>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover datatable mb-0 align-middle">
              <thead>
                <tr>
                  <th style="width: 4%">No</th>
                  <th style="width: 11%">Tanggal Valid</th>
                  <th style="width: 14%">No. Tiket</th>
                  <th style="width: 15%">Nama Pengunjung</th>
                  <th style="width: 14%">Kota / Negara</th>
                  <th style="width: 10%">Kategori Asal</th>
                  <th style="width: 10%">Tipe Tiket</th>
                  <th style="width: 8%">Jumlah</th>
                  <th style="width: 8%">Status</th>
                  <th style="width: 6%">Gate</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($tickets)): ?>
                  <tr>
                    <td colspan="10" class="text-center text-muted py-4">Tidak ada data pengunjung pada rentang filter ini.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($tickets as $idx => $t): ?>
                    <tr>
                      <td><?= $idx + 1 ?></td>
                      <td><?= date('d/m/Y', strtotime($t['valid_date'])) ?></td>
                      <td>
                        <span class="font-monospace fw-bold"><?= esc($t['ticket_code']) ?></span>
                        <?php if (!empty($t['invoice_code'])): ?>
                          <div class="text-muted small"><?= esc($t['invoice_code']) ?></div>
                        <?php endif; ?>
                      </td>
                      <td class="fw-semibold">
                        <?= esc($t['customer_name'] ?? 'Umum') ?>
                        <?php if (!empty($t['customer_phone'])): ?>
                          <div class="text-muted small"><i class="bi bi-telephone"></i> <?= esc($t['customer_phone']) ?></div>
                        <?php endif; ?>
                      </td>
                      <td><?= esc($t['origin'] ?? '-') ?></td>
                      <td>
                        <?php if (($t['origin_type'] ?? '') === 'domestic'): ?>
                          <span class="badge bg-primary">Domestik</span>
                        <?php elseif (($t['origin_type'] ?? '') === 'international'): ?>
                          <span class="badge bg-danger">Mancanegara</span>
                        <?php else: ?>
                          <span class="badge bg-secondary">-</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge bg-<?= ($t['ticket_category'] ?? '') === 'group' ? 'warning text-dark' : 'info text-dark' ?>">
                          <?= esc(ucfirst($t['ticket_category'] ?? 'Individual')) ?>
                        </span>
                      </td>
                      <td class="fw-semibold"><?= (int)($t['total_visitors'] ?? 0) ?> org</td>
                      <td>
                        <?php if (($t['status'] ?? '') === 'used'): ?>
                          <span class="badge bg-success">Hadir</span>
                        <?php elseif (($t['status'] ?? '') === 'active'): ?>
                          <span class="badge bg-primary">Aktif</span>
                        <?php else: ?>
                          <span class="badge bg-secondary">Batal</span>
                        <?php endif; ?>
                      </td>
                      <td><?= esc($t['checkpoint_name'] ?? '-') ?></td>
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
