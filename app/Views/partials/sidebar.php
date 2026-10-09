    <aside class="app-sidebar" data-bs-theme="dark">
      <!-- Brand -->
      <div class="brand-link">
        <div class="brand-icon">
          <img src="<?= base_url('img/logo_nextix.png') ?>" alt="Logo" width="100px" height="auto">
        </div>

        <div class="brand-text">
          <h6>Kecak Uluwatu</h6>
          <span>Loket & Gate</span>
        </div>
      </div>

      <!-- Sidebar Wrapper -->
      <div class="sidebar-wrapper">
        <!-- Menu -->
        <nav>
          <div class="nav-section">Operasional</div>

          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item">
              <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= strpos(current_url(), 'admin/dashboard') !== false ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= base_url('admin/pos') ?>" class="nav-link <?= current_url(true)->getPath() === '/admin/pos' ? 'active' : '' ?>">
                <i class="bi bi-pc-display"></i>
                <p>POS Loket</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= base_url('admin/pos/reprint') ?>" class="nav-link <?= strpos(current_url(), 'admin/pos/reprint') !== false ? 'active' : '' ?>">
                <i class="bi bi-printer"></i>
                <p>Cetak Ulang Tiket</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= base_url('admin/transaction-history') ?>" class="nav-link <?= strpos(current_url(), 'admin/transaction-history') !== false ? 'active' : '' ?>">
                <i class="bi bi-clock-history"></i>
                <p>Riwayat Penjualan</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= base_url('admin/laporan') ?>" class="nav-link <?= strpos(current_url(), 'admin/laporan') !== false ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-line"></i>
                <p>Laporan</p>
              </a>
            </li>
          </ul>

          <div class="nav-section">Master Data</div>

          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item">
              <a href="<?= base_url('admin/ticket-types') ?>" class="nav-link <?= strpos(current_url(), 'admin/ticket-types') !== false ? 'active' : '' ?>">
                <i class="bi bi-ticket-perforated"></i>
                <p>Jenis Tiket</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= base_url('admin/users') ?>" class="nav-link <?= strpos(current_url(), 'admin/users') !== false ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <p>Pengguna</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= base_url('admin/checkpoints') ?>" class="nav-link <?= strpos(current_url(), 'admin/checkpoints') !== false ? 'active' : '' ?>">
                <i class="bi bi-check2-square"></i>
                <p>Checkpoint</p>
              </a>
            </li>
          </ul>

          <div class="nav-section">Sistem</div>

          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item">
              <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= strpos(current_url(), 'admin/settings') !== false ? 'active' : '' ?>">
                <i class="bi bi-gear"></i>
                <p>Pengaturan</p>
              </a>
            </li>
          </ul>
        </nav>

        <!-- User Footer -->
        <div class="sidebar-footer">
          <div class="avatar">AL</div>

          <div class="user-info">
            <h6>Admin Loket 1</h6>
            <span>Terminal loket</span>
          </div>

          <a href="<?= base_url('/logout') ?>">
            <i class="bi bi-box-arrow-right logout-icon"></i>
          </a>
        </div>
      </div>
    </aside>