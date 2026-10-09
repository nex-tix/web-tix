<form method="get" action="<?= current_url() ?>" class="card border-0 shadow-sm bg-white mb-3">
  <input type="hidden" name="date" value="<?= esc($reportDate ?? '') ?>">
  <div class="card-body p-3">
    <div class="d-flex gap-2 border-bottom pb-3 mb-3 overflow-auto flex-nowrap" style="font-size:.8rem;font-weight:600;white-space:nowrap">
      <?php
      $tabs = $tabs ?? ['Audit Scan Gate & Kehadiran', 'Demografi Pengunjung'];
      $active = $active_tab ?? ($tabs[0] ?? '');
      foreach ($tabs as $tab):
        $isActive = $tab === $active;
      ?>
        <button type="button" class="btn btn-sm <?= $isActive ? 'text-white' : 'text-muted border' ?>" style="<?= $isActive ? 'background:#CF4647;border-color:#CF4647' : 'background:#2c3136;border-color:#2c3136;color:white;' ?>"><?= esc($tab) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="row g-3 align-items-end">
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Checkpoint Gate</label>
        <select class="form-select form-select-sm" name="checkpoint">
          <option value="">Semua Checkpoint</option>
          <?php foreach (($checkpoints ?? []) as $checkpoint): ?>
            <option value="<?= esc($checkpoint['id']) ?>" <?= (int)($filters['checkpoint_id'] ?? 0) === (int)$checkpoint['id'] ? 'selected' : '' ?>><?= esc($checkpoint['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Metode Bayar</label>
        <select class="form-select form-select-sm" name="payment">
          <option value="">Semua</option>
          <option value="cash" <?= ($filters['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai (Cash)</option>
          <option value="debit" <?= ($filters['payment_method'] ?? '') === 'debit' ? 'selected' : '' ?>>Debit</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-2">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Kategori Tiket</label>
        <select class="form-select form-select-sm" name="category">
          <option value="">Semua</option>
          <option value="individual" <?= ($filters['ticket_category'] ?? '') === 'individual' ? 'selected' : '' ?>>Individual</option>
          <option value="group" <?= ($filters['ticket_category'] ?? '') === 'group' ? 'selected' : '' ?>>Group</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-2">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Cari Kode Tiket</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="search" name="q" class="form-control" value="<?= esc($filters['search'] ?? '') ?>" placeholder="No. Tiket / barcode...">
        </div>
      </div>
      <div class="col-sm-6 col-lg-2 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-dark flex-grow-1">Terapkan</button>
        <a href="<?= base_url('admin/laporan') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
      </div>
    </div>
  </div>
</form>
