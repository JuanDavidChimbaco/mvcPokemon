@extends('layouts.admin')

@section('title', 'Compra #' . $compra->id)

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-file-invoice-dollar me-2 text-warning"></i>Compra #{{ $compra->id }}</h1>
            <p class="text-muted mb-0">Detalle de los productos ingresados</p>
        </div>
        <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Volver a compras</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><strong>Registrada por:</strong><br>{{ $usuario ? $usuario->nombre_completo : 'Usuario no disponible' }}</div>
                <div class="col-md-4"><strong>Fecha:</strong><br>{{ $compra->created_at ? $compra->created_at->format('d/m/Y H:i') : '—' }}</div>
                <div class="col-md-4"><strong>Estado:</strong><br>{{ ucfirst($compra->estado) }}</div>
                <div class="col-12"><strong>Notas:</strong><br>{{ $compra->notas ?: 'Sin notas' }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0"><h5 class="mb-0">Productos de la compra</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Producto</th><th>Precio unitario</th><th>Cantidad</th><th class="text-end">Subtotal</th></tr></thead>
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
                        <tr><td colspan="4" class="text-center text-muted py-4">Esta compra no tiene productos asociados.</td></tr>
                        @endif
                    </tbody>
                    <tfoot><tr><th colspan="3" class="text-end">Total</th><th class="text-end">${{ number_format($compra->total, 2) }}</th></tr></tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
