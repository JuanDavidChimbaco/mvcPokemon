@extends('layouts.app')

@section('title', 'Tipos de Pokémon - PokéTienda')

@section('styles')
@endsection

@section('content')
<!-- Categories Hero -->
<section class="category-hero" style="background: linear-gradient(135deg, #1A1A2E 0%, #16213E 100%)">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold text-white mb-2">Tipos de Pokémon</h1>
                <p class="lead text-white-50 mb-0">Explora todos los tipos de Pokémon disponibles en nuestra tienda</p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <i class="fas fa-tags fa-5x text-warning opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="categories-section py-5">
    <div class="container">
        <div class="row g-4" id="categoriesGrid">
            @foreach($categorias as $categoria)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('shop.category', $categoria->id) }}" class="category-card text-decoration-none" style="background: {{ $categoria->getGradient() }}">
                    <div class="category-icon">
                        <img src="{{ $categoria->getIconUrl() }}" alt="{{ $categoria->nombreCat }}" class="img-fluid">
                    </div>
                    <div class="category-info">
                        <h5 class="category-name">{{ $categoria->nombreCat }}</h5>
                        <span class="category-count">{{ $categoria->productosCount() }} Pokémon</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
@if(isset($productos) && !empty($productos['data']))
<section class="featured-section py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Productos Destacados</h2>
                <p class="text-muted">Los más populares entre los entrenadores</p>
            </div>
        </div>
        
        <div class="row g-4" id="featuredProducts">
            @foreach($productos['data'] as $producto)
            @include('shop.partials.product-card', ['producto' => $producto])
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('shop.categories') }}" class="btn btn-outline-poke btn-lg">
                <i class="fas fa-th-large me-2"></i>Ver Todos los Pokémon
            </a>
        </div>
    </div>
</section>
@endif
@endsection