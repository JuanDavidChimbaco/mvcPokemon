@extends('layouts.app')

@section('title', 'PokéTienda - Tu Tienda Pokémon')

@section('styles')
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            @foreach($destacados as $index => $producto)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" @if($index === 0) aria-current="true" @endif aria-label="Pokémon destacado {{ $index + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($destacados as $index => $producto)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <div class="hero-slide">
                    <img src="{{ product_image_url($producto->urlFoto) }}" data-pokemon-name="{{ $producto->nombrePro }}" class="d-block w-100 hero-image" alt="{{ $producto->nombrePro }}">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8 text-center">
                                    <span class="hero-badge">{{ $producto->categoria_nombre ?? 'Destacado' }}</span>
                                    <h1 class="hero-title">{{ $producto->nombrePro }}</h1>
                                    <p class="hero-price">${{ number_format($producto->precioPro, 2) }}</p>
                                    <p class="hero-description">{{ $producto->descripcion ?? 'El mejor compañero para tu aventura Pokémon.' }}</p>
                                    <a href="{{ route('shop.product', $producto->id) }}" class="btn btn-hero btn-lg">
                                        <i class="fas fa-shopping-bag me-2"></i>Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title">Explorar por Tipo</h2>
                <p class="text-muted">Encuentra tu Pokémon favorito por su tipo elemental</p>
            </div>
        </div>
        
        <div class="row g-3" id="categoriesCarousel">
            @foreach($categorias as $categoria)
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('shop.category', $categoria->id) }}" class="category-card text-decoration-none" style="background: {{ $categoria->getGradient() }}">
                    <div class="category-icon">
                        <img src="{{ $categoria->getIconUrl() }}" alt="{{ $categoria->nombreCat }}" class="img-fluid">
                    </div>
                    <div class="category-info">
                        <h6 class="category-name">{{ $categoria->nombreCat }}</h6>
                        <span class="category-count">{{ $categoria->productosCount() }} Pokémon</span>
                    </div>
                </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-section py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Pokémon Destacados</h2>
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

<!-- Newsletter / Promo -->
<section class="promo-section py-5">
    <div class="container">
        <div class="promo-card">
            <div class="row g-0 align-items-center">
                <div class="col-md-6 promo-image">
                    <img src="@asset('img/singulares.png')" alt="Pokémon Singulares" class="img-fluid w-100 h-100 object-fit-cover">
                </div>
                <div class="col-md-6 promo-content">
                    <h3 class="promo-title">Pokémon Singulares y Legendarios</h3>
                    <p class="promo-text">Descubre Pokémon únicos, legendarios y míticos de edición limitada. ¡Atrapa los más raros antes de que se agoten!</p>
                    <a href="{{ route('shop.category', 1) }}" class="btn btn-poke-primary btn-lg">
                        <i class="fas fa-star me-2"></i>Ver Colección
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
