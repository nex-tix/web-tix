<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="app-content">
  <div class="container-fluid px-0">
    <!-- Header Halaman Responsif -->
    <div class="app-content-header mb-3">
      <div class="row align-items-center g-2">
        <div class="col-12 col-sm">
          <h4 class="text-slate-900 mb-1">Pengeluaran Kas &amp; Operasional</h4>
          <p class="text-slate-500 mb-0">Pencatatan dan rekapitulasi beban operasional, gaji guide, dan kas keluar.</p>
        </div>
        <div class="col-12 col-sm-auto text-sm-end">
          <button type="button" class="btn btn-sm btn-catat d-flex d-sm-inline-flex align-items-center justify-content-center gap-2 shadow-sm px-3 py-2 text-nowrap" data-bs-toggle="modal" data-bs-target="#expenseModal">
            <i class="bi bi-plus-circle"></i>
            <span>Catat Pengeluaran</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Filter & Export Card Responsif dengan Pencarian -->
    <div class="filter-export table-card mb-3 p-3">
      <form method="get" action="<?= base_url('admin/expenses') ?>" id="form-filter-expense">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <label class="form-label filter-label">Tanggal Awal</label>
            <input type="date" name="start" class="form-control form-control-sm" value="<?= esc($startDate ?? '') ?>">
          </div>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <label class="form-label filter-label">Tanggal Akhir</label>
            <input type="date" name="end" class="form-control form-control-sm" value="<?= esc($endDate ?? '') ?>">
          </div>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <label class="form-label filter-label">Kategori</label>
            <select name="category_id" class="form-select form-select-sm bg-white">
              <option value="">Semua Kategori</option>
              <?php foreach (($categories ?? []) as $cat): ?>
                <option value="<?= esc($cat['id']) ?>" <?= ((string)($filters['category_id'] ?? '') === (string)$cat['id']) ? 'selected' : '' ?>>
                  <?= esc($cat['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <label class="form-label filter-label">Metode Bayar</label>
            <select name="payment_method" class="form-select form-select-sm bg-white">
              <option value="">Semua Metode</option>
              <option value="cash" <?= ($filters['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Tunai (Cash)</option>
              <option value="transfer" <?= ($filters['payment_method'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
            </select>
          </div>
          <div class="col-12 col-sm-8 col-md-8 col-lg-2">
            <label class="form-label filter-label">Cari Pengeluaran</label>
            <div class="input-group input-group-sm">
              <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
              <input type="search" name="q" id="search-expense" class="form-control" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Kode / uraian...">
            </div>
          </div>
          <div class="col-12 col-sm-4 col-md-4 col-lg-2 d-flex gap-1 justify-content-end">
            <button type="submit" class="btn btn-sm btn-dark flex-grow-1 flex-lg-grow-0 px-3" title="Terapkan Filter">
              <i class="bi bi-filter"></i> Filter
            </button>
            <a href="<?= base_url('admin/expenses') ?>" class="btn btn-sm btn-outline-secondary" title="Reset filter">
              <i class="bi bi-arrow-counterclockwise"></i>
            </a>
            <?php
            $exportQuery = http_build_query(array_filter([
              'start'          => !empty($startDate) ? date('d-m-Y', strtotime($startDate)) : '',
              'end'            => !empty($endDate) ? date('d-m-Y', strtotime($endDate)) : '',
              'category_id'    => $filters['category_id'] ?? '',
              'payment_method' => $filters['payment_method'] ?? '',
              'q'              => $filters['q'] ?? '',
            ]));
            ?>
            <a href="<?= base_url('admin/expenses/export-pdf?' . $exportQuery) ?>" class="btn btn-sm btn-transaksi" title="Cetak PDF">
              <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
          </div>
        </div>
      </form>
    </div>

    <!-- Stat Cards KPI Responsif -->
    <div class="row g-2 g-md-3 mb-3">
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Total Pengeluaran</h2>
          </div>
          <div class="stat-value">
            <h4 class="text-danger text-truncate" title="Rp <?= number_format($kpi['total_pengeluaran'], 0, ',', '.') ?>">
              Rp <?= number_format($kpi['total_pengeluaran'], 0, ',', '.') ?>
            </h4>
          </div>
          <div class="stat-sub">
            <h4>Dari <?= (int) $kpi['total_transaksi'] ?> catatan</h4>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Kas Keluar Tunai</h2>
          </div>
          <div class="stat-value">
            <h4 class="text-truncate" title="Rp <?= number_format($kpi['total_cash'], 0, ',', '.') ?>">
              Rp <?= number_format($kpi['total_cash'], 0, ',', '.') ?>
            </h4>
          </div>
          <div class="stat-sub">
            <h4>Kas tunai loket</h4>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Transfer Bank</h2>
          </div>
          <div class="stat-value">
            <h4 class="text-truncate" title="Rp <?= number_format($kpi['total_transfer'], 0, ',', '.') ?>">
              Rp <?= number_format($kpi['total_transfer'], 0, ',', '.') ?>
            </h4>
          </div>
          <div class="stat-sub">
            <h4>Transfer rekening</h4>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="stat-card">
          <div class="stat-label">
            <h2>Gaji / Komisi Guide</h2>
          </div>
          <div class="stat-value">
            <h4 class="text-truncate" title="Rp <?= number_format($kpi['total_guide'], 0, ',', '.') ?>">
              Rp <?= number_format($kpi['total_guide'], 0, ',', '.') ?>
            </h4>
          </div>
          <div class="stat-sub">
            <h4>Biaya komisi trip</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card Responsif -->
    <div class="table-card p-3 mb-3">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
          <h5 class="sub-text mb-0">Rincian Pengeluaran Kas</h5>
          <?php if (!empty($startDate) && !empty($endDate)): ?>
            <span class="small">Periode: <?= date('d/m/Y', strtotime($startDate)) ?> s/d <?= date('d/m/Y', strtotime($endDate)) ?></span>
          <?php elseif (!empty($startDate)): ?>
            <span class="small">Periode: Mulai <?= date('d/m/Y', strtotime($startDate)) ?></span>
          <?php elseif (!empty($endDate)): ?>
            <span class="small">Periode: Sampai <?= date('d/m/Y', strtotime($endDate)) ?></span>
          <?php else: ?>
            <span class="small">Periode: Semua Periode</span>
          <?php endif; ?>
        </div>
        <div class="small" id="table-record-count">
          Menampilkan <?= count($expenses) ?> catatan
        </div>
      </div>

      <div class="table-responsive" style="min-width: 100%; -webkit-overflow-scrolling: touch;">
        <table class="table table-hover align-middle mb-0 table-light" id="table-expenses" style="min-width: 780px;">
          <thead class="table-light">
            <tr>
              <th style="width: 5%; text-align: center;">No</th>
              <th style="width: 11%;">Tanggal</th>
              <th style="width: 14%;">Kode</th>
              <th style="width: 15%;">Kategori</th>
              <th style="width: 25%;">Keterangan</th>
              <th style="width: 13%;">Metode</th>
              <th style="width: 11%; text-align: right;">Nominal (Rp)</th>
              <th style="width: 6%; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody id="expense-tbody">
            <?php if (!empty($expenses)): ?>
              <?php foreach ($expenses as $idx => $exp): ?>
                <tr class="expense-row">
                  <td class="text-center"><?= $idx + 1 ?></td>
                  <td class="text-nowrap"><?= date('d/m/Y', strtotime($exp['expense_date'])) ?></td>
                  <td>
                    <span class="badge bg-white text-dark font-monospace border text-nowrap"><?= esc($exp['expense_code']) ?></span>
                  </td>
                  <td>
                    <span class="fw-semibold text-primary"><?= esc($exp['category_name'] ?? 'Umum') ?></span>
                  </td>
                  <td style="max-width: 250px; word-break: break-word;">
                    <div class="fw-medium"><?= esc($exp['title']) ?></div>
                    <?php if (!empty($exp['notes'])): ?>
                      <small class="text-muted d-block"><i class="bi bi-info-circle me-1"></i><?= esc($exp['notes']) ?></small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if (($exp['payment_method'] ?? '') === 'transfer'): ?>
                      <span class="badge bg-info text-dark text-nowrap">
                        <i class="bi bi-bank me-1"></i>Transfer <?= esc($exp['bank_code'] ?: $exp['bank_name'] ?: 'Bank') ?>
                      </span>
                    <?php else: ?>
                      <span class="badge bg-success text-white text-nowrap">
                        <i class="bi bi-cash me-1"></i>Tunai (Cash)
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end fw-bold text-danger text-nowrap">
                    Rp <?= number_format((float)$exp['amount'], 0, ',', '.') ?>
                  </td>
                  <td class="text-center">
                    <form action="<?= base_url('admin/expenses/' . $exp['id'] . '/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus catatan pengeluaran <?= esc($exp['expense_code']) ?> ini?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus Pengeluaran">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- ==========================================================================
     MODAL CATAT PENGELUARAN BARU (RESPONSIF)
     ========================================================================== -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form id="formExpense" method="POST" action="<?= base_url('admin/expenses/store') ?>" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="expenseModalTitle">Catat Pengeluaran Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if (session()->get('errors')): ?>
            <div class="alert alert-danger mb-3">
              <ul class="mb-0">
                <?php foreach (session()->get('errors') as $err): ?>
                  <li><?= esc($err) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="row g-3">
            <!-- Tanggal Pengeluaran -->
            <div class="col-12 col-md-4">
              <label for="expense_date" class="form-label">Tanggal Pengeluaran <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?= old('expense_date', date('Y-m-d')) ?>" required>
            </div>

            <!-- Kategori Pengeluaran -->
            <div class="col-12 col-md-8">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="category_id" class="form-label mb-0">Kategori Biaya <span class="text-danger">*</span></label>
                <button type="button" id="btnToggleNewCategory" class="btn btn-sm btn-link text-decoration-none py-0 px-1 text-primary">
                  <i class="bi bi-plus-circle"></i> + Kategori Baru
                </button>
              </div>

              <!-- Inline Quick Add Kategori Responsif -->
              <div id="quickAddCategoryBox" class="p-2 mb-2 border rounded bg-white d-none">
                <div class="d-flex flex-column flex-sm-row gap-2">
                  <input type="text" id="newCategoryName" class="form-control form-control-sm" placeholder="Nama Kategori Baru (misal: Biaya Transportasi)">
                  <div class="d-flex gap-1 justify-content-end">
                    <button type="button" id="btnSaveCategory" class="btn btn-sm btn-primary text-nowrap">Simpan</button>
                    <button type="button" id="btnCancelCategory" class="btn btn-sm btn-outline-secondary text-nowrap">Batal</button>
                  </div>
                </div>
                <div id="categoryFeedback" class="small mt-1 text-danger d-none"></div>
              </div>

              <select class="form-select" id="category_id" name="category_id" required>
                <option value="">-- Pilih Kategori Pengeluaran --</option>
                <?php foreach (($categories ?? []) as $cat): ?>
                  <option value="<?= esc($cat['id']) ?>" <?= (old('category_id') == $cat['id']) ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Keterangan / Deskripsi -->
            <div class="col-12">
              <label for="title" class="form-label">Keterangan / Uraian Biaya <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" placeholder="Contoh: Pembelian 10 Roll Kertas Struk Thermal Kasir" required>
            </div>

            <!-- Nominal Pengeluaran -->
            <div class="col-12 col-md-6">
              <label for="amountInput" class="form-label">Nominal Pengeluaran <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-white fw-bold">Rp</span>
                <input type="text" class="form-control fw-bold" id="amountInput" placeholder="0" value="<?= old('amount') ?>" required autocomplete="off">
                <input type="hidden" name="amount" id="amountHidden" value="<?= old('amount') ?>">
              </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="col-12 col-md-6">
              <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
              <div class="d-flex flex-wrap gap-3 gap-sm-4 pt-1">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="payment_method" id="pay_cash" value="cash" <?= (old('payment_method', 'cash') === 'cash') ? 'checked' : '' ?>>
                  <label class="form-check-label fw-semibold" for="pay_cash">
                    <i class="bi bi-cash text-success"></i> Tunai (Kas Laci)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="payment_method" id="pay_transfer" value="transfer" <?= (old('payment_method') === 'transfer') ? 'checked' : '' ?>>
                  <label class="form-check-label fw-semibold" for="pay_transfer">
                    <i class="bi bi-bank text-primary"></i> Transfer Bank
                  </label>
                </div>
              </div>
            </div>

            <!-- Pilihan Rekening Bank (Kondisional Transfer) -->
            <div class="col-12 col-md-6 <?= (old('payment_method') === 'transfer') ? '' : 'd-none' ?>" id="bankContainer">
              <label for="bank_id" class="form-label">Pilih Rekening Bank <span class="text-danger">*</span></label>
              <select class="form-select" id="bank_id" name="bank_id">
                <option value="">-- Pilih Bank Pengirim --</option>
                <?php foreach (($banks ?? []) as $b): ?>
                  <option value="<?= esc($b['id']) ?>" <?= (old('bank_id') == $b['id']) ? 'selected' : '' ?>>
                    <?= esc($b['name']) ?> (<?= esc($b['code']) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Catatan / No. Bukti -->
            <div class="col-12">
              <label for="notes" class="form-label">Catatan Tambahan / No. Bukti (Opsional)</label>
              <input type="text" class="form-control" id="notes" name="notes" value="<?= old('notes') ?>" placeholder="Contoh: No. Nota: INV-BCA-98172 atau Diterima oleh Pak Ketut">
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex flex-column flex-sm-row justify-content-end gap-2">
          <button type="button" class="btn btn-secondary w-100 w-sm-auto" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary w-100 w-sm-auto px-4"><i class="bi bi-check-circle me-1"></i> Simpan Pengeluaran</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    // Inisialisasi DataTable untuk tabel Pengeluaran (7 baris per halaman)
    const expenseTable = $('#table-expenses').DataTable({
      pageLength: 7,
      lengthChange: false,
      searching: true, // Diaktifkan agar pencarian real-time via input berfungsi
      ordering: false, // Pertahankan urutan dari server (DESC)
      info: true,
      paging: true,
      dom: 'rt<"row align-items-center mt-3 g-2"<"col-12 col-sm-6 text-center text-sm-start"i><"col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end"p>>',
      language: {
        emptyTable: '<div class="py-4 text-center text-muted"><i class="bi bi-inbox fs-2 d-block mb-1"></i>Belum ada catatan pengeluaran pada rentang filter ini.</div>',
        zeroRecords: '<div class="py-4 text-center text-muted"><i class="bi bi-search fs-3 d-block mb-1"></i>Tidak ada data pengeluaran yang cocok dengan pencarian.</div>',
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ catatan",
        infoEmpty: "Menampilkan 0 catatan",
        infoFiltered: "(disaring dari _MAX_ total catatan)",
        paginate: {
          previous: "<i class='bi bi-chevron-left'></i>",
          next: "<i class='bi bi-chevron-right'></i>"
        }
      },
      columnDefs: [
        { targets: [0, 7], orderable: false, searchable: false }
      ],
      drawCallback: function(settings) {
        const api = this.api();
        const pageInfo = api.page.info();
        // Beri nomor urut dinamis berlanjut sesuai halaman aktif
        api.column(0, { page: 'current' }).nodes().each(function(cell, i) {
          cell.innerHTML = pageInfo.start + i + 1;
        });

        // Sinkronisasi badge counter catatan di header card
        const countBadge = document.getElementById('table-record-count');
        if (countBadge) {
          countBadge.textContent = `Menampilkan ${pageInfo.recordsDisplay} catatan`;
        }
      }
    });

    // Hubungkan input Cari Pengeluaran dengan pencarian instan DataTables
    const searchInput = document.getElementById('search-expense');
    if (searchInput) {
      if (searchInput.value.trim() !== '') {
        expenseTable.search(searchInput.value.trim()).draw();
      }
      searchInput.addEventListener('input', function() {
        expenseTable.search(this.value.trim()).draw();
      });
    }

    // Format input nominal rupiah realtime
    const amountInput = document.getElementById('amountInput');
    const amountHidden = document.getElementById('amountHidden');

    function formatRupiah(value) {
      let clean = value.replace(/[^0-9]/g, '');
      if (!clean) return '';
      return new Intl.NumberFormat('id-ID').format(clean);
    }

    if (amountInput) {
      if (amountInput.value) {
        amountInput.value = formatRupiah(amountInput.value);
      }

      amountInput.addEventListener('input', function() {
        const raw = this.value.replace(/[^0-9]/g, '');
        amountHidden.value = raw;
        this.value = formatRupiah(raw);
      });
    }

    // Toggle pilihan bank jika transfer
    const payCash = document.getElementById('pay_cash');
    const payTransfer = document.getElementById('pay_transfer');
    const bankBox = document.getElementById('bankContainer');
    const bankSelect = document.getElementById('bank_id');

    function toggleBankBox() {
      if (payTransfer && payTransfer.checked) {
        bankBox.classList.remove('d-none');
        bankSelect.setAttribute('required', 'required');
      } else if (bankBox) {
        bankBox.classList.add('d-none');
        bankSelect.removeAttribute('required');
        bankSelect.value = '';
      }
    }

    if (payCash && payTransfer) {
      payCash.addEventListener('change', toggleBankBox);
      payTransfer.addEventListener('change', toggleBankBox);
      toggleBankBox();
    }

    // Quick Add Kategori Baru via AJAX
    const btnToggleNewCategory = document.getElementById('btnToggleNewCategory');
    const quickAddBox = document.getElementById('quickAddCategoryBox');
    const newCatInput = document.getElementById('newCategoryName');
    const btnSaveCategory = document.getElementById('btnSaveCategory');
    const btnCancelCategory = document.getElementById('btnCancelCategory');
    const categorySelect = document.getElementById('category_id');
    const categoryFeedback = document.getElementById('categoryFeedback');

    if (btnToggleNewCategory && quickAddBox) {
      btnToggleNewCategory.addEventListener('click', function() {
        quickAddBox.classList.toggle('d-none');
        if (!quickAddBox.classList.contains('d-none')) {
          newCatInput.focus();
        }
      });

      btnCancelCategory.addEventListener('click', function() {
        quickAddBox.classList.add('d-none');
        newCatInput.value = '';
        categoryFeedback.classList.add('d-none');
      });

      btnSaveCategory.addEventListener('click', function() {
        const catName = newCatInput.value.trim();
        if (!catName) {
          categoryFeedback.textContent = 'Nama kategori tidak boleh kosong.';
          categoryFeedback.classList.remove('d-none');
          return;
        }

        categoryFeedback.classList.add('d-none');
        btnSaveCategory.disabled = true;
        btnSaveCategory.textContent = 'Menyimpan...';

        const formData = new FormData();
        formData.append('name', catName);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/expenses/category/store') ?>', {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            btnSaveCategory.disabled = false;
            btnSaveCategory.textContent = 'Simpan';

            if (data.success && data.category) {
              // Tambahkan ke dropdown jika belum ada
              let existingOption = Array.from(categorySelect.options).find(opt => opt.value == data.category.id);
              if (!existingOption) {
                const opt = document.createElement('option');
                opt.value = data.category.id;
                opt.textContent = data.category.name;
                categorySelect.appendChild(opt);
                opt.selected = true;
              } else {
                existingOption.selected = true;
              }

              // Reset & tutup kotak inline
              newCatInput.value = '';
              quickAddBox.classList.add('d-none');
            } else {
              categoryFeedback.textContent = data.message || 'Gagal menyimpan kategori.';
              categoryFeedback.classList.remove('d-none');
            }
          })
          .catch(err => {
            btnSaveCategory.disabled = false;
            btnSaveCategory.textContent = 'Simpan';
            categoryFeedback.textContent = 'Terjadi kesalahan sistem.';
            categoryFeedback.classList.remove('d-none');
          });
      });
    }

    // Jika ada error validasi dari controller, buka kembali modal
    <?php if (session()->get('modal') === 'create'): ?>
      const expenseModal = new bootstrap.Modal(document.getElementById('expenseModal'));
      expenseModal.show();
    <?php endif; ?>
  });
</script>
<?= $this->endSection() ?>