<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/admin', 'Home::index');
$routes->get('/index', 'Home::index');

// AUTH ROUTES
$routes->get('/login', 'Auth\LoginController::index');
$routes->post('/login', 'Auth\LoginController::authenticate');
$routes->get('/logout', 'Auth\LoginController::logout');

// ADMIN ROUTES
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    // Dashboard Admin
    $routes->get('/', 'Home::index');
    $routes->get('dashboard', 'Home::index');

    // Users Management
    $routes->get('users', 'Admin\UserController::index');
    $routes->post('users', 'Admin\UserController::store');
    $routes->post('users/(:num)', 'Admin\UserController::update/$1');
    $routes->post('users/(:num)/delete', 'Admin\UserController::delete/$1');
    $routes->post('users/(:num)/reset-password', 'Admin\UserController::resetPassword/$1');

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
});
