<?php
/**
 * Sidebar utama — HTML statis (tanpa foreach/array). Struktur identik dengan
 * render lama Shell.js; hanya state aktif yang dihitung server.
 *
 * Data: $activeKey (dari controller), $userName, $userRole (dihitung layouts/main.php).
 * Sumber menu: file ini sendiri — palette (⌘K) membaca .d-sidebar dari DOM.
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
  <nav class="nav-section">
    <div class="nav-label">OPERASIONAL</div>
      <a class="nav-link<?= $activeKey === 'dashboard' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/dashboard')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grid-1x2" viewBox="0 0 16 16"><path d="M6 1H1v14h5zm9 0h-5v5h5zm0 9v5h-5v-5zM0 1a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm9 0a1 1 0 0 1 1-1h5a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-5a1 1 0 0 1-1-1zm1 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h5a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1z"/></svg>
        <span>Dashboard</span>
      </a>
      <a class="nav-link<?= $activeKey === 'pos' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/pos')) ?>">
        <svg viewBox="0 0 24 24"> xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pc-display" viewBox="0 0 16 16"> <path d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0M9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5M1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2z"/></svg>
        <span>Pos Loket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'reprint' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/pos/reprint')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16"> <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/> <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/></svg>
        <span>Cetak Ulang Tiket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'riwayat' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/transaction-history')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16"> <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z"/> <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z"/> <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5"/> </svg>
        <span>Riwayat Penjualan</span>
      </a>
      <?php $grupAktif = in_array($activeKey, ['laporan-global', 'laporan-pengunjung', 'laporan-guide', 'laporan-keuangan'], true); // grup: is-open + trigger aktif bila salah satu anak aktif ?>
      <div class="nav-item-group<?= $grupAktif ? ' is-open' : '' ?>" data-nav-group>
        <a class="nav-link<?= $grupAktif ? ' is-active' : '' ?>" href="javascript:void(0)" data-nav-toggle>
          <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16"><path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1zm1 12h2V2h-2zm-3 0V7H7v7zm-50v-3H2v3z"/></svg>
          <span>Laporan</span>
          <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m9 18 6-6-6-6"/></svg>
        </a>
        <div class="nav-submenu">
          <a<?= $activeKey === 'laporan-global' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan')) ?>">Laporan Global</a>
          <a<?= $activeKey === 'laporan-pengunjung' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan/pengunjung')) ?>">Laporan Data Pengunjung</a>
          <a<?= $activeKey === 'laporan-guide' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan/guide')) ?>">Laporan Pembayaran Guide</a>
          <a<?= $activeKey === 'laporan-keuangan' ? ' class="is-active"' : '' ?> href="<?= esc(base_url('admin/laporan/keuangan')) ?>">Laporan Keuangan</a>
        </div>
      </div>
      <a class="nav-link<?= $activeKey === 'data-tiket' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/data-tiket')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-perforated" viewBox="0 0 16 16"><path d="M4 4.85v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9z"/><path d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3zM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9z"/></svg>
        <span>Data Tiket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'jenis-tiket' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/ticket-types')) ?>">
        <svg viewBox="0 0 24 24"> xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ticket-detailed" viewBox="0 0 16 16"><path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5M5 7a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2z"/><path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zM1.5 4a.5.5 0 0 0-.5.5v1.05a2.5 2.5 0 0 1 0 4.9v1.05a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-1.05a2.5 2.5 0 0 1 0-4.9V4.5a.5.5 0 0 0-.5-.5z"/></svg>
        <span>Jenis Tiket</span>
      </a>
      <a class="nav-link<?= $activeKey === 'bank' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/banks')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bank" viewBox="0 0 16 16"><path d="m8 0 6.61 3h.89a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H15v7a.5.5 0 0 1 .485.38l.5 2a.498.498 0 0 1-.485.62H.5a.498.498 0 0 1-.485-.62l.5-2A.501.501 0 0 1 1 13V6H.5a.5.5 0 0 1-.5-.5v-2A.5.5 0 0 1 .5 3h.89L8 0ZM3.777 3h8.447L8 1 3.777 3ZM2 6v7h1V6H2Zm2 0v7h2.5V6H4Zm3.5 0v7h1V6h-1Zm2 0v7H12V6H9.5ZM13 6v7h1V6h-1Zm2-1V4H1v1h14Zm-.39 9H1.39l-.25 1h13.72l-.25-1Z"/></svg>
        <span>Bank</span>
      </a>
      <a class="nav-link<?= $activeKey === 'checkpoint' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/checkpoints')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-square" viewBox="0 0 16 16"><path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/><path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/></svg>
        <span>Checkpoint</span>
      </a>
  </nav>
  <nav class="nav-section">
    <div class="nav-label">USERS</div>
      <a class="nav-link<?= $activeKey === 'admin' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/users')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/></svg>
        <span>Admin</span>
      </a>
      <a class="nav-link<?= $activeKey === 'gate' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/gate-users')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16"><path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/><path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/></svg>
        <span>Penjaga Gate</span>
      </a>
      <a class="nav-link<?= $activeKey === 'guide' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/guides')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-video2" viewBox="0 0 16 16"><path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/><path d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zM1 3a1 1 0 0 1 1-1h2v2H1zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3zm-4-2h3v2H2a1 1 0 0 1-1-1zm3-1H1V8h3zm0-3H1V5h3z"/></svg>
        <span>Guides</span>
      </a>
  </nav>
  <nav class="nav-section">
    <div class="nav-label">SISTEM</div>
      <a class="nav-link<?= $activeKey === 'setting' ? ' is-active' : '' ?>" href="<?= esc(base_url('admin/settings')) ?>">
        <svg viewBox="0 0 24 24">xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16"><path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/><path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/></svg>
        <span>Settings</span>
      </a>
  </nav>
  <div class="sidebar-footer">
    <div class="workspace">
      <div class="workspace-avatar"><?= esc($initials) ?></div>
      <div class="workspace-text">
        <div class="workspace-name"><?= esc($userName) ?></div>
        <div class="workspace-role"><?= esc($userRole) ?></div>
      </div>
      <svg class="workspace-chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="m7 9 5-5 5 5"/><path d="m7 15 5 5 5-5"/>
      </svg>
    </div>
  </div>
</aside>
