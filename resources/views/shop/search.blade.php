@extends('layouts.app')

@section('title')
{{ $term !== '' ? 'Resultados para ' . $term : 'Buscar Pokémon' }}
@endsection

@section('content')
<main class="search-results-page">
    <section class="search-results-hero">
        <div class="container">
            <span class="search-results-eyebrow">ENCUENTRA TU PRÓXIMO COMPAÑERO</span>
            <h1>Resultados de búsqueda</h1>
            @if($term !== '')
            <p>Mostrando coincidencias para <strong>“{{ $term }}”</strong></p>
            @else
            <p>Escribe el nombre de un Pokémon para explorar la tienda.</p>
            @endif
        </div>
    </section>

    <section class="container search-results-content">
        @if(empty($productos['data']))
        <div class="search-empty-state">
            <span class="search-empty-icon"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></span>
            <h2>{{ $term !== '' ? 'No encontramos resultados' : '¿A quién estás buscando?' }}</h2>
            <p>{{ $term !== '' ? 'Prueba con otro nombre o revisa la escritura.' : 'Usa el buscador de arriba y te mostraremos Pokémon relacionados.' }}</p>
            <a href="{{ route('shop.categories') }}" class="btn btn-poke-primary">Explorar categorías</a>
        </div>
        @else
        <div class="search-results-summary">
            <h2>{{ $productos['total'] }} Pokémon encontrados</h2>
            <span>Ordenados por más recientes</span>
        </div>
        <div class="row g-4">
            @foreach($productos['data'] as $producto)
            @include('shop.partials.product-card', ['producto' => $producto])
            @endforeach
        </div>
        @endif
    </section>
</main>
@endsection
