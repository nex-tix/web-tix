<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/index', 'Home::index');

// AUTH ROUTES
$routes->get('/login', 'Auth\LoginController::index');
$routes->post('/login', 'Auth\LoginController::authenticate');
$routes->get('/logout', 'Auth\LoginController::logout');
