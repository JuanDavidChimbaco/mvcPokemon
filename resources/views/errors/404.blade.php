@extends('layouts.app')

@section('title', 'Página No Encontrada - 404')

@section('body-class', 'error-page')

@section('content')
<div class="error-container">
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-search fa-4x"></i>
        </div>
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Página No Encontrada</h2>
        <p class="error-message">
            Lo sentimos, no pudimos encontrar la página que buscas.<br>
            Puede que haya sido movida, eliminada o nunca haya existido.
        </p>
        <div class="error-actions">
            <a href="{{ route('home') }}" class="btn btn-poke-primary btn-lg">
                <i class="fas fa-home me-2"></i>Volver a la Tienda
            </a>
            <a href="{{ route('shop.categories') }}" class="btn btn-outline-poke btn-lg">
                <i class="fas fa-tags me-2"></i>Explorar Tipos
            </a>
        </div>
        <div class="error-search mt-4">
            <form action="{{ route('shop.search') }}" method="GET" class="d-flex gap-2 justify-content-center">
                <input type="search" name="q" class="form-control" style="max-width: 300px;" placeholder="Buscar Pokémon...">
                <button type="submit" class="btn btn-poke-primary">
                    <i class="fas fa-search me-2"></i>Buscar
                </button>
            </form>
        </div>
        <div class="error-fun mt-4">
            <small class="text-muted">
                <i class="fas fa-paw me-1"></i> 
                Tal vez un <strong>Pikachu</strong> se la comió... ⚡
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