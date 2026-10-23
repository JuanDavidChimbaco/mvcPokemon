@extends('layouts.admin')

@section('title', 'Productos')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-box-open me-2 text-warning"></i>Productos</h1>
            <p class="text-muted mb-0">Gestiona el catálogo de Pokémon</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-poke-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Producto
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label">Buscar producto</label>
                    <input type="search" id="search" name="search" class="form-control" placeholder="Nombre del Pokémon" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" name="categoria" class="form-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ (string)$category === (string)$cat->id ? 'selected' : '' }}>{{ $cat->nombreCat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="A" {{ $status === 'A' ? 'selected' : '' }}>Activos</option>
                        <option value="I" {{ $status === 'I' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1"><i class="fas fa-filter me-1"></i>Filtrar</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" aria-label="Limpiar filtros"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Productos</h5>
            <span class="badge bg-primary">{{ count($productos) }} productos</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="productsTable">
                    <thead class="table-light">
                        <tr><th>Imagen</th><th>Nombre</th><th>Categoría</th><th>Precio</th><th>Stock</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr>
                            <td><img src="{{ product_image_url($producto->urlFoto) }}" data-pokemon-name="{{ $producto->nombrePro }}" alt="{{ $producto->nombrePro }}" class="product-thumb"></td>
                            <td><strong>{{ $producto->nombrePro }}</strong><br><small class="text-muted">#{{ $producto->id }}</small></td>
                            <td>{{ $producto->categoria_nombre ?? 'Sin categoría' }}</td>
                            <td>${{ number_format($producto->precioPro, 2) }}</td>
                            <td><span class="{{ $producto->cantidadPro <= 5 ? 'text-danger fw-bold' : '' }}">{{ $producto->cantidadPro }} und</span></td>
                            <td><span class="badge {{ $producto->estado === 'A' ? 'bg-success' : 'bg-secondary' }}">{{ $producto->estado === 'A' ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $producto->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar {{ $producto->nombrePro }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.products.destroy', $producto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                    <input type="hidden" name="_token" value="{{ session()->token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar {{ $producto->nombrePro }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($productos))
                        <tr><td colspan="7" class="text-center text-muted py-5">No se encontraron productos para estos filtros.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
