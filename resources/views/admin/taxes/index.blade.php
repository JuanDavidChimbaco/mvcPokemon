@extends('layouts.admin')

@section('title', 'Impuestos')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-percent me-2 text-warning"></i>Impuestos</h1>
            <p class="text-muted mb-0">Administra las tasas aplicadas a las compras</p>
        </div>
        <a href="{{ route('admin.taxes.create') }}" class="btn btn-poke-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Impuesto
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0"><h5 class="mb-0">Tasas configuradas</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Nombre</th><th>Porcentaje</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                    </thead>
                    <tbody>
                        @foreach($impuestos as $impuesto)
                        <tr>
                            <td class="fw-semibold">{{ $impuesto->nombre }}</td>
                            <td>{{ number_format($impuesto->porcentaje, 2) }}%</td>
                            <td><span class="badge {{ $impuesto->estado === 'A' ? 'bg-success' : 'bg-secondary' }}">{{ $impuesto->estado === 'A' ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.taxes.edit', $impuesto->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar {{ $impuesto->nombre }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.taxes.destroy', $impuesto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este impuesto?')">
                                    <input type="hidden" name="_token" value="{{ session()->token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar {{ $impuesto->nombre }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($impuestos))
                        <tr><td colspan="4" class="text-center text-muted py-5">No hay impuestos configurados.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
