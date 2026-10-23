@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('body-class', 'auth-page')

@section('content')
<main class="login-page">
    <a class="login-back-link" href="{{ route('home') }}">
        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
        Volver a la tienda
    </a>

    <section class="login-shell" aria-labelledby="login-title">
        <div class="login-story">
            <a class="login-brand" href="{{ route('home') }}">
                <img src="@asset('img/Squirtle.png')" alt="" width="48" height="48">
                <span>Poké<span>Tienda</span></span>
            </a>
            <div class="login-story-content">
                <span class="login-eyebrow">TU AVENTURA COMIENZA AQUÍ</span>
                <h1>Vuelve a tu mundo Pokémon.</h1>
                <p>Entra a tu cuenta, descubre nuevos compañeros y continúa tu colección.</p>
                <div class="login-feature">
                    <span class="login-feature-icon"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i></span>
                    <span><strong>Tu colección, siempre cerca</strong><small>Encuentra tus favoritos en un solo lugar.</small></span>
                </div>
            </div>
            <span class="login-story-orbit login-story-orbit-one" aria-hidden="true"></span>
            <span class="login-story-orbit login-story-orbit-two" aria-hidden="true"></span>
            <small class="login-story-footnote">Una tienda hecha por fans, para fans.</small>
        </div>

        <div class="login-form-panel">
            <div class="login-form-heading">
                <span class="login-welcome">¡Qué bueno verte!</span>
                <h2 id="login-title">Iniciar sesión</h2>
                <p>Ingresa tus datos para continuar.</p>
            </div>

            <form action="{{ route('auth.login.post') }}" method="POST" class="auth-form login-form">
                <input type="hidden" name="_token" value="{{ session()->token() }}">

                <label for="correo">Correo electrónico</label>
                <div class="login-input-wrap">
                    <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                    <input type="email" id="correo" name="correo" placeholder="entrenador@ejemplo.com"
                           value="{{ old('correo') }}" required autocomplete="email">
                </div>

                <div class="login-password-heading">
                    <label for="password">Contraseña</label>
                </div>
                <div class="login-input-wrap">
                    <i class="fa-solid fa-lock" aria-hidden="true"></i>
                    <input type="password" id="password" name="password" placeholder="Tu contraseña"
                           required autocomplete="current-password">
                    <button type="button" class="login-password-toggle" aria-label="Mostrar contraseña"
                            aria-pressed="false">
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>

                <label class="login-remember">
                    <input type="checkbox" name="remember" value="1">
                    <span>Recordarme en este dispositivo</span>
                </label>

                <button type="submit" class="login-submit">
                    Entrar a mi cuenta
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            <p class="login-register-prompt">
                ¿Aún no tienes cuenta?
                <a href="{{ route('auth.register') }}">Crear una cuenta</a>
            </p>
            <p class="login-secure-note">
                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                Tus datos están protegidos.
            </p>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
document.querySelector('.login-password-toggle')?.addEventListener('click', function () {
    const password = document.getElementById('password');
    const isVisible = password.type === 'text';
    password.type = isVisible ? 'password' : 'text';
    this.setAttribute('aria-pressed', String(!isVisible));
    this.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
    this.innerHTML = `<i class="fa-regular ${isVisible ? 'fa-eye' : 'fa-eye-slash'}" aria-hidden="true"></i>`;
});
</script>
@endsection
