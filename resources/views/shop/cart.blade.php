@extends('layouts.app')

@section('title', 'Carrito de Compras - PokéTienda')

@section('styles')
@endsection

@section('content')
<section class="cart-section py-5">
    <div class="container">
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0"><i class="fas fa-shopping-bag me-2 text-warning"></i>Mi Carrito</h1>
                    <span class="text-muted" data-cart-quantity-count>{{ $summary['quantity_count'] }} unidad{{ $summary['quantity_count'] !== 1 ? 'es' : '' }}</span>
                </div>
                
                @if(empty($items))
                <div class="cart-empty text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h3>Tu carrito está vacío</h3>
                    <p class="text-muted">Parece que aún no has agregado ningún Pokémon</p>
                    <a href="{{ route('home') }}" class="btn btn-poke-primary mt-3">
                        <i class="fas fa-search me-2"></i>Ir a Comprar
                    </a>
                </div>
                @else
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Imagen</th>
                                        <th>Producto</th>
                                        <th style="width: 150px;">Precio</th>
                                        <th style="width: 150px;">Cantidad</th>
                                        <th style="width: 150px;">Subtotal</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr class="cart-row" data-id="{{ $item['producto_id'] }}" data-price="{{ $item['precio'] }}" data-stock="{{ $item['stock'] }}">
                                        <td>
                                            <a href="{{ route('shop.product', $item['producto_id']) }}" class="text-decoration-none">
                                                <img src="{{ product_image_url($item['imagen'] ?? null) }}" data-pokemon-name="{{ $item['nombre'] }}" alt="{{ $item['nombre'] }}" class="cart-item-image">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('shop.product', $item['producto_id']) }}" class="text-decoration-none text-dark fw-medium">
                                                {{ $item['nombre'] }}
                                            </a>
                                            @if(isset($item['categoria']))
                                            <br><small class="text-muted">{{ $item['categoria'] }}</small>
                                            @endif
                                        </td>
                                        <td class="text-nowrap" data-cart-item-price>${{ number_format($item['precio'], 2) }}</td>
                                        <td>
                                            <div class="input-group input-group-sm quantity-control" style="max-width: 130px;">
                                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="decrease" aria-label="Disminuir" {{ $item['cantidad'] <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center qty-input" value="{{ $item['cantidad'] }}" min="1" max="{{ $item['stock'] }}" readonly aria-live="polite">
                                                <button type="button" class="btn btn-outline-secondary qty-btn" data-action="increase" aria-label="Aumentar" {{ $item['cantidad'] >= $item['stock'] || !$item['available'] ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            @if(!$item['available'])
                                            <small class="d-block text-danger mt-1">Stock disponible: {{ $item['stock'] }}. Ajusta la cantidad.</small>
                                            @endif
                                        </td>
                                        <td class="fw-bold subtotal-cell" data-cart-line-subtotal>${{ number_format($item['subtotal'], 2) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm btn-remove" data-id="{{ $item['id'] }}" aria-label="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Cart Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Seguir Comprando
                    </a>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger" id="clearCart">
                            <i class="fas fa-trash me-2"></i>Limpiar Carrito
                        </button>
                        <a href="{{ route('shop.checkout') }}" data-cart-checkout class="btn btn-poke-primary btn-lg {{ !$canCheckout ? 'disabled' : '' }}" {{ !$canCheckout ? 'aria-disabled=true tabindex=-1' : '' }}>
                            <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                        </a>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="summary-card sticky-top" style="top: 100px;">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Resumen del Pedido</h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-row d-flex justify-content-between mb-2">
                                <span data-cart-subtotal-label>Subtotal ({{ $summary['quantity_count'] }} unidad{{ $summary['quantity_count'] !== 1 ? 'es' : '' }})</span>
                                <span class="fw-medium" data-cart-subtotal>${{ number_format($summary['subtotal'], 2) }}</span>
                            </div>
                            <div class="summary-row d-flex justify-content-between mb-2">
                                <span>Envío</span>
                                <span class="fw-medium text-success">GRATIS</span>
                            </div>
                            <div class="summary-row d-flex justify-content-between mb-2">
                                <span>Impuestos (IVA 19%)</span>
                                <span class="fw-medium" data-cart-tax>${{ number_format($summary['tax'], 2) }}</span>
                            </div>
                            <hr>
                            <div class="summary-row d-flex justify-content-between fs-5 fw-bold">
                                <span>Total</span>
                                <span class="text-poke-primary" data-cart-total>${{ number_format($summary['total'], 2) }}</span>
                            </div>
                            
                            <div class="mt-4 p-3 bg-light rounded">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-info-circle me-1"></i>El total incluye IVA del 19 %. El envío es gratis.
                                </small>
                                <a href="{{ route('shop.checkout') }}" data-cart-checkout class="btn btn-poke-primary w-100 {{ !$canCheckout ? 'disabled' : '' }}" {{ !$canCheckout ? 'aria-disabled=true tabindex=-1' : '' }}>
                                    <i class="fas fa-lock me-2"></i>Finalizar Compra
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
