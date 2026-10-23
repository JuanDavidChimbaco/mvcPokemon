<?php

use Core\Router;

$router = app('router');

// API pública para la tienda (frontend)
$router->group(['prefix' => '/api', 'as' => 'api.'], function (Router $router) {
    // Productos
    $router->get('/productos', 'Api\ProductController@index')->name('products.index');
    $router->get('/productos/{id}', 'Api\ProductController@show')->name('products.show');
    $router->get('/productos/categoria/{id}', 'Api\ProductController@byCategory')->name('products.by_category');
    $router->get('/productos/buscar/{term}', 'Api\ProductController@search')->name('products.search');
    $router->get('/productos/destacados', 'Api\ProductController@featured')->name('products.featured');
    
    // Categorías
    $router->get('/categorias', 'Api\CategoryController@index')->name('categories.index');
    $router->get('/categorias/{id}', 'Api\CategoryController@show')->name('categories.show');
    
    // Carrito (requiere sesión)
    $router->group(['middleware' => ['session']], function (Router $router) {
        $router->get('/carrito', 'Api\CartController@index')->name('cart.index');
        $router->post('/carrito/agregar', 'Api\CartController@add')->name('cart.add');
        $router->post('/carrito/actualizar', 'Api\CartController@update')->name('cart.update');
        $router->post('/carrito/eliminar', 'Api\CartController@remove')->name('cart.remove');
        $router->post('/carrito/limpiar', 'Api\CartController@clear')->name('cart.clear');
        $router->post('/carrito/sincronizar', 'Api\CartController@sync')->name('cart.sync');
    });
    
    // Pedidos (requiere auth)
    $router->group(['middleware' => ['auth']], function (Router $router) {
        $router->get('/pedidos', 'Api\OrderController@index')->name('orders.index');
        $router->get('/pedidos/{id}', 'Api\OrderController@show')->name('orders.show');
        $router->post('/pedidos', 'Api\OrderController@store')->name('orders.store');
    });
});