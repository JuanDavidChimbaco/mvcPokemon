@extends('layouts.admin')

@section('title', 'Pedido #' . $pedido->id)

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-receipt me-2 text-warning"></i>Pedido #{{ $pedido->id }}</h1>
            <p class="text-muted mb-0">Detalle y datos de envío</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver a pedidos
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0"><h5 class="mb-0">Información del cliente</h5></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nombre:</strong> {{ $usuario ? $usuario->nombre_completo : 'Cliente no disponible' }}</p>
                    <p class="mb-2"><strong>Correo:</strong> {{ $usuario->correo ?? '—' }}</p>
                    <p class="mb-0"><strong>Dirección de envío:</strong> {{ $pedido->direccion_envio ?: 'No especificada' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0"><h5 class="mb-0">Resumen del pedido</h5></div>
                <div class="card-body">
                    <p class="mb-2"><strong>Fecha:</strong> {{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : '—' }}</p>
                    <p class="mb-2"><strong>Estado:</strong> <span class="badge bg-{{ $pedido->getEstadoBadge() }}">{{ ucfirst($pedido->estado) }}</span></p>
                    <p class="mb-0"><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
                    <form action="{{ route('admin.orders.update', $pedido->id) }}" method="POST" class="mt-3">
                        <input type="hidden" name="_token" value="{{ session()->token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <label for="order-status" class="form-label">Actualizar estado</label>
                        <div class="input-group">
                            <select id="order-status" name="estado" class="form-select" required>
                                <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmado" {{ $pedido->estado === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="enviado" {{ $pedido->estado === 'enviado' ? 'selected' : '' }}>Enviado</option>
                                <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            <button class="btn btn-poke-primary" type="submit">Guardar</button>
                        </div>
                        <small class="text-muted d-block mt-2">Al cancelar se repone el inventario; al reactivar el pedido se verifica y descuenta nuevamente.</small>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0"><h5 class="mb-0">Productos del pedido</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Producto</th><th>Precio unitario</th><th>Cantidad</th><th class="text-end">Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto_nombre ?? 'Producto no disponible' }}</td>
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td class="text-end">${{ number_format($detalle->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                        @if(empty($detalles))
                        <tr><td colspan="4" class="text-center text-muted py-4">Este pedido no tiene productos asociados.</td></tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr><th colspan="3" class="text-end">Total</th><th class="text-end">${{ number_format($pedido->total, 2) }}</th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
