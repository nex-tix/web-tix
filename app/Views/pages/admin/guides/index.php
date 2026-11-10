<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <?php
    $headers = ['Kode', 'Nama Guide', 'No. HP / WhatsApp', 'Gender', 'Bahasa', 'Status', 'Catatan', 'Aksi'];
    $rows = [];
    foreach ($guides as $g) {
      $langs = is_array($g['languages']) ? $g['languages'] : (is_string($g['languages']) ? json_decode($g['languages'], true) : []);
      $langBadges = '';
      if (!empty($langs) && is_array($langs)) {
        foreach ($langs as $lang) {
          $langBadges .= '<span class="badge bg-light text-dark border me-1 mb-1">' . esc($lang) . '</span>';
        }
      } else {
        $langBadges = '<span class="text-muted">-</span>';
      }

      $genderBadge = '<span class="text-muted">-</span>';
      if (($g['gender'] ?? '') === 'L') {
        $genderBadge = '<span class="badge bg-info text-dark">Laki-laki</span>';
      } elseif (($g['gender'] ?? '') === 'P') {
        $genderBadge = '<span class="badge bg-warning text-dark">Perempuan</span>';
      }

      $statusBadge = ($g['status'] ?? 'active') === 'active'
        ? '<span class="badge bg-success">Aktif</span>'
        : '<span class="badge bg-danger">Nonaktif</span>';

      $langsString = !empty($langs) && is_array($langs) ? implode(', ', $langs) : '';

      $rows[] = [
        '<span class="badge bg-secondary font-monospace">' . esc($g['code']) . '</span>',
        '<div><span class="fw-semibold">' . esc($g['name']) . '</span>' . (!empty($g['email']) ? '<div class="small text-muted">' . esc($g['email']) . '</div>' : '') . '</div>',
        !empty($g['phone']) ? '<span class="font-monospace">' . esc($g['phone']) . '</span>' : '<span class="text-muted">-</span>',
        $genderBadge,
        $langBadges,
        $statusBadge,
        !empty($g['notes']) ? '<span class="small" title="' . esc($g['notes']) . '">' . esc(mb_strimwidth($g['notes'], 0, 30, '...')) . '</span>' : '<span>-</span>',
        '<button type="button" class="btn btn-sm btn-outline-warning me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#guideModal" '
          . 'data-id="' . $g['id'] . '" '
          . 'data-code="' . esc($g['code']) . '" '
          . 'data-name="' . esc($g['name']) . '" '
          . 'data-phone="' . esc($g['phone'] ?? '') . '" '
          . 'data-email="' . esc($g['email'] ?? '') . '" '
          . 'data-gender="' . esc($g['gender'] ?? '') . '" '
          . 'data-languages="' . esc($langsString) . '" '
          . 'data-status="' . esc($g['status'] ?? 'active') . '" '
          . 'data-notes="' . esc($g['notes'] ?? '') . '"><i class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/guides/' . $g['id'] . '/delete') . '" method="POST" class="d-inline" data-confirm-delete="true" data-delete-message="Yakin ingin menghapus data guide ' . esc($g['name']) . '?">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
      'title'           => 'Kelola Guide (Pemandu)',
      'subtitle'        => 'Daftar pemandu wisata yang terdaftar dan siap mendampingi pengunjung.',
      'headers'         => $headers,
      'rows'            => $rows,
      'create_modal_id' => 'guideModal',
      'create_text'     => 'Tambah Guide',
      'empty_message'   => 'Belum ada data guide yang terdaftar.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="guideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="guideForm" method="POST" action="<?= base_url('admin/guides/store') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Guide</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if (session()->get('errors')): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach (session()->get('errors') as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="row g-3">
            <div class="col-md-4">
              <label for="code" class="form-label">Kode Guide</label>
              <input type="text" class="form-control" id="code" name="code" value="<?= old('code') ?>" placeholder="Auto (GD-001)">
              <div class="form-text">Biarkan kosong untuk generate otomatis format GD-001.</div>
            </div>
            <div class="col-md-8">
              <label for="name" class="form-label">Nama Guide <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" placeholder="Contoh: Wayan Suardana" required>
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label">No. HP / WhatsApp</label>
              <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" placeholder="Contoh: 081234567890">
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" placeholder="Contoh: guide@gmail.com">
            </div>

            <div class="col-md-4">
              <label for="gender" class="form-label">Jenis Kelamin</label>
              <select class="form-select" id="gender" name="gender">
                <option value="">- Pilih Gender -</option>
                <option value="L" <?= old('gender') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= old('gender') === 'P' ? 'selected' : '' ?>>Perempuan</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status">
                <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Aktif</option>
                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="languages" class="form-label">Bahasa yang Dikuasai</label>
              <input type="text" class="form-control" id="languages" name="languages" value="<?= old('languages') ?>" placeholder="Indonesia, English">
              <div class="form-text">Pisahkan dengan koma.</div>
            </div>

            <div class="col-12">
              <label for="notes" class="form-label">Catatan Tambahan</label>
              <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan opsional mengenai guide ini..."><?= old('notes') ?></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      const form = document.getElementById('guideForm');
      form.action = '<?= base_url('admin/guides/') ?>' + this.dataset.id + '/update';
      document.getElementById('modalTitle').innerText = 'Edit Data Guide';
      document.getElementById('code').value = this.dataset.code || '';
      document.getElementById('name').value = this.dataset.name || '';
      document.getElementById('phone').value = this.dataset.phone || '';
      document.getElementById('email').value = this.dataset.email || '';
      document.getElementById('gender').value = this.dataset.gender || '';
      document.getElementById('status').value = this.dataset.status || 'active';
      document.getElementById('languages').value = this.dataset.languages || '';
      document.getElementById('notes').value = this.dataset.notes || '';
    });
  });

  const modalEl = document.getElementById('guideModal');
  modalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('guideForm');
    form.action = '<?= base_url('admin/guides/store') ?>';
    form.reset();
    document.getElementById('modalTitle').innerText = 'Tambah Guide';
    document.getElementById('status').value = 'active';
  });

  document.addEventListener('DOMContentLoaded', function() {
    <?php if (session()->get('success')): ?>
      toastr.success(<?= json_encode(session()->get('success')) ?>, 'Berhasil', {
        timeOut: 1500,
        extendedTimeOut: 0
      });
    <?php endif; ?>

    <?php if (session()->get('error')): ?>
      toastr.error(<?= json_encode(session()->get('error')) ?>, 'Gagal', {
        timeOut: 2000,
        extendedTimeOut: 0
      });
    <?php endif; ?>

    <?php if (session()->get('errors')): ?>
      toastr.error('Silakan periksa kembali isian form guide.', 'Validasi Gagal', {
        timeOut: 2500,
        extendedTimeOut: 0
      });
    <?php endif; ?>
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(modalEl).show();

    <?php if (session('modal') === 'edit' && session()->has('edit_id')): ?>
      const editForm = document.getElementById('guideForm');
      editForm.action = '<?= base_url('admin/guides/') ?>' + <?= (int) session('edit_id') ?> + '/update';
      document.getElementById('modalTitle').innerText = 'Edit Data Guide';
    <?php endif; ?>
  <?php endif; ?>
</script>

<?= $this->endSection() ?>

