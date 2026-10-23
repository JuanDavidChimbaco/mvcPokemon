<div class="col-6 col-md-4 col-lg-3">
    <article class="product-card h-100" data-product-id="{{ $producto->id }}">
        <div class="product-image">
            <a href="{{ route('shop.product', $producto->id) }}">
                <img src="{{ product_image_url($producto->urlFoto) }}" data-pokemon-name="{{ $producto->nombrePro }}" alt="{{ $producto->nombrePro }}" class="img-fluid">
            </a>
            <div class="product-badges">
                @if($producto->cantidadPro <= 5 && $producto->cantidadPro > 0)
                <span class="badge bg-warning text-dark">Pocas unidades</span>
                @endif
                @if($producto->cantidadPro == 0)
                <span class="badge bg-danger">Agotado</span>
                @endif
            </div>
            <div class="product-quick-add">
                <button type="button" class="btn btn-add-cart" data-id="{{ $producto->id }}" {{ $producto->cantidadPro == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-shopping-bag"></i>
                </button>
            </div>
        </div>
        
        <div class="product-info">
            @if($producto->categoria_nombre)
            <span class="product-category" style="background: {{ $producto->categoria_color ?? '#6c757d' }}">
                {{ $producto->categoria_nombre }}
            </span>
            @endif
            
            <h6 class="product-name">
                <a href="{{ route('shop.product', $producto->id) }}" class="text-decoration-none text-dark">
                    {{ $producto->nombrePro }}
                </a>
            </h6>
            
            <div class="product-price">${{ number_format($producto->precioPro, 2) }}</div>
            
            <div class="product-stock">
                @if($producto->cantidadPro > 0)
                <small class="text-success"><i class="fas fa-check-circle me-1"></i>{{ $producto->cantidadPro }} disponibles</small>
                @else
                <small class="text-danger"><i class="fas fa-times-circle me-1"></i>Agotado</small>
                @endif
            </div>
        </div>
        
        <div class="product-actions">
            <a href="{{ route('shop.product', $producto->id) }}" class="btn btn-outline-poke w-100">
                <i class="fas fa-eye me-2"></i>Ver Detalles
            </a>
            <button type="button"
                    class="btn btn-sm {{ in_array((string)$producto->id, session()->get('shop_wishlist', []), true) ? 'btn-danger active' : 'btn-outline-secondary' }} btn-wishlist"
                    data-id="{{ $producto->id }}"
                    data-product-name="{{ $producto->nombrePro }}"
                    data-favorited="{{ in_array((string)$producto->id, session()->get('shop_wishlist', []), true) ? 'true' : 'false' }}"
                    aria-pressed="{{ in_array((string)$producto->id, session()->get('shop_wishlist', []), true) ? 'true' : 'false' }}"
                    aria-label="Alternar favorito para {{ $producto->nombrePro }}">
                <i class="fas fa-heart" aria-hidden="true"></i>
            </button>
        </div>
    </article>
</div>