@extends('layouts.app')

@section('title', 'Mis Favoritos - PokéTienda')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 mb-1"><i class="fas fa-heart text-danger me-2"></i>Mis Favoritos</h1>
                <p class="text-muted mb-0">Pokémon que guardaste para encontrar fácilmente.</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Seguir explorando</a>
        </div>

        @if(empty($productos))
        <div class="text-center py-5">
            <i class="far fa-heart fa-4x text-muted mb-3"></i>
            <h2 class="h4">Aún no tienes favoritos</h2>
            <p class="text-muted">Usa el corazón en cualquier producto para guardarlo aquí.</p>
            <a href="{{ route('home') }}" class="btn btn-poke-primary">Explorar Pokémon</a>
        </div>
        @else
        <div class="row g-4">
            @foreach($productos as $producto)
            @include('shop.partials.product-card', ['producto' => $producto])
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
