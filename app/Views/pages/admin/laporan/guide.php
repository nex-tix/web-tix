<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Laporan Pembayaran Guide</h4>
        <p class="text-slate-500">Rekapitulasi komisi dan fee pemandu wisata (guide) atas pendampingan wisatawan.</p>
      </div>
    </div>

    <!-- Filter & Export Card -->
    <div class="filter-export table-card mb-3 p-3">
      <form method="get" action="<?= base_url('admin/laporan/guide') ?>" id="form-filter">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-sm-6 col-lg-3">
            <label class="form-label filter-label">Tanggal Awal</label>
            <input type="date" name="start" class="form-control form-control-sm" value="<?= esc($startDate) ?>">
          </div>
          <div class="col-6 col-sm-6 col-lg-3">
            <label class="form-label filter-label">Tanggal Akhir</label>
            <input type="date" name="end" class="form-control form-control-sm" value="<?= esc($endDate) ?>">
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <label class="form-label filter-label">Pilih Pemandu (Guide)</label>
            <select name="guide_id" class="form-select form-select-sm bg-white">
              <option value="">Semua Guide</option>
              <?php foreach (($guidesList ?? []) as $g): ?>
                <option value="<?= esc($g['id']) ?>" <?= ((string)$selectedGuideId === (string)$g['id']) ? 'selected' : '' ?>>
                  <?= esc($g['code'] . ' - ' . $g['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-lg-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-dark flex-grow-1">
              <i class="bi bi-filter"></i> Filter
            </button>
            <a href="<?= base_url('admin/laporan/guide') ?>" class="btn btn-sm btn-outline-secondary" title="Reset filter">
              <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            <?php
              $exportQuery = http_build_query(array_filter([
                'start'    => date('d-m-Y', strtotime($startDate)),
                'end'      => date('d-m-Y', strtotime($endDate)),
                'guide_id' => $selectedGuideId,
              ]));
            ?>
            <a href="<?= base_url('admin/laporan/guide/export-pdf?' . $exportQuery) ?>" class="btn btn-sm btn-transaksi" title="Cetak PDF">
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
            <h2>Pemandu Bertugas</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_guides_active'], 0, ',', '.') ?> <small>orang</small></h4>
          </div>
          <div class="stat-sub">
            <h4>Guide aktif pada periode ini</h4>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Total Rombongan / Trip</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_trips'], 0, ',', '.') ?> <small>trip</small></h4>
          </div>
          <div class="stat-sub green">
            <h4>Kunjungan dengan guide</h4>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Wisatawan Didampingi</h2>
          </div>
          <div class="stat-value">
            <h4><?= number_format($kpi['total_wisatawan'], 0, ',', '.') ?> <small>orang</small></h4>
          </div>
          <div class="stat-sub">
            <h4>Total orang yang dipandu</h4>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Total Komisi Guide</h2>
          </div>
          <div class="stat-value">
            <h4>Rp <?= number_format($kpi['total_komisi'], 0, ',', '.') ?></h4>
          </div>
          <div class="stat-sub">
            <h4>Total fee guide tercatat</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Table Rincian Komisi Guide -->
    <section id="table-guide" class="pt-2">
      <div class="container-fluid p-0">
        <div class="table-card">
          <div class="app-content-header">
            <div class="page-title">
              <h4 class="sub-text">Rincian Trip &amp; Komisi Pemandu Wisata</h4>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover datatable mb-0 align-middle" style="min-width: 850px;">
              <thead>
                <tr>
                  <th style="width: 4%">No</th>
                  <th style="width: 11%">Tanggal</th>
                  <th style="width: 16%">Nama Guide</th>
                  <th style="width: 12%">No. Kontak</th>
                  <th style="width: 14%">No. Tiket</th>
                  <th style="width: 17%">Rombongan / Customer</th>
                  <th style="width: 10%">Wisatawan</th>
                  <th style="width: 16%" class="text-end">Komisi Guide</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($records)): ?>
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada data trip guide pada rentang filter ini.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($records as $idx => $r): ?>
                    <tr>
                      <td><?= $idx + 1 ?></td>
                      <td><?= date('d/m/Y', strtotime($r['valid_date'])) ?></td>
                      <td>
                        <span class="fw-bold"><?= esc($r['guide_name'] ?? $r['guide_name_snapshot'] ?? '-') ?></span>
                        <?php if (!empty($r['guide_code'])): ?>
                          <div class="text-muted small"><?= esc($r['guide_code']) ?></div>
                        <?php endif; ?>
                      </td>
                      <td><?= esc($r['guide_phone'] ?? '-') ?></td>
                      <td>
                        <span class="font-monospace fw-bold"><?= esc($r['ticket_code']) ?></span>
                        <?php if (!empty($r['invoice_code'])): ?>
                          <div class="text-muted small"><?= esc($r['invoice_code']) ?></div>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?= esc($r['customer_name'] ?? 'Rombongan') ?>
                        <?php if (!empty($r['customer_origin'])): ?>
                          <div class="text-muted small"><i class="bi bi-geo-alt"></i> <?= esc($r['customer_origin']) ?></div>
                        <?php endif; ?>
                      </td>
                      <td class="fw-semibold"><?= (int)($r['total_visitors'] ?? 0) ?> orang</td>
                      <td class="text-end fw-bold text-success">
                        Rp <?= number_format((float)($r['guide_fee_amount'] ?? 0), 0, ',', '.') ?>
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
