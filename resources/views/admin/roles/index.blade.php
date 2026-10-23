@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-user-shield me-2 text-warning"></i>Roles</h1>
            <p class="text-muted mb-0">Administra los roles asignados a usuarios</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-poke-primary"><i class="fas fa-plus me-2"></i>Nuevo Rol</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0"><h5 class="mb-0">Roles del sistema</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Rol</th><th>Descripción</th><th>Usuarios</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                        @foreach($roles as $rol)
                        <tr>
                            <td class="fw-semibold">{{ $rol->nombreRol }}</td>
                            <td>{{ $rol->descripcion ?: 'Sin descripción' }}</td>
                            <td>{{ $rol->usuarios()->count() }}</td>
                            <td><span class="badge {{ $rol->estado === 'A' ? 'bg-success' : 'bg-secondary' }}">{{ $rol->estado === 'A' ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.roles.edit', $rol->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Editar {{ $rol->nombreRol }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.roles.destroy', $rol->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar el rol {{ $rol->nombreRol }}?')">
                                    <input type="hidden" name="_token" value="{{ session()->token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Eliminar {{ $rol->nombreRol }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($roles))
                        <tr><td colspan="5" class="text-center text-muted py-5">No hay roles configurados.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
