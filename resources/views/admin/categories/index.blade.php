@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-tags me-2 text-warning"></i>Categorías</h1>
            <p class="text-muted mb-0">Gestiona los tipos de Pokémon</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-poke-primary">
            <i class="fas fa-plus me-2"></i>Nueva Categoría
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Categorías</h5>
            <span class="badge bg-primary">{{ count($categorias) }} categorías</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="categoriesTable">
                    <thead class="table-light">
                        <tr>
                            <th>Icono</th><th>Nombre</th><th>Descripción</th><th>Estado</th><th>Productos</th><th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorias as $categoria)
                        <tr>
                            <td>
                                <div class="category-icon-cell" style="background: {{ $categoria->getGradient() }}">
                                    <img src="{{ $categoria->getIconUrl() }}" alt="{{ $categoria->nombreCat }}" width="40" height="40">
                                </div>
                            </td>
                            <td><strong>{{ $categoria->nombreCat }}</strong><br><small class="text-muted">ID: {{ $categoria->id }}</small></td>
                            <td>{{ $categoria->descripcion ?: 'Sin descripción' }}</td>
                            <td><span class="badge {{ $categoria->estado === 'A' ? 'bg-success' : 'bg-secondary' }}">{{ $categoria->estado === 'A' ? 'Activa' : 'Inactiva' }}</span></td>
                            <td>{{ $categoria->productosCount() }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $categoria->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar {{ $categoria->nombreCat }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.categories.destroy', $categoria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    <input type="hidden" name="_token" value="{{ session()->token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar {{ $categoria->nombreCat }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($categorias))
                        <tr><td colspan="6" class="text-center text-muted py-5">No hay categorías registradas.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#categoriesTable').DataTable({
        pageLength: 10,
        order: [[1, 'asc']],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' }
    });
});
</script>
@endsection
