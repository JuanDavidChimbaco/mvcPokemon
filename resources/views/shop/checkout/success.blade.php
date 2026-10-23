@extends('layouts.app')

@section('title', 'Pedido Confirmado - PokéTienda')

@section('styles')
@endsection

@section('content')
<section class="success-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="success-card text-center">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold mb-3">¡Pedido Confirmado!</h1>
                    <p class="lead text-muted mb-4">
                        Gracias por tu compra, <strong>{{ $usuario->nombre_completo ?? 'Entrenador' }}</strong>.
                    </p>
                    
                    <div class="order-summary text-start mx-auto" style="max-width: 500px;">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Resumen del Pedido #{{ $pedido->id }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Fecha</div>
                                    <div class="col-6 text-end fw-medium">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Estado</div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-{{ $pedido->getEstadoBadge() }} fs-6">
                                            {{ ucfirst($pedido->estado) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6 text-muted">Productos</div>
                                    <div class="col-6 text-end fw-medium">{{ count($detalles) }} items</div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">Subtotal</div>
                                    <div class="col-6 text-end">${{ number_format($subtotal, 2) }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">IVA (19%)</div>
                                    <div class="col-6 text-end">${{ number_format($tax, 2) }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 text-muted">Envío</div>
                                    <div class="col-6 text-end text-success">GRATIS</div>
                                </div>
                                <hr>
                                <div class="row fw-bold fs-5">
                                    <div class="col-6">Total</div>
                                    <div class="col-6 text-end text-poke-primary">${{ number_format($pedido->total, 2) }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Envío a</h5>
                            </div>
                            <div class="card-body text-start">
                                <p class="mb-1 fw-medium">{{ $usuario->nombre_completo ?? 'Cliente' }}</p>
                                <p class="mb-1">{{ $pedido->direccion_envio }}</p>
                                <p class="mb-0 text-muted">{{ $usuario->telefono ?? '' }}</p>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Método de Pago</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $pedido->notas ?? 'Pendiente de confirmación' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('home') }}" class="btn btn-poke-primary btn-lg">
                                <i class="fas fa-home me-2"></i>Volver a la Tienda
                            </a>
                            <button class="btn btn-outline-poke btn-lg" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>Imprimir Comprobante
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection