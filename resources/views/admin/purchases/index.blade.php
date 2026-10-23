@extends('layouts.admin')

@section('title', 'Compras')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Compras</h1>
            <p class="text-muted mb-0">Consulta las compras y entradas de inventario</p>
        </div>
        <span class="badge bg-primary fs-6">{{ count($compras) }} compras</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0"><h5 class="mb-0">Listado de compras</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Compra</th><th>Registrada por</th><th>Fecha</th><th>Total</th><th>Estado</th><th class="text-end">Detalle</th></tr>
                    </thead>
                    <tbody>
                        @foreach($compras as $compra)
                        <tr>
                            <td class="fw-semibold">#{{ $compra->id }}</td>
                            <td>
                                {{ trim(($compra->usuario_nombre ?? '') . ' ' . ($compra->usuario_apellido ?? '')) ?: 'Usuario no disponible' }}
                                <br><small class="text-muted">{{ $compra->usuario_correo ?? '' }}</small>
                            </td>
                            <td>{{ $compra->created_at ? $compra->created_at->format('d/m/Y H:i') : '—' }}</td>
                            <td class="fw-semibold">${{ number_format($compra->total, 2) }}</td>
                            <td><span class="badge {{ $compra->estado === 'recibida' ? 'bg-success' : ($compra->estado === 'cancelada' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($compra->estado) }}</span></td>
                            <td class="text-end"><a href="{{ route('admin.purchases.show', $compra->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye me-1"></i>Ver</a></td>
                        </tr>
                        @endforeach
                        @if(empty($compras))
                        <tr><td colspan="6" class="text-center text-muted py-5"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No hay compras registradas.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
