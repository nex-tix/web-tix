  <?= $this->extend('layouts/main') ?>
  <?= $this->section('content') ?>
  <div class="app-content">
    <div class="container-fluid px-0">
      <div class="app-header-custom">
        <div class="top-bar-left">
          <i class="bi bi-circle-fill dot-green text-success"></i>
          <span style="color: #2c3136">
            Terminal Loket 1 &middot; Shift siang &middot; online
          </span>
        </div>
      </div>
      <!-- ================================================= PAGE HEADER ================================================== -->
      <div class="app-content-header">
        <div class="page-title">
          <h4 style="color: #2c3136">Papan hari ini</h4>
          <p style="color: #2c3136">
            Ringkasan penjualan dan kunjungan seluruh gate pada hari
            dagang berjalan.
          </p>
        </div>
        <div class="d-flex gap-2">
          <button class="btn-laporan">
            <i class="bi bi-bar-chart-line"></i> Laporan
          </button>
          <button class="btn-transaksi">
            <i class="bi bi-plus-lg"></i> <span>Transaksi baru</span>
          </button>
        </div>
      </div>
      <!-- ========================================= STAT CARDS ========================================== -->
            <div class="row g-3 mb-3">
              <div class="col-md-3">
                <?= view('components/stat-card', [
                    'label'     => 'Penjualan Hari Ini',
                    'value'     => 'Rp 8.209.000',
                    'sub'       => '+24% dari kemarin (Rp 6.600.000)',
                    'sub_class' => 'green',
                    'bg'        => '#fdf8e8'
                ]) ?>
              </div>
              <div class="col-md-3">
                <?= view('components/stat-card', [
                    'label' => 'Tiket Terbit',
                    'value' => '43 <small>tiket</small>',
                    'sub'   => '35 individual &middot; 8 rombongan'
                ]) ?>
              </div>
              <div class="col-md-3">
                <?= view('components/stat-card', [
                    'label' => 'Pengunjung Sudah Masuk',
                    'value' => '41 <small>orang</small>',
                    'sub'   => '44% dari 94 pengunjung terjual'
                ]) ?>
              </div>
              <div class="col-md-3">
                <?= view('components/stat-card', [
                    'label'     => 'Belum Dipakai',
                    'value'     => '18 <small>tiket</small>',
                    'sub'       => '53 pengunjung &middot; hangus 24.00 WITA',
                    'dot_class' => 'dot-yellow'
                ]) ?>
              </div>
            </div>
      <!-- ========================================= PAYMENT PROGRESS ========================================== -->
      <div class="card border-0 shadow-sm mb-3 bg-white">
        <div class="card-body p-3">
          <div class="custom-progress">
            <div class="bar-dark" style="width: 66.6%"></div>
            <div class="bar-red" style="width: 33.4%"></div>
          </div>
          <div class="progress-legend">
            <div class="d-flex gap-3 flex-wrap">
              <div class="legend-item">
                <span
                  class="legend-color">
                </span>
                Tunai Rp 5.465.000 (14 nota)
              </div>
              <div class="legend-item">
                <span
                  class="legend-color"
                  style="background-color: #e74c3c">
                </span>
                Debit Rp 2.744.000 (6 nota)
              </div>
            </div>
            <div class="fw-semibold">Total diterima Rp 8.209.000</div>
          </div>
        </div>
      </div>
      <!-- ========================================= CHART + CASH ========================================== -->
          <div class="row g-3 mb-3">
            <!-- Chart -->
            <div class="col-lg-8">
              <?= view('components/chart-box', [
                  'title'        => 'Penjualan tujuh hari',
                  'subtitle'     => 'Total dibayar per hari dagang (WITA), termasuk void',
                  'badge_text'   => 'hari ini',
                  'chart_id'     => 'salesChart',
                  'height'       => 250,
                  'footer_stats' => [
                      ['label' => 'Rata-rata tujuh hari', 'value' => 'Rp 6.067.000'],
                      ['label' => 'Tertinggi', 'value' => 'Rp 8.209.000'],
                      ['label' => 'Hari ini', 'value' => 'Rp 8.209.000']
                  ]
              ]) ?>
            </div>

            <!-- Cash -->
            <div class="col-lg-4">
              <?= view('components/cash-recon', [
                  'title'        => 'Cocokkan kas & EDC',
                  'subtitle'     => 'Yang harus sama saat tutup loket',
                  'cash_rate'    => 66.6,
                  'cash_amount'  => 'Rp 5.465.000',
                  'edc_rate'     => 33.4,
                  'edc_amount'   => 'Rp 2.744.000',
                  'total_notes'  => '20 nota',
                  'discount'     => '-Rp 271.000',
                  'void_info'    => '1 nota &middot; Rp 200.000',
                  'info_text'    => 'Angka tunai dicocokkan dengan isi laci, angka EDC dengan slip mesin. Void bukan refund.'
              ]) ?>
            </div>
          </div>
      <!-- ========================================= TABLE ========================================== -->
          <?= view('components/transaction-table', [
              'title'    => 'Transaksi terbaru',
              'subtitle' => 'Lima nota terakhir di loket ini',
              'link_url' => '#',
              'link_text'=> 'Semua riwayat',
              'table_id' => 'transactionTable',
              'headers'  => ['WAKTU', 'NOTA', 'PENGUNJUNG', 'JENIS', 'BAYAR', 'TOTAL', 'STATUS TIKET', 'AKSI'],
              'rows'     => [
                  [
                      '08:41',
                      'INV-20261006-DBBP3U',
                      '<div class="fw-semibold">Lukman Hakim</div><div class="visitor-meta">6 pengunjung &middot; Denpasar</div>',
                      'Rombongan',
                      'Tunai',
                      'Rp 494.000',
                      '<span class="badge-status-used"> Used </span>',
                      '<i class="bi bi-printer action-icon"></i> <i class="bi bi-three-dots-vertical action-icon"></i>'
                  ],
                  [
                      '08:35',
                      'INV-20261006-A1B2C3',
                      '<div class="fw-semibold">Sarah Whitfield</div><div class="visitor-meta">2 pengunjung &middot; Australia</div>',
                      'Individual',
                      'Debit',
                      'Rp 100.000',
                      '<span class="badge-status-used"> Used </span>',
                      '<i class="bi bi-printer action-icon"></i> <i class="bi bi-three-dots-vertical action-icon"></i>'
                  ],
                  [
                      '08:28',
                      'INV-20261006-X9Y8Z7',
                      '<div class="fw-semibold">Budi Santoso</div><div class="visitor-meta">4 pengunjung &middot; Surabaya</div>',
                      'Individual',
                      'Tunai',
                      'Rp 200.000',
                      '<span class="badge-status-unused"> Active </span>',
                      '<i class="bi bi-printer action-icon"></i> <i class="bi bi-three-dots-vertical action-icon"></i>'
                  ],
                  [
                      '08:15',
                      'INV-20261006-QWERTY',
                      '<div class="fw-semibold">Chen Wei</div><div class="visitor-meta">1 pengunjung &middot; China</div>',
                      'Individual',
                      'Debit',
                      'Rp 50.000',
                      '<span class="badge-status-used"> Used </span>',
                      '<i class="bi bi-printer action-icon"></i> <i class="bi bi-three-dots-vertical action-icon"></i>'
                  ],
                  [
                      '08:02',
                      'INV-20261006-POIUYT',
                      '<div class="fw-semibold">Dewi Lestari</div><div class="visitor-meta">3 pengunjung &middot; Bandung</div>',
                      'Rombongan',
                      'Tunai',
                      'Rp 150.000',
                      '<span class="badge-status-used"> Used </span>',
                      '<i class="bi bi-printer action-icon"></i> <i class="bi bi-three-dots-vertical action-icon"></i>'
                  ]
              ]
          ]) ?>
    </div>
  </div>
  <?= $this->endSection() ?>