<?php
/**
 * Topbar — port verbatim dari renderTopbar()/renderCrumbs() di Shell.js (sudah dihapus).
 *
 * Data: $crumbs ("Orangtua | Anak" dari controller), $username, $userName (dihitung main.php).
 */

// Port getInitials() Shell.js — definisi sama dengan sidebar.php, dipertahankan sekali via function_exists
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

$username  = ($username ?? '') !== '' ? $username : 'admin';
$userName  = ($userName ?? '') !== '' ? $userName : 'Administrator';
$initials  = tp_initials($userName);

// Port renderCrumbs(): split "|", trim, buang kosong, segmen terakhir = .current
$crumbParts = array_values(array_filter(array_map('trim', explode('|', $crumbs ?? '')), static fn ($p) => $p !== ''));
?>
<header class="d-topbar">
  <div class="crumbs">
    <button class="hamburger" data-drawer-open aria-label="Open navigation">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <?php foreach ($crumbParts as $i => $part): ?>
      <?php if ($i > 0): ?><svg class="sep" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg><?php endif; ?>
      <span<?= $i === count($crumbParts) - 1 ? ' class="current"' : '' ?>><?= esc($part) ?></span>
    <?php endforeach; ?>
  </div>
  <div class="topbar-actions">
    <button class="cmd" data-palette-open>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      <span>Search...</span>
      <kbd class="kbd">⌘K</kbd>
    </button>

    <button class="icon-btn" id="themeToggle" aria-label="Toggle theme"></button>

    <div class="dd-wrap">
      <div class="avatar" data-dropdown tabindex="0" role="button" aria-label="Account menu"><?= esc($initials) ?></div>
      <div class="dd-menu dd-profile" role="menu">
        <div class="dd-profile-head">
          <div class="dd-profile-name"><?= esc($username) ?></div>
        </div>
        <div class="dd-divider"></div>
        <a class="dd-menu-item danger" href="<?= esc(base_url('logout')) ?>">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
          Logout
        </a>
      </div>
    </div>
  </div>
</header>
