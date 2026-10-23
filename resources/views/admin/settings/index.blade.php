@extends('layouts.admin')

@section('title', 'Configuración')

@section('content')
<div class="admin-content">
    <div class="mb-4">
        <h1 class="h3 mb-1"><i class="fas fa-cog me-2 text-warning"></i>Configuración</h1>
        <p class="text-muted mb-0">Parámetros activos de la aplicación y accesos de administración</p>
    </div>

    <div class="alert alert-info border-0">
        <i class="fas fa-info-circle me-2"></i>Estos parámetros se cargan desde la configuración del servidor y el archivo de entorno; esta pantalla es informativa y no los modifica.
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0"><h5 class="mb-0">Configuración activa</h5></div>
                <div class="card-body">
                    @foreach($settings as $label => $value)
                    <div class="d-flex justify-content-between gap-3 py-3 border-bottom">
                        <span class="text-muted">{{ $label }}</span>
                        <strong class="text-end">{{ $value }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0"><h5 class="mb-0">Administración</h5></div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.taxes.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-percent me-2 text-primary"></i>Impuestos</a>
                    <a href="{{ route('admin.roles.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-user-shield me-2 text-primary"></i>Roles y accesos</a>
                    <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-users me-2 text-primary"></i>Usuarios</a>
                    <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action"><i class="fas fa-tags me-2 text-primary"></i>Categorías</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
