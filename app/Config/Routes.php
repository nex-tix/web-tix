<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// AUTH ROUTES
$routes->get('/login', 'Auth\LoginController::index');
$routes->post('/login', 'Auth\LoginController::authenticate');
$routes->get('/logout', 'Auth\LoginController::logout');
$routes->get('/', 'Auth\LoginController::index');


// ADMIN ROUTES
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // Dashboard Admin
    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'Home::index');

    // Users Management
    $routes->get('users', 'Admin\UserController::index');
    $routes->post('users', 'Admin\UserController::store');
    $routes->post('users/(:num)', 'Admin\UserController::update/$1');
    $routes->post('users/(:num)/delete', 'Admin\UserController::delete/$1');
    $routes->post('users/(:num)/reset-password', 'Admin\UserController::resetPassword/$1');

    // Gate Users Management
    $routes->get('gate-users', 'Admin\GateUserController::index');
    $routes->post('gate-users', 'Admin\GateUserController::store');
    $routes->post('gate-users/(:num)', 'Admin\GateUserController::update/$1');
    $routes->post('gate-users/(:num)/delete', 'Admin\GateUserController::delete/$1');

    // Guides Management
    $routes->get('guides', 'Admin\GuideController::index');
    $routes->post('guides/store', 'Admin\GuideController::store');
    $routes->post('guides/(:num)/update', 'Admin\GuideController::update/$1');
    $routes->post('guides/(:num)/delete', 'Admin\GuideController::delete/$1');

    // Ticket Types Management
    $routes->get('ticket-types', 'Admin\TicketTypeController::index');
    $routes->post('ticket-types/store', 'Admin\TicketTypeController::store');
    $routes->post('ticket-types/(:num)/update', 'Admin\TicketTypeController::update/$1');
    $routes->post('ticket-types/(:num)/delete', 'Admin\TicketTypeController::delete/$1');

    // Banks Management
    $routes->get('banks', 'Admin\BankController::index');
    $routes->post('banks/store', 'Admin\BankController::store');
    $routes->post('banks/(:num)/update', 'Admin\BankController::update/$1');
    $routes->post('banks/(:num)/delete', 'Admin\BankController::delete/$1');

    // Checkpoints Management
    $routes->get('checkpoints', 'Admin\CheckpointController::index');
    $routes->post('checkpoints/store', 'Admin\CheckpointController::store');
    $routes->post('checkpoints/(:num)/update', 'Admin\CheckpointController::update/$1');
    $routes->post('checkpoints/(:num)/delete', 'Admin\CheckpointController::delete/$1');

    // Settings Management
    $routes->get('settings', 'Admin\SettingController::index');
    $routes->post('settings/store', 'Admin\SettingController::store');
    $routes->post('settings/(:segment)/update', 'Admin\SettingController::update/$1');
    $routes->post('settings/(:segment)/delete', 'Admin\SettingController::delete/$1');

    // Gaji Guide (ledger pembayaran guide)
    $routes->get('gaji-guide', 'Admin\GuideSalaryController::index');
    $routes->post('gaji-guide/generate', 'Admin\GuideSalaryController::generate');
    $routes->post('gaji-guide/(:num)/pay', 'Admin\GuideSalaryController::pay/$1');
    $routes->post('gaji-guide/(:num)/cancel', 'Admin\GuideSalaryController::cancel/$1');

    //Pos Management
    $routes->get('pos', 'Admin\PosController::index');
    $routes->post('pos/store', 'Admin\PosController::store');
    $routes->get('pos/receipt/(:num)', 'Admin\PosController::receipt/$1');

    // Laporan
    $routes->get('laporan', 'ReportController::index');
    $routes->get('laporan/export-pdf', 'ReportController::exportPdf');
    // Endpoint Excel pertama (baru) — pengujian end-to-end sebelum endpoint lain menyusul
    $routes->get('laporan/pengunjung', 'ReportController::pengunjung');
    $routes->get('laporan/pengunjung/export-pdf', 'ReportController::exportPengunjungPdf');
    $routes->get('laporan/guide', 'ReportController::guide');
    $routes->get('laporan/guide/export-pdf', 'ReportController::exportGuidePdf');
    $routes->get('laporan/keuangan', 'ReportController::keuangan');
    $routes->get('laporan/keuangan/export-pdf', 'ReportController::exportKeuanganPdf');

    // Riwayat Penjualan
    $routes->get('transaction-history', 'Admin\TransactionHistoryController::index');
    $routes->get('transaction-history/detail/(:num)', 'Admin\TransactionHistoryController::detail/$1');
    $routes->post('transaction-history/void/(:num)', 'Admin\TransactionHistoryController::void/$1');
    // ubah metode bayar transaksi selesai (cash ↔ debit)
    $routes->post('transaction-history/update-payment/(:num)', 'Admin\TransactionHistoryController::updatePayment/$1');
    // Cetak ulang tiket dari riwayat: GET data tiket aktif (AJAX modal), POST eksekusi reprint
    $routes->get('transaction-history/reprint-data/(:num)', 'Admin\TransactionHistoryController::reprintData/$1');
    $routes->post('transaction-history/reprint/(:num)', 'Admin\TransactionHistoryController::reprint/$1');

    // Data Tiket
    $routes->get('data-tiket', 'Admin\TicketController::index');
    $routes->get('data-tiket/detail/(:num)', 'Admin\TicketController::detail/$1');

    // Pengeluaran Kas & Operasional (Expenses)
    $routes->get('expenses', 'Admin\ExpenseController::index');
    $routes->post('expenses/store', 'Admin\ExpenseController::store');
    $routes->post('expenses/category/store', 'Admin\ExpenseController::storeCategory');
    $routes->post('expenses/(:num)/delete', 'Admin\ExpenseController::delete/$1');
    $routes->get('expenses/export-pdf', 'Admin\ExpenseController::exportPdf');

    //Export excel
    $routes->get('laporan/export-excel', 'ReportController::exportExcel');
    $routes->get('laporan/pengunjung/export-excel', 'ReportController::exportPengunjungExcel');
    $routes->get('laporan/guide/export-excel', 'ReportController::exportGuideExcel');
    $routes->get('laporan/keuangan/export-excel', 'ReportController::exportKeuanganExcel');
    $routes->get('expenses/export-excel', 'Admin/ExpenseController::exportExcel');
});
