<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <?php if (session()->get('success')): ?>
      <!-- isi dibungkus .ico + .body agar cocok dengan aturan grid .alert di tailpanel.css -->
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="ico bi bi-check-circle-fill" aria-hidden="true"></i>
        <div class="body"><?= session()->get('success') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <?php if (session()->get('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="ico bi bi-exclamation-circle-fill" aria-hidden="true"></i>
        <div class="body"><?= session()->get('error') ?></div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Peringatan kini berbentuk toast sticky di kanan layar (lihat blok script gajiWarnings) -->

    <!-- Header + chip tarif per kepala (dibaca dari settings) -->
    <div class="app-content-header">
      <div class="page-title mb-3">
        <h4 class="text-slate-900 d-inline-block me-2">Gaji Guide</h4>
        <!-- badge-tarif: bg putih + teks slate-600 (3A) — aturannya di main.css -->
        <span class="badge badge-tarif align-middle">
          Tarif / kepala: Dewasa Rp <?= number_format($tarifAdult, 0, ',', '.') ?> &middot; Anak Rp <?= number_format($tarifChild, 0, ',', '.') ?>
        </span>
      </div>
    </div>

    <!-- Filter periode & guide -->
    <div class="filter-export table-card mb-3 p-3">
      <form method="get" action="<?= base_url('admin/gaji-guide') ?>" id="form-filter">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-sm-6 col-lg-3">
            <label class="form-label filter-label">Tanggal Awal</label>
            <input type="date" name="start" class="form-control form-control-sm" value="<?= esc($startDate) ?>">
          </div>
          <div class="col-6 col-sm-6 col-lg-3">
            <label class="form-label filter-label">Tanggal Akhir</label>
            <input type="date" name="end" class="form-control form-control-sm" value="<?= esc($endDate) ?>">
          </div>
          <div class="col-12 col-sm-6 col-lg-2">
            <label class="form-label filter-label">Pilih Guide</label>
            <select name="guide_id" class="form-select form-select-sm bg-white">
              <option value="">Semua Guide</option>
              <?php foreach (($guides ?? []) as $g): ?>
                <option value="<?= esc($g['id']) ?>" <?= ((string) $selectedGuideId === (string) $g['id']) ? 'selected' : '' ?>>
                  <?= esc($g['code'] . ' - ' . $g['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-lg-2">
            <label class="form-label filter-label">Status</label>
            <select name="status" class="form-select form-select-sm bg-white">
              <option value="">Semua Status</option>
              <option value="draft" <?= $selectedStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="paid" <?= $selectedStatus === 'paid' ? 'selected' : '' ?>>Dibayar</option>
              <option value="cancelled" <?= $selectedStatus === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
            </select>
          </div>
          <!-- col-lg-2 (bukan 1) agar dua tombol cukup lebar dan tidak luber keluar card -->
          <div class="col-12 col-sm-6 col-lg-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-dark flex-grow-1"><i class="bi bi-filter"></i> Filter</button>
            <a href="<?= base_url('admin/gaji-guide') ?>" class="btn btn-sm btn-outline-dark" title="Reset filter" aria-label="Reset filter"><i class="bi bi-arrow-counterclockwise"></i></a>
          </div>
        </div>
        <div class="form-text">Default periode = bulan berjalan. Ledger tampil jika periodenya tumpang tindih dengan rentang filter.</div>
      </form>
    </div>

    <!-- Kartu KPI -->
    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label"><h2>Ledger Draft</h2></div>
          <div class="stat-value"><h4><?= number_format($kpi['draft_count'], 0, ',', '.') ?> <small>ledger</small></h4></div>
          <div class="stat-sub"><h4>Rp <?= number_format($kpi['draft_amount'], 0, ',', '.') ?></h4></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label"><h2>Sudah Dibayar</h2></div>
          <div class="stat-value"><h4><?= number_format($kpi['paid_count'], 0, ',', '.') ?> <small>ledger</small></h4></div>
          <div class="stat-sub green"><h4>Rp <?= number_format($kpi['paid_amount'], 0, ',', '.') ?></h4></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label"><h2>Total Pengunjung</h2></div>
          <div class="stat-value"><h4><?= number_format($kpi['visitors'], 0, ',', '.') ?> <small>orang</small></h4></div>
          <div class="stat-sub"><h4>Dewasa <?= number_format($kpi['adult'], 0, ',', '.') ?> &middot; Anak <?= number_format($kpi['child'], 0, ',', '.') ?></h4></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label"><h2>Total Nominal Aktif</h2></div>
          <div class="stat-value"><h4>Rp <?= number_format($kpi['active_amount'], 0, ',', '.') ?></h4></div>
          <div class="stat-sub"><h4>Draft + sudah dibayar</h4></div>
        </div>
      </div>
    </div>

    <?php
    $headers = ['Kode', 'Guide', 'Periode', 'Dewasa', 'Anak', 'Nominal', 'Status', 'Aksi'];
    $rows = [];
    foreach ($ledgers as $l) {
        // breakdown_json dari query builder mentah -> decode manual
        $breakdownJson = is_string($l['breakdown_json'] ?? null) ? $l['breakdown_json'] : json_encode($l['breakdown_json'] ?? [], JSON_UNESCAPED_UNICODE);

        $badgeClass = 'bg-secondary';
        if ($l['status'] === 'draft') {
            $badgeClass = 'bg-warning text-dark';
        } elseif ($l['status'] === 'paid') {
            $badgeClass = 'bg-success';
        }
        $statusLabel = ['draft' => 'Draft', 'paid' => 'Dibayar', 'cancelled' => 'Dibatalkan'][$l['status']] ?? $l['status'];

        // Pakai en-dash unicode (bukan entity) supaya aman di atribut data-* maupun di sel tabel
        $periode = date('d/m/Y', strtotime($l['period_start'])) . ' – ' . date('d/m/Y', strtotime($l['period_end']));

        // Tombol Bayar hanya untuk draft; Batal untuk draft/paid (5A)
        $aksi = '<button type="button" class="btn btn-sm btn-outline-secondary me-1 btn-detail" title="Detail"'
            . ' data-bs-toggle="modal" data-bs-target="#detailModal"'
            . ' data-kode="' . esc($l['ledger_code']) . '"'
            . ' data-guide="' . esc(($l['guide_code'] ?? '') . ' - ' . ($l['guide_name'] ?? '-')) . '"'
            . ' data-periode="' . esc($periode) . '"'
            . ' data-status="' . esc($statusLabel) . '"'
            . ' data-adult="' . (int) $l['total_adult'] . '"'
            . ' data-child="' . (int) $l['total_child'] . '"'
            . ' data-visitors="' . (int) $l['total_visitors'] . '"'
            . ' data-total="' . esc(number_format((float) $l['total_amount'], 0, ',', '.')) . '"'
            . ' data-breakdown="' . esc($breakdownJson) . '"><i class="bi bi-eye"></i></button>';

        if ($l['status'] === 'draft') {
            $aksi .= '<button type="button" class="btn btn-sm btn-outline-success me-1 btn-pay" title="Tandai Dibayar"'
                . ' data-bs-toggle="modal" data-bs-target="#payModal"'
                . ' data-id="' . (int) $l['id'] . '"'
                . ' data-kode="' . esc($l['ledger_code']) . '"'
                . ' data-guide="' . esc(($l['guide_code'] ?? '') . ' - ' . ($l['guide_name'] ?? '-')) . '"'
                . ' data-total="' . esc(number_format((float) $l['total_amount'], 0, ',', '.')) . '"><i class="bi bi-cash-coin"></i></button>';
        }

        if (in_array($l['status'], ['draft', 'paid'], true)) {
            $aksi .= '<form action="' . base_url('admin/gaji-guide/' . (int) $l['id'] . '/cancel') . '" method="POST" class="d-inline" data-confirm-delete="true" data-delete-message="Yakin ingin membatalkan ledger ' . esc($l['ledger_code']) . '? Tiket akan dilepas dan bisa digenerate ulang.">'
                . csrf_field()
                . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan"><i class="bi bi-x-circle"></i></button>'
                . '</form>';
        }

        $rows[] = [
            '<span class="badge bg-dark font-monospace">' . esc($l['ledger_code']) . '</span>',
            '<div><span class="fw-semibold">' . esc($l['guide_name'] ?? '-') . '</span><div class="text-gray small">' . esc($l['guide_code'] ?? '') . '</div></div>',
            '<span class="small">' . $periode . '</span>',
            number_format((int) $l['total_adult'], 0, ',', '.'),
            number_format((int) $l['total_child'], 0, ',', '.'),
            '<span class="fw-bold">Rp ' . number_format((float) $l['total_amount'], 0, ',', '.') . '</span>',
            '<span class="badge ' . $badgeClass . '">' . $statusLabel . '</span>',
            $aksi,
        ];
    }

    echo view('components/data-table', [
        'title'           => 'Daftar Ledger Gaji Guide',
        'subtitle'        => 'Ledger dibuat per guide & periode; angka berasal dari transaksi POS yang membawa guide.',
        'headers'         => $headers,
        'rows'            => $rows,
        'create_modal_id' => 'generateModal',
        'create_text'     => 'Buat Ledger',
    ]);
    ?>
  </div>
</div>

<!-- Modal Buat Ledger (generate dari transaksi ber-guide pada periode) -->
<div class="modal fade" id="generateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="generateForm" method="POST" action="<?= base_url('admin/gaji-guide/generate') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="generateModalTitle">Buat Ledger Gaji Guide</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if (session()->get('errors')): ?>
            <!-- struktur .ico + .body mengikuti aturan grid .alert di tailpanel.css -->
            <div class="alert alert-danger">
              <i class="ico bi bi-exclamation-circle-fill" aria-hidden="true"></i>
              <div class="body">
                <ul class="mb-0">
                  <?php foreach (session()->get('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>
          <div class="alert alert-info">
            <i class="ico bi bi-info-circle-fill" aria-hidden="true"></i>
            <div class="body">Sistem menarik semua tiket ber-guide pada periode yang belum masuk ledger mana pun, lalu menghitung dewasa/anak dari tipe tiket.</div>
          </div>
          <div class="row g-3">
            <div class="col-md-12">
              <label for="gen-guide" class="form-label">Guide <span class="text-danger">*</span></label>
              <select class="form-select" id="gen-guide" name="guide_id" required>
                <option value="" selected disabled>- Pilih Guide -</option>
                <?php foreach (($guides ?? []) as $g): ?>
                  <option value="<?= (int) $g['id'] ?>" <?= (string) old('guide_id') === (string) $g['id'] ? 'selected' : '' ?>>
                    <?= esc($g['code'] . ' - ' . $g['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback">Pilih guide terlebih dahulu.</div>
            </div>
            <div class="col-md-6">
              <label for="gen-start" class="form-label">Tanggal Awal <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="gen-start" name="start" value="<?= esc(old('start', $startDate)) ?>" required>
              <div class="invalid-feedback">Tanggal awal wajib diisi.</div>
            </div>
            <div class="col-md-6">
              <label for="gen-end" class="form-label">Tanggal Akhir <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="gen-end" name="end" value="<?= esc(old('end', $endDate)) ?>" required>
              <div class="invalid-feedback">Tanggal akhir wajib diisi.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Buat Ledger</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Tandai Dibayar -->
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="payForm" method="POST" action="<?= base_url('admin/gaji-guide') ?>/0/pay" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title">Tandai Ledger Dibayar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="rounded border bg-light p-3 mb-3 small">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-gray">Kode Ledger</span><strong class="font-monospace" id="pay-info-kode">-</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-gray">Guide</span><strong id="pay-info-guide">-</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-gray">Total Gaji</span><strong class="text-success" id="pay-info-total">Rp 0</strong>
            </div>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="pay-method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
              <select class="form-select" id="pay-method" name="payment_method" required>
                <option value="" selected disabled>- Pilih Metode -</option>
                <option value="cash">Tunai</option>
                <option value="transfer">Transfer Bank</option>
                <option value="qris">QRIS</option>
                <option value="other">Lainnya</option>
              </select>
              <div class="invalid-feedback">Metode pembayaran wajib dipilih.</div>
            </div>
            <div class="col-md-6">
              <label for="pay-ref" class="form-label">No. Referensi</label>
              <input type="text" class="form-control" id="pay-ref" name="payment_reference" maxlength="100" placeholder="Contoh: TRF-BCA-0001 (opsional)">
            </div>
            <div class="col-12">
              <label for="pay-notes" class="form-label">Catatan</label>
              <textarea class="form-control" id="pay-notes" name="notes" rows="2" maxlength="500" placeholder="Catatan opsional..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Detail Rincian (breakdown per tiket) -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Ledger <span class="font-monospace" id="detail-kode">-</span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-2 small mb-3">
          <div class="col-6"><span class="text-gray">Guide:</span> <strong id="detail-guide">-</strong></div>
          <div class="col-6"><span class="text-gray">Periode:</span> <strong id="detail-periode">-</strong></div>
          <div class="col-6"><span class="text-gray">Dewasa / Anak:</span> <strong><span id="detail-adult">0</span> / <span id="detail-child">0</span></strong></div>
          <div class="col-6"><span class="text-gray">Total Pengunjung:</span> <strong><span id="detail-visitors">0</span> orang</strong></div>
          <div class="col-6"><span class="text-gray">Status:</span> <strong id="detail-status">-</strong></div>
          <div class="col-6"><span class="text-gray">Total Gaji:</span> <strong class="text-success" id="detail-total">Rp 0</strong></div>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle mb-0 p-3">
            <thead class="table-light">
              <tr>
                <th class="p-2">Tanggal</th>
                <th class="p-2">No. Tiket</th>
                <th class="p-2">Invoice</th>
                <th class="p-2">Tipe Tiket</th>
                <th class="text-end p-2">Qty</th>
                <th class="text-end p-2">Fee</th>
              </tr>
            </thead>
            <tbody id="detail-items-body" class="table-light p-2"></tbody>
            <tfoot>
              <tr>
                <th colspan="5" class="text-end">Total Fee</th>
                <th class="text-end" id="detail-grand">Rp 0</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  const rp = v => 'Rp ' + Number(v || 0).toLocaleString('id-ID');

  document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->get('success')): ?>
      toastr.success(<?= json_encode(session()->get('success')) ?>, 'Berhasil', { timeOut: 2500, extendedTimeOut: 0 });
    <?php endif; ?>
    <?php if (session()->get('error')): ?>
      toastr.error(<?= json_encode(session()->get('error')) ?>, 'Gagal', { timeOut: 3000, extendedTimeOut: 0 });
    <?php endif; ?>
    <?php if (session()->get('errors')): ?>
      toastr.error('Periksa kembali kolom yang ditandai merah.', 'Validasi Gagal', { timeOut: 2500, extendedTimeOut: 0 });
    <?php endif; ?>
  });

  // Interseksi submit form generate/pay: cek required kosong dulu (pola guides/index.php)
  ['generateForm', 'payForm'].forEach(function(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('input, select, textarea').forEach(field => {
      const clearError = function() {
        if (field.value && field.value.trim() !== '') field.classList.remove('is-invalid');
      };
      field.addEventListener('input', clearError);
      field.addEventListener('change', clearError);
    });
    form.addEventListener('submit', function(event) {
      let hasEmpty = false;
      form.querySelectorAll('[required]').forEach(input => {
        const val = (input.value || '').trim();
        if (!val) { hasEmpty = true; input.classList.add('is-invalid'); }
        else input.classList.remove('is-invalid');
      });
      if (hasEmpty || !form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        form.classList.add('was-validated');
        toastr.error('Kolom tidak boleh kosong', 'Validasi Gagal', { timeOut: 2500, extendedTimeOut: 0 });
        const firstInvalid = form.querySelector('.is-invalid, :invalid');
        if (firstInvalid) firstInvalid.focus();
        return false;
      }
    });
  });

  // Tombol Bayar: isi info ledger + set action POST sesuai id
  document.querySelectorAll('.btn-pay').forEach(btn => {
    btn.addEventListener('click', function() {
      const form = document.getElementById('payForm');
      form.classList.remove('was-validated');
      form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      form.action = '<?= base_url('admin/gaji-guide') ?>/' + this.dataset.id + '/pay';
      document.getElementById('pay-info-kode').textContent = this.dataset.kode || '-';
      document.getElementById('pay-info-guide').textContent = this.dataset.guide || '-';
      document.getElementById('pay-info-total').textContent = 'Rp ' + (this.dataset.total || '0');
      form.reset();
    });
  });

  const payModalEl = document.getElementById('payModal');
  payModalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('payForm');
    form.action = '<?= base_url('admin/gaji-guide') ?>/0/pay';
    form.reset();
    form.classList.remove('was-validated');
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  });

  // Tombol Detail: render rincian breakdown_json ke tabel modal
  document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('detail-kode').textContent = this.dataset.kode || '-';
      document.getElementById('detail-guide').textContent = this.dataset.guide || '-';
      document.getElementById('detail-periode').textContent = this.dataset.periode || '-';
      document.getElementById('detail-status').textContent = this.dataset.status || '-';
      document.getElementById('detail-adult').textContent = this.dataset.adult || '0';
      document.getElementById('detail-child').textContent = this.dataset.child || '0';
      document.getElementById('detail-visitors').textContent = this.dataset.visitors || '0';
      document.getElementById('detail-total').textContent = 'Rp ' + (this.dataset.total || '0');

      let breakdown = [];
      try { breakdown = JSON.parse(this.dataset.breakdown || '[]'); } catch (e) { breakdown = []; }

      const body = document.getElementById('detail-items-body');
      body.innerHTML = '';
      let grand = 0;
      (Array.isArray(breakdown) ? breakdown : []).forEach(t => {
        (Array.isArray(t.items) ? t.items : []).forEach(it => {
          grand += Number(it.fee_subtotal) || 0;
          body.insertAdjacentHTML('beforeend',
            '<tr>'
            + '<td class="small text-center">' + escapeHtml(t.date || '-') + '</td>'
            + '<td class="small text-center font-monospace">' + escapeHtml(t.ticket_code || '-') + '</td>'
            + '<td class="small text-center font-monospace">' + escapeHtml(t.invoice_code || '-') + '</td>'
            + '<td class="small text-center">' + escapeHtml(it.type || '-') + '</td>'
            + '<td class="text-end small pe-2">' + Number(it.qty || 0) + '</td>'
            + '<td class="text-end small pe-2 fw-semibold">' + rp(it.fee_subtotal) + '</td>'
            + '</tr>');
        });
      });
      if (!body.innerHTML) {
        body.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada rincian.</td></tr>';
      }
      document.getElementById('detail-grand').textContent = rp(grand);
    });
  });

  const escapeHtml = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  // Buka modal generate prefilled (dipakai klik isi toast peringatan)
  function openGeneratePrefill(guideId, start, end, title) {
    const form = document.getElementById('generateForm');
    form.classList.remove('was-validated');
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.getElementById('gen-guide').value = guideId;
    document.getElementById('gen-start').value = start;
    document.getElementById('gen-end').value = end;
    document.getElementById('generateModalTitle').innerText = title || 'Buat Ledger';
    bootstrap.Modal.getOrCreateInstance(document.getElementById('generateModal')).show();
  }

  // Toast sticky per peringatan: tidak auto-dismiss, tutup lewat ✕, klik isi → modal generate
  document.addEventListener('DOMContentLoaded', function() {
    (<?= json_encode($warnings ?? [], JSON_HEX_TAG | JSON_HEX_AMP) ?>).forEach(function(w) {
      const body = '<div class="gaji-warn-body"'
        + ' data-guide-id="' + Number(w.guide_id) + '"'
        + ' data-start="' + escapeHtml(w.start) + '"'
        + ' data-end="' + escapeHtml(w.end) + '"'
        + ' data-title="' + (w.type === 'cancelled' ? 'Buat Ulang Ledger' : 'Buat Ledger Susulan') + '">'
        + '<strong>' + escapeHtml(w.guide_code) + ' - ' + escapeHtml(w.guide_name) + '</strong><br>'
        + escapeHtml(w.label) + ' · ' + Number(w.count) + ' tiket · Rp ' + Number(w.amount).toLocaleString('id-ID') + '<br>'
        + '<span style="opacity:.85">Klik untuk buat ledger →</span></div>';
      toastr.warning(body,
        w.type === 'cancelled' ? 'Periode Dibatalkan' : 'Periode Sudah Dibayar',
        {
          timeOut: 0, extendedTimeOut: 0, closeButton: true, tapToDismiss: false,
          position: 'top-right', preventDuplicates: true, maxVisible: 10
        });
    });
  });

  // Klik isi toast → buka modal generate sesuai data peringatan
  document.addEventListener('click', function(e) {
    const body = e.target.closest('.gaji-warn-body');
    if (!body) return;
    openGeneratePrefill(body.dataset.guideId, body.dataset.start, body.dataset.end, body.dataset.title);
  });

  // Kembalikan judul modal generate ke semula setelah ditutup
  document.getElementById('generateModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('generateModalTitle').innerText = 'Buat Ledger Gaji Guide';
  });

  // Buka lagi modal generate jika server mengembalikan error validasi (pola guides/index.php)
  <?php if (session()->has('modal') && session('modal') === 'generate'): ?>
    new bootstrap.Modal(document.getElementById('generateModal')).show();
  <?php endif; ?>
</script>
<?= $this->endSection() ?>
