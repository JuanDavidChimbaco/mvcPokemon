<?php

use Core\Router;

$router = app('router');

// Autenticación de clientes
$router->get('/login', 'Modules\\Auth\\Controllers\\AuthController@showLogin')->name('auth.login');
$router->post('/login', 'Modules\\Auth\\Controllers\\AuthController@performLogin')->name('auth.login.post');
$router->get('/registro', 'Modules\\Auth\\Controllers\\AuthController@showRegister')->name('auth.register');
$router->post('/registro', 'Modules\\Auth\\Controllers\\AuthController@register')->name('auth.register.post');
$router->post('/logout', 'Modules\\Auth\\Controllers\\AuthController@performLogout')->name('auth.logout');

// API de autenticación
$router->group(['prefix' => '/api/auth', 'as' => 'api.auth.'], function (Router $router) {
    $router->post('/login', 'Api\AuthController@login')->name('login');
    $router->post('/register', 'Api\AuthController@register')->name('register');
    $router->post('/logout', 'Api\AuthController@logout')->name('logout')->middleware('auth');
    $router->get('/user', 'Api\AuthController@user')->name('user')->middleware('auth');
    $router->post('/password/email', 'Api\AuthController@passwordEmail')->name('password.email');
    $router->post('/password/reset', 'Api\AuthController@passwordReset')->name('password.reset');
});