<!doctype html>
<html lang="id">

<head>
  <?= $this->include('layouts/head.php') ?>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    <!-- ===================================================== SIDEBAR ====================================================== -->
    <!-- Sidebar AdminLTE 4 -->
    <?= $this->include('partials/sidebar.php') ?>

    <!-- ===================================================== MAIN ====================================================== -->
    <main class="app-main">
      <!-- ================================================= TOP STATUS ================================================== -->
      <?= $this->include('partials/header.php') ?>


      <!-- ================================================= CONTENT ================================================== -->
      <?= $this->renderSection('content') ?>

    </main>
  </div>
  <!-- ========================================================= JAVASCRIPT ========================================================== -->
  <!-- jQuery -->
  <?= $this->include('layouts/js-query') ?>

  <?= $this->renderSection('scripts') ?>

</body>
<!-- CHART JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script src="js/main.js"></script>

</html>