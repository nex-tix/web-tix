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

    // Ticket Types Management
    $routes->get('ticket-types', 'Admin\TicketTypeController::index');
    $routes->post('ticket-types/store', 'Admin\TicketTypeController::store');
    $routes->post('ticket-types/(:num)/update', 'Admin\TicketTypeController::update/$1');
    $routes->post('ticket-types/(:num)/delete', 'Admin\TicketTypeController::delete/$1');

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

    //Pos Management
    $routes->get('pos', 'admin\PosController::index');
    $routes->post('pos/store', 'admin\PosController::store');
    $routes->get('pos/receipt/(:num)', 'admin\PosController::receipt/$1');
    $routes->get('pos/reprint', 'Admin\PosController::reprint');
    $routes->post('pos/reprint/(:num)', 'admin\PosController::doReprint/$1');

    //laporan
    $routes->get('laporan', 'ReportController::index');
    $routes->get('laporan/export-pdf', 'ReportController::exportPdf');

    // Riwayat Penjualan
    $routes->get('transaction-history', 'Admin\TransactionHistoryController::index');
    $routes->get('transaction-history/detail/(:num)', 'Admin\TransactionHistoryController::detail/$1');
    $routes->post('transaction-history/void/(:num)', 'Admin\TransactionHistoryController::void/$1');

    // Data Tiket
    $routes->get('data-tiket', 'Admin\TicketController::index');
    $routes->get('data-tiket/detail/(:num)', 'Admin\TicketController::detail/$1');
});
