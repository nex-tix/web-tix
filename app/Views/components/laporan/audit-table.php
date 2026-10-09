<div class="table-card">
  <div class="card-header-custom">
    <div>
      <h6 style="color:#2c3136"><?= esc($title ?? 'Audit Log Scan Tiket & Kehadiran Per Gate') ?></h6>
      <p><?= esc($subtitle ?? 'Catatan real-time verifikasi barcode tiket di pintu masuk teater') ?></p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
      <span class="badge bg-success-subtle border text-success d-inline-flex align-items-center gap-1" style="font-size:.7rem;background:#e8f5e9!important;color:#198754!important;border-color:#c3e6cb!important"><span class="rounded-circle bg-success d-inline-block" style="width:6px;height:6px"></span> Live Sync Checkpoint</span>
      <button type="button" class="btn btn-sm btn-light border" title="Refresh data"><i class="bi bi-arrow-clockwise"></i></button>
    </div>
  </div>
  <div class="table-responsive">
    <table id="<?= esc($table_id ?? 'laporanAuditTable') ?>" class="table table-hover" style="width:100%">
      <thead>
        <tr style="background:#1a1d20">
          <?php foreach (($headers ?? []) as $h): ?><th><?= esc($h) ?></th><?php endforeach; ?>
        </tr>
      </thead>
      <tbody class="table-light">
        <?php if (!empty($rows)): ?>
          <?php foreach ($rows as $row): ?>
            <tr>
              <?php foreach ($row as $cell): ?><td><?= $cell ?></td><?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="<?= count($headers ?? []) ?>" class="text-center text-muted" style="padding:2rem">
              <i class="bi bi-inbox text-black" style="font-size:2rem;opacity:.3"></i>
              <div class="mt-2 text-black"><p>Tidak ada data audit scan untuk filter yang dipilih</p></div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
