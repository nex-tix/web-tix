<form method="get" action="<?= current_url() ?>" class="card border-0 shadow-sm bg-white mb-3">
  <div class="card-body p-3">
    <div class="row g-3 align-items-end">
      <div class="col-sm-6 col-lg-2">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Tanggal Mulai</label>
        <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($filters['date_from'] ?? '') ?>">
      </div>
      <div class="col-sm-6 col-lg-2">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Tanggal Selesai</label>
        <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($filters['date_to'] ?? '') ?>">
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Tipe Tiket</label>
        <select class="form-select form-select-sm" name="type_id" onchange="this.form.submit()">
          <option value="">Semua Tipe</option>
          <?php foreach (($ticketTypes ?? []) as $type): ?>
            <option value="<?= esc($type['id']) ?>" <?= (int)($filters['type_id'] ?? 0) === (int)$type['id'] ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-sm-6 col-lg-3">
        <label class="form-label" style="font-size:.7rem;font-weight:600;letter-spacing:.5px;color:var(--text-muted);text-transform:uppercase">Cari Kode Tiket</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
          <input type="search" name="q" class="form-control" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Kode tiket...">
        </div>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-dark">Terapkan</button>
        <a href="<?= base_url('admin/data-tiket') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
      </div>
    </div>
  </div>
</form>