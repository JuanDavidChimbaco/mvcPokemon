@extends('layouts.admin')

@section('title', $usuario ? 'Editar Usuario' : 'Nuevo Usuario')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1">
                <i class="fas {{ $usuario ? 'fa-edit' : 'fa-plus' }} me-2 text-warning"></i>
                {{ $usuario ? 'Editar Usuario' : 'Nuevo Usuario' }}
            </h1>
            <p class="text-muted mb-0">{{ $usuario ? 'Modifica la información del entrenador' : 'Registra un nuevo entrenador en el sistema' }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información Personal</h5>
        </div>
        <div class="card-body">
            <form action="{{ $usuario ? route('admin.users.update', $usuario->id) : route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @if($usuario)
                @method('PUT')
                @endif
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tipoDoc" class="form-label fw-medium">Tipo de Documento <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipoDoc') is-invalid @enderror" id="tipoDoc" name="tipoDoc" required>
                            <option value="CC" {{ old('tipoDoc', $usuario->tipoDoc ?? '') === 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="TI" {{ old('tipoDoc', $usuario->tipoDoc ?? '') === 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="CE" {{ old('tipoDoc', $usuario->tipoDoc ?? '') === 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PA" {{ old('tipoDoc', $usuario->tipoDoc ?? '') === 'PA' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        @error('tipoDoc')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="identificacion" class="form-label fw-medium">Número de Identificación <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('identificacion') is-invalid @enderror" 
                               id="identificacion" name="identificacion" placeholder="123456789" 
                               value="{{ old('identificacion', $usuario->identificacion ?? '') }}" required min="1">
                        @error('identificacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="nombre" class="form-label fw-medium">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" placeholder="Juan" 
                               value="{{ old('nombre', $usuario->nombre ?? '') }}" required minlength="2" maxlength="50">
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="apellido" class="form-label fw-medium">Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('apellido') is-invalid @enderror" 
                               id="apellido" name="apellido" placeholder="Pérez" 
                               value="{{ old('apellido', $usuario->apellido ?? '') }}" required minlength="2" maxlength="50">
                        @error('apellido')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="correo" class="form-label fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                               id="correo" name="correo" placeholder="correo@ejemplo.com" 
                               value="{{ old('correo', $usuario->correo ?? '') }}" required maxlength="100">
                        @error('correo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="direccion" class="form-label fw-medium">Dirección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" 
                               id="direccion" name="direccion" placeholder="Calle 123 #45-67" 
                               value="{{ old('direccion', $usuario->direccion ?? '') }}" required maxlength="255">
                        @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="telefono" class="form-label fw-medium">Teléfono <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('telefono') is-invalid @enderror" 
                               id="telefono" name="telefono" placeholder="3001234567" 
                               value="{{ old('telefono', $usuario->telefono ?? '') }}" required maxlength="20">
                        @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="genero" class="form-label fw-medium">Género <span class="text-danger">*</span></label>
                        <select class="form-select @error('genero') is-invalid @enderror" id="genero" name="genero" required>
                            <option value="" {{ !old('genero', $usuario->genero ?? '') ? 'selected' : '' }}>Seleccionar...</option>
                            <option value="M" {{ old('genero', $usuario->genero ?? '') === 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('genero', $usuario->genero ?? '') === 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        @error('genero')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if(!$usuario)
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-medium">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Mínimo 6 caracteres" 
                               required minlength="6" autocomplete="new-password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" 
                               required minlength="6" autocomplete="new-password">
                        @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @else
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-medium">Nueva Contraseña (opcional)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Dejar vacío para no cambiar" 
                               minlength="6" autocomplete="new-password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-medium">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" placeholder="Confirmar" 
                               minlength="6" autocomplete="new-password">
                        @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    
                    <div class="col-md-6">
                        <label for="idRol" class="form-label fw-medium">Rol <span class="text-danger">*</span></label>
                        <select class="form-select @error('idRol') is-invalid @enderror" id="idRol" name="idRol" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id }}" {{ (old('idRol', $usuario->idRol ?? '') == $rol->id) ? 'selected' : '' }}>
                                {{ $rol->nombreRol }}
                            </option>
                            @endforeach
                        </select>
                        @error('idRol')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="estado" class="form-label fw-medium">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="A" {{ (old('estado', $usuario->estado ?? 'A') === 'A') ? 'selected' : '' }}>Activo</option>
                            <option value="I" {{ (old('estado', $usuario->estado ?? '') === 'I') ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-poke-primary">
                        <i class="fas fa-{{ $usuario ? 'save' : 'plus' }} me-2"></i>
                        {{ $usuario ? 'Actualizar' : 'Crear' }} Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
    
    // Password confirmation validation
    const password = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    
    if (password && confirm) {
        const validateMatch = () => {
            if (password.value !== confirm.value) {
                confirm.classList.add('is-invalid');
                confirm.classList.remove('is-valid');
            } else if (confirm.value) {
                confirm.classList.remove('is-invalid');
                confirm.classList.add('is-valid');
            }
        };
        
        password.addEventListener('input', validateMatch);
        confirm.addEventListener('input', validateMatch);
    }
});
</script>
@endsection