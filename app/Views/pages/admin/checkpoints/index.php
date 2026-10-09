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
    $headers = ['Nama', 'Status', 'Dibuat', 'Aksi'];
    $rows = [];
    foreach ($checkpoints as $cp) {
      $rows[] = [
        '<div class="fw-semibold">' . esc($cp['name']) . '</div>',
        $cp['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>',
        $cp['created_at'],
        '<button type="button" class="btn btn-sm btn-outline-primary me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#checkpointModal" '
          . 'data-id="' . $cp['id'] . '" data-name="' . esc($cp['name']) . '" data-active="' . $cp['is_active'] . '"><i class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/checkpoints/' . $cp['id'] . '/delete') . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus checkpoint ini?\')">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
        'title'           => 'Kelola Checkpoint',
        'subtitle'        => 'Daftar checkpoint untuk proses pemeriksaan tiket.',
        'headers'         => $headers,
        'rows'            => $rows,
        'create_modal_id' => 'checkpointModal',
        'create_text'     => 'Tambah Checkpoint',
        'empty_message'   => 'Belum ada data checkpoint.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="checkpointModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="checkpointForm" method="POST" action="<?= base_url('admin/checkpoints/store') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="checkpointModalTitle">Tambah Checkpoint</h5>
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
            <label for="checkpoint_name" class="form-label">Nama Checkpoint <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="checkpoint_name" name="name" value="<?= old('name') ?>" placeholder="Contoh: Gerbang Utama" required maxlength="50">
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="checkpoint_is_active" name="is_active" value="1" <?= old('is_active', 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="checkpoint_is_active">Aktif</label>
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
      const form = document.getElementById('checkpointForm');
      form.action = '<?= base_url('admin/checkpoints/') ?>' + this.dataset.id + '/update';
      document.getElementById('checkpointModalTitle').innerText = 'Edit Checkpoint';
      document.getElementById('checkpoint_name').value = this.dataset.name;
      document.getElementById('checkpoint_is_active').checked = this.dataset.active == '1';
    });
  });

  const checkpointModalEl = document.getElementById('checkpointModal');
  checkpointModalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('checkpointForm');
    form.action = '<?= base_url('admin/checkpoints/store') ?>';
    form.reset();
    document.getElementById('checkpointModalTitle').innerText = 'Tambah Checkpoint';
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(checkpointModalEl).show();
  <?php endif; ?>
</script>

<?= $this->endSection() ?>
