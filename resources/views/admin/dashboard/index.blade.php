@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-content dashboard-page">
    <!-- Page Header -->
    <div class="dashboard-heading d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-tachometer-alt me-2 text-warning"></i>Dashboard</h1>
            <p class="text-muted mb-0">Bienvenido, {{ auth()->nombre_completo ?? 'Admin' }} <span class="badge bg-warning text-dark ms-2">{{ auth()->rol->nombreRol ?? 'Administrador' }}</span></p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.products.create') }}" class="btn btn-poke-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Producto
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-warning">
                <i class="fas fa-shopping-bag me-2"></i>Ver Pedidos
            </a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row dashboard-stats mb-4">
        <div class="col-6 col-xl-3">
            <div class="card stat-card stat-card-products h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Productos Totales</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['productos'] ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>{{ $stats['productos_activos'] ?? 0 }} activos
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-xl-3">
            <div class="card stat-card stat-card-categories h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Categorías</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['categorias'] ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Tipos de Pokémon</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-xl-3">
            <div class="card stat-card stat-card-users h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Usuarios</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['usuarios'] ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-success">
                            <i class="fas fa-user-check me-1"></i>{{ \Shared\Models\Usuario::query()->where('estado', '=', 'A')->count() }} activos
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-xl-3">
            <div class="card stat-card stat-card-orders h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Pedidos Pendientes</p>
                            <h2 class="mb-0 fw-bold text-danger">{{ \Shared\Models\Pedido::query()->where('estado', '=', 'pendiente')->count() ?? 0 }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-exclamation-triangle me-1"></i>Requieren atención
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row dashboard-panels mb-4">
        <div class="col-xl-8">
            <div class="card dashboard-panel h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Productos Recientes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="recentProductsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Imagen</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productosRecientes as $producto)
                                <tr>
                                    <td>
                                        <img src="{{ $producto->urlFoto ?? asset('img/placeholder.png') }}" alt="{{ $producto->nombrePro }}" class="product-thumb">
                                    </td>
                                    <td>
                                        <strong>{{ $producto->nombrePro }}</strong>
                                        <br><small class="text-muted">#{{ $producto->id }}</small>
                                    </td>
                                    <td>
                                        @if($producto->categoria_nombre)
                                            <span class="badge" style="background: {{ \Shared\Models\Categoria::find($producto->categoria)?->getColor() ?? '#6c757d' }}">
                                                {{ $producto->categoria_nombre }}
                                            </span>
                                        @else
                                            <span class="text-muted">Sin categoría</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($producto->precioPro, 2) }}</td>
                                    <td>
                                        <span class="{{ $producto->cantidadPro <= 5 ? 'text-danger fw-bold' : '' }}">
                                            {{ $producto->cantidadPro }} und
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $producto->estado === 'A' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $producto->estado === 'A' ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $producto->id) }}" class="btn btn-sm btn-outline-primary" title="Editar {{ $producto->nombrePro }}" aria-label="Editar {{ $producto->nombrePro }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Ver todos los productos</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card dashboard-panel h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Stock Bajo</h5>
                </div>
                <div class="card-body">
                    @if(empty($lowStock))
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">¡Todos los productos tienen stock suficiente!</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($lowStock as $product)
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $product->urlFoto ?? asset('img/placeholder.png') }}" alt="" class="product-thumb-sm">
                                        <div>
                                            <strong>{{ $product->nombrePro }}</strong>
                                            <br><small class="text-muted">Categoría: {{ $product->categoria_nombre ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-danger fs-6">{{ $product->cantidadPro }} und</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.products.index') }}?search=stock" class="btn btn-sm btn-outline-warning">Ver todos</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.products.create') }}" class="quick-action-card text-center text-decoration-none h-100 quick-action-products">
                                <div class="quick-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-plus fa-2x"></i>
                                </div>
                                <h6 class="mt-3 mb-1">Nuevo Producto</h6>
                                <p class="text-muted small mb-0">Agregar Pokémon al catálogo</p>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.categories.create') }}" class="quick-action-card text-center text-decoration-none h-100 quick-action-categories">
                                <div class="quick-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-tag fa-2x"></i>
                                </div>
                                <h6 class="mt-3 mb-1">Nueva Categoría</h6>
                                <p class="text-muted small mb-0">Crear tipo de Pokémon</p>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.users.create') }}" class="quick-action-card text-center text-decoration-none h-100 quick-action-users">
                                <div class="quick-icon bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-user-plus fa-2x"></i>
                                </div>
                                <h6 class="mt-3 mb-1">Nuevo Usuario</h6>
                                <p class="text-muted small mb-0">Registrar entrenador</p>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.orders.index') }}" class="quick-action-card text-center text-decoration-none h-100 quick-action-orders">
                                <div class="quick-icon bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-shopping-bag fa-2x"></i>
                                </div>
                                <h6 class="mt-3 mb-1">Gestionar Pedidos</h6>
                                <p class="text-muted small mb-0">Revisar pedidos pendientes</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Initialize DataTable for recent products
$(document).ready(function() {
    $('#recentProductsTable').DataTable({
        pageLength: 5,
        lengthChange: false,
        searching: false,
        ordering: true,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    });
});
</script>
@endsection