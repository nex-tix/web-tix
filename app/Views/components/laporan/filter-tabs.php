<form method="get" action="<?= current_url() ?>" class="card border-0 shadow-sm bg-white mb-3">
  <input type="hidden" name="date" value="<?= esc($reportDate ?? '') ?>">
  <input type="hidden" name="checkpoint" value="<?= esc($filters['checkpoint_id'] ?? '') ?>">
  <input type="hidden" name="q" value="<?= esc($filters['search'] ?? '') ?>">
  <div class="card-body p-3">
    <div class="row g-3 align-items-end">
      <div class="col-sm-6 col-lg-4">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Metode Bayar</label>
        <select class="form-select form-select-sm" name="payment" onchange="this.form.submit()">
          <option value="">Semua</option>
          <option value="cash" <?= ($filters['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai (Cash)</option>
          <option value="debit" <?= ($filters['payment_method'] ?? '') === 'debit' ? 'selected' : '' ?>>Debit</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-4">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Kategori Tiket</label>
        <select class="form-select form-select-sm" name="category" onchange="this.form.submit()">
          <option value="">Semua</option>
          <option value="individual" <?= ($filters['ticket_category'] ?? '') === 'individual' ? 'selected' : '' ?>>Individual</option>
          <option value="group" <?= ($filters['ticket_category'] ?? '') === 'group' ? 'selected' : '' ?>>Group</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-4 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-dark flex-grow-1">Terapkan</button>
        <a href="<?= base_url('admin/laporan') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
      </div>
    </div>
  </div>
</form>