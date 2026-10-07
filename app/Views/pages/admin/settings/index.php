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

    <?php
    $headers = ['Key', 'Value', 'Diperbarui', 'Aksi'];
    $rows = [];
    foreach ($settings as $s) {
      $rows[] = [
        '<code class="fw-semibold">' . esc($s['setting_key']) . '</code>',
        '<div>' . esc($s['setting_value']) . '</div>',
        $s['updated_at'],
        '<button type="button" class="btn btn-sm btn-outline-primary me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#settingModal" '
          . 'data-key="' . esc($s['setting_key']) . '" data-value="' . esc($s['setting_value']) . '"><i class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/settings/' . urlencode($s['setting_key']) . '/delete') . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus setting ini?\')">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
        'title'           => 'Kelola Pengaturan',
        'subtitle'        => 'Konfigurasi sistem aplikasi.',
        'headers'         => $headers,
        'rows'            => $rows,
        'create_modal_id' => 'settingModal',
        'create_text'     => 'Tambah Setting',
        'empty_message'   => 'Belum ada data pengaturan.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="settingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="settingForm" method="POST" action="<?= base_url('admin/settings/store') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="settingModalTitle">Tambah Setting</h5>
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
          <div class="mb-3">
            <label for="setting_key" class="form-label">Setting Key <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="setting_key" name="setting_key" value="<?= old('setting_key') ?>" required maxlength="50">
            <div class="form-text">Contoh: company_name, address, phone</div>
          </div>
          <div class="mb-3">
            <label for="setting_value" class="form-label">Setting Value <span class="text-danger">*</span></label>
            <textarea class="form-control" id="setting_value" name="setting_value" rows="3" required maxlength="255"><?= old('setting_value') ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" style="background-color: #e24a3b; border-color: #e24a3b;">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      const form = document.getElementById('settingForm');
      form.action = '<?= base_url('admin/settings/') ?>' + encodeURIComponent(this.dataset.key) + '/update';
      document.getElementById('settingModalTitle').innerText = 'Edit Setting';
      document.getElementById('setting_key').value = this.dataset.key;
      document.getElementById('setting_key').readOnly = true;
      document.getElementById('setting_value').value = this.dataset.value;
    });
  });

  const settingModalEl = document.getElementById('settingModal');
  settingModalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('settingForm');
    form.action = '<?= base_url('admin/settings/store') ?>';
    form.reset();
    document.getElementById('settingModalTitle').innerText = 'Tambah Setting';
    document.getElementById('setting_key').readOnly = false;
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(settingModalEl).show();
  <?php endif; ?>
</script>

<?= $this->endSection() ?>
