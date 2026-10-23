<?php

use Core\Router;

$router = app('router');

// Rutas públicas de la tienda
$router->get('/', 'ShopController@index')->name('home');
$router->get('/categorias', 'ShopController@categories')->name('shop.categories');
$router->get('/categoria/{id}', 'ShopController@category')->name('shop.category');
$router->get('/producto/{id}', 'ShopController@product')->name('shop.product');
$router->get('/favoritos', 'WishlistController@index')->name('shop.wishlist');
$router->get('/buscar', 'ShopController@search')->name('shop.search');

// Carrito
$router->get('/carrito', 'CartController@index')->name('shop.cart');
$router->post('/carrito/agregar', 'CartController@add')->name('shop.cart.add');
$router->post('/carrito/actualizar', 'CartController@update')->name('shop.cart.update');
$router->post('/carrito/eliminar', 'CartController@remove')->name('shop.cart.remove');
$router->post('/carrito/limpiar', 'CartController@clear')->name('shop.cart.clear');
$router->post('/favoritos/toggle', 'WishlistController@toggle')->name('shop.wishlist.toggle');

// Checkout
$router->get('/checkout', 'CheckoutController@index')->name('shop.checkout');
$router->post('/checkout', 'CheckoutController@process')->name('shop.checkout.process');
$router->get('/checkout/exito/{id}', 'CheckoutController@success')->name('shop.checkout.success');

// API para frontend (AJAX)
$router->group(['prefix' => '/api', 'as' => 'api.'], function (Router $router) {
    $router->get('/productos', 'Api\ProductController@index')->name('products.list');
    $router->get('/productos/{id}', 'Api\ProductController@show')->name('products.show');
    $router->get('/productos/categoria/{id}', 'Api\ProductController@byCategory')->name('products.by_category');
    $router->get('/categorias', 'Api\CategoryController@index')->name('categories.list');
    
    $router->post('/carrito/agregar', 'Api\CartController@add')->name('cart.add');
    $router->post('/carrito/actualizar', 'Api\CartController@update')->name('cart.update');
    $router->post('/carrito/eliminar', 'Api\CartController@remove')->name('cart.remove');
    $router->post('/carrito/limpiar', 'Api\CartController@clear')->name('cart.clear');
    $router->get('/carrito', 'Api\CartController@index')->name('cart.index');
});