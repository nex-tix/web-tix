<!doctype html>
<html lang="id">

<head>
  <script>
    (function() {
      try {
        var saved = localStorage.getItem('dash26-theme') || 'light';
        document.documentElement.setAttribute('data-theme', saved);
      } catch (e) {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    })();
  </script>
  <?= $this->include('layouts/head.php') ?>
</head>

<body data-active="<?= esc($activeKey ?? '') ?>" data-crumbs="<?= esc($crumbs ?? '') ?>">
  <div class="shell">
    <div data-shell-sidebar></div>
    <div class="main">
      <div data-shell-topbar></div>
      <main class="content">
        <?= $this->renderSection('content') ?>
      </main>
      <div data-shell-footer></div>
    </div>
  </div>
  <!-- ========================================================= JAVASCRIPT ========================================================== -->
  <!-- jQuery -->
  <?= $this->include('layouts/js-query') ?>

  <?= $this->renderSection('scripts') ?>

</body>

</html>