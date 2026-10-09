<form method="get" action="<?= current_url() ?>" class="card border-0 shadow-sm bg-white mb-3">
  <input type="hidden" name="date" value="<?= esc($reportDate ?? '') ?>">
  <input type="hidden" name="payment" value="<?= esc($filters['payment_method'] ?? '') ?>">
  <input type="hidden" name="category" value="<?= esc($filters['ticket_category'] ?? '') ?>">
  <div class="card-body p-3">
    <div class="row g-3 align-items-end">
      <div class="col-sm-6 col-lg-4">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Checkpoint Gate</label>
        <select class="form-select form-select-sm" name="checkpoint" onchange="this.form.submit()">
          <option value="">Semua Checkpoint</option>
          <?php foreach (($checkpoints ?? []) as $checkpoint): ?>
            <option value="<?= esc($checkpoint['id']) ?>" <?= (int)($filters['checkpoint_id'] ?? 0) === (int)$checkpoint['id'] ? 'selected' : '' ?>><?= esc($checkpoint['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-sm-6 col-lg-4">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Cari Kode Tiket</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="search" name="q" class="form-control" value="<?= esc($filters['search'] ?? '') ?>" placeholder="No. Tiket / barcode...">
        </div>
      </div>
      <div class="col-sm-6 col-lg-4 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-dark flex-grow-1">Terapkan</button>
        <a href="<?= base_url('admin/laporan') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
      </div>
    </div>
  </div>
</form>