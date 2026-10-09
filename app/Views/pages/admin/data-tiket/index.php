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
        <h4 style="color:#2c3136">Data Tiket</h4>
        <p style="color:#2c3136">Seluruh tiket berdasarkan tanggal pembelian, tipe, dan kode tiket.</p>
      </div>
    </div>

    <?= view('components/data-tiket-filter', [
      'filters'     => $filters ?? [],
      'ticketTypes' => $ticketTypes ?? [],
    ]) ?>

    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="">
              <tr style="background: #1a1d20; color: #fff;">
                <th>Tanggal</th>
                <th>Kode Tiket</th>
                <th>Invoice</th>
                <th>Tipe</th>
                <th>Pengunjung</th>
                <th>Status</th>
                <th>Kasir</th>
                <th style="text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-light">
              <?php if (empty($tickets)): ?>
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    <i class="bi bi-inbox" style="font-size:2rem;opacity:.3"></i>
                    <div class="mt-2">Tidak ada tiket ditemukan.</div>
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($tickets as $row): ?>
                  <tr>
                    <td>
                      <div class="fw-semibold"><?= date('d M Y', strtotime($row['created_at'])) ?></div>
                      <small><?= date('H:i', strtotime($row['created_at'])) ?> WITA</small>
                    </td>
                    <td class="font-monospace fw-bold"><?= esc($row['ticket_code']) ?></td>
                    <td class="font-monospace"><?= esc($row['invoice_code'] ?? '-') ?></td>
                    <td>
                      <?php if (($row['type_names'] ?? '') !== ''): ?>
                        <?= esc($row['type_names']) ?>
                      <?php else: ?>
                        <span class="badge bg-<?= ($row['ticket_category'] ?? '') === 'group' ? 'warning text-dark' : 'danger' ?>">
                          <?= esc(ucfirst($row['ticket_category'] ?? '-')) ?>
                        </span>
                      <?php endif; ?>
                    </td>
                    <td><?= (int)($row['total_visitors'] ?? 0) ?> orang</td>
                    <td>
                      <?php if (($row['status'] ?? '') === 'active'): ?>
                        <span class="badge bg-success">Aktif</span>
                      <?php elseif (($row['status'] ?? '') === 'used'): ?>
                        <span class="badge bg-primary">Terpakai</span>
                      <?php else: ?>
                        <span class="badge bg-danger">Dibatalkan</span>
                      <?php endif; ?>
                    </td>
                    <td><?= esc($row['admin_name'] ?? '-') ?></td>
                    <td style="text-align:center">
                      <a href="<?= base_url('admin/data-tiket/detail/' . $row['id']) ?>" class="btn btn-sm btn-outline-dark" title="Detail">
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
