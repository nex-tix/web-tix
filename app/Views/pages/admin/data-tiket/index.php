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
      <div class="page-title mb-3">
        <h4 class="text-slate-900">Data Tiket</h4>
        <p class="text-slate-500">Seluruh tiket berdasarkan tanggal pembelian, tipe, dan kode tiket.</p>
      </div>
    </div>

    <?= view('components/data-tiket-filter', [
      'filters'     => $filters ?? [],
      'ticketTypes' => $ticketTypes ?? [],
    ]) ?>

    <div class="border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTiketTable" class="table table-hover mb-0" style="width:100%">
            <thead>
              <tr style="background: #1a1d20; color: #fff;">
                <th>Tanggal</th>
                <th>Kode Tiket</th>
                <th>Invoice</th>
                <th>Tipe</th>
                <th>Pengunjung</th>
                <th>Hadir</th>
                <th>Status</th>
                <th>Kasir</th>
                <th style="text-align:center">Aksi</th>
              </tr>
            </thead>
            <tbody class="table-light">
                <?php 
                $nowWita = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Makassar'));
                $todayWita = $nowWita->format('Y-m-d');
                ?>
                <?php foreach ($tickets as $row): ?>
                  <?php
                  $isExpired = false;
                  $validDate = $row['valid_date'] ?? '';
                  if (($row['status'] ?? '') === 'active') {
                      if (!empty($validDate) && $validDate < $todayWita) {
                          $isExpired = true;
                      } elseif (!empty($validDate) && $validDate === $todayWita && !empty($row['show_time'])) {
                          if (preg_match('/(\d{1,2})[:.](\d{2})\s*-\s*(\d{1,2})[:.](\d{2})/', $row['show_time'], $m)) {
                              $endH = str_pad($m[3], 2, '0', STR_PAD_LEFT);
                              $endM = str_pad($m[4], 2, '0', STR_PAD_LEFT);
                              $sessionEnd = new \DateTimeImmutable("{$validDate} {$endH}:{$endM}:00", new \DateTimeZone('Asia/Makassar'));
                              if ($nowWita > $sessionEnd) {
                                  $isExpired = true;
                              }
                          }
                      }
                  }
                  ?>
                  <tr>
                    <td data-order="<?= strtotime($row['created_at']) ?>">
                      <div class="fw-semibold"><?= date('d M Y', strtotime($row['created_at'])) ?></div>
                      <small><?= date('H:i', strtotime($row['created_at'])) ?> WITA</small>
                      <?php if (!empty($row['show_time'])): ?>
                        <div class="small text-muted"><i class="bi bi-clock me-1"></i><?= esc($row['show_time']) ?></div>
                      <?php endif; ?>
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
                    <td>
                      <?= (int)($row['total_visitors'] ?? 0) ?> orang
                    </td>
                    <td>
                      <?php if (isset($row['actual_visitors']) && $row['actual_visitors'] !== null && $row['actual_visitors'] !== '' && (int)$row['actual_visitors'] > 0): ?>
                        <?= (int)$row['actual_visitors'] ?> orang
                      <?php else: ?>
                        <span class="fw-bold"> - </span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (($row['status'] ?? '') === 'active'): ?>
                        <?php if ($isExpired): ?>
                          <span class="badge bg-secondary" title="Sesi pertunjukan atau tanggal telah lewat">Kedaluwarsa</span>
                        <?php else: ?>
                          <span class="badge bg-success">Belum Terpakai</span>
                        <?php endif; ?>
                      <?php elseif (($row['status'] ?? '') === 'used'): ?>
                        <span class="badge bg-primary">Terpakai</span>
                      <?php else: ?>
                        <span class="badge bg-danger">Dibatalkan</span>
                      <?php endif; ?>
                    </td>
                    <td><?= esc($row['admin_name'] ?? '-') ?></td>
                    <td style="text-align:center">
                      <a href="<?= base_url('admin/data-tiket/detail/' . $row['id']) ?>" class="btn btn-sm btn-outline-dark" title="Detail">
                        <i class="bi bi-eye action-icon"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>