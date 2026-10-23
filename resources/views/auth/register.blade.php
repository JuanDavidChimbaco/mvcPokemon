@extends('layouts.app')

@section('title', 'Registro')

@section('body-class', 'auth-page register-page')

@section('styles')
@vite('resources/scss/auth.scss')
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">
                <img src="@asset('img/Squirtle.png')" alt="PokéTienda" width="64" height="64">
            </div>
            <h1 class="auth-title">PokéTienda</h1>
            <p class="auth-subtitle">Crea tu cuenta de entrenador</p>
        </div>
        
        <form action="{{ route('auth.register.post') }}" method="POST" class="auth-form">
            <input type="hidden" name="_token" value="{{ session()->token() }}">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="tipoDoc" name="tipoDoc" required>
                            <option value="CC" {{ old('tipoDoc') === 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="TI" {{ old('tipoDoc') === 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                            <option value="CE" {{ old('tipoDoc') === 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PA" {{ old('tipoDoc') === 'PA' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        <label for="tipoDoc"><i class="fas fa-id-card me-2"></i>Tipo de Documento</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="number" class="form-control" 
                               id="identificacion" name="identificacion" placeholder="123456789" 
                               value="{{ old('identificacion') }}" required min="1">
                        <label for="identificacion"><i class="fas fa-hashtag me-2"></i>Número de Identificación</label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" 
                               id="nombre" name="nombre" placeholder="Juan" 
                               value="{{ old('nombre') }}" required minlength="2" maxlength="50">
                        <label for="nombre"><i class="fas fa-user me-2"></i>Nombre</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" 
                               id="apellido" name="apellido" placeholder="Pérez" 
                               value="{{ old('apellido') }}" required minlength="2" maxlength="50">
                        <label for="apellido"><i class="fas fa-user me-2"></i>Apellido</label>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-floating">
                    <input type="email" class="form-control" 
                           id="correo" name="correo" placeholder="correo@ejemplo.com" 
                           value="{{ old('correo') }}" required autocomplete="email">
                    <label for="correo"><i class="fas fa-envelope me-2"></i>Correo Electrónico</label>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" 
                           id="direccion" name="direccion" placeholder="Calle 123 #45-67" 
                           value="{{ old('direccion') }}" required maxlength="255">
                    <label for="direccion"><i class="fas fa-map-marker-alt me-2"></i>Dirección</label>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="tel" class="form-control" 
                               id="telefono" name="telefono" placeholder="3001234567" 
                               value="{{ old('telefono') }}" required maxlength="20">
                        <label for="telefono"><i class="fas fa-phone me-2"></i>Teléfono</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="genero" name="genero" required>
                            <option value="">Seleccionar...</option>
                            <option value="M" {{ old('genero') === 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('genero') === 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                        <label for="genero"><i class="fas fa-venus-mars me-2"></i>Género</label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="password" class="form-control" 
                               id="password" name="password" placeholder="Contraseña" 
                               required minlength="6" autocomplete="new-password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Contraseña</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-floating">
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" placeholder="Confirmar Contraseña" 
                               required minlength="6" autocomplete="new-password">
                        <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirmar Contraseña</label>
                    </div>
                </div>
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    Acepto los <a href="#" class="text-poke-primary">Términos y Condiciones</a> y la <a href="#" class="text-poke-primary">Política de Privacidad</a>
                </label>
            </div>
            
            <button type="submit" class="btn btn-poke-primary w-100 auth-btn">
                <i class="fas fa-user-plus me-2"></i>Crear Cuenta
            </button>
        </form>
        
        <div class="auth-divider">
            <span>¿Ya tienes cuenta?</span>
        </div>
        
        <div class="auth-links">
            <a href="{{ route('auth.login') }}" class="btn btn-outline-poke w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </a>
        </div>
    </div>
    
    <div class="auth-background">
        <div class="floating-pokeball"></div>
        <div class="floating-pokeball delay-1"></div>
        <div class="floating-pokeball delay-2"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Password strength indicator
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');

passwordInput.addEventListener('input', function() {
    const strength = calculateStrength(this.value);
    updateStrengthIndicator(strength);
    
    // Validate confirmation match
    if (confirmInput.value) {
        validateMatch();
    }
});

confirmInput.addEventListener('input', validateMatch);

function validateMatch() {
    if (passwordInput.value === confirmInput.value) {
        confirmInput.classList.remove('is-invalid');
        confirmInput.classList.add('is-valid');
    } else {
        confirmInput.classList.remove('is-valid');
        confirmInput.classList.add('is-invalid');
    }
}

function calculateStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

function updateStrengthIndicator(score) {
    let indicator = document.querySelector('.password-strength');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'password-strength mt-1';
        passwordInput.parentNode.appendChild(indicator);
    }
    
    const levels = ['Muy débil', 'Débil', 'Media', 'Fuerte', 'Muy fuerte'];
    const colors = ['danger', 'warning', 'info', 'success', 'success'];
    
    indicator.innerHTML = `<small class="text-${colors[score-1] || 'muted'}">
        <i class="fas fa-shield-alt me-1"></i>Fortaleza: ${levels[score-1] || 'Muy débil'}
    </small>`;
}
</script>
@endsection