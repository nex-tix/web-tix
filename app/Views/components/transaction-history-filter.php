<form method="get" action="<?= current_url() ?>" class="card border-0 shadow-sm bg-white mb-3">
  <div class="card-body p-3">
    <div class="row g-3 align-items-end">
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Tanggal</label>
        <input type="date" name="date" class="form-control form-control-sm" value="<?= esc($filters['date'] ?? '') ?>">
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Metode Bayar</label>
        <select class="form-select form-select-sm" name="payment" onchange="this.form.submit()">
          <option value="">Semua</option>
          <option value="cash" <?= ($filters['payment'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai (Cash)</option>
          <option value="debit" <?= ($filters['payment'] ?? '') === 'debit' ? 'selected' : '' ?>>Debit</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Kasir / Admin</label>
        <select class="form-select form-select-sm" name="user_id" onchange="this.form.submit()">
          <option value="">Semua Kasir</option>
          <?php foreach (($users ?? []) as $user): ?>
            <option value="<?= esc($user['id']) ?>" <?= (int)($filters['user_id'] ?? 0) === (int)$user['id'] ? 'selected' : '' ?>><?= esc($user['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Cari Invoice / Nama</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="search" name="q" class="form-control" value="<?= esc($filters['q'] ?? '') ?>" placeholder="INV-... / nama pembeli">
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-dark">Terapkan</button>
        <a href="<?= base_url('admin/transaction-history') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
      </div>
    </div>
  </div>
</form>