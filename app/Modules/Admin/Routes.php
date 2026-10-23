<?php

use Core\Router;

$router = app('router');

// Rutas del panel de administración (requieren auth + admin)
$router->group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function (Router $router) {
    $router->get('', 'AdminController@dashboard')->name('dashboard');
    
    // Resource controllers
    $router->resource('productos', 'ProductController', ['except' => ['show']]);
    $router->resource('categorias', 'CategoryController', ['except' => ['show']]);
    $router->resource('usuarios', 'UserController');
    $router->get('/roles', 'RoleController@index')->name('admin.roles.index');
    $router->get('/roles/crear', 'RoleController@create')->name('admin.roles.create');
    $router->post('/roles', 'RoleController@store')->name('admin.roles.store');
    $router->get('/roles/{id}/editar', 'RoleController@edit')->name('admin.roles.edit');
    $router->put('/roles/{id}', 'RoleController@update')->name('admin.roles.update');
    $router->delete('/roles/{id}', 'RoleController@destroy')->name('admin.roles.destroy');
    $router->get('/pedidos', 'OrderController@index')->name('admin.orders.index');
    $router->get('/pedidos/{id}', 'OrderController@show')->name('admin.orders.show');
    $router->put('/pedidos/{id}', 'OrderController@update')->name('admin.orders.update');
    $router->get('/compras', 'PurchaseController@index')->name('admin.purchases.index');
    $router->get('/compras/{id}', 'PurchaseController@show')->name('admin.purchases.show');
    $router->get('/reportes', 'ReportController@index')->name('admin.reports.index');
    $router->get('/configuracion', 'SettingsController@index')->name('admin.settings.index');
    $router->get('/perfil', 'ProfileController@edit')->name('admin.profile.edit');
    $router->put('/perfil', 'ProfileController@update')->name('admin.profile.update');
    $router->get('/impuestos', 'TaxController@index')->name('admin.taxes.index');
    $router->get('/impuestos/crear', 'TaxController@create')->name('admin.taxes.create');
    $router->post('/impuestos', 'TaxController@store')->name('admin.taxes.store');
    $router->get('/impuestos/{id}/editar', 'TaxController@edit')->name('admin.taxes.edit');
    $router->put('/impuestos/{id}', 'TaxController@update')->name('admin.taxes.update');
    $router->delete('/impuestos/{id}', 'TaxController@destroy')->name('admin.taxes.destroy');
});

// API Admin
$router->group(['prefix' => '/admin/api', 'as' => 'admin.api.', 'middleware' => ['auth', 'admin']], function (Router $router) {
    $router->apiResource('productos', 'Api\ProductController');
    $router->apiResource('categorias', 'Api\CategoryController');
    $router->apiResource('usuarios', 'Api\UserController');
    $router->apiResource('roles', 'Api\RoleController');
    $router->apiResource('pedidos', 'Api\OrderController');
});