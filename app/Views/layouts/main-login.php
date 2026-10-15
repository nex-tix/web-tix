<!DOCTYPE html>

<html lang="id">

<head>
    <?= $this->include('layouts/login-head.php') ?>
</head>

<body class="min-h-screen bg-[#f8f6f6] font-sans antialiased text-slate-800 flex flex-col justify-between selection:bg-[#e24a3b] selection:text-white">
    <!-- BEGIN: TopStatusBar -->
    <!-- Top bar mirroring the operational terminal dashboard header in design -->
    <!-- END: TopStatusBar -->
    <!-- BEGIN: MainContent -->
    <main class="grow flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden bg-grid-subtle">
        <?= $this->renderSection('content') ?>
    </main>
    <!-- END: MainContent -->
    <!-- BEGIN: PageFooter -->
    <!-- BEGIN: InteractivityScripts -->
    <!-- Script for toggling password visibility and displaying live Bali operational clock -->
    <script src="<?= base_url('js/auth-main.js') ?>"></script>
    <!-- END: InteractivityScripts -->
</body>

</html>