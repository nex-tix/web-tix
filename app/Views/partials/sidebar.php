<?php
/**
 * Sidebar utama — HTML statis (tanpa foreach/array). Ikon Bootstrap Icons
 * (<i class="bi bi-…">) — daftar item = sumber menu, dibaca juga oleh
 * palette (⌘K) dari DOM .d-sidebar.
 *
 * Section (nav-label) bisa dilipat: default tertutup, section yang mengandung
 * halaman aktif di-set is-open di server; state lanjutan disimpan di
 * localStorage oleh initNavSections() (init.js). Dashboard adalah item lepas
 * di luar section — selalu terlihat.
 *
 * Data: $activeKey (dari controller), $userName, $userRole (dihitung layouts/main.php).
 */

// Port fungsi getInitials() Shell.js — dipakai juga topbar.php
if (!function_exists('tp_initials')) {
    function tp_initials($name)
    {
        if (!is_string($name)) {
            return 'U';
        }
        $trimmed = trim($name);
        if ($trimmed === '') {
            return 'U';
        }
        $words = preg_split('/\s+/', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 2));
        }

        return strtoupper($words[0][0] . $words[1][0]);
    }
}

$activeKey = $activeKey ?? '';
$userName   = ($userName ?? '') !== '' ? $userName : 'Administrator';
$userRole   = ($userRole ?? '') !== '' ? $userRole : 'admin';
$initials   = tp_initials($userName);
?>
<aside class="d-sidebar">
  <div class="brand">
    <div class="brand-logo">
      <img src="<?= esc(base_url('img/logo_nextix.png')) ?>" alt="Nextix">
    </div>
    <div class="brand-text">
      <div class="brand-name">NEXTIX</div>
      <div class="brand-tag">Loket & Gate</div>
    </div>
  </div>
  <!-- Dashboard: item lepas di luar section — selalu terlihat, tak ikut collapse -->
  <nav class="nav-section">
    <a class="nav-link<?= $activeKey === 'dashboard' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/dashboard')) ?>">
      <i class="bi bi-grid-1x2" aria-hidden="true"></i>
      <span>Dashboard</span>
    </a>
  </nav>
  <nav class="nav-section<?= in_array($activeKey, ['guide', 'laporan-global', 'laporan-pengunjung', 'laporan-guide', 'laporan-keuangan'], true) ? ' is-open' : '' ?>" data-nav-section>
    <a class="nav-label" href="javascript:void(0)" data-section-toggle>OPERASIONAL<i class="bi bi-chevron-right chev" aria-hidden="true"></i></a>
    <div class="nav-section-body">
      <a class="nav-link<?= $activeKey === 'guide' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/guides')) ?>">
        <i class="bi bi-person-video2" aria-hidden="true"></i>
        <span>Guides</span>
      </a>
      <?php $grupAktif = in_array($activeKey, ['laporan-global', 'laporan-pengunjung', 'laporan-guide', 'laporan-keuangan'], true); // grup: is-open + trigger aktif bila salah satu anak aktif ?>
      <div class="nav-item-group<?= $grupAktif ? ' is-open' : '' ?>" data-nav-group>
        <a class="nav-link<?= $grupAktif ? ' is-active' : '' ?>" href="javascript:void(0)" data-nav-toggle>
          <i class="bi bi-bar-chart-line" aria-hidden="true"></i>
          <span>Laporan</span>
          <i class="bi bi-chevron-right chev" aria-hidden="true"></i>
        </a>
        <div class="nav-submenu">
          <a<?= $activeKey === 'laporan-global' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan')) ?>">Laporan Global</a>
          <a<?= $activeKey === 'laporan-pengunjung' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan/pengunjung')) ?>">Laporan Data Pengunjung</a>
          <a<?= $activeKey === 'laporan-guide' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan/guide')) ?>">Laporan Pembayaran Guide</a>
          <a<?= $activeKey === 'laporan-keuangan' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan/keuangan')) ?>">Laporan Keuangan</a>
        </div>
      </div>
    </div>
  </nav>
  <nav class="nav-section<?= in_array($activeKey, ['pos', 'riwayat', 'expense', 'gaji-guide'], true) ? ' is-open' : '' ?>" data-nav-section>
    <a class="nav-label" href="javascript:void(0)" data-section-toggle>AKUNTANSI<i class="bi bi-chevron-right chev" aria-hidden="true"></i></a>
    <div class="nav-section-body">
      <a class="nav-link<?= $activeKey === 'pos' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/pos')) ?>">
        <i class="bi bi-pc-display" aria-hidden="true"></i>
        <span>Pos Loket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'riwayat' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/transaction-history')) ?>">
        <i class="bi bi-cash-coin" aria-hidden="true"></i>
        <span>Penjualan</span>
      </a>
      <a class="nav-link<?= $activeKey === 'expense' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/expenses')) ?>">
        <i class="bi bi-safe2" aria-hidden="true"></i>
        <span>Pengeluaran</span>
      </a>
      <!-- Gaji Guide menunjuk ke page ledger sendiri: activeKey terpisah, highlight kembar dgn menu Guides teratasi -->
      <a class="nav-link<?= $activeKey === 'gaji-guide' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/gaji-guide')) ?>">
        <i class="bi bi-wallet" aria-hidden="true"></i>
        <span>Gaji Guide</span>
      </a>
    </div>
  </nav>
  <nav class="nav-section<?= in_array($activeKey, ['data-tiket', 'jenis-tiket', 'bank', 'checkpoint'], true) ? ' is-open' : '' ?>" data-nav-section>
    <a class="nav-label" href="javascript:void(0)" data-section-toggle>MASTER DATA<i class="bi bi-chevron-right chev" aria-hidden="true"></i></a>
    <div class="nav-section-body">
      <a class="nav-link<?= $activeKey === 'data-tiket' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/data-tiket')) ?>">
        <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
        <span>Data Tiket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'jenis-tiket' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/ticket-types')) ?>">
        <i class="bi bi-ticket-detailed" aria-hidden="true"></i>
        <span>Jenis Tiket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'bank' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/banks')) ?>">
        <i class="bi bi-bank" aria-hidden="true"></i>
        <span>Bank</span>
      </a>
      <a class="nav-link<?= $activeKey === 'checkpoint' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/checkpoints')) ?>">
        <i class="bi bi-check2-square" aria-hidden="true"></i>
        <span>Checkpoint</span>
      </a>
    </div>
  </nav>
  <nav class="nav-section<?= in_array($activeKey, ['admin', 'gate'], true) ? ' is-open' : '' ?>" data-nav-section>
    <a class="nav-label" href="javascript:void(0)" data-section-toggle>USERS<i class="bi bi-chevron-right chev" aria-hidden="true"></i></a>
    <div class="nav-section-body">
      <a class="nav-link<?= $activeKey === 'admin' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/users')) ?>">
        <i class="bi bi-people" aria-hidden="true"></i>
        <span>Admin</span>
      </a>
      <a class="nav-link<?= $activeKey === 'gate' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/gate-users')) ?>">
        <i class="bi bi-person-check" aria-hidden="true"></i>
        <span>Penjaga Gate</span>
      </a>
    </div>
  </nav>
  <nav class="nav-section<?= $activeKey === 'setting' ? ' is-open' : '' ?>" data-nav-section>
    <a class="nav-label" href="javascript:void(0)" data-section-toggle>SISTEM<i class="bi bi-chevron-right chev" aria-hidden="true"></i></a>
    <div class="nav-section-body">
      <a class="nav-link<?= $activeKey === 'setting' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/settings')) ?>">
        <i class="bi bi-gear" aria-hidden="true"></i>
        <span>Settings</span>
      </a>
    </div>
  </nav>
  <div class="sidebar-footer">
    <div class="workspace">
      <div class="workspace-avatar"><?= esc($initials) ?></div>
      <div class="workspace-text">
        <div class="workspace-name"><?= esc($userName) ?></div>
        <div class="workspace-role"><?= esc($userRole) ?></div>
      </div>
      <i class="bi bi-chevron-expand workspace-chev" aria-hidden="true"></i>
    </div>
  </div>
</aside>
