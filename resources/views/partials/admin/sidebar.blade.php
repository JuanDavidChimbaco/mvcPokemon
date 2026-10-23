<aside class="admin-sidebar" id="adminSidebar" role="navigation" aria-label="Menú lateral admin">
    <div class="sidebar-header">
        <h5 class="mb-0 text-warning"><i class="fas fa-cogs me-2"></i>Administración</h5>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->path() === '/admin' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li><hr class="sidebar-divider"></li>
            
            <!-- Gestión de Tienda -->
            <li class="nav-item">
                <h6 class="nav-section-title">Gestión de Tienda</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/productos') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box-open"></i>
                    <span>Productos</span>
                    <span class="badge bg-warning text-dark ms-auto">{{ \Shared\Models\Producto::query()->where('estado', '=', 'A')->count() }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/categorias') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Categorías</span>
                    <span class="badge bg-warning text-dark ms-auto">{{ \Shared\Models\Categoria::query()->where('estado', '=', 'A')->count() }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/impuestos') ? 'active' : '' }}" href="{{ route('admin.taxes.index') }}">
                    <i class="fas fa-percent"></i>
                    <span>Impuestos</span>
                </a>
            </li>
            
            <li><hr class="sidebar-divider"></li>
            
            <!-- Pedidos y Compras -->
            <li class="nav-item">
                <h6 class="nav-section-title">Ventas y Compras</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/pedidos') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Pedidos</span>
                    <span class="badge bg-danger ms-auto">{{ \Shared\Models\Pedido::query()->where('estado', '=', 'pendiente')->count() ?? 0 }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/compras') ? 'active' : '' }}" href="{{ route('admin.purchases.index') }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Compras</span>
                </a>
            </li>
            
            <li><hr class="sidebar-divider"></li>
            
            <!-- Usuarios y Roles -->
            <li class="nav-item">
                <h6 class="nav-section-title">Usuarios y Permisos</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/usuarios') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                    <span class="badge bg-info ms-auto">{{ \Shared\Models\Usuario::query()->where('estado', '=', 'A')->count() }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/roles') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Roles</span>
                </a>
            </li>
            
            <li><hr class="sidebar-divider"></li>
            
            <!-- Accesos rápidos -->
            <li class="nav-item">
                <h6 class="nav-section-title">Accesos Rápidos</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Ver Tienda</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ str_starts_with(request()->path(), '/admin/reportes') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <div class="sidebar-version">
            <small class="text-muted">PokéTienda Admin v1.0.0</small>
        </div>
    </div>
</aside>

<!-- Sidebar Toggle Button (Mobile) -->
<button class="btn btn-poke-primary sidebar-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Toggle sidebar">
    <i class="fas fa-bars"></i>
</button>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay" aria-hidden="true"></div>