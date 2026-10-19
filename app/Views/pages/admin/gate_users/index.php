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
    $headers = ['Nama', 'Username', 'Nomor Hp', 'Status', 'Dibuat', 'Aksi'];
    $rows = [];
    foreach ($gateUsers as $u) {
      $rows[] = [
        '<div class="fw-semibold">' . esc($u['name']) . '</div>',
        '<code>' . esc($u['username']) . '</code>',
        !empty($u['phone']) ? esc($u['phone']) : '<span class="text-muted">-</span>',
        $u['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>',
        $u['created_at'],
        '<button type="button" class="btn btn-sm btn-outline-warning me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#gateUserModal" '
          . 'data-id="' . $u['id'] . '" data-name="' . esc($u['name']) . '" data-username="' . esc($u['username']) . '" '
          . 'data-phone="' . esc($u['phone'] ?? '') . '" data-active="' . $u['is_active'] . '"><i style="color:#f1c40f;" class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/gate-users/' . $u['id'] . '/delete') . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus user ini?\')">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
      'title'          => 'Kelola Pengguna Loket',
      'subtitle'       => 'Daftar akun petugas loket penjualan tiket.',
      'headers'        => $headers,
      'rows'           => $rows,
      'create_modal_id' => 'gateUserModal',
      'create_text'    => 'Tambah Pengguna Loket',
      'empty_message'  => 'Belum ada data pengguna loket.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="gateUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="gateUserForm" method="POST" action="<?= base_url('admin/gate-users') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="gateUserModalTitle">Tambah Pengguna Loket</h5>
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
          <input type="hidden" id="gateUser_id" name="id" value="">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="gateUser_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="gateUser_name" name="name" value="<?= old('name') ?>" placeholder="Nama lengkap" required maxlength="100">
            </div>
            <div class="col-md-6">
              <label for="gateUser_username" class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="gateUser_username" name="username" value="<?= old('username') ?>" placeholder="Username" required minlength="3" maxlength="50" pattern="[a-zA-Z0-9]+">
              <div class="form-text">Hanya huruf dan angka (3-50 karakter).</div>
            </div>
            <div class="col-md-6">
              <label for="gateUser_password" class="form-label">Password <span id="pwReq">*</span></label>
              <input type="password" class="form-control" id="gateUser_password" name="password" placeholder="Minimal 8 karakter" required minlength="8" autocomplete="new-password">
              <div class="form-text">Kosongkan jika tidak ubah password.</div>
            </div>
            <div class="col-md-6">
              <label for="gateUser_phone" class="form-label">Nomor Hp</label>
              <input type="text" class="form-control" id="gateUser_phone" name="phone" value="<?= old('phone') ?>" placeholder="08123456789" maxlength="25">
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="gateUser_is_active" name="is_active" value="1" <?= old('is_active', 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="gateUser_is_active">Aktif</label>
              </div>
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
      const form = document.getElementById('gateUserForm');
      form.action = '<?= base_url('admin/gate-users/') ?>' + this.dataset.id;
      document.getElementById('gateUserModalTitle').innerText = 'Edit Pengguna Loket';
      document.getElementById('gateUser_id').value = this.dataset.id;
      document.getElementById('gateUser_name').value = this.dataset.name;
      document.getElementById('gateUser_username').value = this.dataset.username;
      document.getElementById('gateUser_phone').value = this.dataset.phone;
      document.getElementById('gateUser_is_active').checked = this.dataset.active == '1';
      document.getElementById('gateUser_password').value = '';
      document.getElementById('gateUser_password').removeAttribute('required');
      document.getElementById('pwReq').innerHTML = '';
    });
  });

  const gateUserModalEl = document.getElementById('gateUserModal');
  gateUserModalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('gateUserForm');
    form.action = '<?= base_url('admin/gate-users') ?>';
    form.reset();
    document.getElementById('gateUserModalTitle').innerText = 'Tambah Pengguna Loket';
    document.getElementById('gateUser_id').value = '';
    document.getElementById('gateUser_password').setAttribute('required', '');
    document.getElementById('pwReq').innerHTML = '*';
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(gateUserModalEl).show();
  <?php endif; ?>
</script>

<?= $this->endSection() ?>
