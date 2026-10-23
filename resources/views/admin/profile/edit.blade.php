@extends('layouts.admin')

@section('title', 'Mi perfil')

@section('content')
<div class="admin-content">
    <div class="mb-4">
        <h1 class="h3 mb-1"><i class="fas fa-user-cog me-2 text-warning"></i>Mi perfil</h1>
        <p class="text-muted mb-0">Actualiza tus datos personales y, si quieres, tu contraseña.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                <input type="hidden" name="_token" value="{{ session()->token() }}">
                <input type="hidden" name="_method" value="PUT">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $usuario->nombre) }}" maxlength="50" required>
                    </div>
                    <div class="col-md-6">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" value="{{ old('apellido', $usuario->apellido) }}" maxlength="50" required>
                    </div>
                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="{{ old('correo', $usuario->correo) }}" maxlength="100" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}" maxlength="20">
                    </div>
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $usuario->direccion) }}" maxlength="255">
                    </div>

                    <div class="col-12"><hr><h2 class="h6 mb-0">Cambiar contraseña <small class="text-muted">(opcional)</small></h2></div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Nueva contraseña</label>
                        <input type="password" id="password" name="password" class="form-control" minlength="6" autocomplete="new-password">
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" minlength="6" autocomplete="new-password">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-poke-primary"><i class="fas fa-save me-2"></i>Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
