@extends('layouts.app')

@section('title', 'Error del Servidor - 500')

@section('body-class', 'error-page')

@section('content')
<div class="error-container">
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle fa-4x"></i>
        </div>
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Error Interno del Servidor</h2>
        <p class="error-message">
            Algo salió mal en nuestro servidor.<br>
            Nuestro equipo de desarrolladores (tipo Psíquico) ya ha sido notificado.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-poke-primary btn-lg">
                <i class="fas fa-home me-2"></i>Volver a la Tienda
            </a>
            <button class="btn btn-outline-poke btn-lg" onclick="location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Reintentar
            </button>
        </div>
        <div class="error-support mt-4">
            <small class="text-muted">
                <i class="fas fa-life-ring me-1"></i> 
                Si el problema persiste, contacta al equipo de soporte.
            </small>
        </div>
    </div>
    
    <div class="error-background">
        <div class="floating-pokeball"></div>
        <div class="floating-pokeball delay-1"></div>
        <div class="floating-pokeball delay-2"></div>
    </div>
</div>
@endsection