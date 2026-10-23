@extends('layouts.admin')

@section('title', 'Pedidos')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-shopping-bag me-2 text-warning"></i>Pedidos</h1>
            <p class="text-muted mb-0">Consulta los pedidos realizados en la tienda</p>
        </div>
        <span class="badge bg-primary fs-6">{{ count($pedidos) }} pedidos</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0">
            <h5 class="mb-0">Listado de pedidos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-end">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                        <tr>
                            <td class="fw-semibold">#{{ $pedido->id }}</td>
                            <td>
                                <span class="d-block">{{ trim(($pedido->cliente_nombre ?? '') . ' ' . ($pedido->cliente_apellido ?? '')) ?: 'Cliente no disponible' }}</span>
                                <small class="text-muted">{{ $pedido->cliente_correo ?? '' }}</small>
                            </td>
                            <td>{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : '—' }}</td>
                            <td class="fw-semibold">${{ number_format($pedido->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $pedido->getEstadoBadge() }}">{{ ucfirst($pedido->estado) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $pedido->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Ver pedido {{ $pedido->id }}">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @if(empty($pedidos))
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Aún no hay pedidos registrados.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
