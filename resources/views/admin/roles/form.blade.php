@extends('layouts.admin')

@section('title', $rol ? 'Editar rol' : 'Nuevo rol')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-user-shield me-2 text-warning"></i>{{ $rol ? 'Editar rol' : 'Nuevo rol' }}</h1>
            <p class="text-muted mb-0">Configura el nombre y el estado del rol</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ $rol ? route('admin.roles.update', $rol->id) : route('admin.roles.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ session()->token() }}">
                @if($rol)<input type="hidden" name="_method" value="PUT">@endif
                <div class="mb-3">
                    <label for="nombreRol" class="form-label">Nombre del rol</label>
                    <input type="text" id="nombreRol" name="nombreRol" class="form-control" value="{{ old('nombreRol', $rol->nombreRol ?? '') }}" minlength="2" maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-control" rows="3">{{ old('descripcion', $rol->descripcion ?? '') }}</textarea>
                </div>
                <div class="mb-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="A" {{ old('estado', $rol->estado ?? 'A') === 'A' ? 'selected' : '' }}>Activo</option>
                        <option value="I" {{ old('estado', $rol->estado ?? 'A') === 'I' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-poke-primary"><i class="fas fa-save me-2"></i>Guardar rol</button>
            </form>
        </div>
    </div>
</div>
@endsection
