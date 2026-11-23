<!doctype html>
<html lang="id">

<head>
  <script>
    (function() {
      try {
        var saved = localStorage.getItem('dash26-theme') || 'light';
        document.documentElement.setAttribute('data-theme', saved);
        // Sinkronkan juga tema Bootstrap/AdminLTE (adminlte.min.js menimpanya
        // saat DOMContentLoaded; initThemeBridge di init.js mengunci ulang)
        document.documentElement.setAttribute('data-bs-theme', saved);
      } catch (e) {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    })();
  </script>
  <?= $this->include('layouts/head.php') ?>
</head>

<?php
  $currentUserId = session()->get('user_id');
  $currentUser   = null;
  if ($currentUserId) {
      $userModel   = model(\App\Models\UserModel::class);
      $currentUser = $userModel->find($currentUserId);
  }
  $userName     = $currentUser['name'] ?? session()->get('user_name') ?? 'Administrator';
  $userUsername = $currentUser['username'] ?? session()->get('username') ?? 'admin';
  $userRole     = $currentUser['role'] ?? session()->get('role') ?? 'admin';
?>

<body 
  data-active="<?= esc($activeKey ?? '') ?>" 
  data-crumbs="<?= esc($crumbs ?? '') ?>"
  data-user-name="<?= esc($userName) ?>"
  data-user-username="<?= esc($userUsername) ?>"
  data-user-role="<?= esc($userRole) ?>"
>
  <div class="shell">
    <!-- Shell dirender PHP (partial) — atribut data-shell-* dihapus supaya Shell.js lama (cache CDN) tidak menimpa -->
    <?= view('partials/sidebar.php', ['activeKey' => $activeKey ?? '', 'userName' => $userName, 'userRole' => $userRole]) ?>
    <div class="main">
      <?= view('partials/topbar.php', ['crumbs' => $crumbs ?? '', 'username' => $userUsername, 'userName' => $userName]) ?>
      <main class="content">
        <?= $this->renderSection('content') ?>
      </main>
      <?= view('partials/footer.php') ?>
    </div>
  </div>
  <!-- ========================================================= JAVASCRIPT ========================================================== -->
  <!-- jQuery -->
  <?= $this->include('layouts/js-query') ?>

  <?= $this->renderSection('scripts') ?>

</body>

</html>