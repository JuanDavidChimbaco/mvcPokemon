@extends('layouts.app')

@section('title', $producto->nombrePro . ' - PokéTienda')

@section('styles')
@endsection

@section('content')
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="py-3 bg-light border-bottom">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.categories') }}">Tipos</a></li>
            @if($producto->categoria_nombre)
            <li class="breadcrumb-item"><a href="{{ route('shop.category', $producto->categoria) }}">{{ $producto->categoria_nombre }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombrePro }}</li>
        </ol>
    </div>
</nav>

<!-- Product Detail -->
<section class="product-detail py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Gallery -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <div class="main-image mb-3">
                        <img src="{{ product_image_url($producto->urlFoto) }}" data-pokemon-name="{{ $producto->nombrePro }}" alt="{{ $producto->nombrePro }}" class="img-fluid rounded" id="mainImage">
                    </div>
                    <div class="thumbnails d-flex gap-2">
                        <button class="thumb active" data-img="{{ product_image_url($producto->urlFoto) }}" data-pokemon-name="{{ $producto->nombrePro }}">
                            <img src="{{ product_image_url($producto->urlFoto) }}" data-pokemon-name="{{ $producto->nombrePro }}" alt="{{ $producto->nombrePro }}" class="img-fluid rounded">
                        </button>
                        <!-- Additional images would go here -->
                    </div>
                </div>
            </div>
            
            <!-- Info -->
            <div class="col-lg-6">
                <header class="product-header mb-4">
                    @if($producto->categoria_nombre)
                    <span class="badge mb-2 fs-6 px-3 py-2" style="background: {{ $producto->categoria_color ?? '#6c757d' }}">
                        <i class="fas fa-tag me-1"></i>{{ $producto->categoria_nombre }}
                    </span>
                    @endif
                    
                    <h1 class="display-5 fw-bold">{{ $producto->nombrePro }}</h1>
                    
                    <div class="product-meta mt-3">
                        <span class="text-muted">SKU: #{{ $producto->id }}</span>
                        @if($producto->cantidadPro > 0)
                        <span class="text-success ms-3"><i class="fas fa-check-circle me-1"></i>{{ $producto->cantidadPro }} disponibles</span>
                        @else
                        <span class="text-danger ms-3"><i class="fas fa-times-circle me-1"></i>Agotado</span>
                        @endif
                    </div>
                </header>
                
                <div class="product-price-display mb-4">
                    <span class="price-main">${{ number_format($producto->precioPro, 2) }}</span>
                    @if($producto->precioOriginal && $producto->precioOriginal > $producto->precioPro)
                    <span class="price-original">${{ number_format($producto->precioOriginal, 2) }}</span>
                    <span class="price-discount badge bg-danger ms-2">-{{ round((1 - $producto->precioPro / $producto->precioOriginal) * 100) }}%</span>
                    @endif
                </div>
                
                <div class="product-description mb-4">
                    <p class="lead">{{ $producto->descripcion ?? 'Este increíble Pokémon está listo para unirse a tu equipo. ¡Entrena, evoluciona y conviértete en el mejor entrenador!' }}</p>
                </div>
                
                <!-- Quantity Selector -->
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-medium">Cantidad</label>
                    <div class="quantity-stepper product-quantity-control">
                        <button type="button" class="quantity-stepper-btn qty-btn" data-action="decrease" id="qtyMinus" aria-label="Disminuir">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="quantity-stepper-input qty-input" id="quantity" name="quantity" value="1" min="1" max="{{ $producto->cantidadPro }}" readonly aria-live="polite">
                        <button type="button" class="quantity-stepper-btn qty-btn" data-action="increase" id="qtyPlus" aria-label="Aumentar" {{ $producto->cantidadPro <= 1 ? 'disabled' : '' }}>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="form-text">Máximo {{ $producto->cantidadPro }} unidad{{ $producto->cantidadPro !== 1 ? 'es' : '' }} disponible{{ $producto->cantidadPro !== 1 ? 's' : '' }}</div>
                </div>
                
                <!-- Add to Cart -->
                <div class="d-grid gap-3 mb-4">
                    <button type="button" class="btn btn-poke-primary btn-lg btn-add-to-cart" data-id="{{ $producto->id }}" {{ $producto->cantidadPro == 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-bag me-2"></i>
                        {{ $producto->cantidadPro > 0 ? 'Agregar al Carrito' : 'Agotado - Notificarme' }}
                    </button>
                    <button type="button" class="btn {{ $isFavorite ? 'btn-danger active' : 'btn-outline-secondary' }} btn-wishlist" data-id="{{ $producto->id }}" data-favorited="{{ $isFavorite ? 'true' : 'false' }}" aria-pressed="{{ $isFavorite ? 'true' : 'false' }}">
                        <i class="fas fa-heart me-2"></i>{{ $isFavorite ? 'En Favoritos' : 'Añadir a Favoritos' }}
                    </button>
                </div>
                
                <!-- Features -->
                <div class="product-features">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-shipping-fast fa-2x text-warning mb-2"></i>
                                <h6>Envío Rápido</h6>
                                <small class="text-muted">24-48h en tu ciudad</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                <h6>Garantía</h6>
                                <small class="text-muted">Autenticidad certificada</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-undo fa-2x text-warning mb-2"></i>
                                <h6>Devoluciones</h6>
                                <small class="text-muted">30 días sin preguntas</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-headset fa-2x text-warning mb-2"></i>
                                <h6>Soporte 24/7</h6>
                                <small class="text-muted">Equipo experto disponible</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if(isset($relacionados) && count($relacionados) > 0)
        <section class="related-section py-5 bg-light">
            <div class="container">
                <h2 class="section-title text-center mb-5">También te puede interesar</h2>
                <div class="row g-4">
                    @foreach($relacionados as $relacionado)
                    @include('shop.partials.product-card', ['producto' => $relacionado])
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div>
</section>
@endsection
