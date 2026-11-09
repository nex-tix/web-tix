<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <?php
    $headers = ['Nama', 'Harga', 'Kategori', 'Status', 'Dibuat', 'Aksi'];
    $rows = [];
    foreach ($ticketTypes as $tt) {
      $rows[] = [
        '<div class="fw-semibold">' . esc($tt['name']) . '</div>',
        'Rp ' . number_format($tt['price'], 0, ',', '.'),
        '<span class="text-white badge bg-' . ($tt['ticket_category'] === 'individual' ? 'danger' : 'warning') . '">' . ucfirst($tt['ticket_category']) . '</span>',
        $tt['is_active'] ? '<span class="text-white badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>',
        $tt['created_at'],
        '<button type="button" class="btn btn-sm btn-outline-warning me-1 btn-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#ticketModal" '
          . 'data-id="' . $tt['id'] . '" data-name="' . esc($tt['name']) . '" data-price="' . $tt['price'] . '" '
          . 'data-category="' . $tt['ticket_category'] . '" data-discount="' . '" data-active="' . $tt['is_active'] . '"><i class="bi bi-pencil"></i></button>'
          . '<form action="' . base_url('admin/ticket-types/' . $tt['id'] . '/delete') . '" method="POST" class="d-inline" data-confirm-delete="true" data-delete-message="Yakin ingin menghapus jenis tiket ini?">'
          . csrf_field()
          . '<button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>'
          . '</form>',
      ];
    }
    ?>

    <?= view('components/data-table', [
      'title'       => 'Kelola Jenis Tiket',
      'subtitle'    => 'Daftar jenis tiket yang tersedia untuk dijual di loket.',
      'headers'     => $headers,
      'rows'        => $rows,
      'create_modal_id' => 'ticketModal',
      'create_text' => 'Tambah Jenis Tiket',
      'empty_message' => 'Belum ada data jenis tiket.',
    ]) ?>
  </div>
</div>

<div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="ticketForm" method="POST" action="<?= base_url('admin/ticket-types/store') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Jenis Tiket</h5>
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
            <div class="col-md-6">
              <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" placeholder="Contoh: Adult" required>
            </div>
            <div class="col-md-6">
              <label for="ticket_category" class="form-label">Kategori <span class="text-danger">*</span></label>
              <select class="form-select" id="ticket_category" name="ticket_category" required>
                <option value="individual">Individual</option>
                <option value="group">Group</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="price" name="price" value="<?= old('price') ?>" placeholder="0" step="0.01" required>
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
      const form = document.getElementById('ticketForm');
      form.action = '<?= base_url('admin/ticket-types/') ?>' + this.dataset.id + '/update';
      document.getElementById('modalTitle').innerText = 'Edit Jenis Tiket';
      document.getElementById('name').value = this.dataset.name;
      document.getElementById('price').value = this.dataset.price;
      document.getElementById('ticket_category').value = this.dataset.category;
      document.getElementById('is_active').checked = this.dataset.active == '1';
    });
  });

  const modalEl = document.getElementById('ticketModal');
  modalEl.addEventListener('hidden.bs.modal', function() {
    const form = document.getElementById('ticketForm');
    form.action = '<?= base_url('admin/ticket-types/store') ?>';
    form.reset();
    document.getElementById('modalTitle').innerText = 'Tambah Jenis Tiket';
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
      toastr.error('Silakan periksa kembali isian form jenis tiket.', 'Validasi Gagal', {
        timeOut: 2500,
        extendedTimeOut: 0
      });
    <?php endif; ?>
  });

  <?php if (session()->has('modal')): ?>
    new bootstrap.Modal(modalEl).show();
  <?php endif; ?>
</script>

<?= $this->endSection() ?>
