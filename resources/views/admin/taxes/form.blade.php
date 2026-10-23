@extends('layouts.admin')

@section('title', $impuesto ? 'Editar impuesto' : 'Nuevo impuesto')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-percent me-2 text-warning"></i>{{ $impuesto ? 'Editar impuesto' : 'Nuevo impuesto' }}</h1>
            <p class="text-muted mb-0">Configura el nombre, tasa y estado</p>
        </div>
        <a href="{{ route('admin.taxes.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Volver</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ $impuesto ? route('admin.taxes.update', $impuesto->id) : route('admin.taxes.store') }}" method="POST">
                <input type="hidden" name="_token" value="{{ session()->token() }}">
                @if($impuesto)
                <input type="hidden" name="_method" value="PUT">
                @endif

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $impuesto->nombre ?? '') }}" maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label for="porcentaje" class="form-label">Porcentaje</label>
                    <div class="input-group">
                        <input type="number" id="porcentaje" name="porcentaje" class="form-control" value="{{ old('porcentaje', $impuesto->porcentaje ?? '') }}" min="0" max="100" step="0.01" required>
                        <span class="input-group-text">%</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="A" {{ old('estado', $impuesto->estado ?? 'A') === 'A' ? 'selected' : '' }}>Activo</option>
                        <option value="I" {{ old('estado', $impuesto->estado ?? 'A') === 'I' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-poke-primary"><i class="fas fa-save me-2"></i>Guardar impuesto</button>
            </form>
        </div>
    </div>
</div>
@endsection
