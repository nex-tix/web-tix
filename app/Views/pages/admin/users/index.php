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
    $headers = ['Nama', 'Username', 'Role', 'Status', 'Dibuat', 'Aksi'];
    $rows = [];
    foreach ($users as $u) {
      $rows[] = [
        '<div class="fw-semibold">' . esc($u['name']) . '</div>',
        '<code>' . esc($u['username']) . '</code>',
        '<span class="badge bg-' . ($u['role'] === 'admin' ? 'dark' : 'secondary') . '">' . ucfirst($u['role']) . '</span>',
        $u['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>',
        $u['created_at'],
        '<button type="button" class="btn btn-sm btn-outline-warning me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#userModal" '
          . 'data-id="' . $u['id'] . '" data-name="' . esc($u['name']) . '" data-username="' . esc($u['username']) . '" '
          . 'data-role="' . $u['role'] . '" data-active="' . $u['is_active'] . '"><i style="color:#f1c40f;" class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/users/' . $u['id'] . '/delete') . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus user ini?\')">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
      'title'          => 'Kelola Pengguna',
      'subtitle'       => 'Daftar akun administrator sistem web POS dan loket.',
      'headers'        => $headers,
      'rows'           => $rows,
      'create_modal_id' => 'userModal',
      'create_text'    => 'Tambah Pengguna',
      'empty_message'  => 'Belum ada data pengguna.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="userForm" method="POST" action="<?= base_url('admin/users') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="userModalTitle">Tambah Pengguna</h5>
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
          <input type="hidden" id="user_id" name="id" value="">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="user_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="user_name" name="name" value="<?= old('name') ?>" placeholder="Nama lengkap" required maxlength="100">
            </div>
            <div class="col-md-6">
              <label for="user_username" class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="user_username" name="username" value="<?= old('username') ?>" placeholder="Username" required minlength="3" maxlength="50" pattern="[a-zA-Z0-9]+">
              <div class="form-text">Hanya huruf dan angka (3-50 karakter).</div>
            </div>
            <div class="col-md-6">
              <label for="user_password" class="form-label">Password <span id="pwReq">*</span></label>
              <input type="password" class="form-control" id="user_password" name="password" placeholder="Minimal 8 karakter" minlength="8" autocomplete="new-password">
              <div class="form-text">Kosongkan jika tidak ubah password.</div>
            </div>
            <div class="col-md-6">
              <label for="user_role" class="form-label">Role <span class="text-danger">*</span></label>
              <select class="form-select" id="user_role" name="role" required>
                <option value="admin">Admin</option>
              </select>
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="user_is_active" name="is_active" value="1" <?= old('is_active', 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="user_is_active">Aktif</label>
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
      const form = document.getElementById('userForm');
      form.action = '<?= base_url('admin/users/') ?>' + this.dataset.id;
      document.getElementById('userModalTitle').innerText = 'Edit Pengguna';
      document.getElementById('user_id').value = this.dataset.id;
      document.getElementById('user_name').value = this.dataset.name;
      document.getElementById('user_username').value = this.dataset.username;
      document.getElementById('user_role').value = this.dataset.role;
      document.getElementById('user_is_active').checked = this.dataset.active == '1';
      document.getElementById('user_password').value = '';
      document.getElementById('pwReq').innerHTML = '';
    });
  });

  const userModalEl = document.getElementById('userModal');
  userModalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('userForm');
    form.action = '<?= base_url('admin/users') ?>';
    form.reset();
    document.getElementById('userModalTitle').innerText = 'Tambah Pengguna';
    document.getElementById('user_id').value = '';
    document.getElementById('pwReq').innerHTML = '*';
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(userModalEl).show();
  <?php endif; ?>
</script>

<?= $this->endSection() ?>