<?php

return [
    // Rutas públicas (Shop)
    'home' => '/',
    'shop.categories' => '/categorias',
    'shop.category' => '/categoria/{id}',
    'shop.product' => '/producto/{id}',
    'shop.search' => '/buscar',
    'shop.cart' => '/carrito',
    'shop.checkout' => '/checkout',
    
    // Auth
    'auth.login' => '/login',
    'auth.register' => '/registro',
    'auth.logout' => '/logout',
    'auth.password.request' => '/password/reset',
    'auth.password.reset' => '/password/reset/{token}',
    
    // Admin (requiere auth + admin role)
    'admin.dashboard' => '/admin',
    'admin.products.index' => '/admin/productos',
    'admin.products.create' => '/admin/productos/crear',
    'admin.products.store' => '/admin/productos',
    'admin.products.edit' => '/admin/productos/{id}/editar',
    'admin.products.update' => '/admin/productos/{id}',
    'admin.products.destroy' => '/admin/productos/{id}',
    'admin.categories.index' => '/admin/categorias',
    'admin.categories.create' => '/admin/categorias/crear',
    'admin.users.index' => '/admin/usuarios',
    'admin.users.create' => '/admin/usuarios/crear',
    'admin.users.store' => '/admin/usuarios',
    'admin.users.show' => '/admin/usuarios/{id}',
    'admin.users.edit' => '/admin/usuarios/{id}/editar',
    'admin.users.update' => '/admin/usuarios/{id}',
    'admin.users.destroy' => '/admin/usuarios/{id}',
    'admin.roles.index' => '/admin/roles',
    'admin.orders.index' => '/admin/pedidos',
    'admin.purchases.index' => '/admin/compras',
    'admin.taxes.index' => '/admin/impuestos',
    
    // API endpoints (AJAX)
    'api.products.list' => '/api/products',
    'api.products.show' => '/api/products/{id}',
    'api.products.by_category' => '/api/products/category/{id}',
    'api.products.search' => '/api/productos/buscar/{term}',
    'api.categories.list' => '/api/categories',
    'api.cart.add' => '/api/cart/add',
    'api.cart.update' => '/api/cart/update',
    'api.cart.remove' => '/api/cart/remove',
    'api.cart.clear' => '/api/cart/clear',
    'api.auth.user' => '/api/auth/user',
];