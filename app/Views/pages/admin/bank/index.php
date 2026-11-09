<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <?php
    $headers = ['Nama Bank', 'Kode', 'Status', 'Dibuat', 'Aksi'];
    $rows = [];
    foreach ($banks as $bank) {
      $rows[] = [
        '<div class="fw-semibold">' . esc($bank['name']) . '</div>',
        '<span class="fw-bold">' . esc($bank['code']) . '</span>',
        $bank['is_active'] ? '<span class="text-white badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>',
        $bank['created_at'],
        '<button type="button" class="btn btn-sm btn-outline-warning me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#bankModal" '
          . 'data-id="' . $bank['id'] . '" data-name="' . esc($bank['name']) . '" data-code="' . esc($bank['code']) . '" data-active="' . $bank['is_active'] . '"><i class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/banks/' . $bank['id'] . '/delete') . '" method="POST" class="d-inline" data-confirm-delete="true" data-delete-message="Yakin ingin menghapus bank ini?">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
      'title'       => 'Kelola Bank',
      'subtitle'    => 'Daftar bank yang tersedia untuk pembayaran.',
      'headers'     => $headers,
      'rows'        => $rows,
      'create_modal_id' => 'bankModal',
      'create_text' => 'Tambah Bank',
      'empty_message' => 'Belum ada data bank.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="bankModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="bankForm" method="POST" action="<?= base_url('admin/banks/store') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Bank</h5>
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
            <div class="col-md-7">
              <label for="name" class="form-label">Nama Bank <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" placeholder="Contoh: Bank Central Asia" required>
            </div>
            <div class="col-md-5">
              <label for="code" class="form-label">Kode <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="code" name="code" value="<?= old('code') ?>" placeholder="Contoh: BCA" required>
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', 1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Aktif</label>
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
      const form = document.getElementById('bankForm');
      form.action = '<?= base_url('admin/banks/') ?>' + this.dataset.id + '/update';
      document.getElementById('modalTitle').innerText = 'Edit Bank';
      document.getElementById('name').value = this.dataset.name;
      document.getElementById('code').value = this.dataset.code;
      document.getElementById('is_active').checked = this.dataset.active == '1';
    });
  });

  const modalEl = document.getElementById('bankModal');
  modalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('bankForm');
    form.action = '<?= base_url('admin/banks/store') ?>';
    form.reset();
    document.getElementById('modalTitle').innerText = 'Tambah Bank';
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
      toastr.error('Silakan periksa kembali isian form bank.', 'Validasi Gagal', {
        timeOut: 2500,
        extendedTimeOut: 0
      });
    <?php endif; ?>
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(modalEl).show();

    <?php if (session('modal') === 'edit' && session()->has('edit_id')): ?>
      const editForm = document.getElementById('bankForm');
      editForm.action = '<?= base_url('admin/banks/') ?>' + <?= (int) session('edit_id') ?> + '/update';
      document.getElementById('modalTitle').innerText = 'Edit Bank';
    <?php endif; ?>
  <?php endif; ?>
</script>

<?= $this->endSection() ?>